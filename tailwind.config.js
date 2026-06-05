/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './*.php',
    './template-parts/**/*.php',
    './blocks/**/*.php',
    './src/**/*.{js,jsx,ts,tsx}',
    './src/scss/**/*.scss',
  ],
  // Prefix all Tailwind classes to avoid conflicts with WordPress/Gutenberg
  prefix: 'tw-',
  theme: {
    extend: {
      colors: {
        'brand-blue':      '#114CA0',
        'brand-gold':      '#D4A857',
        'brand-gold-dark': '#B8943D',
        'brand-navy':      '#102060',
        brand: {
          50:  '#f0f4ff',
          100: '#dde6ff',
          200: '#c3d0ff',
          300: '#9ab0ff',
          400: '#6a83fc',
          500: '#4457f8',
          600: '#2d36ed',
          700: '#2428d1',
          800: '#2124a9',
          900: '#212585',
          950: '#16174f',
        },
        iron: {
          50:  '#f6f7f9',
          100: '#eceef2',
          200: '#d5d9e3',
          300: '#b0b9ca',
          400: '#8594ac',
          500: '#657693',
          600: '#516079',
          700: '#424e63',
          800: '#394354',
          900: '#333b48',
          950: '#21262f',
        },
      },
      fontFamily: {
        sans:      ['franklin-gothic', 'Arial', 'sans-serif'],
        serif:     ['clarendon-urw', 'Georgia', 'serif'],
        clarendon: ['clarendon-urw', 'Georgia', 'serif'],
        franklin:  ['franklin-gothic', 'Arial', 'sans-serif'],
        condensed: ['franklin-gothic-condensed', 'Arial', 'sans-serif'],
        mono:      ['JetBrains Mono', 'ui-monospace', 'monospace'],
      },
      spacing: {
        section: '5rem',
      },
      borderRadius: {
        '4xl': '2rem',
      },
    },
  },
  plugins: [],
};
