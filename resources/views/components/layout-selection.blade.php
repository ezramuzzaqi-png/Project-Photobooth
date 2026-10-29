{{-- Halaman pemilihan layout: header gradien oranye pastel, tab kategori,
     carousel kartu portrait + badge, Alpine.js untuk filter & navigasi.
     Pakai: <x-layout-selection :templates="$templates" />
     Kategori diturunkan dari data yang ada (tanpa migrasi):
       Classic Stripe  = layout 2x2 / 2x3
       Studio          = punya frame overlay
       Filter Booths   = punya background
       Special Formats = receipt / strip_1x4 --}}
@props(['templates' => []])
@php
    $photoCount = fn($t) => is_array($t->slots ?? null) && isset($t->slots['holes'])
        ? count($t->slots['holes'])
        : (['2x2' => 4, '2x3' => 6, 'receipt' => 4, 'strip_1x4' => 4][$t->layout_type] ?? 4);
    $sizeText = [
        '2x2' => '5 × 3.5 in • 2×2 grid',
        '2x3' => '6 × 2 in strip • 2×3 grid',
        'receipt' => '3 × 8 in • twin receipt',
        'strip_1x4' => '6 × 2 in strip • 1×4',
    ];
    $catOf = function ($t) {
        // Disesuaikan strip yang ada: Classic = strip 2x2/2x3 JADI (ada
        // background-nya); frame transparan murni masuk Studio; background
        // masuk Filter Booths; receipt/strip_1x4 masuk Special Formats.
        $c = [];
        if (in_array($t->layout_type, ['2x2', '2x3']) && $t->background_image) $c[] = 'Classic Stripe';
        if ($t->frame_image) $c[] = 'Studio';
        if ($t->background_image) $c[] = 'Filter Booths';
        if (in_array($t->layout_type, ['receipt', 'strip_1x4'])) $c[] = 'Special Formats';
        return $c;
    };
    $tabs = ['All', 'Classic Stripe', 'Studio', 'Filter Booths', 'Special Formats'];
    $counts = ['All' => count($templates)];
    foreach (array_slice($tabs, 1) as $tab) {
        $counts[$tab] = collect($templates)->filter(fn($t) => in_array($tab, $catOf($t)))->count();
    }
    $isNew = fn($t) => $t->created_at && $t->created_at->gt(now()->subDays(30));
