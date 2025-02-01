<script setup lang="ts">
import ExclamationCircle from "@/Components/Icons/ExclamationCircle.vue";
import InputText from "primevue/inputtext";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    class: {
        type: String,
        required: false,
        default: null,
    },
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
    type: {
        type: String,
        required: false,
        default: "text",
    },
    placeholder: {
        type: String,
        required: false,
        default: null,
    },
    errorMessage: {
        type: String,
        required: false,
        default: null,
    },
});

const modelValue = defineModel({
    type: String,
    default: "",
});
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
                <InputText
                    v-bind="$attrs"
                    :type="type"
                    :name="name"
                    :id="id"
                    :placeholder="placeholder"
                    v-model="modelValue"
                    :invalid="errorMessage !== null"
                    class="w-full"
                />
                <div
                    class="pointer-events-none absolute inset-y-0 right-0 flex h-full w-full items-center justify-end pr-3"
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
