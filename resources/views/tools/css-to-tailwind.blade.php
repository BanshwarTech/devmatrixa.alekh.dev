<x-layout
    title="CSS to Tailwind Converter – Free Online Tool"
    description="Convert raw CSS code to Tailwind utility classes instantly. Speed up your migration to Tailwind CSS without hunting through the docs manually."
    keywords="css to tailwind converter, css to tailwind css, convert css to tailwind classes, tailwind migration tool"
>
    @push('head')
        @vite('resources/js/pages/css-to-tailwind.js')
    @endpush

    <main>
        <x-tool-hero
            badge="Instant CSS &rarr; Tailwind"
            description="Paste raw CSS and get the closest Tailwind utility classes instantly. 180+ mappings with smart spacing and font-size matching."
            :primary-cta="['label' => 'Convert Now', 'href' => '#converter']"
        >
            <x-slot:title>Convert any CSS to<br><span class="s-it text-accent">Tailwind classes.</span></x-slot:title>
            <x-slot:rightPanel>
                <x-preview-card variant="terminal" icon="fa-solid fa-wand-magic-sparkles" icon-bg="linear-gradient(135deg,#16bdca,#a3e635)" title="CSS &rarr; Tailwind" subtitle="Instant mapping">
                    <div class="space-y-2 text-[11px] font-mono">
                        <div class="rounded p-2" style="background:rgba(248,113,113,0.06)">
                            <span style="color:#f87171">padding</span>: <span style="color:var(--c-muted)">16px</span>
                        </div>
                        <div class="text-center text-base" style="color:#a3e635">&darr;</div>
                        <div class="rounded p-2" style="background:rgba(163,230,53,0.08)">
                            <span style="color:#a3e635">p-4</span>
                        </div>
                    </div>
                </x-preview-card>
            </x-slot:rightPanel>
        </x-tool-hero>

        <x-tool-ticker :items="[
            ['icon' => 'fa-solid fa-wand-magic-sparkles', 'color' => '#0694a2', 'label' => 'CSS to Tailwind'],
            ['icon' => 'fa-brands fa-css3-alt', 'color' => '#16bdca', 'label' => 'Utility Classes'],
            ['icon' => 'fa-solid fa-arrows-left-right', 'color' => '#a3e635', 'label' => 'Spacing'],
            ['icon' => 'fa-solid fa-arrow-up-short-wide', 'color' => '#7edce2', 'label' => 'Padding'],
            ['icon' => 'fa-solid fa-grip', 'color' => '#0694a2', 'label' => 'Flexbox'],
            ['icon' => 'fa-solid fa-table-cells-large', 'color' => '#16bdca', 'label' => 'Grid'],
            ['icon' => 'fa-solid fa-font', 'color' => '#a3e635', 'label' => 'Typography'],
            ['icon' => 'fa-solid fa-fill-drip', 'color' => '#7edce2', 'label' => 'Backgrounds'],
            ['icon' => 'fa-solid fa-border-all', 'color' => '#0694a2', 'label' => 'Borders'],
            ['icon' => 'fa-solid fa-text-height', 'color' => '#16bdca', 'label' => 'Font Size'],
            ['icon' => 'fa-solid fa-layer-group', 'color' => '#a3e635', 'label' => 'Z-Index'],
            ['icon' => 'fa-solid fa-bolt', 'color' => '#7edce2', 'label' => 'Instant Convert'],
        ]" />

        <x-how-it-works :steps="[
            ['icon' => 'fa-solid fa-clipboard', 'title' => 'Paste Raw CSS', 'desc' => 'Paste any CSS,full rule blocks or just declarations, with comments or without.'],
            ['icon' => 'fa-solid fa-wand-magic-sparkles', 'title' => 'Smart Mapping', 'desc' => 'We map static properties, plus dynamic spacing, font-size, and z-index to the closest Tailwind class.'],
            ['icon' => 'fa-solid fa-copy', 'title' => 'Copy & Ship', 'desc' => 'Get the final class string with one click,ready to paste into your JSX or HTML.'],
        ]">
            <x-slot:subtitle>Three steps to <span class="s-it text-accent">Tailwind utilities</span></x-slot:subtitle>
        </x-how-it-works>

        <x-feature-highlights
            badge="Why this tool"
            description="A smart converter that knows Tailwind's scale, not just a 1:1 lookup table."
            :features="[
                ['icon' => 'fa-solid fa-bullseye', 'title' => '180+ mappings', 'desc' => 'From flexbox to z-index, the most commonly used properties are mapped to their Tailwind utility.'],
                ['icon' => 'fa-solid fa-wand-magic-sparkles', 'title' => 'Smart spacing', 'desc' => 'Pixel and rem spacing values are snapped to the nearest Tailwind spacing token, not just exact matches.'],
                ['icon' => 'fa-solid fa-text-height', 'title' => 'Font-size matching', 'desc' => 'Font-size values resolve to text-xs through text-9xl using Tailwind\'s default type scale.'],
                ['icon' => 'fa-solid fa-bolt', 'title' => 'Instant + offline', 'desc' => 'Conversion runs in your browser,there are no API calls, no rate limits, no waiting.'],
                ['icon' => 'fa-solid fa-copy', 'title' => 'One-click copy', 'desc' => 'The final Tailwind class string is copy-ready as soon as conversion finishes.'],
                ['icon' => 'fa-solid fa-shield-halved', 'title' => 'Nothing leaves your browser', 'desc' => 'Your CSS is parsed locally,we never see what you paste in.'],
            ]"
        >
            <x-slot:title>CSS to Tailwind without <span class="s-it text-accent">the grunt work.</span></x-slot:title>
        </x-feature-highlights>

        <x-analyzer-section id="converter" max-width="6xl">
            <div id="css-to-tailwind-app"></div>
        </x-analyzer-section>

        <x-faq-section :items="[
            ['q' => 'Will every CSS property map to a Tailwind class?', 'a' => 'No,Tailwind doesn\'t cover every CSS feature, and some values require arbitrary classes. Unmatched declarations are listed separately so you can handle them manually.'],
            ['q' => 'Does it support arbitrary values like w-[123px]?', 'a' => 'For spacing, font-size, and z-index we snap to the nearest preset. For exact custom values, copy the unmatched declaration and use Tailwind\'s arbitrary value syntax.'],
            ['q' => 'Can I paste full CSS rules with selectors?', 'a' => 'Yes. The parser tolerates full rule blocks, comments, and bare declarations,selectors are ignored, only declarations are converted.'],
            ['q' => 'Does it understand media queries and pseudo-classes?', 'a' => 'Not yet,convert the inner declarations and then wrap them with Tailwind variants like md: or hover: manually.'],
            ['q' => 'Is my CSS sent to a server?', 'a' => 'No. Conversion happens entirely in your browser,we don\'t log or store the CSS you paste.'],
        ]" />

        <x-related-tools current-key="css-to-tailwind" category="code" />
    </main>
</x-layout>
