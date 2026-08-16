<x-layout>
    @push('head')
        @vite('resources/js/pages/home.js')
    @endpush

    <main>
        {{-- HERO --}}
        <section class="relative min-h-screen flex items-center pt-20 dot-bg" style="z-index:30;isolation:isolate">
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="orb w-[700px] h-[700px] animate-drift opacity-25 dark:opacity-18" style="background:radial-gradient(circle,#0694a2,transparent 65%);top:-20%;right:-18%"></div>
                <div class="orb w-[500px] h-[500px] animate-drift2 opacity-15 dark:opacity-10" style="background:radial-gradient(circle,#a3e635,transparent 65%);bottom:0;left:-15%"></div>
                <div class="orb w-[320px] h-[320px] animate-drift3 opacity-10" style="background:radial-gradient(circle,#16bdca,transparent 65%);top:40%;left:28%"></div>
                <div class="ring-deco absolute w-[600px] h-[600px] opacity-25 dark:opacity-20 animate-spin-lazy" style="top:-200px;right:-200px"></div>
                <div class="ring-deco absolute w-[380px] h-[380px] opacity-[0.18] dark:opacity-[0.12]" style="bottom:-130px;left:-80px;animation:spin 55s linear infinite reverse"></div>
            </div>

            <div class="relative z-10 max-w-7xl mx-auto px-5 sm:px-8 py-16 w-full">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full text-xs font-semibold uppercase tracking-widest mb-7" style="background:rgba(6,148,162,0.09);border:1.5px solid rgba(6,148,162,0.22);color:#0694a2">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" style="background:#16bdca"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2" style="background:#0694a2"></span>
                            </span>
                            {{ count($tools) }}+ free tools — no account needed
                        </div>

                        <h1 class="font-sans text-4xl sm:text-5xl lg:text-6xl font-700 tracking-tight leading-[1.03] mb-6">
                            Build Faster.<br>
                            <span class="s-it text-accent">Ship Smarter.</span>
                        </h1>

                        <p class="text-base sm:text-base leading-relaxed mb-8 max-w-lg" style="color:var(--c-muted)">
                            Professional developer tools designed to simplify workflows, improve productivity, and help creators move faster without compromising privacy.
                        </p>

                        <div id="hero-search-app"></div>

                        <div class="flex flex-wrap items-center gap-4">
                            <a href="#tools" class="btn-primary px-7 py-3.5 rounded-full text-sm inline-flex items-center gap-2.5 font-700">
                                <i class="fa-solid fa-bolt" style="color:#061c21"></i> Explore All Tools
                            </a>
                            <a href="#featured" class="btn-outline px-7 py-3.5 rounded-full text-sm inline-flex items-center gap-2.5 font-semibold">
                                View Featured <i class="fa-solid fa-arrow-right text-xs" style="color:var(--c-muted)"></i>
                            </a>
                        </div>

                        <div class="flex flex-wrap items-center gap-6 mt-8 text-sm" style="color:var(--c-muted)">
                            <div class="flex items-center gap-2">
                                <div class="flex -space-x-2">
                                    <div class="w-7 h-7 rounded-full border-2 flex items-center justify-center text-[8.5px] font-bold text-white" style="border-color:var(--c-bg);background:linear-gradient(135deg,#0694a2,#16bdca)" title="SEO tools">SEO</div>
                                    <div class="w-7 h-7 rounded-full border-2 flex items-center justify-center text-[8.5px] font-bold" style="border-color:var(--c-bg);background:linear-gradient(135deg,#a3e635,#0694a2);color:#061c21" title="Web tools">WEB</div>
                                    <div class="w-7 h-7 rounded-full border-2 flex items-center justify-center text-[8.5px] font-bold text-white" style="border-color:var(--c-bg);background:linear-gradient(135deg,#036672,#0694a2)" title="Code tools">CODE</div>
                                </div>
                                <span class="text-xs"><strong style="color:var(--c-text)">{{ count($tools) }}+</strong> tools live</span>
                            </div>
                            <div class="flex items-center gap-1.5 text-xs">
                                <i class="fa-solid fa-infinity text-xs" style="color:#a3e635"></i>
                                <strong style="color:var(--c-text)">100% Free</strong> · No signup
                            </div>
                            <div class="text-xs flex items-center gap-1.5"><i class="fa-solid fa-lock text-xs" style="color:#16bdca"></i>Privacy-first</div>
                        </div>
                    </div>

                    <div class="hidden lg:block relative h-[540px]">
                        <div class="hero-card-main absolute top-6 left-6 right-6 glass rounded-3xl p-6 shadow-2xl">
                            <div class="absolute top-0 left-8 right-8 h-[2px] rounded-b-full" style="background:linear-gradient(90deg, transparent, #0694a2, #a3e635, transparent)"></div>
                            <div class="flex items-center gap-3 mb-5">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center relative overflow-hidden" style="background:linear-gradient(135deg,#0694a2,#a3e635);box-shadow:0 8px 22px rgba(6,148,162,0.4), inset 0 1px 0 rgba(255,255,255,0.4)">
                                    <i class="fa-solid fa-code text-xs font-bold relative z-10" style="color:#061c21"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-700" style="color:var(--c-text)">JSON Formatter</p>
                                    <p class="text-[10px] flex items-center gap-1.5" style="color:var(--c-muted)">
                                        <span class="w-1 h-1 rounded-full animate-pulse" style="background:#a3e635"></span>
                                        Beautify & Validate
                                    </p>
                                </div>
                                <div class="ml-auto flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full" style="background:#ff6b6b"></span>
                                    <span class="w-2.5 h-2.5 rounded-full" style="background:#fbbf24"></span>
                                    <span class="w-2.5 h-2.5 rounded-full" style="background:#a3e635"></span>
                                </div>
                            </div>
                            <div class="rounded-xl p-4 text-[11px] font-mono leading-relaxed relative overflow-hidden" style="background:#0a2f36;border:1px solid rgba(22,189,202,0.25)">
                                <div class="flex items-center gap-2 mb-2 pb-2" style="border-bottom:1px dashed rgba(22,189,202,0.2)">
                                    <span class="text-[9px] font-bold uppercase tracking-wider px-1.5 py-0.5 rounded" style="background:rgba(163,230,53,0.15);color:#a3e635">JSON</span>
                                    <span class="text-[9px]" style="color:#76d0d9">devmatrixa.json</span>
                                </div>
                                <div><span style="color:#16bdca">{{ '{' }}</span></div>
                                <div class="pl-4"><span style="color:#7edce2">"name"</span>: <span style="color:#a3e635">"Devmatrixa"</span>,</div>
                                <div class="pl-4"><span style="color:#7edce2">"version"</span>: <span style="color:#daf2f4">"3.0.0"</span>,</div>
                                <div class="pl-4"><span style="color:#7edce2">"tools"</span>: <span style="color:#bef264">{{ count($tools) }}</span>,</div>
                                <div class="pl-4"><span style="color:#7edce2">"premium"</span>: <span style="color:#a3e635">true</span></div>
                                <div><span style="color:#16bdca">{{ '}' }}</span></div>
                            </div>
                            <div class="mt-4 flex items-center gap-3">
                                <div class="flex-1 h-1.5 rounded-full overflow-hidden" style="background:var(--c-bg2)">
                                    <div class="h-full rounded-full" style="width:88%;background:linear-gradient(90deg,#0694a2,#16bdca,#a3e635)"></div>
                                </div>
                                <span class="text-[10px] font-bold inline-flex items-center gap-1" style="color:#a3e635">
                                    <i class="fa-solid fa-circle-check text-[10px]"></i> Valid
                                </span>
                            </div>
                        </div>

                        <div class="hero-card-mini absolute bottom-16 left-2 glass rounded-2xl p-4 animate-drift" style="animation-delay:2s;width:170px">
                            <div class="flex items-center gap-2 mb-2.5">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center relative overflow-hidden" style="background:linear-gradient(135deg,#16bdca,#a3e635);box-shadow:0 6px 14px rgba(22,189,202,0.35)">
                                    <i class="fa-solid fa-compress text-xs relative z-10" style="color:#061c21"></i>
                                </div>
                                <p class="text-xs font-700" style="color:var(--c-text)">Compressor</p>
                            </div>
                            <p class="text-[10px] mb-1" style="color:var(--c-muted)">2.4MB <i class="fa-solid fa-arrow-right text-[8px] mx-0.5"></i> <span style="color:#a3e635;font-weight:700">180KB</span></p>
                            <div class="flex items-center gap-1.5">
                                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded" style="background:rgba(163,230,53,0.15);color:#65a30d">−93%</span>
                                <i class="fa-solid fa-arrow-trend-down text-[9px]" style="color:#a3e635"></i>
                            </div>
                        </div>

                        <div class="hero-card-mini absolute bottom-6 right-2 glass rounded-2xl p-4 animate-drift2" style="animation-delay:1s;width:180px">
                            <div class="flex items-center gap-2 mb-2.5">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center relative overflow-hidden" style="background:linear-gradient(135deg,#0694a2,#16bdca);box-shadow:0 6px 14px rgba(6,148,162,0.35)">
                                    <i class="fa-solid fa-key text-white text-xs relative z-10"></i>
                                </div>
                                <p class="text-xs font-700" style="color:var(--c-text)">Password Gen</p>
                            </div>
                            <div class="flex gap-0.5 mb-1.5">
                                <div class="h-1.5 flex-1 rounded-full" style="background:#ff6b6b"></div>
                                <div class="h-1.5 flex-1 rounded-full" style="background:#fbbf24"></div>
                                <div class="h-1.5 flex-1 rounded-full" style="background:#a3e635"></div>
                                <div class="h-1.5 flex-1 rounded-full" style="background:#a3e635"></div>
                            </div>
                            <p class="text-[10px] font-700 inline-flex items-center gap-1" style="color:#a3e635">
                                <i class="fa-solid fa-shield-halved text-[9px]"></i> Strong
                            </p>
                        </div>

                        <div class="absolute top-0 right-0 glass rounded-full px-3.5 py-2 flex items-center gap-2 animate-drift3" style="box-shadow:0 10px 30px rgba(6,148,162,0.15)">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" style="background:#a3e635"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2" style="background:#a3e635"></span>
                            </span>
                            <span class="text-[10px] font-bold uppercase tracking-wider" style="color:var(--c-text)">238 users live</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <x-tool-ticker :items="[
            ['icon' => 'fa-solid fa-compress', 'color' => '#0694a2', 'label' => 'Image Compressor'],
            ['icon' => 'fa-solid fa-key', 'color' => '#a3e635', 'label' => 'Password Generator'],
            ['icon' => 'fa-solid fa-code', 'color' => '#16bdca', 'label' => 'JSON Formatter'],
            ['icon' => 'fa-solid fa-qrcode', 'color' => '#7edce2', 'label' => 'QR Code Generator'],
            ['icon' => 'fa-solid fa-link', 'color' => '#0694a2', 'label' => 'URL Encoder'],
            ['icon' => 'fa-solid fa-rotate', 'color' => '#a3e635', 'label' => 'Base64 Converter'],
            ['icon' => 'fa-solid fa-chart-line', 'color' => '#16bdca', 'label' => 'SEO Analyzer'],
            ['icon' => 'fa-solid fa-align-left', 'color' => '#7edce2', 'label' => 'Word Counter'],
            ['icon' => 'fa-solid fa-text-height', 'color' => '#a3e635', 'label' => 'Typography SEO Checker'],
        ]" />

        {{-- STATS --}}
        <section class="py-12 px-5 sm:px-8 relative">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-5">
                    @foreach ([
                        ['n' => count($tools).'+', 'l' => 'Free Tools', 'icon' => 'fa-toolbox', 'c1' => '#0694a2', 'c2' => '#16bdca', 'trend' => '+4 this week'],
                        ['n' => '3', 'l' => 'Categories', 'icon' => 'fa-layer-group', 'c1' => '#16bdca', 'c2' => '#a3e635', 'trend' => 'SEO · Code · Web'],
                        ['n' => '100%', 'l' => 'Free Forever', 'icon' => 'fa-infinity', 'c1' => '#a3e635', 'c2' => '#0694a2', 'trend' => 'No signup ever'],
                        ['n' => '0', 'l' => 'Data Collected', 'icon' => 'fa-shield-halved', 'c1' => '#7edce2', 'c2' => '#16bdca', 'trend' => 'Privacy-first'],
                    ] as $s)
                        <div class="stat-card rounded-2xl p-6 relative overflow-hidden">
                            <div class="stat-glow" style="background:radial-gradient(circle at top right, {{ $s['c1'] }}22, transparent 60%)"></div>
                            <div class="absolute top-0 left-6 right-6 h-[2px] rounded-b-full" style="background:linear-gradient(90deg, transparent, {{ $s['c1'] }}, {{ $s['c2'] }}, transparent)"></div>
                            <div class="relative flex items-start justify-between mb-4">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:linear-gradient(135deg, {{ $s['c1'] }}, {{ $s['c2'] }})">
                                    <i class="fa-solid {{ $s['icon'] }} text-sm" style="color:{{ ($s['c2'] === '#a3e635' || $s['c1'] === '#a3e635') ? '#061c21' : '#fff' }}"></i>
                                </div>
                                <span class="text-[9px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md" style="background:rgba(163, 230, 53, 0.12);border:1px solid rgba(163, 230, 53, 0.25);color:#65a30d">Live</span>
                            </div>
                            <p class="stat-num text-4xl sm:text-5xl mb-1 text-accent leading-none">{{ $s['n'] }}</p>
                            <p class="text-[11px] uppercase tracking-widest font-bold mt-2">{{ $s['l'] }}</p>
                            <p class="text-[10.5px] mt-1.5 font-medium flex items-center gap-1" style="color:var(--c-muted)">
                                <i class="fa-solid fa-arrow-trend-up text-[9px]" style="color:#a3e635"></i>
                                {{ $s['trend'] }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- TOOLS --}}
        <section id="tools" class="py-12 px-5 sm:px-8 relative">
            <div class="max-w-7xl mx-auto relative">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-12">
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
                </div>

                <div id="tool-browser-app" data-tools="{{ json_encode(array_values($tools)) }}"></div>
            </div>
        </section>

        <x-how-it-works :steps="[
            ['icon' => 'fa-solid fa-arrow-pointer', 'title' => 'Choose a Tool', 'desc' => 'Browse tools by category or search instantly to find the utility you need.'],
            ['icon' => 'fa-solid fa-wand-magic-sparkles', 'title' => 'Run the Process', 'desc' => 'Paste content, upload files, or enter a URL. Most tools work directly in your browser for faster results.'],
            ['icon' => 'fa-solid fa-rocket', 'title' => 'Copy and Use', 'desc' => 'Get accurate outputs instantly with quick copy and export options ready for your workflow.'],
        ]">
            <x-slot:subtitle>Simple workflow. <span class="s-it text-accent">Instant results.</span></x-slot:subtitle>
        </x-how-it-works>

        {{-- FEATURED --}}
        <section id="featured" class="py-12 px-5 sm:px-8 relative overflow-hidden">
            <div class="absolute inset-0 pointer-events-none opacity-60">
                <div class="orb w-[500px] h-[500px] opacity-20" style="background:radial-gradient(circle,#a3e635,transparent 65%);top:10%;left:-10%"></div>
                <div class="orb w-[400px] h-[400px] opacity-15" style="background:radial-gradient(circle,#0694a2,transparent 65%);bottom:0%;right:-8%"></div>
            </div>
            <div class="max-w-7xl mx-auto relative">
                <div class="text-center mb-10">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest mb-4" style="background:rgba(163,230,53,0.10);border:1px solid rgba(163,230,53,0.30);color:#65a30d">
                        <i class="fa-solid fa-star text-[9px]"></i> Editor's Picks
                    </div>
                    <h2 class="font-sans text-3xl sm:text-5xl font-700 tracking-tight leading-tight">
                        Trusted by <span class="s-it text-accent">power users</span>
                    </h2>
                    <p class="text-sm sm:text-base mt-3 max-w-lg mx-auto" style="color:var(--c-muted)">
                        The tools our team reaches for every single day — handpicked, refined, ready to ship.
                    </p>
                </div>

                <div class="relative rounded-[28px] overflow-hidden grid grid-cols-1 md:grid-cols-2" style="border:1px solid var(--c-border);background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.005))">
                    @foreach ($featured as $idx => $t)
                        @php
                            $iconColor = ($t['g1'] === '#a3e635' || $t['g2'] === '#a3e635') ? '#061c21' : '#fff';
                            $num = str_pad($idx + 1, 2, '0', STR_PAD_LEFT);
                            $isLastRow = $idx >= count($featured) - 2;
                            $isRight = $idx % 2 === 1;
                        @endphp
                        <a href="{{ url($t['url']) }}" class="group relative flex items-center gap-4 px-5 sm:px-6 py-6 transition-all min-w-0" style="border-bottom:{{ $isLastRow ? 'none' : '1px dashed var(--c-border)' }};border-left:{{ $isRight ? '1px dashed var(--c-border)' : 'none' }}">
                            <div class="relative shrink-0 self-stretch flex items-start">
                                <span class="font-sans font-700 leading-none text-[56px] sm:text-[64px] tracking-tighter" style="background:linear-gradient(135deg, {{ $t['g1'] }}, {{ $t['g2'] }});-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent">{{ $num }}</span>
                            </div>
                            <div class="relative shrink-0">
                                <div class="rounded-xl flex items-center justify-center relative" style="width:46px;height:46px;background:linear-gradient(135deg,{{ $t['g1'] }},{{ $t['g2'] }})">
                                    <i class="{{ $t['icon'] }} relative z-10" style="color:{{ $iconColor }};font-size:16px"></i>
                                </div>
                            </div>
                            <div class="relative flex-1 min-w-0">
                                <div class="flex items-center gap-1.5 mb-1 flex-wrap">
                                    <span class="tc-cat text-[9px] font-semibold uppercase tracking-wider px-1.5 py-0.5 rounded inline-flex items-center gap-1">
                                        <span class="w-1 h-1 rounded-full" style="background:currentColor"></span>
                                        {{ ucfirst($t['cat']) }}
                                    </span>
                                    @if (!empty($t['badge']))
                                        <span class="text-[8.5px] font-bold px-1.5 py-0.5 rounded uppercase tracking-wider" style="background:linear-gradient(135deg,{{ $t['g1'] }},{{ $t['g2'] }});color:#061c21">{{ $t['badge'] }}</span>
                                    @endif
                                </div>
                                <h3 class="font-sans font-700 tracking-tight leading-tight text-base sm:text-[17px] mb-1 truncate">{{ $t['name'] }}</h3>
                                <p class="leading-snug text-[12px] line-clamp-2" style="color:var(--c-muted)">{{ $t['desc'] }}</p>
                            </div>
                            <div class="relative shrink-0">
                                <span class="inline-flex items-center justify-center w-9 h-9 rounded-full transition-all group-hover:scale-110" style="background:linear-gradient(135deg,{{ $t['g1'] }},{{ $t['g2'] }});color:#061c21">
                                    <i class="fa-solid fa-arrow-right text-[12px]"></i>
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- WHY DEVMATRIXA --}}
        <section id="why" class="py-12 px-5 sm:px-8 relative">
            <div class="max-w-7xl mx-auto relative">
                <div class="text-center mb-10">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest mb-4" style="background:rgba(163,230,53,0.10);border:1px solid rgba(163,230,53,0.28);color:#65a30d">
                        <i class="fa-solid fa-sparkles text-[11px]"></i> Why Devmatrixa
                    </div>
                    <h2 class="font-sans text-3xl sm:text-5xl font-700 tracking-tight leading-tight">
                        Built for <span class="s-it text-accent">modern creators.</span>
                    </h2>
                    <p class="text-sm sm:text-base mt-3 max-w-xl mx-auto" style="color:var(--c-muted)">
                        Most online utility websites feel outdated, overloaded, and difficult to trust. Devmatrixa is designed differently with a focus on performance, privacy, and usability.
                    </p>
                </div>

                @php
                    $whyItems = [
                        ['icon' => 'fa-solid fa-shield-halved', 'title' => 'Privacy First', 'desc' => 'Your data stays yours. Most tools process information directly in your browser without unnecessary tracking or data collection.', 'g1' => '#0694a2', 'g2' => '#16bdca', 'wide' => true],
                        ['icon' => 'fa-solid fa-bolt', 'title' => 'Fast Performance', 'desc' => 'Optimized architecture and lightweight design ensure fast loading times and smooth interactions.', 'g1' => '#16bdca', 'g2' => '#a3e635', 'wide' => false],
                        ['icon' => 'fa-solid fa-infinity', 'title' => 'No Signup Required', 'desc' => 'Use every tool freely without creating accounts, subscriptions, or unnecessary restrictions.', 'g1' => '#a3e635', 'g2' => '#0694a2', 'wide' => false],
                        ['icon' => 'fa-solid fa-code', 'title' => 'Built by Developers', 'desc' => 'Every utility is created to solve practical problems faced by developers, marketers, and creators.', 'g1' => '#7edce2', 'g2' => '#0694a2', 'wide' => true],
                        ['icon' => 'fa-solid fa-earth-americas', 'title' => 'Real-World Functionality', 'desc' => 'Tools are tested on live websites and production workflows for reliable results.', 'g1' => '#0694a2', 'g2' => '#7edce2', 'wide' => true],
                        ['icon' => 'fa-solid fa-sparkles', 'title' => 'Modern Experience', 'desc' => 'Responsive layouts, dark mode support, and polished interfaces create a smooth experience across devices.', 'g1' => '#16bdca', 'g2' => '#a3e635', 'wide' => false],
                    ];
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5">
                    @foreach ($whyItems as $f)
                        @php $iconColor = $f['g2'] === '#a3e635' ? '#061c21' : '#fff'; @endphp
                        <div class="group relative rounded-3xl p-6 sm:p-7 overflow-hidden transition-all {{ $f['wide'] ? 'md:col-span-2' : 'md:col-span-1' }}" style="background:linear-gradient(135deg, {{ $f['g1'] }}10, {{ $f['g2'] }}05 60%, transparent), rgba(255,255,255,0.015);border:1px solid var(--c-border)">
                            <span class="pointer-events-none absolute -top-16 -right-16 w-56 h-56 rounded-full opacity-25 group-hover:opacity-40 transition-opacity" style="background:radial-gradient(circle, {{ $f['g2'] }}, transparent 65%)"></span>
                            <div class="relative">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-4 transition-transform duration-500 group-hover:scale-110 group-hover:-rotate-6" style="background:linear-gradient(135deg, {{ $f['g1'] }}, {{ $f['g2'] }})">
                                    <i class="{{ $f['icon'] }} relative z-10" style="color:{{ $iconColor }};font-size:22px"></i>
                                </div>
                                <h3 class="font-sans font-700 tracking-tight text-xl sm:text-2xl mb-1.5">{{ $f['title'] }}</h3>
                                <p class="leading-relaxed text-sm" style="color:var(--c-muted)">{{ $f['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </main>
</x-layout>
