@props([
    'icon',
    'iconBg' => 'linear-gradient(135deg,#0694a2,#a3e635)',
    'iconColor' => '#061c21',
    'title',
    'subtitle',
    'variant' => 'browser',
    'status' => null,
    'hostLabel' => 'devmatrixa.com',
])
@php
    $themes = [
        'browser'  => ['accent' => 'linear-gradient(90deg, transparent, #0694a2, #a3e635, transparent)', 'glow' => 'rgba(163,230,53,0.12)', 'ring' => 'rgba(22,189,202,0.18)', 'cornerA' => 'rgba(163,230,53,0.55)', 'cornerB' => 'rgba(22,189,202,0.55)', 'statusDefault' => 'LIVE'],
        'terminal' => ['accent' => 'linear-gradient(90deg, transparent, #16bdca, #a3e635, transparent)', 'glow' => 'rgba(163,230,53,0.16)', 'ring' => 'rgba(101,163,13,0.28)', 'cornerA' => 'rgba(132,204,22,0.7)', 'cornerB' => 'rgba(132,204,22,0.5)', 'statusDefault' => 'READY'],
        'scope'    => ['accent' => 'linear-gradient(90deg, transparent, #f59e0b, #a3e635, transparent)', 'glow' => 'rgba(245,158,11,0.16)', 'ring' => 'rgba(245,158,11,0.30)', 'cornerA' => 'rgba(245,158,11,0.7)', 'cornerB' => 'rgba(163,230,53,0.6)', 'statusDefault' => 'SCANNING'],
        'gauge'    => ['accent' => 'linear-gradient(90deg, transparent, #a3e635, #16bdca, transparent)', 'glow' => 'rgba(22,189,202,0.16)', 'ring' => 'rgba(22,189,202,0.30)', 'cornerA' => 'rgba(22,189,202,0.7)', 'cornerB' => 'rgba(163,230,53,0.6)', 'statusDefault' => 'GRADING'],
        'graph'    => ['accent' => 'linear-gradient(90deg, transparent, #f97316, #16bdca, transparent)', 'glow' => 'rgba(22,189,202,0.16)', 'ring' => 'rgba(249,115,22,0.30)', 'cornerA' => 'rgba(249,115,22,0.7)', 'cornerB' => 'rgba(22,189,202,0.6)', 'statusDefault' => 'TRACING'],
    ];
    $t = $themes[$variant] ?? $themes['browser'];
    $finalStatus = $status ?? $t['statusDefault'];
    $useDots = $variant === 'browser';
    $scanColor = $variant === 'terminal' ? 'rgba(163,230,53,0.85)' : ($variant === 'gauge' ? 'rgba(22,189,202,0.7)' : ($variant === 'graph' ? 'rgba(249,115,22,0.7)' : 'rgba(163,230,53,0.85)'));
@endphp

