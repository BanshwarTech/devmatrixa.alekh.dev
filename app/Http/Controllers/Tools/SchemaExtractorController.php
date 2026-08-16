<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Services\PageFetcher;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;

class SchemaExtractorController extends Controller
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

        $crawler = new Crawler($html);
        $schemas = [];
        $typeCounts = [];

        $crawler->filter('script[type="application/ld+json"]')->each(function (Crawler $el) use (&$schemas, &$typeCounts) {
            $raw = trim($el->text(''));
            if ($raw === '') {
                return;
            }

            $decoded = json_decode($raw, true);
            if (! is_array($decoded)) {
                return;
            }

            $nodes = (isset($decoded['@graph']) && is_array($decoded['@graph'])) ? $decoded['@graph'] : [$decoded];

            foreach ($nodes as $node) {
                if (! is_array($node)) {
                    continue;
                }
                $type = $node['@type'] ?? 'Unknown';
                if (is_array($type)) {
                    $type = implode(', ', $type);
                }
                $type = (string) $type;

                $schemas[] = ['type' => $type, 'json' => $this->prettyJson($node)];
                $typeCounts[$type] = ($typeCounts[$type] ?? 0) + 1;
            }
        });

        return response()->json([
            'url' => $url,
            'total' => count($schemas),
            'typeCounts' => $typeCounts,
            'schemas' => $schemas,
        ]);
    }

    /**
     * Mimics JS JSON.stringify(node, null, 2) — PHP's JSON_PRETTY_PRINT uses
     * 4-space indentation, so we halve the leading whitespace on every line.
     */
    private function prettyJson(array $node): string
    {
        $json = json_encode($node, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        return preg_replace_callback('/^( +)/m', function ($m) {
            return str_repeat(' ', (int) (strlen($m[1]) / 2));
        }, $json);
    }
}
