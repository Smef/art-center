<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    active: Boolean,
    href: String,
    as: String,
});

const classes = computed(() =>
    props.active
        ? "block w-full pl-3 pr-4 py-2 border-l-4 border-indigo-400 text-left font-medium text-indigo-700 bg-indigo-50 focus:outline-none focus:text-indigo-800 focus:bg-indigo-100 focus:border-indigo-700 transition duration-150 ease-in-out"
        : "block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left font-medium text-gray-600 hover:text-gray-800 hover:bg-surface-50 hover:border-surface-300 focus:outline-none focus:text-gray-800 focus:bg-surface-50 focus:border-surface-300 transition duration-150 ease-in-out",
);
</script>

<template>
    <div>
        <button
            v-if="as == 'button'"
            :class="classes"
            class="w-full text-left"
        >
            <slot />
        </button>

        <Link
            v-else
            :href="href"
            :class="classes"
        >
            <slot />
        </Link>
    </div>
</template>
