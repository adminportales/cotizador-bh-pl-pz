/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ["./resources/**/*.blade.php", "./node_modules/flowbite/**/*.js"],
    theme: {
        extend: {},
        colors: {
            primary: {
              500: '#09343f',
            },
            secondary: {
              500: '#1FAFD3',
            },
          }
    },
    plugins: [require("flowbite/plugin")],
};
