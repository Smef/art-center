<script setup lang="ts" generic="T extends { [key: string]: any }">
/**
 * This component takes data in any of the following formats to show a radio group:
 *
 * - An array of strings or numbers (the display and value will be the same):
 *       ['Option A', 'Option B', 'Option C']
 *
 * - An array of objects with 'display' and 'value' properties:
 *       [
 *           {display: 'Option A', value: '1'},
 *           {display: 'Option B', value: '2'},
 *           {display: 'Option C', value: '3'}
 *       ]
 *
 * - An array of objects with properties that are strings or numbers, such as a Model object, along with
 *   displayProperty and valueProperty props:
 *      [
 *           {id: 1, name: 'Option A'},
 *           {id: 2, name: 'Option B'},
 *           {id: 3, name: 'Option C'},
 *      ]
 *       <LabeledRadioGroup :options="options" displayProperty="name" valueProperty="id" />
 *
 * - A single object with properties that are strings or numbers to be used as key->value pairs
 *      {
 *          'Option A': 1,
 *          'Option B': 2,
 *          'Option C': 3,
 *      }
 *
 * */

import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import type DisplayValueObject from "@/Types/DisplayValueObject";
import { PropType } from "vue";
import RadioGroup from "@/Components/RadioGroup.vue";

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
    vertical: {
        type: Boolean,
        required: false,
        default: false,
    },
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

        <RadioGroup
            :id="id"
            v-model="modelValue"
            :options="options"
            :has-error="errorMessage !== null"
            :vertical="vertical"
        />

        <InputError
            class="mt-2"
            :message="errorMessage"
        />
    </div>
</template>
