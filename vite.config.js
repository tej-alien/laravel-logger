import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from '@tailwindcss/vite';
import { writeFileSync } from 'fs';

function mixManifestPlugin() {
    return {
        name: 'mix-manifest',
        closeBundle() {
            writeFileSync('public/mix-manifest.json', JSON.stringify({
                '/app.js': '/app.js',
                '/app.css': '/app.css',
            }, null, 2));
        },
    };
}

export default defineConfig({
    plugins: [
        laravel({ input: ['resources/js/app.js'], refresh: true }),
        vue(),
        tailwindcss(),
        mixManifestPlugin(),
    ],
    build: {
        outDir: 'public',
        emptyOutDir: false,
        manifest: false,
        rollupOptions: {
            output: {
                entryFileNames: '[name].js',
                chunkFileNames: '[name].js',
                assetFileNames: '[name][extname]',
            },
        },
    },
});
