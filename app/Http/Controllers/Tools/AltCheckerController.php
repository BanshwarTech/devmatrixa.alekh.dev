<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Services\PageFetcher;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;

class AltCheckerController extends Controller
{
    public function analyze(Request $request)
    {
        $raw = (string) $request->input('urls', '');
        $urls = collect(preg_split('/[\r\n,]+/', $raw))
            ->map(fn ($s) => trim($s))
            ->filter()
            ->values();

        if ($urls->isEmpty()) {
            return response()->json(['error' => 'Please paste at least one URL.'], 422);
        }

        $results = [];

        foreach ($urls->take(20) as $url) {
            if (! PageFetcher::isValidUrl($url)) {
                $results[$url] = ['issues' => [], 'error' => 'Invalid URL'];
                continue;
            }

            try {
                $page = PageFetcher::fetchPage($url);
                $crawler = new Crawler($page['html']);
                $issues = [];
                $total = 0;

                $crawler->filter('img')->each(function (Crawler $img) use (&$issues, &$total) {
                    $total++;
                    $alt = trim($img->attr('alt') ?? '');
                    if ($alt === '') {
                        $issues[] = [
                            'src' => $img->attr('src') ?? '',
                            'html' => $img->outerHtml(),
                        ];
                    }
                });

                $results[$url] = ['issues' => $issues, 'total' => $total];
            } catch (\Throwable) {
                $results[$url] = ['issues' => [], 'error' => 'Could not fetch URL'];
            }
        }

        return response()->json(['results' => $results]);
    }
}
