@extends('layouts.app')

@section('title', "Tentang Kami — HOLD' MOMENT")
@section('meta-description', "Kenapa HOLD' MOMENT dibangun: photobooth yang ringan, gratis, dan bisa langsung dipakai dari browser tanpa instalasi apa pun.")

@section('content')

{{-- Hero --}}
<section class="safe-x relative overflow-hidden page-enter">
    <div class="pointer-events-none absolute -right-32 -top-32 h-[30rem] w-[30rem] rounded-full accent-haze blur-3xl" aria-hidden="true"></div>

    <div class="relative mx-auto max-w-4xl px-6 pb-16 pt-16 text-center">
        <p class="eyebrow">Tentang Kami</p>
        <h1 class="app-title mt-4 text-4xl md:text-6xl">
            Setiap senyum layak diabadikan
        </h1>
        <p class="mx-auto mt-6 max-w-2xl text-lg leading-relaxed text-brand-muted">
            Kami percaya setiap momen seru bersama teman dan keluarga layak disimpan dengan cara
            yang menyenangkan — cukup lewat browser, tanpa aplikasi tambahan, tanpa biaya.
        </p>
    </div>
</section>

{{-- Statistik (live dari database) --}}
<section class="mx-auto max-w-6xl px-6 pb-6">
    <div class="card grid divide-y divide-brand-line overflow-hidden sm:grid-cols-3 sm:divide-x sm:divide-y-0">
        @foreach ([
            [$stats['photos'], 'Foto diambil', 'Momen yang sudah diabadikan'],
            [$stats['users'], 'Pengguna', 'Yang sudah mencoba'],
            [$stats['rating'], 'Rating', 'Penilaian pengguna'],
        ] as [$value, $label, $hint])
            <div class="p-8 text-center group">
                <p class="font-serif text-5xl font-extrabold text-brand-orange group-hover:text-brand-orange-hover transition-colors">
                    {{ is_numeric($value) ? number_format($value, 0, ',', '.') : $value }}@if($label === 'Foto diambil' || $label === 'Pengguna')<span class="text-brand-orange/50">+</span>@endif
                </p>
                <p class="mt-2 font-semibold group-hover:text-brand-text transition-colors">{{ $label }}</p>
                <p class="mt-1 text-xs text-brand-muted">{{ $hint }}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- Nilai-nilai --}}
<section class="mx-auto max-w-6xl px-6 py-20">
    <div class="mx-auto max-w-2xl text-center">
        <p class="eyebrow">Yang Kami Juniper</p>
        <h2 class="section-title mt-3 text-balance">Dibuat sesederhana mungkin</h2>
    </div>

    <div class="mt-12 grid gap-5 md:grid-cols-3">
        @foreach ([
            ['bolt', 'Cepat', 'Tanpa instalasi, tanpa registrasi. Buka halaman, izinkan kamera, jepret.'],
            ['user', 'Private', 'Fotomu tetap di perangkatmu sampai kamu sendiri yang memutuskan kapan ingin membagikannya.'],
            ['spark', 'Ringan', 'Dibangun ringan supaya tetap lancar bahkan di HP dengan koneksi biasa.'],
        ] as [$icon, $title, $desc])
            <article class="card card-hover p-8">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-orange-soft text-brand-orange">
                    <x-icon :name="$icon" class="h-6 w-6" />
                </div>
                <h3 class="mt-5 text-lg font-bold">{{ $title }}</h3>
                <p class="mt-2 text-sm leading-relaxed text-brand-muted">{{ $desc }}</p>
            </article>
        @endforeach
    </div>
</section>

{{-- CTA --}}
<section class="safe-x mx-auto max-w-6xl px-6 pb-8">
    <div class="relative overflow-hidden rounded-4xl bg-brand-panel px-8 py-16 text-center text-white md:py-20">
        <div class="pointer-events-none absolute -left-24 -top-24 h-72 w-72 rounded-full accent-haze blur-3xl" aria-hidden="true"></div>
        <div class="relative">
            <div class="film-line mx-auto w-32 opacity-50" aria-hidden="true"></div>
            <h2 class="mt-6 text-balance font-serif text-3xl font-extrabold md:text-4xl">
                Siap membuat kenangan pertamamu?
            </h2>
            <p class="mt-3 text-white/75">Gratis dan langsung dari browsermu.</p>
            <a href="{{ route('camera') }}" class="btn btn-primary btn-lg mt-8">
                Mulai Sekarang
                <x-icon name="arrow-right" class="h-5 w-5" />
            </a>
        </div>
    </div>
</section>

@endsection