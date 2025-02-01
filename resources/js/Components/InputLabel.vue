<script setup>
defineProps({
    value: {
        type: String,
        required: false,
        default: "",
    },
});
</script>

<template>
    <label class="block font-medium">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
