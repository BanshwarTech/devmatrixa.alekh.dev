<x-layout
    title="Typography SEO Checker – Audit Fonts & Readability"
    description="Check typography choices on any webpage for SEO and readability. Analyze font size, line height, contrast, and readability score instantly."
>
    @push('head')
        @vite('resources/js/pages/typography-seo-checker.js')
    @endpush

    <main>
        <x-tool-hero
            badge="Font-size SEO audit"
            description="Audit font sizes of H1–H6, paragraphs, body and more. Compare against SEO-recommended ranges and get instant CSS fixes."
            :primary-cta="['label' => 'Audit Typography', 'href' => '#analyzer-panel']"
        >
            <x-slot:title>Typography that<br><span class="s-it text-accent">ranks higher.</span></x-slot:title>
            <x-slot:rightPanel>
                <x-preview-card variant="gauge" icon="fa-solid fa-font" icon-bg="linear-gradient(135deg,#a3e635,#0694a2)" icon-color="#061c21" title="Type Scale" subtitle="Per element">
                    <div class="space-y-2">
                        @foreach ([
                            ['tag' => 'H1', 'size' => '48px', 'status' => 'ideal', 'col' => '#65a30d'],
                            ['tag' => 'H2', 'size' => '32px', 'status' => 'ideal', 'col' => '#65a30d'],
                            ['tag' => 'H3', 'size' => '20px', 'status' => 'ideal', 'col' => '#65a30d'],
                            ['tag' => 'body', 'size' => '12px', 'status' => 'too small', 'col' => '#f87171'],
                            ['tag' => 'small', 'size' => '10px', 'status' => 'too small', 'col' => '#f87171'],
                        ] as $e)
                            <div class="flex items-center gap-2 text-xs rounded-lg px-2.5 py-1.5" style="background:rgba(255,255,255,0.04)">
                                <span class="text-[10px] font-black px-1.5 py-0.5 rounded" style="background:rgba(6,148,162,0.15);color:#0694a2">{{ $e['tag'] }}</span>
                                <span class="font-mono text-[10px]" style="color:{{ $e['col'] }}">{{ $e['size'] }}</span>
                                <span class="ml-auto text-[9px]" style="color:{{ $e['col'] }}">{{ $e['status'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </x-preview-card>
            </x-slot:rightPanel>
        </x-tool-hero>

        <x-tool-ticker :items="[
            ['icon' => 'fa-solid fa-font', 'color' => '#0694a2', 'label' => 'Font Size'],
            ['icon' => 'fa-solid fa-heading', 'color' => '#16bdca', 'label' => 'Heading Scale'],
            ['icon' => 'fa-solid fa-paragraph', 'color' => '#a3e635', 'label' => 'Body Copy'],
            ['icon' => 'fa-solid fa-arrows-up-down', 'color' => '#7edce2', 'label' => 'Line Height'],
            ['icon' => 'fa-solid fa-book-open', 'color' => '#0694a2', 'label' => 'Readability'],
            ['icon' => 'fa-solid fa-sitemap', 'color' => '#16bdca', 'label' => 'Hierarchy'],
            ['icon' => 'fa-solid fa-circle-half-stroke', 'color' => '#a3e635', 'label' => 'Contrast'],
            ['icon' => 'fa-solid fa-ruler', 'color' => '#7edce2', 'label' => 'px / rem / em'],
            ['icon' => 'fa-solid fa-mobile-screen', 'color' => '#0694a2', 'label' => 'Responsive'],
            ['icon' => 'fa-solid fa-font', 'color' => '#16bdca', 'label' => 'Typography'],
            ['icon' => 'fa-solid fa-wand-magic-sparkles', 'color' => '#a3e635', 'label' => 'CSS Fixes'],
            ['icon' => 'fa-solid fa-universal-access', 'color' => '#7edce2', 'label' => 'Accessibility'],
        ]" />

        <x-how-it-works :steps="[
            ['icon' => 'fa-solid fa-link', 'title' => 'Paste Your URL', 'desc' => 'Enter any URL — we fetch the page and parse the first 3 stylesheets.'],
            ['icon' => 'fa-solid fa-font', 'title' => 'Measure Font Sizes', 'desc' => 'We extract font-size for H1–H6, body, p, li and small from CSS and inline styles.'],
            ['icon' => 'fa-solid fa-wand-magic-sparkles', 'title' => 'CSS Fix Suggestions', 'desc' => 'Each element is compared against SEO-recommended ranges — get exact CSS fix snippets.'],
        ]">
            <x-slot:subtitle>Three steps to <span class="s-it text-accent">better readability</span></x-slot:subtitle>
        </x-how-it-works>

        <x-feature-highlights
            badge="Why this audit"
            description="Font sizes are an underrated SEO signal — too small hurts mobile usability, too large breaks visual hierarchy. We compare your site against tested SEO ranges."
            :features="[
                ['icon' => 'fa-solid fa-font', 'title' => '10 elements checked', 'desc' => 'H1–H6, body, paragraph, list items, and small text — every typography-bearing element gets audited.'],
                ['icon' => 'fa-solid fa-bullseye', 'title' => 'SEO-recommended ranges', 'desc' => 'Each element is compared against documented best-practice ranges (e.g. body 14–18px, H1 32–48px).'],
                ['icon' => 'fa-solid fa-wand-magic-sparkles', 'title' => 'CSS fix snippets', 'desc' => 'Each issue ships with a ready-to-paste CSS rule that bumps the font-size into the healthy range.'],
                ['icon' => 'fa-solid fa-ruler', 'title' => 'px, rem, em supported', 'desc' => 'We normalize every unit — REM, EM, and pixel values are all converted to the same scale for comparison.'],
                ['icon' => 'fa-solid fa-universal-access', 'title' => 'Accessibility-aware', 'desc' => 'Body copy under 12px is flagged as an a11y violation, not just an SEO issue — both reasons appear in the fix.'],
                ['icon' => 'fa-solid fa-database', 'title' => 'Nothing stored', 'desc' => 'We fetch and parse three stylesheets, then discard everything. No URLs, no styles, no logs.'],
            ]"
        >
            <x-slot:title>Readability that <span class="s-it text-accent">ranks higher.</span></x-slot:title>
        </x-feature-highlights>

        <x-analyzer-section>
            <div id="typography-seo-checker-app"></div>
        </x-analyzer-section>

        <x-faq-section :items="[
            ['q' => 'What font size is best for SEO?', 'a' => 'Body copy of 14–18px is the sweet spot. Anything smaller hurts mobile readability (which Google measures), anything larger makes content feel bloated.'],
            ['q' => 'Why does mobile body size matter?', 'a' => 'Google\'s mobile-first indexing means your mobile typography directly shapes how your pages are ranked. Tiny body text triggers usability penalties.'],
            ['q' => 'Does it check line-height too?', 'a' => 'We focus on font-size for now — line-height is on the roadmap. For now, target 1.5× the font-size as a safe default.'],
            ['q' => 'Why only three stylesheets?', 'a' => 'Most sites concentrate their typography rules in the first 2–3 stylesheets. Scanning more would slow the audit without changing the verdict.'],
            ['q' => 'Are my URLs stored?', 'a' => 'No. Every URL is fetched, parsed, and discarded — we never log or persist them.'],
        ]" />

        <x-related-tools current-key="typography-seo-checker" category="seo" />
    </main>
</x-layout>
