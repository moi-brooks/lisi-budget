import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                primary: {
                    DEFAULT: '#0f172a', // Slate 900 - High contrast text/elements
                    muted: '#64748b',   // Slate 500 - Secondary text
                },
                accent: {
                    DEFAULT: '#2563eb', // Blue 600 - Action color
                    hover: '#1d4ed8',   // Blue 700
                    soft: '#eff6ff',    // Blue 50
                },
                surface: {
                    50: '#f8fafc',
                    100: '#f1f5f9',
                    200: '#e2e8f0',
                },
                status: {
                    pending: '#9a3412', // Orange 800 - AA Contrast on white
                    'pending-bg': '#fff7ed',
                    approved: '#166534', // Green 800 - AA Contrast on white
                    'approved-bg': '#f0fdf4',
                    rejected: '#991b1b', // Red 800 - AA Contrast on white
                    'rejected-bg': '#fef2f2',
                },
            },
            fontFamily: {
                display: ['Manrope', ...defaultTheme.fontFamily.sans],
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                mono: ['JetBrains Mono', ...defaultTheme.fontFamily.mono],
            },
            borderRadius: {
                '3xl': '1.5rem',
                '4xl': '2rem',
                '5xl': '3rem',
            },
            boxShadow: {
                'premium': '0 10px 30px -12px rgba(15, 23, 42, 0.08)',
                'premium-hover': '0 20px 40px -15px rgba(15, 23, 42, 0.12)',
            }
        },
    },

    plugins: [forms],
};
