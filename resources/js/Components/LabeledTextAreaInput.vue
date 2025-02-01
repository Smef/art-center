<script setup lang="ts">
import ExclamationCircle from "@/Components/Icons/ExclamationCircle.vue";
import Textarea from "primevue/textarea";
import InputLabel from "@/Components/InputLabel.vue";

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
    rows: {
        type: Number,
        required: false,
        default: 3,
    },
});

const modelValue = defineModel({
    type: String,
    default: "",
});
</script>

<template>
    <div
        :class="props.class"
        class="flex flex-col"
    >
        <InputLabel
            :for="id"
            v-if="label"
            :value="label"
            class=""
        />

        <div class="pt-2">
            <div class="relative flex grow flex-col">
                <Textarea
                    v-bind="$attrs"
                    :type="type"
                    :name="name"
                    :id="id"
                    :placeholder="placeholder"
                    fluid
                    v-model="modelValue"
                    :invalid="errorMessage !== null"
                    :rows="rows"
                    class="grow"
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

        <p
            class="mt-2 text-sm text-red-600 dark:text-red-300"
            v-if="errorMessage"
        >
            {{ errorMessage }}
        </p>
    </div>
</template>
