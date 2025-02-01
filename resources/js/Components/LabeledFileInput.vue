<script setup lang="ts">
import FileInput from "@/Components/FileInput.vue";
import ExclamationCircle from "@/Components/Icons/ExclamationCircle.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "./InputLabel.vue";

defineProps({
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
        required: false,
        default: null,
    },
    errorMessage: {
        type: String,
        required: false,
        default: null,
    },
    hint: {
        type: String,
        required: false,
        default: null,
    },
    sizeLimitKb: {
        type: Number,
        required: false,
        default: 1024,
    },
    thumbnail: {
        type: String,
        required: false,
        default: null,
    },
});

defineOptions({
    inheritAttrs: false,
});

const modelValue = defineModel<File | undefined>();
</script>

<template>
    <div class="flex flex-col">
        <InputLabel
            :for="id"
            v-if="label"
            :value="label"
            class=""
        />

        <div class="pt-2">
            <div class="relative">
                <FileInput
                    :name="name"
                    :id="id"
                    v-model="modelValue"
                    :has-error="errorMessage !== null"
                    :hint="hint"
                    :size-limit-kb="sizeLimitKb"
                    v-bind="$attrs"
                    :thumbnail="thumbnail"
                />
                <div
                    class="pointer-events-none absolute left-1 top-1 flex items-center pr-3"
                    v-if="errorMessage"
                >
                    <ExclamationCircle
                        class="h-5 w-5 text-red-500 dark:text-red-300"
                        aria-hidden="true"
                    />
                </div>
            </div>
        </div>

        <div class="flex">
            <InputError
                class="mt-2 w-0 grow"
                :message="errorMessage ?? '&nbsp;'"
            />
        </div>
    </div>
</template>
