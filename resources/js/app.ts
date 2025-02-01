// Core
import "./bootstrap";
import "../css/app.css";
import AppLayout from "@/Layouts/AppLayout.vue";
import { createApp, h, type DefineComponent } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";

// PrimeVue
import PrimeVue from "primevue/config";
import Aura from "@primevue/themes/aura";
import PrimeVueThemeCustomizations from "@/PrimeVueThemeCustomizations";
import { definePreset } from "@primevue/themes";
import ConfirmationService from "primevue/confirmationservice";
import ToastService from "primevue/toastservice";

// Sentry
import { init as sentryInit, addIntegration, browserTracingIntegration } from "@sentry/vue";

// Customize the PrimeVue Theme by applying our changes to the default Aura theme
const customTheme = definePreset(Aura, PrimeVueThemeCustomizations);

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

// Lazy-load the session replay to reduce bundle size of app.js pretty significantly
async function lazyLoadSentryIntegrations() {
    const { replayIntegration } = await import("@sentry/vue");
    addIntegration(
        replayIntegration({
            maskAllText: false,
            blockAllMedia: false,
        }),
    );
}

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: async (name) => {
        const page = await resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob<DefineComponent>("./Pages/**/*.vue"),
        );
        page.default.layout = page.default.layout || AppLayout;
        return page;
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(ConfirmationService)
            .use(PrimeVue, {
                theme: {
                    preset: customTheme,
                    options: {
                        cssLayer: {
                            name: "primevue",
                            order: "tailwind-base, primevue, tailwind-utilities",
                        },
                        darkModeSelector: ".dark",
                    },
                },
            })
            .use(ToastService);

        sentryInit({
            app,
            dsn: import.meta.env.VITE_SENTRY_DSN,
            integrations: [browserTracingIntegration()],

            // Set tracesSampleRate to 1.0 to capture 100%
            // of transactions for performance monitoring.
            // We recommend adjusting this value in production
            tracesSampleRate: import.meta.env.VITE_SENTRY_TRACES_SAMPLE_RATE || 0.0,

            // Set `tracePropagationTargets` to control for which URLs distributed tracing should be enabled
            // tracePropagationTargets: ["localhost", /^https:\/\/yourserver\.io\/api/],

            // This sets the sample rate to be 10%. You may want this to be 100% while
            // in development and sample at a lower rate in production
            replaysSessionSampleRate: import.meta.env.VITE_SENTRY_REPLAY_SAMPLE_RATE || 0.0,

            // If the entire session is not sampled, use the below sample rate to sample
            // sessions when an error occurs.
            replaysOnErrorSampleRate: import.meta.env.VITE_SENTRY_ERROR_REPLAY_SAMPLE_RATE || 0.0,
        });

        app.mount(el);
        return app;
    },
    progress: {
        color: "#179AD6",
    },
}).then(() => {
    lazyLoadSentryIntegrations();
});
