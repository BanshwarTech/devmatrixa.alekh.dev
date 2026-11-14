<x-layout
    title="Tech Stack Detector – What Is This Site Built With?"
    description="Identify the CMS, framework, CDN, analytics, and plugins powering any website. Get instant tech stack insights for competitive research."
    keywords="tech stack detector, website technology checker, cms detector, framework detector, what cms is this site using"
    og-title="Tech Stack Detector — Identify Any Website's Tech Stack Instantly | Devmatrixa"
    og-description="Paste any URL and instantly identify the CMS, JS framework, analytics, hosting, and CDN behind the site. 80+ tech signatures. 10 categories. Completely free."
>
    @push('head')
        @vite('resources/js/pages/tech-stack-detector.js')
    @endpush

    <main>
        <x-tool-hero
            badge="Mini Wappalyzer"
            description="Detect the CMS, JavaScript framework, analytics platform, hosting provider, and CDN behind any website,in seconds, with no extensions or signups required."
            :primary-cta="['label' => 'Detect Stack', 'href' => '#analyzer-panel']"
        >
            <x-slot:title>Identify any site's <span class="s-it text-accent">tech stack.</span></x-slot:title>
            <x-slot:rightPanel>
                <x-preview-card variant="terminal" icon="search-code" icon-bg="linear-gradient(135deg,#0098f0,#0694a2)" title="Detected Stack" subtitle="Site fingerprint">
                    <div class="space-y-2">
                        @foreach ([
                            ['cat' => 'CMS', 'tech' => 'WordPress', 'icon' => 'layers', 'color' => '#0694a2'],
                            ['cat' => 'JS', 'tech' => 'Next.js, React', 'icon' => 'file-code-2', 'color' => '#16bdca'],
                            ['cat' => 'Hosting', 'tech' => 'Vercel', 'icon' => 'cloud', 'color' => '#0098f0'],
                            ['cat' => 'Analytics', 'tech' => 'GA4, GTM', 'icon' => 'chart-bar', 'color' => '#7edce2'],
                        ] as $t)
                            <div class="flex items-center gap-2 text-xs rounded-lg px-2.5 py-1.5" style="background:rgba(255,255,255,0.04)">
                                <i class="text-xs" style="color:{{ $t['color'] }}" data-lucide="{{ $t['icon'] }}"></i>
                                <span class="text-[10px] uppercase font-bold tracking-wider" style="color:var(--c-muted)">{{ $t['cat'] }}</span>
                                <span class="font-semibold ml-auto" style="color:var(--c-text)">{{ $t['tech'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </x-preview-card>
            </x-slot:rightPanel>
        </x-tool-hero>

        <x-tool-ticker :items="[
            ['icon' => 'atom', 'color' => '#0694a2', 'label' => 'React'],
            ['icon' => 'panels-top-left', 'color' => '#16bdca', 'label' => 'Vue'],
            ['icon' => 'file-code-2', 'color' => '#0098f0', 'label' => 'Next.js'],
            ['icon' => 'file-text', 'color' => '#7edce2', 'label' => 'WordPress'],
            ['icon' => 'boxes', 'color' => '#0694a2', 'label' => 'Webpack'],
            ['icon' => 'code', 'color' => '#16bdca', 'label' => 'jQuery'],
            ['icon' => 'cloud', 'color' => '#0098f0', 'label' => 'CDN'],
            ['icon' => 'chart-bar', 'color' => '#7edce2', 'label' => 'Analytics'],
            ['icon' => 'type', 'color' => '#0694a2', 'label' => 'Fonts'],
            ['icon' => 'server', 'color' => '#16bdca', 'label' => 'Hosting'],
            ['icon' => 'shield', 'color' => '#0098f0', 'label' => 'Headers'],
            ['icon' => 'fingerprint', 'color' => '#7edce2', 'label' => 'Fingerprint'],
        ]" />

        <x-how-it-works :steps="[
            ['icon' => 'link', 'title' => 'Paste Your URL', 'desc' => 'Enter any public URL and the tool fetches the full HTML response along with all server response headers.'],
            ['icon' => 'search-code', 'title' => 'Pattern Match', 'desc' => 'Over 80 technology signatures are checked across HTML structure, response headers, meta tags, and script paths simultaneously.'],
            ['icon' => 'layers', 'title' => 'Categorized Results', 'desc' => 'Every detected technology is grouped into clearly labeled categories,CMS, JS framework, analytics, hosting, CDN, server, and more.'],
        ]">
            <x-slot:subtitle>Three steps to <span class="s-it text-accent">site fingerprinting</span></x-slot:subtitle>
        </x-how-it-works>

        <x-feature-highlights
            badge="Why This Tool"
            description="A free, no-install Wappalyzer alternative. Identify the CMS, framework, hosting, analytics, and CDN behind any URL,built for competitive research and rapid stack audits."
            :features="[
                ['icon' => 'target', 'title' => '80+ Tech Signatures', 'desc' => 'Detection rules cover React, Vue, Next.js, WordPress, Shopify, Webpack, Cloudflare, GA4, and dozens more technologies out of the box.'],
                ['icon' => 'layers', 'title' => '10 Categories', 'desc' => 'Results are cleanly grouped into CMS, JS framework, build tools, hosting, CDN, analytics, fonts, e-commerce, server, and response headers.'],
                ['icon' => 'fingerprint', 'title' => 'HTML, Headers, and Meta', 'desc' => 'Server response headers, meta tags, link and script URLs, and inline HTML are all inspected,multiple detection angles means far fewer false positives.'],
                ['icon' => 'bolt', 'title' => 'Sub-3-Second Scans', 'desc' => 'A single HTTP request is all it takes. Most sites are fully fingerprinted in under three seconds,no waiting, no queues.'],
                ['icon' => 'globe', 'title' => 'Works on Any Site', 'desc' => 'Marketing pages, SaaS apps, dashboards, blogs, and ecommerce stores,if it serves HTML, the stack can be identified.'],
                ['icon' => 'database', 'title' => 'Nothing Stored', 'desc' => 'URLs are fetched, parsed, and immediately discarded. Your scans are never logged, stored, or analyzed.'],
            ]"
        >
            <x-slot:title>Fingerprint any site, <span class="s-it text-accent">in seconds.</span></x-slot:title>
        </x-feature-highlights>

        <x-analyzer-section>
            <div id="tech-stack-detector-app"></div>
        </x-analyzer-section>

        <x-faq-section :items="[
            ['q' => 'How accurate is the detection?', 'a' => 'Very accurate for popular technologies. Canonical signatures,script paths, meta generator tags, and response headers,are matched reliably. Niche or heavily customized stacks may not always be identified.'],
            ['q' => 'Does it detect server-side rendered React or Next.js?', 'a' => 'Yes. SSR apps typically expose framework signatures through their runtime chunks,such as _next/static URLs,which are picked up reliably from the initial HTML response.'],
            ['q' => 'Can it detect technologies sitting behind a CDN?', 'a' => 'Yes on both counts. The CDN itself shows up via response headers, and the underlying application stack is detected from the HTML that is served,both layers appear separately in the results.'],
            ['q' => 'Will it ever return zero results?', 'a' => 'Rarely. Most public sites use at least one detectable technology. A clean zero usually means the site is heavily cached behind a CDN that strips identifying headers.'],
            ['q' => 'Are the URLs I submit stored anywhere?', 'a' => 'No. Every URL is fetched, parsed, and discarded immediately. Nothing is ever logged or retained.'],
        ]" />

        <x-related-tools
            current-key="tech-stack-detector"
            :tool-keys="['tailwind-extractor', 'css-variable-scanner', 'css-to-tailwind']"
            title="Related tools."
            description="Pick the next tool that helps you finish the job faster."
        />
    </main>
</x-layout>
