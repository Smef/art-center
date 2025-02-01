/// <reference types="vite/client" />

interface ImportMetaEnv {
    readonly VITE_APP_NAME: string;
    readonly VITE_SENTRY_DSN: string;
    readonly VITE_SENTRY_TRACES_SAMPLE_RATE: number;
    readonly VITE_SENTRY_REPLAY_SAMPLE_RATE: number;
    readonly VITE_SENTRY_ERROR_REPLAY_SAMPLE_RATE: number;
    readonly VITE_DEV_KEY_PATH: string;
    readonly VITE_DEV_CERTIFICATE_PATH: string;
}

interface ImportMeta {
    readonly env: ImportMetaEnv;
}
