import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';
import { viteStaticCopy } from 'vite-plugin-static-copy';
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
    base: '/',
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue(),
        tailwindcss(),
        viteStaticCopy({
            targets: [
                {
                    src: [
                        'public/build/assets/*.png',
                        'public/build/assets/*.jpg',
                        'public/build/assets/*.svg',
                        'public/build/assets/*.eot',
                        'public/build/assets/*.ttf',
                        'public/build/assets/*.woff',
                        'public/build/assets/*.woff2',
                    ],
                    dest: '../assets'
                }
            ]
        })
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),
        },
    },
});
