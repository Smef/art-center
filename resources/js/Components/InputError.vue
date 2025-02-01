<script setup lang="ts">
import { Message } from "primevue";
defineProps({
    message: {
        type: String,
        required: false,
        default: "",
    },
});
</script>

<template>
    <Message
        v-show="message"
        size="small"
        variant="simple"
        severity="error"
        >{{ message }}</Message
    >
</template>
