{{-- Header Navigation Component --}}
<header class="max-w-6xl mx-auto px-6 pt-6 flex items-center justify-between gap-4">
    {{-- Logo Kiri: pita/klip film retro --}}
    <a href="{{ route('home') }}" class="shrink-0 text-brand-text">
        <div class="film-line w-full opacity-70"></div>
        <p class="font-serif font-extrabold tracking-widest text-lg md:text-xl py-1">HOLD' MOMENT</p>
        <div class="film-line w-full opacity-70"></div>
    </a>

    {{-- Pill Menu Tengah --}}
    <nav class="hidden md:flex items-center bg-white rounded-full px-6 py-2 shadow-sm space-x-6 text-sm font-medium">
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-brand-orange underline underline-offset-4 decoration-2' : 'text-brand-text hover:text-brand-orange' }}">Home</a>
        <a href="{{ route('how-it-works') }}" class="{{ request()->routeIs('how-it-works') ? 'text-brand-orange underline underline-offset-4 decoration-2' : 'text-brand-text hover:text-brand-orange' }}">How it works</a>
        <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'text-brand-orange underline underline-offset-4 decoration-2' : 'text-brand-text hover:text-brand-orange' }}">About us</a>
        <a href="{{ route('camera') }}" class="{{ request()->routeIs('camera') ? 'text-brand-orange underline underline-offset-4 decoration-2' : 'text-brand-text hover:text-brand-orange' }}">Camera</a>
    </nav>

    {{-- Action Buttons Kanan --}}
    <div class="flex items-center gap-2">
        @auth
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="border border-brand-orange text-brand-orange rounded-full px-4 py-1.5 text-sm font-semibold hover:bg-brand-orange hover:text-white transition">Dashboard</a>
            @endif
            <span class="hidden sm:inline text-sm text-brand-muted">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="border border-brand-orange text-brand-orange rounded-full px-4 py-1.5 text-sm font-semibold hover:bg-brand-orange hover:text-white transition">Logout</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="border border-brand-orange text-brand-orange rounded-full px-4 py-1.5 text-sm font-semibold hover:bg-brand-orange hover:text-white transition">Login</a>
            <a href="{{ route('register') }}" class="border border-brand-orange text-brand-orange rounded-full px-4 py-1.5 text-sm font-semibold hover:bg-brand-orange hover:text-white transition">Sign up</a>
        @endauth
    </div>
</header>

{{-- Mobile menu sederhana --}}
<nav class="md:hidden max-w-6xl mx-auto px-6 mt-3">
    <div class="bg-white rounded-2xl px-5 py-3 shadow-sm flex flex-wrap gap-x-5 gap-y-2 text-sm font-medium">
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-brand-orange' : '' }}">Home</a>
        <a href="{{ route('how-it-works') }}" class="{{ request()->routeIs('how-it-works') ? 'text-brand-orange' : '' }}">How it works</a>
        <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'text-brand-orange' : '' }}">About us</a>
        <a href="{{ route('camera') }}" class="{{ request()->routeIs('camera') ? 'text-brand-orange' : '' }}">Camera</a>
    </div>
</nav>
