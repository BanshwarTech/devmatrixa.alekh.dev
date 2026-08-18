<x-layout
    title="Free SEO Analyzer – Audit Any Page Instantly"
    description="Paste a URL and get a full on-page SEO report in seconds. Title, meta, headings, canonicals, and more. No account, no credit card required."
    keywords="free seo analyzer, seo audit tool, on page seo checker, website seo checker, seo report generator"
    og-title="Free SEO Analyzer — Instant On-Page Audit | Devmatrixa"
    og-description="Get an instant SEO health score for any URL. Title, meta, headings, OG tags, schema, canonical, ALT text — 10+ signals checked in under 3 seconds. Free forever."
>
    @push('head')
        @vite('resources/js/pages/seo-analyzer.js')
    @endpush

    <main>
        <x-tool-hero
            badge="Instant on-page SEO audit"
            description="Paste any URL and get a full on-page SEO audit in seconds - title tags, meta, headings, Open Graph, image ALT text, and more. No account needed."
            :primary-cta="['label' => 'Analyze Now', 'href' => '#analyzer-panel']"
            :secondary-cta="['label' => 'How it works', 'href' => '#how-it-works']"
        >
            <x-slot:title>Analyze. Score.<br><span class="s-it text-accent">Rank higher.</span></x-slot:title>
            <x-slot:rightPanel>
                <x-preview-card variant="gauge" icon="fa-solid fa-magnifying-glass-chart" icon-bg="linear-gradient(135deg,#a3e635,#0694a2)" icon-color="#061c21" title="SEO Analyzer" subtitle="On-page audit">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-[11px] uppercase tracking-[0.25em] font-semibold" style="color:#76d0d9">SEO Score</span>
                        <span class="text-2xl font-700" style="color:#a3e635">84<span class="text-sm opacity-60">/100</span></span>
                    </div>
                    <div class="h-2 rounded-full overflow-hidden mb-4" style="background:rgba(255,255,255,0.08)">
                        <div class="h-full rounded-full" style="width:84%;background:linear-gradient(90deg,#0694a2,#16bdca,#a3e635)"></div>
                    </div>
                    <div class="grid grid-cols-3 gap-2 text-center">
                        @foreach ([
                            ['l' => 'Passed', 'v' => 18, 'c' => '#a3e635'],
                            ['l' => 'Warnings', 'v' => 3, 'c' => '#fbbf24'],
                            ['l' => 'Issues', 'v' => 1, 'c' => '#f87171'],
                        ] as $s)
                            <div class="rounded-lg py-2" style="background:rgba(255,255,255,0.04)">
                                <p class="text-base font-700 leading-none" style="color:{{ $s['c'] }}">{{ $s['v'] }}</p>
                                <p class="text-[9px] uppercase tracking-wider mt-1" style="color:#76d0d9">{{ $s['l'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </x-preview-card>
            </x-slot:rightPanel>
        </x-tool-hero>

        <x-tool-ticker :items="[
            ['icon' => 'fa-solid fa-heading', 'color' => '#0694a2', 'label' => 'Title Tag'],
            ['icon' => 'fa-solid fa-align-left', 'color' => '#16bdca', 'label' => 'Meta Description'],
            ['icon' => 'fa-solid fa-list-ol', 'color' => '#a3e635', 'label' => 'Headings H1-H3'],
            ['icon' => 'fa-solid fa-share-nodes', 'color' => '#7edce2', 'label' => 'Open Graph'],
            ['icon' => 'fa-brands fa-x-twitter', 'color' => '#0694a2', 'label' => 'Twitter Cards'],
            ['icon' => 'fa-solid fa-code', 'color' => '#a3e635', 'label' => 'Schema.org JSON-LD'],
            ['icon' => 'fa-solid fa-link', 'color' => '#16bdca', 'label' => 'Canonical URL'],
            ['icon' => 'fa-solid fa-image', 'color' => '#7edce2', 'label' => 'ALT Text Audit'],
            ['icon' => 'fa-solid fa-robot', 'color' => '#0694a2', 'label' => 'Robots Meta'],
            ['icon' => 'fa-solid fa-mobile-screen', 'color' => '#a3e635', 'label' => 'Viewport'],
            ['icon' => 'fa-solid fa-bookmark', 'color' => '#16bdca', 'label' => 'Favicon'],
            ['icon' => 'fa-solid fa-gauge-high', 'color' => '#7edce2', 'label' => 'Page Weight'],
            ['icon' => 'fa-solid fa-language', 'color' => '#0694a2', 'label' => 'HTML Lang'],
            ['icon' => 'fa-solid fa-shield-halved', 'color' => '#a3e635', 'label' => 'HTTPS Check'],
        ]" />

        <x-how-it-works :steps="[
            ['icon' => 'fa-solid fa-link', 'title' => 'Enter URL', 'desc' => 'Paste any public URL - a homepage, landing page, or blog post - and hit Analyze. That\'s all it takes to get started.'],
            ['icon' => 'fa-solid fa-magnifying-glass-chart', 'title' => 'Deep Audit', 'desc' => 'We scan 10+ SEO signals in one pass - title tag, meta description, headings, OG tags, ALT text, canonical, schema, robots, and more.'],
            ['icon' => 'fa-solid fa-circle-check', 'title' => 'Score & Fix', 'desc' => 'Get a clear score out of 100. Every flagged issue comes with a one-line fix you can apply immediately - no guesswork, no vague advice.'],
        ]">
            <x-slot:subtitle>Three steps to a <span class="s-it text-accent">perfect SEO score</span></x-slot:subtitle>
        </x-how-it-works>

        <x-feature-highlights
            badge="Why this audit"
            description="A clear breakdown of every signal Google cares about - and the exact fix for each issue, in plain English."
            :features="[
                ['icon' => 'fa-solid fa-magnifying-glass-chart', 'title' => '10+ signals checked', 'desc' => 'Title, meta, headings, OG, Twitter cards, canonical, schema, robots, viewport, ALT text, favicon, page weight - all audited in a single pass.'],
                ['icon' => 'fa-solid fa-wand-magic-sparkles', 'title' => 'Actionable fixes', 'desc' => 'Every issue ships with a one-line fix you can apply right away - no rabbit holes, no learn-more loops.'],
                ['icon' => 'fa-solid fa-code', 'title' => 'Schema-aware', 'desc' => 'Detects JSON-LD types present on the page and flags missing structured data that can unlock rich results in search.'],
                ['icon' => 'fa-solid fa-mobile-screen', 'title' => 'Mobile-first checks', 'desc' => 'Validates the viewport meta tag and warns if your layout is likely to break on small screens - before it costs you rankings.'],
                ['icon' => 'fa-solid fa-chart-column', 'title' => 'Pass / warn / fail', 'desc' => 'Every check is color-coded. See at a glance what\'s healthy, what\'s borderline, and what needs immediate attention.'],
                ['icon' => 'fa-solid fa-database', 'title' => 'Nothing stored', 'desc' => 'We fetch your page once, score it, and discard everything. No logs, no stored URLs, no analytics on your content.'],
            ]"
        >
            <x-slot:title>Beyond a simple <span class="s-it text-accent">score.</span></x-slot:title>
        </x-feature-highlights>

        <x-analyzer-section>
            <div id="seo-analyzer-app"></div>
        </x-analyzer-section>

        <x-faq-section :items="[
            ['q' => 'Does it work on JavaScript-rendered pages?', 'a' => 'We fetch server-rendered HTML, so SPAs without pre-rendering won\'t show their full DOM. Most modern frameworks - Next.js, Nuxt, Astro - ship hydrated HTML and audit cleanly.'],
            ['q' => 'How is the SEO score calculated?', 'a' => 'Each check is weighted by real-world SEO impact. Title tags and meta descriptions carry more weight than favicon detection. A perfect 100 means every signal we check is healthy.'],
            ['q' => 'Are my URLs stored anywhere?', 'a' => 'No. Every request is fetched, parsed, and immediately discarded. We don\'t log URLs, response content, or anything else from your pages.'],
            ['q' => 'Can I audit pages behind a login?', 'a' => 'Not directly - we can\'t carry your session cookies. Audit the public version of the page, or run it against a staging URL that\'s open to the internet.'],
            ['q' => 'Why is HTTPS flagged as an issue?', 'a' => 'Google ranks HTTPS pages higher than HTTP, and modern browsers display security warnings on unencrypted pages. Migrating to HTTPS is one of the highest-ROI SEO changes you can make.'],
        ]" />

        <x-related-tools
            current-key="seo-analyzer"
            :tool-keys="['link-checker', 'alt-checker', 'faq-extractor']"
            title="Useful tools to run next"
            description="Once your page audit looks good, check the links, clean up image ALT text, and turn page FAQs into structured content."
        />
    </main>
</x-layout>
