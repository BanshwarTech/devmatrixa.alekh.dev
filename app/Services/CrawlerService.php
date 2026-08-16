<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

/**
 * PHP port of the Next.js lib/crawler.ts helpers, used by the Link Checker
 * and Sitemap vs Crawl Diff tools. Runs a same-host BFS crawl plus a
 * sitemap.xml / robots.txt discovery pass.
 *
 * NOTE: PHP has no long-lived event loop, so "concurrency" here is achieved
 * per-batch via Http::pool() (Guzzle curl_multi) rather than a single global
 * worker pool like the Next.js version. Defaults are also tuned down from
 * the original (maxPages 250→150, maxDepth 8→6, budget 45s→25s) to stay
 * inside typical PHP-FPM / shared-hosting execution time limits.
 */
class CrawlerService
{
    private const UA = 'Mozilla/5.0 (compatible; DevmatrixaBot/1.0)';

    private const PAGE_EXT_RE = '/\.(php|html?|aspx?|jsp)$/i';

    public static function normalize(string $u): string
    {
        $parts = @parse_url($u);
        if (! $parts || empty($parts['scheme']) || empty($parts['host'])) {
            return $u;
        }

        $host = $parts['host'];
        if (isset($parts['port'])) {
            $host .= ':'.$parts['port'];
        }
        $path = $parts['path'] ?? '/';
        $query = isset($parts['query']) && $parts['query'] !== '' ? '?'.$parts['query'] : '';

        $result = "{$parts['scheme']}://{$host}{$path}{$query}";

        if (str_ends_with($result, '/') && $path !== '/') {
            $result = substr($result, 0, -1);
        }

        return $result;
    }

    public static function sameHost(string $u, string $host): bool
    {
        $h = @parse_url($u, PHP_URL_HOST);
        if (! $h) {
            return false;
        }
        $h = preg_replace('/^www\./', '', strtolower($h));
        $host = preg_replace('/^www\./', '', strtolower($host));

        return $h === $host;
    }

    public static function isLikelyAsset(string $u): bool
    {
        return (bool) preg_match('/\.(png|jpe?g|gif|webp|svg|ico|css|js|mjs|map|pdf|zip|rar|7z|mp4|mp3|webm|woff2?|ttf|eot|xml|json|txt)(\?|$)/i', $u);
    }

    public static function hasPageExt(string $u): bool
    {
        $path = @parse_url($u, PHP_URL_PATH);

        return $path ? (bool) preg_match(self::PAGE_EXT_RE, $path) : false;
    }

    public static function canonicalKey(string $u): string
    {
        $parts = @parse_url($u);
        if (! $parts || empty($parts['scheme']) || empty($parts['host'])) {
            return $u;
        }

        $path = $parts['path'] ?? '/';
        $path = preg_replace('#/index\.(php|html?|aspx?|jsp)$#i', '/', $path);
        $path = preg_replace(self::PAGE_EXT_RE, '', $path);
        if (strlen($path) > 1 && str_ends_with($path, '/')) {
            $path = substr($path, 0, -1);
        }
        $query = isset($parts['query']) && $parts['query'] !== '' ? '?'.$parts['query'] : '';
        $host = $parts['host'].(isset($parts['port']) ? ':'.$parts['port'] : '');

        return "{$parts['scheme']}://{$host}{$path}{$query}";
    }

    /** @param string[] $excludedPrefixes */
    public static function isJunkPath(string $u, array $excludedPrefixes = []): bool
    {
        $p = @parse_url($u, PHP_URL_PATH) ?: '/';
        $p = rtrim($p, '/');
        if ($p === '') {
            $p = '/';
        }

        foreach ($excludedPrefixes as $pref) {
            if ($p === $pref || str_starts_with($p, $pref.'/')) {
                return true;
            }
        }

        if (preg_match('#/\.(php|html?|aspx?|jsp)$#i', $p)) {
            return true;
        }

        return in_array($p, ['/.php', '/.html', '/.htm'], true);
    }

    /**
     * @return string[]
     */
    public static function parseSitemap(string $sitemapUrl, int $depth = 0, array &$seen = []): array
    {
        if ($depth > 3 || isset($seen[$sitemapUrl])) {
            return [];
        }
        $seen[$sitemapUrl] = true;

        try {
            $res = Http::withHeaders(['User-Agent' => self::UA])->timeout(8)->get($sitemapUrl);
            if (! $res->successful()) {
                return [];
            }
            $text = $res->body();
        } catch (\Throwable) {
            return [];
        }

        $isIndex = (bool) preg_match('/<sitemapindex/i', $text);
        $urls = [];
        preg_match_all('/<loc>([^<]+)<\/loc>/i', $text, $matches);
        foreach ($matches[1] as $loc) {
            $loc = trim($loc);
            if ($isIndex) {
                $urls = array_merge($urls, self::parseSitemap($loc, $depth + 1, $seen));
            } else {
                $urls[] = self::normalize($loc);
            }
        }

        return $urls;
    }

