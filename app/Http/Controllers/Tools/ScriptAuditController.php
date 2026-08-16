<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Services\PageFetcher;
use Illuminate\Http\Request;

class ScriptAuditController extends Controller
{
    private const ALIASES = [
        'jquery' => 'jquery', 'jquery-ui' => 'jquery-ui', 'jqueryui' => 'jquery-ui', 'bootstrap' => 'bootstrap',
        'react-dom' => 'react-dom', 'react' => 'react', 'vue' => 'vue', 'angular' => 'angular', 'angularjs' => 'angular',
        'lodash' => 'lodash', 'underscore' => 'underscore', 'moment' => 'moment', 'dayjs' => 'dayjs', 'axios' => 'axios',
        'alpinejs' => 'alpine', 'alpine' => 'alpine', 'gsap' => 'gsap', 'three' => 'three.js', 'swiper' => 'swiper',
        'slick' => 'slick', 'popper' => 'popper',
    ];

    private const BLOCKED_EXTENSIONS = [
        'jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'ico', 'pdf', 'zip', 'css', 'js',
        'woff', 'woff2', 'ttf', 'xml', 'json', 'txt',
    ];

    public function analyze(Request $request)
    {
        $url = (string) $request->input('url', '');
        $maxPages = max(5, min(20, (int) $request->input('maxPages', 15)));

        if (! PageFetcher::isValidUrl($url)) {
            return response()->json(['error' => 'Invalid URL'], 422);
        }

        $parsed = parse_url($url);
        if (! isset($parsed['scheme'], $parsed['host'])) {
            return response()->json(['error' => 'Invalid URL'], 422);
        }
        $base = "{$parsed['scheme']}://{$parsed['host']}".(isset($parsed['port']) ? ":{$parsed['port']}" : '');

        $pages = $this->crawl($url, $base, $maxPages);
        if (count($pages) === 0) {
            return response()->json(['error' => 'Could not fetch the URL.'], 422);
        }

        $pageScripts = [];
        $scriptPages = [];

        foreach ($pages as $pageUrl => $html) {
            $scripts = $this->extractScripts($html, $pageUrl);
            $pageScripts[$pageUrl] = $scripts;

            foreach (array_unique($scripts) as $s) {
                $scriptPages[$s] ??= [];
                if (! in_array($pageUrl, $scriptPages[$s], true)) {
                    $scriptPages[$s][] = $pageUrl;
                }
            }
        }

        $pageDuplicates = [];
        foreach ($pageScripts as $pageUrl => $scripts) {
            $counts = array_count_values($scripts);
            foreach ($counts as $script => $count) {
                if ($count > 1) {
                    $pageDuplicates[] = ['script' => $script, 'name' => $this->scriptName($script), 'page' => $pageUrl, 'count' => $count];
                }
            }
        }

        $crossPage = [];
        foreach ($scriptPages as $s => $pgs) {
            if (count($pgs) >= 2) {
                $crossPage[] = ['url' => $s, 'name' => $this->scriptName($s), 'pageCount' => count($pgs), 'pages' => $pgs];
            }
        }
        usort($crossPage, fn (array $a, array $b) => $b['pageCount'] <=> $a['pageCount']);

        $libs = [];
        foreach ($scriptPages as $script => $pgs) {
            $lib = $this->parseLibrary($script);
            if (! $lib['name']) {
                continue;
            }
            $ver = $lib['version'] ?? 'unversioned';
            $libs[$lib['name']] ??= [];
            $libs[$lib['name']][$ver] ??= ['url' => $script, 'pages' => []];
            foreach ($pgs as $p) {
                if (! in_array($p, $libs[$lib['name']][$ver]['pages'], true)) {
                    $libs[$lib['name']][$ver]['pages'][] = $p;
                }
            }
        }

        $multiVersion = [];
        foreach ($libs as $libName => $versions) {
            $versioned = array_filter(array_keys($versions), fn ($v) => $v !== 'unversioned');
            if (count($versioned) >= 2) {
                $vList = [];
                foreach ($versions as $version => $d) {
                    $vList[] = ['version' => $version, 'url' => $d['url'], 'pageCount' => count($d['pages']), 'pages' => $d['pages']];
                }
                usort($vList, fn (array $a, array $b) => version_compare($b['version'], $a['version']));
                $multiVersion[] = ['library' => $libName, 'versionCount' => count($versions), 'versions' => $vList];
            }
        }

        $pageList = [];
        foreach ($pageScripts as $pageUrl => $scripts) {
            $unique = array_values(array_unique($scripts));
            $pageList[] = [
                'url' => $pageUrl,
                'count' => count($unique),
                'scripts' => array_map(fn (string $s) => ['url' => $s, 'name' => $this->scriptName($s)], $unique),
            ];
        }
        usort($pageList, fn (array $a, array $b) => $b['count'] <=> $a['count']);

        $totalIssues = count($pageDuplicates) + count($multiVersion);
        $totalRefs = array_sum(array_map('count', $pageScripts));

        return response()->json([
            'url' => $url,
            'pagesScanned' => count($pages),
            'totalRefs' => $totalRefs,
            'uniqueScripts' => count($scriptPages),
            'totalIssues' => $totalIssues,
            'issues' => [
                'pageDuplicates' => $pageDuplicates,
                'crossPage' => $crossPage,
                'multiVersion' => $multiVersion,
            ],
            'pages' => $pageList,
        ]);
    }

