import type { Config } from 'tailwindcss'

const config: Config = {
  content: [
    './pages/**/*.{js,ts,jsx,tsx,mdx}',
    './components/**/*.{js,ts,jsx,tsx,mdx}',
    './app/**/*.{js,ts,jsx,tsx,mdx}',
  ],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        ramadan: {
          blue: '#5b76ff',
          'blue-dark': '#3351e9',
          slate: '#334155',
          ice: '#e3ebff',
          'ice-dark': '#c8d7ff',
          snow: '#f1f5ff',
          gold: '#F3CD3B',
          'gold-light': '#F7D95C',
        },
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
      },
    },
  },
  plugins: [],
}
export default config
