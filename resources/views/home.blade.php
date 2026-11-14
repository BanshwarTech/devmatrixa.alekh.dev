<x-layout
    keywords="free seo tools, free developer tools, website audit tools, seo checker online, web development utilities, devmatrixa"
>
    @push('head')
        @vite('resources/js/pages/home.js')
    @endpush

    <main>
        {{-- HERO --}}
        <section class="site-hero home-hero relative flex items-center pt-20" style="z-index:30;isolation:isolate">
            <div class="relative z-10 max-w-7xl mx-auto px-5 sm:px-8 py-10 w-full">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <div class="home-hero-copy">
                        <div class="hero-badge inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full text-xs font-semibold uppercase tracking-widest mb-7" style="background:rgba(6,148,162,0.09);border:1.5px solid rgba(6,148,162,0.22);color:#0694a2">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" style="background:#16bdca"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2" style="background:#0694a2"></span>
                            </span>
                            {{ count($tools) }}+ free tools — no account needed
                        </div>

                        <h1 class="hero-title font-sans text-4xl sm:text-5xl lg:text-6xl font-700 tracking-tight leading-[1.03] mb-6">
                            Build Faster.<br>
                            <span class="s-it text-accent">Ship Smarter.</span>
                        </h1>

                        <p class="hero-description text-base sm:text-base leading-relaxed mb-8 max-w-lg" style="color:var(--c-muted)">
                            Professional developer tools designed to simplify workflows, improve productivity, and help creators move faster without compromising privacy.
                        </p>

                        <div class="flex flex-wrap items-center gap-4">
                            <a href="#tools" class="btn-primary px-7 py-3.5 rounded-full text-sm inline-flex items-center gap-2.5 font-700">
                                <i data-lucide="bolt"></i> Explore All Tools
                            </a>
                            <a href="#featured" class="btn-outline px-7 py-3.5 rounded-full text-sm inline-flex items-center gap-2.5 font-semibold">
                                View Featured <i class="text-xs" data-lucide="arrow-right"></i>
                            </a>
                        </div>

                        <div class="home-hero-trust flex flex-wrap items-center gap-6 mt-8 text-sm" style="color:var(--c-muted)">
                            <div class="flex items-center gap-2">
                                <div class="flex -space-x-2">
                                    <div class="w-7 h-7 rounded-full border-2 flex items-center justify-center text-[8.5px] font-bold text-white" style="border-color:var(--c-bg);background:linear-gradient(135deg,#0694a2,#16bdca)" title="SEO tools">SEO</div>
                                    <div class="w-7 h-7 rounded-full border-2 flex items-center justify-center text-[8.5px] font-bold" style="border-color:var(--c-bg);background:linear-gradient(135deg,#0098f0,#0694a2);color:#061c21" title="Web tools">WEB</div>
                                    <div class="w-7 h-7 rounded-full border-2 flex items-center justify-center text-[8.5px] font-bold text-white" style="border-color:var(--c-bg);background:linear-gradient(135deg,#036672,#0694a2)" title="Code tools">CODE</div>
                                </div>
                                <span class="text-xs"><strong style="color:var(--c-text)">{{ count($tools) }}+</strong> tools live</span>
                            </div>
                            <div class="flex items-center gap-1.5 text-xs">
                                <i class="text-xs" style="color:#0098f0" data-lucide="infinity"></i>
                                <strong style="color:var(--c-text)">100% Free</strong> · No signup
                            </div>
                            <div class="text-xs flex items-center gap-1.5"><i class="text-xs" style="color:#16bdca" data-lucide="lock"></i>Privacy-first</div>
                        </div>
                    </div>

                    <div class="hidden lg:block relative h-[540px]">
                        <div class="hero-card-main absolute top-6 left-6 right-6 glass rounded-3xl p-6 shadow-2xl">
                            <div class="absolute top-0 left-8 right-8 h-[2px] rounded-b-full" style="background:linear-gradient(90deg, transparent, #0694a2, #0098f0, transparent)"></div>
                            <div class="flex items-center gap-3 mb-5">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center relative overflow-hidden" style="background:linear-gradient(135deg,#0694a2,#0098f0);box-shadow:0 8px 22px rgba(6,148,162,0.4), inset 0 1px 0 rgba(255,255,255,0.4)">
                                    <i class="text-xs font-bold relative z-10" style="color:#061c21" data-lucide="code"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-700" style="color:var(--c-text)">JSON Formatter</p>
                                    <p class="text-[10px] flex items-center gap-1.5" style="color:var(--c-muted)">
                                        <span class="w-1 h-1 rounded-full animate-pulse" style="background:#0098f0"></span>
                                        Beautify & Validate
                                    </p>
                                </div>
                                <div class="ml-auto flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full" style="background:#ff6b6b"></span>
                                    <span class="w-2.5 h-2.5 rounded-full" style="background:#fbbf24"></span>
                                    <span class="w-2.5 h-2.5 rounded-full" style="background:#0098f0"></span>
                                </div>
                            </div>
                            <div class="rounded-xl p-4 text-[11px] font-mono leading-relaxed relative overflow-hidden" style="background:#0a2f36;border:1px solid rgba(22,189,202,0.25)">
                                <div class="flex items-center gap-2 mb-2 pb-2" style="border-bottom:1px dashed rgba(22,189,202,0.2)">
                                    <span class="text-[9px] font-bold uppercase tracking-wider px-1.5 py-0.5 rounded" style="background:rgba(0, 152, 240,0.15);color:#0098f0">JSON</span>
                                    <span class="text-[9px]" style="color:#76d0d9">devmatrixa.json</span>
                                </div>
                                <div><span style="color:#16bdca">{{ '{' }}</span></div>
                                <div class="pl-4"><span style="color:#7edce2">"name"</span>: <span style="color:#0098f0">"Devmatrixa"</span>,</div>
                                <div class="pl-4"><span style="color:#7edce2">"version"</span>: <span style="color:#daf2f4">"3.0.0"</span>,</div>
                                <div class="pl-4"><span style="color:#7edce2">"tools"</span>: <span style="color:#a78bfa">{{ count($tools) }}</span>,</div>
                                <div class="pl-4"><span style="color:#7edce2">"premium"</span>: <span style="color:#0098f0">true</span></div>
                                <div><span style="color:#16bdca">{{ '}' }}</span></div>
                            </div>
                            <div class="mt-4 flex items-center gap-3">
                                <div class="flex-1 h-1.5 rounded-full overflow-hidden" style="background:var(--c-bg2)">
                                    <div class="h-full rounded-full" style="width:88%;background:linear-gradient(90deg,#0694a2,#16bdca,#0098f0)"></div>
                                </div>
                                <span class="text-[10px] font-bold inline-flex items-center gap-1" style="color:#0098f0">
                                    <i class="text-[10px]" data-lucide="circle-check"></i> Valid
                                </span>
                            </div>
                        </div>

                        <div class="hero-card-mini absolute bottom-16 left-2 glass rounded-2xl p-4 animate-drift" style="animation-delay:2s;width:170px">
                            <div class="flex items-center gap-2 mb-2.5">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center relative overflow-hidden" style="background:linear-gradient(135deg,#16bdca,#0098f0);box-shadow:0 6px 14px rgba(22,189,202,0.35)">
                                    <i class="text-xs relative z-10" style="color:#061c21" data-lucide="minimize"></i>
                                </div>
                                <p class="text-xs font-700" style="color:var(--c-text)">Compressor</p>
                            </div>
                            <p class="text-[10px] mb-1" style="color:var(--c-muted)">2.4MB <i class="text-[8px] mx-0.5" data-lucide="arrow-right"></i> <span style="color:#0098f0;font-weight:700">180KB</span></p>
                            <div class="flex items-center gap-1.5">
                                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded" style="background:rgba(0, 152, 240,0.15);color:#4010b0">−93%</span>
                                <i class="text-[9px]" style="color:#0098f0" data-lucide="trending-down"></i>
                            </div>
                        </div>

                        <div class="hero-card-mini absolute bottom-6 right-2 glass rounded-2xl p-4 animate-drift2" style="animation-delay:1s;width:180px">
                            <div class="flex items-center gap-2 mb-2.5">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center relative overflow-hidden" style="background:linear-gradient(135deg,#0694a2,#16bdca);box-shadow:0 6px 14px rgba(6,148,162,0.35)">
                                    <i class="text-white text-xs relative z-10" data-lucide="key"></i>
                                </div>
                                <p class="text-xs font-700" style="color:var(--c-text)">Password Gen</p>
                            </div>
                            <div class="flex gap-0.5 mb-1.5">
                                <div class="h-1.5 flex-1 rounded-full" style="background:#ff6b6b"></div>
                                <div class="h-1.5 flex-1 rounded-full" style="background:#fbbf24"></div>
                                <div class="h-1.5 flex-1 rounded-full" style="background:#0098f0"></div>
                                <div class="h-1.5 flex-1 rounded-full" style="background:#0098f0"></div>
                            </div>
                            <p class="text-[10px] font-700 inline-flex items-center gap-1" style="color:#0098f0">
                                <i class="text-[9px]" data-lucide="shield-check"></i> Strong
                            </p>
                        </div>

                        <div class="absolute top-0 right-0 glass rounded-full px-3.5 py-2 flex items-center gap-2 animate-drift3" style="box-shadow:0 10px 30px rgba(6,148,162,0.15)">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" style="background:#0098f0"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2" style="background:#0098f0"></span>
                            </span>
                            <span class="text-[10px] font-bold uppercase tracking-wider" style="color:var(--c-text)">238 users live</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <x-tool-ticker :items="[
            ['icon' => 'minimize', 'color' => '#0694a2', 'label' => 'Image Compressor'],
            ['icon' => 'key', 'color' => '#0098f0', 'label' => 'Password Generator'],
            ['icon' => 'code', 'color' => '#16bdca', 'label' => 'JSON Formatter'],
            ['icon' => 'qr-code', 'color' => '#7edce2', 'label' => 'QR Code Generator'],
            ['icon' => 'link', 'color' => '#0694a2', 'label' => 'URL Encoder'],
            ['icon' => 'rotate-cw', 'color' => '#0098f0', 'label' => 'Base64 Converter'],
            ['icon' => 'chart-line', 'color' => '#16bdca', 'label' => 'SEO Analyzer'],
            ['icon' => 'align-left', 'color' => '#7edce2', 'label' => 'Word Counter'],
            ['icon' => 'text', 'color' => '#0098f0', 'label' => 'Typography SEO Checker'],
        ]" />

        {{-- STATS --}}
        <section class="home-stats-section relative px-5 py-12 sm:px-8 sm:py-14">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-5">
                    @foreach ([
                        ['n' => count($tools).'+', 'l' => 'Free Tools', 'c1' => '#0694a2', 'c2' => '#16bdca', 'trend' => '+4 this week'],
                        ['n' => '3', 'l' => 'Categories', 'c1' => '#16bdca', 'c2' => '#0098f0', 'trend' => 'SEO · Code · Web'],
                        ['n' => '100%', 'l' => 'Free Forever', 'c1' => '#0098f0', 'c2' => '#0694a2', 'trend' => 'No signup ever'],
                        ['n' => '0', 'l' => 'Data Collected', 'c1' => '#7edce2', 'c2' => '#16bdca', 'trend' => 'Privacy-first'],
                    ] as $s)
                        <div class="home-stat-card stat-card relative overflow-hidden rounded-2xl p-5 sm:p-6" style="--stat-accent:{{ $s['c1'] }};--stat-accent-secondary:{{ $s['c2'] }}">
                            <div class="home-stat-glow pointer-events-none absolute -right-10 -top-12 h-36 w-36 rounded-full"></div>
                            <div class="relative flex items-center justify-between">
                                <div class="home-stat-icon flex h-11 w-11 items-center justify-center rounded-2xl">
                                    @switch($loop->index)
                                        @case(0)
                                            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                                                <path d="M3 7h18v13H3z" />
                                                <path d="M7 7V5a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2M3 12h18M10 12v2h4v-2" />
                                            </svg>
                                            @break
                                        @case(1)
                                            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                                                <path d="m12 2 9 5-9 5-9-5 9-5Z" />
                                                <path d="m3 12 9 5 9-5M3 17l9 5 9-5" />
                                            </svg>
                                            @break
                                        @case(2)
                                            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                                                <path d="M6 16c5 0 7-8 12-8a4 4 0 0 1 0 8c-5 0-7-8-12-8a4 4 0 0 0 0 8Z" />
                                            </svg>
                                            @break
                                        @default
                                            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                                                <path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11Z" />
                                                <path d="m9 12 2 2 4-4" />
                                            </svg>
                                    @endswitch
                                </div>
                                <span class="home-stat-live inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[9px] font-bold uppercase tracking-wider">
                                    <span class="h-1.5 w-1.5 rounded-full"></span> Live
                                </span>
                            </div>
                            <p class="home-stat-value stat-num mt-6 text-4xl leading-none sm:text-5xl">{{ $s['n'] }}</p>
                            <p class="home-stat-label mt-2 text-[11px] font-bold uppercase tracking-[0.16em]">{{ $s['l'] }}</p>
                            <p class="home-stat-note mt-3 flex items-center gap-2 text-xs font-medium">
                                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="h-3.5 w-3.5 shrink-0">
                                    <path d="m22 7-8.5 8.5-5-5L2 17" />
                                    <path d="M16 7h6v6" />
                                </svg>
                                {{ $s['trend'] }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- TOOLS --}}
        <section id="tools" class="tool-discovery-section py-12 px-5 sm:px-8 relative">
            <div class="max-w-7xl mx-auto relative">
                <div class="tools-section-heading flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-12">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest mb-4" style="background:rgba(6,148,162,0.08);border:1px solid rgba(6,148,162,0.20);color:#0694a2">
                            <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background:#0694a2"></span>
                            Professional Toolkit
                        </div>
                        <h2 class="font-sans text-3xl sm:text-5xl font-700 tracking-tight leading-[1.05]">
                            Everything you need<br><span class="s-it text-accent">in one workspace.</span>
                        </h2>
                        <p class="text-sm sm:text-base mt-3 max-w-xl" style="color:var(--c-muted)">
                            Devmatrixa is built to eliminate unnecessary tools and fragmented workflows. Every utility is designed for speed, simplicity, and real-world use cases.
                        </p>
                    </div>
                    <div class="tools-section-overview" aria-label="Toolkit overview">
                        <div class="tools-overview-stat">
                            <span class="tools-overview-value">{{ count($tools) }}<span>+</span></span>
                            <span class="tools-overview-label">Free tools</span>
                        </div>
                        <span class="tools-overview-divider" aria-hidden="true"></span>
                        <div class="tools-overview-stat">
                            <span class="tools-overview-value">03</span>
                            <span class="tools-overview-label">Categories</span>
                        </div>
                        <span class="tools-overview-divider" aria-hidden="true"></span>
                        <span class="tools-overview-note"><i aria-hidden="true" data-lucide="shield-check"></i> No signup</span>
                    </div>
                </div>

                <div id="tool-browser-app" data-tools="{{ json_encode(array_values($tools)) }}"></div>
            </div>
        </section>

        <x-how-it-works :steps="[
            ['icon' => 'pointer', 'title' => 'Choose a Tool', 'desc' => 'Browse tools by category or search instantly to find the utility you need.'],
            ['icon' => 'wand-sparkles', 'title' => 'Run the Process', 'desc' => 'Paste content, upload files, or enter a URL. Most tools work directly in your browser for faster results.'],
            ['icon' => 'rocket', 'title' => 'Copy and Use', 'desc' => 'Get accurate outputs instantly with quick copy and export options ready for your workflow.'],
        ]">
            <x-slot:subtitle>Simple workflow. <span class="s-it text-accent">Instant results.</span></x-slot:subtitle>
        </x-how-it-works>

        {{-- FEATURED --}}
        <section id="featured" class="featured-section py-14 px-5 sm:px-8 relative overflow-hidden">
            <div class="absolute inset-0 pointer-events-none opacity-60">
                <div class="orb w-[500px] h-[500px] opacity-20" style="background:radial-gradient(circle,#0098f0,transparent 65%);top:10%;left:-10%"></div>
                <div class="orb w-[400px] h-[400px] opacity-15" style="background:radial-gradient(circle,#0694a2,transparent 65%);bottom:0%;right:-8%"></div>
            </div>
            <div class="max-w-7xl mx-auto relative">
                <div class="text-center mb-10">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest mb-4" style="background:rgba(0, 152, 240,0.10);border:1px solid rgba(0, 152, 240,0.30);color:#4010b0">
                        <i class="text-[9px]" data-lucide="star"></i> Editor's Picks
                    </div>
                    <h2 class="font-sans text-3xl sm:text-5xl font-700 tracking-tight leading-tight">
                        Trusted by <span class="s-it text-accent">power users</span>
                    </h2>
                    <p class="text-sm sm:text-base mt-3 max-w-lg mx-auto" style="color:var(--c-muted)">
                        The tools our team reaches for every single day — handpicked, refined, ready to ship.
                    </p>
                </div>

                @php
                    $spotlight = $featured->first();
                    $featuredPicks = $featured->slice(1);
                @endphp

                @if ($spotlight)
                    @php
                        $spotlightIconColor = ($spotlight['g1'] === '#0098f0' || $spotlight['g2'] === '#0098f0') ? '#061c21' : '#fff';
                    @endphp
                    <div class="featured-showcase">
                        <a href="{{ url($spotlight['url']) }}" class="featured-spotlight group" style="--featured-accent:{{ $spotlight['g1'] }};--featured-accent-secondary:{{ $spotlight['g2'] }}">
                            <div class="featured-spotlight-top">
                                <span class="featured-spotlight-kicker"><i aria-hidden="true" data-lucide="star"></i> Spotlight tool</span>
                                @if (!empty($spotlight['badge']))
                                    <span class="featured-spotlight-badge">{{ $spotlight['badge'] }}</span>
                                @endif
                            </div>
                            <span class="featured-spotlight-number" aria-hidden="true">01</span>
                            <div class="featured-spotlight-icon">
                                <i style="color:{{ $spotlightIconColor }}" aria-hidden="true" data-lucide="{{ $spotlight['icon'] }}"></i>
                            </div>
                            <span class="featured-spotlight-category">{{ ucfirst($spotlight['cat']) }} toolkit</span>
                            <h3 class="featured-spotlight-title">{{ $spotlight['name'] }}</h3>
                            <p class="featured-spotlight-description">{{ $spotlight['desc'] }}</p>
                            <span class="featured-spotlight-action">
                                Open tool <i aria-hidden="true" data-lucide="arrow-right"></i>
                            </span>
                        </a>

                        <div class="featured-picks" aria-label="More editor's picks">
                            @foreach ($featuredPicks as $t)
                                @php
                                    $num = str_pad($loop->iteration + 1, 2, '0', STR_PAD_LEFT);
                                @endphp
                                <a href="{{ url($t['url']) }}" class="featured-pick group" style="--featured-accent:{{ $t['g1'] }};--featured-accent-secondary:{{ $t['g2'] }}">
                                    <span class="featured-pick-number">{{ $num }}</span>
                                    <span class="featured-pick-icon">
                                        <i aria-hidden="true" data-lucide="{{ $t['icon'] }}"></i>
                                    </span>
                                    <span class="featured-pick-content">
                                        <span class="featured-pick-meta">
                                            <span class="featured-pick-category">{{ ucfirst($t['cat']) }}</span>
                                            @if (!empty($t['badge']))
                                                <span class="featured-pick-badge">{{ $t['badge'] }}</span>
                                            @endif
                                        </span>
                                        <span class="featured-pick-title">{{ $t['name'] }}</span>
                                        <span class="featured-pick-description">{{ $t['desc'] }}</span>
                                    </span>
                                    <span class="featured-pick-arrow"><i aria-hidden="true" data-lucide="arrow-right"></i></span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </section>

        {{-- WHY DEVMATRIXA --}}
        <section id="why" class="why-section relative overflow-hidden px-5 py-16 sm:px-8 sm:py-20">
            <div class="why-section-glow pointer-events-none absolute inset-0"></div>
            <div class="why-layout relative mx-auto max-w-7xl">
                <div class="why-section-heading">
                    <div class="why-section-badge mb-5 inline-flex items-center gap-2 rounded-full px-3.5 py-1.5 text-[10px] font-bold uppercase tracking-[0.16em]">
                        <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="h-3.5 w-3.5">
                            <path d="m12 3 1.9 5.8L20 11l-6.1 2.2L12 19l-1.9-5.8L4 11l6.1-2.2L12 3Z" />
                            <path d="m19 14 1.2 2.8L23 18l-2.8 1.2L19 22l-1.2-2.8L15 18l2.8-1.2L19 14Z" />
                        </svg>
                        THE DEVMATRIXA DIFFERENCE
                    </div>
                    <h2 class="font-sans text-3xl sm:text-5xl font-700 tracking-tight leading-tight">
                        Built for people who <span class="s-it text-accent">build.</span>
                    </h2>
                    <p class="mt-4 max-w-xl text-sm leading-relaxed sm:text-base" style="color:var(--c-muted)">
                        Most online utility websites feel outdated, overloaded, and difficult to trust. Devmatrixa is designed differently with a focus on performance, privacy, and usability.
                    </p>
                    <div class="why-proof">
                        <span class="why-proof-mark"><i aria-hidden="true" data-lucide="check"></i></span>
                        <span>{{ count($tools) }}+ tools</span>
                        <span class="why-proof-divider" aria-hidden="true"></span>
                        <span>No signup required</span>
                    </div>
                </div>

                @php
                    $whyItems = [
                        ['icon' => 'shield', 'title' => 'Privacy First', 'desc' => 'Your data stays yours. Most tools process information directly in your browser without unnecessary tracking or data collection.', 'g1' => '#0694a2', 'g2' => '#16bdca'],
                        ['icon' => 'zap', 'title' => 'Fast Performance', 'desc' => 'Optimized architecture and lightweight design ensure fast loading times and smooth interactions.', 'g1' => '#16bdca', 'g2' => '#0098f0'],
                        ['icon' => 'infinity', 'title' => 'No Signup Required', 'desc' => 'Use every tool freely without creating accounts, subscriptions, or unnecessary restrictions.', 'g1' => '#0098f0', 'g2' => '#0694a2'],
                        ['icon' => 'code', 'title' => 'Built by Developers', 'desc' => 'Every utility is created to solve practical problems faced by developers, marketers, and creators.', 'g1' => '#7edce2', 'g2' => '#0694a2'],
                        ['icon' => 'globe', 'title' => 'Real-World Functionality', 'desc' => 'Tools are tested on live websites and production workflows for reliable results.', 'g1' => '#0694a2', 'g2' => '#7edce2'],
                        ['icon' => 'sparkles', 'title' => 'Modern Experience', 'desc' => 'Responsive layouts, dark mode support, and polished interfaces create a smooth experience across devices.', 'g1' => '#16bdca', 'g2' => '#0098f0'],
                    ];
                @endphp

                <div class="why-grid">
                    @foreach ($whyItems as $f)
                        <article class="why-point" style="--why-accent:{{ $f['g1'] }};--why-accent-secondary:{{ $f['g2'] }}">
                            <div class="why-point-top">
                                <span class="why-point-index">0{{ $loop->iteration }}</span>
                                <div class="why-icon flex h-11 w-11 items-center justify-center rounded-xl">
                                    @switch($f['icon'])
                                        @case('shield')
                                            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11Z" /><path d="m9 12 2 2 4-4" /></svg>
                                            @break
                                        @case('zap')
                                            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="m13 2-3 8h8L9 22l3-9H4l9-11Z" /></svg>
                                            @break
                                        @case('infinity')
                                            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="M6 16c5 0 7-8 12-8a4 4 0 0 1 0 8c-5 0-7-8-12-8a4 4 0 0 0 0 8Z" /></svg>
                                            @break
                                        @case('code')
                                            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="m16 18 6-6-6-6M8 6l-6 6 6 6m6-14-4 20" /></svg>
                                            @break
                                        @case('globe')
                                            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><circle cx="12" cy="12" r="10" /><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10Z" /></svg>
                                            @break
                                        @default
                                            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="m12 3 1.9 5.8L20 11l-6.1 2.2L12 19l-1.9-5.8L4 11l6.1-2.2L12 3Z" /><path d="m19 14 1.2 2.8L23 18l-2.8 1.2L19 22l-1.2-2.8L15 18l2.8-1.2L19 14Z" /></svg>
                                    @endswitch
                                </div>
                            </div>
                            <div class="why-point-copy">
                                <h3 class="why-card-title font-sans text-base font-700 tracking-tight">{{ $f['title'] }}</h3>
                                <p class="why-card-description mt-2 text-sm leading-relaxed">{{ $f['desc'] }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    </main>
</x-layout>
