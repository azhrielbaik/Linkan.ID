/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/views/admin_seller/layouts/settings.blade.php',
    './resources/views/admin_seller/features/settings/**/*.blade.php',
    './resources/views/admin_seller/features/account/**/*.blade.php',
    './resources/views/admin_seller/features/payouts/**/*.blade.php',
  ],
  corePlugins: {
    preflight: false,
    container: false,
  },
  theme: {
    extend: {},
  },
  plugins: [],
}
