@extends('layouts.app')

@section('title', "Foto-mu sudah jadi — HOLD' MOMENT")
@section('meta-description', "Photostrip-mu sudah selesai. Cetak, unduh, simpan sebagai PDF, atau bagikan ke teman.")

@section('content')

<section class="mx-auto grid max-w-6xl items-start gap-12 px-6 pb-16 pt-12 md:grid-cols-2 page-enter">

    {{-- ================= Sisi kiri: hasil ================= --}}
    <div class="animate-rise">
        <div class="film-line w-28 opacity-60" aria-hidden="true"></div>
        <p class="py-2 font-serif text-sm font-extrabold tracking-[.18em]">HOLD' MOMENT</p>
        <div class="film-line w-28 opacity-60" aria-hidden="true"></div>

        <h1 class="app-title mt-8">
            Foto-mu sudah jadi!
        </h1>
        <p class="mt-4 text-brand-muted">Simpan atau bagikan hasil photostrip ini ke teman.</p>

        <figure class="print-area mt-8 inline-block rounded-4xl bg-white p-4 shadow-lift ring-1 ring-black/[.05]">
            <img src="{{ asset('storage/' . $photo->result_image_path) }}"
                 alt="Hasil photostrip {{ $photo->visitor_name }}"
                 class="mx-auto h-auto max-h-[460px] w-auto rounded-2xl">
            <figcaption class="mt-4 flex flex-wrap items-center justify-center gap-x-3 gap-y-1 border-t border-brand-line px-2 pt-3 text-xs text-brand-muted">
                <span class="font-semibold text-brand-text">{{ $photo->visitor_name }}</span>
                <span aria-hidden="true">•</span>
                <span>{{ $photo->visitor_social }}</span>
                <span aria-hidden="true">•</span>
                <time datetime="{{ $photo->created_at->toIso8601String() }}">{{ $photo->created_at->translatedFormat('d M Y · H:i') }}</time>
            </figcaption>
        </figure>
    </div>

    {{-- ================= Sisi kanan: aksi ================= --}}
    <div class="no-print animate-rise space-y-4" style="animation-delay:.1s">

        {{-- Cetak --}}
        <button type="button" onclick="window.print()"
                class="card card-hover group flex w-full items-center gap-5 p-6 text-left">
            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-brand-orange text-white transition-transform group-hover:scale-105" aria-hidden="true">
                <x-icon name="print" class="h-5 w-5" />
            </span>
            <span class="flex-1">
                <span class="block font-bold">Cetak Strip</span>
                <span class="block text-sm text-brand-muted">Cetak langsung dari halaman ini</span>
            </span>
            <x-icon name="arrow-right" class="h-5 w-5 text-brand-muted transition-transform group-hover:translate-x-1" />
        </button>

        {{-- Unduh --}}
        <div class="card p-6">
            <div class="flex items-center gap-5">
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-brand-orange-soft text-brand-orange" aria-hidden="true">
                    <x-icon name="download" class="h-5 w-5" />
                </span>
                <div>
                    <h2 class="font-bold">Simpan Foto</h2>
                    <p class="text-sm text-brand-muted">Unduh sebagai gambar resolusi penuh</p>
                </div>
            </div>
            <div class="mt-5 flex flex-wrap gap-2">
                <a href="{{ asset('storage/' . $photo->result_image_path) }}"
                   download="holdmoment-{{ $photo->id }}.png"
                   class="btn btn-dark btn-sm flex-1">
                    <x-icon name="download" class="h-4 w-4" />
                    Unduh PNG
                </a>
                <button type="button" onclick="downloadPDF(this)" id="btnPdf"
                        class="btn btn-outline btn-sm flex-1">
                    <x-icon name="image" class="h-4 w-4" />
                    Simpan PDF
                </button>
            </div>
        </div>

        {{-- Bagikan --}}
        <div class="card p-6">
            <div class="flex items-center gap-5">
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-brand-orange-soft text-brand-orange" aria-hidden="true">
                    <x-icon name="share" class="h-5 w-5" />
                </span>
                <div>
                    <h2 class="font-bold">Bagikan</h2>
                    <p class="text-sm text-brand-muted">Kirim ke teman atau media sosialmu</p>
                </div>
            </div>

            <div class="mt-5 flex items-center gap-2">
                {{-- !px-0 tidak perlu: .btn tanpa .btn-sm memang sudah tidak
                     memberi padding horizontal. --}}
                <button type="button" id="shareWA" class="btn btn-outline h-11 w-11 shrink-0 p-0"
                        aria-label="Bagikan ke WhatsApp">
                    <x-icon name="phone" class="h-4 w-4" />
                </button>
                <button type="button" id="shareNative" class="btn btn-outline h-11 w-11 shrink-0 p-0"
                        aria-label="Bagikan ke aplikasi lain">
                    <x-icon name="share" class="h-4 w-4" />
                </button>
                <button type="button" id="copyLink" class="btn btn-outline h-11 w-11 shrink-0 p-0"
                        aria-label="Salin tautan hasil">
                    <x-icon name="link" class="h-4 w-4" />
                </button>

            </div>
        </div>

        {{-- Navigasi --}}
        <div class="flex items-center gap-3 pt-2">
            <a href="{{ route('camera') }}" class="btn btn-outline btn-md flex-1">
                <x-icon name="arrow-left" class="h-4 w-4" />
                Ambil Ulang
            </a>
            <a href="{{ route('home') }}" class="btn btn-primary btn-md flex-1">Selesai</a>
        </div>
    </div>
