module.exports = {
    content: [
        './templates/**/*.html.twig',
        './assets/**/*.js',
    ],
    theme: {
        extend: {
            backdropBlur: {
                sm: '3px',
            },
            minHeight: {
                '80vh': '80vh',
            },
        },
    },
    plugins: [],
}
