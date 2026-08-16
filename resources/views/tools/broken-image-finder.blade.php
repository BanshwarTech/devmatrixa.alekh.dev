<x-layout
    title="Broken Image Finder – Fix 404 Images on Your Site"
    description="Scan any webpage and detect all broken images with 404 errors. Fix missing images before they damage your UX and crawl quality signals."
    og-title="Broken Image Finder — Scan Any Page for Missing or Dead Images | Devmatrixa"
    og-description="Paste any URL and get a full HTTP-level audit of every image on the page — 404s, 403s, network failures, and working images all clearly labeled. Free and zero data stored."
>
    @push('head')
        @vite('resources/js/pages/broken-image-finder.js')
    @endpush

    <main>
        <x-tool-hero
            badge="HTTP Image Audit"
            description="Scan any webpage and instantly surface every broken or missing image,complete with exact HTTP status codes so you know precisely what to fix and why."
            :primary-cta="['label' => 'Scan Now', 'href' => '#analyzer-panel']"
            :trust-labels="['fast' => 'Lightning Fast', 'privacy' => 'Privacy-First', 'signup' => 'No Signup', 'unlimited' => 'Unlimited Use']"
        >
            <x-slot:title>Find every<br><span class="s-it text-accent">broken image.</span></x-slot:title>
            <x-slot:rightPanel>
                <x-preview-card variant="scope" icon="fa-solid fa-image" icon-bg="linear-gradient(135deg,#f87171,#0694a2)" icon-color="#fff" title="Image Status" subtitle="HTTP check">
                    <div class="space-y-2">
                        @foreach ([
                            ['name' => 'hero.webp', 'status' => 200, 'ok' => true],
                            ['name' => 'team-bg.jpg', 'status' => 404, 'ok' => false],
                            ['name' => 'logo.svg', 'status' => 200, 'ok' => true],
                            ['name' => 'old/banner.png', 'status' => 403, 'ok' => false],
                            ['name' => 'icon-32.png', 'status' => 200, 'ok' => true],
                        ] as $img)
                            <div class="flex items-center gap-2 text-xs rounded-lg px-2.5 py-1.5" style="background:rgba(255,255,255,0.04)">
                                <i class="{{ $img['ok'] ? 'fa-solid fa-image' : 'fa-solid fa-circle-xmark' }} text-xs" style="color:{{ $img['ok'] ? '#65a30d' : '#f87171' }}"></i>
                                <span class="font-mono text-[10px] truncate flex-1" style="color:var(--c-muted)">{{ $img['name'] }}</span>
                                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded" style="background:{{ $img['ok'] ? 'rgba(163,230,53,0.15)' : 'rgba(248,113,113,0.15)' }};color:{{ $img['ok'] ? '#65a30d' : '#f87171' }}">{{ $img['status'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </x-preview-card>
            </x-slot:rightPanel>
        </x-tool-hero>

        <x-tool-ticker :items="[
            ['icon' => 'fa-solid fa-circle-xmark', 'color' => '#0694a2', 'label' => '404 Images'],
            ['icon' => 'fa-solid fa-image', 'color' => '#16bdca', 'label' => 'Working Images'],
            ['icon' => 'fa-solid fa-ban', 'color' => '#a3e635', 'label' => '403 Forbidden'],
            ['icon' => 'fa-solid fa-triangle-exclamation', 'color' => '#7edce2', 'label' => 'Broken Sources'],
            ['icon' => 'fa-solid fa-link', 'color' => '#0694a2', 'label' => 'Image URLs'],
            ['icon' => 'fa-solid fa-gauge', 'color' => '#16bdca', 'label' => 'HTTP Status'],
            ['icon' => 'fa-solid fa-bolt', 'color' => '#a3e635', 'label' => 'Parallel HEAD'],
            ['icon' => 'fa-solid fa-magnifying-glass', 'color' => '#7edce2', 'label' => 'Source Scan'],
            ['icon' => 'fa-solid fa-xmark', 'color' => '#0694a2', 'label' => 'Missing Files'],
            ['icon' => 'fa-solid fa-file-image', 'color' => '#16bdca', 'label' => 'img Tags'],
            ['icon' => 'fa-solid fa-arrow-up-right-from-square', 'color' => '#a3e635', 'label' => 'Hotlinks'],
            ['icon' => 'fa-solid fa-rotate', 'color' => '#7edce2', 'label' => 'Redirects'],
        ]" />

        <x-how-it-works :steps="[
            ['icon' => 'fa-solid fa-link', 'title' => 'Paste Your URL', 'desc' => 'Enter any page URL and the tool extracts up to 50 image sources directly from the HTML,no browser extension or crawl setup needed.'],
            ['icon' => 'fa-solid fa-image', 'title' => 'HEAD Every Image', 'desc' => 'Each image URL is checked in parallel using HEAD requests, with an automatic fallback to GET for servers that block lightweight HEAD probes.'],
            ['icon' => 'fa-solid fa-triangle-exclamation', 'title' => 'Status Per Image', 'desc' => 'Every image is returned with its exact HTTP status code,200, 301, 403, 404, or 0 for network failures,so you can filter broken from working in one view.'],
        ]">
            <x-slot:subtitle>Three steps to <span class="s-it text-accent">a clean image audit.</span></x-slot:subtitle>
        </x-how-it-works>

        <x-feature-highlights
            badge="Why This Tool"
            description="HTTP-level image verification that catches 404s, 403s, and hotlink failures before your users,or search crawlers,ever encounter them."
            :features="[
                ['icon' => 'fa-solid fa-bolt', 'title' => 'Parallel HEAD Probes', 'desc' => 'All image URLs are checked simultaneously using HEAD requests,meaning the entire page audit completes in seconds rather than waiting on each image in sequence.'],
                ['icon' => 'fa-solid fa-rotate', 'title' => 'Auto GET Fallback', 'desc' => 'Servers that block HEAD requests are silently retried with a full GET request, so you never receive a false positive caused by server configuration rather than a missing file.'],
                ['icon' => 'fa-solid fa-gauge', 'title' => 'Exact HTTP Status', 'desc' => 'Every image is annotated with its raw HTTP status code,200 for working, 301 for redirects, 403 for blocked, 404 for missing, and 0 for complete network failures.'],
                ['icon' => 'fa-solid fa-globe', 'title' => 'Hotlink and CDN Safe', 'desc' => 'Cross-origin CDN images and hotlinked assets from third-party hosts are verified with the same thoroughness as first-party files hosted on your own domain.'],
                ['icon' => 'fa-solid fa-file-image', 'title' => 'Up to 50 Images', 'desc' => 'The tool audits the first 50 image sources per page,enough to cover any realistic content page, product listing, or marketing landing page in full.'],
                ['icon' => 'fa-solid fa-database', 'title' => 'Nothing Stored', 'desc' => 'Image URLs and response data never touch a database. Everything is processed entirely in memory and discarded immediately after the audit completes.'],
            ]"
        >
            <x-slot:title>Catch every <span class="s-it text-accent">broken image.</span></x-slot:title>
        </x-feature-highlights>

        <x-analyzer-section>
            <div id="broken-image-finder-app"></div>
        </x-analyzer-section>

        <x-faq-section :items="[
            ['q' => 'Does it detect broken background images set in CSS?', 'a' => 'No. Only image tags in the HTML are scanned. Background images declared through CSS,whether inline or in a stylesheet,are not checked by this tool.'],
            ['q' => 'What does an HTTP status of 0 mean?', 'a' => 'A status of 0 means the request failed before any response was received from the server. This typically indicates a DNS resolution failure, a network timeout, or a completely invalid URL.'],
            ['q' => 'Why are some images flagged as 403?', 'a' => 'A 403 response means the server actively blocked the request. This usually indicates that the origin has hotlink protection enabled and is rejecting requests without the correct referer header. The image may still load normally when accessed from the original page.'],
            ['q' => 'Does it follow redirects?', 'a' => 'Yes. HTTP 301 and 302 redirects are followed automatically and the final destination status code is reported. The tool surfaces the resolved status rather than the redirect itself.'],
            ['q' => 'Are the URLs I submit stored anywhere?', 'a' => 'No. Every request is processed and discarded immediately. No URLs or response content are ever logged or retained.'],
        ]" />

        <x-related-tools current-key="broken-image-finder" category="seo" />
    </main>
</x-layout>
