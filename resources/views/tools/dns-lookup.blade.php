<x-layout
    title="DNS Lookup Tool – Query DNS Records Instantly"
    description="Look up A, AAAA, CNAME, MX, TXT, and NS records for any domain. Free DNS checker tool with real-time results, no installation needed."
    og-title="DNS Lookup Tool — Resolve Every DNS Record | Devmatrixa"
    og-description="Enter a domain and instantly resolve A, AAAA, CNAME, MX, TXT, NS, SOA, CAA and DMARC records with TTLs and email-security detection. Free, instant, no signup."
>
    @push('head')
        @vite('resources/js/pages/dns-lookup.js')
    @endpush

    <main>
        <x-tool-hero
            badge="DNS Record Lookup"
            description="Enter any domain and instantly resolve its full DNS footprint — A, AAAA, CNAME, MX, TXT, NS, SOA, CAA and DMARC — with TTLs, SPF/DKIM/DMARC detection, and reverse DNS. A fast dig alternative, right in your browser."
            :primary-cta="['label' => 'Look Up Now', 'href' => '#analyzer-panel']"
            :secondary-cta="['label' => 'How It Works', 'href' => '#how-it-works']"
            :trust-labels="['fast' => 'Lightning Fast', 'privacy' => 'Privacy-First', 'signup' => 'No Signup', 'unlimited' => 'Unlimited Use']"
        >
            <x-slot:title>Resolve every<br><span class="s-it text-accent">DNS record.</span></x-slot:title>
            <x-slot:rightPanel>
                <x-preview-card variant="terminal" icon="fa-solid fa-server" icon-bg="linear-gradient(135deg,#16bdca,#a3e635)" icon-color="#0b1316" title="dig devmatrixa.com" subtitle="DNS resolve">
                    <div class="space-y-1.5 font-mono text-[11px]">
                        @foreach ([
                            ['t' => 'A', 'v' => '76.76.21.21', 'c' => '#a3e635'],
                            ['t' => 'AAAA', 'v' => '2606:4700::6810', 'c' => '#84cc16'],
                            ['t' => 'MX', 'v' => '10 mx.example.com', 'c' => '#0694a2'],
                            ['t' => 'NS', 'v' => 'ns1.vercel-dns.com', 'c' => '#7edce2'],
                            ['t' => 'TXT', 'v' => 'v=spf1 include:_spf…', 'c' => '#f59e0b'],
                            ['t' => 'CAA', 'v' => '0 issue letsencrypt', 'c' => '#f97316'],
                        ] as $r)
                            <div class="flex items-center gap-2 rounded-lg px-2.5 py-1.5" style="background:rgba(255,255,255,0.04)">
                                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded shrink-0" style="background:{{ $r['c'] }}22;color:{{ $r['c'] }}">{{ $r['t'] }}</span>
                                <span class="truncate flex-1" style="color:var(--c-muted)">{{ $r['v'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </x-preview-card>
            </x-slot:rightPanel>
        </x-tool-hero>

        <x-tool-ticker :items="[
            ['icon' => 'fa-solid fa-globe', 'color' => '#a3e635', 'label' => 'A / AAAA'],
            ['icon' => 'fa-solid fa-link', 'color' => '#16bdca', 'label' => 'CNAME'],
            ['icon' => 'fa-solid fa-envelope', 'color' => '#0694a2', 'label' => 'MX'],
            ['icon' => 'fa-solid fa-file-lines', 'color' => '#f59e0b', 'label' => 'TXT'],
            ['icon' => 'fa-solid fa-network-wired', 'color' => '#7edce2', 'label' => 'NS'],
            ['icon' => 'fa-solid fa-database', 'color' => '#16bdca', 'label' => 'SOA'],
            ['icon' => 'fa-solid fa-shield-halved', 'color' => '#f97316', 'label' => 'CAA'],
            ['icon' => 'fa-solid fa-shield-halved', 'color' => '#f59e0b', 'label' => 'DMARC'],
            ['icon' => 'fa-solid fa-shield-halved', 'color' => '#0694a2', 'label' => 'SPF'],
            ['icon' => 'fa-solid fa-rotate-left', 'color' => '#a3e635', 'label' => 'Reverse DNS'],
            ['icon' => 'fa-solid fa-bolt', 'color' => '#16bdca', 'label' => 'TTL Values'],
            ['icon' => 'fa-solid fa-server', 'color' => '#a3e635', 'label' => 'dig Alternative'],
        ]" />

        <x-how-it-works :steps="[
            ['icon' => 'fa-solid fa-globe', 'title' => 'Enter a Domain', 'desc' => 'Type any domain — example.com, a subdomain, or even a full URL. We strip it down to the hostname and validate it before querying.'],
            ['icon' => 'fa-solid fa-server', 'title' => 'Query DNS Records', 'desc' => 'We resolve every common record type in parallel — A, AAAA, CNAME, MX, TXT, NS, SOA, CAA and the _dmarc policy.'],
            ['icon' => 'fa-solid fa-layer-group', 'title' => 'Read & Copy', 'desc' => 'Records are grouped by type with TTLs, MX priorities, and SPF/DKIM/DMARC tags. One-click copy on every value — no terminal, no dig install.'],
        ]">
            <x-slot:subtitle>Three steps to <span class="s-it text-accent">map your DNS.</span></x-slot:subtitle>
        </x-how-it-works>

        <x-feature-highlights
            badge="Why This Tool"
            description="Most online DNS checkers show you A and MX records and stop. This one resolves the full footprint and explains what each record is for."
            :features="[
                ['icon' => 'fa-solid fa-globe', 'title' => 'Full record coverage', 'desc' => 'A, AAAA, CNAME, MX, TXT, NS, SOA, CAA and DMARC — all in one query. No clicking through separate tabs for each record type.'],
                ['icon' => 'fa-solid fa-bolt', 'title' => 'Real TTL values', 'desc' => 'Every A, AAAA, and SOA record shows its actual TTL, so you know how long records are cached and how fast a change will propagate.'],
                ['icon' => 'fa-solid fa-shield-halved', 'title' => 'Email security at a glance', 'desc' => 'TXT records are auto-tagged as SPF, DKIM, or domain verification, and we pull the _dmarc policy separately so you can audit spoofing protection in one view.'],
                ['icon' => 'fa-solid fa-envelope', 'title' => 'MX priority ordering', 'desc' => 'Mail servers are sorted by priority exactly as a sending server would try them — so you can spot a misconfigured backup MX instantly.'],
                ['icon' => 'fa-solid fa-rotate-left', 'title' => 'Reverse DNS included', 'desc' => 'We run a PTR lookup on the primary IPv4 address automatically — useful for verifying mail server identity and hosting.'],
                ['icon' => 'fa-solid fa-copy', 'title' => 'Copy any value', 'desc' => 'Every resolved value has a one-click copy button. Grab an IP, a name server, or a full SPF string without re-typing.'],
            ]"
        >
            <x-slot:title>Everything <code>dig</code> tells you, <span class="s-it text-accent">without the terminal.</span></x-slot:title>
        </x-feature-highlights>

        <x-analyzer-section>
            <div id="dns-lookup-app"></div>
        </x-analyzer-section>

        <x-faq-section :items="[
            ['q' => 'Where do these DNS results come from?', 'a' => 'We resolve each record type in real time via the server\'s configured DNS resolver. Results reflect what that resolver currently has cached, which is what most of the internet sees.'],
            ['q' => 'Why is a record type showing \'No records\'?', 'a' => 'It simply means the domain has no record of that type published. For example, a domain with no IPv6 will show an empty AAAA, and a domain that doesn\'t send email may have no MX or DMARC.'],
            ['q' => 'What\'s the difference between SPF, DKIM, and DMARC?', 'a' => 'All three are email-authentication records. SPF (a TXT record starting with v=spf1) lists who may send mail for you. DKIM signs messages cryptographically. DMARC (published at _dmarc) tells receivers what to do with mail that fails SPF/DKIM. We tag and surface each one.'],
            ['q' => 'What is the PTR / reverse DNS record?', 'a' => 'PTR maps an IP address back to a hostname — the opposite of an A record. We run it automatically on your domain\'s primary IPv4. It\'s commonly used to verify mail-server identity and reduce the chance of being flagged as spam.'],
            ['q' => 'Why do TTL values matter?', 'a' => 'TTL (time to live) is how long resolvers cache a record. A low TTL means DNS changes propagate quickly; a high TTL means old values may linger for hours. Check the TTL before planning a migration or failover.'],
            ['q' => 'Do you store the domains I look up?', 'a' => 'No. Each lookup is resolved in memory and returned immediately. Nothing is logged or persisted.'],
        ]" />

        <x-related-tools
            current-key="dns-lookup"
            :tool-keys="['security-headers', 'redirect-chain', 'tech-stack-detector']"
            title="Related tools."
            description="Go deeper on security, redirects, and the stack behind any domain."
        />
    </main>
</x-layout>
