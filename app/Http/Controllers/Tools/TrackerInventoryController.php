<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Services\PageFetcher;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;

class TrackerInventoryController extends Controller
{
    /**
     * Known third-party tracker signatures, ported 1:1 from the Next.js
     * app/api/tracker-inventory/route.ts TRACKERS list.
     */
    private const TRACKERS = [
        ['name' => 'Google Analytics', 'category' => 'analytics', 'privacy' => 'high', 'patterns' => ['/google-analytics\.com$/', '/analytics\.google\.com$/']],
        ['name' => 'Google Tag Manager', 'category' => 'tag-manager', 'privacy' => 'high', 'patterns' => ['/googletagmanager\.com$/', '/^tagmanager\.google\.com$/']],
        ['name' => 'Google Ads', 'category' => 'advertising', 'privacy' => 'high', 'patterns' => ['/doubleclick\.net$/', '/googlesyndication\.com$/', '/googleadservices\.com$/', '/^adservice\.google\./']],
        ['name' => 'Facebook Pixel', 'category' => 'advertising', 'privacy' => 'high', 'patterns' => ['/connect\.facebook\.net$/', '/^facebook\.com$/', '/^www\.facebook\.com$/']],
        ['name' => 'TikTok Pixel', 'category' => 'advertising', 'privacy' => 'high', 'patterns' => ['/analytics\.tiktok\.com$/', '/tiktok\.com$/']],
        ['name' => 'LinkedIn Insight', 'category' => 'advertising', 'privacy' => 'high', 'patterns' => ['/^px\.ads\.linkedin\.com$/', '/snap\.licdn\.com$/']],
        ['name' => 'X / Twitter Ads', 'category' => 'advertising', 'privacy' => 'high', 'patterns' => ['/ads-twitter\.com$/', '/static\.ads-twitter\.com$/', '/analytics\.twitter\.com$/']],
        ['name' => 'Pinterest Tag', 'category' => 'advertising', 'privacy' => 'high', 'patterns' => ['/ct\.pinterest\.com$/', '/s\.pinimg\.com$/']],
        ['name' => 'Reddit Pixel', 'category' => 'advertising', 'privacy' => 'high', 'patterns' => ['/redditstatic\.com$/', '/events\.redditmedia\.com$/']],
        ['name' => 'Snap Pixel', 'category' => 'advertising', 'privacy' => 'high', 'patterns' => ['/sc-static\.net$/', '/tr\.snapchat\.com$/']],
        ['name' => 'Bing Ads', 'category' => 'advertising', 'privacy' => 'high', 'patterns' => ['/bat\.bing\.com$/', '/clarity\.ms$/']],
        ['name' => 'Criteo', 'category' => 'advertising', 'privacy' => 'high', 'patterns' => ['/criteo\.net$/', '/criteo\.com$/']],
        ['name' => 'Taboola', 'category' => 'advertising', 'privacy' => 'high', 'patterns' => ['/taboola\.com$/']],
        ['name' => 'Outbrain', 'category' => 'advertising', 'privacy' => 'high', 'patterns' => ['/outbrain\.com$/']],
        ['name' => 'Amazon Ads', 'category' => 'advertising', 'privacy' => 'high', 'patterns' => ['/amazon-adsystem\.com$/', '/adsystem\.amazon\./']],
        ['name' => 'AppNexus / Xandr', 'category' => 'advertising', 'privacy' => 'high', 'patterns' => ['/adnxs\.com$/']],
        ['name' => 'Adobe Analytics', 'category' => 'analytics', 'privacy' => 'high', 'patterns' => ['/demdex\.net$/', '/omtrdc\.net$/', '/^.+\.sc\.omtrdc\.net$/', '/everesttech\.net$/']],
        ['name' => 'Mixpanel', 'category' => 'analytics', 'privacy' => 'high', 'patterns' => ['/mixpanel\.com$/', '/api\.mixpanel\.com$/']],
        ['name' => 'Segment', 'category' => 'analytics', 'privacy' => 'high', 'patterns' => ['/segment\.com$/', '/segment\.io$/', '/cdn\.segment\.com$/']],
        ['name' => 'Amplitude', 'category' => 'analytics', 'privacy' => 'high', 'patterns' => ['/amplitude\.com$/', '/api\.amplitude\.com$/']],
        ['name' => 'Heap', 'category' => 'analytics', 'privacy' => 'high', 'patterns' => ['/heap\.io$/', '/heapanalytics\.com$/']],
        ['name' => 'PostHog', 'category' => 'analytics', 'privacy' => 'medium', 'patterns' => ['/posthog\.com$/', '/^.+\.posthog\.com$/']],
        ['name' => 'Plausible', 'category' => 'analytics', 'privacy' => 'low', 'patterns' => ['/plausible\.io$/']],
        ['name' => 'Fathom', 'category' => 'analytics', 'privacy' => 'low', 'patterns' => ['/usefathom\.com$/', '/cdn\.usefathom\.com$/']],
        ['name' => 'Simple Analytics', 'category' => 'analytics', 'privacy' => 'low', 'patterns' => ['/simpleanalytics\.io$/', '/simpleanalyticscdn\.com$/']],
        ['name' => 'Cloudflare Web Analytics', 'category' => 'analytics', 'privacy' => 'low', 'patterns' => ['/cloudflareinsights\.com$/', '/static\.cloudflareinsights\.com$/']],
        ['name' => 'Matomo', 'category' => 'analytics', 'privacy' => 'low', 'patterns' => ['/matomo\.cloud$/']],
        ['name' => 'StatCounter', 'category' => 'analytics', 'privacy' => 'medium', 'patterns' => ['/statcounter\.com$/']],
        ['name' => 'Hotjar', 'category' => 'session-replay', 'privacy' => 'high', 'patterns' => ['/hotjar\.com$/', '/static\.hotjar\.com$/']],
        ['name' => 'FullStory', 'category' => 'session-replay', 'privacy' => 'high', 'patterns' => ['/fullstory\.com$/', '/fs\.fullstory\.com$/']],
        ['name' => 'Microsoft Clarity', 'category' => 'session-replay', 'privacy' => 'high', 'patterns' => ['/clarity\.ms$/', '/c\.clarity\.ms$/']],
        ['name' => 'LogRocket', 'category' => 'session-replay', 'privacy' => 'high', 'patterns' => ['/logrocket\.com$/', '/logrocket\.io$/']],
        ['name' => 'Mouseflow', 'category' => 'session-replay', 'privacy' => 'high', 'patterns' => ['/mouseflow\.com$/']],
        ['name' => 'Smartlook', 'category' => 'session-replay', 'privacy' => 'high', 'patterns' => ['/smartlook\.com$/']],
        ['name' => 'Optimizely', 'category' => 'ab-testing', 'privacy' => 'medium', 'patterns' => ['/optimizely\.com$/', '/cdn\.optimizely\.com$/']],
        ['name' => 'VWO', 'category' => 'ab-testing', 'privacy' => 'medium', 'patterns' => ['/visualwebsiteoptimizer\.com$/', '/vwo\.com$/']],
        ['name' => 'Google Optimize', 'category' => 'ab-testing', 'privacy' => 'medium', 'patterns' => ['/^optimize\.google\.com$/']],
        ['name' => 'Twitter Widget', 'category' => 'social', 'privacy' => 'medium', 'patterns' => ['/platform\.twitter\.com$/']],
        ['name' => 'Facebook SDK', 'category' => 'social', 'privacy' => 'high', 'patterns' => ['/connect\.facebook\.net$/']],
        ['name' => 'LinkedIn Widget', 'category' => 'social', 'privacy' => 'medium', 'patterns' => ['/platform\.linkedin\.com$/']],
        ['name' => 'YouTube', 'category' => 'video', 'privacy' => 'medium', 'patterns' => ['/^youtube\.com$/', '/^www\.youtube\.com$/', '/youtu\.be$/', '/youtube-nocookie\.com$/', '/ytimg\.com$/']],
        ['name' => 'Vimeo', 'category' => 'video', 'privacy' => 'medium', 'patterns' => ['/vimeo\.com$/', '/player\.vimeo\.com$/', '/vimeocdn\.com$/']],
        ['name' => 'Wistia', 'category' => 'video', 'privacy' => 'medium', 'patterns' => ['/wistia\.com$/', '/wistia\.net$/']],
        ['name' => 'Intercom', 'category' => 'chat', 'privacy' => 'high', 'patterns' => ['/intercom\.io$/', '/intercom\.com$/', '/intercomcdn\.com$/', '/widget\.intercom\.io$/']],
        ['name' => 'Drift', 'category' => 'chat', 'privacy' => 'high', 'patterns' => ['/drift\.com$/', '/js\.driftt\.com$/']],
        ['name' => 'Zendesk Chat', 'category' => 'chat', 'privacy' => 'high', 'patterns' => ['/zendesk\.com$/', '/zdassets\.com$/', '/zopim\.com$/']],
        ['name' => 'Tawk.to', 'category' => 'chat', 'privacy' => 'high', 'patterns' => ['/tawk\.to$/', '/embed\.tawk\.to$/']],
        ['name' => 'Crisp', 'category' => 'chat', 'privacy' => 'high', 'patterns' => ['/crisp\.chat$/', '/client\.crisp\.chat$/']],
        ['name' => 'HubSpot', 'category' => 'chat', 'privacy' => 'high', 'patterns' => ['/hubspot\.com$/', '/hs-analytics\.net$/', '/hs-scripts\.com$/', '/hsforms\.net$/', '/hubspot\.net$/']],
        ['name' => 'LiveChat', 'category' => 'chat', 'privacy' => 'high', 'patterns' => ['/livechatinc\.com$/', '/livechat-static\.com$/']],
        ['name' => 'Freshchat', 'category' => 'chat', 'privacy' => 'high', 'patterns' => ['/freshchat\.com$/', '/wchat\.freshchat\.com$/']],
        ['name' => 'Google Fonts', 'category' => 'fonts', 'privacy' => 'low', 'patterns' => ['/fonts\.googleapis\.com$/', '/fonts\.gstatic\.com$/']],
        ['name' => 'Adobe Fonts (Typekit)', 'category' => 'fonts', 'privacy' => 'low', 'patterns' => ['/use\.typekit\.net$/', '/p\.typekit\.net$/']],
        ['name' => 'Fontawesome', 'category' => 'fonts', 'privacy' => 'low', 'patterns' => ['/use\.fontawesome\.com$/', '/kit\.fontawesome\.com$/', '/pro\.fontawesome\.com$/']],
        ['name' => 'jsDelivr', 'category' => 'cdn', 'privacy' => 'low', 'patterns' => ['/cdn\.jsdelivr\.net$/', '/jsdelivr\.com$/']],
        ['name' => 'cdnjs', 'category' => 'cdn', 'privacy' => 'low', 'patterns' => ['/cdnjs\.cloudflare\.com$/']],
        ['name' => 'unpkg', 'category' => 'cdn', 'privacy' => 'low', 'patterns' => ['/unpkg\.com$/']],
        ['name' => 'jQuery CDN', 'category' => 'cdn', 'privacy' => 'low', 'patterns' => ['/code\.jquery\.com$/']],
        ['name' => 'Google CDN', 'category' => 'cdn', 'privacy' => 'low', 'patterns' => ['/ajax\.googleapis\.com$/', '/www\.gstatic\.com$/']],
        ['name' => 'Cloudflare CDN', 'category' => 'cdn', 'privacy' => 'low', 'patterns' => ['/cdnjs\.cloudflare\.com$/', '/cf\.bstatic\.com$/']],
        ['name' => 'Google Maps', 'category' => 'maps', 'privacy' => 'medium', 'patterns' => ['/maps\.googleapis\.com$/', '/maps\.google\.com$/']],
        ['name' => 'Mapbox', 'category' => 'maps', 'privacy' => 'medium', 'patterns' => ['/mapbox\.com$/', '/api\.mapbox\.com$/']],
        ['name' => 'Stripe', 'category' => 'payment', 'privacy' => 'low', 'patterns' => ['/stripe\.com$/', '/js\.stripe\.com$/', '/m\.stripe\.com$/']],
        ['name' => 'PayPal', 'category' => 'payment', 'privacy' => 'low', 'patterns' => ['/paypal\.com$/', '/paypalobjects\.com$/']],
        ['name' => 'Razorpay', 'category' => 'payment', 'privacy' => 'low', 'patterns' => ['/razorpay\.com$/', '/checkout\.razorpay\.com$/']],
        ['name' => 'reCAPTCHA', 'category' => 'captcha', 'privacy' => 'medium', 'patterns' => ['/recaptcha\.net$/', '#www\.google\.com/recaptcha#', '#www\.gstatic\.com/recaptcha#']],
        ['name' => 'hCaptcha', 'category' => 'captcha', 'privacy' => 'low', 'patterns' => ['/hcaptcha\.com$/', '/js\.hcaptcha\.com$/']],
        ['name' => 'Cloudflare Turnstile', 'category' => 'captcha', 'privacy' => 'low', 'patterns' => ['/challenges\.cloudflare\.com$/']],
        ['name' => 'Sentry', 'category' => 'error-tracking', 'privacy' => 'low', 'patterns' => ['/sentry\.io$/', '/^.+\.ingest\.sentry\.io$/']],
        ['name' => 'Bugsnag', 'category' => 'error-tracking', 'privacy' => 'low', 'patterns' => ['/bugsnag\.com$/', '/notify\.bugsnag\.com$/']],
        ['name' => 'Rollbar', 'category' => 'error-tracking', 'privacy' => 'low', 'patterns' => ['/rollbar\.com$/', '/cdn\.rollbar\.com$/']],
        ['name' => 'Datadog RUM', 'category' => 'error-tracking', 'privacy' => 'medium', 'patterns' => ['/datadoghq\.com$/', '/browser-intake-datadoghq\.com$/']],
    ];

