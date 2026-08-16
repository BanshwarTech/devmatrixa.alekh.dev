@props(['items'])
@php $doubled = array_merge($items, $items); @endphp

<div class="ticker-wrap py-5 overflow-hidden relative" style="border-top:1px solid var(--c-border);border-bottom:1px solid var(--c-border);background:var(--c-bg2);z-index:1">
    <div class="ticker-fade-left"></div>
    <div class="ticker-fade-right"></div>
    <div class="ticker-inner text-[11px] font-semibold uppercase tracking-widest" style="color:var(--c-muted)">
        @foreach ($doubled as $i => $t)
            <span class="flex items-center gap-2 shrink-0">
                <span class="flex items-center gap-2">
                    <i class="{{ $t['icon'] }} text-sm" style="color:{{ $t['color'] }}"></i>
                    {{ $t['label'] }}
                </span>
                @if ($i < count($doubled) - 1)
                    <span style="color:var(--c-border)">&#10022;</span>
                @endif
            </span>
        @endforeach
    </div>
</div>
