<x-layout
    title="Redirect Chain Checker – Trace All Redirects"
    description="Follow every redirect hop on any URL. Detect redirect loops, unnecessary chains, and 301 vs 302 issues that silently kill your PageRank."
    keywords="redirect chain checker, redirect checker, 301 redirect checker, redirect loop finder, url redirect tracer"
    og-title="Redirect Chain Tracer — See Every Hop on Any URL | Devmatrixa"
    og-description="Paste any URL and see the full redirect chain — status codes, response times, HTTPS downgrades, and loops — all in one click. No data stored."
>
    @push('head')
        @vite('resources/js/pages/redirect-chain.js')
    @endpush

    <main>
        <x-tool-hero
            badge="Redirect Path Audit"
            description="Paste any URL and see the full redirect chain — status codes, response times, HTTPS downgrades, and loops. Catch SEO-killing chains before they cost you rankings or speed."
            :primary-cta="['label' => 'Trace Now', 'href' => '#analyzer-panel']"
            :secondary-cta="['label' => 'How It Works', 'href' => '#how-it-works']"
            :trust-labels="['fast' => 'Lightning Fast', 'privacy' => 'Privacy-First', 'signup' => 'No Signup', 'unlimited' => 'Unlimited Use']"
        >
            <x-slot:title>Trace every<br><span class="s-it text-accent">redirect hop.</span></x-slot:title>
            <x-slot:rightPanel>
                <x-preview-card variant="graph" icon="fa-solid fa-rotate" icon-bg="linear-gradient(135deg,#f59e0b,#0694a2)" icon-color="#fff" title="Redirect Chain" subtitle="Hop-by-hop trace">
                    <div class="space-y-1.5">
                        @foreach ([
                            ['u' => 'http://example.com', 's' => 301, 'c' => '#f59e0b'],
                            ['u' => 'https://example.com', 's' => 301, 'c' => '#f59e0b'],
                            ['u' => 'https://www.example.com', 's' => 302, 'c' => '#f59e0b'],
                            ['u' => 'https://www.example.com/home', 's' => 200, 'c' => '#65a30d'],
                        ] as $r)
                            <div class="flex items-center gap-2 text-xs rounded-lg px-2.5 py-1.5" style="background:rgba(255,255,255,0.04)">
                                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded shrink-0" style="background:{{ $r['c'] }}22;color:{{ $r['c'] }}">{{ $r['s'] }}</span>
                                <span class="font-mono text-[10px] truncate flex-1" style="color:var(--c-muted)">{{ $r['u'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </x-preview-card>
            </x-slot:rightPanel>
        </x-tool-hero>

        <x-tool-ticker :items="[
            ['icon' => 'fa-solid fa-rotate', 'color' => '#f59e0b', 'label' => '301 / 302'],
            ['icon' => 'fa-solid fa-code-branch', 'color' => '#0694a2', 'label' => '307 / 308'],
            ['icon' => 'fa-solid fa-shield-halved', 'color' => '#f87171', 'label' => 'HTTPS Downgrade'],
            ['icon' => 'fa-solid fa-shuffle', 'color' => '#a3e635', 'label' => 'Loop Detection'],
            ['icon' => 'fa-solid fa-globe', 'color' => '#16bdca', 'label' => 'Cross-Domain'],
            ['icon' => 'fa-solid fa-stopwatch', 'color' => '#0694a2', 'label' => 'Per-Hop Latency'],
            ['icon' => 'fa-solid fa-wave-square', 'color' => '#a3e635', 'label' => 'Total RTT'],
            ['icon' => 'fa-solid fa-network-wired', 'color' => '#16bdca', 'label' => 'Location Header'],
            ['icon' => 'fa-solid fa-lock', 'color' => '#65a30d', 'label' => 'HTTPS'],
            ['icon' => 'fa-solid fa-unlock', 'color' => '#f87171', 'label' => 'HTTP'],
            ['icon' => 'fa-solid fa-bolt', 'color' => '#a3e635', 'label' => 'Instant Trace'],
            ['icon' => 'fa-solid fa-arrow-up-right-from-square', 'color' => '#0694a2', 'label' => 'Final URL'],
        ]" />

        <x-how-it-works :steps="[
            ['icon' => 'fa-solid fa-globe', 'title' => 'Paste Your URL', 'desc' => 'Drop in any URL — http, https, www, or non-www. The tracer follows redirects manually so it sees every hop the browser would.'],
            ['icon' => 'fa-solid fa-code-branch', 'title' => 'Hop-by-Hop Trace', 'desc' => 'Each 3xx response is captured with status code, Location header, response time, and protocol. We stop at the first 2xx or 4xx — or call out a loop if we detect one.'],
            ['icon' => 'fa-solid fa-circle-check', 'title' => 'Spot the Problems', 'desc' => 'HTTPS downgrades, temporary redirects that should be permanent, multi-hop chains, and cross-domain detours all surface as actionable issues.'],
        ]">
            <x-slot:subtitle>Three steps to <span class="s-it text-accent">a clean redirect path.</span></x-slot:subtitle>
        </x-how-it-works>

        <x-feature-highlights
            badge="Why This Tool"
            description="Most checkers show only the final URL. We show you every hop — because each one costs latency, may break HSTS, and dilutes SEO equity."
            :features="[
                ['icon' => 'fa-solid fa-stopwatch', 'title' => 'Per-hop response time', 'desc' => 'Each redirect\'s latency is measured separately. A \'fast\' final URL can hide a 600ms intermediate hop that\'s killing your TTFB.'],
                ['icon' => 'fa-solid fa-shield-halved', 'title' => 'HTTPS downgrade detection', 'desc' => 'Any HTTPS → HTTP step in your chain breaks HSTS and exposes traffic. We flag these as hard errors with the exact hop.'],
                ['icon' => 'fa-solid fa-shuffle', 'title' => 'Loop detection', 'desc' => 'If a redirect chain revisits a URL it already saw, we stop and call it a loop — instead of timing out like a browser would.'],
                ['icon' => 'fa-solid fa-rotate', 'title' => '301 vs 302 awareness', 'desc' => 'Temporary 302/307 redirects don\'t pass link equity and aren\'t cached by browsers. We point out where you should switch to 301/308.'],
                ['icon' => 'fa-solid fa-network-wired', 'title' => 'Cross-domain detection', 'desc' => 'If your URL ends up on a different host, we tell you whether it\'s expected (www normalization) or a true cross-domain handoff that may leak equity.'],
                ['icon' => 'fa-solid fa-copy', 'title' => 'Final URL one-click copy', 'desc' => 'Once the chain settles, copy just the final destination — handy for canonical tags, sitemap entries, or marketing tracking.'],
            ]"
        >
            <x-slot:title>The chain matters as <span class="s-it text-accent">much as the destination.</span></x-slot:title>
        </x-feature-highlights>

        <x-analyzer-section>
            <div id="redirect-chain-app"></div>
        </x-analyzer-section>

        <x-faq-section :items="[
            ['q' => 'What\'s the ideal number of redirects?', 'a' => 'Zero is best. One is acceptable (e.g., http → https). Two or more starts hurting performance and SEO: each hop adds latency and dilutes link equity, and Google may stop following long chains entirely.'],
            ['q' => 'Why does my HTTPS site still redirect through HTTP?', 'a' => 'If you ever see HTTPS → HTTP in the chain, your edge is misconfigured. This breaks HSTS and exposes the request to MITM. Fix it at the load balancer or CDN level — never redirect down a protocol.'],
            ['q' => 'Should I use 301 or 302?', 'a' => 'Use 301 (permanent) for moves you intend to keep — it passes link equity and browsers cache it. Use 302/307 only when the destination is genuinely temporary (A/B test, maintenance page). Misusing 302 is one of the most common SEO bugs we see.'],
            ['q' => 'Does this tool make HEAD or GET requests?', 'a' => 'GET, with manual redirect handling so we capture every Location header. We don\'t read the response body — just the headers — so it\'s fast and works on pages that 405 on HEAD.'],
            ['q' => 'Are my URLs stored?', 'a' => 'No. Every trace is one chain of GET requests with manual redirect handling. Headers are read, hops returned, nothing logged or persisted.'],
        ]" />

        <x-related-tools
            current-key="redirect-chain"
            :tool-keys="['link-checker', 'security-headers', 'seo-analyzer']"
            title="Related tools."
            description="Audit links, headers, and SEO from the same URL bar."
        />
    </main>
</x-layout>
