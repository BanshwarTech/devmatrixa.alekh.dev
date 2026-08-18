<x-layout
    title="OG Preview – Test Open Graph & Twitter Card Tags"
    description="Preview how your page looks when shared on Twitter, LinkedIn, and Facebook. Check OG title, description, and image before going live."
    keywords="og preview tool, open graph preview, social share preview, twitter card preview, facebook link preview"
    og-title="Social OG Preview - See How Any URL Looks When Shared | Devmatrixa"
    og-description="Paste any URL and instantly see how it looks on Facebook, X, LinkedIn, WhatsApp, and Google - all five share cards generated live in one click. Zero data stored. Completely free."
>
    @push('head')
        @vite('resources/js/pages/og-preview.js')
    @endpush

    <main>
        <x-tool-hero
            badge="Social Share Preview"
            description="See how any URL appears when shared on Facebook, X, LinkedIn, WhatsApp, and Google - all five platform previews generated live in a single click."
            :primary-cta="['label' => 'Generate Preview', 'href' => '#analyzer-panel']"
            :trust-labels="['fast' => 'Lightning Fast', 'privacy' => 'Privacy-First', 'signup' => 'No Signup', 'unlimited' => 'Unlimited Use']"
        >
            <x-slot:title>How it looks<br><span class="s-it text-accent">when shared.</span></x-slot:title>
            <x-slot:rightPanel>
                <x-preview-card icon="fa-solid fa-share-nodes" icon-bg="linear-gradient(135deg,#16bdca,#a3e635)" title="Share Preview" subtitle="OG card mock">
                    <div class="rounded-lg overflow-hidden" style="background:rgba(255,255,255,0.04);border:1px solid var(--c-border)">
                        <div class="h-24" style="background:linear-gradient(135deg,#0694a2,#a3e635)"></div>
                        <div class="p-3">
                            <p class="text-[10px] uppercase" style="color:var(--c-muted)">example.com</p>
                            <p class="text-sm font-semibold mt-0.5" style="color:var(--c-text)">Devmatrixa - Modern Toolkit</p>
                            <p class="text-[11px] mt-1 leading-snug" style="color:var(--c-muted)">All five share cards generated live from one URL.</p>
                        </div>
                    </div>
                </x-preview-card>
            </x-slot:rightPanel>
        </x-tool-hero>

        <x-tool-ticker :items="[
            ['icon' => 'fa-solid fa-share-nodes', 'color' => '#0694a2', 'label' => 'Open Graph'],
            ['icon' => 'fa-brands fa-x-twitter', 'color' => '#16bdca', 'label' => 'Twitter Cards'],
            ['icon' => 'fa-brands fa-facebook', 'color' => '#a3e635', 'label' => 'Facebook Preview'],
            ['icon' => 'fa-brands fa-linkedin', 'color' => '#7edce2', 'label' => 'LinkedIn Preview'],
            ['icon' => 'fa-brands fa-whatsapp', 'color' => '#0694a2', 'label' => 'WhatsApp Card'],
            ['icon' => 'fa-brands fa-google', 'color' => '#16bdca', 'label' => 'Google Snippet'],
            ['icon' => 'fa-solid fa-image', 'color' => '#a3e635', 'label' => 'og:image'],
            ['icon' => 'fa-solid fa-heading', 'color' => '#7edce2', 'label' => 'og:title'],
            ['icon' => 'fa-solid fa-align-left', 'color' => '#0694a2', 'label' => 'og:description'],
            ['icon' => 'fa-solid fa-id-card', 'color' => '#16bdca', 'label' => 'twitter:card'],
            ['icon' => 'fa-solid fa-tags', 'color' => '#a3e635', 'label' => 'Meta Tags'],
            ['icon' => 'fa-solid fa-eye', 'color' => '#7edce2', 'label' => 'Live Preview'],
        ]" />

        <x-how-it-works :steps="[
            ['icon' => 'fa-solid fa-link', 'title' => 'Paste Your URL', 'desc' => 'Enter any public URL - a landing page, blog post, product detail page, or any page you plan to share.'],
            ['icon' => 'fa-solid fa-tags', 'title' => 'Read Meta Tags', 'desc' => 'Open Graph tags, Twitter Card values, the page title, description, favicon, and OG image are all parsed into one structured payload.'],
            ['icon' => 'fa-solid fa-share-nodes', 'title' => 'Preview 5 Platforms', 'desc' => 'See exactly how the link card renders on Facebook, X, LinkedIn, WhatsApp, and Google search - side by side, in one view.'],
        ]">
            <x-slot:subtitle>Three steps to <span class="s-it text-accent">perfect share cards.</span></x-slot:subtitle>
        </x-how-it-works>

        <x-feature-highlights
            badge="Why This Tool"
            description="Five live platform previews, complete Open Graph and Twitter Card parsing - all from a single URL paste, before you hit publish."
            :features="[
                ['icon' => 'fa-solid fa-share-nodes', 'title' => '5 Platforms in One Click', 'desc' => 'Facebook, X, LinkedIn, WhatsApp, and Google Search - every share card rendered from a single URL fetch, no toggling between tabs or tools.'],
                ['icon' => 'fa-solid fa-tags', 'title' => 'Full Meta Parse', 'desc' => 'Open Graph, Twitter Card, page title, meta description, favicon, and OG image are all read and surfaced in one clean, structured panel.'],
                ['icon' => 'fa-solid fa-image', 'title' => 'Live Image Preview', 'desc' => 'Each card loads the actual og:image, so you can instantly catch a broken URL, a wrongly-sized thumbnail, or a missing image before it goes live.'],
                ['icon' => 'fa-solid fa-eye', 'title' => 'Pixel-Accurate Mocks', 'desc' => 'Every preview matches each platform\'s native colors, fonts, and aspect ratios - what you see here is precisely what gets shared out there.'],
                ['icon' => 'fa-solid fa-id-card', 'title' => 'Twitter Card Fallback', 'desc' => 'Pages without explicit twitter:card tags fall back to Open Graph values automatically - exactly the way X handles it in production.'],
                ['icon' => 'fa-solid fa-heading', 'title' => 'Title and Meta Length Checks', 'desc' => 'Titles and descriptions that are too long get truncated differently on each platform - see exactly where the cutoff hits before you publish.'],
            ]"
        >
            <x-slot:title>How your link looks, <span class="s-it text-accent">everywhere.</span></x-slot:title>
        </x-feature-highlights>

        <x-analyzer-section>
            <div id="og-preview-app"></div>
        </x-analyzer-section>

        <x-faq-section :items="[
            ['q' => 'Why does my LinkedIn preview look wrong?', 'a' => 'LinkedIn caches share previews aggressively. After updating your OG tags, use LinkedIn\'s official Post Inspector to force a cache refresh - the corrected card will then appear on the next scan here too.'],
            ['q' => 'Does it test the Slack or Discord preview?', 'a' => 'Not directly, but both platforms use the same Open Graph tags that drive the Facebook and LinkedIn previews. If those look right here, Slack and Discord will render correctly as well.'],
            ['q' => 'What if a page has no OG image?', 'a' => 'The tool falls back to twitter:image, then the first large image tag found in the page HTML, then a generic placeholder. The result panel clearly shows which source was used.'],
            ['q' => 'How recent is the preview data?', 'a' => 'Completely live. Every preview is generated by fetching the URL fresh on the spot - there are no cached or stored responses.'],
            ['q' => 'Are the URLs I submit stored anywhere?', 'a' => 'No. Every request is processed and discarded immediately. No URLs or response content are ever logged or retained.'],
        ]" />

        <x-related-tools current-key="og-preview" category="seo" badge="Keep Going" />
    </main>
</x-layout>
