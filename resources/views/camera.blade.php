@extends('layouts.app')

@section('title', "Camera — HOLD' MOMENT")

@section('content')
<section class="max-w-6xl mx-auto px-6 pt-10 pb-14" x-data>
    <h1 class="font-serif font-extrabold text-3xl md:text-4xl">Photobooth Camera</h1>
    <p class="text-brand-muted mt-2 text-sm">Atur format & filter, jepret momen serumu, pilih desain, lalu simpan.</p>

    <div class="mt-8 grid lg:grid-cols-[84px_1fr_280px] gap-6 items-start">
        {{-- Sidebar vertikal: Strip / Filter / Timer + pop-up sub-menu.
             Sumber data TETAP tombol picker lama di panel kontrol (disembunyikan,
             dipakai sebagai sumber tunggal agar semua logika lama ikut jalan). --}}
        <div class="relative max-lg:order-first" id="toolRailWrap">
            <div class="bg-white rounded-3xl shadow-sm p-2 flex lg:flex-col flex-row gap-1 lg:sticky lg:top-4" id="toolRail">
                <button data-tool="strip" class="tool-btn flex-1 lg:w-full flex flex-col items-center gap-1 rounded-2xl px-2 py-3 transition" title="Pilih Strip">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-6 h-6"><rect x="3.5" y="4.5" width="17" height="15" rx="2.5"/><path d="M12 4.5v15M3.5 12h17" stroke-linecap="round"/></svg>
                    <span class="text-[11px] font-bold leading-none">Strip</span>
                    <span class="tool-val text-[10px] leading-none opacity-70" id="toolValStrip">2x2</span>
                </button>
                <button data-tool="filter" class="tool-btn flex-1 lg:w-full flex flex-col items-center gap-1 rounded-2xl px-2 py-3 transition" title="Pilih Filter">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-6 h-6"><path d="M4 8h8M18 8h2M4 16h2M12 16h8" stroke-linecap="round"/><circle cx="15" cy="8" r="2.2"/><circle cx="9" cy="16" r="2.2"/></svg>
                    <span class="text-[11px] font-bold leading-none">Filter</span>
                    <span class="tool-val text-[10px] leading-none opacity-70" id="toolValFilter">Normal</span>
                </button>
                <button data-tool="timer" class="tool-btn flex-1 lg:w-full flex flex-col items-center gap-1 rounded-2xl px-2 py-3 transition" title="Atur Timer">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-6 h-6"><circle cx="12" cy="13" r="7.5"/><path d="M12 9.5V13l2.5 1.5M9.5 3h5" stroke-linecap="round"/></svg>
                    <span class="text-[11px] font-bold leading-none">Timer</span>
                    <span class="tool-val text-[10px] leading-none opacity-70" id="toolValTimer">3s</span>
                </button>
            </div>
            <div id="toolPopup" class="hidden absolute z-30 lg:left-full lg:top-0 lg:ml-3 lg:mt-0 left-0 top-full mt-3 w-[300px] max-w-[calc(100vw-3rem)] bg-white rounded-3xl shadow-xl border border-black/10 p-5">
                <div class="flex items-center justify-between">
                    <p id="toolPopupTitle" class="font-bold text-sm">Pilih Strip</p>
                    <button id="toolPopupClose" class="w-8 h-8 rounded-full hover:bg-brand-card-light flex items-center justify-center transition" title="Tutup (Esc)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><path d="M6 6l12 12M18 6L6 18" stroke-linecap="round"/></svg>
                    </button>
                </div>
                <p id="toolPopupSub" class="text-xs text-brand-muted mt-1 mb-4"></p>
                <div id="toolPopupBodyStrip" class="hidden space-y-4">
                    <div>
                        <p class="text-xs font-bold text-brand-muted mb-2">FORMAT</p>
                        <div id="stripFormatCards" class="space-y-2"></div>
                    </div>
                </div>
                <div id="toolPopupBodyFilter" class="hidden grid grid-cols-3 gap-3"></div>
                <div id="toolPopupBodyTimer" class="hidden flex gap-2"></div>
            </div>
        </div>
        {{-- Panel kontrol lama dilebur: pickers disembunyikan di sini sebagai sumber data popup/fullscreen; Mirror + info pindah ke panel kamera --}}
        <div class="hidden" aria-hidden="true">
            {{-- Format/Filter/Timer dipindah ke sidebar vertikal (tetap di DOM sebagai sumber data) --}}
            <div class="hidden">
                <p class="font-bold text-sm mb-3">Format Strip</p>
                <div class="grid grid-cols-2 gap-2" id="layoutPicker">
                    <button data-layout="2x2" class="layout-btn border border-brand-orange bg-brand-orange text-white rounded-xl px-2 py-2 text-sm font-semibold">2x2</button>
                    <button data-layout="2x3" class="layout-btn border border-gray-300 rounded-xl px-2 py-2 text-sm font-semibold hover:border-brand-orange">2x3</button>
                </div>
            </div>
            <div class="hidden">
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
            <div class="hidden">
                <p class="font-bold text-sm mb-3">Delay Timer</p>
                <div class="grid grid-cols-3 gap-2" id="timerPicker">
                    <button data-timer="3" class="timer-btn border border-brand-orange bg-brand-orange text-white rounded-xl px-2 py-2 text-sm font-semibold">3s</button>
                    <button data-timer="5" class="timer-btn border border-gray-300 rounded-xl px-2 py-2 text-sm font-semibold hover:border-brand-orange">5s</button>
                    <button data-timer="10" class="timer-btn border border-gray-300 rounded-xl px-2 py-2 text-sm font-semibold hover:border-brand-orange">10s</button>
                </div>
            </div>
        </div>

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
                            <button data-fsback class="fs-chip w-full text-white/90 bg-white/15 hover:bg-white/25 rounded-full px-3 py-1.5 text-xs font-bold inline-flex items-center justify-center gap-1"><x-icon name="chevron-left" class="w-4 h-4" /> Kembali</button>
                        </div>
                        <div id="fsTimerMenu" class="hidden flex-col items-stretch gap-1.5 w-40">
                            <button data-fsback class="fs-chip w-full text-white/90 bg-white/15 hover:bg-white/25 rounded-full px-3 py-1.5 text-xs font-bold inline-flex items-center justify-center gap-1"><x-icon name="chevron-left" class="w-4 h-4" /> Kembali</button>
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
                <button id="btnRetakeOne" class="border border-gray-300 rounded-full px-6 py-3 font-semibold hover:bg-gray-100 transition inline-flex items-center gap-2"><x-icon name="refresh" class="w-4 h-4" /> Retake terakhir</button>
                <button id="btnRetakeAll" class="border border-gray-300 rounded-full px-6 py-3 font-semibold hover:bg-gray-100 transition inline-flex items-center gap-2"><x-icon name="refresh" class="w-4 h-4" /> Ulangi semua</button>
            </div>
            <div class="mt-4 flex flex-wrap items-center gap-3">
                <button id="mirrorToggle" class="border border-gray-300 rounded-full px-5 py-2.5 text-sm font-semibold hover:border-brand-orange whitespace-nowrap" title="Aktifkan jika preview masih terbalik">Mirror: OFF — preview normal</button>
                <p class="text-xs text-brand-muted"><span class="font-bold text-brand-text" id="visitorLabel">Pengunjung: -</span> <span class="mx-1">•</span> <span id="progressLabel">0 / 4 foto terisi</span></p>
            </div>
            <p id="captureError" class="hidden mt-3 text-sm text-red-600"></p>
        </div>

        {{-- Kanan: Preview strip (otomatis ikut pilihan format) --}}
        <aside class="bg-white rounded-3xl p-6 shadow-sm">
            <div class="flex items-center gap-2 text-[11px] font-bold mb-4" id="stepBar"></div>
            <div class="flex items-center justify-between">
                <p class="font-bold text-sm">Strip Preview</p>
                <span id="previewLayoutBadge" class="text-xs font-bold bg-brand-orange text-white rounded-full px-3 py-1">2x2</span>
            </div>
            <p id="previewDesignLabel" class="mt-1 text-xs text-brand-muted">Desain: Polos</p>
            <canvas id="stripPreview" class="mt-4 w-full rounded-2xl border border-black/10"></canvas>
            <button id="btnNext" disabled class="mt-4 bg-brand-dark disabled:opacity-40 text-white rounded-full py-3 w-full font-semibold hover:bg-black transition inline-flex items-center justify-center gap-2">Pilih Desain <x-icon name="arrow-right" class="w-4 h-4" /></button>
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

    @include('components.strip-engine')

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
    // (Pilih desain pindah ke halaman tersendiri setelah slot penuh.)
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
        return captureCanvas.toDataURL('image/jpeg', 0.9);
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

    // ===== Sidebar vertikal + pop-up sub-menu (Strip / Filter / Timer) =====
    // Isi pop-up DIBANGUN dari tombol picker lama (sumber tunggal, pola yang sama
    // seperti chip fullscreen): klik di pop-up didelegasikan ke .click() tombol
    // aslinya agar seluruh logika lama (state, reset shots, render, setActive)
    // ikut jalan tanpa duplikasi. Sinkron tampilan aktif dibaca dari `state`
    // lewat syncToolUI() yang dipanggil di render().
    const toolRailWrap = document.getElementById('toolRailWrap');
    const toolPopup = document.getElementById('toolPopup');
    const toolPopupTitle = document.getElementById('toolPopupTitle');
    const toolPopupSub = document.getElementById('toolPopupSub');
    const stripSection = document.getElementById('toolPopupBodyStrip');
    const stripCards = document.getElementById('stripFormatCards');
    const filterBody = document.getElementById('toolPopupBodyFilter');
    const timerBody = document.getElementById('toolPopupBodyTimer');
    let openTool = null;
    const TOOL_META = {
        strip: { title: 'Pilih Strip', sub: 'Format hasil foto: 2 kolom, baris menyesuaikan.' },
        filter: { title: 'Pilih Filter', sub: 'Efek langsung terlihat di kamera.' },
        timer: { title: 'Atur Timer', sub: 'Jeda hitung mundur sebelum jepret.' },
    };

    // --- Pop-up Strip: kartu layout dari .layout-btn yang sudah ada ---
    document.querySelectorAll('#layoutPicker .layout-btn').forEach(orig => {
        const lay = orig.dataset.layout; // '2x2' | '2x3'
        const parts = lay.split('x');
        const cols = parseInt(parts[0], 10) || 2, rows = parseInt(parts[1], 10) || 2;
        const total = (typeof LAYOUTS !== 'undefined' && LAYOUTS[lay]) ? LAYOUTS[lay].total : cols * rows;
        const card = document.createElement('button');
        card.dataset.stripLayout = lay;
        card.className = 'strip-card w-full flex items-center gap-3 rounded-xl border border-gray-300 p-3 text-left transition hover:border-brand-orange';
        let cells = '';
        for (let i = 0; i < cols * rows; i++) cells += '<span class="rounded-[4px] bg-current opacity-25"></span>';
        card.innerHTML = '<span class="grid gap-1 w-12 h-14 shrink-0" style="grid-template-columns:repeat(' + cols + ',1fr);grid-template-rows:repeat(' + rows + ',1fr)">' + cells + '</span>'
            + '<span><span class="block text-sm font-bold">Strip ' + lay + '</span>'
            + '<span class="block text-xs opacity-70">' + total + ' foto</span></span>';
        card.addEventListener('click', () => { orig.click(); closeTool(); });
        stripCards.appendChild(card);
    });

    // --- Pop-up Filter: grid lingkaran dari .filter-btn yang sudah ada ---
    // Lingkaran preview memakai string filter yang SAMA dari kode (thumbnail
    // gradasi diberi style filter identik agar efeknya terlihat langsung).
    const FILTER_THUMB = 'linear-gradient(135deg,#D95B32 0%,#F5C518 35%,#2E9E5B 65%,#3B82F6 100%)';
    document.querySelectorAll('#filterPicker .filter-btn').forEach(orig => {
        const f = orig.dataset.filter;
        const name = orig.textContent.trim();
        const item = document.createElement('button');
        item.dataset.filterVal = f;
        item.className = 'f-item flex flex-col items-center gap-1.5';
        item.title = name;
        item.innerHTML = '<span class="f-dot block w-14 h-14 rounded-full border-2 border-gray-300 transition hover:border-brand-orange" style="background:' + FILTER_THUMB + ';filter:' + f + '"></span>'
            + '<span class="f-label text-[11px] font-semibold leading-tight text-center">' + name + '</span>';
        item.addEventListener('click', () => { orig.click(); });
        filterBody.appendChild(item);
    });

    // --- Pop-up Timer: pil durasi dari .timer-btn yang sudah ada ---
    document.querySelectorAll('#timerPicker .timer-btn').forEach(orig => {
        const pill = document.createElement('button');
        pill.dataset.timerVal = orig.dataset.timer;
        pill.className = 't-pill flex-1 rounded-xl border border-gray-300 py-3 text-sm font-bold transition hover:border-brand-orange';
        pill.textContent = orig.textContent.trim();
        pill.addEventListener('click', () => { orig.click(); closeTool(); });
        timerBody.appendChild(pill);
    });

    function paintRail() {
        document.querySelectorAll('.tool-btn').forEach(b => {
            const on = b.dataset.tool === openTool;
            b.classList.toggle('bg-brand-orange', on);
            b.classList.toggle('text-white', on);
            b.classList.toggle('text-brand-text', !on);
            b.classList.toggle('hover:bg-brand-card-light', !on);
        });
    }
    function closeTool() {
        openTool = null;
        toolPopup.classList.add('hidden');
        paintRail();
    }
    function openToolPop(tool) {
        if (openTool === tool) { closeTool(); return; }
        openTool = tool;
        stripSection.classList.toggle('hidden', tool !== 'strip');
        filterBody.classList.toggle('hidden', tool !== 'filter');
        timerBody.classList.toggle('hidden', tool !== 'timer');
        toolPopupTitle.textContent = TOOL_META[tool].title;
        toolPopupSub.textContent = TOOL_META[tool].sub;
        toolPopup.classList.remove('hidden');
        paintRail();
        syncToolUI();
    }
    document.querySelectorAll('.tool-btn').forEach(b => b.addEventListener('click', (e) => { e.stopPropagation(); openToolPop(b.dataset.tool); }));
    document.getElementById('toolPopupClose').addEventListener('click', closeTool);
    document.addEventListener('click', (e) => { if (openTool && !toolRailWrap.contains(e.target)) closeTool(); });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeTool(); });

    // Sinkron nilai + status aktif (dibaca dari state mutakhir, dipanggil di render()).
    function syncToolUI() {
        const setCap = (id, txt) => { const el = document.getElementById(id); if (el) el.textContent = txt; };
        setCap('toolValStrip', state.layout);
        const fBtn = document.querySelector('#filterPicker .filter-btn[data-filter="' + CSS.escape(state.filter) + '"]');
        setCap('toolValFilter', fBtn ? fBtn.textContent.trim() : state.filter);
        setCap('toolValTimer', state.timer + 's');
        stripCards.querySelectorAll('.strip-card').forEach(c => {
            const on = c.dataset.stripLayout === state.layout;
            c.classList.toggle('border-brand-orange', on);
            c.classList.toggle('bg-brand-orange/10', on);
            c.classList.toggle('text-brand-orange', on);
            c.classList.toggle('border-gray-300', !on);
            c.classList.toggle('text-brand-text', !on);
        });
        filterBody.querySelectorAll('.f-item').forEach(it => {
            const on = it.dataset.filterVal === state.filter;
            const dot = it.querySelector('.f-dot'), lab = it.querySelector('.f-label');
            dot.classList.toggle('border-brand-orange', on);
            dot.classList.toggle('scale-110', on);
            dot.classList.toggle('shadow-lg', on);
            dot.classList.toggle('border-gray-300', !on);
            lab.classList.toggle('text-brand-orange', on);
        });
        timerBody.querySelectorAll('.t-pill').forEach(p => {
            const on = parseInt(p.dataset.timerVal, 10) === state.timer;
            p.classList.toggle('bg-brand-orange', on);
            p.classList.toggle('text-white', on);
            p.classList.toggle('border-brand-orange', on);
            p.classList.toggle('border-gray-300', !on);
        });
    }
    paintRail();
    syncToolUI();

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

    // (Daftar desain pindah ke halaman tersendiri; filter per format ada di sana.)

    // (tahap 2-3 pindah ke halaman desain)
    // Indikator tahap di halaman camera (tahap 1 Jepret; tahap 2-3 di halaman desain).
    function paintSteps() {
        const full = state.shots.length >= requiredTotal();
        const bar = document.getElementById('stepBar');
        if (bar) {
            const steps = [[1, 'Jepret'], [2, 'Desain'], [3, 'Simpan']];
            let html = '';
            steps.forEach((st, i) => {
                const n = st[0];
                const done = (n === 1 && full);
                const active = (n === 1 && !full);
                html += '<span class="flex items-center gap-1.5' + ((active || done) ? '' : ' opacity-40') + '">'
                    + '<span class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-bold '
                    + (done ? 'bg-brand-orange text-white' : (active ? 'border-2 border-brand-orange text-brand-orange' : 'bg-brand-card-light text-brand-muted')) + '">'
                    + (done ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>' : n) + '</span>'
                    + '<span class="' + ((active || done) ? 'text-brand-text' : 'text-brand-muted') + '">' + st[1] + '</span></span>';
                if (i < steps.length - 1) html += '<span class="flex-1 h-[2px] rounded ' + ((n === 1 && full) ? 'bg-brand-orange' : 'bg-black/10') + '"></span>';
            });
            bar.innerHTML = html;
        }
    }

    function render() {
        syncToolUI(); // sidebar + pop-up ikut status state mutakhir
        paintSteps(); // indikator tahap 1 (tahap 2-3 di halaman desain)
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


    // Alur 2 halaman: slot penuh → titip foto + biodata + format di
    // sessionStorage, lanjut ke halaman pilih desain (desain digabung + POST
    // simpanan dilakukan di sana). JPEG ±200KB/foto → 6 foto aman < 5MB.
    const DESAIN_URL = "{{ route('photo.desain') }}";
    btnNext.addEventListener('click', () => {
        if (btnNext.disabled || state.busy) return;
        errBox.classList.add('hidden');
        try {
            sessionStorage.setItem('holdmoment_stash', JSON.stringify({
                shots: state.shots,
                visitor: state.visitor,
                layout: state.layout,
                savedAt: Date.now(),
            }));
        } catch (e) {
            errBox.textContent = 'Gagal lanjut: penyimpanan browser penuh. Coba retake ulang.';
            errBox.classList.remove('hidden');
            return;
        }
        window.location.href = DESAIN_URL;
    });

    // Kembali dari halaman desain ("Ganti Foto"): pulihkan jepretan + biodata + format.
    // Stash kedaluwarsa 3 jam agar kunjungan lama tidak terbawa.
    try {
        const stash = JSON.parse(sessionStorage.getItem('holdmoment_stash') || 'null');
        if (stash && Array.isArray(stash.shots) && stash.shots.length
            && stash.savedAt && (Date.now() - stash.savedAt) < 3 * 3600 * 1000) {
            state.shots = stash.shots;
            if (stash.visitor) state.visitor = stash.visitor;
            if (stash.layout && LAYOUTS[stash.layout]) state.layout = stash.layout;
            const nm = document.getElementById('visitorName'), sc = document.getElementById('visitorSocial');
            if (nm && state.visitor.name) nm.value = state.visitor.name;
            if (sc && state.visitor.social) sc.value = state.visitor.social;
            syncLayoutButtons();
            const lb = document.querySelector('.layout-btn[data-layout="' + state.layout + '"]');
            if (lb) setActive('.layout-btn', lb);
        }
    } catch (e) {}

    render();
})();
</script>
@endpush
@endsection
