<x-layout
    title="Font Detector – Find Fonts Used on Any Website"
    description="Detect every font loaded on a webpage including Google Fonts, system fonts, and custom webfonts. Free font inspector, no install required."
    keywords="font detector, website font finder, identify fonts on website, google fonts checker, font inspector"
    og-title="Font Detector — Detect Every Font on Any Website | Devmatrixa"
    og-description="Paste any URL and instantly see every font the site loads — source, weights, styles, and usage counts. No DevTools. No extensions. Completely free."
>
    @push('head')
        @vite('resources/js/pages/font-detector.js')
    @endpush

    <main>
        <x-tool-hero
            badge="Font Fingerprinting"
            description="Identify every font used on any website,Google Fonts, custom @font-face declarations, and system stacks,complete with weights and styles, all in one scan."
            :primary-cta="['label' => 'Detect Fonts', 'href' => '#analyzer-panel']"
        >
            <x-slot:title>Detect every <span class="s-it text-accent">font on the page.</span></x-slot:title>
            <x-slot:rightPanel>
                <x-preview-card icon="fa-solid fa-font" icon-bg="linear-gradient(135deg,#16bdca,#0694a2)" icon-color="#fff" title="Detected Fonts" subtitle="Per page">
                    <div class="space-y-2">
                        @foreach ([
                            ['name' => 'Inter', 'source' => 'Google Fonts', 'weights' => '400, 500, 600, 700', 'color' => '#65a30d'],
                            ['name' => 'JetBrains Mono', 'source' => 'Google Fonts', 'weights' => '400, 500', 'color' => '#65a30d'],
                            ['name' => 'Helvetica Neue', 'source' => 'System', 'weights' => 'Fallback', 'color' => '#7edce2'],
                        ] as $f)
                            <div class="rounded-lg px-3 py-2" style="background:rgba(255,255,255,0.04)">
                                <p class="text-xs font-semibold" style="color:var(--c-text);font-family:'{{ $f['name'] }}'">{{ $f['name'] }}</p>
                                <p class="text-[10px] mt-0.5" style="color:{{ $f['color'] }}">{{ $f['source'] }} · <span style="color:var(--c-muted)">{{ $f['weights'] }}</span></p>
                            </div>
                        @endforeach
                    </div>
                </x-preview-card>
            </x-slot:rightPanel>
        </x-tool-hero>

        <x-tool-ticker :items="[
            ['icon' => 'fa-solid fa-font', 'color' => '#0694a2', 'label' => 'Font Family'],
            ['icon' => 'fa-brands fa-google', 'color' => '#16bdca', 'label' => 'Google Fonts'],
            ['icon' => 'fa-solid fa-at', 'color' => '#a3e635', 'label' => '@font-face'],
            ['icon' => 'fa-solid fa-font', 'color' => '#7edce2', 'label' => 'Font Weight'],
            ['icon' => 'fa-solid fa-italic', 'color' => '#0694a2', 'label' => 'Font Style'],
            ['icon' => 'fa-solid fa-server', 'color' => '#16bdca', 'label' => 'System Fonts'],
            ['icon' => 'fa-solid fa-layer-group', 'color' => '#a3e635', 'label' => 'CSS Stack'],
            ['icon' => 'fa-solid fa-sliders', 'color' => '#7edce2', 'label' => 'Variable Fonts'],
            ['icon' => 'fa-solid fa-list', 'color' => '#0694a2', 'label' => 'Usage Counts'],
            ['icon' => 'fa-solid fa-file-import', 'color' => '#16bdca', 'label' => '@import'],
            ['icon' => 'fa-solid fa-link', 'color' => '#a3e635', 'label' => 'Linked Sheets'],
            ['icon' => 'fa-solid fa-magnifying-glass', 'color' => '#7edce2', 'label' => 'Auto Detect'],
        ]" />

        <x-how-it-works :steps="[
            ['icon' => 'fa-solid fa-link', 'title' => 'Paste Your URL', 'desc' => 'Enter any public URL and the tool fetches the full HTML along with every linked stylesheet automatically.'],
            ['icon' => 'fa-solid fa-font', 'title' => 'Scan All Sources', 'desc' => 'Google Fonts links, @import statements, @font-face blocks, and font-family declarations are all detected across every source on the page.'],
            ['icon' => 'fa-solid fa-layer-group', 'title' => 'Grouped Results', 'desc' => 'Fonts are organized by source,Google, custom, and system,with their weights, styles, and usage counts listed clearly alongside each entry.'],
        ]">
            <x-slot:subtitle>Three steps to <span class="s-it text-accent">font fingerprinting</span></x-slot:subtitle>
        </x-how-it-works>

        <x-feature-highlights
            badge="Why This Tool"
            description="Identify every font a site loads,and exactly how it loads,without opening DevTools, diving through source files, or installing an extension."
            :features="[
                ['icon' => 'fa-brands fa-google', 'title' => 'Google Fonts Auto-Detect', 'desc' => 'Recognises Google Fonts links and @import statements automatically, including the exact weights and styles being requested for the page.'],
                ['icon' => 'fa-solid fa-at', 'title' => '@font-face Parsing', 'desc' => 'Custom hosted fonts are detected from @font-face blocks in full detail,including their source URLs and declared unicode ranges.'],
                ['icon' => 'fa-solid fa-sliders', 'title' => 'Weights and Styles', 'desc' => 'Every weight and italic or oblique style in use is listed alongside each font family, so you see the complete picture at a glance.'],
                ['icon' => 'fa-solid fa-list', 'title' => 'Usage Counts', 'desc' => 'Each font shows how many elements actually use it on the page,making it easy to separate the primary typeface from a forgotten or redundant import.'],
                ['icon' => 'fa-solid fa-server', 'title' => 'System Fallback Detection', 'desc' => 'Plain CSS stacks like Helvetica, Arial, sans-serif are classified separately from web fonts, so you always know what is and isn\'t being downloaded.'],
                ['icon' => 'fa-solid fa-eye', 'title' => 'Live Preview', 'desc' => 'Every detected font is rendered in its own typeface directly in the results, so you can see the actual style without leaving the tool.'],
            ]"
        >
            <x-slot:title>Font fingerprints in <span class="s-it text-accent">one scan.</span></x-slot:title>
        </x-feature-highlights>

        <x-analyzer-section>
            <div id="font-detector-app"></div>
        </x-analyzer-section>

        <x-faq-section :items="[
            ['q' => 'Will it detect fonts loaded by JavaScript?', 'a' => 'If the font is requested through a CSS rule present in the initial HTML or a linked stylesheet, yes. Fonts injected purely via JavaScript after page load will be missed.'],
            ['q' => 'What about variable fonts?', 'a' => 'Variable fonts are detected the same way as regular fonts. The declared font-family is surfaced in the results,variation axes are not parsed at this time.'],
            ['q' => 'Why is a font marked as a System Font?', 'a' => 'When a font-family declaration points to a generic CSS stack with no corresponding @font-face rule or webfont link, it is classified as a system fallback,indicating nothing extra is being downloaded by the browser.'],
            ['q' => 'Does it follow @import rules inside imported stylesheets?', 'a' => 'One level of @import is followed. Deeply nested imports are rare in production CSS and are typically flagged as a performance concern regardless.'],
            ['q' => 'Are the URLs I submit stored anywhere?', 'a' => 'No. Every request is processed and discarded immediately. No URLs or response content are ever logged or retained.'],
        ]" />

        <x-related-tools
            current-key="font-detector"
            :tool-keys="['color-palette', 'tech-stack-detector', 'page-weight']"
            title="Related tools."
            description="Pick the next tool that helps you finish the job faster."
        />
    </main>
</x-layout>
