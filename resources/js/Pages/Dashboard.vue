<script setup>
import WelcomeMessage from "@/Components/WelcomeMessage.vue";
import Card from "primevue/card";
</script>

<template>
    <div>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <Card class="overflow-hidden shadow-xl sm:rounded-border">
                    <template #content>
                        <WelcomeMessage />
                    </template>
                </Card>
            </div>
        </div>
    </div>
</template>
