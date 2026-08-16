<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Services\PageFetcher;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;

class SeoAnalyzerController extends Controller
{
    public function analyze(Request $request)
    {
        $url = (string) $request->input('url', '');

        if ($url === '' || ! preg_match('/^https?:\/\/.+/i', $url)) {
            return response()->json(['error' => 'Please provide a valid URL.'], 422);
        }

        try {
            $page = PageFetcher::fetchPage($url);
            $html = $page['html'];
            $loadTime = $page['loadTime'];
        } catch (\Throwable) {
            return response()->json(['error' => 'Could not fetch the URL. Please check it is publicly accessible.'], 422);
        }

        if (! $html) {
            return response()->json(['error' => 'Could not fetch the URL. Please check it is publicly accessible.'], 422);
        }

        $pageSize = round((strlen($html) / 1024) * 10) / 10;
        $crawler = new Crawler($html);

        $title = $this->text($crawler, 'title');
        $metaDesc = $this->attr($crawler, 'meta[name="description"]', 'content');
        $metaRobots = $this->attr($crawler, 'meta[name="robots"]', 'content');
        $canonical = $this->attr($crawler, 'link[rel="canonical"]', 'href');
        $viewport = $this->attr($crawler, 'meta[name="viewport"]', 'content');

        $charset = $this->attr($crawler, 'meta[charset]', 'charset');
        if (! $charset) {
            $httpEquiv = $this->attr($crawler, 'meta[http-equiv="Content-Type"]', 'content');
            if ($httpEquiv && preg_match('/charset=([^\s;]+)/i', $httpEquiv, $m)) {
                $charset = $m[1];
            }
        }

        $langAttr = $this->attr($crawler, 'html', 'lang');
        $hasFavicon = $crawler->filter('link[rel="icon"], link[rel="shortcut icon"], link[rel="apple-touch-icon"]')->count() > 0;

        $og = [];
        $crawler->filter('meta[property^="og:"]')->each(function (Crawler $n) use (&$og) {
            $p = $n->attr('property');
            $c = $n->attr('content');
            if ($p && $c !== null) {
                $og[$p] = $c;
            }
        });

        $twitter = [];
        $crawler->filter('meta[name^="twitter:"]')->each(function (Crawler $n) use (&$twitter) {
            $name = $n->attr('name');
            $c = $n->attr('content');
            if ($name && $c !== null) {
                $twitter[$name] = $c;
            }
        });

        $schemaTypes = [];
        $crawler->filter('script[type="application/ld+json"]')->each(function (Crawler $n) use (&$schemaTypes) {
            try {
                $json = json_decode(trim($n->text('', false)), true);
                if (! is_array($json)) {
                    return;
                }
                if (! empty($json['@type'])) {
                    $schemaTypes[] = is_array($json['@type']) ? implode(', ', $json['@type']) : $json['@type'];
                } elseif (! empty($json['@graph']) && is_array($json['@graph'])) {
                    foreach ($json['@graph'] as $node) {
                        if (! empty($node['@type'])) {
                            $schemaTypes[] = $node['@type'];
                        }
                    }
                }
            } catch (\Throwable) {
                // ignore malformed JSON-LD blocks
            }
        });
        $hasSchema = count($schemaTypes) > 0;

        $headingTexts = function (string $sel) use ($crawler) {
            $out = [];
            $crawler->filter($sel)->each(function (Crawler $n) use (&$out) {
                $out[] = trim(preg_replace('/\s+/', ' ', $n->text('', false)));
            });

            return $out;
        };

        $h1 = $headingTexts('h1');
        $h2 = $headingTexts('h2');
        $h3 = $headingTexts('h3');

        $totalImages = 0;
        $missingAlt = 0;
        $missingAltImages = [];
        $crawler->filter('img')->each(function (Crawler $n) use (&$totalImages, &$missingAlt, &$missingAltImages) {
            $totalImages++;
            $alt = trim($n->attr('alt') ?? '');
            if ($alt === '') {
                $missingAlt++;
                $src = $n->attr('src');
                if ($src && count($missingAltImages) < 10) {
                    $missingAltImages[] = $src;
                }
            }
        });

        $bodyText = preg_replace('/\s+/', ' ', $this->text($crawler, 'body'));
        $wordCount = count(array_filter(preg_split('/\s+/', trim($bodyText))));

        $internalLinks = 0;
        $externalLinks = 0;
        $parsedHost = parse_url($url, PHP_URL_HOST) ?: '';

        $crawler->filter('a[href]')->each(function (Crawler $n) use (&$internalLinks, &$externalLinks, $parsedHost) {
            $href = $n->attr('href') ?? '';
            if (str_starts_with($href, 'http')) {
                $linkHost = parse_url($href, PHP_URL_HOST);
                if ($linkHost === $parsedHost) {
                    $internalLinks++;
                } else {
                    $externalLinks++;
                }
            } else {
                $internalLinks++;
            }
        });

        $isHttps = str_starts_with(strtolower($url), 'https://');

        $score = 100;
        $issues = [];
        $passes = [];

        if (! $isHttps) {
            $score -= 10;
            $issues[] = ['label' => 'Not HTTPS', 'msg' => 'Page is served over plain HTTP — not secure', 'fix' => "Install an SSL certificate and redirect all HTTP traffic to HTTPS. Most hosts offer free SSL via Let's Encrypt."];
        } else {
            $passes[] = 'Page is served over HTTPS (secure)';
        }

        if (! $title) {
            $score -= 15;
            $issues[] = ['label' => 'Title tag missing', 'msg' => 'No <title> tag found on this page', 'fix' => 'Add a <title> tag inside <head>. Write 50–60 characters describing the page with your primary keyword near the beginning.'];
        } elseif (mb_strlen($title) < 30) {
            $score -= 7;
            $issues[] = ['label' => 'Title too short', 'msg' => mb_strlen($title).' chars — recommended 50–60 characters', 'fix' => 'Expand your title to 50–60 characters. Include your primary keyword and brand name. Current title: "'.$title.'"'];
        } elseif (mb_strlen($title) > 70) {
            $score -= 5;
            $issues[] = ['label' => 'Title too long', 'msg' => mb_strlen($title).' chars — Google truncates above ~60 characters in SERPs', 'fix' => 'Trim your title to under 60 characters. Current: "'.mb_substr($title, 0, 60).'…"'];
        } else {
            $passes[] = 'Title tag is optimal ('.mb_strlen($title).' chars)';
        }

        if (! $metaDesc) {
            $score -= 10;
            $issues[] = ['label' => 'Meta description missing', 'msg' => 'No <meta name="description"> found', 'fix' => 'Add <meta name="description" content="…"> inside <head>. 120–160 characters with your target keywords and a CTA.'];
        } elseif (mb_strlen($metaDesc) < 70) {
            $score -= 5;
            $issues[] = ['label' => 'Meta description too short', 'msg' => mb_strlen($metaDesc).' chars — recommended 120–160 characters', 'fix' => 'Expand your meta description to 120–160 characters. Include a clear value proposition and your main keyword.'];
        } elseif (mb_strlen($metaDesc) > 170) {
            $score -= 4;
            $issues[] = ['label' => 'Meta description too long', 'msg' => mb_strlen($metaDesc).' chars — Google truncates above ~160 characters', 'fix' => 'Trim your meta description to under 160 characters.'];
        } else {
            $passes[] = 'Meta description is optimal ('.mb_strlen($metaDesc).' chars)';
        }

        if (count($h1) === 0) {
            $score -= 10;
            $issues[] = ['label' => 'H1 tag missing', 'msg' => 'No H1 tag found on the page', 'fix' => 'Add exactly one <h1> tag with your primary keyword.'];
        } elseif (count($h1) > 1) {
            $score -= 5;
            $issues[] = ['label' => 'Multiple H1 tags', 'msg' => count($h1).' H1 tags found — should be exactly 1', 'fix' => 'Keep only one H1. Convert others to H2 or appropriate levels.'];
        } else {
            $h1Text = $h1[0];
            $passes[] = 'Single H1: "'.mb_substr($h1Text, 0, 55).(mb_strlen($h1Text) > 55 ? '…' : '').'"';
        }

        if (! $canonical) {
            $score -= 5;
            $issues[] = ['label' => 'Canonical URL missing', 'msg' => 'No <link rel="canonical"> tag found', 'fix' => 'Add <link rel="canonical" href="'.$url.'"> inside <head>.'];
        } else {
            $passes[] = 'Canonical URL is set';
        }

        if (! $viewport) {
            $score -= 5;
            $issues[] = ['label' => 'Viewport meta missing', 'msg' => 'No viewport meta tag — page may not be mobile-friendly', 'fix' => 'Add <meta name="viewport" content="width=device-width, initial-scale=1">.'];
        } else {
            $passes[] = 'Viewport meta tag is present';
        }

        if (empty($og['og:title']) || empty($og['og:description'])) {
            $score -= 5;
            $issues[] = ['label' => 'Open Graph tags incomplete', 'msg' => 'og:title or og:description is missing', 'fix' => 'Add og:title, og:description, og:image (1200×630), and og:url.'];
        } else {
            $passes[] = 'Open Graph title and description are set';
        }

        if (empty($og['og:image'])) {
            $score -= 3;
            $issues[] = ['label' => 'OG image missing', 'msg' => 'No og:image tag — social shares will show no preview image', 'fix' => 'Add <meta property="og:image" content="…"> with a 1200×630px image.'];
        } else {
            $passes[] = 'OG image is set';
        }

        if ($missingAlt > 0) {
            $score -= min(10, $missingAlt * 2);
            $issues[] = ['label' => 'Images missing ALT text', 'msg' => $missingAlt.' image'.($missingAlt > 1 ? 's' : '').' missing alt attribute', 'fix' => 'Add descriptive alt attributes to all images.'];
        } elseif ($totalImages > 0) {
            $passes[] = "All {$totalImages} images have ALT text";
        }

        if (str_contains(strtolower($metaRobots), 'noindex')) {
            $score -= 20;
            $issues[] = ['label' => 'Noindex detected', 'msg' => 'Page is set to noindex — search engines will not index it', 'fix' => 'Remove "noindex" from your robots meta tag.'];
        } else {
            $passes[] = 'Page is indexable (no noindex directive)';
        }

        if (! $hasFavicon) {
            $score -= 2;
            $issues[] = ['label' => 'Favicon missing', 'msg' => 'No favicon link tag detected', 'fix' => 'Add <link rel="icon" href="/favicon.ico">.'];
        } else {
            $passes[] = 'Favicon is set';
        }

        if (! $langAttr) {
            $score -= 3;
            $issues[] = ['label' => 'HTML lang attribute missing', 'msg' => 'The <html> tag has no lang attribute', 'fix' => 'Add lang="en" (or appropriate) to <html>.'];
        } else {
            $passes[] = "HTML lang attribute set to \"{$langAttr}\"";
        }

        if ($wordCount < 300) {
            $score -= 5;
            $issues[] = ['label' => 'Thin content', 'msg' => "~{$wordCount} words detected — thin pages often rank poorly", 'fix' => 'Aim for at least 300–500 words for landing pages, 800–1500 for blog posts.'];
        } else {
            $passes[] = "Good content volume (~{$wordCount} words)";
        }

        if (! $hasSchema) {
            $issues[] = ['label' => 'No structured data (Schema)', 'msg' => 'No JSON-LD schema markup found on the page', 'fix' => 'Add JSON-LD structured data relevant to your page type (Article, Product, FAQPage, etc.).'];
        } else {
            $passes[] = 'Structured data detected: '.implode(', ', array_values(array_unique($schemaTypes)));
        }

        if ($loadTime > 5000) {
            $score -= 8;
            $issues[] = ['label' => 'Very slow page', 'msg' => "Page took {$loadTime}ms (>5s is very poor)", 'fix' => 'Compress and lazy-load images, minify and defer CSS/JS, enable caching and use a CDN.'];
        } elseif ($loadTime > 3000) {
            $score -= 4;
            $issues[] = ['label' => 'Slow page load', 'msg' => "Page took {$loadTime}ms (2–3s is the target)", 'fix' => 'Compress images (WebP), enable browser caching, minify CSS/JS, defer non-critical scripts.'];
        } else {
            $passes[] = "Page loaded in {$loadTime}ms (good)";
        }

        $score = max(0, $score);

        return response()->json([
            'url' => $url,
            'isHttps' => $isHttps,
            'title' => $title,
            'metaDesc' => $metaDesc,
            'metaRobots' => $metaRobots,
            'canonical' => $canonical,
            'viewport' => $viewport,
            'charset' => $charset,
            'langAttr' => $langAttr,
            'hasFavicon' => $hasFavicon,
            'og' => (object) $og,
            'twitter' => (object) $twitter,
            'hasSchema' => $hasSchema,
            'schemaTypes' => array_values(array_unique($schemaTypes)),
            'h1' => $h1,
            'h2' => $h2,
            'h3' => $h3,
            'totalImages' => $totalImages,
            'missingAlt' => $missingAlt,
            'missingAltImages' => $missingAltImages,
            'wordCount' => $wordCount,
            'pageSize' => $pageSize,
            'internalLinks' => $internalLinks,
            'externalLinks' => $externalLinks,
            'score' => $score,
            'issues' => $issues,
            'passes' => $passes,
            'loadTime' => $loadTime,
        ]);
    }

    private function text(Crawler $crawler, string $selector): string
    {
        $node = $crawler->filter($selector);

        return $node->count() > 0 ? trim($node->first()->text('', false)) : '';
    }

    private function attr(Crawler $crawler, string $selector, string $attr): string
    {
        $node = $crawler->filter($selector);
        if ($node->count() === 0) {
            return '';
        }

        return trim($node->first()->attr($attr) ?? '');
    }
}
