<script setup lang="ts">
import Card from "primevue/card";
</script>
<template>
    <div
        class="flex min-h-screen flex-col items-center bg-surface-100 pt-6 sm:justify-center sm:pt-0 dark:bg-surface-950"
    >
        <div>
            <slot name="logo" />
        </div>

        <Card class="mt-6 w-full sm:max-w-md">
            <template #content>
                <slot />
            </template>
        </Card>
    </div>
</template>
