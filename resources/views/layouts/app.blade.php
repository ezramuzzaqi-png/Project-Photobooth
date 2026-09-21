<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', "HOLD' MOMENT — Photobooth Online") </title>

    <!-- Google Fonts: Playfair Display (serif) + Plus Jakarta Sans (sans) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS via CDN (langsung jalan tanpa build) + konfigurasi brand -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-bg': '#F6F4EE',
                        'brand-orange': '#D95B32',
                        'brand-orange-hover': '#C44E27',
                        'brand-dark': '#1C1C1C',
                        'brand-card-light': '#EFECE6',
                        'brand-text': '#1A1A1A',
                        'brand-muted': '#737373',
                    },
                    fontFamily: {
                        serif: ['"Playfair Display"', 'Georgia', 'serif'],
                        sans: ['"Plus Jakarta Sans"', 'Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    {{-- Untuk production: jalankan npm run build lalu uncomment baris vite di bawah --}}
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', Inter, sans-serif; }
        .film-line { background-image: repeating-linear-gradient(to right, currentColor 0 8px, transparent 8px 14px); height: 3px; }
    </style>
    @stack('styles')
</head>
<body class="bg-brand-bg text-brand-text font-sans antialiased min-h-screen">
    @include('components.navbar')

    <main>
        @yield('content')
    </main>

    <footer class="mt-20 border-t border-black/10">
        <div class="max-w-6xl mx-auto px-6 py-8 flex flex-col md:flex-row items-center justify-between gap-3 text-sm text-brand-muted">
            <p class="font-serif font-bold text-brand-text tracking-wide">HOLD' MOMENT</p>
            <p>&copy; {{ date('Y') }} HOLD' MOMENT — Abadikan momen serumu dari browser.</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
