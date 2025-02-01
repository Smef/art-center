<script setup lang="ts" generic="T extends { [key: string]: any }">
import ExclamationCircle from "@/Components/Icons/ExclamationCircle.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import SelectMenu from "@/Components/SelectMenu.vue";
import type DisplayValueObject from "@/Types/DisplayValueObject";
import { computed, PropType } from "vue";

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
        required: false,
        default: null,
    },
    placeholder: {
        type: String,
        required: false,
        default: null,
    },
    autocomplete: {
        type: String,
        required: false,
        default: null,
    },
    autofocus: {
        type: Boolean,
        required: false,
        default: false,
    },
    ariaInvalid: {
        type: Boolean,
        required: false,
        default: false,
    },
    ariaDescribedBy: {
        type: String,
        required: false,
        default: null,
    },
    errorMessage: {
        type: String,
        required: false,
        default: null,
    },
    options: {
        type: Array as PropType<T[] | DisplayValueObject[] | number[] | string[]>,
        required: true,
    },
    withClear: {
        type: Boolean,
        default: false,
    },
    displayProperty: {
        type: Object as PropType<keyof T & string>,
        required: false,
        default: undefined,
    },
    valueProperty: {
        type: Object as PropType<keyof T & string>,
        required: false,
        default: undefined,
    },
});

const errorIconClasses = computed(() => {
    if (props.errorMessage && props.withClear) {
        return "pr-14";
    }
    if (props.errorMessage) {
        return "pr-8";
    }
    return "";
});

const modelValue = defineModel<string | number | undefined>();
</script>

<template>
    <div class="relative">
        <InputLabel
            :for="id"
            v-if="label"
            :value="label"
            class=""
        />

        <div class="relative pt-2">
            <SelectMenu
                :name="name"
                :id="id"
                :placeholder="placeholder"
                v-model="modelValue"
                :aria-invalid="ariaInvalid"
                :aria-describedby="ariaDescribedBy"
                :autocomplete="autocomplete"
                :autofocus="autofocus"
                :has-error="errorMessage !== null"
                :options="options"
                :with-clear="withClear"
                :display-property="displayProperty"
                :value-property="valueProperty"
                fluid
            />

            <div
                class="pointer-events-none absolute inset-y-0 right-0 flex items-center"
                :class="errorIconClasses"
                v-if="errorMessage"
            >
                <ExclamationCircle
                    class="h-5 w-5 text-red-500 dark:text-red-300"
                    aria-hidden="true"
                />
            </div>
        </div>

        <InputError
            class="mt-2"
            :message="errorMessage"
        />
    </div>
</template>
