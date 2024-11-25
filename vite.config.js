import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
            refresh: true,
        }),
    ],
    // Added to resolve cors issues in safari as it is a bit stricter than other browsers
    build: {
        manifest: true,
        rollupOptions: {
            input: 'resources/js/app.js',
        },
    }
});
