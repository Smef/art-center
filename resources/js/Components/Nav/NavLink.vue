<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { type Component, computed, PropType } from "vue";

const props = defineProps({
    href: {
        type: String,
        default: "",
    },
    active: {
        type: Boolean,
        required: false,
    },
    label: {
        type: String,
        default: "",
    },
    expanded: {
        type: Boolean,
        default: false,
    },
    icon: {
        type: Object as PropType<Component>,
        required: true,
    },
});

const classes = computed(() => (props.active ? "bg-surface-800 font-bold" : "font-light"));
const barColor = computed(() => (props.active ? "bg-primary-500" : ""));
</script>

<template>
    <Link
        :href="href"
        class="relative block text-white hover:bg-surface-700"
        :class="classes"
    >
        <div
            class="absolute h-16 w-2"
            :class="barColor"
        />

        <div class="h-16 px-7 py-5">
            <div class="flex items-center justify-start gap-x-3 overflow-clip">
                <Component
                    :is="icon"
                    class="h-6 w-6 flex-none fill-white"
                />

                <span
                    class="whitespace-nowrap font-light transition-all"
                    :class="{ '': expanded, 'opacity-0': !expanded }"
                >
                    {{ label }}
                </span>
            </div>
        </div>
    </Link>
</template>
