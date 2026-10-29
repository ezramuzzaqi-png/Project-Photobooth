@extends('layouts.app')

@section('title', "Cara Kerja — HOLD' MOMENT")
@section('meta-description', "Lima langkah sederhana: isi biodata, atur strip dan filter, jepret, pilih desain, lalu cetak atau bagikan photostrip-mu.")

@section('content')

<section class="mx-auto max-w-3xl px-6 pb-10 pt-16 text-center page-enter">
    <p class="eyebrow">Cara Kerja</p>
    <h1 class="app-title mt-4">Lima langkah, lalu jadi</h1>
    <p class="mx-auto mt-4 max-w-xl text-brand-muted">
        Dari kamera sampai photostrip siap dibagikan — tanpa aplikasi tambahan.
    </p>
</section>

<section class="mx-auto max-w-3xl px-6 pb-8">
    <ol class="relative space-y-5">
        {{-- Garis konektor --}}
        <span class="absolute left-[27px] top-2 bottom-2 w-px bg-gradient-to-b from-brand-orange/40 via-brand-line to-transparent" aria-hidden="true"></span>

        @php
            $steps = [
                ['icon' => 'user', 'title' => 'Isi biodata', 'desc' => 'Cukup nama dan username media sosialmu. Dipakai sebagai label di photostrip hasil.'],
                ['icon' => 'sliders', 'title' => 'Atur strip, filter & timer', 'desc' => 'Pilih format 2x2 atau 2x3, pilih filter kamera, dan atur timer 3/5/10 detik lewat rail di sisi kiri.'],
                ['icon' => 'camera', 'title' => 'Jepret fotomu', 'desc' => 'Ikuti hitung mundur, lalu jepret. Preview strip terisi langsung di panel kanan.', 'badges' => ['4 foto untuk 2x2', '6 foto untuk 2x3', 'Retake foto terakhir kapan saja']],
                ['icon' => 'image', 'title' => 'Pilih desain strip', 'desc' => 'Galeri desain menyesuaikan jumlah fotomu. Terapkan frame pilihanmu ke strip.'],
            ];
        @endphp

        @foreach ($steps as $i => $s)
            <li class="relative flex items-start gap-5">
                <span class="relative z-10 flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-brand-orange text-white shadow-glow" aria-hidden="true">
                    <x-icon :name="$s['icon']" class="h-6 w-6" />
                </span>

                <div class="card flex-1 p-6 group">
                    <div class="flex items-baseline gap-2">
                        <span class="text-2xs font-bold tracking-[.16em] text-brand-orange group-hover:text-brand-orange-hover transition-colors">LANGKAH {{ $i + 1 }}</span>
                    </div>
                    <h2 class="mt-1 font-bold group-hover:text-brand-text transition-colors">{{ $s['title'] }}</h2>
                    <p class="mt-2 text-sm leading-relaxed text-brand-muted">{{ $s['desc'] }}</p>

                    @if (!empty($s['badges']))
                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach ($s['badges'] as $badge)
                                <span class="chip px-3 py-1 text-xs">{{ $badge }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </li>
        @endforeach

        {{-- Langkah terakhir --}}
        <li class="relative flex items-start gap-5">
            <span class="relative z-10 flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-brand-dark text-white shadow-lift" aria-hidden="true">
                <x-icon name="check" class="h-6 w-6" />
            </span>

            <div class="relative w-full flex-1 overflow-hidden rounded-3xl bg-brand-panel p-7 text-white shadow-lift">
                <div class="pointer-events-none absolute -right-12 -top-12 h-40 w-40 rounded-full accent-haze blur-3xl" aria-hidden="true"></div>

                <div class="relative">
                    <span class="text-2xs font-bold tracking-[.16em] text-brand-orange-bright">LANGKAH 5</span>
                    <h2 class="mt-1 font-serif text-xl font-extrabold">Strip-mu siap!</h2>

                    <div class="mt-5 grid grid-cols-3 gap-3 text-center text-sm">
                        @foreach ([['print', 'Cetak'], ['download', 'Simpan'], ['share', 'Bagikan']] as [$icon, $label])
                            <div class="flex flex-col items-center rounded-2xl bg-white/10 py-5 ring-1 ring-white/10">
                                <x-icon :name="$icon" class="h-6 w-6" />
                                <p class="mt-2 font-semibold">{{ $label }}</p>
                            </div>
                        @endforeach
                    </div>

                    <a href="{{ route('camera') }}" class="btn btn-primary btn-md mt-6">Coba Sekarang</a>
                </div>
            </div>
        </li>
    </ol>
</section>
@endsection