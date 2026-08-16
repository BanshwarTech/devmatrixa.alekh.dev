<x-layout
    title="FAQ Extractor – Pull FAQs From Any Webpage"
    description="Extract all FAQ questions and answers from any URL in one click. Great for content research, competitor analysis, and schema markup planning."
    og-title="FAQ Extractor — Pull FAQs from Any URL | Devmatrixa"
    og-description="Paste any URL. Auto-detect FAQ blocks. Export clean Q&A pairs as a ready-to-import JavaScript file. No signup. No data stored. Completely free."
>
    @push('head')
        @vite('resources/js/pages/faq-extractor.js')
    @endpush

    <main>
        <x-tool-hero
            badge="Q&A Scraping"
            description="Bulk extract page-wise FAQ content from any URL. Auto-detects question and answer blocks and exports them as a clean, ready-to-import JavaScript file."
            :primary-cta="['label' => 'Extract FAQs', 'href' => '#analyzer-panel']"
        >
            <x-slot:title>Pull FAQs from <span class="s-it text-accent">any URL.</span></x-slot:title>
            <x-slot:rightPanel>
                <x-preview-card variant="terminal" icon="fa-solid fa-circle-question" icon-bg="linear-gradient(135deg,#047481,#16bdca)" icon-color="#fff" title="FAQ Extract" subtitle="3 questions found">
                    <div class="space-y-2">
                        @foreach ([
                            ['q' => 'How does Devmatrixa work?', 'a' => 'Every utility runs locally...'],
                            ['q' => 'Is my data stored?', 'a' => 'No, zero data is collected...'],
                            ['q' => 'Can I use this offline?', 'a' => 'Most tools work offline...'],
                        ] as $f)
                            <div class="rounded-lg px-2.5 py-2" style="background:rgba(255,255,255,0.04)">
                                <p class="text-xs font-semibold inline-flex items-center" style="color:var(--c-text)"><i class="fa-solid fa-circle-question mr-1.5" style="color:#16bdca;font-size:10px"></i>{{ $f['q'] }}</p>
                                <p class="text-[10px] mt-0.5 truncate" style="color:var(--c-muted)">{{ $f['a'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </x-preview-card>
            </x-slot:rightPanel>
        </x-tool-hero>

        <x-tool-ticker :items="[
            ['icon' => 'fa-solid fa-circle-question', 'color' => '#0694a2', 'label' => 'Questions'],
            ['icon' => 'fa-solid fa-comment-dots', 'color' => '#16bdca', 'label' => 'Answers'],
            ['icon' => 'fa-solid fa-diagram-project', 'color' => '#a3e635', 'label' => 'FAQ Schema'],
            ['icon' => 'fa-solid fa-code', 'color' => '#7edce2', 'label' => 'JSON-LD'],
            ['icon' => 'fa-solid fa-star', 'color' => '#0694a2', 'label' => 'Rich Snippets'],
            ['icon' => 'fa-solid fa-bars', 'color' => '#16bdca', 'label' => 'Accordion'],
            ['icon' => 'fa-solid fa-download', 'color' => '#a3e635', 'label' => 'Export JS'],
            ['icon' => 'fa-solid fa-list', 'color' => '#7edce2', 'label' => 'Q&A Pairs'],
            ['icon' => 'fa-solid fa-magnifying-glass', 'color' => '#0694a2', 'label' => 'Auto Detect'],
            ['icon' => 'fa-solid fa-layer-group', 'color' => '#16bdca', 'label' => 'FAQPage'],
            ['icon' => 'fa-solid fa-bolt', 'color' => '#a3e635', 'label' => 'Fast Scrape'],
            ['icon' => 'fa-solid fa-tags', 'color' => '#7edce2', 'label' => 'Structured Data'],
        ]" />

        <x-how-it-works :steps="[
            ['icon' => 'fa-solid fa-link', 'title' => 'Paste Your URL', 'desc' => 'Drop in any page that contains FAQs,a blog post, landing page, product detail page, or documentation article.'],
            ['icon' => 'fa-solid fa-diagram-project', 'title' => 'Schema and Accordion Scan', 'desc' => 'The tool checks for FAQPage JSON-LD first, then falls back to Elementor patterns, definition lists, and common accordion structures.'],
            ['icon' => 'fa-solid fa-download', 'title' => 'Export as JS', 'desc' => 'Get a clean, paired Q&A list and download it as a ready-to-use JavaScript file,no reformatting needed.'],
        ]">
            <x-slot:subtitle>Three steps to <span class="s-it text-accent">reusable FAQ data</span></x-slot:subtitle>
        </x-how-it-works>

        <x-feature-highlights
            badge="Why This Tool"
            description="Auto-detect Q&A blocks on any URL and ship them out as a typed, importable JS file in seconds."
            :features="[
                ['icon' => 'fa-solid fa-sparkles', 'title' => 'Smart Auto-Detect', 'desc' => 'Recognises FAQ schemas, definition lists, accordion patterns, and common question-heading conventions,even on messy markup.'],
                ['icon' => 'fa-solid fa-diagram-project', 'title' => 'JSON-LD Aware', 'desc' => 'If a page already exposes FAQPage structured data, those Q&A pairs are extracted first for maximum accuracy.'],
                ['icon' => 'fa-solid fa-download', 'title' => 'Export as faqs.js', 'desc' => 'Download a ready-to-import JavaScript file,no copy-paste, no manual JSON cleanup, no reformatting required.'],
                ['icon' => 'fa-solid fa-layer-group', 'title' => 'Pairs Cleanly Grouped', 'desc' => 'Questions are matched to their answers even when the underlying markup is inconsistent or non-semantic.'],
                ['icon' => 'fa-solid fa-bolt', 'title' => 'Single Request', 'desc' => 'One fetch. One parse. One download. No rate limits, no queues, no waiting.'],
                ['icon' => 'fa-solid fa-database', 'title' => 'Zero Data Stored', 'desc' => 'Pages are processed entirely in-memory and immediately discarded. Nothing is logged, cached, or saved.'],
            ]"
        >
            <x-slot:title>FAQ pages turned into <span class="s-it text-accent">real data.</span></x-slot:title>
        </x-feature-highlights>

        <x-analyzer-section>
            <div id="faq-extractor-app"></div>
        </x-analyzer-section>

        <x-faq-section :items="[
            ['q' => 'What types of FAQ markup does this tool support?', 'a' => 'FAQPage JSON-LD, details and summary accordions, definition lists, and layouts where a question-styled heading is immediately followed by an answer paragraph,all detected automatically.'],
            ['q' => 'Does it work on JavaScript-rendered FAQ content?', 'a' => 'No. The tool fetches raw server-side HTML. FAQ blocks that load after the initial page render via client-side JavaScript will not be captured unless they exist in the static markup.'],
            ['q' => 'What does the exported JS file contain?', 'a' => 'A single default export,an array of question and answer objects,ready to drop into a Next.js component, a CMS integration, or any modern build pipeline with a simple import statement.'],
            ['q' => 'Can it extract FAQs spread across multiple pages?', 'a' => 'Only single-URL scans are supported. If your FAQ content is paginated, run the tool once for each URL.'],
            ['q' => 'Are the URLs I submit stored anywhere?', 'a' => 'No. Every request is processed and discarded immediately. No URLs, page content, or response data is ever logged or retained.'],
        ]" />

        <x-related-tools
            current-key="faq-extractor"
            :tool-keys="['seo-analyzer', 'link-checker', 'alt-checker']"
            title="Related tools."
            description="Pick the next tool that helps you move faster."
        />
    </main>
</x-layout>
