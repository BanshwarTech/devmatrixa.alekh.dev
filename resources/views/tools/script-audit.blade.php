<x-layout
    title="Script Audit – Analyze All JS Scripts on a Page"
    description="Audit every JavaScript file loaded on a webpage. See script sizes, load types (async/defer), and third-party scripts blocking your render."
    keywords="javascript audit tool, script audit, page scripts analyzer, third party script checker, js performance audit"
>
    @push('head')
        @vite('resources/js/pages/script-audit.js')
    @endpush

    <main>
        <x-tool-hero
            badge="Script bloat detection"
            description="Crawl your entire site and find scripts loaded in multiple versions, included twice on a page, or repeated across every page."
            :primary-cta="['label' => 'Audit Scripts', 'href' => '#analyzer-panel']"
        >
            <x-slot:title>Find script<br><span class="s-it text-accent">bloat &amp; duplicates.</span></x-slot:title>
            <x-slot:rightPanel>
                <x-preview-card variant="terminal" icon="fa-solid fa-code-branch" icon-bg="linear-gradient(135deg,#f59e0b,#0694a2)" icon-color="#fff" title="Script Bloat" subtitle="3 issues found">
                    <div class="space-y-2">
                        <div class="rounded-lg px-2.5 py-2" style="background:rgba(249,115,22,0.07);border:1px solid rgba(249,115,22,0.18)">
                            <p class="text-xs font-semibold" style="color:var(--c-text)">jquery — 2 versions</p>
                            <p class="text-[10px] mt-0.5" style="color:var(--c-muted)">v2.2.4 + v3.6.0 loaded</p>
                        </div>
                        <div class="rounded-lg px-2.5 py-2" style="background:rgba(248,113,113,0.07);border:1px solid rgba(248,113,113,0.18)">
                            <p class="text-xs font-semibold" style="color:var(--c-text)">analytics.js × 2</p>
                            <p class="text-[10px] mt-0.5" style="color:var(--c-muted)">Duplicate on /home</p>
                        </div>
                        <div class="rounded-lg px-2.5 py-2" style="background:rgba(6,148,162,0.07);border:1px solid rgba(6,148,162,0.18)">
                            <p class="text-xs font-semibold" style="color:var(--c-text)">gtm.js — every page</p>
                            <p class="text-[10px] mt-0.5" style="color:var(--c-muted)">15 / 15 pages</p>
                        </div>
                    </div>
                </x-preview-card>
            </x-slot:rightPanel>
        </x-tool-hero>

        <x-tool-ticker :items="[
            ['icon' => 'fa-solid fa-code-branch', 'color' => '#0694a2', 'label' => 'Script Audit'],
            ['icon' => 'fa-solid fa-copy', 'color' => '#16bdca', 'label' => 'Duplicates'],
            ['icon' => 'fa-solid fa-layer-group', 'color' => '#a3e635', 'label' => 'Multi-Version'],
            ['icon' => 'fa-solid fa-bug', 'color' => '#7edce2', 'label' => 'Site Crawl'],
            ['icon' => 'fa-solid fa-bolt', 'color' => '#0694a2', 'label' => 'Async'],
            ['icon' => 'fa-solid fa-pause', 'color' => '#16bdca', 'label' => 'Defer'],
            ['icon' => 'fa-solid fa-ban', 'color' => '#a3e635', 'label' => 'Render-Blocking'],
            ['icon' => 'fa-solid fa-network-wired', 'color' => '#7edce2', 'label' => 'Third-Party'],
            ['icon' => 'fa-solid fa-file-code', 'color' => '#0694a2', 'label' => 'Inline Scripts'],
            ['icon' => 'fa-solid fa-chart-line', 'color' => '#16bdca', 'label' => 'Tracking'],
            ['icon' => 'fa-solid fa-cloud', 'color' => '#a3e635', 'label' => 'CDN'],
            ['icon' => 'fa-solid fa-triangle-exclamation', 'color' => '#7edce2', 'label' => 'JS Bloat'],
        ]" />

        <x-how-it-works :steps="[
            ['icon' => 'fa-solid fa-bug', 'title' => 'Paste Your URL', 'desc' => 'Enter the homepage URL — we crawl up to 15 internal pages of the same domain.'],
            ['icon' => 'fa-solid fa-code-branch', 'title' => 'Collect Every Script', 'desc' => 'Extract every <script src> from each page and group by file, library and version.'],
            ['icon' => 'fa-solid fa-triangle-exclamation', 'title' => 'Find Bloat', 'desc' => 'Detect same-page duplicates, multi-version libraries (jQuery 2 + 3), and cross-page repeats.'],
        ]">
            <x-slot:subtitle>Three steps to <span class="s-it text-accent">a leaner JS bundle</span></x-slot:subtitle>
        </x-how-it-works>

        <x-feature-highlights
            badge="Why this tool"
            description="Site-wide script crawl that finds the duplicates, multi-versions, and per-page reloads slowing your site down."
            :features="[
                ['icon' => 'fa-solid fa-bug', 'title' => 'Multi-page crawl', 'desc' => 'Crawls up to 15 internal pages, not just one — duplicates and version conflicts only surface across multiple URLs.'],
                ['icon' => 'fa-solid fa-copy', 'title' => 'Same-page duplicates', 'desc' => 'Catches scripts that are loaded twice on a single page — a common copy-paste tag-manager mistake.'],
                ['icon' => 'fa-solid fa-layer-group', 'title' => 'Multi-version detection', 'desc' => 'Flags libraries (jQuery, React, GA) loaded in more than one version across the site.'],
                ['icon' => 'fa-solid fa-network-wired', 'title' => 'Cross-page repeats', 'desc' => 'Spots scripts loaded on every page that don\'t need to be — easy global → page-scoped wins.'],
                ['icon' => 'fa-solid fa-gauge-high', 'title' => 'Bloat indicator', 'desc' => 'A single bloat score summarises the number of issues so you can track improvement over time.'],
                ['icon' => 'fa-solid fa-database', 'title' => 'No data stored', 'desc' => 'Each crawled page is fetched, parsed in-memory, and discarded — we keep nothing.'],
            ]"
        >
            <x-slot:title>Cut the JavaScript <span class="s-it text-accent">bloat.</span></x-slot:title>
        </x-feature-highlights>

        <x-analyzer-section>
            <div id="script-audit-app"></div>
        </x-analyzer-section>

        <x-faq-section :items="[
            ['q' => 'How does it identify the same library at different versions?', 'a' => 'We normalise script URLs (stripping versions and CDN paths) and look up the underlying library by filename and known CDN patterns — so jquery-3.6.0.min.js and jquery-2.2.4.min.js both bucket as \'jquery\'.'],
            ['q' => 'How many pages does the crawler hit?', 'a' => 'Up to 15 internal pages per audit. The starting URL plus internal links discovered from it — enough to expose duplication patterns without being abusive.'],
            ['q' => 'Does it execute the JavaScript?', 'a' => 'No — we only inspect the script tags in the HTML. Dynamically-injected scripts loaded via JS are not analysed.'],
            ['q' => 'Will it flag third-party tags I need?', 'a' => 'It reports the data, not the decision. GTM, GA, and pixels show up so you can decide which are truly required everywhere vs. scoped to a few templates.'],
            ['q' => 'Are my URLs stored anywhere?', 'a' => 'No. Every request is processed and discarded — we don\'t log URLs or response content.'],
        ]" />

        <x-related-tools current-key="script-audit" category="web" />
    </main>
</x-layout>
