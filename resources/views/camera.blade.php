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
                <p class="font-bold text-sm mb-3">Desain Strip</p>
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
                <div class="grid grid-cols-2 gap-2" id="filterPicker">
                    <button data-filter="none" class="filter-btn border border-brand-orange bg-brand-orange text-white rounded-xl px-2 py-2 text-sm font-semibold">Normal</button>
                    <button data-filter="grayscale(1)" class="filter-btn border border-gray-300 rounded-xl px-2 py-2 text-sm font-semibold hover:border-brand-orange">B&W</button>
                    <button data-filter="sepia(0.8)" class="filter-btn border border-gray-300 rounded-xl px-2 py-2 text-sm font-semibold hover:border-brand-orange">Vintage</button>
                    <button data-filter="sepia(0.35) saturate(1.6) contrast(1.05)" class="filter-btn border border-gray-300 rounded-xl px-2 py-2 text-sm font-semibold hover:border-brand-orange">Warm</button>
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

        {{-- Tengah: Live Camera --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm">
            <div class="relative rounded-2xl overflow-hidden bg-brand-dark aspect-[4/3]">
                <video id="webcam" autoplay playsinline muted class="w-full h-full object-cover" style="transform: none;"></video>
                <div id="countdownOverlay" class="absolute inset-0 hidden items-center justify-center bg-black/50 text-white font-serif font-extrabold text-8xl">3</div>
                <p id="cameraHint" class="absolute bottom-3 left-1/2 -translate-x-1/2 text-white/80 text-xs bg-black/40 rounded-full px-4 py-1">Kamera belum aktif — isi biodata dulu</p>
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
    TEMPLATES.forEach(t => {
        if (t.frame_url) {
            const img = new Image();
            img.onload = () => { frameCache[t.id] = img; if (String(state.templateId) === String(t.id)) drawStripPreview(); };
            img.onerror = () => { delete frameCache[t.id]; };
            img.src = t.frame_url;
        }
        if (t.background_url) {
            const bg = new Image();
            bg.onload = () => { backgroundCache[t.id] = bg; if (String(state.templateId) === String(t.id)) drawStripPreview(); };
            bg.onerror = () => { delete backgroundCache[t.id]; };
            bg.src = t.background_url;
        }
    });

    function selectedTemplate() {
        return TEMPLATES.find(t => String(t.id) === String(state.templateId)) || null;
    }

    const LAYOUTS = {
        '2x2': { cols: 2, rows: 2, total: 4 },
        '2x3': { cols: 2, rows: 3, total: 6 },
        'receipt': { cols: 2, rows: 2, total: 4 }, // 2 strip kembar, tiap strip 1 kolom × 2 foto
    };

    function requiredTotal() { return LAYOUTS[state.layout].total; }

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

    btnCapture.addEventListener('click', async () => {
        if (state.busy || !state.stream) return;
        if (state.shots.length >= requiredTotal()) return;
        state.busy = true;
        btnCapture.disabled = true;
        await countdown(state.timer);
        try {
            state.shots.push(captureFrame());
            render();
        } catch (e) {
            errBox.textContent = 'Gagal mengambil foto: ' + e.message;
            errBox.classList.remove('hidden');
        }
        state.busy = false;
        btnCapture.disabled = state.shots.length >= requiredTotal();
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

    function render() {
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

    // Geometri strip untuk lebar W (s = skala, preview 600px / simpan 1200px)
    function geomFor(W) {
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
        // Render ulang resolusi tinggi (1200px) dengan mesin yang sama → file = preview
        // JPEG 0.85 biar payload < 2MB (PNG 1200px bisa >5MB → gagal di Railway)
        return Promise.all(state.shots.map(loadShot)).then((imgs) => {
            const cv = document.createElement('canvas');
            paintStrip(cv, 1200, imgs);
            return cv.toDataURL('image/jpeg', 0.85);
        });
    }

    btnNext.addEventListener('click', async () => {
        if (btnNext.disabled || state.busy) return;
        state.busy = true;
        btnNext.textContent = 'Menyimpan...';
        try {
            const image = await fullResStrip();
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const res = await fetch("{{ route('photo.save') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                body: JSON.stringify({
                    visitor_name: state.visitor.name,
                    visitor_social: state.visitor.social,
                    template_id: state.templateId,
                    layout_type: state.layout,
                    image: image,
                }),
            });
            const data = await res.json();
            if (!res.ok) throw new Error(data.message || 'Gagal menyimpan foto.');
            window.location.href = data.redirect;
        } catch (e) {
            errBox.textContent = e.message;
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
