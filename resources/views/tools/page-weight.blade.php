<x-layout
    title="Page Weight Checker – Measure Total Page Size"
    description="Check the total size of any webpage including HTML, CSS, JS, and images. Identify heavy assets slowing down your Core Web Vitals scores."
    keywords="page weight checker, page size checker, website weight analyzer, core web vitals checker, page speed audit"
    og-title="Page Weight Breakdown — See Where Every Byte Goes on Any URL | Devmatrixa"
    og-description="Paste any URL and instantly see a full breakdown of page weight by HTML, JS, CSS, images, and media — with the largest assets listed and real Content-Length sizes. Free and zero data stored."
>
    @push('head')
        @vite('resources/js/pages/page-weight.js')
    @endpush

    <main>
        <x-tool-hero
            badge="Asset Breakdown"
            description="See a full breakdown of page weight by HTML, JS, CSS, images, and media. Know exactly which asset type is bloating your page — and where to start cutting."
            :primary-cta="['label' => 'Analyze Weight', 'href' => '#analyzer-panel']"
            :trust-labels="['fast' => 'Lightning Fast', 'privacy' => 'Privacy-First', 'signup' => 'No Signup', 'unlimited' => 'Unlimited Use']"
        >
            <x-slot:title>Where every<br><span class="s-it text-accent">byte goes.</span></x-slot:title>
            <x-slot:rightPanel>
                <x-preview-card variant="gauge" icon="fa-solid fa-scale-balanced" icon-bg="linear-gradient(135deg,#a3e635,#f59e0b)" icon-color="#061c21" title="Page Weight" subtitle="2.4 MB total">
                    <div class="space-y-2">
                        @foreach ([
                            ['type' => 'Images', 'size' => '1.2 MB', 'pct' => 50, 'color' => '#a3e635'],
                            ['type' => 'JavaScript', 'size' => '680 KB', 'pct' => 28, 'color' => '#f59e0b'],
                            ['type' => 'CSS', 'size' => '180 KB', 'pct' => 7, 'color' => '#0694a2'],
                            ['type' => 'Media', 'size' => '240 KB', 'pct' => 10, 'color' => '#16bdca'],
                            ['type' => 'HTML', 'size' => '120 KB', 'pct' => 5, 'color' => '#7edce2'],
                        ] as $r)
                            <div>
                                <div class="flex justify-between text-[10px] mb-0.5">
                                    <span style="color:var(--c-text)"><strong>{{ $r['type'] }}</strong></span>
                                    <span style="color:{{ $r['color'] }}">{{ $r['size'] }}</span>
                                </div>
                                <div class="h-1.5 rounded-full overflow-hidden" style="background:rgba(255,255,255,0.06)">
                                    <div class="h-full rounded-full" style="width:{{ $r['pct'] }}%;background:{{ $r['color'] }}"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-preview-card>
            </x-slot:rightPanel>
        </x-tool-hero>

        <x-tool-ticker :items="[
            ['icon' => 'fa-solid fa-scale-balanced', 'color' => '#0694a2', 'label' => 'Total Weight'],
            ['icon' => 'fa-solid fa-file-code', 'color' => '#16bdca', 'label' => 'HTML Size'],
            ['icon' => 'fa-brands fa-css3-alt', 'color' => '#a3e635', 'label' => 'CSS Size'],
            ['icon' => 'fa-brands fa-js', 'color' => '#7edce2', 'label' => 'JS Size'],
            ['icon' => 'fa-solid fa-image', 'color' => '#0694a2', 'label' => 'Image Bytes'],
            ['icon' => 'fa-solid fa-film', 'color' => '#16bdca', 'label' => 'Media Files'],
            ['icon' => 'fa-solid fa-list-ol', 'color' => '#a3e635', 'label' => 'Total Requests'],
            ['icon' => 'fa-solid fa-chart-pie', 'color' => '#7edce2', 'label' => 'Type Breakdown'],
            ['icon' => 'fa-solid fa-file-zipper', 'color' => '#0694a2', 'label' => 'Compression'],
            ['icon' => 'fa-solid fa-gauge-high', 'color' => '#16bdca', 'label' => 'Largest Assets'],
            ['icon' => 'fa-solid fa-bolt', 'color' => '#a3e635', 'label' => 'Parallel Probes'],
            ['icon' => 'fa-solid fa-ruler', 'color' => '#7edce2', 'label' => 'Content-Length'],
        ]" />

        <x-how-it-works :steps="[
            ['icon' => 'fa-solid fa-link', 'title' => 'Paste Your URL', 'desc' => 'Enter any URL and the tool fetches the full HTML, then discovers every linked JS, CSS, image, and media file referenced on the page.'],
            ['icon' => 'fa-solid fa-scale-balanced', 'title' => 'Measure Everything', 'desc' => 'Each discovered resource is HEAD-requested in parallel to retrieve its real Content-Length — no estimates, no heuristics, just actual transferred sizes.'],
            ['icon' => 'fa-solid fa-chart-pie', 'title' => 'Type Breakdown', 'desc' => 'Bytes are grouped and ranked by asset type — HTML, JS, CSS, images, and media — with the largest individual files surfaced for immediate action.'],
        ]">
            <x-slot:subtitle>Three steps to <span class="s-it text-accent">a leaner page.</span></x-slot:subtitle>
        </x-how-it-works>

        <x-feature-highlights
            badge="Why This Tool"
            description="Page weight broken down by asset type so you know which specific file — not just which folder — is costing your users load time."
            :features="[
                ['icon' => 'fa-solid fa-chart-pie', 'title' => 'Type-Level Breakdown', 'desc' => 'HTML, JS, CSS, image, and media bytes are independently summed and ranked by size, so you can target the heaviest category first without guessing.'],
                ['icon' => 'fa-solid fa-list-ol', 'title' => 'Largest Asset List', 'desc' => 'Every resource is listed individually with its exact size — making it easy to surface the single oversized hero image or unminified JS bundle that is dragging the rest of the page down.'],
                ['icon' => 'fa-solid fa-ruler', 'title' => 'Real Content-Length', 'desc' => 'Sizes are pulled directly from actual HTTP response headers — not estimated from file extensions — so the totals reflect what your CDN bills and what browsers actually download.'],
                ['icon' => 'fa-solid fa-bolt', 'title' => 'Parallel Probing', 'desc' => 'Every asset is HEAD-checked simultaneously, meaning even pages with over 200 individual requests are fully measured in a matter of seconds.'],
                ['icon' => 'fa-solid fa-file-zipper', 'title' => 'Pre-Compression Aware', 'desc' => 'Transferred sizes are reported, so brotli and gzip savings are already reflected in the totals — giving you the real-world figure your users experience.'],
                ['icon' => 'fa-solid fa-gauge-high', 'title' => 'Perf-Budget Friendly', 'desc' => 'Run the tool against a target weight threshold — 2 MB for content pages, 1 MB for landing pages, 500 KB for critical paths — and instantly see where your page stands.'],
            ]"
        >
            <x-slot:title>Know exactly where the <span class="s-it text-accent">bytes go.</span></x-slot:title>
        </x-feature-highlights>

        <x-analyzer-section>
            <div id="page-weight-app"></div>
        </x-analyzer-section>

        <x-faq-section :items="[
            ['q' => 'Does this measure the same way Lighthouse does?', 'a' => 'The tool measures transferred bytes via parallel HEAD requests, which is closely aligned with Lighthouse\'s transfer size metric. Assets are not decoded and re-measured, so significant decompression differences may cause minor variations against Lighthouse totals.'],
            ['q' => 'Why are some assets missing from the breakdown?', 'a' => 'Only resources discoverable from the server-rendered HTML and linked stylesheets are included. Assets that load exclusively after JavaScript executes — lazy-loaded images, dynamically injected scripts — are not present in the initial markup and will not appear.'],
            ['q' => 'Does it include third-party scripts and tracking pixels?', 'a' => 'Yes. Any asset referenced from the page HTML or its linked CSS files is measured and included in the breakdown, regardless of whether it is hosted on your own domain or a third-party origin.'],
            ['q' => 'What is a healthy total page weight to aim for?', 'a' => 'A common target is under 2 MB for content-heavy pages and under 1 MB for landing pages. Mobile-first performance audits typically aim for 500 KB or less on the critical rendering path.'],
            ['q' => 'Are the URLs I submit stored anywhere?', 'a' => 'No. Every request is processed and discarded immediately. No URLs or response content are ever logged or retained.'],
        ]" />

        <x-related-tools current-key="page-weight" category="web" />
    </main>
</x-layout>
