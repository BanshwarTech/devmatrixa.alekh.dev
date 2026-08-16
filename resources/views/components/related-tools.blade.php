@props([
    'currentKey',
    'category' => null,
    'toolKeys' => null,
    'limit' => 3,
    'badge' => 'Keep going',
    'title' => 'Related tools.',
    'description' => 'Pick the next tool that helps you move faster.',
])
@php
    $tools = collect(config('tools'))->filter(fn ($t) => $t['key'] !== $currentKey && !empty($t['url']));

    if ($toolKeys) {
        $byKey = $tools->keyBy('key');
        $pool = collect($toolKeys)->map(fn ($k) => $byKey->get($k))->filter()->values();
    } elseif ($category) {
        $pool = $tools->where('cat', $category)->values();
    } else {
        $pool = $tools->values();
    }

    $items = $pool->take($limit);
@endphp

@if ($items->isNotEmpty())
<section class="py-12 px-5 sm:px-8 relative overflow-hidden">
    <div class="absolute inset-0 pointer-events-none opacity-40">
        <div class="orb w-[380px] h-[380px] opacity-15" style="background:radial-gradient(circle,#0694a2,transparent 65%);top:20%;right:-8%"></div>
    </div>
    <div class="max-w-7xl mx-auto relative">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3 mb-8">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest mb-3" style="background:rgba(22,189,202,0.10);border:1px solid rgba(22,189,202,0.28);color:#16bdca">
                    <i class="fa-solid fa-wrench text-[11px]"></i> {{ $badge }}
                </div>
                <h2 class="font-sans text-2xl sm:text-3xl font-700 tracking-tight leading-tight">{{ $title }}</h2>
                <p class="text-sm sm:text-[15px] mt-2 max-w-xl" style="color:var(--c-muted)">{{ $description }}</p>
            </div>
            <a href="{{ url('/#tools') }}" class="btn-outline px-5 py-2.5 rounded-full text-xs font-semibold inline-flex items-center gap-2 self-start sm:self-end">
                View All Tools <i class="fa-solid fa-arrow-right text-xs" style="color:var(--c-muted)"></i>
            </a>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($items as $t)
                @php $iconColor = ($t['g1'] === '#a3e635' || $t['g2'] === '#a3e635') ? '#061c21' : '#fff'; @endphp
                <a href="{{ url($t['url']) }}" class="tool-card rounded-[20px] p-5 flex flex-col relative overflow-hidden focus:outline-none focus-visible:ring-2 focus-visible:ring-teal-400/60">
                    <div class="tc-spotlight" style="background:radial-gradient(360px circle at var(--mx,50%) var(--my,50%), {{ $t['g1'] }}26, transparent 50%)"></div>
                    <div class="relative flex items-start justify-between z-10">
                        <div class="t-icon-wrap">
                            <div class="t-icon w-12 h-12 rounded-2xl flex items-center justify-center relative" style="background:linear-gradient(135deg,{{ $t['g1'] }},{{ $t['g2'] }});box-shadow:0 10px 24px {{ $t['g1'] }}55, inset 0 1px 0 rgba(255,255,255,0.35)">
                                <i class="{{ $t['icon'] }} text-[16px] relative z-10" style="color:{{ $iconColor }}"></i>
                                <span class="t-icon-shine"></span>
                            </div>
                        </div>
                        @if (!empty($t['badge']))
                            <span class="tc-badge text-[9px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider" style="background:linear-gradient(135deg,{{ $t['g1'] }},{{ $t['g2'] }});color:#061c21;letter-spacing:0.06em">
                                {{ $t['badge'] }}
                            </span>
                        @endif
                    </div>

                    <div class="relative flex-1 mt-4 z-10">
                        <h3 class="tc-name font-sans font-700 text-[15.5px] tracking-tight mb-2 leading-snug" style="color:var(--c-text)">{{ $t['name'] }}</h3>
                        <p class="text-[12.5px] leading-relaxed line-clamp-3" style="color:var(--c-muted)">{{ $t['desc'] }}</p>
                    </div>

                    <div class="relative flex items-center justify-end pt-4 mt-4 z-10" style="border-top:1px dashed var(--c-border)">
                        <span class="tc-cta text-[11.5px] font-bold inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg" style="color:#0694a2">
                            Open Tool
                            <i class="fa-solid fa-arrow-right tc-arrow text-[11px]"></i>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif
