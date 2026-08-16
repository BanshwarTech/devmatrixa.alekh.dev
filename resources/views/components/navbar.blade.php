<header id="nav" class="fixed top-0 inset-x-0 z-50" data-navbar>
    <div class="max-w-7xl mx-auto px-5 sm:px-8 flex items-center justify-between h-[66px]">
        <a href="{{ url('/') }}" class="flex items-center group" aria-label="devmatrixa home">
            <img src="{{ asset('logo.png') }}" alt="devmatrixa" width="160" height="40" class="h-9 w-auto transition-transform duration-300 group-hover:scale-[1.02]">
        </a>

        <nav class="hidden md:flex items-center gap-1">
            <a href="{{ url('/') }}" class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 hover:bg-teal-50 dark:hover:bg-teal-900/20" style="color:var(--c-muted)">Home</a>
            <a href="{{ url('/#tools') }}" class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 hover:bg-teal-50 dark:hover:bg-teal-900/20" style="color:var(--c-muted)">Tools</a>
            <a href="{{ url('/#featured') }}" class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 hover:bg-teal-50 dark:hover:bg-teal-900/20" style="color:var(--c-muted)">Featured</a>
            <a href="{{ url('/about') }}" class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 hover:bg-teal-50 dark:hover:bg-teal-900/20" style="color:var(--c-muted)">About</a>
            <a href="{{ url('/contact') }}" class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 hover:bg-teal-50 dark:hover:bg-teal-900/20" style="color:var(--c-muted)">Contact</a>
        </nav>

        <div class="flex items-center gap-3">
            <a href="{{ url('/#tools') }}" class="hidden sm:inline-flex btn-primary px-5 py-2.5 rounded-full text-sm items-center gap-2">
                Explore Tools <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
            <button type="button" data-mob-toggle class="md:hidden p-2.5 rounded-xl transition-colors hover:bg-teal-50 dark:hover:bg-white/5">
                <i class="fa-solid fa-bars text-sm" data-mob-icon style="color:var(--c-muted)"></i>
            </button>
        </div>
    </div>

    <div id="mob" style="border-top:1px solid var(--c-border)">
        <div class="px-5 py-4 flex flex-col gap-1">
            <a href="{{ url('/') }}" class="px-4 py-2.5 rounded-xl text-sm font-medium transition-colors hover:bg-teal-50 dark:hover:bg-white/5">Home</a>
            <a href="{{ url('/#tools') }}" class="px-4 py-2.5 rounded-xl text-sm font-medium transition-colors hover:bg-teal-50 dark:hover:bg-white/5">Tools</a>
            <a href="{{ url('/#featured') }}" class="px-4 py-2.5 rounded-xl text-sm font-medium transition-colors hover:bg-teal-50 dark:hover:bg-white/5">Featured</a>
            <a href="{{ url('/about') }}" class="px-4 py-2.5 rounded-xl text-sm font-medium transition-colors hover:bg-teal-50 dark:hover:bg-white/5">About</a>
            <a href="{{ url('/contact') }}" class="px-4 py-2.5 rounded-xl text-sm font-medium transition-colors hover:bg-teal-50 dark:hover:bg-white/5">Contact</a>
            <div class="pt-2">
                <a href="{{ url('/#tools') }}" class="btn-primary inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-sm">
                    Explore Tools <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
    </div>
</header>

@once
@push('scripts')
<script>
(function () {
  var nav = document.querySelector('[data-navbar]');
  if (!nav) return;
  var mob = document.getElementById('mob');
  var toggle = nav.querySelector('[data-mob-toggle]');
  var icon = nav.querySelector('[data-mob-icon]');
  var open = false;

  function onScroll() {
    nav.classList.toggle('scrolled', window.scrollY > 8);
  }
  onScroll();
  window.addEventListener('scroll', onScroll, { passive: true });

  toggle.addEventListener('click', function () {
    open = !open;
    mob.classList.toggle('open', open);
    icon.classList.toggle('fa-bars', !open);
    icon.classList.toggle('fa-xmark', open);
  });

  mob.querySelectorAll('a').forEach(function (a) {
    a.addEventListener('click', function () {
      open = false;
      mob.classList.remove('open');
      icon.classList.add('fa-bars');
      icon.classList.remove('fa-xmark');
    });
  });
})();
</script>
@endpush
@endonce
