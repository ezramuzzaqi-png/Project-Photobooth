@extends('layouts.app')

@section('title', "HOLD' MOMENT — Abadikan Momen Serumu")
@section('meta-description', "Photobooth online gratis. Jepret langsung dari browser, pilih template photostrip, terapkan filter, dan unduh hasilnya dalam hitungan detik.")

@section('content')

{{-- ============================ HERO ============================ --}}
<section class="safe-x relative overflow-hidden page-enter">
    {{-- Cahaya latar halus --}}
    <div class="pointer-events-none absolute -right-40 -top-40 h-[34rem] w-[34rem] rounded-full accent-haze blur-3xl" aria-hidden="true"></div>
    <div class="pointer-events-none absolute -left-32 top-24 h-96 w-96 rounded-full accent-haze blur-3xl" aria-hidden="true"></div>

    <div class="relative mx-auto grid max-w-6xl items-center gap-14 px-6 pb-20 pt-16 md:grid-cols-2 md:pt-24">

        {{-- Copy --}}
        <div class="animate-rise">
            <span class="badge">
                <span class="h-1.5 w-1.5 rounded-full bg-brand-orange" aria-hidden="true"></span>
                Photobooth Online · Tanpa Aplikasi
            </span>

            <h1 class="mt-6 text-balance font-serif text-[2.5rem] font-extrabold leading-[1.08] md:text-6xl">
                Abadikan Momen
                <span class="relative inline-block">
                    <span class="relative z-10 text-brand-orange">Serumu</span>
                    <span class="absolute inset-x-0 bottom-1 z-0 h-3 rounded-sm bg-brand-orange/20 md:bottom-2 md:h-4" aria-hidden="true"></span>
                </span>
                Bersama HOLD' MOMENT
            </h1>

            <p class="mt-6 max-w-lg text-lg leading-relaxed text-brand-muted">
                Foto langsung dari browser, pilih template photostrip favoritmu,
                terapkan filter, dan unduh hasilnya dalam hitungan detik.
            </p>

            <div class="mt-9 flex flex-wrap items-center gap-3">
                <a href="{{ route('camera') }}" class="btn btn-primary btn-lg">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5" aria-hidden="true">
                        <path d="M4 8h2.6L8.6 5.5h6.8L17.4 8H20A1.5 1.5 0 0 1 21.5 9.5V18a1.5 1.5 0 0 1-1.5 1.5H4A1.5 1.5 0 0 1 2.5 18V9.5A1.5 1.5 0 0 1 4 8z" stroke-linejoin="round"/>
                        <circle cx="12" cy="13.5" r="3.4"/>
                    </svg>
                    Coba Sekarang
                </a>
                <a href="{{ route('how-it-works') }}" class="btn btn-outline btn-lg">Lihat Cara Kerja</a>
            </div>

            <dl class="mt-10 flex flex-wrap items-center gap-x-8 gap-y-4 border-t border-brand-line pt-7">
                <div>
                    <dt class="text-2xs font-bold uppercase tracking-[.14em] text-brand-muted">Rating</dt>
                    <dd class="mt-1 flex items-center gap-1.5 text-lg font-bold">
                        <x-icon name="star" class="h-4 w-4 text-brand-orange" />
                        {{ $stats['rating'] }}
                    </dd>
                </div>
                <div>
                    <dt class="text-2xs font-bold uppercase tracking-[.14em] text-brand-muted">Foto Dibuat</dt>
                    <dd class="mt-1 text-lg font-bold">{{ number_format($stats['photos'], 0, ',', '.') }}+</dd>
                </div>
                <div>
                    <dt class="text-2xs font-bold uppercase tracking-[.14em] text-brand-muted">Tanpa Daftar</dt>
                    <dd class="mt-1 text-lg font-bold">Langsung Jepret</dd>
                </div>
            </dl>
        </div>

        {{-- Showcase photostrip: susunan bertumpuk, semua terkurung di dalam
             kolom (pos absolut) supaya tidak meluber keluar container. --}}
        <div class="relative mx-auto h-[38rem] w-full max-w-[22rem] animate-rise" style="animation-delay:.1s">
            @php $spots = [
                ['left' => '4%',  'top' => '0%',  'w' => '56%', 'tilt' => -6, 'z' => 1],
                ['left' => '26%', 'top' => '15%', 'w' => '60%', 'tilt' => 4,  'z' => 3],
                ['left' => '0%',  'top' => '42%', 'w' => '58%', 'tilt' => -3, 'z' => 2],
            ]; @endphp

            @forelse($showcase->take(3) as $i => $t)
                @php
                    $img = $t->background_image ?? $t->frame_image;
                    $s = $spots[$i];
                @endphp
                <figure class="absolute animate-float"
                        style="left:{{ $s['left'] }};top:{{ $s['top'] }};width:{{ $s['w'] }};z-index:{{ $s['z'] }};--tilt:{{ $s['tilt'] }}deg;animation-delay:{{ $i * 1.1 }}s">
                    <a href="{{ route('templates') }}"
                       class="card-interact block h-full overflow-hidden p-2.5 shadow-sm ring-1 ring-black/[.04] transition-all duration-300 group">
                        @if($img)
                            <img src="{{ asset('storage/' . $img) }}"
                                 alt="Contoh photostrip {{ $t->name }}"
                                 loading="lazy"
                                 class="aspect-[2/3] w-full rounded-2xl bg-brand-card-light object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="aspect-[2/3] w-full rounded-2xl bg-gradient-to-br from-brand-orange to-amber-200" role="img" aria-label="Contoh desain"></div>
                        @endif
                        <figcaption class="mt-2.5 flex items-center justify-between gap-2 px-1 pb-0.5">
                            <span class="truncate font-serif text-xs font-bold">{{ $t->name }}</span>
                            <span class="shrink-0 rounded-full bg-brand-card-light px-2 py-0.5 text-2xs font-bold text-brand-muted">{{ $t->layout_type }}</span>
                        </figcaption>
                    </a>
                </figure>
            @empty
                <div class="empty-state">
                    <p class="font-semibold">Belum ada desain tersedia</p>
                    <a href="{{ route('camera') }}" class="link-orange mt-2 inline-block">Mulai jepret sekarang</a>
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ============================ KEUNGGULAN ============================ --}}
<section class="border-y border-brand-line bg-white/60">
    <div class="mx-auto max-w-6xl px-6 py-20">
        <div class="mx-auto max-w-2xl text-center">
            <p class="eyebrow">Kenapa HOLD' MOMENT?</p>
            <h2 class="section-title mt-3 text-balance">Semua yang kamu butuh, dalam satu halaman</h2>
        </div>

        <div class="mt-12 grid gap-5 md:grid-cols-3">
            @foreach ([
                ['spark', 'Tanpa aplikasi tambahan', 'Cukup buka browser, izinkan kamera, langsung jepret. Tidak perlu install apa pun.'],
                ['grid', 'Template beragam', 'Format 2x2 dan 2x3 dengan frame overlay yang bisa disesuaikan admin.'],
                ['bolt', 'Hasil instan', 'Cetak, simpan sebagai foto atau PDF, atau bagikan ke media sosial sekali klik.'],
            ] as [$icon, $title, $desc])
                <article class="card card-hover p-8 group">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-orange-soft text-brand-orange group-hover:scale-110 transition-transform duration-200">
                        <x-icon :name="$icon" class="h-6 w-6 group-hover:rotate-12 transition-transform duration-200" />
                    </div>
                    <h3 class="mt-5 text-lg font-bold group-hover:text-brand-orange transition-colors">{{ $title }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-brand-muted">{{ $desc }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================ CARA KERJA SINGKAT ============================ --}}
<section class="mx-auto max-w-6xl px-6 py-20">
    <div class="grid items-center gap-12 md:grid-cols-[1fr_1.1fr]">
        <div>
            <p class="eyebrow">Tiga langkah saja</p>
            <h2 class="section-title mt-3 text-balance">Dari senyum sampai jadi, tanpa ribet</h2>
            <a href="{{ route('how-it-works') }}" class="link-orange mt-6 inline-flex items-center gap-1.5 text-sm">
                Lihat panduan lengkap
                <x-icon name="arrow-right" class="h-4 w-4" />
            </a>
        </div>

        <ol class="grid gap-4">
            @foreach ([
                ['01', 'Isi biodata', 'Cukup nama dan username media sosialmu.'],
                ['02', 'Jepret & atur', 'Pilih format, filter, dan timer sesuai ritme yang kamu mau.'],
                ['03', 'Simpan & bagikan', 'Unduh PNG, cetak, atau kirim ke teman.'],
            ] as [$num, $title, $desc])
                <li class="card flex items-start gap-5 p-6">
                    <span class="font-serif text-2xl font-extrabold text-brand-orange/30">{{ $num }}</span>
                    <div>
                        <h3 class="font-bold">{{ $title }}</h3>
                        <p class="mt-1 text-sm text-brand-muted">{{ $desc }}</p>
                    </div>
                </li>
            @endforeach
        </ol>
    </div>
</section>

{{-- ============================ CTA ============================ --}}
<section class="safe-x mx-auto max-w-6xl px-6 pb-8">
    <div class="relative overflow-hidden rounded-4xl bg-brand-panel px-8 py-16 text-center text-white md:py-20">
        <div class="pointer-events-none absolute -left-24 -top-24 h-72 w-72 rounded-full accent-haze blur-3xl" aria-hidden="true"></div>
        <div class="relative">
            <div class="film-line mx-auto w-32 opacity-50" aria-hidden="true"></div>
            <h2 class="mt-6 text-balance font-serif text-3xl font-extrabold md:text-5xl">
                Siap membuat kenangan pertamamu?
            </h2>
            <p class="mt-4 text-white/75">Gratis, tanpa signup, langsung dari browsermu.</p>
            <a href="{{ route('camera') }}" class="btn btn-primary btn-lg mt-9">
                Mulai Sekarang
                <x-icon name="arrow-right" class="h-5 w-5" />
            </a>
        </div>
    </div>
</section>

@endsection