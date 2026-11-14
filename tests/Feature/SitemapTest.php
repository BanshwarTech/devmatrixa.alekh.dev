<?php

namespace Tests\Feature;

use Tests\TestCase;

class SitemapTest extends TestCase
{
    public function test_sitemap_lists_public_pages_and_all_tools(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertOk()
            ->assertHeader('Content-Type', 'text/xml; charset=utf-8')
            ->assertSee('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">', false)
            ->assertSee('<loc>'.url('/').'</loc>', false)
            ->assertSee('<loc>'.url('/about').'</loc>', false)
            ->assertSee('<loc>'.url('/contact').'</loc>', false);

        foreach (config('tools') as $tool) {
            $response->assertSee('<loc>'.url($tool['url']).'</loc>', false);
        }

        $this->assertSame(count(config('tools')) + 3, substr_count($response->getContent(), '<url>'));
    }
}