    private const CATEGORIES = [
        'analytics', 'advertising', 'social', 'tag-manager', 'chat',
        'video', 'fonts', 'cdn', 'maps', 'payment', 'captcha',
        'error-tracking', 'ab-testing', 'session-replay', 'other',
    ];

    public function analyze(Request $request)
    {
        $url = (string) $request->input('url', '');

        if (! PageFetcher::isValidUrl($url)) {
            return response()->json(['error' => 'Invalid URL'], 422);
        }

        try {
            $page = PageFetcher::fetchPage($url, 15000);
        } catch (\Throwable) {
            return response()->json(['error' => 'Could not fetch the URL.'], 422);
        }

        $html = $page['html'];
        $baseHostRaw = strtolower((string) (parse_url($url, PHP_URL_HOST) ?? ''));
        $baseHost = preg_replace('/^www\./', '', $baseHostRaw);

        $crawler = new Crawler($html);
        $resources = [];

        $push = function (?string $raw, string $type) use (&$resources, $url, $baseHost) {
            if (! $raw) {
                return;
            }
            $href = trim($raw);
            if (
                $href === ''
                || str_starts_with($href, 'data:')
                || str_starts_with($href, 'blob:')
                || str_starts_with($href, 'javascript:')
                || str_starts_with($href, '#')
            ) {
                return;
            }
            try {
                $abs = PageFetcher::absUrl($href, $url);
                $absHost = strtolower((string) (parse_url($abs, PHP_URL_HOST) ?? ''));
                if ($absHost === '') {
                    return;
                }
                $h = preg_replace('/^www\./', '', $absHost);
                if ($h === $baseHost) {
                    return;
                }
                $resources[] = ['url' => $abs, 'type' => $type, 'host' => $absHost];
            } catch (\Throwable) {
                // ignore malformed URLs
            }
        };

        $crawler->filter('script[src]')->each(fn (Crawler $n) => $push($n->attr('src'), 'script'));

        $crawler->filter('link[href]')->each(function (Crawler $n) use ($push) {
            $rel = strtolower($n->attr('rel') ?? '');
            $type = str_contains($rel, 'stylesheet')
                ? 'stylesheet'
                : ((str_contains($rel, 'preconnect') || str_contains($rel, 'dns-prefetch'))
                    ? 'preconnect'
                    : (str_contains($rel, 'preload') ? 'preload' : 'link'));
            $push($n->attr('href'), $type);
        });

        $crawler->filter('iframe[src]')->each(fn (Crawler $n) => $push($n->attr('src'), 'iframe'));
        $crawler->filter('img[src]')->each(fn (Crawler $n) => $push($n->attr('src'), 'image'));

        $crawler->filter('img[srcset]')->each(function (Crawler $n) use ($push) {
            $ss = explode(',', $n->attr('srcset') ?? '');
            foreach ($ss as $part) {
                $bits = preg_split('/\s+/', trim($part));
                $u = $bits[0] ?? '';
                if ($u !== '') {
                    $push($u, 'image');
                }
            }
        });

        $crawler->filter('video[src], audio[src], source[src]')->each(fn (Crawler $n) => $push($n->attr('src'), 'media'));

        $byHost = [];
        foreach ($resources as $r) {
            if (isset($byHost[$r['host']])) {
                $byHost[$r['host']]['count']++;
                $byHost[$r['host']]['types'][$r['type']] = ($byHost[$r['host']]['types'][$r['type']] ?? 0) + 1;
            } else {
                $tracker = $this->matchTracker($r['host']);
                $byHost[$r['host']] = [
                    'host' => $r['host'],
                    'count' => 1,
                    'types' => [$r['type'] => 1],
                    'tracker' => $tracker ? ['name' => $tracker['name'], 'category' => $tracker['category'], 'privacy' => $tracker['privacy']] : null,
                    'category' => $tracker ? $tracker['category'] : 'other',
                    'privacy' => $tracker ? $tracker['privacy'] : 'unknown',
                    'sample' => $r['url'],
                ];
            }
        }

        $domains = array_values($byHost);
        usort($domains, fn ($a, $b) => $b['count'] <=> $a['count']);

        $byCategory = array_fill_keys(self::CATEGORIES, 0);
        $highPrivacy = 0;
        foreach ($domains as $d) {
            $byCategory[$d['category']]++;
            if ($d['privacy'] === 'high') {
                $highPrivacy++;
            }
        }

        $knownTrackers = count(array_filter($domains, fn ($d) => $d['tracker'] !== null));

        return response()->json([
            'url' => $url,
            'baseHost' => $baseHost,
            'totalRequests' => count($resources),
            'uniqueDomains' => count($domains),
            'knownTrackers' => $knownTrackers,
            'highPrivacyCount' => $highPrivacy,
            'byCategory' => $byCategory,
            'domains' => $domains,
        ]);
    }

    private function matchTracker(string $host): ?array
    {
        foreach (self::TRACKERS as $t) {
            foreach ($t['patterns'] as $p) {
                if (@preg_match($p, $host)) {
                    return $t;
                }
            }
        }

        return null;
    }
}
