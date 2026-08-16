<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Services\PageFetcher;
use Illuminate\Http\Request;

class HeadingCheckerController extends Controller
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
            return response()->json(['error' => 'Could not fetch the URL. Check if it is publicly accessible.'], 422);
        }

        $headings = [];
        preg_match_all('/<h([1-6])(?:[^>]*)>(.*?)<\/h\1>/is', $html, $matches, PREG_SET_ORDER);
        foreach ($matches as $m) {
            $level = (int) $m[1];
            $text = preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($m[2]), ENT_QUOTES | ENT_HTML5));
            $text = trim($text);
            $headings[] = ['level' => $level, 'text' => $text !== '' ? $text : '(empty)'];
        }

        $counts = [];
        for ($l = 1; $l <= 6; $l++) {
            $counts['h'.$l] = count(array_filter($headings, fn ($h) => $h['level'] === $l));
        }

        $issues = [];

        if ($counts['h1'] === 0) {
            $issues[] = ['type' => 'error', 'msg' => 'No H1 found — every page should have exactly one H1.', 'fix' => 'Add one H1 tag at the top describing the main topic.'];
        } elseif ($counts['h1'] > 1) {
            $issues[] = ['type' => 'warning', 'msg' => "{$counts['h1']} H1 tags found — should be exactly one per page.", 'fix' => 'Keep only the first H1; change others to H2 or appropriate level.'];
        }

        $prevLevel = 0;
        $skippedPairs = [];
        foreach ($headings as $idx => $h) {
            if ($h['text'] === '(empty)') {
                $issues[] = ['type' => 'warning', 'msg' => 'Empty H'.$h['level']." at position #".($idx + 1).'.', 'fix' => 'Either remove the empty <h'.$h['level'].'> or add meaningful text.'];
            }
            if ($prevLevel > 0 && $h['level'] > $prevLevel + 1) {
                $pair = "H{$prevLevel}→H{$h['level']}";
                if (! isset($skippedPairs[$pair])) {
                    $skippedPairs[$pair] = true;
                    $issues[] = ['type' => 'warning', 'msg' => "Level skipped: H{$prevLevel} → H{$h['level']} (H".($prevLevel + 1).' is missing).', 'fix' => "Change H{$h['level']} to H".($prevLevel + 1).", or insert an H".($prevLevel + 1).' between them.'];
                }
            }
            if ($h['text'] !== '(empty)' && mb_strlen($h['text']) > 70) {
                $issues[] = ['type' => 'info', 'msg' => "H{$h['level']} is long (".mb_strlen($h['text']).' chars): "'.mb_substr($h['text'], 0, 50).'…"', 'fix' => 'Shorten to under 70 characters. Move detail to body text.'];
            }
            $prevLevel = $h['level'];
        }

        $prevSuggested = 0;
        $suggested = [];
        foreach ($headings as $h) {
            if ($prevSuggested === 0) {
                $sLevel = 1;
            } elseif ($h['level'] <= $prevSuggested + 1) {
                $sLevel = $h['level'];
            } else {
                $sLevel = $prevSuggested + 1;
            }
            $prevSuggested = $sLevel;
            $suggested[] = ['level' => $h['level'], 'text' => $h['text'], 'suggestedLevel' => $sLevel, 'changed' => $sLevel !== $h['level']];
        }

        $hasChanges = count(array_filter($suggested, fn ($s) => $s['changed'])) > 0;

        return response()->json([
            'url' => $url,
            'total' => count($headings),
            'counts' => $counts,
            'headings' => $headings,
            'issues' => $issues,
            'suggested' => $suggested,
            'hasChanges' => $hasChanges,
        ]);
    }
}
