<x-layout
    title="Tracker Inventory – Detect All 3rd Party Scripts"
    description="Scan any URL to see every third-party tracker, pixel, and analytics tag loaded on the page. Great for privacy audits and performance checks."
    keywords="tracker inventory tool, third party script detector, website tracker scanner, privacy audit tool, pixel tracker checker"
    og-title="Tracker Inventory — Audit Every Third-Party Pixel | Devmatrixa"
    og-description="Paste a URL and see every external domain it loads — classified into analytics, ads, social, CDN, fonts, chat and more, with privacy-impact grading."
>
    @push('head')
        @vite('resources/js/pages/tracker-inventory.js')
    @endpush

    <main>
        <x-tool-hero
            badge="Third-Party Audit"
            description="See every external domain a URL loads — analytics, ad pixels, social SDKs, session replays, chat widgets, fonts, CDN — classified by purpose with privacy-impact grading."
            :primary-cta="['label' => 'Scan Now', 'href' => '#analyzer-panel']"
            :secondary-cta="['label' => 'How It Works', 'href' => '#how-it-works']"
            :trust-labels="['fast' => 'Lightning Fast', 'privacy' => 'Privacy-First', 'signup' => 'No Signup', 'unlimited' => 'Unlimited Use']"
        >
            <x-slot:title>Every pixel<br><span class="s-it text-accent">on your page.</span></x-slot:title>
            <x-slot:rightPanel>
                <x-preview-card variant="scope" icon="fa-solid fa-satellite-dish" icon-bg="linear-gradient(135deg,#0694a2,#a3e635)" icon-color="#061c21" title="Detected Trackers" subtitle="By purpose">
                    <div class="space-y-1.5">
                        @foreach ([
                            ['h' => 'google-analytics.com', 't' => 'Analytics', 'c' => '#0694a2'],
                            ['h' => 'connect.facebook.net', 't' => 'Advertising', 'c' => '#f87171'],
                            ['h' => 'static.hotjar.com', 't' => 'Session Replay', 'c' => '#f87171'],
                            ['h' => 'js.stripe.com', 't' => 'Payment', 'c' => '#65a30d'],
                            ['h' => 'fonts.googleapis.com', 't' => 'Fonts', 'c' => '#7edce2'],
                            ['h' => 'platform.twitter.com', 't' => 'Social', 'c' => '#16bdca'],
                        ] as $r)
                            <div class="flex items-center gap-2 text-xs rounded-lg px-2.5 py-1.5" style="background:rgba(255,255,255,0.04)">
                                <span class="font-mono text-[10px] truncate flex-1" style="color:var(--c-muted)">{{ $r['h'] }}</span>
                                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded" style="background:{{ $r['c'] }}22;color:{{ $r['c'] }}">{{ $r['t'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </x-preview-card>
            </x-slot:rightPanel>
        </x-tool-hero>

        <x-tool-ticker :items="[
            ['icon' => 'fa-solid fa-chart-bar', 'color' => '#0694a2', 'label' => 'Analytics'],
            ['icon' => 'fa-solid fa-bullhorn', 'color' => '#f87171', 'label' => 'Ad Pixels'],
            ['icon' => 'fa-solid fa-share-nodes', 'color' => '#16bdca', 'label' => 'Social'],
            ['icon' => 'fa-solid fa-film', 'color' => '#f87171', 'label' => 'Session Replay'],
            ['icon' => 'fa-solid fa-tag', 'color' => '#f59e0b', 'label' => 'Tag Manager'],
            ['icon' => 'fa-solid fa-comment-dots', 'color' => '#a3e635', 'label' => 'Chat'],
            ['icon' => 'fa-solid fa-font', 'color' => '#7edce2', 'label' => 'Fonts'],
            ['icon' => 'fa-solid fa-server', 'color' => '#0694a2', 'label' => 'CDN'],
            ['icon' => 'fa-solid fa-shield-halved', 'color' => '#f87171', 'label' => 'Privacy Grade'],
            ['icon' => 'fa-solid fa-eye', 'color' => '#f59e0b', 'label' => 'GDPR Audit'],
            ['icon' => 'fa-solid fa-bolt', 'color' => '#a3e635', 'label' => 'Instant Scan'],
            ['icon' => 'fa-solid fa-globe', 'color' => '#16bdca', 'label' => 'Per-Domain'],
        ]" />

        <x-how-it-works :steps="[
            ['icon' => 'fa-solid fa-globe', 'title' => 'Paste Your URL', 'desc' => 'Drop in any page URL. We fetch the HTML and parse every script, stylesheet, iframe, image, and media src — anything that triggers a third-party request.'],
            ['icon' => 'fa-solid fa-magnifying-glass', 'title' => 'Classified by Purpose', 'desc' => 'Each external domain is matched against a database of 70+ known trackers and grouped into analytics, ads, social, chat, CDN, fonts, payment, captcha, error tracking and more.'],
            ['icon' => 'fa-solid fa-shield-halved', 'title' => 'Privacy-Graded', 'desc' => 'Trackers are flagged High / Medium / Low privacy impact. Session replays and ad pixels are High; fonts and CDN are Low. You see exactly what\'s worth disclosing in your privacy policy.'],
        ]">
            <x-slot:subtitle>Three steps to <span class="s-it text-accent">your real stack.</span></x-slot:subtitle>
        </x-how-it-works>

        <x-feature-highlights
            badge="Why This Tool"
            description="Hidden tag managers fire other pixels. Session replays record everything users type. This tool surfaces them all — not just the ones you intentionally installed."
            :features="[
                ['icon' => 'fa-solid fa-satellite-dish', 'title' => '70+ tracker signatures', 'desc' => 'Recognizes Google Analytics, Meta Pixel, TikTok, LinkedIn Insight, Hotjar, FullStory, Clarity, Intercom, Stripe, reCAPTCHA, Sentry, and dozens more by domain pattern.'],
                ['icon' => 'fa-solid fa-shield-halved', 'title' => 'Privacy-impact grading', 'desc' => 'Each tracker gets a privacy grade: High (Ad pixels, session replays), Medium (analytics with PII), Low (fonts, CDN). Quick triage for your privacy policy.'],
                ['icon' => 'fa-solid fa-tag', 'title' => 'Tag manager detection', 'desc' => 'Google Tag Manager (and similar) is flagged separately — because what GTM loads is invisible to a static scan, so you know to audit GTM rules separately.'],
                ['icon' => 'fa-solid fa-film', 'title' => 'Session replay surfaced', 'desc' => 'Hotjar, FullStory, Microsoft Clarity, LogRocket and others record user sessions. We highlight these as High privacy impact since they often capture form input and clicks.'],
                ['icon' => 'fa-solid fa-server', 'title' => 'Per-domain request count', 'desc' => 'Each domain shows total request count and breakdown by type (script, stylesheet, iframe, image). Spot the bloat that came in from one library.'],
                ['icon' => 'fa-solid fa-copy', 'title' => 'Copy-as-list output', 'desc' => 'Each category has a one-click copy button — paste the host list into your CSP allowlist, ad-blocker rule, or privacy policy disclosure.'],
            ]"
        >
            <x-slot:title>Most pages load <span class="s-it text-accent">more than you think.</span></x-slot:title>
        </x-feature-highlights>

        <x-analyzer-section>
            <div id="tracker-inventory-app"></div>
        </x-analyzer-section>

        <x-faq-section :items="[
            ['q' => 'Does this catch trackers loaded by tag managers?', 'a' => 'Partially. We detect Google Tag Manager itself, but the pixels GTM fires at runtime can\'t be seen from a static HTML scan — those load via JS execution after the page boots. For a complete view, also audit your GTM container directly.'],
            ['q' => 'Why is Hotjar marked \'High Privacy Impact\'?', 'a' => 'Session replay tools record user mouse movement, clicks, scrolls, and often form input. Even with PII masking enabled, they typically require explicit consent under GDPR and disclosure in your privacy policy.'],
            ['q' => 'Are subdomain requests counted as third-party?', 'a' => 'We compare hostnames with the www. prefix stripped. A request to api.yoursite.com from yoursite.com is treated as same-origin. Different roots (cdn.yourcdn.com) are third-party.'],
            ['q' => 'Why doesn\'t my favicon show up?', 'a' => 'We focus on requests that load executable code or trackable assets: scripts, stylesheets, iframes, images, media, preconnect/preload links. Inline data: URIs and same-origin assets are excluded.'],
            ['q' => 'Are URLs stored?', 'a' => 'No. The page is fetched, parsed in memory, classified, and the result is returned. Nothing is logged or persisted.'],
        ]" />

        <x-related-tools
            current-key="tracker-inventory"
            :tool-keys="['script-audit', 'security-headers', 'page-weight']"
            title="Related tools."
            description="Audit scripts, headers, and weight from the same URL bar."
        />
    </main>
</x-layout>
