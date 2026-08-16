<footer style="border-top:1px solid var(--c-border)" class="py-14 px-5 sm:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col lg:flex-row lg:items-start gap-12 lg:gap-16 mb-12">
            <div class="lg:w-[20%] shrink-0">
                <a href="{{ url('/') }}" class="inline-flex items-center mb-4" aria-label="devmatrixa home">
                    <img src="{{ asset('logo.png') }}" alt="devmatrixa" width="180" height="45" class="h-10 w-auto">
                </a>
                <p class="text-sm leading-relaxed max-w-xs mb-5" style="color:var(--c-muted)">
                    Premium developer tools crafted for creators who value speed, simplicity, and clean workflows.
                </p>
                <div class="flex gap-3">
                    <a href="https://github.com/BanshwarTech" target="_blank" rel="noopener noreferrer" aria-label="GitHub" class="w-9 h-9 rounded-xl flex items-center justify-center text-sm transition-all hover:-translate-y-0.5" style="background:var(--c-bg2);border:1px solid var(--c-border);color:var(--c-muted)">
                        <i class="fa-brands fa-github"></i>
                    </a>
                    <a href="https://www.linkedin.com/in/alekh-banshwar/" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn" class="w-9 h-9 rounded-xl flex items-center justify-center text-sm transition-all hover:-translate-y-0.5" style="background:var(--c-bg2);border:1px solid var(--c-border);color:var(--c-muted)">
                        <i class="fa-brands fa-linkedin-in"></i>
                    </a>
                    <a href="mailto:contact@alekh.dev" aria-label="Email" class="w-9 h-9 rounded-xl flex items-center justify-center text-sm transition-all hover:-translate-y-0.5" style="background:var(--c-bg2);border:1px solid var(--c-border);color:var(--c-muted)">
                        <i class="fa-solid fa-envelope"></i>
                    </a>
                    <a href="{{ url('/contact') }}" aria-label="Contact" class="w-9 h-9 rounded-xl flex items-center justify-center text-sm transition-all hover:-translate-y-0.5" style="background:var(--c-bg2);border:1px solid var(--c-border);color:var(--c-muted)">
                        <i class="fa-solid fa-paper-plane"></i>
                    </a>
                </div>
            </div>

            <div class="lg:w-[15%] shrink-0">
                <h3 class="font-sans font-600 text-sm mb-4">Product</h3>
                <ul class="space-y-2.5 text-sm" style="color:var(--c-muted)">
                    <li><a href="{{ url('/') }}" class="hover:text-accent transition-colors duration-200">Home</a></li>
                    <li><a href="{{ url('/#tools') }}" class="hover:text-accent transition-colors duration-200">All Tools</a></li>
                    <li><a href="{{ url('/#featured') }}" class="hover:text-accent transition-colors duration-200">Featured</a></li>
                    <li><a href="{{ url('/about') }}" class="hover:text-accent transition-colors duration-200">About</a></li>
                    <li><a href="{{ url('/contact') }}" class="hover:text-accent transition-colors duration-200">Contact</a></li>
                </ul>
            </div>

            <div class="lg:w-[15%] shrink-0">
                <h3 class="font-sans font-600 text-sm mb-4">SEO Tools</h3>
                <ul class="space-y-2.5 text-sm" style="color:var(--c-muted)">
                    <li><a href="{{ url('/seo-analyzer') }}" class="hover:text-accent transition-colors duration-200">SEO Analyzer</a></li>
                    <li><a href="{{ url('/link-checker') }}" class="hover:text-accent transition-colors duration-200">Link Checker</a></li>
                    <li><a href="{{ url('/alt-checker') }}" class="hover:text-accent transition-colors duration-200">Alt Text Checker</a></li>
                    <li><a href="{{ url('/heading-checker') }}" class="hover:text-accent transition-colors duration-200">Heading Checker</a></li>
                    <li><a href="{{ url('/anchor-text-analyzer') }}" class="hover:text-accent transition-colors duration-200">Anchor Text Analyzer</a></li>
                </ul>
            </div>

            <div class="lg:w-[15%] shrink-0">
                <h3 class="font-sans font-600 text-sm mb-4">Dev Tools</h3>
                <ul class="space-y-2.5 text-sm" style="color:var(--c-muted)">
                    <li><a href="{{ url('/tailwind-extractor') }}" class="hover:text-accent transition-colors duration-200">Tailwind Extractor</a></li>
                    <li><a href="{{ url('/css-to-tailwind') }}" class="hover:text-accent transition-colors duration-200">CSS to Tailwind</a></li>
                    <li><a href="{{ url('/css-variable-scanner') }}" class="hover:text-accent transition-colors duration-200">CSS Variable Scanner</a></li>
                    <li><a href="{{ url('/script-audit') }}" class="hover:text-accent transition-colors duration-200">Script Audit</a></li>
                </ul>
            </div>

            <div class="lg:w-[15%] shrink-0">
                <h3 class="font-sans font-600 text-sm mb-4">More Tools</h3>
                <ul class="space-y-2.5 text-sm" style="color:var(--c-muted)">
                    <li><a href="{{ url('/color-palette') }}" class="hover:text-accent transition-colors duration-200">Color Palette</a></li>
                    <li><a href="{{ url('/font-detector') }}" class="hover:text-accent transition-colors duration-200">Font Detector</a></li>
                    <li><a href="{{ url('/tech-stack-detector') }}" class="hover:text-accent transition-colors duration-200">Tech Stack Detector</a></li>
                    <li><a href="{{ url('/og-preview') }}" class="hover:text-accent transition-colors duration-200">OG Preview</a></li>
                </ul>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-6 text-xs" style="border-top:1px solid var(--c-border);color:var(--c-muted)">
            <span>&copy; {{ date('Y') }} Devmatrixa Inc. All rights reserved.</span>
            <span class="flex items-center gap-1.5">
                Crafted with <i class="fa-solid fa-heart" style="color:#0694a2"></i> for the craft-obsessed
            </span>
        </div>
    </div>
</footer>
