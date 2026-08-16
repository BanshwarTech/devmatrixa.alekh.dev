<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DnsLookupController extends Controller
{
    public function analyze(Request $request)
    {
        $domain = $this->normalizeDomain((string) $request->input('domain', ''));

        if (! $domain) {
            return response()->json(['error' => 'Enter a valid domain, e.g. example.com'], 422);
        }

        $records = [];

        $ipv4 = $this->lookup($domain, 'A', 'A — IPv4 Address', 'Maps the domain to one or more IPv4 addresses.', DNS_A, function ($rows) {
            return array_map(fn ($r) => ['value' => $r['ip'], 'meta' => "TTL {$r['ttl']}s"], $rows);
        });
        $records[] = $ipv4;

        $records[] = $this->lookup($domain, 'AAAA', 'AAAA — IPv6 Address', 'Maps the domain to one or more IPv6 addresses.', DNS_AAAA, function ($rows) {
            return array_map(fn ($r) => ['value' => $r['ipv6'], 'meta' => "TTL {$r['ttl']}s"], $rows);
        });

        $records[] = $this->lookup($domain, 'CNAME', 'CNAME — Canonical Name', 'Aliases this domain to another canonical hostname.', DNS_CNAME, function ($rows) {
            return array_map(fn ($r) => ['value' => rtrim($r['target'], '.')], $rows);
        });

        $records[] = $this->lookup($domain, 'MX', 'MX — Mail Exchange', 'Mail servers that accept email for this domain, by priority.', DNS_MX, function ($rows) {
            usort($rows, fn ($a, $b) => $a['pri'] <=> $b['pri']);
            return array_map(fn ($r) => ['value' => rtrim($r['target'], '.'), 'meta' => "priority {$r['pri']}"], $rows);
        });

        $records[] = $this->lookup($domain, 'TXT', 'TXT — Text Records', 'Free-form text records — used for SPF, DKIM, and domain verification.', DNS_TXT, function ($rows) {
            return array_map(function ($r) {
                $txt = $r['txt'] ?? '';
                return ['value' => $txt, 'tag' => $this->classifyTxt($txt)];
            }, $rows);
        });

        $records[] = $this->lookup($domain, 'NS', 'NS — Name Servers', "Authoritative name servers responsible for this domain's zone.", DNS_NS, function ($rows) {
            $values = array_map(fn ($r) => rtrim($r['target'], '.'), $rows);
            sort($values);
            return array_map(fn ($v) => ['value' => $v], $values);
        });

        $records[] = $this->lookup($domain, 'SOA', 'SOA — Start of Authority', 'Zone-level metadata — primary NS, admin contact, and refresh timers.', DNS_SOA, function ($rows) {
            if (empty($rows)) return [];
            $s = $rows[0];
            return [
                ['value' => rtrim($s['mname'], '.'), 'meta' => 'primary NS'],
                ['value' => rtrim($s['rname'], '.'), 'meta' => 'hostmaster'],
                ['value' => (string) $s['serial'], 'meta' => 'serial'],
                ['value' => "{$s['refresh']}s", 'meta' => 'refresh'],
                ['value' => "{$s['retry']}s", 'meta' => 'retry'],
                ['value' => "{$s['expire']}s", 'meta' => 'expire'],
                ['value' => "{$s['minimum-ttl']}s", 'meta' => 'min TTL'],
            ];
        });

        if (defined('DNS_CAA')) {
            $records[] = $this->lookup($domain, 'CAA', 'CAA — Certificate Authority', 'Restricts which certificate authorities may issue TLS certs for this domain.', DNS_CAA, function ($rows) {
                return array_map(fn ($r) => [
                    'value' => (string) ($r['value'] ?? ''),
                    'meta' => 'flags '.($r['flags'] ?? 0),
                    'tag' => $r['tag'] ?? '',
                ], $rows);
            });
        } else {
            $records[] = [
                'type' => 'CAA',
                'label' => 'CAA — Certificate Authority',
                'desc' => 'Restricts which certificate authorities may issue TLS certs for this domain.',
                'status' => 'error',
                'values' => [],
                'error' => 'Not supported on this server',
            ];
        }

        $records[] = $this->lookup("_dmarc.{$domain}", 'DMARC', 'DMARC — Email Policy', 'Email authentication policy published at _dmarc — controls spoofing handling.', DNS_TXT, function ($rows) {
            return array_map(fn ($r) => ['value' => $r['txt'] ?? '', 'tag' => 'DMARC'], $rows);
        }, overrideType: 'DMARC');

        $firstIp = $ipv4['values'][0]['value'] ?? null;
        if ($firstIp) {
            $host = @gethostbyaddr($firstIp);
            $records[] = [
                'type' => 'PTR',
                'label' => 'PTR — Reverse DNS',
                'desc' => "Reverse lookup of the primary IPv4 address ({$firstIp}).",
                'status' => ($host && $host !== $firstIp) ? 'found' : 'none',
                'values' => ($host && $host !== $firstIp) ? [['value' => rtrim($host, '.'), 'meta' => $firstIp]] : [],
            ];
        }

        $counts = [
            'total' => array_sum(array_map(fn ($r) => count($r['values']), $records)),
            'found' => count(array_filter($records, fn ($r) => $r['status'] === 'found')),
            'types' => count($records),
            'errors' => count(array_filter($records, fn ($r) => $r['status'] === 'error')),
        ];

        return response()->json(['domain' => $domain, 'records' => $records, 'counts' => $counts]);
    }

    private function lookup(string $host, string $type, string $label, string $desc, int $dnsType, callable $map, ?string $overrideType = null): array
    {
        $displayType = $overrideType ?? $type;

        try {
            $rows = @dns_get_record($host, $dnsType);

            if ($rows === false) {
                return ['type' => $displayType, 'label' => $label, 'desc' => $desc, 'status' => 'none', 'values' => []];
            }

            $values = array_values(array_filter($map($rows), fn ($v) => trim((string) ($v['value'] ?? '')) !== ''));

            return ['type' => $displayType, 'label' => $label, 'desc' => $desc, 'status' => count($values) ? 'found' : 'none', 'values' => $values];
        } catch (\Throwable $e) {
            return ['type' => $displayType, 'label' => $label, 'desc' => $desc, 'status' => 'error', 'values' => [], 'error' => 'Lookup failed'];
        }
    }

    private function classifyTxt(string $txt): ?string
    {
        $lc = strtolower($txt);

        if (str_starts_with($lc, 'v=spf1')) return 'SPF';
        if (str_starts_with($lc, 'v=dmarc1')) return 'DMARC';
        if (str_starts_with($lc, 'v=dkim1') || str_contains($lc, 'dkim')) return 'DKIM';
        if (preg_match('/(google-site-verification|facebook-domain-verification|ms=|apple-domain-verification|stripe-verification|atlassian-domain-verification|_globalsign)/', $lc)) {
            return 'Verification';
        }

        return null;
    }

    private function normalizeDomain(string $input): ?string
    {
        $s = strtolower(trim($input));
        if ($s === '') return null;

        if (str_contains($s, '://')) {
            $host = parse_url($s, PHP_URL_HOST);
            if (! $host) return null;
            $s = $host;
        } else {
            $s = explode('/', $s)[0];
            $s = explode('?', $s)[0];
            $s = explode('#', $s)[0];
            $s = explode(':', $s)[0];
        }

        $s = rtrim($s, '.');

        if (strlen($s) > 253) return null;
        if (! preg_match('/^(?=.{1,253}$)([a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z]{2,63}$/', $s)) return null;

        return $s;
    }
}
