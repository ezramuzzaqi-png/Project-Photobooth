@extends('layouts.app')

@section('title', "Camera — HOLD' MOMENT")

@section('content')
<section class="safe-x max-w-6xl mx-auto px-6 pt-10 pb-14 page-enter" x-data>
    <h1 class="page-title">Photobooth Camera</h1>
    <p class="text-brand-muted mt-2 text-sm">Atur format & filter, jepret momen serumu, pilih desain, lalu simpan.</p>

    <div class="mt-8 grid lg:grid-cols-[84px_1fr_280px] gap-6 items-start">
        {{-- Sidebar vertikal: Strip / Filter / Timer + pop-up sub-menu.
             Sumber data TETAP tombol picker lama di panel kontrol (disembunyikan,
             dipakai sebagai sumber tunggal agar semua logika lama ikut jalan). --}}
        <div class="relative max-lg:order-first" id="toolRailWrap">
            <div class="card flex gap-1 p-2 lg:sticky lg:top-24 lg:flex-col lg:flex-row" id="toolRail">
                <button type="button" data-tool="strip" aria-expanded="false" aria-controls="toolPopup" aria-haspopup="dialog"
                    class="tool-btn flex-1 lg:w-full flex flex-col items-center gap-1 rounded-2xl px-2 py-3 transition-all duration-200">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-6 h-6" aria-hidden="true"><rect x="3.5" y="4.5" width="17" height="15" rx="2.5"/><path d="M12 4.5v15M3.5 12h17" stroke-linecap="round"/></svg>
                    <span class="text-xs font-bold leading-none">Strip</span>
                    <span class="tool-val text-[11px] leading-none opacity-70" id="toolValStrip">2x2</span>
                </button>
                <button type="button" data-tool="filter" aria-expanded="false" aria-controls="toolPopup" aria-haspopup="dialog"
                    class="tool-btn flex-1 lg:w-full flex flex-col items-center gap-1 rounded-2xl px-2 py-3 transition-all duration-200">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-6 h-6" aria-hidden="true"><path d="M4 8h8M18 8h2M4 16h2M12 16h8" stroke-linecap="round"/><circle cx="15" cy="8" r="2.2"/><circle cx="9" cy="16" r="2.2"/></svg>
                    <span class="text-xs font-bold leading-none">Filter</span>
                    <span class="tool-val text-[11px] leading-none opacity-70" id="toolValFilter">Normal</span>
                </button>
                <button type="button" data-tool="timer" aria-expanded="false" aria-controls="toolPopup" aria-haspopup="dialog"
                    class="tool-btn flex-1 lg:w-full flex flex-col items-center gap-1 rounded-2xl px-2 py-3 transition-all duration-200">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-6 h-6" aria-hidden="true"><circle cx="12" cy="13" r="7.5"/><path d="M12 9.5V13l2.5 1.5M9.5 3h5" stroke-linecap="round"/></svg>
                    <span class="text-xs font-bold leading-none">Timer</span>
                    <span class="tool-val text-[11px] leading-none opacity-70" id="toolValTimer">3s</span>
                </button>
            </div>
            <div id="toolPopup" role="dialog" aria-modal="false" aria-labelledby="toolPopupTitle" class="hidden absolute z-30 lg:left-full lg:top-0 lg:ml-3 lg:mt-0 left-0 top-full mt-3 w-[300px] max-w-[calc(100vw-3rem)] card p-5">
                <div class="flex items-center justify-between">
                    <p id="toolPopupTitle" class="font-bold text-sm">Pilih Strip</p>
                    <button type="button" id="toolPopupClose" class="w-8 h-8 rounded-full hover:bg-brand-card-light flex items-center justify-center transition" aria-label="Tutup (Esc)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18" stroke-linecap="round"/></svg>
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
                    <button data-layout="2x3" class="layout-btn border border-brand-line-strong rounded-xl px-2 py-2 text-sm font-semibold hover:border-brand-orange">2x3</button>
                </div>
            </div>
            <div class="hidden">
                <p class="font-bold text-sm mb-3">Filter Kamera</p>
                <div class="grid grid-cols-2 gap-2 max-h-64 overflow-y-auto pr-1" id="filterPicker">
                    <button data-filter="none" class="filter-btn border border-brand-orange bg-brand-orange text-white rounded-xl px-2 py-2 text-sm font-semibold">Normal</button>
                    <button data-filter="grayscale(1)" class="filter-btn border border-brand-line-strong rounded-xl px-2 py-2 text-sm font-semibold hover:border-brand-orange">B&W</button>
                    <button data-filter="grayscale(1) contrast(1.5) brightness(0.9)" class="filter-btn border border-brand-line-strong rounded-xl px-2 py-2 text-sm font-semibold hover:border-brand-orange">Noir</button>
                    <button data-filter="sepia(0.8) contrast(1.1) brightness(0.95)" class="filter-btn border border-brand-line-strong rounded-xl px-2 py-2 text-sm font-semibold hover:border-brand-orange">Vintage</button>
                    <button data-filter="sepia(1)" class="filter-btn border border-brand-line-strong rounded-xl px-2 py-2 text-sm font-semibold hover:border-brand-orange">Sepia</button>
                    <button data-filter="sepia(0.35) saturate(1.6) contrast(1.05)" class="filter-btn border border-brand-line-strong rounded-xl px-2 py-2 text-sm font-semibold hover:border-brand-orange">Warm</button>
                    <button data-filter="sepia(0.55) saturate(2.2) hue-rotate(-25deg)" class="filter-btn border border-brand-line-strong rounded-xl px-2 py-2 text-sm font-semibold hover:border-brand-orange">Sunset</button>
                    <button data-filter="saturate(1.8) contrast(1.2)" class="filter-btn border border-brand-line-strong rounded-xl px-2 py-2 text-sm font-semibold hover:border-brand-orange">Vivid</button>
                    <button data-filter="sepia(0.35) hue-rotate(170deg) saturate(1.8)" class="filter-btn border border-brand-line-strong rounded-xl px-2 py-2 text-sm font-semibold hover:border-brand-orange">Cool</button>
                    <button data-filter="brightness(0.8) contrast(1.3) saturate(0.85)" class="filter-btn border border-brand-line-strong rounded-xl px-2 py-2 text-sm font-semibold hover:border-brand-orange">Moody</button>
                    <button data-filter="brightness(1.3) contrast(1.02)" class="filter-btn border border-brand-line-strong rounded-xl px-2 py-2 text-sm font-semibold hover:border-brand-orange">Bright</button>
                    <button data-filter="sepia(0.3) contrast(0.85) brightness(1.12) saturate(0.8)" class="filter-btn border border-brand-line-strong rounded-xl px-2 py-2 text-sm font-semibold hover:border-brand-orange">Faded</button>
                    <button data-filter="invert(1)" class="filter-btn border border-brand-line-strong rounded-xl px-2 py-2 text-sm font-semibold hover:border-brand-orange">Negatif</button>
                </div>
            </div>
            <div class="hidden">
                <p class="font-bold text-sm mb-3">Delay Timer</p>
                <div class="grid grid-cols-3 gap-2" id="timerPicker">
                    <button data-timer="3" class="timer-btn border border-brand-orange bg-brand-orange text-white rounded-xl px-2 py-2 text-sm font-semibold">3s</button>
                    <button data-timer="5" class="timer-btn border border-brand-line-strong rounded-xl px-2 py-2 text-sm font-semibold hover:border-brand-orange">5s</button>
                    <button data-timer="10" class="timer-btn border border-brand-line-strong rounded-xl px-2 py-2 text-sm font-semibold hover:border-brand-orange">10s</button>
                </div>
            </div>
        </div>

        {{-- Tengah: Live Camera (tampil paling atas di HP) --}}
        <div class="card p-6 max-lg:order-first">
            <div id="cameraBox" class="relative rounded-2xl overflow-hidden bg-brand-dark aspect-[4/3]">
                <video id="webcam" autoplay playsinline muted class="w-full h-full object-cover" style="transform: none;"></video>
                {{-- Overlay angka hitung mundur. purely visual — screen reader
                     drowningnya lewat #liveRegion, supaya tidak dibaca 2×. --}}
                <div id="countdownOverlay" aria-hidden="true" class="absolute inset-0 z-20 hidden items-center justify-center bg-black/50 text-white font-serif font-extrabold text-8xl">3</div>
                <div id="captureFlash" class="absolute inset-0 z-10 bg-white opacity-0 pointer-events-none"></div>
                {{-- Hint kamera. Dibatasi max-width + dipindah ke atas tombol fullscreen:
                     tanpa ini teks 48 karakter ("Kamera aktif — atur format…")
                     melebihi lebar cameraBox yang overflow-hidden, sehingga
                     terpotong di layar sempit. --}}
                <p id="cameraHint"
                   class="pointer-events-none absolute inset-x-3 bottom-14 z-10 mx-auto max-w-[85%] rounded-2xl
                          bg-black/55 px-3 py-1.5 text-center text-[11px] leading-snug text-white/90 backdrop-blur
                          sm:inset-x-auto sm:bottom-3 sm:left-1/2 sm:right-auto sm:max-w-[75%] sm:-translate-x-1/2
                          sm:rounded-full sm:px-4 sm:text-xs">Kamera belum aktif — isi biodata dulu</p>
                <button id="fsToggleMini" type="button" title="Fullscreen (F)"
                        aria-label="Masuk mode fullscreen (tombol F)"
                        class="absolute bottom-3 right-3 z-10 w-10 h-10 rounded-full bg-black/55 hover:bg-black/80 text-white flex items-center justify-center backdrop-blur transition">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5" aria-hidden="true"><path d="M4 9V5.5A1.5 1.5 0 0 1 5.5 4H9M15 4h3.5A1.5 1.5 0 0 1 20 5.5V9M20 15v3.5a1.5 1.5 0 0 1-1.5 1.5H15M9 20H5.5A1.5 1.5 0 0 1 4 18.5V15" stroke-linecap="round"/></svg>
                </button>
                <button id="fsExitBtn" type="button" title="Keluar fullscreen (Esc)"
                        aria-label="Keluar dari mode fullscreen (tekan Esc)"
                        class="absolute top-3 right-3 z-10 w-9 h-9 rounded-full bg-black/55 hover:bg-black/80 text-white items-center justify-center backdrop-blur transition">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5" aria-hidden="true"><path d="M9 4v5.5A1.5 1.5 0 0 1 7.5 11H4M20 4h-4.5A1.5 1.5 0 0 0 14 5.5V9M15 20v-5.5a1.5 1.5 0 0 1 1.5-1.5H20M4 20h4.5A1.5 1.5 0 0 0 10 18.5V14" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
                {{-- Overlay UI fullscreen: menubar kiri layar + tombol shutter kanan tengah --}}
                <div id="fsUI" class="absolute inset-y-0 inset-x-0 z-10 items-center justify-between pl-5 pr-5">
                    <div id="fsMenuBar" class="flex flex-col items-stretch gap-2 w-auto max-h-[62vh] overflow-y-auto bg-black/55 backdrop-blur rounded-2xl px-2.5 py-2.5">
                        <div id="fsMainMenu" class="flex flex-col items-center gap-2">
                            <button type="button" data-fsmenu="filter" title="Filter" aria-label="Menu filter" class="fs-chip w-11 h-11 rounded-full text-white/90 bg-white/15 hover:bg-white/25 flex items-center justify-center">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5" aria-hidden="true"><path d="M4 5h16l-6.5 7.5V19l-3 1.5v-8L4 5z" stroke-linejoin="round"/></svg>
                            </button>
                            <button type="button" data-fsmenu="timer" title="Timer" aria-label="Menu timer" class="fs-chip w-11 h-11 rounded-full text-white/90 bg-white/15 hover:bg-white/25 flex items-center justify-center">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5" aria-hidden="true"><circle cx="12" cy="13" r="7.5"/><path d="M12 9.5V13l2.5 1.5M9.5 3h5" stroke-linecap="round"/></svg>
                            </button>
                        </div>
                        <div id="fsFilterMenu" class="hidden flex-col items-stretch gap-1.5 w-40">
                            <button data-fsback class="fs-chip w-full text-white/90 bg-white/15 hover:bg-white/25 rounded-full px-3 py-1.5 text-xs font-bold inline-flex items-center justify-center gap-1"><x-icon name="chevron-left" class="w-4 h-4" /> Kembali</button>
                        </div>
                        <div id="fsTimerMenu" class="hidden flex-col items-stretch gap-1.5 w-40">
                            <button data-fsback class="fs-chip w-full text-white/90 bg-white/15 hover:bg-white/25 rounded-full px-3 py-1.5 text-xs font-bold inline-flex items-center justify-center gap-1"><x-icon name="chevron-left" class="w-4 h-4" /> Kembali</button>
                        </div>
                    </div>
                    <button id="btnFsShutter" type="button" title="Jepret foto" aria-label="Jepret foto"
                            class="shrink-0 w-20 h-20 rounded-full bg-brand-orange text-white flex items-center justify-center hover:bg-brand-orange-hover">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="w-9 h-9" aria-hidden="true"><path d="M4 8h2.6L8.6 5.5h6.8L17.4 8H20A1.5 1.5 0 0 1 21.5 9.5V18a1.5 1.5 0 0 1-1.5 1.5H4A1.5 1.5 0 0 1 2.5 18V9.5A1.5 1.5 0 0 1 4 8z" stroke-linejoin="round"/><circle cx="12" cy="13.5" r="3.4"/></svg>
                    </button>
                </div>
            </div>
            <canvas id="captureCanvas" class="hidden"></canvas>
            <div class="mt-4 flex flex-wrap gap-3">
                <button id="btnCapture" type="button" disabled
                        class="btn btn-primary btn-md disabled:opacity-40">
                    Jepret Foto
                </button>
                <button id="btnRetakeOne" type="button"
                        class="btn btn-outline btn-md border-brand-line-strong">
                    <x-icon name="refresh" class="w-4 h-4" /> Retake terakhir
                </button>
                <button id="btnRetakeAll" type="button"
                        class="btn btn-outline btn-md border-brand-line-strong">
                    <x-icon name="refresh" class="w-4 h-4" /> Ulangi semua
                </button>
            </div>
            <div class="mt-4 flex flex-wrap items-center gap-3">
                <button id="mirrorToggle" type="button" aria-pressed="true"
                        class="rounded-full border border-brand-line-strong px-5 py-2.5 text-sm font-semibold hover:border-brand-orange">
                    Mirror: ON — preview seperti kaca
                </button>
                <p class="text-xs text-brand-muted"><span class="font-bold text-brand-text" id="visitorLabel">Pengunjung: -</span> <span class="mx-1" aria-hidden="true">•</span> <span id="progressLabel">0 / 4 foto terisi</span></p>
            </div>
            <p id="captureError" class="hidden mt-3 text-sm text-red-700" role="alert"></p>
        </div>

        {{-- Kanan: Preview strip (otomatis ikut pilihan format) --}}
        <aside class="card p-6">
            <div class="flex items-center gap-2 text-[11px] font-bold mb-4" id="stepBar" role="list" aria-label="Tahap pembuatan photostrip"></div>
            <div class="flex items-center justify-between">
                <p class="font-bold text-sm">Strip Preview</p>
                <span id="previewLayoutBadge" class="text-xs font-bold bg-brand-orange text-white rounded-full px-3 py-1">2x2</span>
            </div>
            <p id="previewDesignLabel" class="mt-1 text-xs text-brand-muted">Desain: Polos</p>
            <canvas id="stripPreview" class="mt-4 w-full rounded-2xl border border-black/10"></canvas>
            <button id="btnNext" type="button" disabled class="btn btn-dark mt-4 w-full disabled:opacity-40">
                Pilih Desain <x-icon name="arrow-right" class="w-4 h-4" />
            </button>
            <p class="mt-2 text-xs text-brand-muted">Setelah slot penuh, klik Selanjutnya untuk menggabung + mengirim ke server.</p>
        </aside>
    </div>
</section>

{{-- Step 1: Modal Biodata (mandatori).
     Dialog sungguhan: role/aria-modal, focus di dalam form, scroll body
     terkunci, dan konten di belakang dibuat inert supaya tidak bisa di-tab
     atau dibaca screen reader lewat. --}}
<div id="biodataModal"
     class="fixed inset-0 z-50 overflow-y-auto bg-black/50 p-4"
     role="dialog" aria-modal="true"
     aria-labelledby="biodataTitle" aria-describedby="biodataDesc">
    <div class="flex min-h-full items-center justify-center">
        <form id="biodataForm" class="card w-full max-w-md p-8">
            <h2 id="biodataTitle" class="text-center font-serif text-2xl font-extrabold">HOLD' MOMENT</h2>
            <p id="biodataDesc" class="mt-1 text-center text-sm text-brand-muted">Isi biodata dulu sebelum kamera aktif</p>

            <label for="visitorName" class="label mt-6">Nama Lengkap</label>
            <input id="visitorName" name="visitor_name" type="text" required autocomplete="name"
                   placeholder="Nama kamu" class="input" aria-required="true">

            <label for="visitorSocial" class="label mt-4">Media Sosial / Instagram</label>
            <input id="visitorSocial" name="visitor_social" type="text" required autocomplete="username"
                   placeholder="@username" class="input" aria-required="true">

            <button type="submit" class="btn btn-primary btn-md mt-6 w-full">Lanjutkan ke Kamera</button>
        </form>
    </div>
</div>

{{-- Pengumuman untuk screen reader (countdown, status kamera, galat).
     Visually hidden tapi tetap dibaca AT. --}}
<div id="liveRegion" class="sr-only" role="status" aria-live="polite" aria-atomic="true"></div>

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
    const liveRegion = document.getElementById('liveRegion');

    /** Pengumuman singkat ke screen reader (countdown, status, galat). */
    function announce(text) {
        if (liveRegion) liveRegion.textContent = text;
    }

    // Desain strip dari database (di-upload admin) + URL frame overlay-nya
    const TEMPLATES = @json($designs);

    const state = {
        visitor: { name: '', social: '' },
        layout: '2x2',
        filter: 'none',
        timer: 3,
        mirror: true, // default ON agar preview front-camera tidak mirror (teks tidak kebalik); klik toggle untuk balik
        // Di halaman ini desain BELUM dipilih (memangnya terjadi di /desain),
        // tapi kedua field tetap ada karena components/strip-engine.blade.php
        // membacanya — di sini nilainya selalu kosong/"plain".
        templateId: null,
        builtin: 'plain',
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
            // State harus terbaca AT, bukan cuma dari warna.
            btn.setAttribute('aria-pressed', on ? 'true' : 'false');
            btn.classList.toggle('bg-brand-orange', on);
            btn.classList.toggle('text-white', on);
            btn.classList.toggle('border-brand-orange', on);
            btn.classList.toggle('border-brand-line-strong', !on);
        }
    }

    /* ---------- Modal biodata: focus trap + latar inert ----------
       Tanpa ini, pengguna keyboard bisa me-tab ke navbar di belakang modal dan
       screen reader masih membaca konten yang seharusnya tersembunyi.

       Catatan struktur: modal ini berada DI DALAM elemen main (dirender lewat
       section 'content'), jadi main tidak boleh di-inert — kalau begitu modal
       ikut mati dan field biodata tidak bisa diklik/diisi. Target inert dipilih
       per-elemen di luar modal, bukan per-induknya. */
    const FOCUSABLE = 'a[href],button:not([disabled]),input:not([disabled]),select:not([disabled]),textarea:not([disabled]),[tabindex]:not([tabindex="-1"])';

    function backdropTargets() {
        const out = [];
        document.querySelectorAll('body > header, body > footer, body > main > *').forEach(el => {
            // Lewati modal itu sendiri beserta section yang memuatnya.
            if (el === modal || el.contains(modal)) return;
            out.push(el);
        });
        return out;
    }

    function lockBackdrop() {
        document.body.classList.add('overflow-hidden');
        backdropTargets().forEach(el => { el.inert = true; });
    }
    function unlockBackdrop() {
        document.body.classList.remove('overflow-hidden');
        backdropTargets().forEach(el => { el.inert = false; });
    }

    lockBackdrop();
    // Fokus awal setelah browser sempat menggambar ulang (inert baru berlaku
    // setelah style recalc, jadi langsung .focus() bisa gagal diam-diam).
    requestAnimationFrame(() => {
        document.getElementById('visitorName')?.focus();
    });

    document.addEventListener('keydown', (e) => {
        if (modal.classList.contains('hidden') || e.key !== 'Tab') return;
        const f = [...form.querySelectorAll(FOCUSABLE)].filter(el => el.offsetParent !== null);
        if (!f.length) return;
        const first = f[0], last = f[f.length - 1];
        if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
        else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
    });

    @include('components.strip-engine')

    function setActive(selector, btn) {
        document.querySelectorAll(selector).forEach(b => {
            b.classList.remove('bg-brand-orange', 'text-white', 'border-brand-orange');
            b.classList.add('border-brand-line-strong');
        });
        btn.classList.add('bg-brand-orange', 'text-white', 'border-brand-orange');
        btn.classList.remove('border-brand-line-strong');
    }

    function syncLayoutButtons() {
        document.querySelectorAll('.layout-btn').forEach(x => {
            const on = x.dataset.layout === state.layout;
            x.classList.toggle('bg-brand-orange', on);
            x.classList.toggle('text-white', on);
            x.classList.toggle('border-brand-orange', on);
            x.classList.toggle('border-brand-line-strong', !on);
        });
    }

    document.querySelectorAll('.layout-btn').forEach(b => b.addEventListener('click', () => {
        state.layout = b.dataset.layout;
        // Ganti format selalu memulai sesi jepretan dari nol.
        // (Pemilihan desain dipindah ke halaman /desain, jadi tidak ada lagi
        //  reset template di sini seperti pada versi sebelum alur 2 halaman.)
        state.shots = [];
        setActive('.layout-btn', b);
        announce('Format diubah ke ' + state.layout + '. Foto yang sudah diambil dihapus.');
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
        unlockBackdrop();
        btnCapture.focus();
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
            announce('Kamera aktif. Tekan Jepret Foto untuk memulai.');
        } catch (err) {
            errBox.textContent = 'Gagal mengakses kamera: ' + err.message;
            errBox.classList.remove('hidden');
            announce('Gagal mengakses kamera. ' + err.message);
        }
    }

    function countdown(seconds) {
        return new Promise((resolve) => {
            let n = seconds;
            overlay.classList.remove('hidden');
            overlay.classList.add('flex');
            overlay.textContent = n;
            announce('Hitung mundur ' + n);
            const tick = setInterval(() => {
                n -= 1;
                if (n <= 0) {
                    clearInterval(tick);
                    overlay.classList.add('hidden');
                    overlay.classList.remove('flex');
                    announce('Mengambil foto');
                    resolve();
                } else {
                    overlay.textContent = n;
                    announce('Hitung mundur ' + n);
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
        // Kilau_oranye menandakan proses berjalan (lihat #btnCapture.is-busy).
        btnCapture.classList.add('is-busy');
        await countdown(state.timer);
        try {
            state.shots.push(captureFrame());
            render();
            if (typeof flashCapture === 'function') flashCapture();
            announce('Foto ' + state.shots.length + ' dari ' + requiredTotal() + ' terambil.');
        } catch (e) {
            errBox.textContent = 'Gagal mengambil foto: ' + e.message;
            errBox.classList.remove('hidden');
            announce('Gagal mengambil foto. ' + e.message);
        }
        state.busy = false;
        btnCapture.classList.remove('is-busy');
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
            announce('Gagal masuk fullscreen. ' + e.message);
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
        card.className = 'strip-card w-full flex items-center gap-3 rounded-xl border border-brand-line-strong p-3 text-left transition hover:border-brand-orange';
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
    const FILTER_THUMB = 'linear-gradient(135deg,#BE4A1C 0%,#F5C518 35%,#2E9E5B 65%,#3B82F6 100%)';
    document.querySelectorAll('#filterPicker .filter-btn').forEach(orig => {
        const f = orig.dataset.filter;
        const name = orig.textContent.trim();
        const item = document.createElement('button');
        item.dataset.filterVal = f;
        item.className = 'f-item flex flex-col items-center gap-1.5';
        item.title = name;
        item.innerHTML = '<span class="f-dot block w-14 h-14 rounded-full border-2 border-brand-line-strong transition hover:border-brand-orange" style="background:' + FILTER_THUMB + ';filter:' + f + '"></span>'
            + '<span class="f-label text-[11px] font-semibold leading-tight text-center">' + name + '</span>';
        item.addEventListener('click', () => { orig.click(); });
        filterBody.appendChild(item);
    });

    // --- Pop-up Timer: pil durasi dari .timer-btn yang sudah ada ---
    document.querySelectorAll('#timerPicker .timer-btn').forEach(orig => {
        const pill = document.createElement('button');
        pill.dataset.timerVal = orig.dataset.timer;
        pill.className = 't-pill flex-1 rounded-xl border border-brand-line-strong py-3 text-sm font-bold transition hover:border-brand-orange';
        pill.textContent = orig.textContent.trim();
        pill.addEventListener('click', () => { orig.click(); closeTool(); });
        timerBody.appendChild(pill);
    });

    function paintRail() {
        document.querySelectorAll('.tool-btn').forEach(b => {
            b.classList.toggle('is-open', b.dataset.tool === openTool);
            b.setAttribute('aria-expanded', b.dataset.tool === openTool ? 'true' : 'false');
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
    document.querySelectorAll('.tool-btn').forEach(b => {
        b.addEventListener('click', (e) => { e.stopPropagation(); openToolPop(b.dataset.tool); });
        // Tiap sub-menu dipindah fokusnya ke heading popup supaya pengguna
        // keyboard langsung sampai ke isi (bukan harus menebak dari tombol rail).
        b.addEventListener('keydown', (e) => {
            if (e.key !== 'Enter' && e.key !== ' ') return;
            e.preventDefault();
            openToolPop(b.dataset.tool);
            toolPopup.querySelector('.focus\\:outline-none, button, [href], input, select')?.focus();
        });
    });
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
            c.classList.toggle('border-brand-line-strong', !on);
            c.classList.toggle('text-brand-text', !on);
        });
        filterBody.querySelectorAll('.f-item').forEach(it => {
            const on = it.dataset.filterVal === state.filter;
            const dot = it.querySelector('.f-dot'), lab = it.querySelector('.f-label');
            dot.classList.toggle('border-brand-orange', on);
            dot.classList.toggle('scale-110', on);
            dot.classList.toggle('shadow-lg', on);
            dot.classList.toggle('border-brand-line-strong', !on);
            lab.classList.toggle('text-brand-orange', on);
        });
        timerBody.querySelectorAll('.t-pill').forEach(p => {
            const on = parseInt(p.dataset.timerVal, 10) === state.timer;
            p.classList.toggle('bg-brand-orange', on);
            p.classList.toggle('text-white', on);
            p.classList.toggle('border-brand-orange', on);
            p.classList.toggle('border-brand-line-strong', !on);
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
        if (!state.shots.length) return;
        state.shots.pop();
        render();
        btnCapture.disabled = false;
        announce('Foto terakhir dihapus. ' + state.shots.length + ' foto terisi.');
    });
    btnRetakeAll.addEventListener('click', () => {
        state.shots = [];
        render();
        btnCapture.disabled = !state.stream;
        announce('Semua foto dihapus. Mulai dari awal.');
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
                // Status per tahap dibacakan AT lewat aria-label, bukan hanya
                // lewat warna/ikon centang.
                const status = done ? 'selesai' : (active ? 'sedang berjalan' : 'belum selesai');
                html += '<span role="listitem" class="flex items-center gap-1.5' + ((active || done) ? '' : ' opacity-40') + '"'
                    + ' aria-label="Tahap ' + n + ', ' + st[1] + ': ' + status + '">'
                    + '<span aria-hidden="true" class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-bold '
                    + (done ? 'bg-brand-orange text-white' : (active ? 'border-2 border-brand-orange text-brand-orange' : 'bg-brand-card-light text-brand-muted')) + '">'
                    + (done ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>' : n) + '</span>'
                    + '<span aria-hidden="true" class="' + ((active || done) ? 'text-brand-text' : 'text-brand-muted') + '">' + st[1] + '</span></span>';
                if (i < steps.length - 1) html += '<span aria-hidden="true" class="flex-1 h-[2px] rounded ' + ((n === 1 && full) ? 'bg-brand-orange' : 'bg-black/10') + '"></span>';
            });
            bar.innerHTML = html;
        }
    }

    function render() {
        syncToolUI(); // sidebar + pop-up ikut status state mutakhir
        paintSteps(); // indikator tahap 1 (tahap 2-3 di halaman desain)
        progressLabel.textContent = state.shots.length + ' / ' + requiredTotal() + ' foto terisi';
        if (previewLayoutBadge) previewLayoutBadge.textContent = state.layout === 'receipt' ? 'Receipt' : state.layout;
        // Desain BELUM dipilih di halaman ini — pemilihan terjadi di /desain
        // setelah semua slot terisi. label di sini karena itu selalu "Polos".
        if (previewDesignLabel) previewDesignLabel.textContent = 'Desain: belum dipilih';
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
            announce('Gagal melanjutkan ke halaman desain. Penyimpanan browser penuh.');
            return;
        }
        announce('Membuka halaman pilihan desain…');
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
