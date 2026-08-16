<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Services\PageFetcher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SecurityHeadersController extends Controller
{
    public function analyze(Request $request)
    {
        $url = (string) $request->input('url', '');

        if (! PageFetcher::isValidUrl($url)) {
            return response()->json(['error' => 'Invalid URL'], 422);
        }

        try {
            $res = Http::withHeaders([
                'User-Agent' => PageFetcher::USER_AGENT,
                'Accept' => 'text/html,application/xhtml+xml',
            ])
                ->timeout(15)
                ->withOptions(['allow_redirects' => true])
                ->get($url);
        } catch (\Throwable) {
            return response()->json(['error' => 'Could not fetch the URL.'], 422);
        }

        $get = fn (string $k) => $res->header($k) ?: null;

        $csp = $get('content-security-policy');
        $hsts = $get('strict-transport-security');
        $xfo = $get('x-frame-options');
        $xcto = $get('x-content-type-options');
        $ref = $get('referrer-policy');
        $perm = $get('permissions-policy') ?? $get('feature-policy');
        $coop = $get('cross-origin-opener-policy');
        $coep = $get('cross-origin-embedder-policy');
        $server = $get('server');
        $xpb = $get('x-powered-by');
        $asp = $get('x-aspnet-version');

        $checks = [
            array_merge(['key' => 'content-security-policy', 'label' => 'Content-Security-Policy', 'weight' => 25, 'value' => $csp], $this->evalCSP($csp), ['docs' => 'https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy']),
            array_merge(['key' => 'strict-transport-security', 'label' => 'Strict-Transport-Security', 'weight' => 20, 'value' => $hsts], $this->evalHSTS($hsts), ['docs' => 'https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Strict-Transport-Security']),
            array_merge(['key' => 'x-frame-options', 'label' => 'X-Frame-Options', 'weight' => 10, 'value' => $xfo], $this->evalXFO($xfo, $csp), ['docs' => 'https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Frame-Options']),
            array_merge(['key' => 'x-content-type-options', 'label' => 'X-Content-Type-Options', 'weight' => 10, 'value' => $xcto], $this->evalXCTO($xcto), ['docs' => 'https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Content-Type-Options']),
            array_merge(['key' => 'referrer-policy', 'label' => 'Referrer-Policy', 'weight' => 10, 'value' => $ref], $this->evalReferrer($ref), ['docs' => 'https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Referrer-Policy']),
            array_merge(['key' => 'permissions-policy', 'label' => 'Permissions-Policy', 'weight' => 10, 'value' => $perm], $this->evalPermissions($perm), ['docs' => 'https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy']),
            array_merge(['key' => 'cross-origin-opener-policy', 'label' => 'Cross-Origin-Opener-Policy', 'weight' => 5, 'value' => $coop], $this->evalCOOP($coop), ['docs' => 'https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Cross-Origin-Opener-Policy']),
            array_merge(['key' => 'cross-origin-embedder-policy', 'label' => 'Cross-Origin-Embedder-Policy', 'weight' => 5, 'value' => $coep], $this->evalCOEP($coep), ['docs' => 'https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Cross-Origin-Embedder-Policy']),
            array_merge(['key' => 'server', 'label' => 'Server (info leak)', 'weight' => 2, 'value' => $server], $this->evalLeak('Server', $server), ['docs' => 'https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Server']),
            array_merge(['key' => 'x-powered-by', 'label' => 'X-Powered-By (info leak)', 'weight' => 2, 'value' => $xpb], $this->evalLeak('X-Powered-By', $xpb), ['docs' => 'https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Powered-By']),
            array_merge(['key' => 'x-aspnet-version', 'label' => 'X-AspNet-Version (info leak)', 'weight' => 1, 'value' => $asp], $this->evalLeak('X-AspNet-Version', $asp), ['docs' => 'https://learn.microsoft.com/en-us/aspnet/web-forms/overview/getting-started/getting-started-with-aspnet-45-web-forms/']),
        ];

        $earned = 0;
        $possible = 0;
        foreach ($checks as $c) {
            $possible += $c['weight'];
            if ($c['status'] === 'good') {
                $earned += $c['weight'];
            } elseif ($c['status'] === 'weak') {
                $earned += (int) round($c['weight'] * 0.4);
            }
        }
        $score = (int) round(($earned / max($possible, 1)) * 100);
        $g = $this->grade($score);

        $counts = [
            'good' => count(array_filter($checks, fn ($c) => $c['status'] === 'good')),
            'weak' => count(array_filter($checks, fn ($c) => $c['status'] === 'weak')),
            'missing' => count(array_filter($checks, fn ($c) => $c['status'] === 'missing')),
            'leak' => count(array_filter($checks, fn ($c) => $c['status'] === 'leak')),
        ];

        $finalUrl = (string) ($res->effectiveUri() ?? $url);

        return response()->json([
            'url' => $url,
            'finalUrl' => $finalUrl,
            'status' => $res->status(),
            'score' => $score,
            'grade' => $g['letter'],
            'gradeColor' => $g['color'],
            'counts' => $counts,
            'checks' => $checks,
        ]);
    }

    private function evalCSP(?string $v): array
    {
        if (! $v) {
            return ['status' => 'missing', 'why' => 'No Content-Security-Policy header. XSS attacks have no defense-in-depth.', 'fix' => "Add a CSP. Start strict: default-src 'self'; script-src 'self'; object-src 'none'; frame-ancestors 'none'; base-uri 'self'."];
        }
        $lc = strtolower($v);
        $issues = [];
        if (str_contains($lc, "'unsafe-inline'")) {
            $issues[] = "'unsafe-inline' allows inline scripts";
        }
        if (str_contains($lc, "'unsafe-eval'")) {
            $issues[] = "'unsafe-eval' allows eval()";
        }
        if (preg_match('/script-src[^;]*\*/', $lc)) {
            $issues[] = 'wildcard (*) in script-src';
        }
        if (! preg_match('/frame-ancestors/', $lc)) {
            $issues[] = 'frame-ancestors not set';
        }
        if (! preg_match('/default-src/', $lc) && ! preg_match('/script-src/', $lc)) {
            $issues[] = 'no default-src or script-src';
        }
        if (empty($issues)) {
            return ['status' => 'good', 'why' => 'CSP is set with strict directives.', 'fix' => 'Already strong — keep monitoring violations via report-uri.'];
        }

        return ['status' => 'weak', 'why' => 'CSP present but weak: '.implode(', ', $issues).'.', 'fix' => "Remove 'unsafe-inline' / 'unsafe-eval'. Add frame-ancestors 'none' or specific origins. Avoid wildcards in script-src."];
    }

    private function evalHSTS(?string $v): array
    {
        if (! $v) {
            return ['status' => 'missing', 'why' => 'No HSTS header. Browsers may downgrade to HTTP.', 'fix' => 'Add: Strict-Transport-Security: max-age=31536000; includeSubDomains; preload'];
        }
        $age = 0;
        if (preg_match('/max-age=(\d+)/i', $v, $m)) {
            $age = (int) $m[1];
        }
        $issues = [];
        if ($age < 15552000) {
            $issues[] = "max-age too short ({$age}s, recommend ≥6 months)";
        }
        if (! preg_match('/includesubdomains/i', $v)) {
            $issues[] = 'includeSubDomains missing';
        }
        if (empty($issues)) {
            return ['status' => 'good', 'why' => 'HSTS enforced for '.round($age / 86400).' days.', 'fix' => "Consider adding 'preload' and submitting to hstspreload.org."];
        }

        return ['status' => 'weak', 'why' => 'HSTS present but weak: '.implode(', ', $issues).'.', 'fix' => 'Set max-age=31536000 (1y) and add includeSubDomains; preload.'];
    }

    private function evalXFO(?string $v, ?string $csp): array
    {
        $cspHasAncestors = $csp && preg_match('/frame-ancestors/i', $csp);
        if (! $v && ! $cspHasAncestors) {
            return ['status' => 'missing', 'why' => 'No clickjacking protection (X-Frame-Options or CSP frame-ancestors).', 'fix' => "Add: X-Frame-Options: DENY (or SAMEORIGIN), or use CSP frame-ancestors 'none'."];
        }
        if ($cspHasAncestors && ! $v) {
            return ['status' => 'good', 'why' => 'Clickjacking covered by CSP frame-ancestors.', 'fix' => 'Already strong.'];
        }
        if ($v && preg_match('/^(deny|sameorigin)$/i', trim($v))) {
            return ['status' => 'good', 'why' => "X-Frame-Options: {$v}", 'fix' => 'Already strong.'];
        }

        return ['status' => 'weak', 'why' => "X-Frame-Options value is non-standard: {$v}", 'fix' => "Use 'DENY' or 'SAMEORIGIN' only. 'ALLOW-FROM' is deprecated; migrate to CSP frame-ancestors."];
    }

    private function evalXCTO(?string $v): array
    {
        if (! $v) {
            return ['status' => 'missing', 'why' => 'MIME sniffing not disabled — browsers may interpret content as a different type.', 'fix' => 'Add: X-Content-Type-Options: nosniff'];
        }
        if (preg_match('/nosniff/i', $v)) {
            return ['status' => 'good', 'why' => 'MIME sniffing disabled.', 'fix' => 'Already strong.'];
        }

        return ['status' => 'weak', 'why' => "Unexpected value: {$v}", 'fix' => "Set to exactly 'nosniff'."];
    }

    private function evalReferrer(?string $v): array
    {
        if (! $v) {
            return ['status' => 'missing', 'why' => 'No Referrer-Policy. Full URLs may leak to third parties.', 'fix' => 'Add: Referrer-Policy: strict-origin-when-cross-origin'];
        }
        $strong = ['no-referrer', 'same-origin', 'strict-origin', 'strict-origin-when-cross-origin'];
        $first = trim(explode(',', strtolower($v))[0]);
        if (in_array($first, $strong, true)) {
            return ['status' => 'good', 'why' => "Referrer-Policy: {$v}", 'fix' => 'Already strong.'];
        }

        return ['status' => 'weak', 'why' => "Weak policy: {$v} — may leak referrer cross-origin.", 'fix' => "Use 'strict-origin-when-cross-origin' or 'no-referrer'."];
    }

    private function evalPermissions(?string $v): array
    {
        if (! $v) {
            return ['status' => 'missing', 'why' => 'No Permissions-Policy — powerful features (camera, geolocation, etc.) have no restriction.', 'fix' => 'Add: Permissions-Policy: geolocation=(), camera=(), microphone=(), payment=()'];
        }

        return ['status' => 'good', 'why' => 'Permissions-Policy is set.', 'fix' => 'Review which features your origin actually needs and tighten the policy.'];
    }

    private function evalCOOP(?string $v): array
    {
        if (! $v) {
            return ['status' => 'missing', 'why' => 'No COOP — cross-origin windows can interact with this page.', 'fix' => 'Add: Cross-Origin-Opener-Policy: same-origin'];
        }
        if (preg_match('/same-origin/i', $v)) {
            return ['status' => 'good', 'why' => "COOP: {$v}", 'fix' => 'Already strong.'];
        }

        return ['status' => 'weak', 'why' => "COOP value '{$v}' weaker than same-origin.", 'fix' => "Prefer 'same-origin' unless you need legacy popup compatibility."];
    }

    private function evalCOEP(?string $v): array
    {
        if (! $v) {
            return ['status' => 'missing', 'why' => 'No COEP — required for SharedArrayBuffer / cross-origin isolation.', 'fix' => 'Add: Cross-Origin-Embedder-Policy: require-corp'];
        }
        if (preg_match('/require-corp|credentialless/i', $v)) {
            return ['status' => 'good', 'why' => "COEP: {$v}", 'fix' => 'Already strong.'];
        }

        return ['status' => 'weak', 'why' => "Unexpected COEP value: {$v}", 'fix' => "Use 'require-corp' or 'credentialless'."];
    }

    private function evalLeak(string $key, ?string $v): array
    {
        if (! $v) {
            return ['status' => 'good', 'why' => "{$key} not exposed.", 'fix' => 'Already good.'];
        }

        return ['status' => 'leak', 'why' => "Exposes server tech: '{$v}' — helps attackers fingerprint your stack.", 'fix' => "Remove or obscure the {$key} response header at the web server / reverse proxy."];
    }

    private function grade(int $score): array
    {
        if ($score >= 95) {
            return ['letter' => 'A+', 'color' => '#65a30d'];
        }
        if ($score >= 85) {
            return ['letter' => 'A', 'color' => '#84cc16'];
        }
        if ($score >= 70) {
            return ['letter' => 'B', 'color' => '#a3e635'];
        }
        if ($score >= 55) {
            return ['letter' => 'C', 'color' => '#f59e0b'];
        }
        if ($score >= 40) {
            return ['letter' => 'D', 'color' => '#f97316'];
        }

        return ['letter' => 'F', 'color' => '#f87171'];
    }
}
