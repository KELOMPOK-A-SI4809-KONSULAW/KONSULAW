/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
  ],
  theme: {
    extend: {
      fontFamily: {
        display: ['Fraunces', 'Georgia', 'serif'],
        sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
      },
      colors: {
        ivory: '#FAF6EF',
        paper: '#FFFFFF',
        ink: '#1A1412',
        'ink-soft': '#6B5E57',
        line: '#E7DED2',
        accent: {
          DEFAULT: '#C8321E',
          dark: '#9E2415',
          soft: '#FBE3DC',
        },
        glow: '#F2542D',
        navy: '#1E2A5E',
        dark: '#1A1412',
        success: '#15803D',
        info: '#1D4ED8',
        warning: '#B45309',
        danger: '#8A1538',
        neutral: '#6B7280',
      },
      borderRadius: {
        card: '20px',
        img: '16px',
        input: '14px',
      },
      boxShadow: {
        card: '0 1px 0 rgba(26,20,18,.04)',
        btn: '0 6px 16px rgba(200,50,30,.25)',
      },
    },
  },
  plugins: [],
};
