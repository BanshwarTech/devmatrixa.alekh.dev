<x-layout
    title="Security Headers Checker – Test HTTP Headers"
    description="Check if your site has proper security headers like CSP, HSTS, and X-Frame-Options. Free HTTP security header scanner with instant results."
    keywords="security headers checker, http security header scanner, csp checker, hsts checker, website security audit"
    og-title="Security Headers Auditor — Grade Any URL's Headers | Devmatrixa"
    og-description="Paste a URL, get an A+ to F grade on its security headers with per-header explanations, weak-config warnings, and one-line fix snippets. No data stored."
>
    @push('head')
        @vite('resources/js/pages/security-headers.js')
    @endpush

    <main>
        <x-tool-hero
            badge="HTTP Security Audit"
            description="Paste any URL and get an instant A+ to F grade on its HTTP security headers — CSP, HSTS, X-Frame-Options, Permissions-Policy and more — with per-header explanations and copy-paste fix snippets."
            :primary-cta="['label' => 'Audit Now', 'href' => '#analyzer-panel']"
            :secondary-cta="['label' => 'How It Works', 'href' => '#how-it-works']"
            :trust-labels="['fast' => 'Lightning Fast', 'privacy' => 'Privacy-First', 'signup' => 'No Signup', 'unlimited' => 'Unlimited Use']"
        >
            <x-slot:title>Grade your<br><span class="s-it text-accent">security headers.</span></x-slot:title>
            <x-slot:rightPanel>
                <x-preview-card variant="gauge" icon="fa-solid fa-shield" icon-bg="linear-gradient(135deg,#0694a2,#a3e635)" icon-color="#0b1316" title="Header Grade" subtitle="Live audit">
                    <div class="space-y-1.5">
                        @foreach ([
                            ['h' => 'Content-Security-Policy', 's' => 'good', 'c' => '#65a30d'],
                            ['h' => 'Strict-Transport-Security', 's' => 'good', 'c' => '#65a30d'],
                            ['h' => 'X-Frame-Options', 's' => 'weak', 'c' => '#f59e0b'],
                            ['h' => 'Referrer-Policy', 's' => 'missing', 'c' => '#f87171'],
                            ['h' => 'Permissions-Policy', 's' => 'missing', 'c' => '#f87171'],
                            ['h' => 'X-Powered-By', 's' => 'leak', 'c' => '#f97316'],
                        ] as $r)
                            <div class="flex items-center gap-2 text-xs rounded-lg px-2.5 py-1.5" style="background:rgba(255,255,255,0.04)">
                                <span class="font-mono text-[10px] truncate flex-1" style="color:var(--c-muted)">{{ $r['h'] }}</span>
                                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded" style="background:{{ $r['c'] }}22;color:{{ $r['c'] }}">{{ strtoupper($r['s']) }}</span>
                            </div>
                        @endforeach
                    </div>
                </x-preview-card>
            </x-slot:rightPanel>
        </x-tool-hero>

        <x-tool-ticker :items="[
            ['icon' => 'fa-solid fa-shield', 'color' => '#0694a2', 'label' => 'CSP'],
            ['icon' => 'fa-solid fa-lock', 'color' => '#16bdca', 'label' => 'HSTS'],
            ['icon' => 'fa-solid fa-shield-halved', 'color' => '#a3e635', 'label' => 'X-Frame-Options'],
            ['icon' => 'fa-solid fa-magnifying-glass', 'color' => '#7edce2', 'label' => 'X-Content-Type'],
            ['icon' => 'fa-solid fa-eye', 'color' => '#0694a2', 'label' => 'Referrer-Policy'],
            ['icon' => 'fa-solid fa-key', 'color' => '#16bdca', 'label' => 'Permissions-Policy'],
            ['icon' => 'fa-solid fa-globe', 'color' => '#a3e635', 'label' => 'COOP / COEP'],
            ['icon' => 'fa-solid fa-user-shield', 'color' => '#f59e0b', 'label' => 'Clickjacking'],
            ['icon' => 'fa-solid fa-shield-virus', 'color' => '#f87171', 'label' => 'Info Leaks'],
            ['icon' => 'fa-solid fa-server', 'color' => '#16bdca', 'label' => 'Server Header'],
            ['icon' => 'fa-solid fa-bolt', 'color' => '#a3e635', 'label' => 'Instant Grade'],
            ['icon' => 'fa-solid fa-file-circle-exclamation', 'color' => '#f97316', 'label' => 'Fix Snippets'],
        ]" />

        <x-how-it-works :steps="[
            ['icon' => 'fa-solid fa-globe', 'title' => 'Paste Your URL', 'desc' => 'Drop in any production or staging URL. The auditor performs a single GET request and reads the response headers — no JS execution, no crawl, no data stored.'],
            ['icon' => 'fa-solid fa-magnifying-glass', 'title' => 'Per-Header Grade', 'desc' => 'Each header is checked against industry best practice. CSP is parsed for unsafe-inline / unsafe-eval, HSTS for max-age, X-Frame-Options for clickjacking, and so on.'],
            ['icon' => 'fa-solid fa-shield-halved', 'title' => 'Copy & Fix', 'desc' => 'Each weak or missing header comes with a one-line fix snippet — copy it straight into your nginx, Apache, Cloudflare, or framework config.'],
        ]">
            <x-slot:subtitle>Three steps to <span class="s-it text-accent">harden your headers.</span></x-slot:subtitle>
        </x-how-it-works>

        <x-feature-highlights
            badge="Why This Tool"
            description="Most security-headers tools either grade you without explanation or dump raw RFCs at you. This one tells you the why and the exact line to ship."
            :features="[
                ['icon' => 'fa-solid fa-shield', 'title' => 'CSP that\'s parsed, not just present', 'desc' => 'We grade the CSP value itself — \'unsafe-inline\', \'unsafe-eval\', wildcards in script-src, and missing frame-ancestors all drop your score, even if the header is technically there.'],
                ['icon' => 'fa-solid fa-lock', 'title' => 'HSTS max-age awareness', 'desc' => 'Most checkers say \'HSTS: present\'. We tell you if your max-age is under 6 months or missing includeSubDomains — the things that actually matter for preload eligibility.'],
                ['icon' => 'fa-solid fa-file-circle-exclamation', 'title' => 'Info-leak detection', 'desc' => 'Server, X-Powered-By, and X-AspNet-Version headers fingerprint your stack for attackers. We flag them as leaks and tell you exactly where to strip them.'],
                ['icon' => 'fa-solid fa-shield-halved', 'title' => 'Cross-Origin isolation checks', 'desc' => 'COOP, COEP, and CORP are graded so you know when your site is ready for SharedArrayBuffer or cross-origin isolation features.'],
                ['icon' => 'fa-solid fa-copy', 'title' => 'Copy-paste fix snippets', 'desc' => 'Every weak or missing header has a one-line value you can drop straight into your config. No more digging through MDN to remember the exact directive syntax.'],
                ['icon' => 'fa-solid fa-bolt', 'title' => 'Single request, instant grade', 'desc' => 'One GET request, full grade in under a second. No crawling, no signups, no data stored — just headers in, A+ to F out.'],
            ]"
        >
            <x-slot:title>An A+ shouldn't be <span class="s-it text-accent">a guessing game.</span></x-slot:title>
        </x-feature-highlights>

        <x-analyzer-section>
            <div id="security-headers-app"></div>
        </x-analyzer-section>

        <x-faq-section :items="[
            ['q' => 'What\'s a passing grade?', 'a' => 'B or above (70+) is considered acceptable for a production site. A or A+ requires a strict CSP, full HSTS with preload, and proper Cross-Origin policies. F means most critical headers are missing.'],
            ['q' => 'Why is my CSP graded as \'weak\' even though it\'s present?', 'a' => 'We parse the CSP value, not just check its presence. \'unsafe-inline\' and \'unsafe-eval\' allow most XSS payloads to still run. Wildcards in script-src defeat the purpose. Add \'frame-ancestors\' to block clickjacking too.'],
            ['q' => 'Is HSTS really needed if my site forces HTTPS?', 'a' => 'Yes. A 301 to HTTPS still leaves the first request vulnerable to MITM. HSTS tells the browser to never speak HTTP to your origin again. With max-age ≥ 6 months and includeSubDomains, you also qualify for the HSTS preload list.'],
            ['q' => 'Are Server and X-Powered-By really dangerous?', 'a' => 'Not directly exploitable, but they tell attackers your exact stack and version — useful for targeting known CVEs. Strip them at your reverse proxy or framework config.'],
            ['q' => 'Does this tool store the URLs I check?', 'a' => 'No. Every audit is one GET request, headers are graded in memory, and the result is returned. Nothing is logged or persisted.'],
        ]" />

        <x-related-tools
            current-key="security-headers"
            :tool-keys="['script-audit', 'tech-stack-detector', 'link-checker']"
            title="Related tools."
            description="Audit deeper across performance, scripts, and stack."
        />
    </main>
</x-layout>
