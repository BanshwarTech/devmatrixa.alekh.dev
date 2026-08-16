<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Services\PageFetcher;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;

class AnchorTextAnalyzerController extends Controller
{
    private const GENERIC = [
        'click here', 'here', 'read more', 'more', 'link', 'this', 'this link', 'this page',
        'visit', 'see more', 'learn more', 'go here', 'click', 'website', 'page', 'article',
        'post', 'source', 'view', 'check it out', 'find out more', 'continue reading',
        'read here', 'click now', 'go to', 'visit here', 'read', 'download', 'see here',
        'check this', 'visit now', 'view more', 'see all', 'learn', 'info', 'details',
        'click this', 'press here', 'go', 'open', 'view all', 'button', 'tap here',
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
            return response()->json(['error' => 'Could not fetch the URL. Check if it is publicly accessible.'], 422);
        }

        $crawler = new Crawler($html);
        $parsedHost = parse_url($url, PHP_URL_HOST) ?: '';

        $anchors = [];
        $typeCounts = ['keyword' => 0, 'generic' => 0, 'naked_url' => 0, 'image' => 0, 'empty' => 0];
        $internal = 0;
        $external = 0;
        $nofollow = 0;
        $follow = 0;
        $seen = [];
        $duplicates = [];

        $crawler->filter('a[href]')->each(function (Crawler $a) use (
            $url, $parsedHost, &$anchors, &$typeCounts, &$internal, &$external, &$nofollow, &$follow, &$seen, &$duplicates
        ) {
            $href = trim($a->attr('href') ?? '');
            if ($href === '' || $href === '#' || preg_match('/^(javascript:|mailto:|tel:)/i', $href)) {
                return;
            }
            $href = PageFetcher::absUrl($href, $url);

            $rawText = trim(preg_replace('/\s+/', ' ', $a->text()));
            $hasImage = $a->filter('img')->count() > 0;

            if ($rawText === '') {
                $type = $hasImage ? 'image' : 'empty';
                $text = $hasImage ? '(image link)' : '(empty)';
            } elseif (preg_match('#^https?://#i', $rawText) || preg_match('/^www\./i', $rawText)) {
                $type = 'naked_url';
                $text = $rawText;
            } elseif (in_array(strtolower($rawText), self::GENERIC, true)) {
                $type = 'generic';
                $text = $rawText;
            } else {
                $type = 'keyword';
                $text = $rawText;
            }

            $linkHost = parse_url($href, PHP_URL_HOST) ?: '';
            $isInternal = $linkHost === $parsedHost || $linkHost === '';
            $isInternal ? $internal++ : $external++;

            $rel = strtolower($a->attr('rel') ?? '');
            $isNofollow = str_contains($rel, 'nofollow');
            $isNofollow ? $nofollow++ : $follow++;

            $typeCounts[$type]++;

            $dupKey = strtolower($text).'||'.strtolower($href);
            if (isset($seen[$dupKey])) {
                $duplicates[$dupKey] = true;
            }
            $seen[$dupKey] = true;

            $anchors[] = [
                'text' => $text,
                'href' => $href,
                'type' => $type,
                'internal' => $isInternal,
                'nofollow' => $isNofollow,
                'rel' => $rel !== '' ? $rel : 'follow',
                'title' => $a->attr('title') ?: null,
            ];
        });

        foreach ($anchors as &$a) {
            $k = strtolower($a['text']).'||'.strtolower($a['href']);
            $a['duplicate'] = isset($duplicates[$k]);
        }
        unset($a);

        $total = count($anchors);
        $issues = [];

        $genericPct = $total > 0 ? (int) round(($typeCounts['generic'] / $total) * 100) : 0;
        if ($genericPct >= 30) {
            $issues[] = ['type' => 'warning', 'msg' => "{$genericPct}% of anchors are generic (\"click here\", \"read more\") — hurts SEO relevance.", 'fix' => 'Replace with descriptive, keyword-rich text.'];
        }
        if ($typeCounts['empty'] > 0) {
            $issues[] = ['type' => 'error', 'msg' => "{$typeCounts['empty']} anchor(s) have no text — invisible to users and SEO.", 'fix' => 'Add meaningful anchor text to every link.'];
        }
        if ($typeCounts['naked_url'] > 5) {
            $issues[] = ['type' => 'info', 'msg' => "{$typeCounts['naked_url']} anchors use the raw URL as text — missed SEO opportunity.", 'fix' => 'Replace naked URL anchors with descriptive text.'];
        }
        if (count($duplicates) > 0) {
            $dupCount = count($duplicates);
            $issues[] = ['type' => 'info', 'msg' => "{$dupCount} duplicate anchor(s) found — same text linking to the same URL.", 'fix' => 'Consolidate duplicate links or vary anchor text.'];
        }
        $nofollowPct = $total > 0 ? (int) round(($nofollow / $total) * 100) : 0;
        if ($nofollowPct > 50 && $internal > 5) {
            $issues[] = ['type' => 'warning', 'msg' => "{$nofollowPct}% of links are nofollow — excessive nofollow on internal links wastes PageRank.", 'fix' => 'Remove nofollow from internal links unless specifically needed.'];
        }

        $textFreq = [];
        foreach ($anchors as $a) {
            if ($a['type'] === 'keyword') {
                $k = strtolower($a['text']);
                $textFreq[$k] = ($textFreq[$k] ?? 0) + 1;
            }
        }
        arsort($textFreq);
        $topAnchors = array_slice($textFreq, 0, 10, true);

        return response()->json([
            'url' => $url,
            'total' => $total,
            'internal' => $internal,
            'external' => $external,
            'follow' => $follow,
            'nofollow' => $nofollow,
            'typeCounts' => $typeCounts,
            'anchors' => $anchors,
            'issues' => $issues,
            'topAnchors' => (object) $topAnchors,
            'duplicateCount' => count($duplicates),
        ]);
    }
}
