<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Services\PageFetcher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FontDetectorController extends Controller
{
    private const SYSTEM_FONTS = [
        'arial', 'helvetica', 'verdana', 'georgia', 'times new roman', 'times', 'courier new', 'courier',
        'impact', 'comic sans ms', 'trebuchet ms', 'palatino', 'garamond', 'tahoma', 'sans-serif', 'serif',
        'monospace', 'cursive', 'fantasy', 'system-ui', '-apple-system', 'blinkmacsystemfont', 'segoe ui',
        'roboto', 'oxygen', 'ubuntu', 'cantarell', 'open sans', 'helvetica neue', 'fira sans', 'droid sans',
        'ui-sans-serif', 'ui-serif', 'ui-monospace', 'noto sans', 'source sans pro',
    ];

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

        $fonts = [];

        if (preg_match_all('/<link[^>]+href=["\']([^"\']*fonts\.googleapis\.com[^"\']*)["\'][^>]*>/i', $html, $m)) {
            foreach ($m[1] as $href) {
                $this->parseGoogleFontsUrl($href, $fonts);
            }
        }
        if (preg_match_all('/@import\s+url\(["\']?([^"\')\s]*fonts\.googleapis\.com[^"\')\s]*)["\']?\)/i', $allCss, $m)) {
            foreach ($m[1] as $href) {
                $this->parseGoogleFontsUrl($href, $fonts);
            }
        }

        if (preg_match_all('/@font-face\s*\{([^}]+)\}/i', $allCss, $m)) {
            foreach ($m[1] as $block) {
                if (! preg_match('/font-family\s*:\s*["\']?([^"\';\n]+)["\']?/i', $block, $fm)) {
                    continue;
                }
                $family = trim(str_replace(['"', "'"], '', $fm[1]));
                $weight = '400';
                if (preg_match('/font-weight\s*:\s*([^;\n]+)/i', $block, $wm)) {
                    $weight = trim($wm[1]);
                }
                $style = 'normal';
                if (preg_match('/font-style\s*:\s*([^;\n]+)/i', $block, $sm)) {
                    $style = trim($sm[1]);
                }

                $key = strtolower($family);
                if (! isset($fonts[$key])) {
                    $fonts[$key] = ['family' => $family, 'source' => 'Custom (@font-face)', 'weights' => [], 'styles' => [], 'usageCount' => 0];
                }
                if (! in_array($weight, $fonts[$key]['weights'], true)) {
                    $fonts[$key]['weights'][] = $weight;
                }
                if (! in_array($style, $fonts[$key]['styles'], true)) {
                    $fonts[$key]['styles'][] = $style;
                }
            }
        }

        if (preg_match_all('/font-family\s*:\s*([^;}\n]+)/i', $allCss, $m)) {
            foreach ($m[1] as $decl) {
                foreach (explode(',', $decl) as $f) {
                    $cleaned = trim(str_replace(['"', "'"], '', preg_replace('/\s+/', ' ', $f)));
                    if ($cleaned === '' || in_array(strtolower($cleaned), ['inherit', 'initial', 'unset', 'revert'], true)) {
                        continue;
                    }
                    $key = strtolower($cleaned);
                    if (! isset($fonts[$key])) {
                        $fonts[$key] = ['family' => $cleaned, 'source' => $this->isSystem($cleaned) ? 'System Font' : 'CSS Stack', 'weights' => [], 'styles' => [], 'usageCount' => 0];
                    }
                    $fonts[$key]['usageCount']++;
                }
            }
        }

        $order = ['Google Fonts' => 0, 'Custom (@font-face)' => 1, 'CSS Stack' => 2, 'System Font' => 3];
        $list = array_values($fonts);
        usort($list, function ($a, $b) use ($order) {
            $ao = $order[$a['source']] ?? 4;
            $bo = $order[$b['source']] ?? 4;

            return $ao !== $bo ? $ao <=> $bo : $b['usageCount'] <=> $a['usageCount'];
        });

        return response()->json(['url' => $url, 'loadTime' => $loadTime, 'total' => count($list), 'fonts' => $list]);
    }

    private function parseGoogleFontsUrl(string $url, array &$fonts): void
    {
        if (! preg_match_all('/family=([^&"\'#\s]+)/i', $url, $matches)) {
            return;
        }

        foreach ($matches[1] as $match) {
            foreach (explode('|', urldecode($match)) as $f) {
                $name = '';
                $weights = [];

                if (preg_match('/^([^:]+):(?:ital,)?wght@(.+)$/', $f, $p)) {
                    $name = trim(str_replace('+', ' ', $p[1]));
                    $weights = preg_split('/[;,]/', preg_replace('/\d+,/', '', $p[2]));
                } elseif (preg_match('/^([^:]+):([0-9,]+)$/', $f, $p)) {
                    $name = trim(str_replace('+', ' ', $p[1]));
                    $weights = explode(',', $p[2]);
                } else {
                    $name = trim(str_replace('+', ' ', preg_replace('/:.+$/', '', $f)));
                }

                if ($name === '') {
                    continue;
                }

                $key = strtolower($name);
                if (! isset($fonts[$key])) {
                    $fonts[$key] = ['family' => $name, 'source' => 'Google Fonts', 'weights' => [], 'styles' => [], 'usageCount' => 0];
                }
                foreach ($weights as $w) {
                    $t = trim($w);
                    if ($t !== '' && ! in_array($t, $fonts[$key]['weights'], true)) {
                        $fonts[$key]['weights'][] = $t;
                    }
                }
            }
        }
    }

    private function isSystem(string $name): bool
    {
        return in_array(strtolower(trim($name)), self::SYSTEM_FONTS, true);
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
}
