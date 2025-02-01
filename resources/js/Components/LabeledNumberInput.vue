<script setup lang="ts">
import ExclamationCircle from "@/Components/Icons/ExclamationCircle.vue";
import InputNumber from "primevue/inputnumber";
import InputError from "@/Components/InputError.vue";
import InputLabel from "./InputLabel.vue";

const props = defineProps({
    label: {
        type: String,
        required: false,
        default: null,
    },
    id: {
        type: String,
        required: true,
    },
    name: {
        type: String,
        required: true,
        default: null,
    },
    placeholder: {
        type: String,
        required: false,
        default: null,
    },
    class: {
        type: String,
        required: false,
        default: null,
    },
    errorMessage: {
        type: String,
        required: false,
        default: null,
    },
    useGrouping: {
        type: Boolean,
        required: false,
        default: false,
    },
});

const modelValue = defineModel({ type: Number });
</script>

<template>
    <div :class="props.class">
        <InputLabel
            :for="id"
            v-if="label"
            :value="label"
            class=""
        />

        <div class="pt-2">
            <div class="relative">
                <InputNumber
                    v-bind="$attrs"
                    :name="name"
                    :id="id"
                    :placeholder="placeholder"
                    v-model="modelValue"
                    :invalid="errorMessage !== null"
                    :use-grouping="useGrouping"
                    fluid
                />
                <div
                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3"
                    v-if="errorMessage"
                >
                    <ExclamationCircle
                        class="h-5 w-5 text-red-500 dark:text-red-300"
                        aria-hidden="true"
                    />
                </div>
            </div>
        </div>

        <InputError
            class="mt-2"
            :message="errorMessage"
        />
    </div>
</template>
