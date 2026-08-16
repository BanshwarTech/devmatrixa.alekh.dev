<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Services\PageFetcher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RedirectChainController extends Controller
{
    private const MAX_HOPS = 15;

    private const HOP_TIMEOUT_SEC = 8;

    private const UA = 'Mozilla/5.0 (compatible; DevmatrixaBot/1.0)';

    public function analyze(Request $request)
    {
        $url = (string) $request->input('url', '');

        if (! PageFetcher::isValidUrl($url)) {
            return response()->json(['error' => 'Invalid URL'], 422);
        }

        $hops = [];
        $visited = [];
        $current = $url;
        $loopDetected = false;

        for ($i = 0; $i < self::MAX_HOPS; $i++) {
            $start = microtime(true) * 1000;
            $parsedCurrent = parse_url($current);
            $protocol = ($parsedCurrent['scheme'] ?? '').':';
            $host = ($parsedCurrent['host'] ?? '').(isset($parsedCurrent['port']) ? ':'.$parsedCurrent['port'] : '');

            try {
                $res = Http::withHeaders(['User-Agent' => self::UA, 'Accept' => 'text/html,application/xhtml+xml'])
                    ->timeout(self::HOP_TIMEOUT_SEC)
                    ->withOptions(['allow_redirects' => false])
                    ->get($current);
            } catch (\Throwable) {
                $hops[] = [
                    'index' => $i,
                    'url' => $current,
                    'status' => 0,
                    'statusText' => 'Network error / timeout',
                    'location' => null,
                    'timeMs' => (int) (microtime(true) * 1000 - $start),
                    'protocol' => $protocol,
                    'host' => $host,
                    'contentType' => null,
                    'server' => null,
                ];
                break;
            }

            $timeMs = (int) (microtime(true) * 1000 - $start);
            $location = $res->header('Location') ?: null;

            $hops[] = [
                'index' => $i,
                'url' => $current,
                'status' => $res->status(),
                'statusText' => $res->reason() ?: '',
                'location' => $location,
                'timeMs' => $timeMs,
                'protocol' => $protocol,
                'host' => $host,
                'contentType' => $res->header('Content-Type') ?: null,
                'server' => $res->header('Server') ?: null,
            ];

            if ($res->status() < 300 || $res->status() >= 400 || ! $location) {
                break;
            }

            $next = PageFetcher::absUrl($location, $current);
            if (in_array($next, $visited, true)) {
                $loopDetected = true;
                $parsedNext = parse_url($next);
                $hops[] = [
                    'index' => $i + 1,
                    'url' => $next,
                    'status' => -1,
                    'statusText' => 'Loop detected — already visited',
                    'location' => null,
                    'timeMs' => 0,
                    'protocol' => ($parsedNext['scheme'] ?? '').':',
                    'host' => ($parsedNext['host'] ?? '').(isset($parsedNext['port']) ? ':'.$parsedNext['port'] : ''),
                    'contentType' => null,
                    'server' => null,
                ];
                break;
            }
            $visited[] = $current;
            $current = $next;
        }

        $issues = [];
        $final = end($hops) ?: null;
        $first = $hops[0] ?? null;
        $redirectCount = count(array_filter($hops, fn ($h) => $h['status'] >= 300 && $h['status'] < 400));
        $totalTime = array_sum(array_column($hops, 'timeMs'));

        if ($loopDetected) {
            $issues[] = ['type' => 'error', 'msg' => 'Redirect loop detected — the chain returns to a URL already visited.', 'fix' => 'Fix the redirect rule causing the cycle. Loops permanently break access for users and bots.'];
        }
        if ($redirectCount >= 3) {
            $issues[] = ['type' => 'warning', 'msg' => "{$redirectCount} hops before final URL. Each hop adds latency and may dilute link equity.", 'fix' => 'Collapse intermediate redirects so the source URL points directly to the final destination.'];
        }
        for ($i = 1; $i < count($hops); $i++) {
            $prev = $hops[$i - 1];
            $curr = $hops[$i];
            if ($prev['protocol'] === 'https:' && $curr['protocol'] === 'http:') {
                $issues[] = ['type' => 'error', 'msg' => 'HTTPS → HTTP downgrade at hop '.($i + 1).'.', 'fix' => 'Never redirect from HTTPS to HTTP — this breaks HSTS and exposes traffic.'];
            }
            if ($prev['status'] === 302 || $prev['status'] === 307) {
                $issues[] = ['type' => 'info', 'msg' => "Hop {$i}: {$prev['status']} is a temporary redirect.", 'fix' => 'Use 301 (permanent) for SEO-friendly moves so link equity transfers and browsers cache the redirect.'];
            }
        }
        if ($final && $final['status'] >= 400) {
            $issues[] = ['type' => 'error', 'msg' => "Final URL returned {$final['status']} {$final['statusText']}.", 'fix' => 'Fix or remove the broken destination URL.'];
        }
        if ($first && $final && $first['host'] !== $final['host']) {
            $startNoWww = preg_replace('/^www\./', '', $first['host']);
            $finalNoWww = preg_replace('/^www\./', '', $final['host']);
            if ($startNoWww === $finalNoWww) {
                $issues[] = ['type' => 'info', 'msg' => "Host normalization: {$first['host']} → {$final['host']}.", 'fix' => 'This is expected — just verify your canonical host preference (www vs non-www) is consistent across the site.'];
            } else {
                $issues[] = ['type' => 'warning', 'msg' => "Cross-domain redirect: {$first['host']} → {$final['host']}.", 'fix' => 'Cross-domain hops lose some link equity. Make sure this is intentional.'];
            }
        }
        if (count($hops) === self::MAX_HOPS) {
            $issues[] = ['type' => 'error', 'msg' => 'Chain exceeded '.self::MAX_HOPS.' hops and was stopped.', 'fix' => 'Likely indicates a loop or excessively deep chain — investigate the redirect rules.'];
        }

        return response()->json([
            'input' => $url,
            'finalUrl' => $final ? $final['url'] : $url,
            'finalStatus' => $final ? $final['status'] : 0,
            'totalTimeMs' => $totalTime,
            'redirectCount' => $redirectCount,
            'hopCount' => count($hops),
            'loopDetected' => $loopDetected,
            'hops' => $hops,
            'issues' => $issues,
        ]);
    }
}
