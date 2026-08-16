<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Services\PageFetcher;
use Illuminate\Http\Request;

class TechStackDetectorController extends Controller
{
    /**
     * @var array<string, array<string, array{html?: string[], headers?: array<string,string>|string[], meta_gen?: string[]}>>
     */
    private const SIGNATURES = [
        'CMS' => [
            'WordPress' => ['html' => ['wp-content/', 'wp-includes/', 'wp-json']],
            'Shopify' => ['html' => ['cdn.shopify.com', 'Shopify.theme', 'shopify_pay']],
            'Wix' => ['html' => ['static.wixstatic.com', 'wix-bolt', '_wixCIDX']],
            'Squarespace' => ['html' => ['squarespace.com/universal', 'static1.squarespace']],
            'Webflow' => ['html' => ['webflow.com', 'assets.website-files.com', 'wf-form']],
            'Ghost' => ['html' => ['ghost.io', 'ghost-card'], 'meta_gen' => ['ghost']],
            'Joomla' => ['html' => ['/media/jui/', 'com_content'], 'meta_gen' => ['joomla']],
            'Drupal' => ['html' => ['drupal.js', 'data-drupal-'], 'meta_gen' => ['drupal']],
        ],
        'JS Framework' => [
            'Next.js' => ['html' => ['__NEXT_DATA__', '_next/static', '__next_f']],
            'Nuxt.js' => ['html' => ['__nuxt', '_nuxt/', '__NUXT__']],
            'React' => ['html' => ['react-dom', 'data-reactroot', 'react.development.js']],
            'Vue.js' => ['html' => ['vue.min.js', '__vue_store__', 'data-v-app']],
            'Angular' => ['html' => ['ng-version=', 'angular/core', 'ng-app']],
            'Svelte' => ['html' => ['__svelte', 'svelte/internal']],
            'Alpine.js' => ['html' => ['cdn.jsdelivr.net/npm/alpinejs', 'x-data=']],
            'HTMX' => ['html' => ['htmx.org', 'hx-get=', 'hx-post=']],
            'Ember.js' => ['html' => ['ember.js', 'ember-application']],
        ],
        'Backend' => [
            'Laravel' => ['html' => ['laravel_session', 'XSRF-TOKEN'], 'headers' => ['x-powered-by' => 'php']],
            'Ruby on Rails' => ['html' => ['rails-ujs', 'data-remote="true"']],
            'Django' => ['html' => ['csrfmiddlewaretoken', '__django']],
            'ASP.NET' => ['headers' => ['x-aspnet-version']],
            'Express.js' => ['headers' => ['x-powered-by' => 'express']],
        ],
        'CSS Framework' => [
            'Tailwind CSS' => ['html' => ['cdn.tailwindcss', 'tailwindcss.com']],
            'Bootstrap' => ['html' => ['bootstrap.min.css', 'bootstrap.css', 'bootstrap@']],
            'Bulma' => ['html' => ['bulma.min.css', 'bulma.css']],
            'Foundation' => ['html' => ['foundation.min.css', 'foundation.css']],
            'Materialize' => ['html' => ['materialize.min.css', 'materializecss.com']],
            'UIkit' => ['html' => ['uikit.min.css', 'uikit.min.js']],
        ],
        'JS Library' => [
            'jQuery' => ['html' => ['jquery.min.js', 'jquery-3', 'jquery-2']],
            'GSAP' => ['html' => ['gsap.min.js', 'cdnjs.cloudflare.com/ajax/libs/gsap']],
            'Three.js' => ['html' => ['three.min.js', 'three.r1', 'three.module']],
            'Swiper' => ['html' => ['swiper.min.js', 'swiperjs.com']],
            'Lodash' => ['html' => ['lodash.min.js', 'lodash.core']],
            'Axios' => ['html' => ['axios.min.js', 'cdn.jsdelivr.net/npm/axios']],
            'Chart.js' => ['html' => ['chart.min.js', 'chart.umd.js']],
            'D3.js' => ['html' => ['d3.min.js', 'd3.v7', 'd3.v6']],
        ],
        'Analytics' => [
            'Google Analytics' => ['html' => ['google-analytics.com/analytics', 'gtag/js?id=g-', 'gtag/js?id=ua-']],
            'Google Tag Manager' => ['html' => ['googletagmanager.com/gtm.js', 'GTM-']],
            'Facebook Pixel' => ['html' => ['connect.facebook.net/en_US/fbevents', "fbq('init"]],
            'Hotjar' => ['html' => ['static.hotjar.com', 'hjSVID']],
            'Mixpanel' => ['html' => ['cdn.mxpnl.com', 'mixpanel.init']],
            'Clarity' => ['html' => ['clarity.ms/tag/']],
            'Segment' => ['html' => ['cdn.segment.com', 'analytics.js']],
            'Plausible' => ['html' => ['plausible.io/js']],
        ],
        'CDN & Hosting' => [
            'Cloudflare' => ['headers' => ['cf-ray', 'cf-cache-status']],
            'Vercel' => ['headers' => ['x-vercel-id', 'x-vercel-cache']],
            'Netlify' => ['headers' => ['x-nf-request-id', 'netlify-vary']],
            'AWS CloudFront' => ['headers' => ['x-amz-cf-id']],
            'GitHub Pages' => ['html' => ['github.io/']],
        ],
        'Server' => [
            'Nginx' => ['headers' => ['server' => 'nginx']],
            'Apache' => ['headers' => ['server' => 'apache']],
            'LiteSpeed' => ['headers' => ['server' => 'litespeed']],
            'Caddy' => ['headers' => ['server' => 'caddy']],
        ],
        'E-commerce' => [
            'WooCommerce' => ['html' => ['woocommerce', 'wc-cart', 'wc_add_to_cart_params']],
            'Magento' => ['html' => ['Magento_', 'mage/cookies', 'mage/mage']],
            'PrestaShop' => ['html' => ['prestashop', '/modules/ps_']],
            'BigCommerce' => ['html' => ['cdn.bigcommerce.com', 'BigCommerce']],
        ],
        'Chat & Support' => [
            'Intercom' => ['html' => ['widget.intercom.io', "Intercom('boot'"]],
            'Drift' => ['html' => ['js.driftt.com', 'drift.load']],
            'Crisp' => ['html' => ['client.crisp.chat', '$crisp']],
            'Zendesk' => ['html' => ['static.zdassets.com', 'zE(']],
            'Tawk.to' => ['html' => ['embed.tawk.to']],
            'Freshchat' => ['html' => ['wchat.freshchat.com']],
        ],
    ];

