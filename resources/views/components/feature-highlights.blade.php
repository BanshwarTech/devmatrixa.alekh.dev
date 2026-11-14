@props(['badge' => 'Why this tool', 'description' => null, 'features'])
@php
    $gradients = [['#6818E8', '#0078E0'], ['#4010B0', '#6818E8'], ['#0078E0', '#0098F0'], ['#6818E8', '#4010B0'], ['#0078E0', '#6818E8'], ['#0098F0', '#4010B0']];
@endphp

<section class="tool-features-section relative px-5 py-16 sm:px-8">
    <div class="mx-auto max-w-7xl relative">
        <div class="tool-features-heading mb-10 text-center">
            <div class="tool-features-badge inline-flex items-center gap-2 rounded-full px-3.5 py-1.5 text-[10px] font-bold uppercase tracking-widest">
                <i class="text-[11px]" aria-hidden="true" data-lucide="sparkles"></i> {{ $badge }}
            </div>
            <h2 class="mt-4 font-sans text-3xl sm:text-4xl font-700 tracking-tight leading-tight">{!! $title !!}</h2>
            @if ($description)
                <p class="text-sm sm:text-base mt-3 max-w-2xl mx-auto leading-relaxed" style="color:var(--c-muted)">{{ $description }}</p>
            @endif
        </div>

        <div class="tool-features-grid grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($features as $i => $f)
                @php
                    $g1 = $f['g1'] ?? $gradients[$i % 6][0];
                    $g2 = $f['g2'] ?? $gradients[$i % 6][1];
                @endphp
                <div class="tool-feature-card" style="--feature-accent:{{ $g1 }};--feature-accent-secondary:{{ $g2 }}">
                    <div class="tool-feature-top">
                        <div class="tool-feature-icon">
                            <i aria-hidden="true" data-lucide="{{ $f['icon'] }}"></i>
                        </div>
                        <span class="tool-feature-index" aria-hidden="true">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <h3 class="tool-feature-title">{{ $f['title'] }}</h3>
                    <p class="tool-feature-description">{{ $f['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
