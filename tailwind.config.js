export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
    ],
    theme: {
        extend: {
            colors: {
                navy: {
                    DEFAULT: "#003d64",
                    light: "#0059a3",
                },
            },
            animation: {
                "spin-custom": "spin 1s linear infinite",
            },
        },
    },
    plugins: [],
};
