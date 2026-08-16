@props(['steps', 'stats' => []])
@php
    $gradients = [['#0694a2', '#16bdca'], ['#16bdca', '#a3e635'], ['#a3e635', '#0694a2']];
@endphp

<section id="how-it-works" class="py-20 px-5 sm:px-8 dot-bg relative overflow-hidden">
    <div class="absolute inset-0 pointer-events-none opacity-50">
        <div class="orb w-[450px] h-[450px] opacity-20" style="background:radial-gradient(circle,#0694a2,transparent 65%);top:10%;left:-8%"></div>
        <div class="orb w-[350px] h-[350px] opacity-15" style="background:radial-gradient(circle,#a3e635,transparent 65%);bottom:0%;right:-5%"></div>
    </div>

    <div class="max-w-7xl mx-auto relative">
        <div class="text-center mb-14">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest mb-4" style="background:rgba(6,148,162,0.08);border:1px solid rgba(6,148,162,0.20);color:#0694a2">
                <span style="font-size:11px">&#9671;</span> How It Works
            </div>
            <h2 class="font-sans text-3xl sm:text-5xl font-700 tracking-tight leading-tight">{!! $subtitle !!}</h2>
        </div>

        <div class="relative">
            <div class="hidden md:block absolute top-[44px] left-[16%] right-[16%] h-px pointer-events-none" style="background:repeating-linear-gradient(90deg, var(--c-border) 0 6px, transparent 6px 12px)"></div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 md:gap-6 relative">
                @foreach ($steps as $i => $s)
                    @php
                        $g1 = $s['g1'] ?? $gradients[$i][0];
                        $g2 = $s['g2'] ?? $gradients[$i][1];
                        $n = str_pad($i + 1, 2, '0', STR_PAD_LEFT);
                        $iconColor = $g2 === '#a3e635' ? '#061c21' : '#fff';
                    @endphp
                    <div class="hiw-step group relative text-center">
                        <div class="relative inline-flex mb-5">
                            <div class="relative w-[88px] h-[88px] rounded-full flex items-center justify-center transition-transform duration-500 group-hover:scale-105" style="background:var(--c-bg)">
                                <span class="absolute inset-0 rounded-full" style="background:linear-gradient(135deg, {{ $g1 }}, {{ $g2 }});padding:2px;-webkit-mask:linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);-webkit-mask-composite:xor;mask-composite:exclude"></span>
                                <span class="font-sans font-700 text-3xl tracking-tighter" style="background:linear-gradient(135deg, {{ $g1 }}, {{ $g2 }});-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent">{{ $n }}</span>
                                <span class="absolute inset-[-6px] rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500" style="border:1px solid {{ $g1 }}55;animation:ping 2.4s cubic-bezier(0,0,0.2,1) infinite"></span>
                            </div>
                            <div class="absolute -bottom-1 -right-2 w-10 h-10 rounded-xl flex items-center justify-center transition-transform duration-500 group-hover:rotate-6" style="background:linear-gradient(135deg,{{ $g1 }},{{ $g2 }});box-shadow:0 10px 24px {{ $g1 }}55, inset 0 1px 0 rgba(255,255,255,0.4)">
                                <i class="{{ $s['icon'] }} text-sm" style="color:{{ $iconColor }}"></i>
                            </div>
                        </div>

                        <h3 class="font-sans text-lg sm:text-xl font-700 tracking-tight mb-2">{{ $s['title'] }}</h3>
                        <p class="text-[13.5px] leading-relaxed max-w-xs mx-auto" style="color:var(--c-muted)">{{ $s['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        @if (count($stats) > 0)
            <div class="mt-14 grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach ($stats as $i => $s)
                    @php
                        $c1 = $gradients[$i % 3][0];
                        $c2 = $gradients[$i % 3][1];
                    @endphp
                    <div class="stat-card rounded-2xl px-5 py-5 relative overflow-hidden">
                        <div class="stat-glow" style="background:radial-gradient(circle at top right, {{ $c1 }}22, transparent 60%)"></div>
                        <div class="absolute top-0 left-6 right-6 h-[2px] rounded-b-full" style="background:linear-gradient(90deg, transparent, {{ $c1 }}, {{ $c2 }}, transparent)"></div>
                        <div class="relative flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0" style="background:linear-gradient(135deg, {{ $c1 }}, {{ $c2 }});box-shadow:0 6px 16px {{ $c1 }}40, inset 0 1px 0 rgba(255,255,255,0.35)">
                                @if (!empty($s['icon']))
                                    <i class="{{ $s['icon'] }} text-sm" style="color:{{ ($c2 === '#a3e635' || $c1 === '#a3e635') ? '#061c21' : '#fff' }}"></i>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="font-sans text-2xl font-700 text-accent leading-none">{{ $s['value'] }}</p>
                                <p class="text-[10px] uppercase tracking-widest font-bold mt-1 truncate" style="color:var(--c-muted)">{{ $s['label'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
