<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Services\CrawlerService;
use App\Services\PageFetcher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LinkCheckerController extends Controller
{
    private const UA = 'Mozilla/5.0 (compatible; DevmatrixaBot/1.0)';

    private const EXCLUDED_PREFIXES = ['/blog'];

    /**
     * Port of app/api/link-checker/route.ts — discovers every same-host page
     * reachable via a live crawl + sitemap.xml/robots.txt, returns the
     * de-duplicated URL list. The Vue island then hits `status()` below for
     * each URL to get live HTTP status codes.
     */
    public function analyze(Request $request)
    {
        $url = (string) $request->input('url', '');

        if (! PageFetcher::isValidUrl($url)) {
            return response()->json(['error' => 'Invalid URL'], 422);
        }

        $target = CrawlerService::normalize($url);
        $parsed = parse_url($target);
        $host = $parsed['host'].(isset($parsed['port']) ? ':'.$parsed['port'] : '');
        $baseUrl = "{$parsed['scheme']}://{$host}";

        $sitemapResult = CrawlerService::discoverSitemaps($baseUrl);
        $crawledUrls = CrawlerService::crawl($target, $host, ['excludedPrefixes' => self::EXCLUDED_PREFIXES]);

        $byKey = [];
        foreach ([...$crawledUrls, ...$sitemapResult['urls']] as $u) {
            $n = CrawlerService::normalize($u);
            if (! CrawlerService::sameHost($n, $host)) {
                continue;
            }
            if (CrawlerService::isLikelyAsset($n)) {
                continue;
            }
            if (CrawlerService::isJunkPath($n, self::EXCLUDED_PREFIXES)) {
                continue;
            }
            $key = CrawlerService::canonicalKey($n);
            $existing = $byKey[$key] ?? null;
            if (! $existing || (CrawlerService::hasPageExt($existing) && ! CrawlerService::hasPageExt($n))) {
                $byKey[$key] = $n;
            }
        }

        $urls = array_values($byKey);
        sort($urls);

        return response()->json(['urls' => $urls, 'targetUrl' => $target, 'count' => count($urls)]);
    }

    /**
     * Port of app/api/link-status/route.ts — checks the live HTTP status of
     * a single URL (HEAD first, falling back to GET on 4xx/405).
     */
    public function status(Request $request)
    {
        $url = (string) $request->input('url', '');
        if ($url === '') {
            return response()->json(['status' => 0]);
        }

        return response()->json(['status' => $this->headOrGet($url, 10000)]);
    }

    private function headOrGet(string $url, int $timeoutMs): int
    {
        $timeoutSec = max(1, (int) ceil($timeoutMs / 1000));

        try {
            $res = Http::withHeaders(['User-Agent' => self::UA])
                ->timeout($timeoutSec)
                ->withOptions(['allow_redirects' => true])
                ->head($url);

            if ($res->status() >= 400 || $res->status() === 405) {
                $res = Http::withHeaders(['User-Agent' => self::UA])
                    ->timeout($timeoutSec)
                    ->withOptions(['allow_redirects' => true])
                    ->get($url);
            }

            return $res->status();
        } catch (\Throwable) {
            return 0;
        }
    }
}