    /**
     * Breadth-first crawl of up to $maxPages same-site pages, mirroring the
     * Next.js sequential crawler (8s per-page timeout, same page cap).
     *
     * @return array<string, string> HTML keyed by page URL, insertion-ordered
     */
    private function crawl(string $startUrl, string $base, int $maxPages): array
    {
        $queue = [$this->normalizeUrl($startUrl)];
        $visited = [];
        $results = [];

        while (count($queue) > 0 && count($results) < $maxPages) {
            $current = array_shift($queue);
            if (isset($visited[$current])) {
                continue;
            }
            $visited[$current] = true;

            try {
                $page = PageFetcher::fetchPage($current, 8000);
                $html = $page['html'];
                if (! $html || strlen($html) < 100 || ! $this->looksLikeHtml($html)) {
                    continue;
                }
                $results[$current] = $html;

                if (count($results) < $maxPages) {
                    preg_match_all('/<a[^>]+href=["\']([^"\']+)["\'][^>]*>/i', $html, $matches);
                    foreach ($matches[1] as $href) {
                        $r = $this->resolveLink($href, $current, $base);
                        if ($r !== null && ! isset($visited[$r]) && ! in_array($r, $queue, true)) {
                            $queue[] = $r;
                        }
                    }
                }
            } catch (\Throwable) {
                // skip unreachable pages, same as the Next.js crawler
            }
        }

        return $results;
    }

    private function looksLikeHtml(string $s): bool
    {
        $snippet = substr(ltrim($s), 0, 200);

        return (bool) preg_match('/<(html|head)|<!doctype/i', $snippet);
    }

    private function normalizeUrl(string $u): string
    {
        $trimmed = rtrim($u, '/');

        return $trimmed !== '' ? $trimmed : $u;
    }

    private function scriptName(string $u): string
    {
        $path = parse_url($u, PHP_URL_PATH);
        if (! $path) {
            return $u;
        }
        $segments = explode('/', $path);
        $last = end($segments);

        return ($last !== '' && $last !== false) ? $last : $u;
    }

    /**
     * @return array{name: ?string, version: ?string}
     */
    private function parseLibrary(string $url): array
    {
        $path = parse_url($url, PHP_URL_PATH);
        if (! $path) {
            return ['name' => null, 'version' => null];
        }
        $segments = explode('/', $path);
        $filename = end($segments);
        if (! $filename) {
            return ['name' => null, 'version' => null];
        }

        $version = null;
        if (preg_match('/[.\-_@]v?(\d+(?:\.\d+){1,3})(?:[.\-_@]|\.js|$)/i', $filename, $m)) {
            $version = $m[1];
        }

        $name = $filename;
        $name = preg_replace('/[.\-_@]v?\d+(?:\.\d+){0,3}/i', '', $name);
        $name = preg_replace('/\.(min|bundle|prod|dev|production|development|esm|cjs|umd|iife|global|slim|full|core|common|module)(\.js)?$/i', '.js', $name);
        $name = preg_replace('/\.js$/i', '', $name);
        $name = trim($name, '.-_@');
        $name = strtolower($name);

        $name = self::ALIASES[$name] ?? $name;
        if (strlen($name) < 2) {
            return ['name' => null, 'version' => null];
        }

        return ['name' => $name, 'version' => $version];
    }

    private function extractScripts(string $html, string $pageUrl): array
    {
        $out = [];
        preg_match_all('/<script[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $html, $matches);
        foreach ($matches[1] as $src) {
            $src = trim($src);
            if ($src === '' || str_starts_with($src, 'data:')) {
                continue;
            }
            $abs = PageFetcher::absUrl($src, $pageUrl);
            $abs = explode('#', explode('?', $abs)[0])[0];
            $out[] = $abs;
        }

        return $out;
    }

    private function resolveLink(string $href, string $pageUrl, string $base): ?string
    {
        $href = trim($href);
        if ($href === '' || preg_match('/^#/', $href) || preg_match('/^(mailto:|tel:|javascript:)/i', $href)) {
            return null;
        }

        $resolved = explode('#', PageFetcher::absUrl($href, $pageUrl))[0];
        if (! str_starts_with($resolved, $base)) {
            return null;
        }
        $resolved = explode('?', $resolved)[0];

        $ext = '';
        if (preg_match('/\.([a-z0-9]+)$/i', $resolved, $m)) {
            $ext = strtolower($m[1]);
        }
        if (in_array($ext, self::BLOCKED_EXTENSIONS, true)) {
            return null;
        }

        return $this->normalizeUrl($resolved);
    }
}
