<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Services\CrawlerService;
use App\Services\PageFetcher;
use Illuminate\Http\Request;

class SitemapDiffController extends Controller
{
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
        $crawledUrls = CrawlerService::crawl($target, $host);

        $filterValid = function (string $u) use ($host) {
            $n = CrawlerService::normalize($u);
            if (! CrawlerService::sameHost($n, $host)) {
                return null;
            }
            if (CrawlerService::isLikelyAsset($n)) {
                return null;
            }
            if (CrawlerService::isJunkPath($n)) {
                return null;
            }

            return $n;
        };

        $sitemapClean = $this->dedupePreferClean(array_values(array_filter(array_map($filterValid, $sitemapResult['urls']))));
        $crawledClean = $this->dedupePreferClean(array_values(array_filter(array_map($filterValid, $crawledUrls))));

        $sitemapKeys = [];
        foreach ($sitemapClean as $u) {
            $sitemapKeys[CrawlerService::canonicalKey($u)] = $u;
        }
        $crawledKeys = [];
        foreach ($crawledClean as $u) {
            $crawledKeys[CrawlerService::canonicalKey($u)] = $u;
        }

        $inBoth = [];
        $orphanInSitemap = [];
        $missingFromSitemap = [];

        foreach ($sitemapKeys as $k => $u) {
            if (isset($crawledKeys[$k])) {
                $inBoth[] = $u;
            } else {
                $orphanInSitemap[] = $u;
            }
        }
        foreach ($crawledKeys as $k => $u) {
            if (! isset($sitemapKeys[$k])) {
                $missingFromSitemap[] = $u;
            }
        }

        sort($inBoth);
        sort($orphanInSitemap);
        sort($missingFromSitemap);

        $totalUnique = count(array_unique(array_merge(array_keys($sitemapKeys), array_keys($crawledKeys))));
        $coverage = $totalUnique > 0 ? (int) round((count($inBoth) / $totalUnique) * 100) : 0;

        return response()->json([
            'url' => $target,
            'baseUrl' => $baseUrl,
            'sitemapsFound' => $sitemapResult['sitemaps'],
            'sitemapCount' => count($sitemapClean),
            'crawledCount' => count($crawledClean),
            'inBothCount' => count($inBoth),
            'orphanCount' => count($orphanInSitemap),
            'missingCount' => count($missingFromSitemap),
            'coverage' => $coverage,
            'inBoth' => $inBoth,
            'orphanInSitemap' => $orphanInSitemap,
            'missingFromSitemap' => $missingFromSitemap,
        ]);
    }

    /**
     * @param  string[]  $urls
     * @return string[]
     */
    private function dedupePreferClean(array $urls): array
    {
        $byKey = [];
        foreach ($urls as $u) {
            $key = CrawlerService::canonicalKey($u);
            $existing = $byKey[$key] ?? null;
            if (! $existing || (CrawlerService::hasPageExt($existing) && ! CrawlerService::hasPageExt($u))) {
                $byKey[$key] = $u;
            }
        }

        return array_values($byKey);
    }
}
