import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import vueDevTools from 'vite-plugin-vue-devtools'

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vueDevTools(),
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
    server: {
        cors: true,
        hmr: {
            host: process.env.HMR_HOST || 'localhost',
            protocol: process.env.HMR_PROTOCOL || 'http', // Ensures HTTPS on live, http on local
        },
    },
    build: {
        manifest: true,
        rollupOptions: {
            input: 'resources/js/app.js',
        },
    }
});
