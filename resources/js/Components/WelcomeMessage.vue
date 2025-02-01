<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Divider from "primevue/divider";
</script>

<template>
    <div>
        <div class="p-6 lg:p-8">
            <ApplicationLogo class="block h-24 w-auto" />

            <h1 class="mt-8 text-2xl font-medium text-gray-900 dark:text-white">
                Gearbox Solutions Phase 1 Starter Kit
            </h1>

            <p class="mt-6 leading-relaxed text-gray-500 dark:text-gray-400"></p>
        </div>
        <Divider />

        <div class="grid grid-cols-1 gap-6 p-6 md:grid-cols-2 lg:gap-8 lg:p-8">
            <div>
                <p class="mt-4 text-sm leading-relaxed text-muted-color">
                    This is the Phase-1 starter kit from Gearbox Solutions
                </p>
            </div>
        </div>
    </div>
</template>
