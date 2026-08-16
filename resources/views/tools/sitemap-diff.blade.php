<x-layout
    title="Sitemap Diff Tool – Compare Two Sitemaps"
    description="Compare two XML sitemaps and instantly see added, removed, or changed URLs. Perfect for tracking site migrations and content updates."
    og-title="Sitemap vs Crawl Diff — Orphan & Missing Page Finder | Devmatrixa"
    og-description="Paste your homepage and we'll diff your sitemap.xml against a live crawl — orphan pages, missing pages, and a coverage score, all in one click."
>
    @push('head')
        @vite('resources/js/pages/sitemap-diff.js')
    @endpush

    <main>
        <x-tool-hero
            badge="Sitemap Coverage Audit"
            description="Diff your sitemap.xml against a live crawl. Surface orphan pages (sitemap-only) and missing pages (linked but not in sitemap) — and get a coverage score that tells you how indexable your site really is."
            :primary-cta="['label' => 'Run Diff', 'href' => '#analyzer-panel']"
            :secondary-cta="['label' => 'How It Works', 'href' => '#how-it-works']"
            :trust-labels="['fast' => 'Lightning Fast', 'privacy' => 'Privacy-First', 'signup' => 'No Signup', 'unlimited' => 'Unlimited Use']"
        >
            <x-slot:title>Find every<br><span class="s-it text-accent">orphan page.</span></x-slot:title>
            <x-slot:rightPanel>
                <x-preview-card variant="scope" icon="fa-solid fa-code-compare" icon-bg="linear-gradient(135deg,#0694a2,#a3e635)" icon-color="#061c21" title="Coverage Diff" subtitle="Sitemap vs Crawl">
                    <div class="space-y-1.5">
                        @foreach ([
                            ['p' => '/about', 't' => 'both', 'c' => '#65a30d'],
                            ['p' => '/products', 't' => 'both', 'c' => '#65a30d'],
                            ['p' => '/old-landing', 't' => 'orphan', 'c' => '#f59e0b'],
                            ['p' => '/blog/new-post', 't' => 'missing', 'c' => '#f87171'],
                            ['p' => '/case-study/acme', 't' => 'missing', 'c' => '#f87171'],
                            ['p' => '/legacy/promo', 't' => 'orphan', 'c' => '#f59e0b'],
                        ] as $r)
                            <div class="flex items-center gap-2 text-xs rounded-lg px-2.5 py-1.5" style="background:rgba(255,255,255,0.04)">
                                <span class="font-mono text-[10px] truncate flex-1" style="color:var(--c-muted)">{{ $r['p'] }}</span>
                                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded uppercase" style="background:{{ $r['c'] }}22;color:{{ $r['c'] }}">{{ $r['t'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </x-preview-card>
            </x-slot:rightPanel>
        </x-tool-hero>

        <x-tool-ticker :items="[
            ['icon' => 'fa-solid fa-map', 'color' => '#0694a2', 'label' => 'sitemap.xml'],
            ['icon' => 'fa-solid fa-network-wired', 'color' => '#16bdca', 'label' => 'Live Crawl'],
            ['icon' => 'fa-solid fa-code-compare', 'color' => '#a3e635', 'label' => 'Diff'],
            ['icon' => 'fa-solid fa-link-slash', 'color' => '#f59e0b', 'label' => 'Orphan Pages'],
            ['icon' => 'fa-solid fa-file-circle-xmark', 'color' => '#f87171', 'label' => 'Missing Pages'],
            ['icon' => 'fa-solid fa-circle-check', 'color' => '#65a30d', 'label' => 'Coverage'],
            ['icon' => 'fa-solid fa-scroll', 'color' => '#16bdca', 'label' => 'robots.txt'],
            ['icon' => 'fa-solid fa-list-tree', 'color' => '#0694a2', 'label' => 'Sitemap Index'],
            ['icon' => 'fa-solid fa-crosshairs', 'color' => '#a3e635', 'label' => 'Indexability'],
            ['icon' => 'fa-solid fa-magnifying-glass', 'color' => '#7edce2', 'label' => 'SEO Audit'],
            ['icon' => 'fa-solid fa-bolt', 'color' => '#0694a2', 'label' => 'One Click'],
            ['icon' => 'fa-solid fa-link', 'color' => '#16bdca', 'label' => 'Internal Links'],
        ]" />

        <x-how-it-works :steps="[
            ['icon' => 'fa-solid fa-globe', 'title' => 'Paste Your Homepage', 'desc' => 'Drop in your root URL. We\'ll auto-discover sitemap.xml, sitemap_index.xml, and any sitemaps listed in robots.txt — recursing through sitemap indexes as needed.'],
            ['icon' => 'fa-solid fa-code-compare', 'title' => 'Crawl + Diff', 'desc' => 'A live crawl from the homepage runs alongside the sitemap parse. Each URL is normalized (canonical, trailing slash, .php/.html) before comparison.'],
            ['icon' => 'fa-solid fa-file-circle-xmark', 'title' => 'Fix Coverage Gaps', 'desc' => 'Missing pages get added to sitemap.xml; orphan pages get fresh internal links or get removed. Coverage score climbs toward 100%.'],
        ]">
            <x-slot:subtitle>Three steps to <span class="s-it text-accent">100% coverage.</span></x-slot:subtitle>
        </x-how-it-works>

        <x-feature-highlights
            badge="Why This Tool"
            description="A page can be perfectly optimized and still invisible to Google — if it's not in your sitemap AND not linked from anywhere, it won't get crawled. This tool finds both gaps."
            :features="[
                ['icon' => 'fa-solid fa-link-slash', 'title' => 'Orphan page detection', 'desc' => 'Pages listed in sitemap but unreachable from any internal link. Search engines may still find them, but users can\'t — usually a sign of stale content or broken navigation.'],
                ['icon' => 'fa-solid fa-file-circle-xmark', 'title' => 'Missing-from-sitemap detection', 'desc' => 'Pages reachable by crawl but absent from sitemap.xml. Adding them speeds up indexation, especially for fresh content or deep-nested pages.'],
                ['icon' => 'fa-solid fa-circle-check', 'title' => 'Coverage score', 'desc' => 'Percentage of pages that appear in both sitemap and crawl. A score below 90% usually means your sitemap is stale or your internal linking has gaps.'],
                ['icon' => 'fa-solid fa-scroll', 'title' => 'robots.txt sitemap discovery', 'desc' => 'We honor the Sitemap: directive in robots.txt — including multiple sitemaps and sitemap indexes — so we audit what Google actually sees, not just /sitemap.xml.'],
                ['icon' => 'fa-solid fa-list-tree', 'title' => 'Sitemap index recursion', 'desc' => 'Large sites split sitemaps into chunks via sitemap_index.xml. We recurse through them so the diff is accurate even on large sites.'],
                ['icon' => 'fa-solid fa-copy', 'title' => 'Copy-as-list output', 'desc' => 'Each bucket (orphan, missing, healthy) has a one-click copy button — paste the missing pages straight into your sitemap-builder or CMS.'],
            ]"
        >
            <x-slot:title>Indexable doesn't mean <span class="s-it text-accent">indexed.</span></x-slot:title>
        </x-feature-highlights>

        <x-analyzer-section>
            <div id="sitemap-diff-app"></div>
        </x-analyzer-section>

        <x-faq-section :items="[
            ['q' => 'What\'s the difference between orphan and missing?', 'a' => 'Orphan = in your sitemap.xml but no internal link reaches it (users can\'t navigate to it organically). Missing = reachable by crawl but absent from sitemap.xml (search engines find it slower). Both are coverage gaps but with different fixes.'],
            ['q' => 'What\'s a good coverage score?', 'a' => 'Above 90% is healthy. 70–90% usually means a stale sitemap or fragmented internal linking. Below 70% suggests your sitemap is auto-generated incorrectly or your nav is severely broken.'],
            ['q' => 'How many pages does the crawl cover?', 'a' => 'A bounded number of unique pages per audit from the seed URL, to keep results fast. For larger sites, run the audit from multiple section homepages and combine results.'],
            ['q' => 'Why don\'t blog posts show up?', 'a' => 'By default, sub-sections are crawled normally. If your blog is on a subdomain (e.g., blog.example.com) it\'s treated as a different host and excluded — run a separate audit on the subdomain.'],
            ['q' => 'Are URLs stored?', 'a' => 'No. The crawl runs in memory, the diff is computed, results return, and nothing is logged or persisted.'],
        ]" />

        <x-related-tools
            current-key="sitemap-diff"
            :tool-keys="['link-checker', 'seo-analyzer', 'redirect-chain']"
            title="Related tools."
            description="Audit links, redirects, and SEO from the same URL bar."
        />
    </main>
</x-layout>
