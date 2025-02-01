<script setup lang="ts">
import BrandIcon from "@/Components/Icons/BrandIcon.vue";
import HamburgerButton from "@/Components/Nav/HamburgerButton.vue";
import { Link } from "@inertiajs/vue3";

defineProps({
    sidenavVisible: {
        type: Boolean,
        default: false,
    },
});
const emit = defineEmits(["toggle-sidenav"]);

function toggleSidebar() {
    emit("toggle-sidenav");
}
</script>

<template>
    <div class="sticky top-0 z-10">
        <!-- Top nav bar -->
        <div class="flex h-16 items-center justify-between bg-surface-900">
            <!-- Mobile hamburger button -->
            <HamburgerButton
                class="h-full px-4"
                :sidenav-open="sidenavVisible"
                @click="toggleSidebar"
            />

            <Link
                :href="route('dashboard')"
                class="flex h-full items-center px-4"
            >
                <BrandIcon class="h-auto w-8" />
            </Link>
        </div>
    </div>
</template>
