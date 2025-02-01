<script setup lang="ts">
import Button from "primevue/button";

defineProps({
    loading: {
        type: Boolean,
        default: false,
    },
    recentlySuccessful: {
        type: Boolean,
        default: false,
    },
    hasErrors: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <div class="flex items-center gap-x-2">
        <div class="flex items-center gap-x-2">
            <Button
                class="min-w-20"
                type="submit"
                :loading="loading"
                :label="loading ? '' : recentlySuccessful ? 'Saved!' : 'Save'"
            >
                <template #icon></template>
            </Button>
        </div>

        <p
            v-if="hasErrors"
            class="text-sm text-red-600 dark:text-red-300"
        >
            Oops! Please check for invalid values.
        </p>
    </div>
</template>
