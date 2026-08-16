@props(['badge' => 'FAQ', 'description' => null, 'items'])

<section class="py-12 px-5 sm:px-8 relative">
    <div class="max-w-3xl mx-auto relative">
        <div class="text-center mb-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest mb-4" style="background:rgba(6,148,162,0.08);border:1px solid rgba(6,148,162,0.20);color:#0694a2">
                <i class="fa-solid fa-circle-question text-[11px]"></i> {{ $badge }}
            </div>
            <h2 class="font-sans text-3xl sm:text-4xl font-700 tracking-tight leading-tight">
                {!! isset($title) ? $title : 'Common <span class="s-it text-accent">questions.</span>' !!}
            </h2>
            @if ($description)
                <p class="text-sm sm:text-base mt-3" style="color:var(--c-muted)">{{ $description }}</p>
            @endif
        </div>

        <div class="space-y-3">
            @foreach ($items as $f)
                <details class="glass rounded-2xl overflow-hidden group">
                    <summary class="cursor-pointer px-5 py-4 flex items-center justify-between gap-4 text-sm font-semibold list-none" style="color:var(--c-text)">
                        <span>{{ $f['q'] }}</span>
                        <span class="shrink-0 w-7 h-7 rounded-full flex items-center justify-center transition-transform duration-300 group-open:rotate-45" style="background:rgba(6,148,162,0.08);color:#0694a2">
                            <i class="fa-solid fa-plus text-xs"></i>
                        </span>
                    </summary>
                    <div class="px-5 pb-4 pt-3 text-sm leading-relaxed" style="color:var(--c-muted);border-top:1px dashed var(--c-border)">
                        {{ $f['a'] }}
                    </div>
                </details>
            @endforeach
        </div>
    </div>
</section>
