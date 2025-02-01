<script setup>
import { computed, useSlots } from "vue";
import SectionTitle from "./SectionTitle.vue";
import Card from "primevue/card";

defineEmits(["submitted"]);

const hasActions = computed(() => !!useSlots().actions);
</script>

<template>
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <SectionTitle>
            <template #title>
                <slot name="title" />
            </template>
            <template #description>
                <slot name="description" />
            </template>
        </SectionTitle>
        <form
            @submit.prevent="$emit('submitted')"
            class="col-span-2"
        >
            <Card class="mt-5 md:col-span-2 md:mt-0">
                <template #content>
                    <div :class="hasActions ? 'sm:rounded-tl-md sm:rounded-tr-md' : 'sm:rounded-border'">
                        <div class="grid grid-cols-6 gap-6">
                            <slot name="form" />
                        </div>
                    </div>
                </template>
                <template #footer>
                    <div
                        v-if="hasActions"
                        class="flex items-center justify-end text-right"
                    >
                        <slot name="actions" />
                    </div>
                </template>
            </Card>
        </form>
    </div>
</template>
