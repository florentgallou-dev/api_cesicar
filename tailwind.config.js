/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    fontFamily: {
      'quicksand': ['"Quicksand"', 'sans-serif'],
    },
    colors: {
      transparent: 'transparent',
      current: 'currentColor',
      green: '#00FF00',
      red: '#FF0000',
      black: '#11293C',
      orange: '#F97729',
      blue: '#434AFE',
      yellow: '#FFE22D',
      white: '#ffffff',
      violet: {
          dark2: '#4695D4',
          dark1: '#4695D4',
          DEFAULT: '#4C50C7',
          light1: '#4975DE',
          light2: '#4695D4',
      },
      bleur: {
          dark2: '#0B61DE',
          dark1: '#0C9FE8',
          DEFAULT: '#01C4D1',
          light1: '#0CE8B9',
          light2: '#0BDE76',
      },
      grey: {
          from: '#D7DBE0',
          dark: '#97A3B5',
          DEFAULT: '#C9D5E1',
          light: '#EDF3F9',
          to: '#F3F6FD',
      }
    },
    extend: {},
  },
  plugins: [],
}

