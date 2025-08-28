import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    safelist: [
        // Background colors used by animated blobs
        'bg-indigo-300', 'bg-blue-300', 'bg-cyan-300',
        // Opacity variants used in JS
        'opacity-70', 'opacity-60', 'dark:opacity-40', 'dark:opacity-30', 'dark:opacity-25'
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
