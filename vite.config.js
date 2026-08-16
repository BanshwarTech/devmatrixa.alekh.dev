import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/pages/home.js',
                'resources/js/pages/contact.js',
                'resources/js/pages/alt-checker.js',
                'resources/js/pages/anchor-text-analyzer.js',
                'resources/js/pages/broken-image-finder.js',
                'resources/js/pages/color-palette.js',
                'resources/js/pages/css-to-tailwind.js',
                'resources/js/pages/css-variable-scanner.js',
                'resources/js/pages/dns-lookup.js',
                'resources/js/pages/faq-extractor.js',
                'resources/js/pages/font-detector.js',
                'resources/js/pages/heading-checker.js',
                'resources/js/pages/link-checker.js',
                'resources/js/pages/og-preview.js',
                'resources/js/pages/page-weight.js',
                'resources/js/pages/redirect-chain.js',
                'resources/js/pages/schema-extractor.js',
                'resources/js/pages/script-audit.js',
                'resources/js/pages/security-headers.js',
                'resources/js/pages/seo-analyzer.js',
                'resources/js/pages/sitemap-diff.js',
                'resources/js/pages/tailwind-extractor.js',
                'resources/js/pages/tech-stack-detector.js',
                'resources/js/pages/tracker-inventory.js',
                'resources/js/pages/typography-seo-checker.js',
            ],
            refresh: true,
        }),
        vue(),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
