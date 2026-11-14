<header id="nav" class="fixed top-0 inset-x-0 z-50" data-navbar>
    <div class="max-w-[1300px] mx-auto px-1.5 lg:px-0 flex items-center justify-between h-[72px]">
      <a href="{{ url('/') }}" class="brand-lockup flex items-center group" aria-label="Devmatrixa home">
        <img src="{{ asset('main-logo.png') }}" alt="devmatrixa" width="192" height="64" class="brand-logo h-16 w-auto transition-transform duration-300 group-hover:scale-[1.02]">
        </a>

        <nav class="hidden md:flex items-center gap-1">
            <a href="{{ url('/') }}" class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 hover:bg-teal-50" style="color:var(--c-muted)">Home</a>
            <a href="{{ url('/#tools') }}" class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 hover:bg-teal-50" style="color:var(--c-muted)">Tools</a>
            <a href="{{ url('/#featured') }}" class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 hover:bg-teal-50" style="color:var(--c-muted)">Featured</a>
            <a href="{{ url('/about') }}" class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 hover:bg-teal-50" style="color:var(--c-muted)">About</a>
            <a href="{{ url('/contact') }}" class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 hover:bg-teal-50" style="color:var(--c-muted)">Contact</a>
        </nav>

        <div class="flex items-center gap-3">
            <a href="{{ url('/#tools') }}" class="hidden sm:inline-flex btn-primary px-5 py-2.5 rounded-full text-sm items-center gap-2">
                Explore Tools <i class="text-xs" data-lucide="arrow-right"></i>
            </a>
            <button type="button" data-mob-toggle aria-label="Open navigation" aria-expanded="false" aria-controls="mob" class="md:hidden p-2.5 rounded-xl transition-colors hover:bg-teal-50">
                <i class="text-sm" data-mob-icon-menu style="color:var(--c-muted)" data-lucide="menu"></i>
                <i class="text-sm hidden" data-mob-icon-close style="color:var(--c-muted)" data-lucide="x"></i>
            </button>
        </div>
    </div>

    <div id="mob" aria-hidden="true" inert>
        <button type="button" class="mob-backdrop" data-mob-close aria-label="Close navigation"></button>
        <nav id="mob-drawer" class="mob-drawer" role="dialog" aria-modal="true" aria-label="Mobile navigation">
            <div class="mob-drawer-header">
                <p class="mob-drawer-title">Explore</p>
                <button type="button" class="mob-drawer-close" data-mob-close aria-label="Close navigation">
                    <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" class="h-5 w-5">
                        <path d="m18 6-12 12M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="mob-drawer-links">
                <a href="{{ url('/') }}">Home</a>
                <a href="{{ url('/#tools') }}">Tools</a>
                <a href="{{ url('/#featured') }}">Featured</a>
                <a href="{{ url('/about') }}">About</a>
                <a href="{{ url('/contact') }}">Contact</a>
            </div>

            <a href="{{ url('/#tools') }}" class="mob-drawer-cta btn-primary inline-flex items-center justify-center gap-2.5 rounded-xl px-5 py-3.5 text-sm font-700">
                Explore Tools
                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                    <path d="M5 12h14M12 5l7 7-7 7" />
                </svg>
            </a>
        </nav>
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
  var closeButtons = mob.querySelectorAll('[data-mob-close]');
  var drawer = document.getElementById('mob-drawer');
  var open = false;
  var previousBodyOverflow = '';

  function onScroll() {
    nav.classList.toggle('scrolled', window.scrollY > 8);
  }

  function setOpen(isOpen) {
    open = isOpen;
    mob.classList.toggle('open', open);
    mob.setAttribute('aria-hidden', String(!open));
    mob.inert = !open;
    toggle.setAttribute('aria-expanded', String(open));
    toggle.setAttribute('aria-label', open ? 'Close navigation' : 'Open navigation');
    var menuIcon = nav.querySelector('[data-mob-icon-menu]');
    var closeIcon = nav.querySelector('[data-mob-icon-close]');
    if (menuIcon) menuIcon.classList.toggle('hidden', open);
    if (closeIcon) closeIcon.classList.toggle('hidden', !open);

    if (open) {
      previousBodyOverflow = document.body.style.overflow;
      document.body.style.overflow = 'hidden';
      window.setTimeout(function () {
        if (open) drawer.querySelector('button').focus();
      }, 50);
    } else {
      document.body.style.overflow = previousBodyOverflow;
      window.setTimeout(function () {
        if (!open) toggle.focus();
      }, 50);
    }
  }

  onScroll();
  window.addEventListener('scroll', onScroll, { passive: true });

  toggle.addEventListener('click', function () {
    setOpen(!open);
  });

  closeButtons.forEach(function (button) {
    button.addEventListener('click', function () {
      setOpen(false);
    });
  });

  drawer.querySelectorAll('a').forEach(function (a) {
    a.addEventListener('click', function () {
      setOpen(false);
    });
  });

  document.addEventListener('keydown', function (event) {
    if (!open) return;

    if (event.key === 'Escape') {
      setOpen(false);
      return;
    }

    if (event.key !== 'Tab') return;

    var focusable = Array.prototype.slice.call(drawer.querySelectorAll('a, button'));
    var first = focusable[0];
    var last = focusable[focusable.length - 1];

    if (event.shiftKey && document.activeElement === first) {
      event.preventDefault();
      last.focus();
    } else if (!event.shiftKey && document.activeElement === last) {
      event.preventDefault();
      first.focus();
    } else if (!drawer.contains(document.activeElement)) {
      event.preventDefault();
      first.focus();
    }
  });
})();
</script>
@endpush
@endonce
