import forms from "@tailwindcss/forms";
import typography from "@tailwindcss/typography";
import tailwindcssPrimeUI from "tailwindcss-primeui";
import tailwindContainerQueries from "@tailwindcss/container-queries";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./vendor/laravel/jetstream/**/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
    ],
    darkMode: "selector",
    theme: {
        extend: {
            transitionDuration: {
                DEFAULT: "350ms",
            },
        },
    },

    plugins: [forms, typography, tailwindcssPrimeUI, tailwindContainerQueries],
};
