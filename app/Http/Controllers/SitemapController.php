<?php

namespace App\Http\Controllers;

class SitemapController extends Controller
{
    public function index()
    {
        $pages = collect([
            ['loc' => '/', 'changefreq' => 'weekly', 'priority' => '1.0'],
            ['loc' => '/about', 'changefreq' => 'monthly', 'priority' => '0.5'],
            ['loc' => '/contact', 'changefreq' => 'monthly', 'priority' => '0.5'],
        ])->concat(
            collect(config('tools'))->map(fn ($tool) => [
                'loc' => $tool['url'],
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ])
        );

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        foreach ($pages as $page) {
            $xml .= '<url>'."\n";
            $xml .= '<loc>'.htmlspecialchars(url($page['loc']), ENT_XML1).'</loc>'."\n";
            $xml .= '<changefreq>'.$page['changefreq'].'</changefreq>'."\n";
            $xml .= '<priority>'.$page['priority'].'</priority>'."\n";
            $xml .= '</url>'."\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'text/xml');
    }
}
