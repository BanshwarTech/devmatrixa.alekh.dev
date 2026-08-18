<x-layout
    title="Anchor Text Analyzer – Audit Link Text on Any Page"
    description="Extract and analyze all anchor text from internal and external links on any URL. Find over-optimized anchors and fix your internal link strategy."
    keywords="anchor text analyzer, anchor text checker, link text audit, internal link anchor text, seo link analysis"
>
    @push('head')
        @vite('resources/js/pages/anchor-text-analyzer.js')
    @endpush

    <main>
        <x-tool-hero
            badge="On-page anchor audit"
            description="Extract and audit all anchor links. Classify by type, detect generic anchors, duplicates, and nofollow issues."
            :primary-cta="['label' => 'Analyze Now', 'href' => '#analyzer-panel']"
        >
            <x-slot:title>Audit every<br><span class="s-it text-accent">anchor on the page.</span></x-slot:title>
            <x-slot:rightPanel>
                <x-preview-card variant="scope" icon="fa-solid fa-link" icon-bg="linear-gradient(135deg,#a3e635,#0694a2)" icon-color="#061c21" title="Anchor Audit" subtitle="On-page links">
                    <div class="space-y-2">
                        @foreach ([
                            ['type' => 'Keyword', 'count' => 24, 'color' => '#65a30d', 'pct' => 60],
                            ['type' => 'Generic', 'count' => 8, 'color' => '#f97316', 'pct' => 20],
                            ['type' => 'Naked URL', 'count' => 5, 'color' => '#16bdca', 'pct' => 12],
                            ['type' => 'Empty', 'count' => 3, 'color' => '#f87171', 'pct' => 8],
                        ] as $a)
                            <div class="flex items-center gap-2 text-xs">
                                <span class="w-16 text-[10px] font-black uppercase tracking-wider" style="color:{{ $a['color'] }}">{{ $a['type'] }}</span>
                                <div class="flex-1 h-1.5 rounded-full overflow-hidden" style="background:rgba(255,255,255,0.06)">
                                    <div class="h-full rounded-full" style="width:{{ $a['pct'] }}%;background:{{ $a['color'] }}"></div>
                                </div>
                                <span class="text-[10px] font-bold" style="color:var(--c-text)">{{ $a['count'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </x-preview-card>
            </x-slot:rightPanel>
        </x-tool-hero>

        <x-tool-ticker :items="[
            ['icon' => 'fa-solid fa-link', 'color' => '#0694a2', 'label' => 'Anchor Text'],
            ['icon' => 'fa-solid fa-arrow-right-arrow-left', 'color' => '#16bdca', 'label' => 'Internal Links'],
            ['icon' => 'fa-solid fa-arrow-up-right-from-square', 'color' => '#a3e635', 'label' => 'External Links'],
            ['icon' => 'fa-solid fa-link-slash', 'color' => '#7edce2', 'label' => 'NoFollow'],
            ['icon' => 'fa-solid fa-quote-right', 'color' => '#0694a2', 'label' => 'Generic Anchors'],
            ['icon' => 'fa-solid fa-copy', 'color' => '#16bdca', 'label' => 'Duplicates'],
            ['icon' => 'fa-solid fa-magnifying-glass', 'color' => '#a3e635', 'label' => 'Keyword Anchors'],
            ['icon' => 'fa-solid fa-image', 'color' => '#7edce2', 'label' => 'Image Anchors'],
            ['icon' => 'fa-solid fa-ban', 'color' => '#0694a2', 'label' => 'Empty Anchors'],
            ['icon' => 'fa-solid fa-globe', 'color' => '#16bdca', 'label' => 'Naked URLs'],
            ['icon' => 'fa-solid fa-tags', 'color' => '#a3e635', 'label' => 'Rel Attribute'],
            ['icon' => 'fa-solid fa-list', 'color' => '#7edce2', 'label' => 'Top Anchors'],
        ]" />

        <x-how-it-works :steps="[
            ['icon' => 'fa-solid fa-link', 'title' => 'Paste Your URL', 'desc' => 'Enter any public URL and we extract every anchor on the page.'],
            ['icon' => 'fa-solid fa-tags', 'title' => 'Classify Anchors', 'desc' => 'Each link is tagged keyword, generic, naked URL, image, or empty automatically.'],
            ['icon' => 'fa-solid fa-triangle-exclamation', 'title' => 'Spot Issues', 'desc' => 'See duplicates, generic anchors, nofollow ratios, and over-optimization signals.'],
        ]">
            <x-slot:subtitle>Three steps to <span class="s-it text-accent">smarter linking</span></x-slot:subtitle>
        </x-how-it-works>

        <x-feature-highlights
            badge="Why this tool"
            description="Every link classified, ranked, and flagged — so you can fix anchor-text issues that actually move rankings."
            :features="[
                ['icon' => 'fa-solid fa-layer-group', 'title' => '5 anchor types classified', 'desc' => 'Keyword, generic, naked-URL, image, and empty anchors are auto-labelled with one click.'],
                ['icon' => 'fa-solid fa-triangle-exclamation', 'title' => 'Issue detection', 'desc' => 'Spots generic anchors, duplicate targets, empty links, and nofollow misuse out of the box.'],
                ['icon' => 'fa-solid fa-globe', 'title' => 'Internal vs external split', 'desc' => 'See how your link equity is distributed between your own pages and outbound destinations.'],
                ['icon' => 'fa-solid fa-magnifying-glass', 'title' => 'Top anchor frequency', 'desc' => 'Surfaces the most-used anchor phrases so you can detect over-optimised keywords fast.'],
                ['icon' => 'fa-solid fa-copy', 'title' => 'Copy-ready list', 'desc' => 'Every anchor is paired with its href in a scannable table — copy any cell instantly.'],
                ['icon' => 'fa-solid fa-database', 'title' => 'Zero data retention', 'desc' => 'URLs you submit are scanned in-memory and dropped — we never store the target page.'],
            ]"
        >
            <x-slot:title>Anchor audits that go <span class="s-it text-accent">deeper.</span></x-slot:title>
        </x-feature-highlights>

        <x-analyzer-section>
            <div id="anchor-text-analyzer-app"></div>
        </x-analyzer-section>

        <x-faq-section :items="[
            ['q' => 'What\'s a generic anchor and why does it matter?', 'a' => 'Anchors like \'click here\', \'read more\', or \'this link\' are classified as generic. They waste link equity because they tell Google nothing about the destination page.'],
            ['q' => 'Does it follow rel attributes?', 'a' => 'Yes — we parse the full rel attribute and explicitly flag nofollow, ugc, and sponsored links so you can audit your outbound link policy.'],
            ['q' => 'Can I analyse pages behind a login?', 'a' => 'No. The crawler only fetches publicly accessible HTML, so authenticated pages won\'t be reachable from our server.'],
            ['q' => 'How are duplicate anchors detected?', 'a' => 'Two anchors are considered duplicates when both the visible text and the href match exactly. These often indicate template-level link bloat.'],
            ['q' => 'Are my URLs stored anywhere?', 'a' => 'No. Every request is processed and discarded — we don\'t log URLs or response content.'],
        ]" />

        <x-related-tools current-key="anchor-text-analyzer" category="seo" />
    </main>
</x-layout>
