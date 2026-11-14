@props([
    'badge',
    'badgeColor' => '#4010b0',
    'badgeBg' => 'rgba(0, 152, 240,0.09)',
    'badgeBorder' => 'rgba(0, 152, 240,0.25)',
    'description',
    'primaryCta' => null,
    'secondaryCta' => null,
    'trustStrip' => true,
    'trustLabels' => [],
])
@php
    $trust = array_merge([
        'fast' => 'Lightning fast',
        'privacy' => 'Privacy-first',
        'signup' => 'No signup',
        'unlimited' => 'Unlimited use',
    ], $trustLabels);
    $hasRightPanel = isset($rightPanel) && trim((string) $rightPanel) !== '';
@endphp

<section class="site-hero tool-hero relative flex items-center overflow-hidden pt-20">
    <div class="tool-hero-shell relative z-10 mx-auto w-full max-w-7xl px-5 py-12 sm:px-8 sm:py-16">
        <div class="grid items-center gap-12 lg:gap-16 {{ $hasRightPanel ? 'lg:grid-cols-[0.92fr_1.08fr]' : '' }}">
            <div class="{{ $hasRightPanel ? 'text-center lg:text-left' : 'mx-auto max-w-4xl text-center' }}">
                <div class="hero-badge tool-hero-badge inline-flex items-center gap-2.5 rounded-full px-4 py-1.5 text-xs font-semibold uppercase tracking-widest mb-7 {{ $hasRightPanel ? '' : 'mx-auto' }}" style="background:{{ $badgeBg }};border:1.5px solid {{ $badgeBorder }};color:{{ $badgeColor }};backdrop-filter:blur(12px)">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" style="background:{{ $badgeColor }}"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2" style="background:{{ $badgeColor }}"></span>
                    </span>
                    {{ $badge }}
                </div>
                <h1 class="tool-hero-title mb-6 font-sans text-4xl font-700 leading-[1.03] tracking-tight sm:text-5xl lg:text-6xl xl:text-7xl">{!! $title !!}</h1>
                <p class="tool-hero-description mb-8 max-w-xl text-base leading-relaxed sm:text-lg {{ $hasRightPanel ? 'mx-auto lg:mx-0' : 'mx-auto' }}" style="color:var(--c-muted)">{{ $description }}</p>
                <div class="flex flex-wrap items-center gap-4 {{ $hasRightPanel ? 'justify-center lg:justify-start' : 'justify-center' }}">
                    @if ($primaryCta)
                        <a href="{{ $primaryCta['href'] }}" class="btn-primary px-7 py-3.5 rounded-full text-sm inline-flex items-center gap-2.5 font-700">
                            <i class="text-xs" style="color:#061c21" data-lucide="{{ $primaryCta['icon'] ?? 'bolt' }}"></i> {{ $primaryCta['label'] }}
                        </a>
                    @endif
                    @if ($secondaryCta)
                        <a href="{{ $secondaryCta['href'] }}" class="btn-outline px-7 py-3.5 rounded-full text-sm inline-flex items-center gap-2.5 font-semibold">
                            {{ $secondaryCta['label'] }} <i class="text-xs" style="color:var(--c-muted)" data-lucide="arrow-right"></i>
                        </a>
                    @endif
                </div>

                @if ($trustStrip)
                    <div class="tool-hero-trust mt-8 flex flex-wrap items-center gap-x-3 gap-y-2 text-[11px] font-medium {{ $hasRightPanel ? 'justify-center lg:justify-start' : 'justify-center' }}" style="color:var(--c-muted)">
                        <span class="flex items-center gap-1.5"><i class="text-xs" style="color:#0098f0" data-lucide="bolt"></i>{{ $trust['fast'] }}</span>
                        <span class="flex items-center gap-1.5"><i class="text-xs" style="color:#16bdca" data-lucide="shield"></i>{{ $trust['privacy'] }}</span>
                        <span class="flex items-center gap-1.5"><i class="text-xs" style="color:#0098f0" data-lucide="circle-check"></i>{{ $trust['signup'] }}</span>
                        <span class="flex items-center gap-1.5"><i class="text-xs" style="color:#16bdca" data-lucide="infinity"></i>{{ $trust['unlimited'] }}</span>
                    </div>
                @endif
            </div>

            @if ($hasRightPanel)
                <div class="tool-hero-visual hidden relative mx-auto w-full max-w-xl lg:ml-auto lg:block">
                    {{ $rightPanel }}
                </div>
            @endif
        </div>
    </div>
</section>
