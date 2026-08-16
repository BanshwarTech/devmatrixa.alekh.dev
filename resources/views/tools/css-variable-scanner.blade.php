<x-layout
    title="CSS Variable Scanner – Extract CSS Custom Props"
    description="Scan any webpage and extract all CSS custom properties (variables). See tokens for colors, spacing, and typography used in the design system."
    og-title="CSS Variable Scanner — Audit Every CSS Custom Property on Any Site | Devmatrixa"
    og-description="Paste any URL and instantly see every CSS custom property the site declares — auto-classified by type, usage-counted, and grouped by purpose. Free and zero data stored."
>
    @push('head')
        @vite('resources/js/pages/css-variable-scanner.js')
    @endpush

    <main>
        <x-tool-hero
            badge="--custom-properties Audit"
            description="Extract all CSS custom properties from any live site,colors, sizes, and fonts auto-grouped by type with usage counts so you can read any design system at a glance."
            :primary-cta="['label' => 'Scan Variables', 'href' => '#analyzer-panel']"
        >
            <x-slot:title>Audit every <span class="s-it text-accent">CSS variable.</span></x-slot:title>
            <x-slot:rightPanel>
                <x-preview-card variant="terminal" icon="fa-solid fa-sliders" icon-bg="linear-gradient(135deg,#0694a2,#a3e635)" title="CSS Variables" subtitle="Grouped by type">
                    <div class="space-y-1.5">
                        @foreach ([
                            ['name' => '--c-bg', 'value' => '#061c21', 'color' => true, 'count' => 42],
                            ['name' => '--c-accent', 'value' => '#0694a2', 'color' => true, 'count' => 28],
                            ['name' => '--c-radius', 'value' => '12px', 'color' => false, 'count' => 16],
                            ['name' => '--font-sans', 'value' => 'Inter', 'color' => false, 'count' => 9],
                        ] as $v)
                            <div class="flex items-center gap-2 text-xs rounded-lg px-2.5 py-1.5" style="background:rgba(255,255,255,0.04)">
                                @if ($v['color'])
                                    <div class="w-4 h-4 rounded shrink-0" style="background:{{ $v['value'] }};border:1px solid var(--c-border)"></div>
                                @endif
                                <span class="font-mono text-[10px] font-semibold truncate" style="color:var(--c-text)">{{ $v['name'] }}</span>
                                <span class="font-mono text-[10px] ml-auto" style="color:var(--c-muted)">{{ $v['value'] }}</span>
                                <span class="text-[9px]" style="color:#a3e635">&times;{{ $v['count'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </x-preview-card>
            </x-slot:rightPanel>
        </x-tool-hero>

        <x-tool-ticker :items="[
            ['icon' => 'fa-solid fa-sliders', 'color' => '#0694a2', 'label' => 'Custom Properties'],
            ['icon' => 'fa-solid fa-code', 'color' => '#16bdca', 'label' => '--color Tokens'],
            ['icon' => 'fa-solid fa-droplet', 'color' => '#a3e635', 'label' => 'Color Vars'],
            ['icon' => 'fa-solid fa-ruler', 'color' => '#7edce2', 'label' => 'Size Vars'],
            ['icon' => 'fa-solid fa-font', 'color' => '#0694a2', 'label' => 'Font Vars'],
            ['icon' => 'fa-solid fa-layer-group', 'color' => '#16bdca', 'label' => 'Design Tokens'],
            ['icon' => 'fa-solid fa-percent', 'color' => '#a3e635', 'label' => 'Usage Counts'],
            ['icon' => 'fa-solid fa-bullseye', 'color' => '#7edce2', 'label' => 'Scopes'],
            ['icon' => 'fa-solid fa-file-code', 'color' => '#0694a2', 'label' => 'Inline Styles'],
            ['icon' => 'fa-solid fa-folder-tree', 'color' => '#16bdca', 'label' => 'Linked CSS'],
            ['icon' => 'fa-solid fa-tags', 'color' => '#a3e635', 'label' => 'Token Names'],
            ['icon' => 'fa-solid fa-bolt', 'color' => '#7edce2', 'label' => 'Auto Classify'],
        ]" />

        <x-how-it-works :steps="[
            ['icon' => 'fa-solid fa-link', 'title' => 'Paste Your URL', 'desc' => 'Enter any URL,works on Tailwind, shadcn, MUI, custom design systems, or any site that uses CSS custom properties.'],
            ['icon' => 'fa-solid fa-sliders', 'title' => 'Extract Variables', 'desc' => 'Every --custom-property declared in inline styles, style blocks, and linked CSS files is found and pulled into the results.'],
            ['icon' => 'fa-solid fa-layer-group', 'title' => 'Grouped by Type', 'desc' => 'Variables are automatically classified as colors, sizes, fonts, or other,each with a usage count so you know what actually matters.'],
        ]">
            <x-slot:subtitle>Three steps to <span class="s-it text-accent">design tokens unlocked</span></x-slot:subtitle>
        </x-how-it-works>

        <x-feature-highlights
            badge="Why This Tool"
            description="See every CSS variable a site declares,auto-typed, usage-counted, and grouped by purpose,without opening a single DevTools panel."
            :features="[
                ['icon' => 'fa-solid fa-layer-group', 'title' => 'Auto-Grouped by Type', 'desc' => 'Colors, sizes, fonts, durations, and shadows are automatically sorted into separate buckets, making any design system readable in seconds.'],
                ['icon' => 'fa-solid fa-percent', 'title' => 'Usage Counts', 'desc' => 'Each variable shows exactly how many times it is referenced elsewhere in the stylesheet,making it easy to spot dead or redundant tokens at a glance.'],
                ['icon' => 'fa-solid fa-droplet', 'title' => 'Color Swatch Preview', 'desc' => 'Color variables render as an inline swatch right in the results, so you can read the full palette without copying a single value into a picker.'],
                ['icon' => 'fa-solid fa-folder-tree', 'title' => 'Inline and Linked CSS', 'desc' => 'Scans the page\'s inline styles alongside every linked external stylesheet, giving you a complete token map from all CSS sources in one pass.'],
                ['icon' => 'fa-solid fa-copy', 'title' => 'Copy Any Variable', 'desc' => 'Click any row to copy the variable name or its value instantly,ready to paste directly into your code, no reformatting needed.'],
                ['icon' => 'fa-solid fa-database', 'title' => 'No Data Stored', 'desc' => 'Stylesheets are fetched, parsed entirely in memory, and immediately discarded. Nothing is ever logged, cached, or persisted.'],
            ]"
        >
            <x-slot:title>Design tokens, <span class="s-it text-accent">decoded.</span></x-slot:title>
        </x-feature-highlights>

        <x-analyzer-section>
            <div id="css-variable-scanner-app"></div>
        </x-analyzer-section>

        <x-faq-section :items="[
            ['q' => 'What is the difference between declarations and usage count?', 'a' => 'Declarations are how many times a variable is defined,for example, once on :root and again on .dark. Usage count is how many other CSS rules reference that variable via var(...).'],
            ['q' => 'Does it pick up variables defined inside @media queries?', 'a' => 'Yes. Variable declarations inside @media or @supports blocks are captured, though only the final declared value for each variable is shown in the results.'],
            ['q' => 'Why are some variables marked as type other?', 'a' => 'If a value is not recognisably a color, size, font, duration, or number, it is left as other. Computed expressions and complex calc() values most commonly fall into this category.'],
            ['q' => 'Can it read variables set by JavaScript?', 'a' => 'No. Only variables declared directly in CSS are detected. Variables set at runtime via element.style.setProperty are invisible to a static page fetch.'],
            ['q' => 'Are the URLs I submit stored anywhere?', 'a' => 'No. Every request is processed and discarded immediately. No URLs or response content are ever logged or retained.'],
        ]" />

        <x-related-tools
            current-key="css-variable-scanner"
            :tool-keys="['tailwind-extractor', 'css-to-tailwind']"
            title="Related tools."
            description="Pick the next tool that helps you finish the job faster."
        />
    </main>
</x-layout>
