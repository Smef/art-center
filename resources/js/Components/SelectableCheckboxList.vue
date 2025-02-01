<script setup lang="ts">
import { PropType } from "vue";
import Checkbox from "primevue/checkbox";

defineProps({
    legend: {
        type: String,
        required: true,
    },
    options: {
        type: Array as PropType<
            {
                label: string;
                description: string;
                value: string;
                id: string;
                name: string;
            }[]
        >,
        required: true,
    },
});

const modelValue = defineModel();
</script>

<template>
    <fieldset class="">
        <legend class="sr-only">{{ legend }}</legend>

        <div class="space-y-5">
            <div
                class="relative flex items-start"
                v-for="option in options"
                :key="option.value"
            >
                <div class="flex h-6 items-center">
                    <Checkbox
                        :input-id="option.id"
                        :aria-describedby="(option.name ?? option.id) + '-description'"
                        :name="option.id"
                        v-model="modelValue"
                        :value="option.value"
                    />
                </div>

                <div class="ml-3 text-sm leading-6">
                    <label
                        :for="option.id"
                        class="font-medium"
                        >{{ option.label }} <br />

                        <span
                            :id="option.id + '-description'"
                            class="text-surface-500 dark:text-surface-400"
                        >
                            {{ option.description }}
                        </span>
                    </label>
                </div>
            </div>
        </div>
    </fieldset>
</template>