<div class="pc-card hero-card-main absolute top-6 left-6 right-6 glass rounded-3xl p-6 relative overflow-hidden">
    <div class="absolute top-0 left-8 right-8 h-[2px] rounded-b-full" style="background:{{ $t['accent'] }}"></div>

    <span class="absolute top-3 left-3 w-3.5 h-3.5 pointer-events-none" style="border-left:1.5px solid {{ $t['cornerA'] }};border-top:1.5px solid {{ $t['cornerA'] }}"></span>
    <span class="absolute top-3 right-3 w-3.5 h-3.5 pointer-events-none" style="border-right:1.5px solid {{ $t['cornerB'] }};border-top:1.5px solid {{ $t['cornerB'] }}"></span>
    <span class="absolute bottom-3 left-3 w-3.5 h-3.5 pointer-events-none" style="border-left:1.5px solid {{ $t['cornerB'] }};border-bottom:1.5px solid {{ $t['cornerB'] }}"></span>
    <span class="absolute bottom-3 right-3 w-3.5 h-3.5 pointer-events-none" style="border-right:1.5px solid {{ $t['cornerA'] }};border-bottom:1.5px solid {{ $t['cornerA'] }}"></span>

    <div class="relative flex items-center gap-3 mb-5">
        <div class="w-11 h-11 rounded-2xl flex items-center justify-center relative overflow-hidden" style="background:{{ $iconBg }};box-shadow:0 10px 24px rgba(6,148,162,0.35), inset 0 1px 0 rgba(255,255,255,0.4)">
            <i class="{{ $icon }} relative z-10" style="color:{{ $iconColor }}"></i>
            <span class="absolute inset-0" style="background:linear-gradient(135deg, rgba(255,255,255,0.4), transparent 55%)"></span>
            <span class="pc-icon-shimmer absolute inset-0"></span>
        </div>
        <div class="flex-1 min-w-0">
            @if ($variant === 'terminal')
                <p class="text-sm font-mono truncate flex items-center gap-1" style="color:#a3e635">
                    <span style="color:var(--c-muted)">~$</span> {{ $title }}
                    <span class="pc-caret inline-block w-1.5 h-3.5 align-middle" style="background:#a3e635"></span>
                </p>
            @else
                <p class="text-sm font-700 truncate" style="color:var(--c-text)">{{ $title }}</p>
            @endif
            <p class="text-[10px] uppercase tracking-[0.25em] flex items-center gap-1.5" style="color:var(--c-muted)">
                <span class="w-1 h-1 rounded-full animate-pulse" style="background:{{ $variant === 'scope' ? '#f59e0b' : '#a3e635' }}"></span>
                {{ $subtitle }}
            </p>
        </div>
        @if ($useDots)
            <div class="ml-auto flex items-center gap-1.5 shrink-0">
                <span class="w-2.5 h-2.5 rounded-full" style="background:#ff6b6b"></span>
                <span class="w-2.5 h-2.5 rounded-full" style="background:#fbbf24"></span>
                <span class="w-2.5 h-2.5 rounded-full" style="background:#a3e635"></span>
            </div>
        @elseif ($variant === 'terminal')
            <span class="ml-auto inline-flex items-center gap-1.5 text-[9px] font-mono px-2 py-0.5 rounded shrink-0" style="background:rgba(132,204,22,0.12);color:#a3e635;border:1px solid rgba(132,204,22,0.3)">bash</span>
        @elseif ($variant === 'scope')
            <span class="ml-auto inline-flex items-center justify-center w-7 h-7 rounded-full shrink-0 relative" style="border:1.5px solid rgba(245,158,11,0.5)">
                <span class="w-1 h-1 rounded-full" style="background:#f59e0b"></span>
                <span class="absolute inset-[3px] rounded-full" style="border:1px dashed rgba(245,158,11,0.35)"></span>
            </span>
        @endif
    </div>

    <div class="relative rounded-[1.25rem] p-4 overflow-hidden" style="background:rgba(10, 47, 54, 0.55);border:1px solid {{ $t['ring'] }};box-shadow:inset 0 1px 0 rgba(255,255,255,0.04)">
        <span class="pc-scanline pointer-events-none absolute left-0 right-0 h-px" style="background:linear-gradient(90deg, transparent, {{ $scanColor }}, transparent)"></span>
        <div class="relative">{{ $slot }}</div>
    </div>

    <div class="relative flex items-center justify-between gap-3 mt-4 text-[9px] uppercase tracking-[0.18em] font-bold">
        <span class="inline-flex items-center gap-1.5" style="color:{{ $variant === 'scope' ? '#f59e0b' : '#a3e635' }}">
            <span class="relative inline-flex w-1.5 h-1.5">
                <span class="absolute inset-0 rounded-full animate-ping" style="background:{{ $variant === 'scope' ? '#f59e0b' : '#a3e635' }}"></span>
                <span class="relative w-1.5 h-1.5 rounded-full" style="background:{{ $variant === 'scope' ? '#f59e0b' : '#a3e635' }}"></span>
            </span>
            {{ $finalStatus }}
        </span>
        <span class="font-mono normal-case tracking-normal truncate" style="color:var(--c-muted)">{{ $hostLabel }}</span>
        <span style="color:var(--c-muted)">{{ $variant === 'scope' ? 'v1.0' : ($variant === 'gauge' ? '0–100' : ($variant === 'graph' ? 'TRACE' : ($variant === 'terminal' ? 'v$' : 'READY'))) }}</span>
    </div>
</div>
