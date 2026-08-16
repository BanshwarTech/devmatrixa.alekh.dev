<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Services\PageFetcher;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;

class FaqExtractorController extends Controller
{
    private const ALLOWED_TAGS = ['a', 'b', 'i', 'ul', 'ol', 'li', 'table', 'thead', 'tbody', 'tr', 'td', 'th', 'strong', 'em', 'u', 'br', 'p'];

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
            return response()->json(['url' => $url, 'faqs' => [], 'error' => 'Could not fetch URL']);
        }

        $faqs = [];

        // 1. Schema-based extraction (FAQPage JSON-LD)
        if (preg_match_all('/<script type="application\/ld\+json">(.*?)<\/script>/is', $html, $matches)) {
            foreach ($matches[1] as $raw) {
                $data = json_decode(trim($raw), true);
                if (! is_array($data)) {
                    continue;
                }
                $blocks = (isset($data['@graph']) && is_array($data['@graph'])) ? $data['@graph'] : [$data];
                foreach ($blocks as $block) {
                    if (! is_array($block)) {
                        continue;
                    }
                    if (($block['@type'] ?? null) === 'FAQPage' && isset($block['mainEntity']) && is_array($block['mainEntity'])) {
                        foreach ($block['mainEntity'] as $item) {
                            if (! is_array($item)) {
                                continue;
                            }
                            $q = $this->cleanQuestion((string) ($item['name'] ?? ''));
                            $aRaw = (string) ($item['acceptedAnswer']['text'] ?? '');
                            $faqs[] = ['question' => $q, 'answer' => $this->stripTagsAllowing($aRaw)];
                        }
                    }
                }
            }
        }

        // 2. HTML fallback (Elementor / accordion patterns)
        if (count($faqs) === 0) {
            $crawler = new Crawler($html);
            $selectors = ['.elementor-tab-title', '.accordion-header', "[itemprop='name']", '.faq-question'];

            foreach ($selectors as $sel) {
                try {
                    $nodes = $crawler->filter($sel);
                } catch (\Throwable) {
                    continue;
                }

                $nodes->each(function (Crawler $qEl) use (&$faqs) {
                    $qText = $this->cleanQuestion($qEl->text(''));
                    $sibling = $qEl->nextAll();
                    if ($sibling->count() === 0) {
                        return;
                    }
                    $firstSibling = $sibling->first();

                    $aHtml = '';
                    try {
                        $inner = $firstSibling->filter('.content, .body');
                        if ($inner->count() > 0) {
                            $aHtml = $inner->first()->html('');
                        } else {
                            $aHtml = $firstSibling->html('');
                        }
                    } catch (\Throwable) {
                        $aHtml = '';
                    }

                    if ($qText !== '' && $aHtml !== '') {
                        $faqs[] = ['question' => $qText, 'answer' => $this->stripTagsAllowing($aHtml)];
                    }
                });

                if (count($faqs) > 0) {
                    break;
                }
            }
        }

        // Clean answer whitespace and dedupe
        $seen = [];
        $clean = [];
        foreach ($faqs as $f) {
            $answer = preg_replace('/[\r\n]+/', ' ', $f['answer']);
            $answer = trim(preg_replace('/\s+/', ' ', $answer));
            $question = $f['question'];
            $key = $question.'||'.$answer;
            if (isset($seen[$key])) {
                continue;
            }
            if (mb_strlen($question) <= 1) {
                continue;
            }
            $seen[$key] = true;
            $clean[] = ['question' => $question, 'answer' => $answer];
        }

        return response()->json(['url' => $url, 'faqs' => $clean]);
    }

    private function cleanQuestion(string $q): string
    {
        $cleaned = preg_replace('/\s+/', ' ', $q);
        $cleaned = trim($cleaned);
        $cleaned = preg_replace('/[\s+\-?]+$/', '', $cleaned);

        return $cleaned.'?';
    }

    private function stripTagsAllowing(string $html): string
    {
        return preg_replace_callback('/<\/?([a-z][a-z0-9]*)\b[^>]*>/i', function ($m) {
            return in_array(strtolower($m[1]), self::ALLOWED_TAGS, true) ? $m[0] : '';
        }, $html);
    }
}