    private const CAT_ICONS = [
        'CMS' => 'fa-layer-group', 'JS Framework' => 'fa-js', 'Backend' => 'fa-server',
        'CSS Framework' => 'fa-palette', 'JS Library' => 'fa-code', 'Analytics' => 'fa-chart-bar',
        'CDN & Hosting' => 'fa-cloud', 'Server' => 'fa-database', 'E-commerce' => 'fa-cart-shopping',
        'Chat & Support' => 'fa-comments',
    ];

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
        $loadTime = $page['loadTime'];
        $headers = $page['headers'];

        $htmlLow = strtolower($html);

        $hdrParts = [];
        foreach ($headers as $k => $v) {
            $hdrParts[] = "{$k}: {$v}";
        }
        $hdrStr = strtolower(implode("\n", $hdrParts));

        $generator = '';
        if (preg_match('/<meta[^>]+name=["\']generator["\'][^>]+content=["\']([^"\']+)/i', $html, $gm)) {
            $generator = strtolower($gm[1]);
        }

        $detected = [];
        $total = 0;

        foreach (self::SIGNATURES as $cat => $techs) {
            $found = [];

            foreach ($techs as $name => $patterns) {
                $matched = false;

                if (! empty($patterns['html'])) {
                    foreach ($patterns['html'] as $p) {
                        if (str_contains($htmlLow, strtolower($p))) {
                            $matched = true;
                            break;
                        }
                    }
                }

                if (! $matched && ! empty($patterns['headers'])) {
                    $hdrPatterns = $patterns['headers'];
                    if (array_is_list($hdrPatterns)) {
                        foreach ($hdrPatterns as $h) {
                            if (str_contains($hdrStr, strtolower($h))) {
                                $matched = true;
                                break;
                            }
                        }
                    } else {
                        foreach ($hdrPatterns as $k => $v) {
                            if (str_contains($hdrStr, strtolower($k)) && str_contains($hdrStr, strtolower($v))) {
                                $matched = true;
                                break;
                            }
                        }
                    }
                }

                if (! $matched && $generator !== '' && ! empty($patterns['meta_gen'])) {
                    foreach ($patterns['meta_gen'] as $mg) {
                        if (str_contains($generator, $mg)) {
                            $matched = true;
                            break;
                        }
                    }
                }

                if ($matched) {
                    $found[] = $name;
                    $total++;
                }
            }

            if (count($found)) {
                $detected[$cat] = ['icon' => self::CAT_ICONS[$cat] ?? 'fa-puzzle-piece', 'techs' => $found];
            }
        }

        return response()->json(['url' => $url, 'loadTime' => $loadTime, 'total' => $total, 'detected' => $detected]);
    }
}