    /**
     * @return array{sitemaps: string[], urls: string[]}
     */
    public static function discoverSitemaps(string $baseUrl): array
    {
        $candidates = [
            "{$baseUrl}/sitemap.xml",
            "{$baseUrl}/sitemap_index.xml",
            "{$baseUrl}/sitemap-index.xml",
            "{$baseUrl}/sitemap/sitemap.xml",
        ];

        try {
            $res = Http::withHeaders(['User-Agent' => self::UA])->timeout(5)->get("{$baseUrl}/robots.txt");
            if ($res->successful()) {
                preg_match_all('/^\s*Sitemap:\s*(\S+)/im', $res->body(), $matches);
                foreach ($matches[1] as $sm) {
                    $candidates[] = trim($sm);
                }
            }
        } catch (\Throwable) {
            // ignore
        }

        $candidates = array_values(array_unique($candidates));

        $found = [];
        $allUrls = [];
        $seen = [];
        foreach ($candidates as $sm) {
            $r = self::parseSitemap($sm, 0, $seen);
            if (count($r) > 0) {
                $found[] = $sm;
            }
            $allUrls = array_merge($allUrls, $r);
        }

        return ['sitemaps' => $found, 'urls' => $allUrls];
    }

    /**
     * @return string[]
     */
    public static function extractLinks(string $html, string $base): array
    {
        $out = [];
        $push = function (?string $href) use (&$out, $base) {
            $href = trim((string) $href);
            if ($href === '') {
                return;
            }
            if (str_starts_with($href, '#') || str_starts_with($href, 'javascript:') || str_starts_with($href, 'mailto:') || str_starts_with($href, 'tel:')) {
                return;
            }
            $abs = PageFetcher::absUrl($href, $base);
            $out[] = self::normalize($abs);
        };

        try {
            $crawler = new Crawler($html);
            $crawler->filter('a[href]')->each(fn (Crawler $n) => $push($n->attr('href')));
            $crawler->filter('link[rel="canonical"], link[rel="next"], link[rel="prev"], link[rel="alternate"]')->each(fn (Crawler $n) => $push($n->attr('href')));
            $crawler->filter('area[href]')->each(fn (Crawler $n) => $push($n->attr('href')));
        } catch (\Throwable) {
            // malformed HTML — return whatever we already collected
        }

        return $out;
    }

    /**
     * @param  array{maxPages?: int, maxDepth?: int, concurrency?: int, pageTimeoutMs?: int, budgetMs?: int, excludedPrefixes?: string[]}  $opts
     * @return string[]
     */
    public static function crawl(string $seedUrl, string $host, array $opts = []): array
    {
        $o = array_merge([
            'maxPages' => 150,
            'maxDepth' => 6,
            'concurrency' => 8,
            'pageTimeoutMs' => 5000,
            'budgetMs' => 25000,
            'excludedPrefixes' => [],
        ], $opts);

        $startMs = microtime(true) * 1000;
        $seed = self::normalize($seedUrl);
        $discovered = [$seed => true];
        $seenKeys = [self::canonicalKey($seed) => true];
        $queue = [['url' => $seed, 'depth' => 0]];
        $visitedKeys = [];

        while (count($queue) > 0 && count($visitedKeys) < $o['maxPages']) {
            if ((microtime(true) * 1000 - $startMs) > $o['budgetMs']) {
                break;
            }

            $batch = array_splice($queue, 0, $o['concurrency']);
            $toFetch = [];
            foreach ($batch as $item) {
                $vKey = self::canonicalKey($item['url']);
                if (isset($visitedKeys[$vKey]) || count($visitedKeys) >= $o['maxPages']) {
                    continue;
                }
                $visitedKeys[$vKey] = true;
                if ($item['depth'] >= $o['maxDepth']) {
                    continue;
                }
                $toFetch[] = $item;
            }

            if (empty($toFetch)) {
                continue;
            }

            $timeoutSec = max(1, (int) ceil($o['pageTimeoutMs'] / 1000));

            try {
                $responses = Http::pool(function ($pool) use ($toFetch, $timeoutSec) {
                    foreach ($toFetch as $i => $item) {
                        $pool->as($i)
                            ->withHeaders(['User-Agent' => self::UA])
                            ->timeout($timeoutSec)
                            ->get($item['url']);
                    }
                });
            } catch (\Throwable) {
                $responses = [];
            }

            foreach ($toFetch as $i => $item) {
                $res = $responses[$i] ?? null;
                if (! $res || $res instanceof \Throwable || ! method_exists($res, 'status')) {
                    continue;
                }

                $contentType = (string) ($res->header('Content-Type') ?? '');
                if (! str_contains(strtolower($contentType), 'html')) {
                    continue;
                }

                $links = self::extractLinks($res->body(), $item['url']);
                foreach ($links as $l) {
                    if (! self::sameHost($l, $host)) {
                        continue;
                    }
                    if (self::isLikelyAsset($l)) {
                        continue;
                    }
                    if (self::isJunkPath($l, $o['excludedPrefixes'])) {
                        continue;
                    }
                    $k = self::canonicalKey($l);
                    if (isset($seenKeys[$k])) {
                        continue;
                    }
                    $seenKeys[$k] = true;
                    $discovered[$l] = true;
                    if (count($visitedKeys) + count($queue) < $o['maxPages']) {
                        $queue[] = ['url' => $l, 'depth' => $item['depth'] + 1];
                    }
                }
            }
        }

        return array_keys($discovered);
    }
}
