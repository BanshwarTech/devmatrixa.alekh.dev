<x-layout
    title="FAQ Extractor – Pull FAQs From Any Webpage"
    description="Extract all FAQ questions and answers from any URL in one click. Great for content research, competitor analysis, and schema markup planning."
    keywords="faq extractor, extract faq from webpage, faq scraper, faq schema generator"
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
                <x-preview-card variant="terminal" icon="circle-help" icon-bg="linear-gradient(135deg,#047481,#16bdca)" icon-color="#fff" title="FAQ Extract" subtitle="3 questions found">
                    <div class="space-y-2">
                        @foreach ([
                            ['q' => 'How does Devmatrixa work?', 'a' => 'Every utility runs locally...'],
                            ['q' => 'Is my data stored?', 'a' => 'No, zero data is collected...'],
                            ['q' => 'Can I use this offline?', 'a' => 'Most tools work offline...'],
                        ] as $f)
                            <div class="rounded-lg px-2.5 py-2" style="background:rgba(255,255,255,0.04)">
                                <p class="text-xs font-semibold inline-flex items-center" style="color:var(--c-text)"><i class="mr-1.5" style="color:#16bdca;font-size:10px" data-lucide="circle-help"></i>{{ $f['q'] }}</p>
                                <p class="text-[10px] mt-0.5 truncate" style="color:var(--c-muted)">{{ $f['a'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </x-preview-card>
            </x-slot:rightPanel>
        </x-tool-hero>

        <x-tool-ticker :items="[
            ['icon' => 'circle-help', 'color' => '#0694a2', 'label' => 'Questions'],
            ['icon' => 'message-circle', 'color' => '#16bdca', 'label' => 'Answers'],
            ['icon' => 'network', 'color' => '#0098f0', 'label' => 'FAQ Schema'],
            ['icon' => 'code', 'color' => '#7edce2', 'label' => 'JSON-LD'],
            ['icon' => 'star', 'color' => '#0694a2', 'label' => 'Rich Snippets'],
            ['icon' => 'menu', 'color' => '#16bdca', 'label' => 'Accordion'],
            ['icon' => 'download', 'color' => '#0098f0', 'label' => 'Export JS'],
            ['icon' => 'list', 'color' => '#7edce2', 'label' => 'Q&A Pairs'],
            ['icon' => 'search', 'color' => '#0694a2', 'label' => 'Auto Detect'],
            ['icon' => 'layers', 'color' => '#16bdca', 'label' => 'FAQPage'],
            ['icon' => 'bolt', 'color' => '#0098f0', 'label' => 'Fast Scrape'],
            ['icon' => 'tags', 'color' => '#7edce2', 'label' => 'Structured Data'],
        ]" />

        <x-how-it-works :steps="[
            ['icon' => 'link', 'title' => 'Paste Your URL', 'desc' => 'Drop in any page that contains FAQs,a blog post, landing page, product detail page, or documentation article.'],
            ['icon' => 'network', 'title' => 'Schema and Accordion Scan', 'desc' => 'The tool checks for FAQPage JSON-LD first, then falls back to Elementor patterns, definition lists, and common accordion structures.'],
            ['icon' => 'download', 'title' => 'Export as JS', 'desc' => 'Get a clean, paired Q&A list and download it as a ready-to-use JavaScript file,no reformatting needed.'],
        ]">
            <x-slot:subtitle>Three steps to <span class="s-it text-accent">reusable FAQ data</span></x-slot:subtitle>
        </x-how-it-works>

        <x-feature-highlights
            badge="Why This Tool"
            description="Auto-detect Q&A blocks on any URL and ship them out as a typed, importable JS file in seconds."
            :features="[
                ['icon' => 'sparkles', 'title' => 'Smart Auto-Detect', 'desc' => 'Recognises FAQ schemas, definition lists, accordion patterns, and common question-heading conventions,even on messy markup.'],
                ['icon' => 'network', 'title' => 'JSON-LD Aware', 'desc' => 'If a page already exposes FAQPage structured data, those Q&A pairs are extracted first for maximum accuracy.'],
                ['icon' => 'download', 'title' => 'Export as faqs.js', 'desc' => 'Download a ready-to-import JavaScript file,no copy-paste, no manual JSON cleanup, no reformatting required.'],
                ['icon' => 'layers', 'title' => 'Pairs Cleanly Grouped', 'desc' => 'Questions are matched to their answers even when the underlying markup is inconsistent or non-semantic.'],
                ['icon' => 'bolt', 'title' => 'Single Request', 'desc' => 'One fetch. One parse. One download. No rate limits, no queues, no waiting.'],
                ['icon' => 'database', 'title' => 'Zero Data Stored', 'desc' => 'Pages are processed entirely in-memory and immediately discarded. Nothing is logged, cached, or saved.'],
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
