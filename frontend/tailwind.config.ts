import type { Config } from 'tailwindcss'

const config: Config = {
  content: [
    './pages/**/*.{js,ts,jsx,tsx,mdx}',
    './components/**/*.{js,ts,jsx,tsx,mdx}',
    './app/**/*.{js,ts,jsx,tsx,mdx}',
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          50: '#f1f5ff',
          100: '#e3ebff',
          200: '#c8d7ff',
          300: '#a5baff',
          400: '#7d95ff',
          500: '#5b76ff',
          600: '#3351e9',
          700: '#1e3fc7',
          800: '#1b35a3',
          900: '#182d86',
        },
        brand: {
          300: '#6FA2F1',
          400: '#4D89EC',
          500: '#1E63E9', // utama (biru)
          600: '#194FBA',
        },
        accent: {
          400: '#3DD2BF', // toska lembut
          500: '#18BCA8', // pelengkap toska
          600: '#139A8A',
        },
        support: {
          400: '#F98FB0',
          500: '#F65A8D', // aksen (pink)
          600: '#E24078',
        },
        highlight: {
          400: '#F7D95C',
          500: '#F3CD3B', // kuning lembut sebagai aksen kecil
          600: '#DCB528',
        },
      },
    },
  },
  plugins: [],
}
export default config

