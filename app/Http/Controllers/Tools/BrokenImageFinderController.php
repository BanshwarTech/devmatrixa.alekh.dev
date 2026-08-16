<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Services\PageFetcher;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BrokenImageFinderController extends Controller
{
    public function analyze(Request $request)
    {
        $url = (string) $request->input('url', '');

        if (! PageFetcher::isValidUrl($url)) {
            return response()->json(['error' => 'Invalid URL'], 422);
        }

        try {
            $page = PageFetcher::fetchPage($url);
        } catch (\Throwable) {
            return response()->json(['error' => 'Could not fetch the URL.'], 422);
        }

        $html = $page['html'];

        $srcs = [];
        if (preg_match_all('/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $html, $m)) {
            foreach ($m[1] as $src) {
                if (! str_starts_with($src, 'data:')) {
                    $srcs[$src] = true;
                }
            }
        }

        $resolved = array_slice(array_keys($srcs), 0, 50);
        $resolved = array_map(fn ($s) => PageFetcher::absUrl($s, $url), $resolved);

        $statuses = $this->checkImages($resolved);

        $images = [];
        foreach ($resolved as $i => $imgUrl) {
            $status = $statuses[$i] ?? 0;
            $images[] = ['url' => $imgUrl, 'status' => $status, 'broken' => $status === 0 || $status >= 400];
        }

        $broken = array_values(array_filter($images, fn ($i) => $i['broken']));
        $working = array_values(array_filter($images, fn ($i) => ! $i['broken']));

        return response()->json([
            'url' => $url,
            'total' => count($images),
            'broken' => count($broken),
            'working' => count($working),
            'images' => $images,
        ]);
    }

    /**
     * HEAD every image URL in parallel; any that error or come back >=400/405
     * are retried with a GET (some servers block lightweight HEAD probes).
     * Mirrors lib/fetchPage.ts's headOrGet(), just via Http::pool for concurrency.
     *
     * @param  string[]  $urls
     * @return array<int,int> status keyed by the same index as $urls
     */
    private function checkImages(array $urls): array
    {
        if (empty($urls)) {
            return [];
        }

        $statuses = [];
        $needsGet = [];

        $headResponses = Http::pool(function (Pool $pool) use ($urls) {
            foreach ($urls as $i => $imgUrl) {
                $pool->as((string) $i)
                    ->withHeaders(['User-Agent' => PageFetcher::USER_AGENT])
                    ->timeout(8)
                    ->withOptions(['allow_redirects' => true])
                    ->head($imgUrl);
            }
        });

        foreach ($urls as $i => $imgUrl) {
            $resp = $headResponses[(string) $i] ?? null;
            if ($resp instanceof Response) {
                $status = $resp->status();
                if ($status >= 400 || $status === 405) {
                    $needsGet[$i] = $imgUrl;
                } else {
                    $statuses[$i] = $status;
                }
            } else {
                // network error / timeout — same as headOrGet()'s catch -> status 0
                $statuses[$i] = 0;
            }
        }

        if (! empty($needsGet)) {
            $getResponses = Http::pool(function (Pool $pool) use ($needsGet) {
                foreach ($needsGet as $i => $imgUrl) {
                    $pool->as((string) $i)
                        ->withHeaders(['User-Agent' => PageFetcher::USER_AGENT])
                        ->timeout(8)
                        ->withOptions(['allow_redirects' => true])
                        ->get($imgUrl);
                }
            });

            foreach ($needsGet as $i => $imgUrl) {
                $resp = $getResponses[(string) $i] ?? null;
                $statuses[$i] = $resp instanceof Response ? $resp->status() : 0;
            }
        }

        return $statuses;
    }
}
