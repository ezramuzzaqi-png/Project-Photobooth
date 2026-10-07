@extends('layouts.app')

@section('title', "Camera — HOLD' MOMENT")

@section('content')
<section class="max-w-6xl mx-auto px-6 pt-10 pb-14" x-data>
    <h1 class="font-serif font-extrabold text-3xl md:text-4xl">Photobooth Camera</h1>
    <p class="text-brand-muted mt-2 text-sm">Isi biodata, atur format & filter, lalu jepret momen serumu.</p>

    <div class="mt-8 grid lg:grid-cols-[280px_1fr_280px] gap-6 items-start">
        {{-- Panel Kiri: Kontrol --}}
        <aside class="bg-white rounded-3xl p-6 shadow-sm space-y-6">
            <div>
                <p class="font-bold text-sm mb-3">Format Strip</p>
                <div class="grid grid-cols-2 gap-2" id="layoutPicker">
                    <button data-layout="2x2" class="layout-btn border border-brand-orange bg-brand-orange text-white rounded-xl px-2 py-2 text-sm font-semibold">2x2</button>
                    <button data-layout="2x3" class="layout-btn border border-gray-300 rounded-xl px-2 py-2 text-sm font-semibold hover:border-brand-orange">2x3</button>
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between mb-3">
                    <p class="font-bold text-sm">Desain Strip</p>
                    <span id="designCount" class="text-xs text-brand-muted"></span>
                </div>
                <div class="space-y-2 max-h-64 overflow-y-auto pr-1" id="designPicker">
                    <button data-template="" class="design-btn w-full flex items-center gap-3 border border-brand-orange bg-brand-orange/10 rounded-xl p-2 text-left hover:border-brand-orange transition">
                        <span class="w-10 h-12 shrink-0 rounded-lg bg-brand-card-light flex items-center justify-center text-brand-muted text-xs font-bold">—</span>
                        <span>
                            <span class="block text-sm font-bold">Polos</span>
                            <span class="block text-xs text-brand-muted">Cream bawaan</span>
                        </span>
                    </button>
                    <button data-template="receipt" data-layout="receipt" class="design-btn w-full flex items-center gap-3 border border-gray-300 rounded-xl p-2 text-left hover:border-brand-orange transition">
                        <span class="w-10 h-12 shrink-0 rounded-lg bg-white border border-black/10 flex flex-col items-center justify-center gap-[3px] px-2">
                            <span class="w-full h-[2px] bg-black/80"></span>
                            <span class="w-2/3 h-[2px] bg-black/50"></span>
                            <span class="w-full h-[2px] bg-black/50"></span>
                            <span class="w-1/2 h-[2px] bg-black/80"></span>
                        </span>
                        <span>
                            <span class="block text-sm font-bold">Receipt</span>
                            <span class="block text-xs text-brand-muted">2 strip kembar • gaya struk</span>
                        </span>
                    </button>
                    @forelse($templates as $t)
                        <button data-template="{{ $t->id }}" data-layout="{{ $t->layout_type }}" class="design-btn w-full flex items-center gap-3 border border-gray-300 rounded-xl p-2 text-left hover:border-brand-orange transition">
                            @if($t->background_image)
                                <img src="{{ asset('storage/' . $t->background_image) }}" alt="{{ $t->name }}" class="w-10 h-12 shrink-0 rounded-lg object-cover bg-brand-card-light border border-black/10" onerror="this.style.visibility='hidden'">
                            @elseif($t->frame_image)
                                <img src="{{ asset('storage/' . $t->frame_image) }}" alt="{{ $t->name }}" class="w-10 h-12 shrink-0 rounded-lg object-cover bg-brand-card-light border border-black/10" onerror="this.style.visibility='hidden'">
                            @else
                                <span class="w-10 h-12 shrink-0 rounded-lg bg-brand-card-light border border-black/10 flex items-center justify-center text-xs">—</span>
                            @endif
                            <span>
                                <span class="block text-sm font-bold">{{ $t->name }}</span>
                                <span class="block text-xs text-brand-muted">Format {{ $t->layout_type }} @if($t->background_image) • bg @endif @if($t->frame_image) • frame @endif</span>
                            </span>
                        </button>
                    @empty
                        <p class="text-xs text-brand-muted bg-brand-card-light rounded-xl p-3">Belum ada desain. Admin bisa upload lewat panel admin → Template.</p>
                    @endforelse
                </div>
            </div>
            <div>
                <p class="font-bold text-sm mb-3">Filter Kamera</p>
                <div class="grid grid-cols-2 gap-2 max-h-64 overflow-y-auto pr-1" id="filterPicker">
                    <button data-filter="none" class="filter-btn border border-brand-orange bg-brand-orange text-white rounded-xl px-2 py-2 text-sm font-semibold">Normal</button>
                    <button data-filter="grayscale(1)" class="filter-btn border border-gray-300 rounded-xl px-2 py-2 text-sm font-semibold hover:border-brand-orange">B&W</button>
                    <button data-filter="grayscale(1) contrast(1.5) brightness(0.9)" class="filter-btn border border-gray-300 rounded-xl px-2 py-2 text-sm font-semibold hover:border-brand-orange">Noir</button>
                    <button data-filter="sepia(0.8) contrast(1.1) brightness(0.95)" class="filter-btn border border-gray-300 rounded-xl px-2 py-2 text-sm font-semibold hover:border-brand-orange">Vintage</button>
                    <button data-filter="sepia(1)" class="filter-btn border border-gray-300 rounded-xl px-2 py-2 text-sm font-semibold hover:border-brand-orange">Sepia</button>
                    <button data-filter="sepia(0.35) saturate(1.6) contrast(1.05)" class="filter-btn border border-gray-300 rounded-xl px-2 py-2 text-sm font-semibold hover:border-brand-orange">Warm</button>
                    <button data-filter="sepia(0.55) saturate(2.2) hue-rotate(-25deg)" class="filter-btn border border-gray-300 rounded-xl px-2 py-2 text-sm font-semibold hover:border-brand-orange">Sunset</button>
                    <button data-filter="saturate(1.8) contrast(1.2)" class="filter-btn border border-gray-300 rounded-xl px-2 py-2 text-sm font-semibold hover:border-brand-orange">Vivid</button>
                    <button data-filter="sepia(0.35) hue-rotate(170deg) saturate(1.8)" class="filter-btn border border-gray-300 rounded-xl px-2 py-2 text-sm font-semibold hover:border-brand-orange">Cool</button>
                    <button data-filter="brightness(0.8) contrast(1.3) saturate(0.85)" class="filter-btn border border-gray-300 rounded-xl px-2 py-2 text-sm font-semibold hover:border-brand-orange">Moody</button>
                    <button data-filter="brightness(1.3) contrast(1.02)" class="filter-btn border border-gray-300 rounded-xl px-2 py-2 text-sm font-semibold hover:border-brand-orange">Bright</button>
                    <button data-filter="sepia(0.3) contrast(0.85) brightness(1.12) saturate(0.8)" class="filter-btn border border-gray-300 rounded-xl px-2 py-2 text-sm font-semibold hover:border-brand-orange">Faded</button>
                    <button data-filter="invert(1)" class="filter-btn border border-gray-300 rounded-xl px-2 py-2 text-sm font-semibold hover:border-brand-orange">Negatif</button>
                </div>
            </div>
            <div>
                <p class="font-bold text-sm mb-3">Delay Timer</p>
                <div class="grid grid-cols-3 gap-2" id="timerPicker">
                    <button data-timer="3" class="timer-btn border border-brand-orange bg-brand-orange text-white rounded-xl px-2 py-2 text-sm font-semibold">3s</button>
                    <button data-timer="5" class="timer-btn border border-gray-300 rounded-xl px-2 py-2 text-sm font-semibold hover:border-brand-orange">5s</button>
                    <button data-timer="10" class="timer-btn border border-gray-300 rounded-xl px-2 py-2 text-sm font-semibold hover:border-brand-orange">10s</button>
                </div>
            </div>
            <div>
                <p class="font-bold text-sm mb-3">Mirror</p>
                <button id="mirrorToggle" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm font-semibold hover:border-brand-orange">Mirror: OFF — preview normal</button>
                <p class="text-xs text-brand-muted mt-1">Aktifkan jika preview masih terbalik.</p>
            </div>
            <div class="bg-brand-card-light rounded-2xl p-4 text-xs text-brand-muted">
                <p><span class="font-bold text-brand-text" id="visitorLabel">Pengunjung: -</span></p>
                <p id="progressLabel" class="mt-1">0 / 4 foto terisi</p>
            </div>
        </aside>

        {{-- Tengah: Live Camera (tampil paling atas di HP) --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm max-lg:order-first">
            <style>
                #cameraBox:fullscreen { aspect-ratio: auto !important; width: 100%; height: 100%; border-radius: 0; background: #1C1C1C; }
                #cameraBox:fullscreen #webcam { width: 100%; height: 100%; }
                #cameraBox:fullscreen #countdownOverlay { font-size: 12rem; }
                #cameraBox:fullscreen #cameraHint { font-size: 1rem; }
                /* Overlay UI khusus fullscreen: sembunyi di mode normal */
                #fsUI { display: none; }
                #cameraBox:fullscreen #fsUI { display: flex; }
                #fsMenuBar { scrollbar-width: thin; }
                #fsMenuBar::-webkit-scrollbar { width: 6px; }
                #fsMenuBar::-webkit-scrollbar-thumb { background: rgba(255,255,255,.35); border-radius: 999px; }
                #fsUI #fsMenuBar, #fsUI #btnFsShutter { transition: opacity .25s ease, transform .15s ease, box-shadow .2s ease, background-color .15s ease; }
                #fsUI.fs-hidden-ui #fsMenuBar, #fsUI.fs-hidden-ui #btnFsShutter { opacity: 0; pointer-events: none; }
                #fsUI .fs-chip:active { transform: scale(.9); }
                #fsUI .fs-chip.fs-active { background: #D95B32; color: #fff; box-shadow: 0 0 0 2px rgba(255,255,255,.9), 0 8px 18px rgba(217,91,50,.55); transform: translateY(-2px); }
                #btnFsShutter { box-shadow: 0 10px 28px rgba(0,0,0,.55), 0 0 0 4px rgba(255,255,255,.9); }
                #btnFsShutter:active { transform: scale(.88); }
                #btnFsShutter.fs-capturing { animation: fsPulse 1s ease-out infinite; pointer-events: none; }
                @keyframes fsPulse { 0% { box-shadow: 0 0 0 0 rgba(217,91,50,.75), 0 0 0 4px rgba(255,255,255,.9); } 70% { box-shadow: 0 0 0 26px rgba(217,91,50,0), 0 0 0 4px rgba(255,255,255,.9); } 100% { box-shadow: 0 0 0 0 rgba(217,91,50,0), 0 0 0 4px rgba(255,255,255,.9); } }
                #captureFlash { transition: opacity .35s ease; }
                /* Tombol fullscreen mini gaya Youtube: muncul saat hover, selalu tampil di sentuh */
                #fsToggleMini { opacity: 0; }
                #cameraBox:hover #fsToggleMini { opacity: 1; }
                @media (hover: none) { #fsToggleMini { opacity: 1; } }
                #cameraBox:fullscreen #fsToggleMini { display: none; }
                #fsExitBtn { display: none; }
                #cameraBox:fullscreen #fsExitBtn { display: flex; }
            </style>
            <div id="cameraBox" class="relative rounded-2xl overflow-hidden bg-brand-dark aspect-[4/3]">
                <video id="webcam" autoplay playsinline muted class="w-full h-full object-cover" style="transform: none;"></video>
                <div id="countdownOverlay" class="absolute inset-0 z-20 hidden items-center justify-center bg-black/50 text-white font-serif font-extrabold text-8xl">3</div>
                <div id="captureFlash" class="absolute inset-0 z-10 bg-white opacity-0 pointer-events-none"></div>
                <p id="cameraHint" class="absolute bottom-3 left-1/2 -translate-x-1/2 text-white/80 text-xs bg-black/40 rounded-full px-4 py-1">Kamera belum aktif — isi biodata dulu</p>
                <button id="fsToggleMini" title="Fullscreen (F)" class="absolute bottom-3 right-3 z-10 w-10 h-10 rounded-full bg-black/55 hover:bg-black/80 text-white flex items-center justify-center backdrop-blur transition">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5"><path d="M4 9V5.5A1.5 1.5 0 0 1 5.5 4H9M15 4h3.5A1.5 1.5 0 0 1 20 5.5V9M20 15v3.5a1.5 1.5 0 0 1-1.5 1.5H15M9 20H5.5A1.5 1.5 0 0 1 4 18.5V15" stroke-linecap="round"/></svg>
                </button>
                <button id="fsExitBtn" title="Keluar fullscreen (Esc)" class="absolute top-3 right-3 z-10 w-9 h-9 rounded-full bg-black/55 hover:bg-black/80 text-white items-center justify-center backdrop-blur transition">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5"><path d="M9 4v5.5A1.5 1.5 0 0 1 7.5 11H4M20 4h-4.5A1.5 1.5 0 0 0 14 5.5V9M15 20v-5.5a1.5 1.5 0 0 1 1.5-1.5H20M4 20h4.5A1.5 1.5 0 0 0 10 18.5V14" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
                {{-- Overlay UI fullscreen: menubar kiri layar + tombol shutter kanan tengah --}}
                <div id="fsUI" class="absolute inset-y-0 inset-x-0 z-10 items-center justify-between pl-5 pr-5">
                    <div id="fsMenuBar" class="flex flex-col items-stretch gap-2 w-auto max-h-[62vh] overflow-y-auto bg-black/55 backdrop-blur rounded-2xl px-2.5 py-2.5">
                        <div id="fsMainMenu" class="flex flex-col items-center gap-2">
                            <button data-fsmenu="filter" title="Filter" class="fs-chip w-11 h-11 rounded-full text-white/90 bg-white/15 hover:bg-white/25 flex items-center justify-center">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5"><path d="M4 5h16l-6.5 7.5V19l-3 1.5v-8L4 5z" stroke-linejoin="round"/></svg>
                            </button>
                            <button data-fsmenu="timer" title="Timer" class="fs-chip w-11 h-11 rounded-full text-white/90 bg-white/15 hover:bg-white/25 flex items-center justify-center">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5"><circle cx="12" cy="13" r="7.5"/><path d="M12 9.5V13l2.5 1.5M9.5 3h5" stroke-linecap="round"/></svg>
                            </button>
                        </div>
                        <div id="fsFilterMenu" class="hidden flex-col items-stretch gap-1.5 w-40">
                            <button data-fsback class="fs-chip w-full text-white/90 bg-white/15 hover:bg-white/25 rounded-full px-3 py-1.5 text-xs font-bold">‹ Kembali</button>
                        </div>
                        <div id="fsTimerMenu" class="hidden flex-col items-stretch gap-1.5 w-40">
                            <button data-fsback class="fs-chip w-full text-white/90 bg-white/15 hover:bg-white/25 rounded-full px-3 py-1.5 text-xs font-bold">‹ Kembali</button>
                        </div>
                    </div>
                    <button id="btnFsShutter" title="Jepret foto" class="shrink-0 w-20 h-20 rounded-full bg-brand-orange text-white flex items-center justify-center hover:bg-brand-orange-hover">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="w-9 h-9"><path d="M4 8h2.6L8.6 5.5h6.8L17.4 8H20A1.5 1.5 0 0 1 21.5 9.5V18a1.5 1.5 0 0 1-1.5 1.5H4A1.5 1.5 0 0 1 2.5 18V9.5A1.5 1.5 0 0 1 4 8z" stroke-linejoin="round"/><circle cx="12" cy="13.5" r="3.4"/></svg>
                    </button>
                </div>
            </div>
            <canvas id="captureCanvas" class="hidden"></canvas>
            <div class="mt-4 flex flex-wrap gap-3">
                <button id="btnCapture" disabled class="bg-brand-orange disabled:opacity-40 text-white rounded-full px-8 py-3 font-semibold hover:bg-brand-orange-hover transition">Jepret Foto</button>
                <button id="btnRetakeOne" class="border border-gray-300 rounded-full px-6 py-3 font-semibold hover:bg-gray-100 transition">↺ Retake terakhir</button>
                <button id="btnRetakeAll" class="border border-gray-300 rounded-full px-6 py-3 font-semibold hover:bg-gray-100 transition">⟲ Ulangi semua</button>
            </div>
            <p id="captureError" class="hidden mt-3 text-sm text-red-600"></p>
        </div>

        {{-- Kanan: Preview strip (otomatis ikut pilihan format) --}}
        <aside class="bg-white rounded-3xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="font-bold text-sm">Strip Preview</p>
                <span id="previewLayoutBadge" class="text-xs font-bold bg-brand-orange text-white rounded-full px-3 py-1">2x2</span>
            </div>
            <p id="previewDesignLabel" class="mt-1 text-xs text-brand-muted">Desain: Polos</p>
            <div id="thumbs" class="mt-3 grid grid-cols-2 gap-2 min-h-[120px]"></div>
            <canvas id="stripPreview" class="mt-4 w-full rounded-2xl border border-black/10"></canvas>
            <button id="btnNext" disabled class="mt-4 bg-brand-dark disabled:opacity-40 text-white rounded-full py-3 w-full font-semibold hover:bg-black transition">Selanjutnya → Gabung & Simpan</button>
            <p class="mt-2 text-xs text-brand-muted">Setelah slot penuh, klik Selanjutnya untuk menggabung + mengirim ke server.</p>
        </aside>
    </div>
</section>

{{-- Step 1: Modal Biodata (mandatori) --}}
<div id="biodataModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <form id="biodataForm" class="bg-white rounded-3xl p-8 max-w-md w-full mx-auto shadow-xl">
        <p class="font-serif font-extrabold text-2xl text-center">HOLD' MOMENT</p>
        <p class="text-center text-sm text-brand-muted mt-1">Isi biodata dulu sebelum kamera aktif</p>
        <label class="block mt-6 text-sm font-semibold">Nama Lengkap
            <input id="visitorName" type="text" required placeholder="Nama kamu" class="mt-1 w-full rounded-xl border border-gray-300 bg-brand-card-light px-4 py-2.5 font-normal outline-none focus:border-brand-orange">
        </label>
        <label class="block mt-4 text-sm font-semibold">Media Sosial / Instagram
            <input id="visitorSocial" type="text" required placeholder="@username" class="mt-1 w-full rounded-xl border border-gray-300 bg-brand-card-light px-4 py-2.5 font-normal outline-none focus:border-brand-orange">
        </label>
        <button type="submit" class="mt-6 bg-brand-orange text-white rounded-full py-3 w-full font-semibold hover:bg-brand-orange-hover transition">Lanjutkan ke Kamera</button>
    </form>
</div>

@push('scripts')
<script>
(function () {
    const modal = document.getElementById('biodataModal');
    const form = document.getElementById('biodataForm');
    const video = document.getElementById('webcam');
    const overlay = document.getElementById('countdownOverlay');
    const hint = document.getElementById('cameraHint');
    const captureCanvas = document.getElementById('captureCanvas');
    const stripPreview = document.getElementById('stripPreview');
    const thumbs = document.getElementById('thumbs');
    const btnCapture = document.getElementById('btnCapture');
    const btnNext = document.getElementById('btnNext');
    const btnRetakeOne = document.getElementById('btnRetakeOne');
    const btnRetakeAll = document.getElementById('btnRetakeAll');
    const errBox = document.getElementById('captureError');
    const visitorLabel = document.getElementById('visitorLabel');
    const progressLabel = document.getElementById('progressLabel');
    const previewLayoutBadge = document.getElementById('previewLayoutBadge');
    const previewDesignLabel = document.getElementById('previewDesignLabel');

    // Desain strip dari database (di-upload admin) + URL frame overlay-nya
    const TEMPLATES = @json($designs);

    const state = {
        visitor: { name: '', social: '' },
        layout: '2x2',
        filter: 'none',
        timer: 3,
        mirror: true, // default ON agar preview front-camera tidak mirror (teks tidak kebalik); klik toggle untuk balik
        templateId: null, // id desain terpilih, null = Polos
        builtin: 'plain', // 'plain' | 'receipt' (tema bawaan tanpa upload)
        shots: [], // array of dataURL (filtered capture)
        stream: null,
        busy: false,
    };

    function applyMirror() {
        video.style.transform = state.mirror ? 'scaleX(-1)' : 'none';
        const btn = document.getElementById('mirrorToggle');
        if (btn) {
            const on = state.mirror;
            btn.textContent = on ? 'Mirror: ON — preview seperti kaca' : 'Mirror: OFF — preview normal';
            btn.classList.toggle('bg-brand-orange', on);
            btn.classList.toggle('text-white', on);
            btn.classList.toggle('border-brand-orange', on);
            btn.classList.toggle('border-gray-300', !on);
        }
    }

    // Preload frame overlay & background agar preview langsung tampil saat desain dipilih
    const frameCache = {};
    const backgroundCache = {};
    const detectedCache = {}; // hasil ukur otomatis lubang per template (kunci: id_total)
    function invalidateDetected(id) {
        Object.keys(detectedCache).forEach(k => { if (k.startsWith(id + '_')) delete detectedCache[k]; });
    }
    TEMPLATES.forEach(t => {
        if (t.frame_url) {
            const img = new Image();
            img.onload = () => { frameCache[t.id] = img; invalidateDetected(t.id); if (String(state.templateId) === String(t.id)) drawStripPreview(); };
            img.onerror = () => { delete frameCache[t.id]; };
            img.src = t.frame_url;
        }
        if (t.background_url) {
            const bg = new Image();
            bg.onload = () => { backgroundCache[t.id] = bg; invalidateDetected(t.id); if (String(state.templateId) === String(t.id)) drawStripPreview(); };
            bg.onerror = () => { delete backgroundCache[t.id]; };
            bg.src = t.background_url;
        }
    });

    // Ukur otomatis lubang foto pada gambar frame/background (transparan ATAU
    // kotak putih) untuk template upload-an admin yang belum punya slots.
    // Murni JS (canvas getImageData), tanpa library. Gagal → null (pakai grid baku).
    function detectHoles(img, total) {
        const fw = img.naturalWidth, fh = img.naturalHeight;
        if (!fw || !fh) return null;
        if (!Number.isInteger(total / 2) || total < 1) return null;
        const aw = Math.min(fw, 360);
        const ah = Math.max(1, Math.round(aw * fh / fw));
        const cv = document.createElement('canvas');
        cv.width = aw; cv.height = ah;
        const cx = cv.getContext('2d', { willReadFrequently: true });
        cx.drawImage(img, 0, 0, aw, ah);
        let data;
        try { data = cx.getImageData(0, 0, aw, ah).data; }
        catch (e) { return null; }
        const isHole = (x, y) => {
            if (x < 0 || y < 0 || x >= aw || y >= ah) return false;
            const i = (y * aw + x) * 4;
            if (data[i + 3] < 128) return true;
            return data[i] >= 215 && data[i + 1] >= 215 && data[i + 2] >= 215; // kotak terang
        };
        const holes = [];
        const minH = ah * 0.07, minW = (aw / 2) * 0.55;
        [Math.floor(aw / 4), Math.floor(3 * aw / 4)].forEach(px => {
            const half = px < aw / 2 ? [0, Math.floor(aw / 2)] : [Math.floor(aw / 2), aw];
            const runs = [];
            let inRun = false, s = 0;
            for (let y = 0; y < ah; y++) {
                const t = isHole(px, y);
                if (t && !inRun) { inRun = true; s = y; }
                else if (!t && inRun) { inRun = false; runs.push([s, y]); }
            }
            if (inRun) runs.push([s, ah]);
            runs.forEach(r => {
                if ((r[1] - r[0]) < minH) return;
                const ym = Math.floor((r[0] + r[1]) / 2);
                let l = px; while (l > half[0] && isHole(l - 1, ym)) l--;
                let rr = px; while (rr < half[1] - 1 && isHole(rr + 1, ym)) rr++;
                if ((rr - l) < minW) return;
                const midx = Math.floor((l + rr) / 2);
                let t = r[0]; while (t > 0 && isHole(midx, t - 1)) t--;
                let b = r[1]; while (b < ah - 1 && isHole(midx, b + 1)) b++;
                holes.push([l, t, rr - l, b - t]);
            });
        });
        let kept = holes;
        if (kept.length > total) {
            const areas = kept.map(hh => hh[2] * hh[3]).sort((a, b) => a - b);
            const med = areas[Math.floor(areas.length / 2)];
            kept = kept.filter(hh => (hh[2] * hh[3]) >= 0.55 * med);
        }
        if (kept.length !== total) return null;
        kept.sort((a, b) => (a[1] - b[1]) || (a[0] - b[0]));
        const k = fw / aw;
        return { fw, fh, holes: kept.map(hh => [Math.round(hh[0] * k), Math.round(hh[1] * k), Math.round(hh[2] * k), Math.round(hh[3] * k)]) };
    }

    function selectedTemplate() {
        return TEMPLATES.find(t => String(t.id) === String(state.templateId)) || null;
    }

    const LAYOUTS = {
        '2x2': { cols: 2, rows: 2, total: 4 },
        '2x3': { cols: 2, rows: 3, total: 6 },
        'receipt': { cols: 2, rows: 2, total: 4 }, // 2 strip kembar, tiap strip 1 kolom × 2 foto
    };

    // Foto mengikuti lubang desain (bukan grid baku) bila tersedia:
    // 1) slots presisi dari database, 2) hasil ukur otomatis gambarnya.
    // Syarat: jumlah lubang harus sama dengan total slot layout aktif.
    function slottedTemplate() {
        const tpl = selectedTemplate();
        if (!tpl || !LAYOUTS[state.layout]) return null;
        const total = LAYOUTS[state.layout].total;
        if (tpl.slots && Array.isArray(tpl.slots.holes) && tpl.slots.fw && tpl.slots.fh
            && tpl.slots.holes.length === total) return tpl.slots;
        const key = tpl.id + '_' + total;
        if (!(key in detectedCache)) {
            const img = (tpl.frame_url && frameCache[tpl.id]) || (tpl.background_url && backgroundCache[tpl.id]) || null;
            detectedCache[key] = (img && img.complete && img.naturalWidth) ? detectHoles(img, total) : null;
        }
        return detectedCache[key];
    }

    function requiredTotal() {
        const s = slottedTemplate();
        return s ? s.holes.length : LAYOUTS[state.layout].total;
    }

    function setActive(selector, btn) {
        document.querySelectorAll(selector).forEach(b => {
            b.classList.remove('bg-brand-orange', 'text-white', 'border-brand-orange');
            b.classList.add('border-gray-300');
        });
        btn.classList.add('bg-brand-orange', 'text-white', 'border-brand-orange');
        btn.classList.remove('border-gray-300');
    }

    function markDesignActive(btn) {
        document.querySelectorAll('.design-btn').forEach(x => {
            x.classList.remove('border-brand-orange', 'bg-brand-orange/10');
            x.classList.add('border-gray-300');
        });
        if (btn) {
            btn.classList.add('border-brand-orange', 'bg-brand-orange/10');
            btn.classList.remove('border-gray-300');
        }
    }

    function syncLayoutButtons() {
        document.querySelectorAll('.layout-btn').forEach(x => {
            const on = x.dataset.layout === state.layout;
            x.classList.toggle('bg-brand-orange', on);
            x.classList.toggle('text-white', on);
            x.classList.toggle('border-brand-orange', on);
            x.classList.toggle('border-gray-300', !on);
        });
    }

    document.querySelectorAll('.layout-btn').forEach(b => b.addEventListener('click', () => {
        state.layout = b.dataset.layout;
        state.shots = [];
        // Tema Receipt punya format sendiri → ganti format lain kembali ke Polos
        if (state.builtin === 'receipt' && state.layout !== 'receipt') {
            state.builtin = 'plain';
            markDesignActive(document.querySelector('.design-btn[data-template=""]'));
        }
        // Desain upload yang layout-nya beda otomatis kembali ke Polos
        const tpl = selectedTemplate();
        if (tpl && tpl.layout_type !== state.layout) {
            state.templateId = null;
            markDesignActive(document.querySelector('.design-btn[data-template=""]'));
        }
        setActive('.layout-btn', b);
        render();
    }));
    document.querySelectorAll('.design-btn').forEach(b => b.addEventListener('click', () => {
        const id = b.dataset.template || '';
        if (id === 'receipt') {
            // Tema 2 strip struk kembar — digambar prosedural di canvas
            state.builtin = 'receipt';
            state.templateId = null;
            if (state.layout !== 'receipt') {
                state.layout = 'receipt';
                state.shots = [];
                syncLayoutButtons();
            }
        } else {
            state.builtin = 'plain';
            const tpl = TEMPLATES.find(t => String(t.id) === String(id)) || null;
            state.templateId = tpl ? tpl.id : null;
            // Desain membawa formatnya sendiri → preview + slot otomatis menyesuaikan
            if (tpl && tpl.layout_type !== state.layout) {
                state.layout = tpl.layout_type;
                state.shots = [];
                syncLayoutButtons();
            }
        }
        markDesignActive(b);
        render();
    }));
    document.querySelectorAll('.filter-btn').forEach(b => b.addEventListener('click', () => {
        state.filter = b.dataset.filter;
        video.style.filter = state.filter;
        setActive('.filter-btn', b);
    }));
    document.querySelectorAll('.timer-btn').forEach(b => b.addEventListener('click', () => {
        state.timer = parseInt(b.dataset.timer, 10);
        setActive('.timer-btn', b);
    }));
    document.getElementById('mirrorToggle')?.addEventListener('click', () => {
        state.mirror = !state.mirror;
        applyMirror();
    });
    applyMirror();

    // Restore biodata dari session (biar refresh tidak isi ulang)
    try {
        const saved = JSON.parse(sessionStorage.getItem('holdmoment_visitor') || 'null');
        if (saved && saved.name && saved.social) {
            document.getElementById('visitorName').value = saved.name;
            document.getElementById('visitorSocial').value = saved.social;
        }
    } catch (e) {}

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const name = document.getElementById('visitorName').value.trim();
        const social = document.getElementById('visitorSocial').value.trim();
        if (!name || !social) return;
        state.visitor = { name, social };
        sessionStorage.setItem('holdmoment_visitor', JSON.stringify(state.visitor));
        modal.classList.add('hidden');
        await startCamera();
    });

    async function startCamera() {
        errBox.classList.add('hidden');
        try {
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                throw new Error('Browser tidak mendukung WebRTC getUserMedia. Gunakan Chrome/Edge terbaru via HTTPS atau localhost.');
            }
            state.stream = await navigator.mediaDevices.getUserMedia({ video: { width: { ideal: 1280 }, height: { ideal: 720 }, facingMode: 'user' }, audio: false });
            video.srcObject = state.stream;
            video.style.filter = state.filter;
            await video.play().catch(() => {});
            hint.textContent = 'Kamera aktif — atur format & filter, lalu Jepret!';
            btnCapture.disabled = false;
            visitorLabel.textContent = 'Pengunjung: ' + state.visitor.name + ' (' + state.visitor.social + ')';
        } catch (err) {
            errBox.textContent = 'Gagal mengakses kamera: ' + err.message;
            errBox.classList.remove('hidden');
        }
    }

    function countdown(seconds) {
        return new Promise((resolve) => {
            let n = seconds;
            overlay.classList.remove('hidden');
            overlay.classList.add('flex');
            overlay.textContent = n;
            const tick = setInterval(() => {
                n -= 1;
                if (n <= 0) {
                    clearInterval(tick);
                    overlay.classList.add('hidden');
                    overlay.classList.remove('flex');
                    resolve();
                } else {
                    overlay.textContent = n;
                }
            }, 1000);
        });
    }

    function captureFrame() {
        const w = video.videoWidth || 640;
        const h = video.videoHeight || 480;
        captureCanvas.width = w;
        captureCanvas.height = h;
        const ctx = captureCanvas.getContext('2d');
        ctx.filter = state.filter === 'none' ? 'none' : state.filter;
        if (state.mirror) {
            // mirror aktif → preview dibalik via CSS, hasil juga dibalik agar WYSIWYG
            ctx.translate(w, 0);
            ctx.scale(-1, 1);
        }
        ctx.drawImage(video, 0, 0, w, h);
        return captureCanvas.toDataURL('image/png');
    }

    async function startCapture() {
        if (state.busy || !state.stream) return;
        if (state.shots.length >= requiredTotal()) return;
        state.busy = true;
        btnCapture.disabled = true;
        await countdown(state.timer);
        try {
            state.shots.push(captureFrame());
            render();
            if (typeof flashCapture === 'function') flashCapture();
        } catch (e) {
            errBox.textContent = 'Gagal mengambil foto: ' + e.message;
            errBox.classList.remove('hidden');
        }
        state.busy = false;
        btnCapture.disabled = state.shots.length >= requiredTotal();
    }

    btnCapture.addEventListener('click', startCapture);

    // Mode fullscreen: tombol mini hover (gaya Youtube) + tombol F (Esc keluar)
    const cameraBox = document.getElementById('cameraBox');
    async function toggleFullscreen() {
        errBox.classList.add('hidden');
        try {
            if (document.fullscreenElement) {
                await document.exitFullscreen();
            } else {
                const req = cameraBox.requestFullscreen || cameraBox.webkitRequestFullscreen;
                if (!req) throw new Error('browser tidak mendukung Fullscreen API.');
                await req.call(cameraBox);
            }
        } catch (e) {
            errBox.textContent = 'Gagal masuk fullscreen: ' + e.message;
            errBox.classList.remove('hidden');
        }
    }
    document.getElementById('fsToggleMini')?.addEventListener('click', toggleFullscreen);
    document.getElementById('fsExitBtn')?.addEventListener('click', toggleFullscreen);
    document.addEventListener('keydown', (e) => {
        if (e.key !== 'f' && e.key !== 'F') return;
        const tag = (e.target && e.target.tagName) || '';
        if (['INPUT', 'TEXTAREA', 'SELECT'].includes(tag)) return;
        if (!modal.classList.contains('hidden')) return; // biodata belum diisi
        e.preventDefault();
        toggleFullscreen();
    });

    // ===== Overlay UI fullscreen: shutter + menubar Filter/Timer =====
    const fsUI = document.getElementById('fsUI');
    const fsMainMenu = document.getElementById('fsMainMenu');
    const fsFilterMenu = document.getElementById('fsFilterMenu');
    const fsTimerMenu = document.getElementById('fsTimerMenu');
    const btnFsShutter = document.getElementById('btnFsShutter');
    const captureFlash = document.getElementById('captureFlash');

    function fsShowMenu(which) {
        fsMainMenu.classList.toggle('hidden', which !== 'main');
        fsMainMenu.classList.toggle('flex', which === 'main');
        fsFilterMenu.classList.toggle('hidden', which !== 'filter');
        fsFilterMenu.classList.toggle('flex', which === 'filter');
        fsTimerMenu.classList.toggle('hidden', which !== 'timer');
        fsTimerMenu.classList.toggle('flex', which === 'timer');
        if (which !== 'main') syncFsMenus();
    }

    function syncFsMenus() {
        fsFilterMenu.querySelectorAll('[data-fsfilter]').forEach(c => c.classList.toggle('fs-active', c.dataset.fsfilter === state.filter));
        fsTimerMenu.querySelectorAll('[data-fstimer]').forEach(c => c.classList.toggle('fs-active', parseInt(c.dataset.fstimer, 10) === state.timer));
    }

    function flashCapture() {
        if (!captureFlash) return;
        captureFlash.style.opacity = '0.85';
        setTimeout(() => { captureFlash.style.opacity = '0'; }, 120);
    }

    // Chip filter/timer dibangun dari tombol panel samping (sumber tunggal)
    document.querySelectorAll('#filterPicker .filter-btn').forEach(b => {
        const c = document.createElement('button');
        c.dataset.fsfilter = b.dataset.filter;
        c.textContent = b.textContent.trim();
        c.className = 'fs-chip w-full text-left text-white/90 bg-white/15 hover:bg-white/25 rounded-full px-3 py-1.5 text-xs font-semibold';
        c.addEventListener('click', () => {
            state.filter = c.dataset.fsfilter;
            video.style.filter = state.filter;
            const side = document.querySelector('#filterPicker .filter-btn[data-filter="' + CSS.escape(state.filter) + '"]');
            if (side) setActive('.filter-btn', side);
            syncFsMenus();
        });
        fsFilterMenu.appendChild(c);
    });
    document.querySelectorAll('#timerPicker .timer-btn').forEach(b => {
        const c = document.createElement('button');
        c.dataset.fstimer = b.dataset.timer;
        c.textContent = b.textContent.trim();
        c.className = 'fs-chip w-full text-left text-white/90 bg-white/15 hover:bg-white/25 rounded-full px-3 py-1.5 text-xs font-semibold';
        c.addEventListener('click', () => {
            state.timer = parseInt(c.dataset.fstimer, 10);
            const side = document.querySelector('#timerPicker .timer-btn[data-timer="' + CSS.escape(String(state.timer)) + '"]');
            if (side) setActive('.timer-btn', side);
            syncFsMenus();
        });
        fsTimerMenu.appendChild(c);
    });
    document.querySelectorAll('[data-fsmenu]').forEach(b => b.addEventListener('click', () => fsShowMenu(b.dataset.fsmenu)));
    document.querySelectorAll('[data-fsback]').forEach(b => b.addEventListener('click', () => fsShowMenu('main')));
    document.addEventListener('fullscreenchange', () => { if (document.fullscreenElement) fsShowMenu('main'); });

    btnFsShutter?.addEventListener('click', async () => {
        if (state.busy || !state.stream) return;
        if (state.shots.length >= requiredTotal()) return;
        fsUI.classList.add('fs-hidden-ui'); // semua bar & tombol hilang, countdown tetap
        btnFsShutter.classList.add('fs-capturing');
        try { await startCapture(); }
        finally {
            btnFsShutter.classList.remove('fs-capturing');
            fsUI.classList.remove('fs-hidden-ui');
        }
    });

    btnRetakeOne.addEventListener('click', () => {
        state.shots.pop();
        render();
        btnCapture.disabled = false;
    });
    btnRetakeAll.addEventListener('click', () => {
        state.shots = [];
        render();
        btnCapture.disabled = !state.stream;
    });

    // Daftar desain hanya menampilkan yang sesuai format terpilih:
    // Polos (universal) selalu tampil; Receipt dihitung anggota kelompok 2x2.
    function filterDesigns() {
        let n = 0;
        document.querySelectorAll('.design-btn').forEach(b => {
            const tplId = b.dataset.template || '';
            const lay = b.dataset.layout || '';
            // Receipt anggota kelompok 2x2: tampil saat format 2x2, sembunyi saat 2x3.
            const show = tplId === '' || lay === state.layout || (lay === 'receipt' && state.layout === '2x2');
            b.classList.toggle('hidden', !show);
            if (show) n++;
        });
        const el = document.getElementById('designCount');
        if (el) el.textContent = n + ' pilihan utk ' + (state.layout === 'receipt' ? 'Receipt' : state.layout);
    }

    function render() {
        filterDesigns();
        // thumbnails
        thumbs.innerHTML = '';
        state.shots.forEach((src) => {
            const img = document.createElement('img');
            img.src = src;
            img.className = 'rounded-xl w-full aspect-[4/3] object-cover border border-black/10';
            thumbs.appendChild(img);
        });
        for (let i = state.shots.length; i < requiredTotal(); i++) {
            const d = document.createElement('div');
            d.className = 'rounded-xl w-full aspect-[4/3] bg-brand-card-light flex items-center justify-center text-brand-muted text-xs';
            d.textContent = 'Slot ' + (i + 1);
            thumbs.appendChild(d);
        }
        progressLabel.textContent = state.shots.length + ' / ' + requiredTotal() + ' foto terisi';
        if (previewLayoutBadge) previewLayoutBadge.textContent = state.layout === 'receipt' ? 'Receipt' : state.layout;
        let designName = 'Polos';
        if (state.builtin === 'receipt') designName = 'Receipt (2 strip kembar)';
        else {
            const tplNow = selectedTemplate();
            if (tplNow) designName = tplNow.name + ' (' + tplNow.layout_type + ')';
        }
        if (previewDesignLabel) previewDesignLabel.textContent = 'Desain: ' + designName;
        btnNext.disabled = state.shots.length !== requiredTotal();
        drawStripPreview(); // preview kanan langsung digambar ulang sesuai pilihan strip + desain
    }

    // ===== Mesin gambar strip (dipakai preview & file simpanan) =====
    // Pola barcode deterministik agar preview dan hasil simpan identik
    const BARCODE_BARS = (() => {
        let seed = 20260915;
        const rnd = () => (seed = (seed * 1103515245 + 12345) & 0x7fffffff) / 0x7fffffff;
        const bars = [];
        for (let i = 0; i < 64; i++) bars.push(1 + Math.floor(rnd() * 4));
        return bars;
    })();

    function isReceipt() { return state.builtin === 'receipt'; }

    // Geometri strip untuk lebar W (s = skala, preview 600px / simpan 900px)
    function geomFor(W) {
        const slots = slottedTemplate();
        if (slots) {
            // Kanvas mengikuti rasio asli frame (1200x1800) agar overlay
            // tidak melar. Foto sedikit DILEBARKAN (+3px) bila ada frame
            // overlay di atasnya (tepi terselip di bawah bingkai), tapi
            // DIKECILKAN (-3px) bila tanpa frame agar tidak menutupi
            // border milik desain background.
            const k = W / slots.fw;
            const hasFrame = !!(selectedTemplate() && selectedTemplate().frame_url);
            const pad = hasFrame ? 3 : -3;
            const holes = slots.holes.map(h => ({
                x: (h[0] - pad) * k,
                y: (h[1] - pad) * k,
                w: (h[2] + pad * 2) * k,
                h: (h[3] + pad * 2) * k,
            }));
            // radius sudut mengikuti lubang desain (px frame → px kanvas);
            // hasil ukur otomatis (tanpa radius) pakai default 12px frame.
            const radius = (slots.radius != null ? slots.radius : 12) * k;
            return { slotted: true, s: k, holes, radius, H: slots.fh * k };
        }
        const s = W / 600;
        if (state.layout === 'receipt') {
            // Dua strip struk kembar kiri-kanan; tiap strip 1 kolom × 2 foto
            const pad = 16 * s, gutter = 24 * s, ip = 14 * s, gap = 12 * s;
            const colW = (W - pad * 2 - gutter) / 2;
            const cellW = colW - ip * 2;
            const cellH = cellW * 0.75;
            const headerH = 250 * s, footerH = 150 * s;
            const H = pad + headerH + cellH * 2 + gap + footerH + pad;
            return { double: true, cols: 2, rows: 2, s, pad, gutter, ip, gap, colW, cellW, cellH, headerH, footerH, radius: 3 * s, H };
        }
        const { cols, rows } = LAYOUTS[state.layout];
        const pad = 20 * s, gap = 12 * s;
        const cellW = (W - pad * 2 - gap * (cols - 1)) / cols;
        const cellH = cellW * 0.75;
        const headerH = 110 * s, footerH = 90 * s;
        const H = headerH + rows * cellH + (rows - 1) * gap + footerH + pad * 2;
        return { double: false, cols, rows, s, pad, gap, cellW, cellH, headerH, footerH, radius: 12 * s, H };
    }

    function dashedLine(ctx, x1, x2, y, s) {
        ctx.save();
        ctx.strokeStyle = '#1C1C1C';
        ctx.lineWidth = Math.max(1, 1.5 * s);
        ctx.setLineDash([6 * s, 4 * s]);
        ctx.beginPath();
        ctx.moveTo(x1, y);
        ctx.lineTo(x2, y);
        ctx.stroke();
        ctx.restore();
    }

    // Kecilkan font sampai teks muat di maxW (jamin tidak meluber keluar kolom)
    function fitFont(ctx, text, maxW, startPx, family, weight) {
        let px = startPx;
        const apply = (p) => { ctx.font = (weight ? weight + ' ' : '') + p + 'px ' + family; };
        apply(px);
        while (px > 6 && ctx.measureText(text).width > maxW) {
            px -= 1;
            apply(px);
        }
        return px;
    }

    // Kop satu kolom struk (dipakai tiap strip kembar kiri & kanan)
    function paintReceiptHead(ctx, x0, colW, topY, g) {
        const s = g.s, ip = g.ip, cx = x0 + colW / 2;
        const qtyX = x0 + ip + 4 * s;          // awal kolom QTY
        const itemX = x0 + ip + 82 * s;        // awal kolom ITEM
        const rightEdge = x0 + colW - ip - 4 * s;
        const qtyCX = qtyX + 34 * s;           // tengah kolom QTY
        ctx.fillStyle = '#111';
        ctx.textAlign = 'center';
        fitFont(ctx, '*****RECEIPT*****', colW - ip * 2 - 4 * s, 40 * s, '"Playfair Display", Georgia, serif', '800');
        ctx.fillText('*****RECEIPT*****', cx, topY + 46 * s);
        dashedLine(ctx, x0 + ip, x0 + colW - ip, topY + 62 * s, s);
        ctx.font = (22 * s) + 'px "Courier New", monospace';
        ctx.fillText('QTY', qtyCX, topY + 88 * s);
        ctx.textAlign = 'left';
        ctx.fillText('ITEM', itemX, topY + 88 * s);
        ctx.textAlign = 'center';
        dashedLine(ctx, x0 + ip, x0 + colW - ip, topY + 102 * s, s);
        const rows = [
            ['7', 'HOURS A DAY THINKING ABOUT YOU'],
            ['24', 'DAYS IN A WEEK'],
            ['365', 'TIRED ALL THE TIME'],
        ];
        rows.forEach((r, i) => {
            const y = topY + (132 + i * 34) * s;
            fitFont(ctx, r[1], rightEdge - itemX, 19 * s, '"Courier New", monospace', '');
            ctx.textAlign = 'center';
            ctx.fillText(r[0], qtyCX, y);
            ctx.textAlign = 'left';
            ctx.fillText(r[1], itemX, y);
        });
        ctx.textAlign = 'center';
    }

    // Latar + kop: cream HOLD' MOMENT atau kertas struk (tunggal/ganda)
    function paintChrome(ctx, W, g) {
        const s = g.s, pad = g.pad, cx = W / 2;
        ctx.fillStyle = g.double ? '#FBFAF6' : '#F6F4EE';
        ctx.fillRect(0, 0, W, g.H);
        if (g.double) {
            for (let k = 0; k < 2; k++) {
                paintReceiptHead(ctx, g.pad + k * (g.colW + g.gutter), g.colW, g.pad, g);
            }
            return;
        }
        ctx.fillStyle = '#1C1C1C';
        ctx.font = '800 ' + (34 * s) + 'px "Playfair Display", Georgia, serif';
        ctx.textAlign = 'center';
        ctx.fillText("HOLD' MOMENT", cx, 55 * s);
        ctx.fillStyle = '#D95B32';
        ctx.font = '600 ' + (14 * s) + 'px "Plus Jakarta Sans", sans-serif';
        ctx.fillText('• • •  PHOTOSTRIP  • • •', cx, 80 * s);
    }

    // Satu foto cover-fit ke sel
    function drawCover(ctx, im, x, y, w, h, r) {
        const ir = im.width / im.height, cr = w / h;
        let sw, sh, sx, sy;
        if (ir > cr) { sh = im.height; sw = sh * cr; sx = (im.width - sw) / 2; sy = 0; }
        else { sw = im.width; sh = sw / cr; sx = 0; sy = (im.height - sh) / 2; }
        ctx.save();
        ctx.beginPath();
        ctx.roundRect(x, y, w, h, r);
        ctx.clip();
        ctx.drawImage(im, sx, sy, sw, sh, x, y, w, h);
        ctx.restore();
    }

    // Sel foto — strip ganda: foto 1-2 kiri, 3-4 kanan; slot kosong = kertas
    function paintPhotos(ctx, g, imgs) {
        if (g.double) {
            for (let k = 0; k < 2; k++) {
                for (let j = 0; j < 2; j++) {
                    const im = imgs[k * 2 + j];
                    if (!im) continue;
                    const x = g.pad + k * (g.colW + g.gutter) + g.ip;
                    const y = g.pad + g.headerH + j * (g.cellH + g.gap);
                    drawCover(ctx, im, x, y, g.cellW, g.cellH, g.radius);
                }
            }
            return;
        }
        imgs.forEach((im, i) => {
            if (!im) return;
            const c = i % g.cols, r = Math.floor(i / g.cols);
            const x = g.pad + c * (g.cellW + g.gap);
            const y = g.headerH + r * (g.cellH + g.gap);
            drawCover(ctx, im, x, y, g.cellW, g.cellH, g.radius);
        });
    }

    function paintBarcode(ctx, cx, y, h, s, maxW) {
        const sum = BARCODE_BARS.reduce((a, b) => a + b, 0) + BARCODE_BARS.length - 1;
        let unit = 2.2 * s;
        if (maxW) unit = Math.min(unit, maxW / sum);
        const totalW = sum * unit;
        let x = cx - totalW / 2;
        ctx.fillStyle = '#111';
        BARCODE_BARS.forEach(w => {
            ctx.fillRect(x, y, w * unit, h);
            x += (w + 1) * unit;
        });
    }

    // Kaki strip: biodata pengunjung atau THANK YOU + barcode tiap strip kembar
    function paintFooter(ctx, W, g) {
        const s = g.s, cx = W / 2, H = g.H;
        ctx.textAlign = 'center';
        if (g.double) {
            for (let k = 0; k < 2; k++) {
                const ccx = g.pad + k * (g.colW + g.gutter) + g.colW / 2;
                const fy = g.pad + g.headerH + g.cellH * 2 + g.gap;
                ctx.fillStyle = '#111';
                fitFont(ctx, '*******THANK YOU!*******', g.colW - g.ip * 2 - 4 * s, 22 * s, '"Courier New", monospace', '');
                ctx.fillText('*******THANK YOU!*******', ccx, fy + 30 * s);
                paintBarcode(ctx, ccx, fy + 44 * s, 62 * s, s, g.colW - g.ip * 2);
                ctx.fillStyle = '#737373';
                ctx.font = '500 ' + (14 * s) + 'px "Plus Jakarta Sans", sans-serif';
                ctx.fillText((state.visitor.name || 'Pengunjung') + ' • ' + (state.visitor.social || '@sosmed'), ccx, fy + 128 * s);
            }
            return;
        }
        ctx.fillStyle = '#1C1C1C';
        ctx.font = '700 ' + (20 * s) + 'px "Plus Jakarta Sans", sans-serif';
        ctx.fillText(state.visitor.name || 'Pengunjung', cx, H - 55 * s);
        ctx.fillStyle = '#737373';
        ctx.font = '500 ' + (15 * s) + 'px "Plus Jakarta Sans", sans-serif';
        ctx.fillText((state.visitor.social || '@sosmed') + '  •  ' + new Date().toLocaleDateString('id-ID'), cx, H - 30 * s);
    }

    // Frame PNG upload-an admin (digambar paling atas bila ada & cocok)
    function paintFrameOverlay(ctx, W, H) {
        const fr = state.templateId ? frameCache[state.templateId] : null;
        if (fr && fr.complete && fr.naturalWidth) {
            ctx.drawImage(fr, 0, 0, W, H);
        }
    }

    function paintStrip(canvas, W, imgs) {
        const g = geomFor(W);
        canvas.width = W;
        canvas.height = g.H;
        const ctx = canvas.getContext('2d');
        if (g.slotted) {
            // Background digambar utuh mengikuti rasio aslinya (tidak melar);
            // frame transparan menutupi penuh kecuali lubang; keduanya opsional.
            // Tiap foto digambar tepat di lubangnya (milik background kotak
            // putih/hitam maupun lubang transparan frame).
            const bg = state.templateId ? backgroundCache[state.templateId] : null;
            if (bg && bg.complete && bg.naturalWidth) {
                ctx.drawImage(bg, 0, 0, W, g.H);
            } else {
                ctx.fillStyle = '#1C1C1C';
                ctx.fillRect(0, 0, W, g.H);
            }
            imgs.forEach((im, i) => {
                const hole = g.holes[i];
                if (!im || !hole) return;
                drawCover(ctx, im, hole.x, hole.y, hole.w, hole.h, g.radius);
            });
            paintFrameOverlay(ctx, W, g.H);
            return;
        }
        const bg = state.templateId ? backgroundCache[state.templateId] : null;
        const hasBg = bg && bg.complete && bg.naturalWidth;
        if (hasBg) {
            // Background upload admin — 100% persis seperti file (pixel sky, dll.) cover seluruh strip
            ctx.drawImage(bg, 0, 0, W, g.H);
        } else {
            paintChrome(ctx, W, g);
        }
        paintPhotos(ctx, g, imgs);
        if (!hasBg) paintFooter(ctx, W, g);
        // Jika pakai background, visitor name tidak digambar ulang agar 100% sesuai file upload
        // (data tetap tersimpan di DB dan tampil di halaman result/admin)
        paintFrameOverlay(ctx, W, g.H);
    }

    const loadShot = (src) => new Promise((res) => {
        const im = new Image();
        im.onload = () => res(im);
        im.onerror = () => res(null);
        im.src = src;
    });

    function drawStripPreview() {
        // Geometri dihitung ulang saat gambar selesai dimuat (layout bisa berubah)
        Promise.all(state.shots.map(loadShot)).then((imgs) => paintStrip(stripPreview, 600, imgs));
    }

    function fullResStrip() {
        // 900px + JPEG 0.75 → ~400KB, aman untuk Railway (limit 4MB)
        return Promise.all(state.shots.map(loadShot)).then((imgs) => {
            const cv = document.createElement('canvas');
            paintStrip(cv, 900, imgs);
            return cv.toDataURL('image/jpeg', 0.75);
        });
    }

    btnNext.addEventListener('click', async () => {
        if (btnNext.disabled || state.busy) return;
        state.busy = true;
        btnNext.textContent = 'Menyimpan...';
        errBox.classList.add('hidden');
        try {
            const image = await fullResStrip();
            console.log('strip size', Math.round(image.length/1024), 'KB');
            if (image.length > 4 * 1024 * 1024) throw new Error('Gambar kebesaran, coba retake dengan kualitas lebih kecil.');
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const res = await fetch("{{ route('photo.save') }}", {
                method: 'POST',
                credentials: 'same-origin',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                body: JSON.stringify({
                    visitor_name: state.visitor.name,
                    visitor_social: state.visitor.social,
                    template_id: state.templateId,
                    layout_type: state.layout,
                    image: image,
                }),
            });
            const text = await res.text();
            let data;
            try { data = JSON.parse(text); } catch { throw new Error('Server error ('+res.status+'): '+text.slice(0,300)); }
            if (!res.ok) throw new Error(data.message || 'Gagal menyimpan ('+res.status+')');
            window.location.href = data.redirect;
        } catch (e) {
            console.error(e);
            errBox.textContent = e.message.includes('Failed to fetch') ? 'Gagal terhubung ke server. Cek koneksi / coba lagi. Detail: '+e.message : e.message;
            errBox.classList.remove('hidden');
            state.busy = false;
            btnNext.textContent = 'Selanjutnya → Gabung & Simpan';
        }
    });

    render();
})();
</script>
@endpush
@endsection
