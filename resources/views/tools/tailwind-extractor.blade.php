<x-layout
    title="Tailwind Class Extractor – Scan Any Webpage"
    description="Extract all Tailwind CSS classes used on any live webpage. Reverse-engineer designs and speed up your own Tailwind-based development."
    keywords="tailwind class extractor, extract tailwind css classes, tailwind css scanner, reverse engineer tailwind"
    og-title="Tailwind Extractor — Extract Every Tailwind Class from Any URL | Devmatrixa"
    og-description="Point it at any live site. Get every Tailwind class, grouped by category, with one-click copy on each group. Zero data stored. Completely free."
>
    @push('head')
        @vite('resources/js/pages/tailwind-extractor.js')
    @endpush

    <main>
        <x-tool-hero
            badge="Tailwind Class Extraction"
            description="Scan any live URL and pull out every Tailwind CSS class in use,auto-grouped by category with one-click copy on each group."
            :primary-cta="['label' => 'Extract Now', 'href' => '#analyzer-panel']"
        >
            <x-slot:title>Extract every <span class="s-it text-accent">Tailwind class.</span></x-slot:title>
            <x-slot:rightPanel>
                <x-preview-card variant="terminal" icon="fa-brands fa-css3-alt" icon-bg="linear-gradient(135deg,#0694a2,#16bdca)" icon-color="#fff" title="Tailwind Classes" subtitle="Layout group">
                    <div class="flex flex-wrap gap-1.5">
                        @foreach (['flex', 'items-center', 'justify-between', 'gap-4', 'px-6', 'py-3', 'rounded-2xl', 'bg-teal-500', 'text-white', 'shadow-lg', 'hover:scale-105', 'transition-all'] as $c)
                            <span class="font-mono text-[10px] px-2 py-1 rounded" style="background:rgba(6,148,162,0.10);color:#0694a2;border:1px solid rgba(6,148,162,0.22)">{{ $c }}</span>
                        @endforeach
                    </div>
                </x-preview-card>
            </x-slot:rightPanel>
        </x-tool-hero>

        <x-tool-ticker :items="[
            ['icon' => 'fa-brands fa-css3-alt', 'color' => '#0694a2', 'label' => 'Utility Classes'],
            ['icon' => 'fa-solid fa-ruler', 'color' => '#16bdca', 'label' => 'Spacing'],
            ['icon' => 'fa-solid fa-palette', 'color' => '#a3e635', 'label' => 'Colors'],
            ['icon' => 'fa-solid fa-font', 'color' => '#7edce2', 'label' => 'Typography'],
            ['icon' => 'fa-solid fa-mobile-screen', 'color' => '#0694a2', 'label' => 'Breakpoints'],
            ['icon' => 'fa-solid fa-wand-magic-sparkles', 'color' => '#16bdca', 'label' => 'Variants'],
            ['icon' => 'fa-solid fa-code', 'color' => '#a3e635', 'label' => 'Arbitrary Values'],
            ['icon' => 'fa-solid fa-table-cells', 'color' => '#7edce2', 'label' => 'Layout'],
            ['icon' => 'fa-solid fa-sparkles', 'color' => '#0694a2', 'label' => 'Effects'],
            ['icon' => 'fa-solid fa-layer-group', 'color' => '#16bdca', 'label' => 'Grouped'],
            ['icon' => 'fa-solid fa-copy', 'color' => '#a3e635', 'label' => 'One-Click Copy'],
            ['icon' => 'fa-solid fa-filter', 'color' => '#7edce2', 'label' => 'Filtered'],
        ]" />

        <x-how-it-works :steps="[
            ['icon' => 'fa-solid fa-link', 'title' => 'Paste Your URL', 'desc' => 'Enter any live URL built with Tailwind,a homepage, landing page, product page, or documentation site.'],
            ['icon' => 'fa-solid fa-filter', 'title' => 'Filter Tailwind', 'desc' => 'Every class on the page is scanned and filtered down to only those matching Tailwind\'s prefix conventions. Non-Tailwind classes are stripped out automatically.'],
            ['icon' => 'fa-solid fa-layer-group', 'title' => 'Grouped and Ready to Copy', 'desc' => 'Classes are sorted into Layout, Spacing, Typography, Effects, and more. Copy any entire group in one click and paste straight into your project.'],
        ]">
            <x-slot:subtitle>Three steps to <span class="s-it text-accent">a complete class list</span></x-slot:subtitle>
        </x-how-it-works>

        <x-feature-highlights
            badge="Why This Tool"
            description="Stop guessing how a site styles itself. Get the full Tailwind class list, sorted by intent, with one-click copy on every group."
            :features="[
                ['icon' => 'fa-solid fa-layer-group', 'title' => '12 Categories', 'desc' => 'Classes are automatically sorted into Layout, Spacing, Typography, Colors, Effects, Borders, and more,so you find exactly what you need without scrolling through noise.'],
                ['icon' => 'fa-solid fa-filter', 'title' => 'Tailwind-Only Filter', 'desc' => 'Custom and non-standard classes are stripped away, leaving only clean, canonical Tailwind classes in the output.'],
                ['icon' => 'fa-solid fa-mobile-screen', 'title' => 'Variants Preserved', 'desc' => 'Responsive prefixes like sm:, md:, lg:, plus hover:, focus:, and dark:,every variant is captured exactly as it appears on the page.'],
                ['icon' => 'fa-solid fa-copy', 'title' => 'Copy Any Group', 'desc' => 'One click copies every class in a category group. Paste directly into your JSX or HTML and keep moving.'],
                ['icon' => 'fa-solid fa-globe', 'title' => 'Works on Live URLs', 'desc' => 'Point it at any production site using Tailwind,marketing pages, dashboards, docs, component libraries,it handles them all.'],
                ['icon' => 'fa-solid fa-database', 'title' => 'Zero Data Stored', 'desc' => 'URLs are fetched, parsed, and immediately discarded. Your scans never leave a trace.'],
            ]"
        >
            <x-slot:title>Every class, <span class="s-it text-accent">grouped and ready.</span></x-slot:title>
        </x-feature-highlights>

        <x-analyzer-section>
            <div id="tailwind-extractor-app"></div>
        </x-analyzer-section>

        <x-faq-section :items="[
            ['q' => 'Does it work with Tailwind v3 and v4?', 'a' => 'Yes. The extractor matches any class following Tailwind\'s prefix conventions, which remain consistent across both v3 and v4.'],
            ['q' => 'Will classes from custom Tailwind plugins show up?', 'a' => 'Only if they follow Tailwind\'s standard naming pattern,such as classes from official plugins like @tailwindcss/forms. Fully custom classes outside the convention are filtered out.'],
            ['q' => 'What about arbitrary values like w-[37px]?', 'a' => 'These are captured as-is. Bracket-syntax arbitrary values are valid Tailwind and appear in the output just like any other class.'],
            ['q' => 'Can I scan a page that requires a login?', 'a' => 'No. The tool fetches publicly accessible HTML only. Pages behind authentication cannot be reached.'],
            ['q' => 'Are the URLs I submit stored anywhere?', 'a' => 'No. Every URL is fetched, parsed, and discarded immediately. Nothing is ever logged or retained.'],
        ]" />

        <x-related-tools
            current-key="tailwind-extractor"
            :tool-keys="['css-variable-scanner', 'css-to-tailwind']"
            title="Related tools."
            description="Pick the next tool that helps you finish the job faster."
        />
    </main>
</x-layout>
