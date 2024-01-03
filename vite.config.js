import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/css/admin.css', 'resources/js/post.js', 'resources/js/createPost.js', 'resources/js/filtr.js', 'resources/js/profile.js', 'resources/js/history.js', 'resources/js/highlight.js'],
            refresh: true,
            server: {
                host: 'localhost',
            },
        }),
    ],
});
