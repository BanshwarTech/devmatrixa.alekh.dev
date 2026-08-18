<x-layout
    title="Alt Text Checker – Find Images Missing Alt Tags"
    description="Scan any webpage for images without alt attributes. Fix accessibility issues and improve image SEO with this free alt text audit tool."
    keywords="alt text checker, image alt tag checker, missing alt attributes, image seo audit, accessibility checker"
>
    @push('head')
        @vite('resources/js/pages/alt-checker.js')
    @endpush

    <main>
        <x-tool-hero
            badge="Free Alt Checker"
            description="Scan any webpage and instantly detect missing, duplicate, or poorly optimized image alt tags. Improve your website's SEO and accessibility with actionable insights."
            :primary-cta="['label' => 'Check Alt Tags Now', 'href' => '#analyzer-panel']"
        >
            <x-slot:title>Check Missing Image Alt Tags <span class="s-it text-accent">Instantly</span></x-slot:title>
            <x-slot:rightPanel>
                <x-preview-card variant="scope" icon="fa-solid fa-magnifying-glass" icon-bg="linear-gradient(135deg,#16bdca,#a3e635)" icon-color="#061c21" title="Alt Analysis" subtitle="Image SEO insights">
                    <div class="space-y-2">
                        @foreach ([
                            ['alt' => 'Product showcase image', 'status' => 'Optimized', 'ok' => true, 'color' => '#65a30d'],
                            ['alt' => '', 'status' => 'Missing', 'ok' => false, 'color' => '#f87171'],
                            ['alt' => 'Product', 'status' => 'Generic', 'ok' => false, 'color' => '#f59e0b'],
                            ['alt' => 'Team collaboration', 'status' => 'Good', 'ok' => true, 'color' => '#65a30d'],
                        ] as $p)
                            <div class="flex items-center gap-2 text-xs rounded-lg px-2.5 py-1.5" style="background:rgba(255,255,255,0.04)">
                                <i class="fa-solid {{ $p['ok'] ? 'fa-circle-check' : 'fa-circle-xmark' }} text-xs" style="color:{{ $p['color'] }}"></i>
                                <span class="font-mono text-[10px]" style="color:var(--c-muted)">{{ $p['alt'] ?: '[empty]' }}</span>
                                <span class="ml-auto text-[10px] font-bold" style="color:{{ $p['color'] }}">{{ $p['status'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </x-preview-card>
            </x-slot:rightPanel>
        </x-tool-hero>

        <x-tool-ticker :items="[
            ['icon' => 'fa-solid fa-magnifying-glass', 'color' => '#0694a2', 'label' => 'Missing Alt Tags'],
            ['icon' => 'fa-solid fa-circle-exclamation', 'color' => '#16bdca', 'label' => 'Empty Attributes'],
            ['icon' => 'fa-solid fa-list-check', 'color' => '#a3e635', 'label' => 'Duplicate Alt Text'],
            ['icon' => 'fa-solid fa-wave-square', 'color' => '#7edce2', 'label' => 'Quality Analysis'],
            ['icon' => 'fa-solid fa-bolt', 'color' => '#0694a2', 'label' => 'Fast Scanning'],
            ['icon' => 'fa-solid fa-globe', 'color' => '#16bdca', 'label' => 'Website Audit'],
            ['icon' => 'fa-solid fa-circle-check', 'color' => '#a3e635', 'label' => 'Accessibility'],
            ['icon' => 'fa-solid fa-shield-halved', 'color' => '#7edce2', 'label' => 'SEO Friendly'],
            ['icon' => 'fa-solid fa-palette', 'color' => '#0694a2', 'label' => 'Image Indexing'],
            ['icon' => 'fa-solid fa-triangle-exclamation', 'color' => '#16bdca', 'label' => 'Optimization Tips'],
            ['icon' => 'fa-solid fa-clipboard', 'color' => '#a3e635', 'label' => 'Detailed Report'],
            ['icon' => 'fa-solid fa-arrow-right', 'color' => '#7edce2', 'label' => '100% Free'],
        ]" />

        <x-how-it-works :steps="[
            ['icon' => 'fa-solid fa-clipboard', 'title' => 'Enter Your Website URL', 'desc' => 'Paste the webpage URL you want to analyze for image accessibility and SEO issues.'],
            ['icon' => 'fa-solid fa-wave-square', 'title' => 'Run the Scan', 'desc' => 'Our system scans all image elements and detects missing or weak alt attributes.'],
            ['icon' => 'fa-solid fa-triangle-exclamation', 'title' => 'Review the Results', 'desc' => 'Get a detailed report showing missing, duplicate, and unoptimized image alt tags.'],
        ]">
            <x-slot:subtitle>Three steps to <span class="s-it text-accent">optimize image alt tags</span></x-slot:subtitle>
        </x-how-it-works>

        <x-feature-highlights
            badge="Everything You Need"
            description="Detect missing alt attributes, find duplicate descriptions, analyze quality, and boost your image SEO instantly."
            :features="[
                ['icon' => 'fa-solid fa-magnifying-glass', 'title' => 'Detect Missing Alt', 'desc' => 'Identify images without alt text and improve accessibility for search engines and screen readers.'],
                ['icon' => 'fa-solid fa-list-check', 'title' => 'Find Duplicates', 'desc' => 'Discover duplicate image descriptions that may affect SEO quality and content relevance.'],
                ['icon' => 'fa-solid fa-wave-square', 'title' => 'Analyze Quality', 'desc' => 'Get insights into weak, generic, or poorly optimized alt tags across your webpage.'],
                ['icon' => 'fa-solid fa-bolt', 'title' => 'Boost Image SEO', 'desc' => 'Improve image indexing and visibility in Google Image Search with optimized alt attributes.'],
                ['icon' => 'fa-solid fa-globe', 'title' => 'Accessibility Focus', 'desc' => 'Make your website more accessible for visually impaired users and improve usability standards.'],
                ['icon' => 'fa-solid fa-triangle-exclamation', 'title' => 'Instant Scanning', 'desc' => 'Run fast and accurate image alt audits without complex setup or technical knowledge.'],
            ]"
        >
            <x-slot:title>Optimize Image Alt Tags <span class="s-it text-accent">with confidence.</span></x-slot:title>
        </x-feature-highlights>

        <x-analyzer-section>
            <div id="alt-checker-app"></div>
        </x-analyzer-section>

        <x-faq-section :items="[
            ['q' => 'What is image alt text?', 'a' => 'Image alt text is a short description added to images in HTML that helps search engines and screen readers understand image content.'],
            ['q' => 'Why are alt tags important for SEO?', 'a' => 'Alt tags help search engines understand your images, improve image search visibility, and strengthen overall on-page SEO.'],
            ['q' => 'Can this tool detect missing alt tags?', 'a' => 'Yes, the Alt Checker scans your webpage and identifies images with missing, empty, duplicate, or poorly optimized alt text.'],
            ['q' => 'Who should use this Alt Checker tool?', 'a' => 'This tool is useful for SEO experts, developers, marketers, bloggers, ecommerce websites, and business owners.'],
            ['q' => 'Does image alt text improve accessibility?', 'a' => 'Yes, properly written alt text improves website accessibility for users who rely on screen readers.'],
            ['q' => 'Is the Alt Checker free to use?', 'a' => 'Yes, you can scan and analyze image alt tags for free using the DevMatrixa Alt Checker.'],
        ]" />

        <x-related-tools current-key="alt-checker" category="seo" />
    </main>
</x-layout>