</section>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js" defer></script>
<script>
(function () {
    const pageUrl = @json(route('photo.result', $photo->id));
    const imgUrl  = @json(asset('storage/' . $photo->result_image_path));
    const fileBase = 'holdmoment-{{ $photo->id }}';

    async function copy(text) {
        try {
            await navigator.clipboard.writeText(text);
            return true;
        } catch (e) {
            // Fallback untuk konteks non-HTTPS
            const ta = document.createElement('textarea');
            ta.value = text;
            ta.setAttribute('readonly', '');
            ta.style.position = 'fixed';
            ta.style.opacity = '0';
            document.body.appendChild(ta);
            ta.select();
            let ok = false;
            try { ok = document.execCommand('copy'); } catch (_) {}
            document.body.removeChild(ta);
            return ok;
        }
    }

    const shareText = "Lihat photostrip HOLD' MOMENT ku!";

    document.getElementById('shareWA').addEventListener('click', async () => {
        window.open('https://wa.me/?text=' + encodeURIComponent(shareText + ' ' + pageUrl), '_blank', 'noopener');
    });

    document.getElementById('shareNative').addEventListener('click', async () => {
        if (navigator.share) {
            try {
                await navigator.share({ title: "HOLD' MOMENT", text: shareText, url: pageUrl });
            } catch (err) { /* pengguna membatalkan */ }
        } else if (await copy(pageUrl)) {
            window.toast('Tautan disalin', 'success');
        } else {
            window.open(imgUrl, '_blank', 'noopener');
        }
    });

    document.getElementById('copyLink').addEventListener('click', async () => {
        window.toast(await copy(pageUrl) ? 'Tautan disalin!' : pageUrl, 'success');
    });

    window.downloadPDF = async function (btn) {
        // Simpan innerHTML (bukan textContent) supaya ikon + spinner bisa
        // ditambahkan tanpa kehilangan markup asli saat dikembalikan.
        const label = btn.innerHTML;
        const SPINNER = '<svg class="h-4 w-4 animate-hm-spin shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">'
            + '<circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2.5" opacity=".25"/>'
            + '<path d="M21 12a9 9 0 0 0-9-9" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg>';
        btn.disabled = true;
        btn.setAttribute('aria-busy', 'true');
        btn.innerHTML = SPINNER + ' Menyiapkan…';
        try {
            await new Promise((res, rej) => {
                if (window.jspdf) return res();
                let tries = 0;
                const t = setInterval(() => {
                    if (window.jspdf) { clearInterval(t); res(); }
                    else if (++tries > 40) { clearInterval(t); rej(new Error('jsPDF gagal dimuat')); }
                }, 100);
            });

            const img = new Image();
            img.crossOrigin = 'anonymous';
            img.src = imgUrl;
            await img.decode();

            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF({ unit: 'px', format: [img.width * 0.75, img.height * 0.75] });
            pdf.addImage(img, 'PNG', 0, 0, img.width * 0.75, img.height * 0.75);
            pdf.save(fileBase + '.pdf');
        } catch (e) {
            // Selalu beri jalan keluar: buka gambar langsung
            window.open(imgUrl, '_blank', 'noopener');
        } finally {
            btn.disabled = false;
            btn.removeAttribute('aria-busy');
            btn.innerHTML = label;
        }
    };
})();
</script>
@endpush

@endsection