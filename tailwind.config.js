/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./storage/framework/views/*.php",
  ],
  theme: {
    extend: {
      colors: {
        'brand-bg': '#F6F4EE',
        'brand-orange': '#D95B32',
        'brand-orange-hover': '#C44E27',
        'brand-dark': '#1C1C1C',
        'brand-card-light': '#EFECE6',
        'brand-text': '#1A1A1A',
        'brand-muted': '#737373',
      },
      fontFamily: {
        serif: ['"Playfair Display"', 'Georgia', 'serif'],
        sans: ['"Plus Jakarta Sans"', 'Inter', 'sans-serif'],
      },
    },
  },
  plugins: [],
}
