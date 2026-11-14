<x-layout
    title="Contact DevMatrixa | Get in Touch"
    description="Have a feature request, found a bug, or want to collaborate? Reach out to the DevMatrixa team. We respond fast and actually read every message."
    keywords="contact devmatrixa, devmatrixa support, feature request, report a bug, devmatrixa feedback"
>
    @push('head')
        @vite('resources/js/pages/contact.js')
    @endpush

    @php
        $reachOutTopics = [
            ['icon' => 'fa-bug', 'title' => 'Bug Report', 'text' => 'Something broken or wrong output', 'bg' => 'rgba(239, 68, 68, 0.12)', 'color' => '#f87171'],
            ['icon' => 'fa-lightbulb', 'title' => 'Tool Idea', 'text' => 'Suggest a new tool to build', 'bg' => 'rgba(0, 152, 240, 0.12)', 'color' => '#0098f0'],
            ['icon' => 'fa-handshake', 'title' => 'Collaboration', 'text' => 'Partnership or guest content', 'bg' => 'rgba(22, 189, 202, 0.12)', 'color' => '#16bdca'],
            ['icon' => 'fa-comment-dots', 'title' => 'General Feedback', 'text' => 'Anything else on your mind', 'bg' => 'rgba(64, 16, 176, 0.14)', 'color' => '#0078e0'],
        ];

        $faqs = [
            ['q' => 'Are all tools really free?', 'a' => 'Yes. Devmatrixa tools are free to use with no signup required.'],
            ['q' => 'Do I need to create an account?', 'a' => 'No account is needed. Open a tool and start working right away.'],
            ['q' => 'Do you store the URLs I analyze?', 'a' => 'No. Tool requests are processed for the result and are not used to build user profiles.'],
            ['q' => 'Can I suggest a new tool?', 'a' => 'Absolutely. Send a message with the workflow you want improved and what output would help you.'],
            ['q' => 'I found a bug - how do I report it?', 'a' => 'Use the contact form with a short description, the tool name, and any URL or input that reproduces it.'],
            ['q' => 'Can I use Devmatrixa for commercial projects?', 'a' => 'Yes. You can use the outputs in client work and commercial projects.'],
        ];
    @endphp

    <main class="relative overflow-hidden">
        <section class="contact-hero dot-bg relative px-5 pb-14 pt-32 sm:px-8 lg:pb-16 lg:pt-36">
            <div class="contact-hero-wash pointer-events-none absolute inset-x-0 top-0 h-[520px]" style="background:radial-gradient(circle at 18% 6%,rgba(22,189,202,0.22),transparent 34%),radial-gradient(circle at 86% 42%,rgba(0, 152, 240,0.18),transparent 32%)"></div>

            <div class="contact-hero-content relative mx-auto max-w-7xl">
                <div class="contact-hero-copy mx-auto max-w-3xl text-center">
                    <div class="hero-badge mb-7 inline-flex items-center gap-2 rounded-full border px-4 py-2 text-xs font-700 uppercase tracking-widest" style="border-color:var(--c-border);color:#16bdca;background:rgba(6, 148, 162, 0.10)">
                        <span class="h-2 w-2 rounded-full bg-teal-400"></span>
                        Get in touch
                    </div>
                    <h1 class="hero-title font-sans text-5xl font-700 leading-[0.95] tracking-tight sm:text-6xl lg:text-7xl">
                        Say hello to <br>
                        <span class="s-it text-accent font-normal">Devmatrixa.</span>
                    </h1>
                    <p class="hero-description mx-auto mt-7 max-w-2xl text-lg font-600 leading-relaxed sm:text-xl" style="color:var(--c-muted)">
                        Found a bug? Have a tool idea? Want to collaborate? Drop a message - we reply to every email.
                    </p>
                </div>
            </div>
        </section>

        <section class="contact-form-section px-5 py-12 sm:px-8 sm:py-16">
            <div class="mx-auto max-w-7xl">
                <div class="grid items-start gap-8 lg:grid-cols-[0.8fr_1.2fr] lg:px-10">
                    <aside class="space-y-5">
                        <div class="glass rounded-[22px] p-6">
                            <div class="flex items-center gap-4">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-teal-500 to-cyan-400 text-white">
                                    <i class="fa-solid fa-envelope"></i>
                                </div>
                                <div>
                                    <h2 class="font-600">Email Us</h2>
                                    <a href="mailto:contact@alekh.dev" class="mt-1 block text-sm font-700" style="color:#16bdca">
                                        contact@alekh.dev
                                    </a>
                                    <p class="mt-1 text-xs font-600" style="color:var(--c-muted)">We reply within 24 hours</p>
                                </div>
                            </div>
                        </div>

                        <div class="glass rounded-[22px] p-6">
                            <div class="flex items-center gap-4">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-400 to-teal-500" style="color:#061c21">
                                    <i class="fa-solid fa-clock"></i>
                                </div>
                                <div>
                                    <h2 class="font-600">Response Time</h2>
                                    <p class="mt-1 text-sm font-700" style="color:#0098f0">Usually within 24h</p>
                                    <p class="mt-1 text-xs font-600" style="color:var(--c-muted)">Mon-Sat, 9AM-7PM IST</p>
                                </div>
                            </div>
                        </div>

                        <div class="glass rounded-[22px] p-6">
                            <h2 class="mb-5 font-600">What can you reach out about?</h2>
                            <div class="space-y-3">
                                @foreach ($reachOutTopics as $topic)
                                    <div class="contact-topic flex items-center gap-4 rounded-2xl border p-4" style="border-color:var(--c-border);background:{{ $topic['bg'] }}">
                                        <div class="contact-topic-icon flex h-9 w-9 shrink-0 items-center justify-center rounded-xl" style="color:{{ $topic['color'] }};background:rgba(6, 28, 33, 0.25)">
                                            <i class="fa-solid {{ $topic['icon'] }} text-sm"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-700">{{ $topic['title'] }}</h3>
                                            <p class="text-xs font-600" style="color:var(--c-muted)">{{ $topic['text'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </aside>

                    <div id="contact-form-app"></div>
                </div>
            </div>
        </section>

        <x-faq-section badge="Quick answers" :items="$faqs" />
    </main>
</x-layout>
