@props(['badge' => 'Why this tool', 'description' => null, 'features'])
@php
    $gradients = [['#0694a2', '#16bdca'], ['#16bdca', '#a3e635'], ['#a3e635', '#0694a2'], ['#7edce2', '#0694a2'], ['#0694a2', '#7edce2'], ['#a3e635', '#16bdca']];
@endphp

<section class="py-12 px-5 sm:px-8 relative">
    <div class="max-w-7xl mx-auto relative">
        <div class="text-center mb-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest mb-4" style="background:rgba(6,148,162,0.08);border:1px solid rgba(6,148,162,0.20);color:#0694a2">
                <i class="fa-solid fa-sparkles text-[11px]"></i> {{ $badge }}
            </div>
            <h2 class="font-sans text-3xl sm:text-4xl font-700 tracking-tight leading-tight">{!! $title !!}</h2>
            @if ($description)
                <p class="text-sm sm:text-base mt-3 max-w-xl mx-auto" style="color:var(--c-muted)">{{ $description }}</p>
            @endif
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($features as $i => $f)
                @php
                    $g1 = $f['g1'] ?? $gradients[$i % 6][0];
                    $g2 = $f['g2'] ?? $gradients[$i % 6][1];
                    $iconColor = $g2 === '#a3e635' ? '#061c21' : '#fff';
                @endphp
                <div class="feat-card glass rounded-3xl p-6 relative overflow-hidden isolate">
                    <div class="feat-mesh" style="background:radial-gradient(circle at 20% 0%, {{ $g1 }}22, transparent 55%), radial-gradient(circle at 100% 100%, {{ $g2 }}1f, transparent 50%)"></div>
                    <div class="relative">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-4 relative overflow-hidden" style="background:linear-gradient(135deg,{{ $g1 }},{{ $g2 }});box-shadow:0 10px 24px {{ $g1 }}40, inset 0 1px 0 rgba(255,255,255,0.4)">
                            <i class="{{ $f['icon'] }} relative z-10" style="color:{{ $iconColor }}"></i>
                            <span class="absolute inset-0" style="background:linear-gradient(135deg, rgba(255,255,255,0.35), transparent 55%)"></span>
                        </div>
                        <h3 class="font-sans text-lg font-700 tracking-tight mb-2">{{ $f['title'] }}</h3>
                        <p class="text-[13px] leading-relaxed" style="color:var(--c-muted)">{{ $f['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
