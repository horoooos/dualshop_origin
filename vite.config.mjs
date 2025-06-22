import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { viteStaticCopy } from 'vite-plugin-static-copy';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        viteStaticCopy({
            targets: [
                {
                    src: 'resources/images/*',
                    dest: 'images'
                }
            ]
        })
    ],
    server: {
        hmr: {
            host: 'localhost',
        },
    },
    build: {
        outDir: 'public/build',
        assetsDir: 'assets',
        manifest: true,
        rollupOptions: {
            input: [
                'resources/css/app.css',
                'resources/js/app.js'
            ]
        }
    }
});
