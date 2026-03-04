import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.tsx',
        './resources/js/**/*.ts',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                background: "var(--background)",
                foreground: "var(--foreground)",
                skeleton: "var(--skeleton)",
                border: "var(--btn-border)",
                input: "var(--input)",
            },
            borderRadius: {
                DEFAULT: "0.5rem",
            },
            boxShadow: {
                input: [
                    "0px 2px 3px -1px rgba(0, 0, 0, 0.1)",
                    "0px 1px 0px 0px rgba(25, 28, 33, 0.02)",
                    "0px 0px 0px 1px rgba(25, 28, 33, 0.08)",
                ].join(", "),
            },
            maxWidth: {
                container: "1280px",
            },
            animation: {
                marquee: 'marquee var(--duration) linear infinite',
                ripple: "ripple 2s ease calc(var(--i, 0) * 0.2s) infinite",
                orbit: "orbit calc(var(--duration) * 1s) linear infinite",
            },
            keyframes: {
                marquee: {
                    from: { transform: 'translateX(0)' },
                    to: { transform: 'translateX(calc(-100% - var(--gap)))' }
                },
                ripple: {
                    "0%, 100%": { transform: "translate(-50%, -50%) scale(1)" },
                    "50%": { transform: "translate(-50%, -50%) scale(0.9)" },
                },
                orbit: {
                    "0%": {
                        transform: "rotate(0deg) translateY(calc(var(--radius) * 1px)) rotate(0deg)",
                    },
                    "100%": {
                        transform: "rotate(360deg) translateY(calc(var(--radius) * 1px)) rotate(-360deg)",
                    },
                }
            }
        },
    },

    plugins: [forms],
};
