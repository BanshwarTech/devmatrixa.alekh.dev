<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Services\PageFetcher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ColorPaletteController extends Controller
{
    public function analyze(Request $request)
    {
        $url = (string) $request->input('url', '');

        if (! PageFetcher::isValidUrl($url)) {
            return response()->json(['error' => 'Invalid URL'], 422);
        }

        try {
            $collected = $this->collectCss($url);
        } catch (\Throwable) {
            return response()->json(['error' => 'Could not fetch the URL.'], 422);
        }

        $html = $collected['html'];
        $allCss = $collected['allCss'];
        $loadTime = $collected['loadTime'];

        $raw = [];
        $add = function (string $c) use (&$raw) {
            $n = strtolower(trim($c));
            $raw[$n] = ($raw[$n] ?? 0) + 1;
        };

        $collect = function (string $css) use ($add) {
            if (preg_match_all('/#([0-9a-fA-F]{3,8})\b/', $css, $m)) {
                foreach ($m[0] as $match) {
                    $add($match);
                }
            }
            if (preg_match_all('/rgba?\(\s*[\d.]+\s*,\s*[\d.]+\s*,\s*[\d.]+(?:\s*,\s*[\d.]+)?\s*\)/i', $css, $m)) {
                foreach ($m[0] as $match) {
                    $add($match);
                }
            }
            if (preg_match_all('/hsla?\(\s*[\d.]+\s*,\s*[\d.%]+\s*,\s*[\d.%]+(?:\s*,\s*[\d.]+)?\s*\)/i', $css, $m)) {
                foreach ($m[0] as $match) {
                    $add($match);
                }
            }
        };
        $collect($allCss);

        if (preg_match('/<meta[^>]+name=["\']theme-color["\'][^>]+content=["\']([^"\']+)["\']/i', $html, $tm)) {
            $add($tm[1]);
        }

        $seen = [];
        foreach ($raw as $color => $cnt) {
            $hex = $this->toHex($color);
            if (! $hex || strlen($hex) !== 7) {
                continue;
            }
            if (! isset($seen[$hex])) {
                $seen[$hex] = ['hex' => $hex, 'rgb' => $this->hexToRgb($hex), 'count' => 0, 'original' => $color];
            }
            $seen[$hex]['count'] += $cnt;
        }

        $colors = array_values($seen);
        usort($colors, fn ($a, $b) => $b['count'] <=> $a['count']);

        return response()->json(['url' => $url, 'loadTime' => $loadTime, 'total' => count($colors), 'colors' => $colors]);
    }

    /**
     * Fetch the page and gather every CSS source: inline style="" attributes,
     * <style> blocks, and up to $maxFiles linked stylesheets.
     *
     * @return array{html: string, allCss: string, loadTime: int, baseUrl: string}
     */
    private function collectCss(string $url, int $maxFiles = 5): array
    {
        $page = PageFetcher::fetchPage($url);
        $html = $page['html'];
        $loadTime = $page['loadTime'];

        $scheme = parse_url($url, PHP_URL_SCHEME) ?: 'https';
        $host = parse_url($url, PHP_URL_HOST) ?: '';
        $baseUrl = "{$scheme}://{$host}";

        $allCss = '';

        if (preg_match_all('/style=["\']([^"\']*)["\']/i', $html, $m)) {
            foreach ($m[1] as $s) {
                $allCss .= "\n".$s;
            }
        }
        if (preg_match_all('/<style[^>]*>([\s\S]*?)<\/style>/i', $html, $m)) {
            foreach ($m[1] as $s) {
                $allCss .= "\n".$s;
            }
        }

        $links = [];
        if (preg_match_all('/<link[^>]+href=["\']([^"\']+\.css[^"\']*)["\']/i', $html, $m)) {
            $links = array_slice(array_values(array_filter($m[1])), 0, $maxFiles);
        }

        foreach ($links as $href) {
            $finalHref = PageFetcher::absUrl($href, $baseUrl);
            try {
                $res = Http::withHeaders(['User-Agent' => PageFetcher::USER_AGENT])
                    ->timeout(8)
                    ->withOptions(['allow_redirects' => true])
                    ->get($finalHref);
                if ($res->successful()) {
                    $allCss .= "\n".$res->body();
                }
            } catch (\Throwable) {
                // ignore unreachable stylesheets, same as the Next.js version
            }
        }

        return ['html' => $html, 'allCss' => $allCss, 'loadTime' => $loadTime, 'baseUrl' => $baseUrl];
    }

    private function toHex(string $c): ?string
    {
        $v = strtolower(trim($c));

        if (preg_match('/^#([0-9a-f]{3,8})$/', $v, $m)) {
            $h = $m[1];
            if (strlen($h) === 3) {
                $h = $h[0].$h[0].$h[1].$h[1].$h[2].$h[2];
            }
            if (strlen($h) === 8 || strlen($h) === 4) {
                $h = substr($h, 0, 6);
            }

            return strlen($h) === 6 ? '#'.$h : null;
        }

        if (preg_match('/rgba?\(\s*([\d.]+)\s*,\s*([\d.]+)\s*,\s*([\d.]+)/', $v, $m)) {
            $r = (int) $m[1];
            $g = (int) $m[2];
            $b = (int) $m[3];

            return '#'.sprintf('%02x%02x%02x', max(0, min(255, $r)), max(0, min(255, $g)), max(0, min(255, $b)));
        }

        if (preg_match('/hsla?\(\s*([\d.]+)\s*,\s*([\d.]+)%\s*,\s*([\d.]+)%/', $v, $m)) {
            [$r, $g, $b] = $this->hslToRgb((float) $m[1], (float) $m[2] / 100, (float) $m[3] / 100);

            return '#'.sprintf('%02x%02x%02x', $r, $g, $b);
        }

        return null;
    }

    private function hexToRgb(string $hex): array
    {
        $h = ltrim($hex, '#');

        return ['r' => hexdec(substr($h, 0, 2)), 'g' => hexdec(substr($h, 2, 2)), 'b' => hexdec(substr($h, 4, 2))];
    }

    private function hue2rgb(float $p, float $q, float $t): float
    {
        if ($t < 0) {
            $t += 1;
        }
        if ($t > 1) {
            $t -= 1;
        }
        if ($t < 1 / 6) {
            return $p + ($q - $p) * 6 * $t;
        }
        if ($t < 1 / 2) {
            return $q;
        }
        if ($t < 2 / 3) {
            return $p + ($q - $p) * (2 / 3 - $t) * 6;
        }

        return $p;
    }

    private function hslToRgb(float $h, float $s, float $l): array
    {
        $h /= 360;

        if ($s === 0.0) {
            $r = $g = $b = $l;
        } else {
            $q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
            $p = 2 * $l - $q;
            $r = $this->hue2rgb($p, $q, $h + 1 / 3);
            $g = $this->hue2rgb($p, $q, $h);
            $b = $this->hue2rgb($p, $q, $h - 1 / 3);
        }

        return [(int) round($r * 255), (int) round($g * 255), (int) round($b * 255)];
    }
}
