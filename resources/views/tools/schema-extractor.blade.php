<x-layout
    title="Schema Extractor – View Structured Data on Any Page"
    description="Extract and visualize all JSON-LD, Microdata, and RDFa schema markup from any URL. Instantly check what structured data Google can read."
    og-title="Schema Extractor — Inspect Every JSON-LD Block on Any URL | Devmatrixa"
    og-description="Paste any URL and instantly extract every JSON-LD structured data block — auto-typed, pretty-printed, and copy-ready for Google's Rich Results Test. Free and zero data stored."
>
    @push('head')
        @vite('resources/js/pages/schema-extractor.js')
    @endpush

    <main>
        <x-tool-hero
            badge="JSON-LD Structured Data"
            description="Extract all structured data blocks from any URL,FAQPage, Product, Article, and more. Auto-formatted, type-tagged, and copyable in one click."
            :primary-cta="['label' => 'Extract Schema', 'href' => '#analyzer-panel']"
            :trust-labels="['fast' => 'Lightning Fast', 'privacy' => 'Privacy-First', 'signup' => 'No Signup', 'unlimited' => 'Unlimited Use']"
        >
            <x-slot:title>Inspect every<br><span class="s-it text-accent">schema block.</span></x-slot:title>
            <x-slot:rightPanel>
                <x-preview-card variant="terminal" icon="fa-solid fa-diagram-project" icon-bg="linear-gradient(135deg,#a3e635,#047481)" title="JSON-LD Block" subtitle="Article schema">
                    <pre class="text-[10px] font-mono leading-relaxed overflow-hidden" style="color:#daf2f4"><span style="color:#16bdca">&lbrace;</span><br>  <span style="color:#7edce2">"@type"</span>: <span style="color:#a3e635">"Article"</span>,<br>  <span style="color:#7edce2">"headline"</span>: <span style="color:#a3e635">"How to ..."</span>,<br>  <span style="color:#7edce2">"author"</span>: <span style="color:#a3e635">"Alekh B."</span>,<br>  <span style="color:#7edce2">"datePublished"</span>: <span style="color:#a3e635">"2026-05"</span><br><span style="color:#16bdca">&rbrace;</span></pre>
                </x-preview-card>
            </x-slot:rightPanel>
        </x-tool-hero>

        <x-tool-ticker :items="[
            ['icon' => 'fa-solid fa-diagram-project', 'color' => '#0694a2', 'label' => 'JSON-LD'],
            ['icon' => 'fa-solid fa-code', 'color' => '#16bdca', 'label' => 'Microdata'],
            ['icon' => 'fa-solid fa-tags', 'color' => '#a3e635', 'label' => 'RDFa'],
            ['icon' => 'fa-solid fa-building', 'color' => '#7edce2', 'label' => 'Organization'],
            ['icon' => 'fa-solid fa-newspaper', 'color' => '#0694a2', 'label' => 'Article'],
            ['icon' => 'fa-solid fa-circle-question', 'color' => '#16bdca', 'label' => 'FAQPage'],
            ['icon' => 'fa-solid fa-box', 'color' => '#a3e635', 'label' => 'Product'],
            ['icon' => 'fa-solid fa-list', 'color' => '#7edce2', 'label' => 'Breadcrumb'],
            ['icon' => 'fa-solid fa-utensils', 'color' => '#0694a2', 'label' => 'Recipe'],
            ['icon' => 'fa-solid fa-video', 'color' => '#16bdca', 'label' => 'VideoObject'],
            ['icon' => 'fa-solid fa-copy', 'color' => '#a3e635', 'label' => 'Copy JSON'],
            ['icon' => 'fa-solid fa-star', 'color' => '#7edce2', 'label' => 'Rich Results'],
        ]" />

        <x-how-it-works :steps="[
            ['icon' => 'fa-solid fa-link', 'title' => 'Paste Your URL', 'desc' => 'Enter any URL that contains structured data,an article, product page, FAQ, recipe, or any page targeting Google rich results.'],
            ['icon' => 'fa-solid fa-diagram-project', 'title' => 'Detect JSON-LD', 'desc' => 'Every script tag with type application/ld+json is found and extracted,including individual nodes nested inside @graph blocks.'],
            ['icon' => 'fa-solid fa-copy', 'title' => 'Copy and Use', 'desc' => 'Each schema block is auto-formatted with a type label and a one-click copy button,paste straight into Google\'s Rich Results Test or your own code.'],
        ]">
            <x-slot:subtitle>Three steps to <span class="s-it text-accent">rich-result ready.</span></x-slot:subtitle>
        </x-how-it-works>

        <x-feature-highlights
            badge="Why This Tool"
            description="Inspect every JSON-LD block on any page,auto-typed, pretty-printed, and ready to paste into a rich-results test in seconds."
            :features="[
                ['icon' => 'fa-solid fa-diagram-project', 'title' => 'All JSON-LD Blocks', 'desc' => 'Every script tag with the application/ld+json type is captured from the page,including multiple blocks and individual entities split across @graph nodes.'],
                ['icon' => 'fa-solid fa-tags', 'title' => 'Type-Aware Grouping', 'desc' => 'Article, Product, FAQPage, Organization, Recipe, and other schema types are automatically detected and labeled with a colored chip for instant visual clarity.'],
                ['icon' => 'fa-solid fa-code', 'title' => 'Pretty-Printed JSON', 'desc' => 'Every extracted block is formatted with clean 2-space indentation,instantly readable, diff-friendly, and ready to paste without reformatting.'],
                ['icon' => 'fa-solid fa-copy', 'title' => 'One-Click Copy Per Block', 'desc' => 'Each schema block has its own dedicated copy button. Paste the output directly into Google\'s Rich Results Test or wherever you need it next.'],
                ['icon' => 'fa-solid fa-file-circle-check', 'title' => 'Rich Result Hints', 'desc' => 'Schema types that map to Google rich features,Article, FAQPage, Recipe, Product, and more,are highlighted so you always know what your markup is targeting.'],
                ['icon' => 'fa-solid fa-database', 'title' => 'Nothing Stored', 'desc' => 'Fetched HTML and parsed schemas live in memory only and are discarded the moment you leave the page. No data is ever logged or retained.'],
            ]"
        >
            <x-slot:title>Structured data, <span class="s-it text-accent">demystified.</span></x-slot:title>
        </x-feature-highlights>

        <x-analyzer-section>
            <div id="schema-extractor-app"></div>
        </x-analyzer-section>

        <x-faq-section :items="[
            ['q' => 'Does it parse Microdata or RDFa as well?', 'a' => 'Currently only JSON-LD is parsed,it is the most widely used format and the one Google explicitly recommends for structured data. Microdata support is planned for a future update.'],
            ['q' => 'What if the schema contains invalid JSON?', 'a' => 'Invalid blocks are skipped with a clear notice so the remaining blocks on the page still parse successfully. For full schema.org validation, use Google\'s Rich Results Test alongside this tool.'],
            ['q' => 'How are @graph nodes handled?', 'a' => 'If a JSON-LD block uses an @graph array, the tool flattens it and reports each node separately,so every entity the page declares appears as its own distinct block in the results.'],
            ['q' => 'Will it pick up schemas injected by JavaScript?', 'a' => 'No. Only schemas present in the initial HTML response are parsed. JSON-LD added to the DOM after page load by client-side JavaScript is invisible to a server-side fetch.'],
            ['q' => 'Are the URLs I submit stored anywhere?', 'a' => 'No. Every request is processed and discarded immediately. No URLs or response content are ever logged or retained.'],
        ]" />

        <x-related-tools current-key="schema-extractor" category="seo" />
    </main>
</x-layout>
