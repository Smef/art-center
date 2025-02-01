<script setup lang="ts">
import { computed } from "vue";

const props = defineProps({
    open: {
        type: Boolean,
        default: false,
    },
});

// handling for expand/collapse
const gridTemplateRows = computed(() => (props.open ? "grid-rows-[1fr]" : "grid-rows-[0fr]"));
</script>

<template>
    <div
        class="grid overflow-hidden transition-all"
        :class="gridTemplateRows"
    >
        <div class="min-h-0">
            <slot />
        </div>
    </div>
</template>
