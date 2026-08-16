<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Services\PageFetcher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CssVariableScannerController extends Controller
{
    public function analyze(Request $request)
    {
        $url = (string) $request->input('url', '');

        if (! PageFetcher::isValidUrl($url)) {
            return response()->json(['error' => 'Invalid URL'], 422);
        }

        try {
            ['allCss' => $allCss, 'loadTime' => $loadTime] = $this->collectCss($url);
        } catch (\Throwable) {
            return response()->json(['error' => 'Could not fetch the URL.'], 422);
        }

        $vars = [];

        if (preg_match_all('/--([a-zA-Z0-9_-]+)\s*:\s*([^;}\n][^;}\n]*)/', $allCss, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $m) {
                $name = '--'.$m[1];
                $value = trim($m[2]);
                if (strlen($value) > 200) {
                    continue;
                }
                if (! isset($vars[$name])) {
                    $vars[$name] = [
                        'name' => $name,
                        'value' => $value,
                        'type' => $this->guessType($name, $value),
                        'declCount' => 0,
                        'usageCount' => 0,
                    ];
                }
                $vars[$name]['declCount']++;
            }
        }

        if (preg_match_all('/var\(\s*(--[a-zA-Z0-9_-]+)[^)]*\)/', $allCss, $usageMatches)) {
            foreach ($usageMatches[1] as $varName) {
                if (isset($vars[$varName])) {
                    $vars[$varName]['usageCount']++;
                }
            }
        }

        $list = array_values($vars);
        usort($list, fn ($a, $b) => $b['usageCount'] <=> $a['usageCount']);

        $grouped = ['Colors' => [], 'Sizes & Spacing' => [], 'Typography' => [], 'Other' => []];
        foreach ($list as $v) {
            if ($v['type'] === 'color') {
                $grouped['Colors'][] = $v;
            } elseif ($v['type'] === 'size') {
                $grouped['Sizes & Spacing'][] = $v;
            } elseif ($v['type'] === 'font') {
                $grouped['Typography'][] = $v;
            } else {
                $grouped['Other'][] = $v;
            }
        }

        $filtered = array_filter($grouped, fn ($v) => count($v) > 0);

        return response()->json([
            'url' => $url,
            'loadTime' => $loadTime,
            'total' => count($list),
            'grouped' => $filtered,
        ]);
    }

    /**
     * Ports lib/collectCss.ts — gathers inline style attrs, <style> blocks,
     * and up to 5 linked stylesheets into one CSS blob.
     *
     * @return array{allCss: string, loadTime: int}
     */
    private function collectCss(string $url, int $maxFiles = 5): array
    {
        $page = PageFetcher::fetchPage($url);
        $html = $page['html'];
        $loadTime = $page['loadTime'];

        $parts = [];

        if (preg_match_all('/style=["\']([^"\']*)["\']/i', $html, $m)) {
            foreach ($m[1] as $style) {
                $parts[] = $style;
            }
        }

        if (preg_match_all('/<style[^>]*>([\s\S]*?)<\/style>/i', $html, $m)) {
            foreach ($m[1] as $style) {
                $parts[] = $style;
            }
        }

        $links = [];
        if (preg_match_all('/<link[^>]+href=["\']([^"\']+\.css[^"\']*)["\']/i', $html, $m)) {
            $links = array_slice(array_filter($m[1]), 0, $maxFiles);
        }

        foreach ($links as $href) {
            $finalHref = PageFetcher::absUrl($href, $url);
            try {
                $res = Http::withHeaders(['User-Agent' => PageFetcher::USER_AGENT])
                    ->timeout(8)
                    ->withOptions(['allow_redirects' => true])
                    ->get($finalHref);
                if ($res->successful()) {
                    $parts[] = $res->body();
                }
            } catch (\Throwable) {
                // ignore individual stylesheet fetch failures
            }
        }

        return ['allCss' => implode("\n", $parts), 'loadTime' => $loadTime];
    }

    private function guessType(string $name, string $value): string
    {
        $v = strtolower(trim($value));
        $n = strtolower($name);

        if (preg_match('/^#[0-9a-f]{3,8}$/i', $v)) {
            return 'color';
        }
        if (preg_match('/^rgba?\(|^hsla?\(/i', $v)) {
            return 'color';
        }
        if (preg_match('/^(transparent|currentcolor|black|white|red|blue|green|yellow|orange|purple|pink|gray|grey|teal|navy|coral|crimson|indigo|violet)$/i', $v)) {
            return 'color';
        }
        if (preg_match('/color|bg|background|fill|stroke|shadow|border|ring/i', $n)) {
            return 'color';
        }
        if (preg_match('/\d+(px|rem|em|vh|vw|%|fr|ch|ex)/', $v)) {
            return 'size';
        }
        if (preg_match('/spacing|gap|padding|margin|size|width|height|radius|border/i', $n)) {
            return 'size';
        }
        if (preg_match('/font|family|weight|line-height|letter|tracking/i', $n)) {
            return 'font';
        }
        if (preg_match('/^["\']/', $v)) {
            return 'font';
        }

        return 'other';
    }
}
