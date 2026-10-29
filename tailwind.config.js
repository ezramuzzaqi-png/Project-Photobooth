/**
 * HOLD' MOMENT — Design Tokens
 *
 * Palet oranye dipilih ulang agar lolos WCAG AA:
 *   brand-orange #BE4A1C → teks putih di atasnya 5.02:1 (AA)
 *   brand-orange #BE4A1C → teks oranye di atas brand-bg 4.60:1 (AA)
 *   brand-muted  #66604F → di atas brand-bg 5.75:1 (AA)
 *
 * brand-orange-bright (#D95B32) hanya untuk kebutuhan dekoratif
 * (gradasi, glow, pulse) — JANGAN dipakai untuk teks.
 */
/** @type {import('tailwindcss').Config} */
export default {
  // Catatan: pernah ada welcome.blade.php (sisa default Laravel, tidak pernah
  // di-route). File itu sudah dihapus — kalau dig reinstate, jangan ikut
  // discan karena membawa ratusan class mati (Figtree, size-12, dst).
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./storage/framework/views/*.php",
  ],
  // Class yang dinyalakan/dimatikan lewat JS di camera.blade.php & desain.blade.php.
  // Kilang CSS hanya memindai teks file, jadi class ini diamankan agar tidak ter-purge.
  safelist: [
    'bg-brand-orange', 'bg-brand-orange/10', 'text-brand-orange',
    'text-brand-text', 'text-white', 'border-brand-orange',
    'border-brand-line-strong', 'bg-brand-line-strong',
    'hover:bg-brand-card-light', 'active', 'fs-active', 'is-active',
    'accent-brand-orange',
  ],
  theme: {
    extend: {
      colors: {
        'brand-bg': '#F7F5F0',
        'brand-surface': '#FFFFFF',
        'brand-card-light': '#EFEAE1',
        'brand-orange': '#BE4A1C',
        'brand-orange-hover': '#A93C15',
        'brand-orange-bright': '#D95B32',
        'brand-orange-soft': '#FBEDE5',
        'brand-dark': '#1C1917',
        'brand-text': '#17140F',
        'brand-muted': '#66604F',
        // brand-line = pembatas dekoratif (halus, sengaja tipis).
        'brand-line': '#E5DFD3',
        // brand-line-strong = pembatas komponen INTERAKTIF (input, tombol chip,
        // kontrol yang bisa difokus). #948669 → 3.58:1 di putih, 3.28:1 di
        // brand-bg. Wajib ≥3:1 menurut WCAG 1.4.11 Non-text Contrast, yang
        // tidak bisa dipenuhi brand-line (#E5DFD3 hanya 1.33:1).
        'brand-line-strong': '#948669',
        // brand-orange-ink = teks oranye di atas latar orange/10.
        // brand-orange (#BE4A1C) hanya 4.37:1 di sana — gagal AA untuk teks
        // kecil. brand-orange-hover (#A93C15) → 5.47:1.
        'brand-orange-ink': '#A93C15',
      },
      fontFamily: {
        serif: ['"Playfair Display"', 'Georgia', 'serif'],
        sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
      },
      fontSize: {
        '2xs': ['0.6875rem', { lineHeight: '1rem' }],
      },
      boxShadow: {
        card: '0 1px 2px rgba(23,20,15,.04), 0 8px 24px -12px rgba(23,20,15,.12)',
        lift: '0 2px 4px rgba(23,20,15,.04), 0 18px 40px -18px rgba(23,20,15,.22)',
        glow: '0 10px 30px -10px rgba(190,74,28,.45)',
        inset: 'inset 0 1px 0 rgba(255,255,255,.6)',
      },
      borderRadius: {
        '4xl': '2rem',
      },
      keyframes: {
        // PENTING: keyframe reveal TIDAK boleh mulai dari opacity 0.
        // Kalau fill-mode "both" + animation-delay, elemen akan tertahan
        // tak terlihat selama delay — dan kalau animasi tidak pernah jalan
        // (headless, throttling, reduced-motion parsial) konten hilang total.
        // Karena itu reveal hanya menganimasikan transform; opacity selalu 1.
        rise: {
          from: { transform: 'translateY(14px)' },
          to: { transform: 'translateY(0)' },
        },
        'fade-in': { from: { opacity: '0' }, to: { opacity: '1' } },
        'pop-in': {
          from: { opacity: '0', transform: 'scale(.94)' },
          to: { opacity: '1', transform: 'scale(1)' },
        },
        float: {
          '0%, 100%': { transform: 'translateY(0) rotate(var(--tilt, -3deg))' },
          '50%': { transform: 'translateY(-10px) rotate(var(--tilt, -3deg))' },
        },
        shimmer: { '100%': { transform: 'translateX(100%)' } },
        // Kilau "menyibak" untuk tombol yang sedang memproses.
        sheen: {
          '0%': { transform: 'translateX(-100%)' },
          '100%': { transform: 'translateX(100%)' },
        },
        // Tekanan fisik saat tombol ditekan (dikombinasikan dengan active:).
        press: { '0%': { transform: 'scale(1)' }, '100%': { transform: 'scale(.96)' } },
        spin: { to: { transform: 'rotate(360deg)' } },
      },
      animation: {
        rise: 'rise .55s cubic-bezier(.22,1,.36,1) both',
        'fade-in': 'fade-in .4s ease both',
        'pop-in': 'pop-in .35s cubic-bezier(.22,1,.36,1) both',
        float: 'float 7s ease-in-out infinite',
        sheen: 'sheen 1.2s ease-in-out infinite',
        press: 'press .12s ease-out',
        'hm-spin': 'spin .7s linear infinite',
      },
      transitionTimingFunction: {
        spring: 'cubic-bezier(.22,1,.36,1)',
      },
    },
  },
  plugins: [],
}