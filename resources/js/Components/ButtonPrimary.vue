<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    type: {
        type: String,
        default: "submit",
    },
    href: {
        type: String,
        default: null,
        required: false,
    },

    icon: {
        type: [String, Object, Function],
        default: null,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
});

const classes = computed(() => ({
    "bg-primary-700 hover:bg-primary-600": !props.disabled,
    "bg-surface-300": props.disabled,
}));

const componentType = computed(() => {
    if (props.href) {
        return Link;
    }

    return "button";
});
</script>

<template>
    <Component
        :is="componentType"
        :type="type"
        :href="href"
        :disabled="disabled"
        class="px-3 py-2 text-sm font-semibold text-white shadow-sm rounded-border focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-700"
        :class="classes"
    >
        <span class="flex items-center justify-center gap-x-2">
            <span v-if="icon">
                <Component
                    :is="icon"
                    v-if="icon"
                    class="h-4 w-4 fill-gray-950"
                />
            </span>

            <slot />
        </span>
    </Component>
</template>
