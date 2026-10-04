import { execFileSync } from 'node:child_process';

import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';

function generateWayfinderRoutes() {
    return {
        name: 'generate-wayfinder-routes',

        buildStart() {
            console.log('\n[Wayfinder] Generating routes...');

            execFileSync(
                process.platform === 'win32' ? 'php.exe' : 'php',
                ['artisan', 'wayfinder:generate', '--with-form'],
                {
                    stdio: 'inherit',
                },
            );
        },
    };
}

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.ts'],
            ssr: 'resources/js/ssr.ts',
            refresh: true,
        }),

        tailwindcss(),

        generateWayfinderRoutes(),

        vue({
            transformAssetUrls: {
                base: null,
                includeAbsolute: false,
            },
        }),
    ],
});
