@extends('layouts.app')

@section('title', "Pilih Desain — HOLD' MOMENT")

@section('content')
<section class="safe-x max-w-6xl mx-auto px-6 pt-10 pb-14 page-enter">
    <h1 class="page-title">Pilih Desain Strip</h1>
    <p class="text-brand-muted mt-2 text-sm">Foto sudah lengkap — tinggal pilih desain favoritmu, lalu simpan.</p>

    <div class="flex items-center gap-2 text-[11px] font-bold mt-6" id="stepBarDesain" role="list" aria-label="Tahap pembuatan photostrip"></div>

    <div class="mt-6 grid lg:grid-cols-[1fr_320px] gap-6 items-start">
        {{-- Kiri: preview + simpan.
             lg:top-24 (bukan top-4) karena navbar sticky ~81px — dengan top-4
             bagian atas panel ini tertutup navbar. --}}
        <div class="card p-6 lg:sticky lg:top-24">
            <div class="flex items-center justify-between">
                <p class="font-bold text-sm">Strip Preview</p>
                <span id="previewLayoutBadge" class="text-xs font-bold bg-brand-orange text-white rounded-full px-3 py-1"></span>
            </div>
            <p id="previewDesignLabel" class="mt-1 text-xs text-brand-muted">Desain: Polos</p>
            <canvas id="stripPreview" class="mt-4 w-full max-w-[420px] mx-auto rounded-2xl border border-black/10"></canvas>
            <p id="saveError" class="hidden mt-3 text-sm text-red-700" role="alert"></p>
            <div class="mt-4 flex flex-wrap gap-3">
                <a href="{{ route('camera') }}" class="btn btn-outline btn-md font-semibold">
                    <x-icon name="arrow-left" class="h-4 w-4" /> Ganti Foto
                </a>
                <button id="btnSave" type="button" class="btn btn-primary btn-md flex-1 min-w-52">
                    Simpan &amp; Lihat Hasil
                </button>
            </div>
        </div>

        {{-- Kanan: daftar desain (difilter sesuai jumlah foto).
             role=radiogroup supaya state terpilih bisa dibacakan AT; sebelum
             ini pemilihan hanya terlihat lewat warna/centang. --}}
        <aside class="card p-6">
            <div class="flex items-center justify-between mb-3">
                <p class="font-bold text-sm">Desain Strip</p>
                <span id="designCountDesain" class="text-xs text-brand-muted"></span>
            </div>
            <div class="grid grid-cols-2 gap-3 max-h-[32rem] overflow-y-auto pr-1"
                 id="designListDesain" role="radiogroup" aria-label="Pilih desain strip">
                <button data-template="" role="radio" aria-checked="true"
                    class="design-card relative rounded-xl border border-brand-orange bg-brand-orange/10 overflow-hidden text-left transition hover:shadow-card">
                    <span class="tick absolute top-2 right-2 w-6 h-6 rounded-full bg-brand-orange text-white flex items-center justify-center shadow"><x-icon name="check" class="w-3.5 h-3.5" /></span>
                    <span class="block h-28 bg-brand-card-light flex items-center justify-center text-brand-muted text-2xl font-bold">—</span>
                    <span class="block p-2.5 bg-white">
                        <span class="block text-sm font-bold">Polos</span>
                        <span class="block text-[11px] text-brand-muted">Cream bawaan</span>
                    </span>
                </button>
                <button data-template="receipt" data-variant="receipt" role="radio" aria-checked="false"
                       class="design-card relative rounded-xl border border-brand-line-strong overflow-hidden text-left transition hover:border-brand-orange/50 hover:shadow-card">
                    <span class="tick hidden absolute top-2 right-2 z-10 w-6 h-6 rounded-full bg-brand-orange text-white flex items-center justify-center shadow"><x-icon name="check" class="w-3.5 h-3.5" /></span>
                    <span class="block h-28 bg-white border-b border-black/10 flex flex-col items-center justify-center gap-[3px] px-8">
                        <span class="w-full h-[2px] bg-black/80"></span>
                        <span class="w-2/3 h-[2px] bg-black/50"></span>
                        <span class="w-full h-[2px] bg-black/50"></span>
                        <span class="w-1/2 h-[2px] bg-black/80"></span>
                    </span>
                    <span class="block p-2.5 bg-white">
                        <span class="block text-sm font-bold">Receipt</span>
                        <span class="block text-[11px] text-brand-muted">2 strip kembar • 4 foto</span>
                    </span>
                </button>
                <button data-template="receipt" data-variant="receipt_2x3" role="radio" aria-checked="false"
                       class="design-card relative rounded-xl border border-brand-line-strong overflow-hidden text-left transition hover:border-brand-orange/50 hover:shadow-card">
                    <span class="tick hidden absolute top-2 right-2 z-10 w-6 h-6 rounded-full bg-brand-orange text-white flex items-center justify-center shadow"><x-icon name="check" class="w-3.5 h-3.5" /></span>
                    <span class="block h-28 bg-white border-b border-black/10 flex flex-col items-center justify-center gap-[3px] px-8">
                        <span class="w-full h-[2px] bg-black/80"></span>
                        <span class="w-2/3 h-[2px] bg-black/50"></span>
                        <span class="w-full h-[2px] bg-black/50"></span>
                        <span class="w-1/2 h-[2px] bg-black/80"></span>
                    </span>
                    <span class="block p-2.5 bg-white">
                        <span class="block text-sm font-bold">Receipt 2x3</span>
                        <span class="block text-[11px] text-brand-muted">2 strip kembar • 6 foto</span>
                    </span>
                </button>
                @forelse($templates as $t)
                    <button data-template="{{ $t->id }}" data-layout="{{ $t->layout_type }}" role="radio" aria-checked="false"
                       class="design-card relative rounded-xl border border-brand-line-strong overflow-hidden text-left transition hover:border-brand-orange/50 hover:shadow-card">
                        <span class="tick hidden absolute top-2 right-2 w-6 h-6 rounded-full bg-brand-orange text-white flex items-center justify-center shadow"><x-icon name="check" class="w-3.5 h-3.5" /></span>
                        @if($t->background_image)
                            <img src="{{ asset('storage/' . $t->background_image) }}" alt="{{ $t->name }}" class="block h-28 w-full object-cover bg-brand-card-light" onerror="this.style.visibility='hidden'">
                        @elseif($t->frame_image)
                            <img src="{{ asset('storage/' . $t->frame_image) }}" alt="{{ $t->name }}" class="block h-28 w-full object-cover bg-brand-card-light" onerror="this.style.visibility='hidden'">
                        @else
                            <span class="block h-28 bg-brand-card-light flex items-center justify-center text-xs">—</span>
                        @endif
                        <span class="block p-2.5 bg-white">
                            <span class="block text-sm font-bold leading-tight">{{ $t->name }}</span>
                            <span class="inline-block mt-1 text-[10px] font-bold bg-brand-card-light rounded-full px-2 py-0.5">{{ $t->layout_type }}</span>
                            <span class="text-[11px] text-brand-muted ml-1">@if($t->background_image)bg @endif @if($t->frame_image)frame @endif</span>
                        </span>
                    </button>
                @empty
                    <p class="col-span-2 text-xs text-brand-muted bg-brand-card-light rounded-xl p-3">Belum ada desain. Admin bisa upload lewat panel admin → Template.</p>
                @endforelse
            </div>
        </aside>
    </div>
