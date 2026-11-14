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
<section class="related-tools-section relative overflow-hidden px-5 py-16 sm:px-8">
    <div class="related-tools-glow pointer-events-none absolute inset-0" aria-hidden="true">
        <div class="orb w-[380px] h-[380px]" style="background:radial-gradient(circle,#6818E8,transparent 65%);top:10%;right:-8%"></div>
    </div>
    <div class="max-w-7xl mx-auto relative">
        <div class="related-tools-heading mb-9 text-center">
            <div class="related-tools-badge inline-flex items-center gap-2 rounded-full px-3.5 py-1.5 text-[10px] font-bold uppercase tracking-widest">
                <i class="text-[11px]" aria-hidden="true" data-lucide="wand-sparkles"></i> {{ $badge }}
            </div>
            <h2 class="mt-4 font-sans text-3xl sm:text-4xl font-700 tracking-tight leading-tight">{{ $title }}</h2>
            <p class="text-sm sm:text-base mt-3 max-w-xl mx-auto leading-relaxed" style="color:var(--c-muted)">{{ $description }}</p>
            <a href="{{ url('/#tools') }}" class="related-tools-all mt-5 inline-flex items-center gap-2 rounded-full px-5 py-2.5 text-xs font-semibold">
                View all tools <i class="text-xs" aria-hidden="true" data-lucide="arrow-right"></i>
            </a>
        </div>

        <div class="related-tools-grid grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($items as $t)
                @php $iconColor = ($t['g1'] === '#0098f0' || $t['g2'] === '#0098f0') ? '#061c21' : '#fff'; @endphp
                <a href="{{ url($t['url']) }}" class="related-tool-card group flex flex-col relative overflow-hidden" style="--related-accent:{{ $t['g1'] }};--related-accent-secondary:{{ $t['g2'] }}">
                    <div class="relative flex items-start justify-between z-10">
                        <div class="t-icon-wrap">
                            <div class="related-tool-icon t-icon">
                                <i style="color:{{ $iconColor }}" aria-hidden="true" data-lucide="{{ $t['icon'] }}"></i>
                            </div>
                        </div>
                        <div class="related-tool-labels">
                            <span class="related-tool-category">{{ ucfirst($t['cat']) }}</span>
                            @if (!empty($t['badge']))
                                <span class="related-tool-badge">{{ $t['badge'] }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="relative flex-1 mt-4 z-10">
                        <h3 class="tc-name font-sans font-700 text-[15.5px] tracking-tight mb-2 leading-snug" style="color:var(--c-text)">{{ $t['name'] }}</h3>
                        <p class="text-[12.5px] leading-relaxed line-clamp-3" style="color:var(--c-muted)">{{ $t['desc'] }}</p>
                    </div>

                    <div class="related-tool-footer relative flex items-center justify-between mt-5 pt-4 z-10">
                        <span class="related-tool-hint">Explore tool</span>
                        <span class="related-tool-cta">
                            Open tool <i aria-hidden="true" data-lucide="arrow-right"></i>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif
