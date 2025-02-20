/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ["./src/**/*.{php,html,js,jsx}"],
    theme: {
        extend: {

            animation: {
                fade: 'fadeIn .5s ease-in-out',
            },

            keyframes: {
                fadeIn: {
                    from: { opacity: 0 },
                    to: { opacity: 1 },
                },
            },
        },
    },
    plugins: [],
}
