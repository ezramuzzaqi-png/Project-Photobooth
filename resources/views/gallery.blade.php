@extends('layouts.app')

@section('title', "Galeri Template — HOLD' MOMENT")

@section('content')
<section class="max-w-6xl mx-auto px-6 pt-12 pb-14">
    <p class="inline-block bg-white rounded-full px-4 py-1.5 text-xs font-semibold tracking-wide text-brand-orange shadow-sm">KOLEKSI DESAIN</p>
    <h1 class="font-serif font-extrabold text-4xl md:text-5xl mt-4">Galeri Template</h1>
    <p class="mt-3 text-brand-muted max-w-xl text-sm">Pilih desain photostrip favoritmu, lalu tekan pakai untuk langsung membuka kamera dengan desain tersebut.</p>

    @if($templates->isEmpty())
        <div class="mt-10 bg-white rounded-3xl p-10 shadow-sm text-center text-sm text-brand-muted">
            Belum ada template. Admin bisa menambah lewat panel admin → Template.
        </div>
    @else
        <div class="mt-10 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            @foreach($templates as $t)
                @php $img = $t->background_image ?? $t->frame_image; @endphp
                <div class="bg-white rounded-3xl p-3 shadow-sm hover:shadow-xl transition flex flex-col">
                    @if($img)
                        <img src="{{ asset('storage/' . $img) }}" alt="{{ $t->name }}" class="rounded-2xl h-96 w-full object-contain bg-brand-card-light" loading="lazy">
                    @else
                        <div class="rounded-2xl h-96 bg-brand-card-light flex items-center justify-center text-brand-muted text-xs font-bold">Tanpa gambar</div>
                    @endif
                    <p class="font-bold mt-3 text-sm px-1">{{ $t->name }}</p>
                    <p class="text-xs text-brand-muted px-1">Format {{ $t->layout_type }}</p>
                    <a href="{{ route('camera') }}" class="mt-3 mb-1 bg-brand-orange hover:bg-brand-orange-hover text-white rounded-full py-2 text-sm font-semibold text-center transition">Pakai desain ini →</a>
                </div>
            @endforeach
        </div>
    @endif
</section>
@endsection
