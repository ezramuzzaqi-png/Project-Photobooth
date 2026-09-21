@extends('layouts.app')

@section('title', "About us — HOLD' MOMENT")

@section('content')
<section class="max-w-4xl mx-auto px-6 pt-14 pb-8 text-center">
    <h1 class="font-serif font-extrabold text-5xl md:text-6xl">About us</h1>
    <p class="mt-6 text-brand-muted max-w-2xl mx-auto leading-relaxed">Kami percaya setiap momen seru bersama teman dan keluarga layak diabadikan dengan cara yang menyenangkan — cukup lewat browser, tanpa aplikasi tambahan.</p>

    {{-- Metrik Statistik --}}
    <div class="mt-12 grid grid-cols-1 sm:grid-cols-3 gap-8">
        <div>
            <p class="text-brand-orange font-bold text-5xl">100k++</p>
            <p class="mt-2 text-sm text-brand-muted">Foto diambil</p>
        </div>
        <div>
            <p class="text-brand-orange font-bold text-5xl">1M+</p>
            <p class="mt-2 text-sm text-brand-muted">Pengguna Aktif</p>
        </div>
        <div>
            <p class="text-brand-orange font-bold text-5xl">5/5</p>
            <p class="mt-2 text-sm text-brand-muted">Rating Pengguna</p>
        </div>
    </div>

    {{-- Kartu CTA Gelap --}}
    <div class="mt-14 bg-brand-dark rounded-3xl p-10 text-white text-center">
        <h2 class="font-serif font-bold text-2xl md:text-3xl">Siap buat kenangan pertama mu?</h2>
        <p class="mt-2 text-white/70 text-sm">Gratis dan langsung dari browsermu !</p>
        <a href="{{ route('camera') }}" class="inline-block mt-6 bg-brand-orange hover:bg-brand-orange-hover text-white rounded-full px-8 py-3 font-semibold transition">Mulai sekarang</a>
    </div>
</section>
@endsection
