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
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                // Opcional: Fuente elegante para títulos
                display: ['Oswald', 'sans-serif'],
            },
            // AQUI AGREGAMOS TUS COLORES
            colors: {
                barber: {
                    DEFAULT: '#d4af37',    // Dorado principal (bg-barber)
                    gold: '#d4af37',       // Alias por compatibilidad (text-barber-gold)
                    'gold-hover': '#b5952f', // Dorado más oscuro para hovers
                    hover: '#b5952f',      // Alias para hover:bg-barber-hover
                    dark: '#1a1a1a',       // Negro elegante
                    panel: '#2d2d2d',      // Gris oscuro para tarjetas
                    light: '#f4f6f9',      // Fondo claro para el admin
                }
            }
        },
    },

    plugins: [forms],
};
