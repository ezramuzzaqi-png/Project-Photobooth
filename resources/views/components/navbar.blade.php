@php
    $links = [
        ['route' => 'home',         'label' => 'Beranda',    'match' => 'home'],
        ['route' => 'how-it-works', 'label' => 'Cara Kerja', 'match' => 'how-it-works'],
        ['route' => 'templates',    'label' => 'Galeri',     'match' => 'templates'],
        ['route' => 'about',        'label' => 'Tentang',    'match' => 'about'],
    ];
@endphp

<header x-data="{ open: false, scrolled: false }"
        @scroll.window="scrolled = window.scrollY > 10"
        class="safe-x sticky top-0 z-40 border-b border-brand-line bg-brand-bg/90 backdrop-blur supports-[backdrop-filter]:bg-brand-bg/70
               transition-all duration-300"
        :class="scrolled ? 'shadow-sm' : ''">
    <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-6 py-4">

        {{-- Logo: motif pita film retro --}}
        <a href="{{ route('home') }}" class="group shrink-0 text-brand-text" aria-label="HOLD' MOMENT — Beranda">
            <div class="film-line w-full opacity-50 transition-opacity group-hover:opacity-90"></div>
            <p class="py-1.5 font-serif text-lg font-extrabold tracking-[.16em] md:text-xl">HOLD' MOMENT</p>
            <div class="film-line w-full opacity-50 transition-opacity group-hover:opacity-90"></div>
        </a>

        {{-- Navigasi desktop --}}
        <nav class="hidden items-center gap-1 rounded-full bg-white px-3 py-1.5 shadow-card ring-1 ring-black/[.04] md:flex" aria-label="Navigasi utama">
            @foreach ($links as $l)
                <a href="{{ route($l['route']) }}"
                   @class([
                       'nav-link',
                       'nav-link-active' => request()->routeIs($l['match']),
                   ])
                   @if(request()->routeIs($l['match'])) aria-current="page" @endif>
                    {{ $l['label'] }}
                </a>
            @endforeach

            <span class="mx-1 h-4 w-px bg-brand-line"></span>

            {{-- State aktif ditangani di app.css lewat [aria-current="page"].btn-primary
     supaya tidak bertabrakan dengan bg yang sudah diset .btn-primary. --}}
            <a href="{{ route('camera') }}"
               class="btn btn-primary btn-sm ml-1"
               @if(request()->routeIs('camera')) aria-current="page" @endif>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4" aria-hidden="true">
                    <path d="M4 8h2.6L8.6 5.5h6.8L17.4 8H20A1.5 1.5 0 0 1 21.5 9.5V18a1.5 1.5 0 0 1-1.5 1.5H4A1.5 1.5 0 0 1 2.5 18V9.5A1.5 1.5 0 0 1 4 8z" stroke-linejoin="round"/>
                    <circle cx="12" cy="13.5" r="3.4"/>
                </svg>
                Camera
            </a>
        </nav>

        {{-- Aksi akun (desktop) --}}
        <div class="hidden items-center gap-2 md:flex">
            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline btn-sm">Dashboard</a>
                @endif
                <span class="max-w-[10rem] truncate text-sm font-medium text-brand-muted" title="{{ auth()->user()->name }}">
                    {{ auth()->user()->name }}
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline btn-sm">Keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-ghost btn-sm">Masuk</a>
                <a href="{{ route('register') }}" class="btn btn-outline btn-sm">Daftar</a>
            @endauth
        </div>

        {{-- Tombol menu mobile --}}
        <button type="button"
                @click="open = !open"
                class="btn btn-outline btn-sm md:hidden"
                :aria-expanded="open ? 'true' : 'false'"
                aria-controls="mobile-nav"
                aria-label="Buka menu navigasi">
            <svg x-show="!open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5" aria-hidden="true">
                <path d="M4 7h16M4 12h16M4 17h16" stroke-linecap="round"/>
            </svg>
            <svg x-show="open" x-cloak viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5" aria-hidden="true">
                <path d="M6 6l12 12M18 6L6 18" stroke-linecap="round"/>
            </svg>
        </button>
    </div>

    {{-- Navigasi mobile --}}
    <nav id="mobile-nav" x-show="open" x-cloak x-transition.duration.200ms
         class="border-t border-brand-line bg-brand-bg px-6 py-4 md:hidden" aria-label="Navigasi mobile">
        <div class="grid gap-1">
            @foreach ($links as $l)
                <a href="{{ route($l['route']) }}"
                   @click="open = false"
                   @class([
                       'rounded-2xl px-4 py-3 text-sm font-semibold transition',
                       'bg-brand-orange text-white shadow-glow' => request()->routeIs($l['match']),
                       'text-brand-text hover:bg-brand-card-light' => !request()->routeIs($l['match']),
                   ])>
                    {{ $l['label'] }}
                </a>
            @endforeach

            <a href="{{ route('camera') }}"
               @click="open = false"
               @class([
                   'rounded-2xl px-4 py-3 text-sm font-semibold transition',
                   'bg-brand-orange text-white shadow-glow' => request()->routeIs('camera'),
                   'bg-brand-dark text-white' => !request()->routeIs('camera'),
               ])>
                Mulai Jepret
            </a>

            <div class="my-2 border-t border-brand-line"></div>

            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="rounded-2xl px-4 py-3 text-sm font-semibold text-brand-orange hover:bg-brand-card-light">
                        Dashboard Admin
                    </a>
                @endif
                <p class="px-4 pb-2 text-xs text-brand-muted">Masuk sebagai {{ auth()->user()->name }}</p>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full rounded-2xl border border-brand-line px-4 py-3 text-left text-sm font-semibold hover:bg-brand-card-light">
                        Keluar
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="rounded-2xl px-4 py-3 text-sm font-semibold hover:bg-brand-card-light">Masuk</a>
                <a href="{{ route('register') }}" class="rounded-2xl bg-brand-dark px-4 py-3 text-center text-sm font-semibold text-white">
                    Daftar Sekarang
                </a>
            @endauth
        </div>
    </nav>
</header>