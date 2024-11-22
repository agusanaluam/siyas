import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { viteStaticCopy } from 'vite-plugin-static-copy'

export default defineConfig({
    build: {
        manifest: true,
        rtl: true,
        base: 'resources/',
        outDir: 'public/build/',
        cssCodeSplit: true,
        rollupOptions: {
            output: {
                assetFileNames: (css) => {
                    if (css.name.split('.').pop() == 'css') {
                        return 'css/' + `[name]` + '.min.' + 'css';
                    } else {
                        return 'icons/' + css.name;
                    }
                },
                entryFileNames: 'js/' + `[name]` + `.js`,
            },
        },
    },
    plugins: [
        laravel({
            input: [
                'resources/css/style.css',
                'resources/js/script.js'
                // 'resources/js/pages/dashboard.js',
                // 'resources/js/pages/donation-app.js',
                // 'resources/js/pages/profile.js',
                // 'resources/js/pages/campaign/campaign.js',
                // 'resources/js/pages/campaign/category.js',
                // 'resources/js/pages/donation/donation.js',
                // 'resources/js/pages/donation/mutation.js',
                // 'resources/js/pages/volunteer/group.js',
                // 'resources/js/pages/volunteer/volunteer.js',
                // 'resources/js/component/location.js'
            ],
            refresh: true,
        }),

        viteStaticCopy({
            targets: [
                {
                    src: 'resources/css',
                    dest: ''
                },
                {
                    src: 'resources/fonts',
                    dest: ''
                },
                {
                    src: 'resources/img',
                    dest: ''
                },
                {
                    src: 'resources/js',
                    dest: ''
                },
                {
                    src: 'resources/json',
                    dest: ''
                },
                {
                    src: 'resources/plugins',
                    dest: ''
                },
                {
                    src: 'resources/scss',
                    dest: ''
                },
            ]
        }),
    ],

});


