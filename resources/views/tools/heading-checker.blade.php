<x-layout
    title="Heading Checker – Audit H1–H6 Tag Structure"
    description="Analyze the heading hierarchy of any webpage. Spot missing H1s, skipped heading levels, and structure issues hurting your on-page SEO."
    og-title="Heading Hierarchy Checker — Audit H1–H6 Structure of Any URL | Devmatrixa"
    og-description="Paste any URL and instantly see your full H1–H6 heading tree. Detect missing H1s, skipped levels, and empty headings — with auto-fix suggestions included. Free and zero data stored."
>
    @push('head')
        @vite('resources/js/pages/heading-checker.js')
    @endpush

    <main>
        <x-tool-hero
            badge="H1–H6 Hierarchy Audit"
            description="Audit the full H1–H6 structure of any URL. Detect missing H1s, skipped levels, and empty headings,visualized as a hierarchy tree with one-click fix suggestions."
            :primary-cta="['label' => 'Check Now', 'href' => '#analyzer-panel']"
            :secondary-cta="['label' => 'How It Works', 'href' => '#how-it-works']"
            :trust-labels="['fast' => 'Lightning Fast', 'privacy' => 'Privacy-First', 'signup' => 'No Signup', 'unlimited' => 'Unlimited Use']"
        >
            <x-slot:title>Find skipped<br><span class="s-it text-accent">heading levels.</span></x-slot:title>
            <x-slot:rightPanel>
                <x-preview-card variant="terminal" icon="fa-solid fa-heading" icon-bg="linear-gradient(135deg,#0694a2,#16bdca)" icon-color="#fff" title="Heading Tree" subtitle="H1–H6 audit">
                    <div class="space-y-1.5">
                        @foreach ([
                            ['tag' => 'H1', 'text' => 'Best Running Shoes 2025', 'color' => '#0694a2', 'indent' => 0],
                            ['tag' => 'H2', 'text' => 'Top Picks This Year', 'color' => '#16bdca', 'indent' => 12],
                            ['tag' => 'H3', 'text' => 'Nike Pegasus 41', 'color' => '#a3e635', 'indent' => 24],
                            ['tag' => 'H3', 'text' => 'Adidas Ultraboost 5', 'color' => '#a3e635', 'indent' => 24],
                            ['tag' => 'H2', 'text' => 'How We Tested', 'color' => '#16bdca', 'indent' => 12],
                        ] as $h)
                            <div class="flex items-center gap-2 text-xs rounded-lg px-2.5 py-1.5" style="background:rgba(255,255,255,0.04);margin-left:{{ $h['indent'] }}px">
                                <span class="text-[9px] font-black px-1.5 py-0.5 rounded" style="background:{{ $h['color'] }}22;color:{{ $h['color'] }}">{{ $h['tag'] }}</span>
                                <span style="color:var(--c-muted)">{{ $h['text'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </x-preview-card>
            </x-slot:rightPanel>
        </x-tool-hero>

        <x-tool-ticker :items="[
            ['icon' => 'fa-solid fa-heading', 'color' => '#0694a2', 'label' => 'H1 Tag'],
            ['icon' => 'fa-solid fa-list-ol', 'color' => '#16bdca', 'label' => 'H2 - H6'],
            ['icon' => 'fa-solid fa-sitemap', 'color' => '#a3e635', 'label' => 'Hierarchy'],
            ['icon' => 'fa-solid fa-arrow-down-1-9', 'color' => '#7edce2', 'label' => 'Skipped Levels'],
            ['icon' => 'fa-solid fa-ban', 'color' => '#0694a2', 'label' => 'Empty Headings'],
            ['icon' => 'fa-solid fa-tree', 'color' => '#16bdca', 'label' => 'Outline Tree'],
            ['icon' => 'fa-solid fa-wand-magic-sparkles', 'color' => '#a3e635', 'label' => 'Fix Suggestions'],
            ['icon' => 'fa-solid fa-circle-exclamation', 'color' => '#7edce2', 'label' => 'Missing H1'],
            ['icon' => 'fa-solid fa-copy', 'color' => '#0694a2', 'label' => 'Duplicate H1'],
            ['icon' => 'fa-solid fa-list', 'color' => '#16bdca', 'label' => 'DOM Order'],
            ['icon' => 'fa-solid fa-magnifying-glass-chart', 'color' => '#a3e635', 'label' => 'Structure Audit'],
            ['icon' => 'fa-solid fa-universal-access', 'color' => '#7edce2', 'label' => 'Accessibility'],
        ]" />

        <x-how-it-works :steps="[
            ['icon' => 'fa-solid fa-link', 'title' => 'Paste Your URL', 'desc' => 'Enter any public URL,a homepage, landing page, or blog post,and the audit begins instantly.'],
            ['icon' => 'fa-solid fa-sitemap', 'title' => 'Structure Audit', 'desc' => 'Every H1–H6 tag is extracted in DOM order and visualized as a full, indented heading tree so structural issues are immediately obvious.'],
            ['icon' => 'fa-solid fa-wand-magic-sparkles', 'title' => 'Get Fix Suggestions', 'desc' => 'Skipped levels and empty headings are flagged individually, each paired with a suggested correction so your outline can be fixed in one pass.'],
        ]">
            <x-slot:subtitle>Three steps to a <span class="s-it text-accent">perfect heading tree.</span></x-slot:subtitle>
        </x-how-it-works>

        <x-feature-highlights
            badge="Why This Tool"
            description="Spot missing H1s, skipped levels, and empty headings in one tree view,with auto-fix suggestions you can act on immediately."
            :features="[
                ['icon' => 'fa-solid fa-tree', 'title' => 'Visual Outline Tree', 'desc' => 'Headings render as an indented tree so you can see exactly where hierarchy breaks down,without having to read a single line of raw HTML.'],
                ['icon' => 'fa-solid fa-arrow-down-1-9', 'title' => 'Skipped Level Detection', 'desc' => 'H1 to H3 jumps and similar ordering errors are flagged automatically,the kind of issues that confuse screen readers, crawlers, and content editors alike.'],
                ['icon' => 'fa-solid fa-wand-magic-sparkles', 'title' => 'Auto Fix Suggestions', 'desc' => 'Every problematic heading gets a recommended level correction, so you can refactor your full outline structure in one focused review.'],
                ['icon' => 'fa-solid fa-ban', 'title' => 'Empty Headings Caught', 'desc' => 'Heading tags that exist in the DOM but contain no readable text,common with icon-only or decorative markup,are surfaced clearly in the results.'],
                ['icon' => 'fa-solid fa-universal-access', 'title' => 'Accessibility-Friendly', 'desc' => 'A clean heading order is foundational to WCAG compliance and screen-reader navigation. This tool surfaces the exact issues accessibility auditors look for.'],
                ['icon' => 'fa-solid fa-copy', 'title' => 'Copy the Corrected Outline', 'desc' => 'The suggested heading tree is copy-ready as plain text,paste it directly into a content brief, a doc, or your CMS without reformatting.'],
            ]"
        >
            <x-slot:title>Heading hierarchy, <span class="s-it text-accent">visualised.</span></x-slot:title>
        </x-feature-highlights>

        <x-analyzer-section>
            <div id="heading-checker-app"></div>
        </x-analyzer-section>

        <x-faq-section :items="[
            ['q' => 'Is having multiple H1s really a problem?', 'a' => 'Modern HTML5 allows multiple H1 tags, but most CMS templates and SEO best practices still expect a single page-level H1. The tool flags any page with more than one so you can decide intentionally rather than by accident.'],
            ['q' => 'How are skipped levels detected?', 'a' => 'Headings are walked in document order. Any jump greater than one level deeper than the previous heading,such as H2 followed directly by H4,is flagged as a skipped level.'],
            ['q' => 'Does it parse headings inside JavaScript-rendered sections?', 'a' => 'No. Only headings present in the initial server-side HTML are analyzed. Single-page applications that render their content entirely client-side will appear empty in the results.'],
            ['q' => 'What counts as an empty heading?', 'a' => 'Any H1–H6 tag whose text content is whitespace-only after all inline child tags have been stripped. Decorative empty headings should be replaced with a non-semantic element like a div.'],
            ['q' => 'Are the URLs I submit stored anywhere?', 'a' => 'No. Every request is processed and discarded immediately. No URLs or response content are ever logged or retained.'],
        ]" />

        <x-related-tools current-key="heading-checker" category="seo" />
    </main>
</x-layout>
