/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./src/**/*.{html,js}"],
  theme: {
    extend: {
      fontFamily: {
        regular: ["Cooper"],
      },
      margin: {
        '-25': '-25px', // Adding custom margin for -25px
        '30': '30px',    // Adding custom margin for 30px
      },
    },
  },
  plugins: [],
};
