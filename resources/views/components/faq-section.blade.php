@props(['badge' => 'FAQ', 'description' => null, 'items'])

<section class="faq-section relative px-5 py-16 sm:px-8">
    <div class="faq-container relative mx-auto max-w-3xl">
        <div class="faq-heading mb-9 text-center">
            <div class="faq-badge inline-flex items-center gap-2 rounded-full px-3.5 py-1.5 text-[10px] font-bold uppercase tracking-widest">
                <i class="text-[11px]" aria-hidden="true" data-lucide="circle-help"></i> {{ $badge }}
            </div>
            <h2 class="mt-4 font-sans text-3xl sm:text-4xl font-700 tracking-tight leading-tight">
                {!! isset($title) ? $title : 'Common <span class="s-it text-accent">questions.</span>' !!}
            </h2>
            @if ($description)
                <p class="mt-3 text-sm leading-relaxed sm:text-base" style="color:var(--c-muted)">{{ $description }}</p>
            @endif
        </div>

        <div class="faq-list">
            @foreach ($items as $f)
                <details class="faq-item group">
                    <summary class="faq-question">
                        <span class="faq-question-copy">
                            <span class="faq-question-mark" aria-hidden="true">
                                <i data-lucide="circle-help"></i>
                            </span>
                            <span>{{ $f['q'] }}</span>
                        </span>
                        <span class="faq-toggle" aria-hidden="true">
                            <i data-lucide="plus"></i>
                        </span>
                    </summary>
                    <div class="faq-answer">
                        <p>{{ $f['a'] }}</p>
                    </div>
                </details>
            @endforeach
        </div>
    </div>
</section>
