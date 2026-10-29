import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

/* ==========================================================================
   HOLD' MOMENT — Utility global (toast, back-to-top, page transition)
   ========================================================================== */

/* Toast notifikasi: pemanggilan toast('Pesan', 'success'|'error'|'info') */
window.toast = function (text, type = 'info') {
    const container = document.getElementById('toastContainer');
    if (!container) return;

    const el = document.createElement('div');
    el.className = 'toast ' + (type === 'error' ? 'toast.error' : type === 'success' ? 'toast.success' : 'toast.info');

    const icon = type === 'error'
        ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6M9 9l6 6" stroke-linecap="round"/></svg>'
        : type === 'success'
        ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>'
        : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01" stroke-linecap="round"/></svg>';

    el.innerHTML =
        '<div class="toast-icon ' + (type === 'error' ? 'toast-icon.error' : type === 'success' ? 'toast-icon.success' : 'toast-icon.info') + '">' + icon + '</div>' +
        '<div class="toast-body">' +
        '<div class="toast-title">' + (type === 'error' ? 'Gagal' : type === 'success' ? 'Berhasil' : 'Info') + '</div>' +
        '<div class="toast-desc">' + text + '</div>' +
        '</div>' +
        '<button type="button" class="shrink-0 p-1 text-brand-muted hover:text-brand-orange" aria-label="Tutup notifikasi">' +
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>' +
        '</button>';

    container.appendChild(el);
    // Reflow lalu tambahkan class show
    el.offsetHeight;
    el.classList.add('show');

    // Hapus setelah 4 detik (atau saat tombol tutup diklik)
    const timer = setTimeout(() => { el.classList.remove('show'); setTimeout(() => el.remove(), 300); }, 4000);
    el.querySelector('button').addEventListener('click', () => {
        clearTimeout(timer);
        el.classList.remove('show');
        setTimeout(() => el.remove(), 300);
    });
};

/* Back-to-top: muncul saat scroll > 400px */
(function () {
    const btn = document.getElementById('backToTop');
    if (!btn) return;
    let ticking = false;
    function onScroll() {
        if (window.scrollY > 400) btn.classList.add('show');
        else btn.classList.remove('show');
        ticking = false;
    }
    window.addEventListener('scroll', () => {
        if (!ticking) {
            window.requestAnimationFrame(onScroll);
            ticking = true;
        }
    }, { passive: true });
    btn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
})();

/* Page entry animation: halaman baru muncul dengan fade + geser kecil. */
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.page-enter').forEach(el => {
        el.classList.add('page-enter-active');
    });
});