@endphp
<div x-data="{ tab: 'All', scrollList(dir) { this.$refs.track.scrollBy({ left: dir * 300, behavior: 'smooth' }); } }">
    {{-- Header gradien oranye pastel (selaras brand) --}}
    <div>
        <section class="max-w-6xl mx-auto px-6 pt-14 pb-10 text-center">
            <p class="inline-block bg-white rounded-full px-4 py-1.5 text-xs font-semibold tracking-widest text-brand-orange shadow-card">LAYOUT COLLECTION</p>
            <h1 class="font-serif font-light text-4xl md:text-6xl mt-4">choose your layout</h1>
            <p class="mt-3 text-brand-muted text-sm md:text-base">Select from our collection of photo booth layouts</p>

            {{-- Tab kategori --}}
            <div class="mt-8 flex flex-wrap justify-center gap-2">
                @foreach($tabs as $c)
                    <button
                        @click="tab = '{{ $c }}'"
                        :class="tab === '{{ $c }}' ? 'bg-brand-orange border-brand-orange text-white shadow-card' : 'bg-white border-brand-line text-brand-text hover:border-brand-orange'"
                        class="rounded-full border px-5 py-2 text-sm font-semibold transition">
                        {{ $c }} <span class="opacity-70">({{ $counts[$c] }})</span>
                    </button>
                @endforeach
            </div>
        </section>
    </div>

    {{-- Carousel kartu --}}
    <section class="max-w-6xl mx-auto px-6 py-10">
        @if(count($templates) === 0)
            <div class="bg-white rounded-3xl p-10 shadow-card text-center text-sm text-brand-muted">
                Belum ada template. Admin bisa menambah lewat panel admin → Template.
            </div>
        @else
            <div class="relative">
                <div x-ref="track" class="flex gap-5 overflow-x-auto snap-x snap-mandatory scroll-smooth pb-4 pt-1 px-1">
                    @foreach($templates as $t)
                        @php
                            $cats = $catOf($t);
                            $n = $photoCount($t);
                            $badge = $isNew($t) ? 'NEW LAYOUT!' : ($t->background_image ? 'FILTER LAYOUT' : 'TEMPLATE LAYOUT');
                            $badgeClass = $isNew($t)
                                ? 'bg-brand-orange text-white'
                                : ($t->background_image ? 'bg-white/90 text-brand-orange border border-brand-orange/30' : 'bg-brand-dark text-white');
                        @endphp
                        <article x-show="tab === 'All' || '{{ implode(',', $cats) }}'.split(',').includes(tab)" class="snap-start shrink-0 w-60 md:w-72">
                            <a href="{{ route('camera') }}" class="block bg-white rounded-3xl p-3 shadow-card hover:shadow-lift transition h-full">
                                <div class="relative">
                                    <span class="absolute top-2 left-2 z-10 rounded-full px-2.5 py-1 text-[10px] font-bold tracking-wide {{ $badgeClass }}">{{ $badge }}</span>
                                    @if($t->background_image)
                                        <img src="{{ asset('storage/' . $t->background_image) }}" alt="{{ $t->name }}" class="rounded-2xl h-80 md:h-96 w-full object-cover bg-brand-card-light" loading="lazy">
                                    @elseif($t->frame_image)
                                        <div class="rounded-2xl h-80 md:h-96 bg-brand-dark flex items-center justify-center p-4">
                                            <img src="{{ asset('storage/' . $t->frame_image) }}" alt="{{ $t->name }}" class="max-h-full w-auto object-contain" loading="lazy">
                                        </div>
                                    @elseif($t->layout_type === 'receipt')
                                        <img src="{{ asset('images/receipt-thumb.png') }}" alt="Receipt" class="rounded-2xl h-80 md:h-96 w-full object-cover bg-brand-card-light" loading="lazy">
                                    @else
                                        <div class="rounded-2xl h-80 md:h-96 bg-brand-card-light flex items-center justify-center text-brand-muted text-xs font-bold">Tanpa gambar</div>
                                    @endif
                                </div>
                                <p class="font-bold mt-3 text-sm px-1">{{ $t->name }} - {{ $n }} Photos</p>
                                <p class="text-xs text-brand-muted px-1 pb-1">{{ $sizeText[$t->layout_type] ?? $t->layout_type }} • {{ $n }} photos</p>
                            </a>
                        </article>
                    @endforeach
                </div>

                {{-- Panah navigasi --}}
                <button @click="scrollList(-1)" title="Sebelumnya"
                    class="hidden md:flex absolute -left-5 top-40 w-11 h-11 rounded-full bg-white shadow-card border border-black/5 items-center justify-center text-brand-text hover:bg-brand-orange hover:text-white hover:border-brand-orange transition">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M14.5 6L8.5 12l6 6"/></svg>
                </button>
                <button @click="scrollList(1)" title="Berikutnya"
                    class="hidden md:flex absolute -right-5 top-40 w-11 h-11 rounded-full bg-white shadow-card border border-black/5 items-center justify-center text-brand-text hover:bg-brand-orange hover:text-white hover:border-brand-orange transition">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M9.5 6l6 6-6 6"/></svg>
                </button>
            </div>

            {{-- Panah mobile + CTA --}}
            <div class="mt-2 flex md:hidden justify-center gap-3">
                <button @click="scrollList(-1)" class="w-11 h-11 rounded-full bg-white shadow border border-black/5 flex items-center justify-center" title="Sebelumnya">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M14.5 6L8.5 12l6 6"/></svg>
                </button>
                <button @click="scrollList(1)" class="w-11 h-11 rounded-full bg-white shadow border border-black/5 flex items-center justify-center" title="Berikutnya">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M9.5 6l6 6-6 6"/></svg>
                </button>
            </div>
            <div class="mt-8 text-center">
                <a href="{{ route('camera') }}" class="inline-block bg-brand-orange hover:bg-brand-orange-hover text-white rounded-full px-10 py-3 font-semibold transition">Mulai Foto Sekarang</a>
            </div>
        @endif
    </section>
</div>
