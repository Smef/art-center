<script setup lang="ts">
import useColorScheme from "@/Composables/useColorScheme";
import MoonIcon from "@/Components/Icons/MoonIcon.vue";
import SunIcon from "@/Components/Icons/SunIcon.vue";
import ToggleSwitch from "primevue/toggleswitch";

defineProps({
    expanded: {
        type: Boolean,
        required: false,
        default: false,
    },
});

const { toggleDarkMode, isDarkMode } = useColorScheme();
</script>
<template>
    <div
        class="flex w-full items-center justify-center gap-x-2 gap-y-2 py-4"
        :class="{
            'flex-col': !expanded,
        }"
    >
        <div class="flex items-center gap-x-3">
            <SunIcon class="h-4 fill-surface-200" />

            <MoonIcon
                class="h-4 text-surface-200"
                v-if="!expanded"
            />
        </div>
        <ToggleSwitch
            @click="toggleDarkMode"
            v-model="isDarkMode"
        />
        <MoonIcon
            class="h-4 text-surface-200"
            v-if="expanded"
        />
    </div>
</template>
