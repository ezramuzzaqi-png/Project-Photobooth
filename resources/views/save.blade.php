@extends('layouts.app')

@section('title', "Foto-mu sudah jadi! — HOLD' MOMENT")

@section('content')
<section class="max-w-6xl mx-auto px-6 pt-10 pb-14 grid md:grid-cols-2 gap-10 items-start">
    {{-- Sisi Kiri --}}
    <div class="pt-6">
        <p class="font-serif font-extrabold tracking-widest text-sm">HOLD' MOMENT</p>
        <h1 class="font-serif font-extrabold text-5xl md:text-6xl mt-4 leading-tight">Foto-mu sudah jadi!</h1>
        <p class="mt-4 font-bold">Pilih opsi untuk menyimpan kenangan.</p>
        <div class="mt-6 bg-white rounded-3xl p-4 shadow-sm inline-block">
            <img src="{{ asset('storage/' . $photo->result_image_path) }}" alt="Hasil photostrip {{ $photo->visitor_name }}" class="rounded-2xl max-h-[420px] w-auto mx-auto">
            <p class="text-center text-xs text-brand-muted mt-3">{{ $photo->visitor_name }} • {{ $photo->visitor_social }} • {{ $photo->created_at->format('d M Y H:i') }}</p>
        </div>
    </div>

    {{-- Sisi Kanan: kartu melayang --}}
    <div class="bg-white rounded-3xl p-8 shadow-2xl">
        <p class="font-bold text-lg mb-4">Pilih Tindakan</p>

        <button onclick="window.print()" class="w-full text-left bg-brand-card-light rounded-2xl p-4 flex items-center space-x-4 mb-3 hover:bg-[#e6e1d8] transition">
            <span class="w-11 h-11 rounded-full bg-brand-orange text-white flex items-center justify-center text-xl shrink-0">⎙</span>
            <span>
                <span class="block font-bold">Cetak Strip</span>
                <span class="block text-sm text-brand-muted">Cetak langsung dari sini!</span>
            </span>
        </button>

        <div class="bg-brand-card-light rounded-2xl p-4 flex items-center space-x-4 mb-3">
            <span class="w-11 h-11 rounded-full bg-brand-orange text-white flex items-center justify-center text-xl shrink-0">⬇</span>
            <span class="flex-1">
                <span class="block font-bold">Simpan foto</span>
                <span class="block text-sm text-brand-muted">Simpan sebagai foto atau PDF</span>
            </span>
        </div>
        <div class="px-4 pb-2 flex gap-2">
            <a href="{{ asset('storage/' . $photo->result_image_path) }}" download="holdmoment-{{ $photo->id }}.png" class="flex-1 text-center bg-brand-dark text-white rounded-full py-2 text-sm font-semibold hover:bg-black transition">Unduh PNG</a>
            <button onclick="downloadPDF()" class="flex-1 bg-white border border-gray-300 rounded-full py-2 text-sm font-semibold hover:bg-gray-100 transition">Simpan PDF</button>
        </div>

        <div class="bg-brand-card-light rounded-2xl p-4 mb-6 mt-3">
            <div class="flex items-center space-x-4">
                <span class="w-11 h-11 rounded-full bg-brand-orange text-white flex items-center justify-center text-xl shrink-0">↗</span>
                <span>
                    <span class="block font-bold">Bagikan ke media sosial</span>
                    <span class="block text-sm text-brand-muted">Langsung dari sini</span>
                </span>
            </div>
            <div class="mt-3 flex items-center gap-2">
                <a id="shareWA" href="#" target="_blank" class="w-10 h-10 bg-white rounded-full flex items-center justify-center font-bold hover:bg-brand-orange hover:text-white transition" title="WhatsApp">✆</a>
                <a id="shareIG" href="#" class="w-10 h-10 bg-white rounded-full flex items-center justify-center font-bold hover:bg-brand-orange hover:text-white transition" title="Instagram">◎</a>
                <button id="copyLink" class="w-10 h-10 bg-white rounded-full flex items-center justify-center hover:bg-brand-orange hover:text-white transition" title="Copy link">⛓</button>
                <span id="copyMsg" class="text-xs text-green-700 hidden">Link disalin!</span>
            </div>
        </div>

        <div class="flex items-center justify-between gap-3">
            <a href="{{ route('camera') }}" class="border border-gray-400 text-gray-700 rounded-full px-6 py-2.5 hover:bg-gray-100 transition font-semibold text-sm">← Ambil Ulang</a>
            <a href="{{ route('home') }}" class="bg-brand-orange text-white rounded-full px-12 py-2.5 font-semibold hover:bg-brand-orange-hover transition text-sm">Selesai</a>
        </div>
    </div>
</section>

@push('scripts')
<script>
    const pageUrl = @json(route('photo.result', $photo->id));
    const imgUrl = @json(asset('storage/' . $photo->result_image_path));
    document.getElementById('shareWA').href = 'https://wa.me/?text=' + encodeURIComponent('Lihat photostrip HOLD MOMENT ku! ' + pageUrl);
    document.getElementById('shareIG').href = imgUrl;
    document.getElementById('copyLink').addEventListener('click', async () => {
        try {
            await navigator.clipboard.writeText(pageUrl);
            document.getElementById('copyMsg').classList.remove('hidden');
        } catch (e) { alert(pageUrl); }
    });
    async function downloadPDF() {
        const { jsPDF } = window.jspdf || {};
        if (!jsPDF) {
            // fallback: buka gambar, user Save as PDF via print
            window.open(imgUrl, '_blank');
            return;
        }
    }
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
    async function downloadPDF() {
        try {
            const img = new Image();
            img.crossOrigin = 'anonymous';
            img.src = imgUrl;
            await img.decode();
            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF({ unit: 'px', format: [img.width * 0.75, img.height * 0.75] });
            pdf.addImage(img, 'PNG', 0, 0, img.width * 0.75, img.height * 0.75);
            pdf.save('holdmoment-{{ $photo->id }}.pdf');
        } catch (e) { window.open(imgUrl, '_blank'); }
    }
</script>
@endpush
@endsection
