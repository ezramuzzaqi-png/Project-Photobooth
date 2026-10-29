<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', "HOLD' MOMENT — Photobooth Online") </title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="100x75" href="{{ asset('logo-hold-moment.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo-hold-moment.png') }}">

    <!-- Google Fonts: Playfair Display (serif) + Plus Jakarta Sans (sans) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@300;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Tailwind CSS + Alpine.js di-build lewat Vite (resources/css/app.css & resources/js/app.js) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="bg-brand-bg text-brand-text font-sans antialiased min-h-screen">
    @include('components.navbar')

    <main>
        @yield('content')
    </main>

    <footer class="mt-20 bg-brand-orange text-white">
        <div class="max-w-6xl mx-auto px-6 pt-12 pb-6 grid gap-10 md:grid-cols-[1.4fr_1fr_1fr]">
            <div>
                <div class="film-line w-40 opacity-70"></div>
                <p class="font-serif font-extrabold tracking-widest text-xl py-1">HOLD' MOMENT</p>
                <div class="film-line w-40 opacity-70"></div>
                <p class="text-sm text-white/80 mt-4 max-w-xs">Abadikan momen serumu langsung dari browser — tanpa aplikasi tambahan, hasil instan siap dibagikan.</p>
            </div>
            <div>
                <p class="text-xs font-bold tracking-widest text-white/70">JELAJAHI</p>
                <ul class="mt-4 space-y-2.5 text-sm font-medium">
                    <li><a href="{{ route('home') }}" class="hover:underline underline-offset-4 transition">Home</a></li>
                    <li><a href="{{ route('about') }}" class="hover:underline underline-offset-4 transition">About us</a></li>
                    <li><a href="{{ route('templates') }}" class="hover:underline underline-offset-4 transition">Galeri</a></li>
                </ul>
            </div>
            <div>
                <p class="text-xs font-bold tracking-widest text-white/70">PHOTOBOOTH</p>
                <ul class="mt-4 space-y-2.5 text-sm font-medium">
                    <li><a href="{{ route('camera') }}" class="hover:underline underline-offset-4 transition">Camera</a></li>
                    <li><a href="{{ route('how-it-works') }}" class="hover:underline underline-offset-4 transition">How it works</a></li>
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <li><a href="{{ route('admin.dashboard') }}" class="hover:underline underline-offset-4 transition">Admin Dashboard</a></li>
                        @endif
                    @else
                        <li><a href="{{ route('login') }}" class="hover:underline underline-offset-4 transition">Login</a></li>
                    @endauth
                </ul>
            </div>
        </div>
        <div class="max-w-6xl mx-auto px-6 pb-8">
            <div class="border-t border-white/25 pt-5 flex flex-col md:flex-row items-center justify-between gap-2 text-sm text-white/80">
                <p>&copy; {{ date('Y') }} HOLD' MOMENT. Seluruh hak cipta dilindungi.</p>
                <p>Abadikan momen serumu dari browser.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
