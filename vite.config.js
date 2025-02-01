import { sentryVitePlugin } from "@sentry/vite-plugin";
import { defineConfig, loadEnv } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import VueDevTools from "vite-plugin-vue-devtools";

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd());
    const host = env.VITE_DEV_HOST ?? "localhost";

    return {
        build: {
            sourcemap: true,
        },
        plugins: [
            VueDevTools({
                appendTo: "app.ts",
            }),
            laravel({
                input: "resources/js/app.ts",
                refresh: true,
            }),
            vue({
                template: {
                    transformAssetUrls: {
                        // The Vue plugin will re-write asset URLs, when referenced
                        // in Single File Components, to point to the Laravel web
                        // server. Setting this to `null` allows the Laravel plugin
                        // to instead re-write asset URLs to point to the Vite
                        // server instead.
                        base: null,

                        // The Vue plugin will parse absolute URLs and treat them
                        // as absolute paths to files on disk. Setting this to
                        // `false` will leave absolute URLs un-touched so they can
                        // reference assets in the public directory as expected.
                        includeAbsolute: false,
                    },
                },
            }),

            sentryVitePlugin({
                // Put the Sentry vite plugin after all other plugins
                authToken: env.VITE_SENTRY_AUTH_TOKEN,
                org: "gearbox",
                project: "phase-1-kit",
            }),
        ],
        server: {
            // enable HTTPS if a key and cert are provided in the env
            host,
            https: env.VITE_DEV_KEY_PATH
                ? {
                      key: env.VITE_DEV_KEY_PATH,
                      cert: env.VITE_DEV_CERTIFICATE_PATH,
                  }
                : false,
            hmr: { host },
        },
    };
});
