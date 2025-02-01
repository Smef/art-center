<script setup lang="ts">
import { Link } from "@inertiajs/vue3";

defineProps({
    label: {
        type: String,
        default: "",
    },
    icon: {
        type: Object,
        default: null,
    },
    href: {
        type: String,
        default: "",
    },
});
</script>

<template>
    <Link
        :href="href"
        class="flex w-48 items-center gap-x-3 px-6 py-5 hover:bg-surface-700"
    >
        <Component
            :is="icon"
            class="h-6 w-6 fill-white"
        />

        <span class="text-nowrap font-light text-white">{{ label }}</span>
    </Link>
</template>
