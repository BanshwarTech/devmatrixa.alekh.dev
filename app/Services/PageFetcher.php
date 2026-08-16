<?php

namespace App\Services;

use GuzzleHttp\Psr7\Utils;
use GuzzleHttp\Psr7\UriResolver;
use Illuminate\Support\Facades\Http;

class PageFetcher
{
    public const USER_AGENT = 'Mozilla/5.0 (compatible; DevmatrixaBot/1.0; +https://devmatrixa.com)';

    /**
     * @return array{html: string, loadTime: int, size: int, status: int, finalUrl: string, headers: array<string, string>}
     */
    public static function fetchPage(string $url, int $timeoutMs = 15000): array
    {
        $start = (int) (microtime(true) * 1000);

        $response = Http::withHeaders([
                'User-Agent' => self::USER_AGENT,
                'Accept' => 'text/html,application/xhtml+xml',
            ])
            ->timeout((int) ceil($timeoutMs / 1000))
            ->withOptions(['allow_redirects' => true])
            ->get($url);

        $html = $response->body();
        $headers = [];
        foreach ($response->headers() as $key => $values) {
            $headers[$key] = implode(', ', $values);
        }

        return [
            'html' => $html,
            'loadTime' => (int) (microtime(true) * 1000) - $start,
            'size' => strlen($html),
            'status' => $response->status(),
            'finalUrl' => (string) ($response->effectiveUri() ?? $url),
            'headers' => $headers,
        ];
    }

    public static function isValidUrl(string $url): bool
    {
        return (bool) preg_match('#^https?://.+#i', $url);
    }

    public static function absUrl(string $href, string $base): string
    {
        try {
            return (string) UriResolver::resolve(Utils::uriFor($base), Utils::uriFor($href));
        } catch (\Throwable) {
            return $href;
        }
    }
}
