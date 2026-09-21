@extends('layouts.app')

@section('title', "HOLD' MOMENT — Abadikan Momen Serumu")

@section('content')
{{-- Hero Section --}}
<section class="max-w-6xl mx-auto px-6 pt-12 pb-10 grid md:grid-cols-2 gap-10 items-center">
    <div>
        <p class="inline-block bg-white rounded-full px-4 py-1.5 text-xs font-semibold tracking-wide text-brand-orange shadow-sm mb-5">PHOTOBOOTH ONLINE • TANPA APLIKASI</p>
        <h1 class="font-serif font-extrabold text-4xl md:text-6xl leading-tight">Abadikan Momen Serumu Bersama <span class="text-brand-orange">HOLD' MOMENT</span></h1>
        <p class="mt-4 text-brand-muted max-w-md">Foto langsung dari browser, pilih template photostrip favoritmu, terapkan filter, dan unduh hasilnya dalam hitungan detik.</p>
        <div class="mt-7 flex items-center gap-3">
            <a href="{{ route('camera') }}" class="bg-brand-orange hover:bg-brand-orange-hover text-white rounded-full px-8 py-3 font-semibold transition">Coba Sekarang!</a>
            <a href="{{ route('how-it-works') }}" class="border border-brand-text/20 rounded-full px-6 py-3 font-semibold hover:bg-white transition">Cara kerja</a>
        </div>
        <div class="mt-6 flex items-center gap-4 text-sm text-brand-muted">
            <span>★ 5/5 rating</span>
            <span>•</span>
            <span>99M+ foto diambil</span>
        </div>
    </div>

    {{-- Preview Showcase Photostrip --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white rounded-3xl p-3 shadow-xl rotate-[-3deg]">
            <div class="space-y-2">
                <div class="rounded-2xl h-28 bg-gradient-to-br from-brand-orange to-amber-200"></div>
                <div class="rounded-2xl h-28 bg-brand-card-light"></div>
                <div class="rounded-2xl h-28 bg-brand-dark"></div>
            </div>
            <p class="text-center font-serif font-bold mt-3 text-sm">Classic 2x2</p>
        </div>
        <div class="bg-brand-dark text-white rounded-3xl p-3 shadow-xl mt-6">
            <div class="space-y-2">
                <div class="rounded-2xl h-20 bg-brand-orange"></div>
                <div class="rounded-2xl h-20 bg-white/20"></div>
                <div class="rounded-2xl h-20 bg-white/20"></div>
                <div class="rounded-2xl h-20 bg-white/20"></div>
            </div>
            <p class="text-center font-serif font-bold mt-3 text-sm">Classic 2x3</p>
        </div>
        <div class="bg-white rounded-3xl p-3 shadow-xl rotate-[3deg]">
            <div class="grid grid-cols-2 gap-2">
                <div class="rounded-xl h-24 bg-brand-card-light"></div>
                <div class="rounded-xl h-24 bg-brand-orange/70"></div>
                <div class="rounded-xl h-24 bg-brand-dark"></div>
                <div class="rounded-xl h-24 bg-gradient-to-br from-stone-300 to-brand-bg"></div>
            </div>
            <p class="text-center font-serif font-bold mt-3 text-sm">Retro 2x3</p>
        </div>
    </div>
</section>

{{-- Fitur Keunggulan --}}
<section class="max-w-6xl mx-auto px-6 py-10">
    <h2 class="font-serif font-bold text-2xl md:text-3xl text-center">Kenapa HOLD' MOMENT?</h2>
    <div class="mt-8 grid md:grid-cols-3 gap-5">
        <div class="bg-white rounded-3xl p-8 shadow-sm">
            <div class="w-12 h-12 rounded-full bg-brand-orange text-white flex items-center justify-center text-xl">✦</div>
            <h3 class="font-bold mt-4 text-lg">Tanpa aplikasi tambahan</h3>
            <p class="text-brand-muted mt-2 text-sm">Cukup buka browser, izinkan kamera, langsung jepret. Tidak perlu install apapun.</p>
        </div>
        <div class="bg-white rounded-3xl p-8 shadow-sm">
            <div class="w-12 h-12 rounded-full bg-brand-orange text-white flex items-center justify-center text-xl">▦</div>
            <h3 class="font-bold mt-4 text-lg">Pilihan template beragam</h3>
            <p class="text-brand-muted mt-2 text-sm">Format 2x2 dan 2x3 dengan frame overlay yang bisa dikustom admin.</p>
        </div>
        <div class="bg-white rounded-3xl p-8 shadow-sm">
            <div class="w-12 h-12 rounded-full bg-brand-orange text-white flex items-center justify-center text-xl">⚡</div>
            <h3 class="font-bold mt-4 text-lg">Hasil instan</h3>
            <p class="text-brand-muted mt-2 text-sm">Cetak, simpan sebagai foto/PDF, atau bagikan ke media sosial dalam sekali klik.</p>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="max-w-6xl mx-auto px-6 py-10">
    <div class="bg-brand-dark rounded-3xl p-10 text-white text-center">
        <h2 class="font-serif font-bold text-2xl md:text-4xl">Siap buat kenangan pertama mu?</h2>
        <p class="mt-2 text-white/70">Gratis dan langsung dari browsermu !</p>
        <a href="{{ route('camera') }}" class="inline-block mt-6 bg-brand-orange hover:bg-brand-orange-hover text-white rounded-full px-8 py-3 font-semibold transition">Mulai sekarang</a>
    </div>
</section>
@endsection
