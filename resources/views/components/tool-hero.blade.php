@props([
    'badge',
    'badgeColor' => '#65a30d',
    'badgeBg' => 'rgba(163,230,53,0.09)',
    'badgeBorder' => 'rgba(163,230,53,0.25)',
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
    $hasRightPanel = isset($rightPanel) && trim($rightPanel) !== '';
@endphp

<section class="relative min-h-screen flex items-center pt-20 dot-bg">
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="orb w-[700px] h-[700px] animate-drift opacity-25 dark:opacity-18" style="background:radial-gradient(circle,#a3e635,transparent 65%);top:-20%;right:-18%"></div>
        <div class="orb w-[500px] h-[500px] animate-drift2 opacity-15 dark:opacity-10" style="background:radial-gradient(circle,#0694a2,transparent 65%);bottom:0;left:-15%"></div>
        <div class="orb w-[320px] h-[320px] animate-drift3 opacity-10" style="background:radial-gradient(circle,#16bdca,transparent 65%);top:40%;left:28%"></div>
        <div class="ring-deco absolute w-[600px] h-[600px] opacity-25 dark:opacity-20 animate-spin-lazy" style="top:-200px;right:-200px"></div>
        <div class="ring-deco absolute w-[380px] h-[380px] opacity-[0.18] dark:opacity-[0.12]" style="bottom:-130px;left:-80px;animation:spin 55s linear infinite reverse"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-5 sm:px-8 py-16 w-full">
        <div class="grid {{ $hasRightPanel ? 'lg:grid-cols-2' : '' }} gap-12 items-center">
            <div>
                <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full text-xs font-semibold uppercase tracking-widest mb-7" style="background:{{ $badgeBg }};border:1.5px solid {{ $badgeBorder }};color:{{ $badgeColor }};backdrop-filter:blur(12px)">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" style="background:{{ $badgeColor }}"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2" style="background:{{ $badgeColor }}"></span>
                    </span>
                    {{ $badge }}
                </div>
                <h1 class="font-sans text-4xl sm:text-5xl lg:text-6xl font-700 tracking-tight leading-[1.03] mb-6">{!! $title !!}</h1>
                <p class="text-base sm:text-base leading-relaxed mb-8 max-w-lg" style="color:var(--c-muted)">{{ $description }}</p>
                <div class="flex flex-wrap items-center gap-4">
                    @if ($primaryCta)
                        <a href="{{ $primaryCta['href'] }}" class="btn-primary px-7 py-3.5 rounded-full text-sm inline-flex items-center gap-2.5 font-700">
                            <i class="{{ $primaryCta['icon'] ?? 'fa-solid fa-bolt' }} text-xs" style="color:#061c21"></i> {{ $primaryCta['label'] }}
                        </a>
                    @endif
                    @if ($secondaryCta)
                        <a href="{{ $secondaryCta['href'] }}" class="btn-outline px-7 py-3.5 rounded-full text-sm inline-flex items-center gap-2.5 font-semibold">
                            {{ $secondaryCta['label'] }} <i class="fa-solid fa-arrow-right text-xs" style="color:var(--c-muted)"></i>
                        </a>
                    @endif
                </div>

                @if ($trustStrip)
                    <div class="flex flex-wrap items-center gap-x-6 gap-y-3 mt-8 text-[11px] font-medium" style="color:var(--c-muted)">
                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-bolt text-xs" style="color:#a3e635"></i>{{ $trust['fast'] }}</span>
                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-shield text-xs" style="color:#16bdca"></i>{{ $trust['privacy'] }}</span>
                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-circle-check text-xs" style="color:#a3e635"></i>{{ $trust['signup'] }}</span>
                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-infinity text-xs" style="color:#16bdca"></i>{{ $trust['unlimited'] }}</span>
                    </div>
                @endif
            </div>
            @if ($hasRightPanel)
                <div class="hidden lg:block relative h-[460px]">{{ $rightPanel }}</div>
            @endif
        </div>
    </div>
</section>
