<script setup lang="ts">
defineProps({
    expanded: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <!-- separate the shadow onto another div to work around clipping issue in safari-->
    <div class="absolute bg-transparent drop-shadow-xl">
        <div class="z-20 flex flex-col bg-surface-800 text-white drop-shadow-xl">
            <slot />
        </div>
    </div>
</template>
