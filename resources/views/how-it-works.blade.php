@extends('layouts.app')

@section('title', "How It Works — HOLD' MOMENT")

@section('content')
<section class="max-w-3xl mx-auto px-6 pt-14 pb-10">
    <h1 class="font-serif font-extrabold text-4xl md:text-5xl text-center">How it works</h1>
    <p class="text-center text-brand-muted mt-3">Lima langkah cepat dari kamera sampai photostrip siap dibagikan.</p>

    <div class="mt-12 relative">
        {{-- garis konektor kiri --}}
        <div class="absolute left-[27px] top-2 bottom-2 w-px bg-brand-text/15"></div>

        @php
            $steps = [
                ['icon' => 'user', 'title' => 'Isi biodata', 'desc' => 'Nama dan media sosial — tersimpan otomatis untuk kunjungan berikutnya', 'dark' => false],
                ['icon' => 'sliders', 'title' => 'Atur strip, filter & timer', 'desc' => 'Format 2x2 atau 2x3, 13 filter kamera, dan timer 3/5/10 detik lewat menu vertikal di sisi kiri', 'dark' => false],
                ['icon' => 'camera', 'title' => 'Jepret fotomu', 'desc' => null, 'dark' => false, 'badges' => ['4 foto untuk strip 2x2', '6 foto untuk strip 2x3', 'Kurang puas? Retake']],
                ['icon' => 'image', 'title' => 'Pilih desain strip', 'desc' => 'Otomatis pindah ke halaman desain — daftar menyesuaikan format fotomu, termasuk Receipt untuk 4 foto', 'dark' => false],
            ];
        @endphp

        <div class="space-y-6">
            @foreach($steps as $s)
                <div class="relative flex gap-5 items-start">
                    <div class="relative z-10 w-14 h-14 shrink-0 rounded-full {{ $s['dark'] ?? false ? 'bg-black' : 'bg-brand-orange' }} text-white flex items-center justify-center shadow"><x-icon :name="$s['icon']" class="w-6 h-6" /></div>
                    <div class="bg-white rounded-2xl px-6 py-4 shadow-sm flex-1">
                        <p class="font-bold">{{ $s['title'] }}</p>
                        @if(!empty($s['desc']))
                            <p class="text-sm text-brand-muted mt-1">{{ $s['desc'] }}</p>
                        @endif
                        @if(!empty($s['badges'] ?? []))
                            <div class="mt-2 flex flex-wrap gap-2">
                                @foreach($s['badges'] as $b)
                                    <span class="text-xs bg-brand-card-light rounded-full px-3 py-1">{{ $b }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach

            {{-- Step final: kartu hitam --}}
            <div class="relative flex gap-5 items-start">
                <div class="relative z-10 w-14 h-14 shrink-0 rounded-full bg-black text-white flex items-center justify-center shadow"><x-icon name="check" class="w-6 h-6" /></div>
                <div class="bg-brand-dark rounded-2xl p-6 text-white flex-1">
                    <p class="font-bold text-lg">Strip-mu siap!</p>
                    <div class="mt-4 grid grid-cols-3 gap-3 text-center text-sm">
                        <div class="bg-white/10 rounded-xl py-4 flex flex-col items-center">
                            <x-icon name="print" class="w-7 h-7" />
                            <p class="mt-1 font-semibold">Cetak</p>
                        </div>
                        <div class="bg-white/10 rounded-xl py-4 flex flex-col items-center">
                            <x-icon name="download" class="w-7 h-7" />
                            <p class="mt-1 font-semibold">Simpan</p>
                        </div>
                        <div class="bg-white/10 rounded-xl py-4 flex flex-col items-center">
                            <x-icon name="share" class="w-7 h-7" />
                            <p class="mt-1 font-semibold">Bagikan</p>
                        </div>
                    </div>
                    <a href="{{ route('camera') }}" class="inline-block mt-5 bg-brand-orange hover:bg-brand-orange-hover rounded-full px-6 py-2.5 font-semibold text-sm transition">Try it now!</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