</section>

@push('scripts')
<script>
(function () {
    // Ambil titipan dari /camera; tanpa itu kembali ke camera.
    let stash = null;
    try { stash = JSON.parse(sessionStorage.getItem('holdmoment_stash') || 'null'); } catch (e) {}
    if (!stash || !Array.isArray(stash.shots) || !stash.shots.length
        || !stash.visitor || !stash.visitor.name || !stash.visitor.social) {
        window.location.href = "{{ route('camera') }}";
        return;
    }

    const TEMPLATES = @json($designs);
    const stripPreview = document.getElementById('stripPreview');
    const previewLayoutBadge = document.getElementById('previewLayoutBadge');
    const previewDesignLabel = document.getElementById('previewDesignLabel');
    const designCountDesain = document.getElementById('designCountDesain');
    const btnSave = document.getElementById('btnSave');

    const state = {
        visitor: stash.visitor,
        layout: (stash.layout && ['2x2', '2x3'].includes(stash.layout)) ? stash.layout : '2x2',
        filter: 'none',
        timer: 3,
        mirror: false,
        templateId: null, // null = Polos
        builtin: 'plain', // 'plain' | 'receipt'
        shots: stash.shots,
        stream: null,
        busy: false,
    };
    const BASE_LAYOUT = state.layout; // format dari /camera (acuan filter & kembali dari receipt)
    const LAYOUT_LABEL = { '2x2': '2x2', '2x3': '2x3', 'receipt': 'Receipt', 'receipt_2x3': 'Receipt 2x3' };

    @include('components.strip-engine')

    // Hanya desain yang cocok dengan jumlah foto: template seformat,
    // Polos selalu, Receipt khusus 4 foto (butuh 2x2 kembar prosedural).
    // Saat mode receipt, daftar tetap memakai format asal (BASE_LAYOUT) agar
    // desain lain tidak hilang — klik template kembali ke formatnya sendiri.
    function filterDesignsDesain() {
        let n = 0;
        const effLayout = state.builtin === 'receipt' ? BASE_LAYOUT : state.layout;
        document.querySelectorAll('.design-card').forEach(b => {
            const id = b.dataset.template || '';
            let show = true;
            if (id === 'receipt') show = !!(LAYOUTS[b.dataset.variant] && LAYOUTS[b.dataset.variant].total === state.shots.length);
            else if (id !== '') show = b.dataset.layout === effLayout;
            b.classList.toggle('hidden', !show);
            if (show) n++;
        });
        if (designCountDesain) designCountDesain.textContent = n + ' pilihan utk ' + effLayout;
    }

    function markActiveDesain(btn) {
        document.querySelectorAll('.design-card').forEach(x => {
            const on = x === btn;
            x.classList.toggle('border-brand-orange', on);
            x.classList.toggle('bg-brand-orange/10', on);
            x.classList.toggle('border-brand-line-strong', !on);
            // State terpilih harus bisa dibaca AT, bukan hanya dilihat.
            x.setAttribute('aria-checked', on ? 'true' : 'false');
            x.setAttribute('tabindex', on ? '0' : '-1');
            x.querySelectorAll('.tick').forEach(t => t.classList.toggle('hidden', !on));
        });
    }

    // Navigasi panah di dalam radiogroup (pola WAI-ARIA untuk radio group).
    document.getElementById('designListDesain').addEventListener('keydown', (e) => {
        if (!['ArrowRight', 'ArrowLeft', 'ArrowDown', 'ArrowUp'].includes(e.key)) return;
        const cards = [...document.querySelectorAll('.design-card')].filter(c => !c.classList.contains('hidden'));
        const i = cards.indexOf(document.activeElement);
        if (i < 0) return;
        e.preventDefault();
        const step = (e.key === 'ArrowRight' || e.key === 'ArrowDown') ? 1 : -1;
        cards[(i + step + cards.length) % cards.length].focus();
    });

    function paintStepsDesain() {
        const bar = document.getElementById('stepBarDesain');
        if (!bar) return;
        const designed = state.templateId !== null || state.builtin === 'receipt';
        const steps = [[1, 'Jepret', true], [2, 'Desain', true], [3, 'Simpan', designed]];
        let html = '';
        steps.forEach((st, i) => {
            const lit = st[2];
            // Status tahap dibacakan lewat aria-label, bukan hanya lewat warna.
            const status = lit ? 'selesai' : 'belum selesai';
            html += '<span role="listitem" class="flex items-center gap-1.5' + (lit ? '' : ' opacity-40') + '"'
                + ' aria-label="Tahap ' + st[0] + ', ' + st[1] + ': ' + status + '">'
                + '<span aria-hidden="true" class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-bold '
                + (lit ? 'bg-brand-orange text-white' : 'bg-brand-card-light text-brand-muted') + '">'
                + (st[0] === 3 && !lit ? st[0] : (lit ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>' : st[0])) + '</span>'
                + '<span aria-hidden="true" class="' + (lit ? 'text-brand-text' : 'text-brand-muted') + '">' + st[1] + '</span></span>';
            if (i < steps.length - 1) html += '<span aria-hidden="true" class="flex-1 h-[2px] rounded ' + (lit ? 'bg-brand-orange' : 'bg-black/10') + '"></span>';
        });
        bar.innerHTML = html;
    }

    function renderDesain() {
        filterDesignsDesain();
        paintStepsDesain();
        if (previewLayoutBadge) previewLayoutBadge.textContent = LAYOUT_LABEL[state.layout] || state.layout;
        let name = 'Polos';
        if (state.builtin === 'receipt') name = (state.layout === 'receipt_2x3' ? 'Receipt 2x3' : 'Receipt') + ' (2 strip kembar)';
        else {
            const t = selectedTemplate();
            if (t) name = t.name + ' (' + t.layout_type + ')';
        }
        if (previewDesignLabel) previewDesignLabel.textContent = 'Desain: ' + name + ' • ' + state.visitor.name;
        drawStripPreview();
    }

    document.querySelectorAll('.design-card').forEach(b => b.addEventListener('click', () => {
        const id = b.dataset.template || '';
        if (id === 'receipt') {
            const variant = b.dataset.variant || 'receipt';
            if (!LAYOUTS[variant] || LAYOUTS[variant].total !== state.shots.length) return;
            state.builtin = 'receipt';
            state.templateId = null;
            state.layout = variant; // mode struk kembar prosedural (2 / 2x3 baris per strip)
        } else {
            state.builtin = 'plain';
            const tpl = TEMPLATES.find(t => String(t.id) === String(id)) || null;
            state.templateId = tpl ? tpl.id : null;
            // Format dikunci ke asal (daftar difilter, jadi selalu cocok).
            state.layout = BASE_LAYOUT;
        }
        markActiveDesain(b);
        renderDesain();
    }));

    // Label tombol asli disimpan supaya bisa dikembalikan persis seperti semula
    // (spinner + teks) tanpa kehilangan ikon apa pun.
    const SAVE_IDLE = btnSave.innerHTML;
    const SPINNER = '<svg class="h-4 w-4 animate-hm-spin shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">'
        + '<circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2.5" opacity=".25"/>'
        + '<path d="M21 12a9 9 0 0 0-9-9" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg>';

    btnSave.addEventListener('click', async () => {
        if (state.busy) return;
        state.busy = true;
        // Tombol harus benar-benar nonaktif selama proses; sebelumnya hanya
        // dijaga flag `busy`, jadi masih tampak bisa diklik.
        btnSave.disabled = true;
        btnSave.setAttribute('aria-busy', 'true');
        btnSave.innerHTML = SPINNER + ' Menyimpan…';
        try {
            const image = await fullResStrip();
            if (image.length > 4 * 1024 * 1024) throw new Error('Gambar kebesaran, coba lagi.');
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
            try { data = JSON.parse(text); } catch { throw new Error('Server error (' + res.status + '): ' + text.slice(0, 300)); }
            if (!res.ok) throw new Error(data.message || 'Gagal menyimpan (' + res.status + ')');
            try { sessionStorage.removeItem('holdmoment_stash'); } catch (e) {}
            window.toast('Foto berhasil disimpan', 'success');
            window.location.href = data.redirect;
        } catch (e) {
            console.error(e);
            window.toast(e.message.includes('Failed to fetch') ? 'Gagal terhubung ke server. Cek koneksi / coba lagi.' : e.message, 'error');
            state.busy = false;
            btnSave.disabled = false;
            btnSave.removeAttribute('aria-busy');
            btnSave.innerHTML = SAVE_IDLE;
            btnSave.focus();
        }
    });

    if (previewLayoutBadge) previewLayoutBadge.textContent = state.layout + ' • ' + state.shots.length + ' foto';
    markActiveDesain(document.querySelector('.design-card[data-template=""]'));
    renderDesain();
})();
</script>
@endpush
@endsection
