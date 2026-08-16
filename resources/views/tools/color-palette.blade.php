<x-layout
    title="Color Palette Extractor – Get Colors From Any Site"
    description="Instantly extract the full color palette from any website. Find hex codes for backgrounds, text, buttons, and borders used on any live URL."
    og-title="Color Palette Extractor — Extract Every Color from Any Website | Devmatrixa"
    og-description="Paste any URL and instantly get a visual color palette — hex codes, RGB values, and frequency-ranked swatches from every CSS source on the page. Free and zero data stored."
>
    @push('head')
        @vite('resources/js/pages/color-palette.js')
    @endpush

    <main>
        <x-tool-hero
            badge="Visual Color Extraction"
            description="Extract every color from any website,inline styles, CSS files, and style blocks,delivered as a visual palette with hex and RGB values ready to copy."
            :primary-cta="['label' => 'Extract Palette', 'href' => '#analyzer-panel']"
        >
            <x-slot:title>Steal the <span class="s-it text-accent">color palette.</span></x-slot:title>
            <x-slot:rightPanel>
                <x-preview-card icon="fa-solid fa-palette" icon-bg="linear-gradient(135deg,#a3e635,#0694a2)" title="Site Palette" subtitle="6 dominant colors">
                    <div class="grid grid-cols-3 gap-2">
                        @foreach ([
                            ['hex' => '#0694a2', 'count' => 84],
                            ['hex' => '#a3e635', 'count' => 56],
                            ['hex' => '#16bdca', 'count' => 42],
                            ['hex' => '#061c21', 'count' => 28],
                            ['hex' => '#f87171', 'count' => 14],
                            ['hex' => '#f59e0b', 'count' => 9],
                        ] as $c)
                            <div class="rounded-lg overflow-hidden" style="background:rgba(255,255,255,0.04)">
                                <div class="h-12" style="background:{{ $c['hex'] }}"></div>
                                <div class="px-2 py-1.5">
                                    <p class="font-mono text-[9px] font-semibold" style="color:var(--c-text)">{{ $c['hex'] }}</p>
                                    <p class="text-[9px]" style="color:var(--c-muted)">×{{ $c['count'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-preview-card>
            </x-slot:rightPanel>
        </x-tool-hero>

        <x-tool-ticker :items="[
            ['icon' => 'fa-solid fa-palette', 'color' => '#0694a2', 'label' => 'Color Palette'],
            ['icon' => 'fa-solid fa-hashtag', 'color' => '#16bdca', 'label' => 'Hex Codes'],
            ['icon' => 'fa-solid fa-droplet', 'color' => '#a3e635', 'label' => 'RGB Values'],
            ['icon' => 'fa-solid fa-eye-dropper', 'color' => '#7edce2', 'label' => 'HSL'],
            ['icon' => 'fa-solid fa-fill-drip', 'color' => '#0694a2', 'label' => 'Color Tokens'],
            ['icon' => 'fa-solid fa-copy', 'color' => '#16bdca', 'label' => 'Click to Copy'],
            ['icon' => 'fa-solid fa-trophy', 'color' => '#a3e635', 'label' => 'Frequency'],
            ['icon' => 'fa-solid fa-paintbrush', 'color' => '#7edce2', 'label' => 'Inline Styles'],
            ['icon' => 'fa-solid fa-file-code', 'color' => '#0694a2', 'label' => 'CSS Files'],
            ['icon' => 'fa-solid fa-palette', 'color' => '#16bdca', 'label' => 'Swatches'],
            ['icon' => 'fa-solid fa-circle-half-stroke', 'color' => '#a3e635', 'label' => 'RGBA'],
            ['icon' => 'fa-solid fa-paintbrush', 'color' => '#7edce2', 'label' => 'Brand Colors'],
        ]" />

        <x-how-it-works :steps="[
            ['icon' => 'fa-solid fa-link', 'title' => 'Paste Your URL', 'desc' => 'Enter any URL,the tool fetches the page, its inline styles, and every linked CSS file automatically.'],
            ['icon' => 'fa-solid fa-eye-dropper', 'title' => 'Scan All Colors', 'desc' => 'Hex, rgb, rgba, hsl, and hsla color values are all detected across every source and normalized into clean hex codes.'],
            ['icon' => 'fa-solid fa-palette', 'title' => 'Click to Copy', 'desc' => 'A visual palette is generated with usage counts, sorted by frequency. Click any color swatch to instantly copy its hex code.'],
        ]">
            <x-slot:subtitle>Three steps to <span class="s-it text-accent">the perfect palette</span></x-slot:subtitle>
        </x-how-it-works>

        <x-feature-highlights
            badge="Why This Tool"
            description="Every color, every source, frequency-ranked and copy-ready,your brand research tab can finally close."
            :features="[
                ['icon' => 'fa-solid fa-trophy', 'title' => 'Frequency-Ranked', 'desc' => 'Colors are ordered by how often they actually appear on the page, so dominant brand colors surface at the top where they belong.'],
                ['icon' => 'fa-solid fa-copy', 'title' => 'One-Click Copy', 'desc' => 'Tap any color swatch to copy its hex code instantly,no tooltip hunting, no right-click menus, no extra steps.'],
                ['icon' => 'fa-solid fa-file-code', 'title' => 'Inline and Linked CSS', 'desc' => 'Scans inline style attributes, style blocks, and every linked external stylesheet,so nothing is missed regardless of how the colors are declared.'],
                ['icon' => 'fa-solid fa-droplet', 'title' => 'Hex and RGB Output', 'desc' => 'Every color is normalized to a clean 6-digit hex code plus an RGB triple,ready to drop into any design tool or codebase.'],
                ['icon' => 'fa-solid fa-globe', 'title' => 'Works on Any Site', 'desc' => 'Marketing pages, dashboards, blogs, ecommerce stores,if it ships HTML and CSS, the colors are extracted.'],
                ['icon' => 'fa-solid fa-database', 'title' => 'Nothing Stored', 'desc' => 'URLs and stylesheets are fetched, parsed, and immediately discarded. No data is ever logged or persisted.'],
            ]"
        >
            <x-slot:title>Steal palettes the <span class="s-it text-accent">smart way.</span></x-slot:title>
        </x-feature-highlights>

        <x-analyzer-section>
            <div id="color-palette-app"></div>
        </x-analyzer-section>

        <x-faq-section :items="[
            ['q' => 'Does it detect colors set by JavaScript?', 'a' => 'No. The tool parses the initial HTML and linked CSS only. Colors injected at runtime by JS frameworks,such as inline styles applied after hydration,will not be captured.'],
            ['q' => 'How many colors can it extract?', 'a' => 'There is no hard cap on unique colors. Most sites return between 20 and 200 unique values once near-duplicate shades are merged and consolidated.'],
            ['q' => 'Why are some colors I see on the page missing?', 'a' => 'Background images, SVG-defined gradients, and colors embedded inside image files are not parsed. Only declared CSS color values are detected.'],
            ['q' => 'Can I export the palette as JSON or CSS variables?', 'a' => 'Not yet,individual hex codes can be copied per swatch for now. A bulk export option is planned for a future update.'],
            ['q' => 'Are the URLs I submit stored anywhere?', 'a' => 'No. Every request is processed and discarded immediately. No URLs or response content are ever logged or retained.'],
        ]" />

        <x-related-tools
            current-key="color-palette"
            :tool-keys="['font-detector', 'tech-stack-detector', 'page-weight']"
            title="Related tools."
            description="Pick the next tool that helps you finish the job faster."
        />
    </main>
</x-layout>
