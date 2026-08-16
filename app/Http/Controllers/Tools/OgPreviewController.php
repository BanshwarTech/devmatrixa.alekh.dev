<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Services\PageFetcher;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;

class OgPreviewController extends Controller
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
            return response()->json(['error' => 'Could not fetch the URL. Check if it is publicly accessible.'], 422);
        }

        $html = $page['html'];

        $scheme = parse_url($url, PHP_URL_SCHEME) ?: 'https';
        $host = parse_url($url, PHP_URL_HOST) ?: '';
        $baseUrl = "{$scheme}://{$host}";
        $domain = $host;

        // Do NOT wrap in Masterminds\HTML5 — it puts elements in the XHTML
        // namespace and breaks Crawler's CSS selectors. Plain Crawler works.
        $crawler = new Crawler($html);

        $og = [];
        $crawler->filter('meta[property^="og:"]')->each(function (Crawler $node) use (&$og) {
            $prop = $node->attr('property');
            $content = $node->attr('content');
            if ($prop && $content !== null && $content !== '') {
                $og[substr($prop, 3)] = $content;
            }
        });

        $tw = [];
        $crawler->filter('meta[name^="twitter:"]')->each(function (Crawler $node) use (&$tw) {
            $name = $node->attr('name');
            $content = $node->attr('content');
            if ($name && $content !== null && $content !== '') {
                $tw[substr($name, 8)] = $content;
            }
        });

        $pageTitle = '';
        $titleNodes = $crawler->filter('title');
        if ($titleNodes->count() > 0) {
            $pageTitle = trim($titleNodes->first()->text());
        }

        $pageDesc = '';
        $descNodes = $crawler->filter('meta[name="description"]');
        if ($descNodes->count() > 0) {
            $pageDesc = $descNodes->first()->attr('content') ?? '';
        }

        $faviconHref = null;
        foreach (['link[rel="icon"]', 'link[rel="shortcut icon"]'] as $sel) {
            $nodes = $crawler->filter($sel);
            if ($nodes->count() > 0) {
                $faviconHref = $nodes->first()->attr('href');
                break;
            }
        }
        $favicon = $faviconHref ? PageFetcher::absUrl($faviconHref, $baseUrl) : "{$baseUrl}/favicon.ico";

        $ogImage = ! empty($og['image']) ? PageFetcher::absUrl($og['image'], $baseUrl) : null;
        $twImage = ! empty($tw['image']) ? PageFetcher::absUrl($tw['image'], $baseUrl) : $ogImage;

        return response()->json([
            'url' => $url,
            'domain' => $domain,
            'favicon' => $favicon,
            'page' => ['title' => $pageTitle, 'description' => $pageDesc],
            'og' => [
                'title' => $og['title'] ?? $pageTitle,
                'description' => $og['description'] ?? $pageDesc,
                'image' => $ogImage,
                'url' => $og['url'] ?? $url,
                'type' => $og['type'] ?? 'website',
                'site_name' => $og['site_name'] ?? $domain,
            ],
            'twitter' => [
                'card' => $tw['card'] ?? 'summary_large_image',
                'title' => $tw['title'] ?? ($og['title'] ?? $pageTitle),
                'description' => $tw['description'] ?? ($og['description'] ?? $pageDesc),
                'image' => $twImage,
                'creator' => $tw['creator'] ?? null,
                'site' => $tw['site'] ?? null,
            ],
        ]);
    }
}
