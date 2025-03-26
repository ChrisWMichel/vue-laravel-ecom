import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    define: {
        // Make sure environment variables are properly exposed
        'import.meta.env.VITE_API_URL': JSON.stringify(process.env.VITE_API_URL),
        'import.meta.env.VITE_APP_NAME': JSON.stringify(process.env.VITE_APP_NAME),
    },
});
