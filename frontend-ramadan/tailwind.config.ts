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
          navy: '#1B2A4A',
          'navy-light': '#2D4373',
          brown: '#5C4033',
          'brown-light': '#8B7355',
          sage: '#9CAF88',
          'sage-dark': '#7A9568',
          green: '#7CAE7A',
          'green-dark': '#5C8A5A',
          gold: '#C49B4E',
          'gold-light': '#D4AF37',
          cream: '#F5F0E8',
          'cream-dark': '#E8DFD0',
          beige: '#FAF6F0',
          warm: '#6B5B4E',
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
