<script setup lang="ts">
// This only works on Chrome / Android, unfortunately.
// This event is not supported on iOS.

import ButtonPrimary from "@/Components/ButtonPrimary.vue";
import { ref } from "vue";

const showInstallButton = ref(false);

let installPrompt: Event | null = null;

window.addEventListener("beforeinstallprompt", (event) => {
    // event.preventDefault();
    installPrompt = event;
    showInstallButton.value = true;
});

function disableInAppInstallPrompt() {
    installPrompt = null;
    showInstallButton.value = false;
}

async function displayInstallPrompt() {
    if (!installPrompt) {
        return;
    }
    disableInAppInstallPrompt();
}

window.addEventListener("appinstalled", () => {
    disableInAppInstallPrompt();
});
</script>

<template>
    <ButtonPrimary
        id="install"
        v-if="showInstallButton"
        @click="displayInstallPrompt"
        >Install App
    </ButtonPrimary>
</template>
