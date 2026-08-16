<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Services\PageFetcher;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PageWeightController extends Controller
{
    public function analyze(Request $request)
    {
        $url = (string) $request->input('url', '');

        if (! PageFetcher::isValidUrl($url)) {
            return response()->json(['error' => 'Invalid URL'], 422);
        }

        try {
            $page = PageFetcher::fetchPage($url);
            $html = $page['html'];
        } catch (\Throwable) {
            return response()->json(['error' => 'Could not fetch the URL.'], 422);
        }

        $htmlSize = strlen($html);

        $js = $this->extract($html, '/<script[^>]+src=["\']([^"\']+)["\'][^>]*>/i');
        $css = $this->extract($html, '/<link[^>]+rel=["\']stylesheet["\'][^>]+href=["\']([^"\']+)["\'][^>]*>/i');
        $imgs = $this->extract($html, '/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i');
        $media = $this->extract($html, '/<(?:video|audio)[^>]+src=["\']([^"\']+)["\'][^>]*>/i');

        $buildRefs = fn (array $srcs, string $type) => array_map(
            fn (string $src) => ['url' => PageFetcher::absUrl($src, $url), 'type' => $type],
            $srcs
        );

        $refs = array_merge(
            $buildRefs($js, 'JavaScript'),
            $buildRefs($css, 'CSS'),
            $buildRefs($imgs, 'Image'),
            $buildRefs($media, 'Media'),
        );

        $sizes = $this->probeSizes($refs);

        $resources = array_map(function (array $r) use ($sizes) {
            $size = $sizes[$r['url']] ?? 0;
            $segments = explode('/', $r['url']);
            $name = end($segments) ?: $r['url'];

            return [
                'url' => $r['url'],
                'name' => $name,
                'type' => $r['type'],
                'size' => $size,
                'sizeF' => $this->formatBytes($size),
            ];
        }, $refs);

        usort($resources, fn (array $a, array $b) => $b['size'] <=> $a['size']);

        $byType = [];
        $totalSize = $htmlSize;
        foreach ($resources as $r) {
            $byType[$r['type']] ??= ['count' => 0, 'size' => 0];
            $byType[$r['type']]['count']++;
            $byType[$r['type']]['size'] += $r['size'];
            $totalSize += $r['size'];
        }

        $typeSummary = [];
        foreach ($byType as $type => $d) {
            $typeSummary[] = ['type' => $type, 'count' => $d['count'], 'size' => $d['size'], 'sizeF' => $this->formatBytes($d['size'])];
        }
        usort($typeSummary, fn (array $a, array $b) => $b['size'] <=> $a['size']);

        return response()->json([
            'url' => $url,
            'htmlSize' => $htmlSize,
            'htmlSizeF' => $this->formatBytes($htmlSize),
            'totalSize' => $totalSize,
            'totalSizeF' => $this->formatBytes($totalSize),
            'typeSummary' => $typeSummary,
            'resources' => $resources,
        ]);
    }

    /**
     * @return string[] up to 10 unique, non-data: URLs
     */
    private function extract(string $html, string $pattern): array
    {
        preg_match_all($pattern, $html, $matches);
        $found = $matches[1] ?? [];
        $unique = array_values(array_unique($found));
        $unique = array_values(array_filter($unique, fn (string $s) => ! str_starts_with($s, 'data:')));

        return array_slice($unique, 0, 10);
    }

    /**
     * HEAD-probe every resource in parallel (falling back to GET when HEAD
     * is rejected), mirroring the Next.js Promise.all() HEAD-or-GET probe.
     *
     * @param  array<int, array{url: string, type: string}>  $refs
     * @return array<string, int> size in bytes keyed by resource URL
     */
    private function probeSizes(array $refs): array
    {
        if (empty($refs)) {
            return [];
        }

        $urls = array_values(array_unique(array_column($refs, 'url')));

        $headResponses = Http::pool(fn (Pool $pool) => array_map(
            fn (string $u) => $pool->as($u)
                ->withHeaders(['User-Agent' => PageFetcher::USER_AGENT])
                ->timeout(8)
                ->withOptions(['allow_redirects' => true])
                ->head($u),
            $urls
        ));

        $sizes = [];
        $needsGet = [];

        foreach ($urls as $u) {
            $resp = $headResponses[$u] ?? null;
            if ($resp instanceof Response && $resp->status() < 400 && $resp->status() !== 405) {
                $sizes[$u] = (int) ($resp->header('Content-Length') ?: 0);
            } else {
                $needsGet[] = $u;
            }
        }

        if (! empty($needsGet)) {
            $getResponses = Http::pool(fn (Pool $pool) => array_map(
                fn (string $u) => $pool->as($u)
                    ->withHeaders(['User-Agent' => PageFetcher::USER_AGENT])
                    ->timeout(8)
                    ->withOptions(['allow_redirects' => true])
                    ->get($u),
                $needsGet
            ));

            foreach ($needsGet as $u) {
                $resp = $getResponses[$u] ?? null;
                $sizes[$u] = ($resp instanceof Response) ? (int) ($resp->header('Content-Length') ?: 0) : 0;
            }
        }

        return $sizes;
    }

    private function formatBytes(int $b): string
    {
        if ($b <= 0) {
            return '—';
        }
        if ($b < 1024) {
            return "{$b} B";
        }
        if ($b < 1048576) {
            return (round($b / 1024, 1)).' KB';
        }

        return (round($b / 1048576, 2)).' MB';
    }
}
