<x-layout
    title="About DevMatrixa | Built for Devs & SEOs"
    description="DevMatrixa is a free toolkit for developers and SEO professionals. Learn who built it, why, and how these tools save hours of manual audit work."
    keywords="about devmatrixa, devmatrixa team, free seo tools platform, developer tools company"
>
    @php
        $tools = config('tools');
        $seoTools = collect($tools)->where('cat', 'seo')->values();
        $devTools = collect($tools)->where('cat', '!=', 'seo')->values();
        $totalTools = count($tools);

        $storyCards = [
            ['icon' => 'fa-bolt', 'title' => 'Instant Results', 'desc' => 'No queues, no waiting. Analysis runs in seconds directly in your browser.', 'g1' => '#0694a2', 'g2' => '#16bdca'],
            ['icon' => 'fa-shield-halved', 'title' => 'Privacy First', 'desc' => "We don't store URLs, results, or any user data. What you check stays yours.", 'g1' => '#16bdca', 'g2' => '#a3e635'],
            ['icon' => 'fa-code', 'title' => 'Developer Grade', 'desc' => 'Built by developers for developers. Every tool is precise, reliable, and clutter-free.', 'g1' => '#a3e635', 'g2' => '#0694a2'],
            ['icon' => 'fa-sparkles', 'title' => 'Always Free', 'desc' => 'No trial periods, no pro tiers, no credit card. Genuinely free, always.', 'g1' => '#047481', 'g2' => '#16bdca'],
        ];

        $principles = [
            ['n' => '01', 'icon' => 'fa-door-open', 'title' => 'No Barriers', 'desc' => 'Every tool is immediately accessible. No account creation, no email required, no onboarding flow to slip through.'],
            ['n' => '02', 'icon' => 'fa-eye-slash', 'title' => 'Zero Tracking', 'desc' => "We don't log the URLs you analyze, the results you get, or any personal information. Your data is genuinely yours."],
            ['n' => '03', 'icon' => 'fa-bullseye', 'title' => 'Single Purpose', 'desc' => 'Each tool does one thing extremely well. No feature creep. No trying to replicate entire SaaS platforms. Focused utility.'],
            ['n' => '04', 'icon' => 'fa-arrow-trend-up', 'title' => 'Always Improving', 'desc' => 'New tools ship regularly. Existing tools get smarter — better checks, richer output, more actionable fixes over time.'],
        ];

        $stack = [
            ['name' => 'Laravel', 'icon' => 'fa-brands fa-laravel', 'g1' => '#0694a2', 'g2' => '#16bdca', 'desc' => 'PHP framework powering server rendering, routing, and the backend for every tool.'],
            ['name' => 'Vue.js', 'icon' => 'fa-brands fa-vuejs', 'g1' => '#16bdca', 'g2' => '#a3e635', 'desc' => 'Lightweight interactive islands for the parts of the UI that need real client-side state.'],
            ['name' => 'Tailwind CSS', 'icon' => 'fa-solid fa-wind', 'g1' => '#a3e635', 'g2' => '#0694a2', 'desc' => 'Utility-first CSS for a consistent, responsive UI across every page.'],
            ['name' => 'Font Awesome', 'icon' => 'fa-solid fa-icons', 'g1' => '#047481', 'g2' => '#a3e635', 'desc' => 'Icon library providing consistent, scalable icons throughout the interface.'],
        ];
    @endphp

    <main>
        {{-- HERO --}}
        <section class="relative pt-32 pb-16 px-5 sm:px-8 dot-bg overflow-hidden" style="z-index:30;isolation:isolate">
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="orb w-[700px] h-[700px] animate-drift opacity-20 dark:opacity-15" style="background:radial-gradient(circle,#0694a2,transparent 65%);top:-25%;right:-18%"></div>
                <div class="orb w-[500px] h-[500px] animate-drift2 opacity-15 dark:opacity-10" style="background:radial-gradient(circle,#a3e635,transparent 65%);bottom:-15%;left:-12%"></div>
            </div>

            <div class="relative z-10 max-w-3xl mx-auto text-center">
                <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full text-xs font-semibold uppercase tracking-widest mb-7" style="background:rgba(6,148,162,0.10);border:1.5px solid rgba(6,148,162,0.22);color:#0694a2">
                    <i class="fa-solid fa-wand-magic-sparkles"></i> About Devmatrixa
                </div>

                <h1 class="font-sans text-5xl sm:text-6xl font-700 tracking-tight leading-[1.05] mb-6">
                    Tools built for<br>
                    <span class="s-it text-accent">people who ship.</span>
                </h1>

                <p class="text-lg leading-relaxed mb-9 max-w-2xl mx-auto" style="color:var(--c-muted)">
                    Devmatrixa is a free, no-signup collection of developer and SEO tools built to remove friction from your workflow. No bloat. No paywalls. Just tools that work.
                </p>

                <div class="flex flex-wrap items-center justify-center gap-4">
                    <a href="{{ url('/#tools') }}" class="btn-primary px-7 py-3.5 rounded-full text-sm inline-flex items-center gap-2.5 font-700">
                        <i class="fa-solid fa-rocket" style="color:#061c21"></i> Explore All Tools
                    </a>
                    <a href="#story" class="btn-outline px-7 py-3.5 rounded-full text-sm inline-flex items-center gap-2.5 font-semibold">
                        Our Story <i class="fa-solid fa-arrow-down text-xs" style="color:var(--c-muted)"></i>
                    </a>
                </div>
            </div>
        </section>

        {{-- STATS --}}
        <section class="py-12 px-5 sm:px-8">
            <div class="max-w-6xl mx-auto">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
                    @foreach ([
                        ['n' => $totalTools.'+', 'l' => 'Free Tools'],
                        ['n' => '0', 'l' => 'Data Stored'],
                        ['n' => '100%', 'l' => 'Free Forever'],
                        ['n' => '0', 'l' => 'Signups Required'],
                    ] as $s)
                        <div class="text-center">
                            <p class="stat-num text-5xl mb-1 text-accent">{{ $s['n'] }}</p>
                            <p class="text-[11px] uppercase tracking-widest font-semibold" style="color:var(--c-muted)">{{ $s['l'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- OUR STORY --}}
        <section id="story" class="py-16 px-5 sm:px-8">
            <div class="max-w-7xl mx-auto grid lg:grid-cols-2 gap-12 items-start">
                <div>
                    <p class="text-xs uppercase tracking-widest font-semibold mb-2" style="color:#0694a2">Our Story</p>
                    <h2 class="font-sans text-3xl sm:text-4xl font-700 tracking-tight mb-5">
                        Born from developer <span class="s-it text-accent">frustration.</span>
                    </h2>
                    <div class="space-y-4 text-[15px] leading-relaxed" style="color:var(--c-muted)">
                        <p>Every developer and SEO professional knows the pain — you need to quickly check a site's meta tags, find broken links, or extract critical color palettes, but every tool either costs money, demands an account, or buries the answer behind five ads.</p>
                        <p>Devmatrixa started as a personal collection of scripts. Tools we actually used every day while building and auditing websites. Simple, fast, single-purpose utilities that get out of the way and let you focus on the work.</p>
                        <p>We turned those scripts into a clean, consistent web app — and made every single one completely free with zero signup requirements. Because good tools shouldn't be locked behind paywalls.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach ($storyCards as $c)
                        <div class="feat-card rounded-2xl p-5">
                            <div class="t-icon w-11 h-11 rounded-xl flex items-center justify-center mb-3 shadow-lg" style="background:linear-gradient(135deg,{{ $c['g1'] }},{{ $c['g2'] }});box-shadow:0 8px 20px {{ $c['g1'] }}44">
                                <i class="fa-solid {{ $c['icon'] }} text-sm" style="color:{{ $c['g2'] === '#a3e635' ? '#061c21' : '#fff' }}"></i>
                            </div>
                            <h3 class="font-sans font-600 text-sm tracking-tight mb-1.5">{{ $c['title'] }}</h3>
                            <p class="text-xs leading-relaxed" style="color:var(--c-muted)">{{ $c['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- PRINCIPLES --}}
        <section class="py-16 px-5 sm:px-8 dot-bg">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-12">
                    <p class="text-xs uppercase tracking-widest font-semibold mb-2" style="color:#0694a2">What we stand for</p>
                    <h2 class="font-sans text-3xl sm:text-4xl font-700 tracking-tight">
                        Our principles shape every <span class="s-it text-accent">tool we build</span>
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                    @foreach ($principles as $p)
                        <div class="feat-card rounded-2xl p-6 relative overflow-hidden">
                            <span class="absolute top-4 right-5 stat-num text-3xl opacity-30" style="color:#16bdca">{{ $p['n'] }}</span>
                            <div class="t-icon w-12 h-12 rounded-2xl flex items-center justify-center mb-4 shadow-lg" style="background:linear-gradient(135deg,#0694a2,#a3e635);box-shadow:0 10px 24px rgba(6,148,162,0.32)">
                                <i class="fa-solid {{ $p['icon'] }} text-base" style="color:#061c21"></i>
                            </div>
                            <h3 class="font-sans font-600 text-base tracking-tight mb-1.5">{{ $p['title'] }}</h3>
                            <p class="text-xs leading-relaxed" style="color:var(--c-muted)">{{ $p['desc'] }}</p>
                            <div class="mt-4 h-1 rounded-full" style="background:linear-gradient(90deg, rgba(6,148,162,0.45), rgba(163,230,53,0.0))"></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- TOOLKIT --}}
        <section class="py-16 px-5 sm:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-12">
                    <p class="text-xs uppercase tracking-widest font-semibold mb-2" style="color:#0694a2">The toolkit</p>
                    <h2 class="font-sans text-3xl sm:text-4xl font-700 tracking-tight">
                        {{ $totalTools }} tools. Two categories. <span class="s-it text-accent">Infinite use.</span>
                    </h2>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="glass rounded-3xl p-6 sm:p-7">
                        <div class="flex items-center gap-3 mb-5 pb-4" style="border-bottom:1px solid var(--c-border)">
                            <div class="w-11 h-11 rounded-xl flex items-center justify-center shadow-lg" style="background:linear-gradient(135deg,#a3e635,#0694a2);box-shadow:0 8px 20px rgba(163,230,53,0.32)">
                                <i class="fa-solid fa-magnifying-glass-chart text-sm" style="color:#061c21"></i>
                            </div>
                            <div>
                                <h3 class="font-sans font-600 text-base tracking-tight">SEO Tools</h3>
                                <p class="text-[10px] uppercase tracking-widest font-semibold" style="color:var(--c-muted)">{{ $seoTools->count() }} tools</p>
                            </div>
                        </div>
                        <ul class="space-y-3">
                            @foreach ($seoTools as $t)
                                <li>
                                    <a href="{{ url($t['url'] ?? '#') }}" class="flex items-start gap-3 p-2 -m-2 rounded-xl transition-colors hover:bg-teal-900/15">
                                        <div class="shrink-0 w-8 h-8 rounded-lg flex items-center justify-center mt-0.5" style="background:linear-gradient(135deg,{{ $t['g1'] }},{{ $t['g2'] }})">
                                            <i class="{{ $t['icon'] }} text-[11px]" style="color:{{ $t['g2'] === '#a3e635' ? '#061c21' : '#fff' }}"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold" style="color:var(--c-text)">{{ $t['name'] }}</p>
                                            <p class="text-[11px] leading-relaxed mt-0.5" style="color:var(--c-muted)">{{ explode('.', $t['desc'])[0] }}.</p>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="glass rounded-3xl p-6 sm:p-7">
                        <div class="flex items-center gap-3 mb-5 pb-4" style="border-bottom:1px solid var(--c-border)">
                            <div class="w-11 h-11 rounded-xl flex items-center justify-center shadow-lg" style="background:linear-gradient(135deg,#0694a2,#16bdca);box-shadow:0 8px 20px rgba(6,148,162,0.32)">
                                <i class="fa-solid fa-code text-sm text-white"></i>
                            </div>
                            <div>
                                <h3 class="font-sans font-600 text-base tracking-tight">Dev Tools</h3>
                                <p class="text-[10px] uppercase tracking-widest font-semibold" style="color:var(--c-muted)">{{ $devTools->count() }} tools</p>
                            </div>
                        </div>
                        <ul class="space-y-3">
                            @foreach ($devTools as $t)
                                <li>
                                    <a href="{{ url($t['url'] ?? '#') }}" class="flex items-start gap-3 p-2 -m-2 rounded-xl transition-colors hover:bg-teal-900/15">
                                        <div class="shrink-0 w-8 h-8 rounded-lg flex items-center justify-center mt-0.5" style="background:linear-gradient(135deg,{{ $t['g1'] }},{{ $t['g2'] }})">
                                            <i class="{{ $t['icon'] }} text-[11px]" style="color:{{ $t['g2'] === '#a3e635' ? '#061c21' : '#fff' }}"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold" style="color:var(--c-text)">{{ $t['name'] }}</p>
                                            <p class="text-[11px] leading-relaxed mt-0.5" style="color:var(--c-muted)">{{ explode('.', $t['desc'])[0] }}.</p>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        {{-- TEAM --}}
        <section class="py-16 px-5 sm:px-8">
            <div class="max-w-5xl mx-auto">
                <div class="text-center mb-10">
                    <p class="text-xs uppercase tracking-widest font-semibold mb-2" style="color:#0694a2">The team</p>
                    <h2 class="font-sans text-3xl sm:text-4xl font-700 tracking-tight">
                        Crafted with <span class="s-it text-accent">care</span>
                    </h2>
                </div>

                <div class="glass rounded-3xl p-6 sm:p-8">
                    <div class="flex flex-col sm:flex-row gap-6 items-start">
                        <div class="shrink-0 w-20 h-20 rounded-2xl flex items-center justify-center shadow-lg" style="background:linear-gradient(135deg,#0694a2,#a3e635);box-shadow:0 12px 28px rgba(6,148,162,0.34)">
                            <span class="font-sans font-700 text-3xl" style="color:#061c21">A</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-sans font-600 text-xl tracking-tight">Alekh Banshwar</h3>
                            <p class="text-xs uppercase tracking-widest font-semibold mb-3" style="color:#0694a2">Founder &amp; Developer</p>
                            <p class="text-sm leading-relaxed mb-4" style="color:var(--c-muted)">
                                Full-stack developer passionate about building tools that solve real problems. Devmatrixa is a personal project born out of the need for fast, no-nonsense utilities that respect your time and privacy. Every tool here is something I use myself — built to the standard I want in my own workflow.
                            </p>
                            <div class="flex flex-wrap gap-2">
                                @foreach ([
                                    ['icon' => 'fa-solid fa-server', 'label' => 'Laravel'],
                                    ['icon' => 'fa-brands fa-vuejs', 'label' => 'Vue.js'],
                                    ['icon' => 'fa-solid fa-wind', 'label' => 'Tailwind CSS'],
                                    ['icon' => 'fa-solid fa-magnifying-glass-chart', 'label' => 'SEO Tools'],
                                ] as $b)
                                    <span class="text-[11px] font-semibold px-3 py-1.5 rounded-full inline-flex items-center gap-1.5" style="background:rgba(6,148,162,0.10);border:1px solid rgba(6,148,162,0.22);color:#0694a2">
                                        <i class="{{ $b['icon'] }} text-[10px]"></i> {{ $b['label'] }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- STACK --}}
        <section class="py-16 px-5 sm:px-8 dot-bg">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-12">
                    <p class="text-xs uppercase tracking-widest font-semibold mb-2" style="color:#0694a2">How it's built</p>
                    <h2 class="font-sans text-3xl sm:text-4xl font-700 tracking-tight">
                        Simple stack. <span class="s-it text-accent">Reliable foundation.</span>
                    </h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    @foreach ($stack as $s)
                        <div class="feat-card rounded-2xl p-6">
                            <div class="t-icon w-12 h-12 rounded-2xl flex items-center justify-center mb-4 shadow-lg" style="background:linear-gradient(135deg,{{ $s['g1'] }},{{ $s['g2'] }});box-shadow:0 10px 24px {{ $s['g1'] }}44">
                                <i class="{{ $s['icon'] }} text-base" style="color:{{ $s['g2'] === '#a3e635' ? '#061c21' : '#fff' }}"></i>
                            </div>
                            <h3 class="font-sans font-600 text-base tracking-tight mb-1.5">{{ $s['name'] }}</h3>
                            <p class="text-xs leading-relaxed" style="color:var(--c-muted)">{{ $s['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- CTA --}}
        <section class="py-20 px-5 sm:px-8">
            <div class="max-w-5xl mx-auto">
                <div class="relative rounded-3xl overflow-hidden px-8 sm:px-14 py-16 text-center" style="background:var(--c-bg2);border:1px solid var(--c-border)">
                    <div class="orb w-80 h-80 opacity-30 dark:opacity-20" style="background:radial-gradient(circle,#0694a2,transparent 70%);top:-40%;left:-10%;filter:blur(60px)"></div>
                    <div class="orb w-80 h-80 opacity-20 dark:opacity-15" style="background:radial-gradient(circle,#a3e635,transparent 70%);bottom:-30%;right:-10%;filter:blur(60px)"></div>
                    <div class="relative z-10">
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-semibold uppercase tracking-wider mb-6" style="background:rgba(6,148,162,0.10);border:1px solid rgba(6,148,162,0.22);color:#0694a2">
                            <i class="fa-solid fa-rocket"></i> Ready to build
                        </div>
                        <h2 class="font-sans text-4xl sm:text-5xl font-700 tracking-tight mb-4">
                            Start using Devmatrixa.<br><span class="s-it text-accent">No account needed.</span>
                        </h2>
                        <p class="text-lg mb-10 max-w-xl mx-auto" style="color:var(--c-muted)">
                            Every tool is live and ready to use right now. Pick one, paste a URL, and get results in seconds.
                        </p>
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                            <a href="{{ url('/#tools') }}" class="btn-primary px-8 py-4 rounded-full text-sm font-700 inline-flex items-center gap-2.5">
                                <i class="fa-solid fa-hammer" style="color:#061c21"></i> Browse All Tools
                            </a>
                            <a href="{{ url('/seo-analyzer') }}" class="btn-outline px-8 py-4 rounded-full text-sm font-semibold inline-flex items-center gap-2.5">
                                <i class="fa-solid fa-magnifying-glass-chart text-xs" style="color:#0694a2"></i> Try SEO Analyzer
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-layout>
