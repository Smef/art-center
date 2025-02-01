<script lang="ts" setup>
import MobileTopNav from "@/Components/Nav/MobileTopNav.vue";
import SideNav from "@/Components/Nav/SideNav.vue";
import { usePage } from "@inertiajs/vue3";
import * as Sentry from "@sentry/vue";
import { User } from "@sentry/vue";
import { useStorage } from "@vueuse/core";
import { computed, ref } from "vue";
import Toast from "primevue/toast";
import ConfirmDialog from "primevue/confirmdialog";
import useColorScheme from "@/Composables/useColorScheme";

// Track the user for Sentry reporting
const page = usePage();
const user = (page.props.auth.user as User) ?? false;
if (user) {
    Sentry.setUser({
        email: user.email,
        name: user.name,
    });
}

// handle color scheme
const { setColorModeToUserPreference } = useColorScheme();
setColorModeToUserPreference();

// inline composable for code organization
function useSideNavStatus() {
    const sidenavVisible = ref(false);

    // storage can only store text, so we'll retrieve it and then use a computed property to convert it to a boolean for convenience
    const sidenavExpandedStorage = useStorage("sidenav-expanded", "true");
    const sidenavExpanded = ref(sidenavExpandedStorage.value === "true");

    function toggleSidenavVisible() {
        sidenavVisible.value = !sidenavVisible.value;
    }

    const mainLeftPadding = computed(() => (sidenavExpanded.value ? "lg:pl-48" : "lg:pl-20"));

    return {
        sidenavVisible,
        sidenavExpanded,
        toggleSidenavVisible,
        mainLeftPadding,
    };
}

const { sidenavVisible, sidenavExpanded, toggleSidenavVisible, mainLeftPadding } = useSideNavStatus();
</script>

<template>
    <div class="">
        <Toast />
        <ConfirmDialog />

        <!-- Mobile top nav bar -->
        <MobileTopNav
            class="lg:hidden"
            :sidenav-visible="sidenavVisible"
            @toggle-sidenav="toggleSidenavVisible"
        />

        <!-- Main Nav -->
        <SideNav
            class="fixed top-0 z-10 lg:fixed lg:left-0 lg:w-fit"
            v-if="$page.props.auth ?? false"
            v-model:visible="sidenavVisible"
            :expanded="sidenavExpanded"
            @change-expanded="(expanded) => (sidenavExpanded = expanded)"
        />

        <!-- Page Content -->
        <main
            id="main"
            class="z-0 flex min-h-dvh flex-col py-8 transition-all @container lg:py-12"
            :class="mainLeftPadding"
        >
            <div class="flex grow flex-col px-4 lg:px-12">
                <slot />
            </div>
        </main>
    </div>
</template>
