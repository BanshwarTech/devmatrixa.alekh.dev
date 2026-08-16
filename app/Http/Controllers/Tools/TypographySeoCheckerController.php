<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Services\PageFetcher;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class TypographySeoCheckerController extends Controller
{
    private const RECOMMENDATIONS = [
        'h1' => ['min' => 24, 'ideal_min' => 28, 'ideal_max' => 48, 'label' => 'H1 Heading'],
        'h2' => ['min' => 18, 'ideal_min' => 22, 'ideal_max' => 34, 'label' => 'H2 Heading'],
        'h3' => ['min' => 16, 'ideal_min' => 18, 'ideal_max' => 26, 'label' => 'H3 Heading'],
        'h4' => ['min' => 14, 'ideal_min' => 16, 'ideal_max' => 22, 'label' => 'H4 Heading'],
        'h5' => ['min' => 13, 'ideal_min' => 14, 'ideal_max' => 20, 'label' => 'H5 Heading'],
        'h6' => ['min' => 12, 'ideal_min' => 13, 'ideal_max' => 18, 'label' => 'H6 Heading'],
        'body' => ['min' => 14, 'ideal_min' => 16, 'ideal_max' => 18, 'label' => 'Body Text'],
        'p' => ['min' => 14, 'ideal_min' => 16, 'ideal_max' => 18, 'label' => 'Paragraph'],
        'li' => ['min' => 13, 'ideal_min' => 15, 'ideal_max' => 18, 'label' => 'List Item'],
        'small' => ['min' => 11, 'ideal_min' => 12, 'ideal_max' => 14, 'label' => 'Small Text'],
    ];

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

        $crawler = new Crawler($html);
        $parsed = parse_url($url);
        $origin = ($parsed['scheme'] ?? 'https').'://'.($parsed['host'] ?? '');

        $css = '';
        $crawler->filter('style')->each(function (Crawler $node) use (&$css) {
            $css .= $node->text()."\n";
        });

        $linkHrefs = [];
        $crawler->filter('link[rel="stylesheet"][href]')->each(function (Crawler $node) use (&$linkHrefs, $origin) {
            $href = $node->attr('href');
            if ($href && ! str_starts_with($href, 'data:')) {
                $linkHrefs[] = PageFetcher::absUrl($href, $origin);
            }
        });

        $hrefsToFetch = array_slice($linkHrefs, 0, 3);
        if (! empty($hrefsToFetch)) {
            $responses = Http::pool(fn (Pool $pool) => array_map(
                fn (string $h) => $pool->as($h)
                    ->withHeaders(['User-Agent' => PageFetcher::USER_AGENT])
                    ->timeout(8)
                    ->get($h),
                $hrefsToFetch
            ));

            foreach ($hrefsToFetch as $h) {
                $resp = $responses[$h] ?? null;
                if ($resp instanceof Response && $resp->successful()) {
                    $css .= "\n".$resp->body();
                }
            }
        }

        $cleanCss = preg_replace('/\/\*[\s\S]*?\*\//', '', $css);

        $elements = [];
        $scoreSum = 0;
        $scoreTotal = 0;

        foreach (self::RECOMMENDATIONS as $tag => $rec) {
            $cssSizes = $this->findCssFontSizes($cleanCss, $tag);

            $inlineSizes = [];
            $crawler->filter("{$tag}[style]")->each(function (Crawler $node) use (&$inlineSizes) {
                $style = $node->attr('style') ?? '';
                if (preg_match('/font-size\s*:\s*([^;!]+)/i', $style, $m)) {
                    $px = $this->toPx(trim($m[1]));
                    if ($px !== null && ! in_array($px, $inlineSizes, true)) {
                        $inlineSizes[] = $px;
                    }
                }
            });

            $allSizes = array_values(array_unique(array_merge($cssSizes, $inlineSizes)));
            $count = $crawler->filter($tag)->count();

            $pxSize = null;
            if (count($allSizes) === 0) {
                $status = 'undefined';
                $statusMsg = 'No explicit font-size found — browser default used';
            } else {
                $pxSize = $allSizes[count($allSizes) - 1];
                if ($pxSize < $rec['min']) {
                    $status = 'too-small';
                    $statusMsg = "Too small at {$pxSize}px — minimum is {$rec['min']}px";
                } elseif ($pxSize >= $rec['ideal_min'] && $pxSize <= $rec['ideal_max']) {
                    $status = 'ideal';
                    $statusMsg = "Ideal at {$pxSize}px (recommended {$rec['ideal_min']}–{$rec['ideal_max']}px)";
                } elseif ($pxSize > $rec['ideal_max']) {
                    $status = 'too-large';
                    $statusMsg = "Above ideal at {$pxSize}px (recommended {$rec['ideal_min']}–{$rec['ideal_max']}px)";
                } else {
                    $status = 'acceptable';
                    $statusMsg = "Acceptable at {$pxSize}px (ideal {$rec['ideal_min']}–{$rec['ideal_max']}px)";
                }
            }

            $scoreTotal++;
            $scoreSum += match ($status) {
                'ideal' => 1,
                'acceptable' => 0.7,
                'undefined' => 0.5,
                default => 0.2,
            };

            $sources = [];
            if (count($cssSizes)) {
                $sources[] = 'CSS / Style block';
            }
            if (count($inlineSizes)) {
                $sources[] = 'Inline style';
            }

            $elements[] = [
                'tag' => $tag,
                'label' => $rec['label'],
                'pxSize' => $pxSize,
                'allSizes' => $allSizes,
                'rec' => $rec,
                'status' => $status,
                'statusMsg' => $statusMsg,
                'count' => $count,
                'sources' => $sources,
            ];
        }

        $score = $scoreTotal > 0 ? (int) round(($scoreSum / $scoreTotal) * 100) : 0;
        $issues = [];
        $passes = [];

        foreach ($elements as $el) {
            switch ($el['status']) {
                case 'too-small':
                    $issues[] = [
                        'type' => 'error',
                        'msg' => "{$el['label']} ({$el['tag']}) is {$el['pxSize']}px — below the {$el['rec']['min']}px minimum.",
                        'fix' => "Set `{$el['tag']} { font-size: {$el['rec']['ideal_min']}px; }`",
                    ];
                    break;
                case 'too-large':
                    $issues[] = [
                        'type' => 'warning',
                        'msg' => "{$el['label']} ({$el['tag']}) is {$el['pxSize']}px — above the ideal max of {$el['rec']['ideal_max']}px.",
                        'fix' => "Reduce to {$el['rec']['ideal_min']}–{$el['rec']['ideal_max']}px.",
                    ];
                    break;
                case 'undefined':
                    if (in_array($el['tag'], ['h1', 'p', 'body'], true)) {
                        $issues[] = [
                            'type' => 'info',
                            'msg' => "No explicit font-size found for `{$el['tag']}` — browser default applied.",
                            'fix' => "Define `{$el['tag']} { font-size: {$el['rec']['ideal_min']}px; }` for consistent rendering.",
                        ];
                    }
                    break;
                case 'ideal':
                    $passes[] = "{$el['label']} ({$el['pxSize']}px) is in the ideal range.";
                    break;
                case 'acceptable':
                    $passes[] = "{$el['label']} ({$el['pxSize']}px) is acceptable.";
                    break;
            }
        }

        return response()->json([
            'url' => $url,
            'score' => $score,
            'elements' => $elements,
            'issues' => $issues,
            'passes' => $passes,
        ]);
    }

    private function toPx(string $value): ?int
    {
        $v = strtolower(trim($value));

        if (preg_match('/^([\d.]+)px$/', $v, $m)) {
            return (int) round((float) $m[1]);
        }
        if (preg_match('/^([\d.]+)rem$/', $v, $m)) {
            return (int) round((float) $m[1] * 16);
        }
        if (preg_match('/^([\d.]+)em$/', $v, $m)) {
            return (int) round((float) $m[1] * 16);
        }
        if (preg_match('/^([\d.]+)pt$/', $v, $m)) {
            return (int) round((float) $m[1] * 1.333);
        }
        if (preg_match('/^([\d.]+)vw$/', $v, $m)) {
            return (int) round((float) $m[1] * 14);
        }
        if (preg_match('/^([\d.]+)%$/', $v, $m)) {
            return (int) round((float) $m[1] * 0.16);
        }

        return null;
    }

    /**
     * @return int[]
     */
    private function findCssFontSizes(string $css, string $tag): array
    {
        $quoted = preg_quote($tag, '/');
        $pattern = '/(?:^|[,}{])([^{]*\\b'.$quoted.'\\b[^{]*)\\{([^}]*)\\}/im';
        $sizes = [];

        if (preg_match_all($pattern, $css, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $m) {
                if (! preg_match('/\\b'.$quoted.'\\b/i', $m[1])) {
                    continue;
                }
                if (preg_match('/font-size\s*:\s*([^;!\n]+)/i', $m[2], $sm)) {
                    $px = $this->toPx(trim($sm[1]));
                    if ($px !== null && ! in_array($px, $sizes, true)) {
                        $sizes[] = $px;
                    }
                }
            }
        }

        return $sizes;
    }
}
