<x-layout
    title="Free Link Checker – Find Broken Links Fast"
    description="Scan any webpage for broken internal and external links instantly. Catch 404 errors before they hurt your SEO. Free, fast, no sign-up needed."
    keywords="broken link checker, link checker tool, 404 link finder, dead link checker, seo link audit"
    og-title="Link Checker — Real-Time Internal Link Auditor | Devmatrixa"
    og-description="Crawl any page and check every internal link live. Catch 404s, 301 redirects, and server errors in seconds — free, no sign-up, no data stored."
>
    @push('head')
        @vite('resources/js/pages/link-checker.js')
    @endpush

    <main>
        <x-tool-hero
            badge="Realtime link audit"
            description="Crawl any page and audit every internal link in real time. Surface 404s, redirects, and server errors instantly - before they quietly kill your search rankings."
            :primary-cta="['label' => 'Start Crawling', 'href' => '#analyzer-panel']"
        >
            <x-slot:title>Find every<br><span class="s-it text-accent">broken link.</span></x-slot:title>
            <x-slot:rightPanel>
                <x-preview-card variant="scope" icon="bug" icon-bg="linear-gradient(135deg,#0694a2,#0098f0)" title="Live Crawl" subtitle="Status by URL">
                    <div class="space-y-1.5">
                        @foreach ([
                            ['url' => '/blog/welcome', 'status' => 200, 'c' => '#4010b0'],
                            ['url' => '/products/new', 'status' => 301, 'c' => '#f59e0b'],
                            ['url' => '/about-old', 'status' => 404, 'c' => '#f87171'],
                            ['url' => '/team/sarah', 'status' => 200, 'c' => '#4010b0'],
                            ['url' => '/api/v1', 'status' => 500, 'c' => '#f87171'],
                            ['url' => '/contact', 'status' => 200, 'c' => '#4010b0'],
                        ] as $u)
                            <div class="flex items-center gap-2 text-xs rounded-lg px-2.5 py-1.5" style="background:rgba(255,255,255,0.04)">
                                <span class="font-mono text-[10px] truncate flex-1" style="color:var(--c-muted)">{{ $u['url'] }}</span>
                                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded" style="background:{{ $u['c'] }}22;color:{{ $u['c'] }}">{{ $u['status'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </x-preview-card>
            </x-slot:rightPanel>
        </x-tool-hero>

        <x-tool-ticker :items="[
            ['icon' => 'link', 'color' => '#0694a2', 'label' => 'Internal Links'],
            ['icon' => 'arrow-up-right-from-square', 'color' => '#16bdca', 'label' => 'External Links'],
            ['icon' => 'unlink', 'color' => '#0098f0', 'label' => 'Broken Links'],
            ['icon' => 'circle-x', 'color' => '#7edce2', 'label' => '404s'],
            ['icon' => 'rotate-cw', 'color' => '#0694a2', 'label' => 'Redirects'],
            ['icon' => 'circle-gauge', 'color' => '#16bdca', 'label' => 'HTTP Status'],
            ['icon' => 'bug', 'color' => '#0098f0', 'label' => 'Live Crawl'],
            ['icon' => 'bolt', 'color' => '#7edce2', 'label' => 'Parallel Checks'],
            ['icon' => 'triangle-alert', 'color' => '#0694a2', 'label' => '5xx Errors'],
            ['icon' => 'shield-check', 'color' => '#16bdca', 'label' => 'HTTPS'],
            ['icon' => 'search', 'color' => '#0098f0', 'label' => 'URL Audit'],
            ['icon' => 'list', 'color' => '#7edce2', 'label' => 'Status Map'],
        ]" />

        <x-how-it-works :steps="[
            ['icon' => 'network', 'title' => 'Paste Your URL', 'desc' => 'Drop in any page URL. The crawler reads your sitemap and scans every HTML link it finds - no setup, no configuration.'],
            ['icon' => 'bolt', 'title' => 'Live Status Scan', 'desc' => 'Each discovered URL is checked in real time. Status codes stream in row by row - 200s, 301s, 404s, and everything in between.'],
            ['icon' => 'circle-check', 'title' => 'Fix and Export', 'desc' => 'Broken links surface at the top. Sort by status code, isolate the problems, and fix them before they cost you rankings.'],
        ]">
            <x-slot:subtitle>Three steps to <span class="s-it text-accent">zero broken links.</span></x-slot:subtitle>
        </x-how-it-works>

        <x-feature-highlights
            badge="Why This Tool"
            description="Paste a URL, hit crawl, and watch every internal link status stream in live - no waiting, no batch jobs, no guesswork."
            :features="[
                ['icon' => 'bolt', 'title' => 'Live status streaming', 'desc' => 'Results appear as each link is checked - you see broken links the moment they\'re found, not after the entire crawl finishes.'],
                ['icon' => 'circle-gauge', 'title' => 'Concurrent requests', 'desc' => 'Six links run in parallel by default. Even pages with hundreds of links finish in seconds, not minutes.'],
                ['icon' => 'rotate-cw', 'title' => 'Redirect aware', 'desc' => '301s and 302s are flagged separately from 4xx and 5xx errors so you know exactly what needs fixing versus what\'s just moved.'],
                ['icon' => 'bug', 'title' => 'Internal-link focus', 'desc' => 'Only same-domain links are audited. The crawl stays clean and relevant - no noise from third-party URLs.'],
                ['icon' => 'shield-check', 'title' => 'Fail-soft network handling', 'desc' => 'Timeouts and DNS failures return a status 0 instead of crashing the crawl. Every broken path gets reported, nothing gets skipped.'],
                ['icon' => 'circle-check', 'title' => 'Color-coded results', 'desc' => 'Green for healthy, amber for redirects, red for errors. Broken links are impossible to miss - visible state at a single glance.'],
            ]"
        >
            <x-slot:title>404s caught the moment <span class="s-it text-accent">they happen.</span></x-slot:title>
        </x-feature-highlights>

        <x-analyzer-section>
            <div id="link-checker-app"></div>
        </x-analyzer-section>

        <x-faq-section :items="[
            ['q' => 'Does it crawl an entire website?', 'a' => 'Yes. The crawler follows internal links from the URL you submit, combines them with your sitemap.xml and robots.txt sitemaps, and discovers up to 150 unique pages across the site - not just the page you paste.'],
            ['q' => 'Why do some links show status 0?', 'a' => 'Status 0 means the request never completed - typically a DNS failure, connection timeout, or malformed URL. From a visitor\'s perspective, that link is broken.'],
            ['q' => 'Does it check external links too?', 'a' => 'The crawl is scoped to your domain by default. External links appear in the results but aren\'t status-checked - this keeps the audit focused and avoids hitting third-party rate limits.'],
            ['q' => 'Does it follow nofollow or sponsored links?', 'a' => 'Yes. Rel attributes are ignored during the status check. Every href gets hit so you get a complete picture of your link health - not just the ones Google follows.'],
            ['q' => 'Are my URLs saved anywhere?', 'a' => 'No. Every request is processed in memory and immediately discarded. We don\'t log URLs, paths, or response content - ever.'],
        ]" />

        <x-related-tools
            current-key="link-checker"
            :tool-keys="['seo-analyzer', 'alt-checker', 'faq-extractor']"
            title="Related tools."
            description="Pick the next tool that helps you close the job faster."
        />
    </main>
</x-layout>
