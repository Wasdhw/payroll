/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        'savio-blue': '#003366',
        'savio-gold': '#D4AF37',
      },
    },
  },
  plugins: [],
}