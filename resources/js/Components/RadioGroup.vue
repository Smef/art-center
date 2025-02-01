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

import DisplayValueObject from "@/Types/DisplayValueObject";
import { RadioButtonGroup, RadioButton } from "primevue";
import { computed, PropType } from "vue";

const props = defineProps({
    options: {
        type: Array as PropType<T[] | DisplayValueObject[] | number[] | string[]>,
        required: true,
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
    displayProperty: {
        type: String,
        required: false,
        default: null,
    },
    valueProperty: {
        type: String,
        required: false,
        default: null,
    },
    autofocus: {
        type: Boolean,
        required: false,
        default: false,
    },
    hasError: {
        type: Boolean,
        required: false,
        default: false,
    },
    vertical: {
        type: Boolean,
        required: false,
        default: false,
    },
});

const modelValue = defineModel<string | number | undefined>();

const optionsComputed = computed((): DisplayValueObject[] => {
    // check for display and value properties, in which case we should read from the objects passed in
    if (props.displayProperty && props.valueProperty) {
        return (props.options as T[]).map((option) => ({
            display: option[props.displayProperty as keyof T],
            value: option[props.valueProperty as keyof T],
        }));
    }
    // check for a basic object with properties that are strings or numbers
    if (!Array.isArray(props.options)) {
        return Object.entries(props.options).map(([key, value]) => ({
            display: key,
            value: value as string | number,
        }));
    }

    // check for a basic array of strings or numbers
    if (typeof props.options[0] === "string" || typeof props.options[0] === "number") {
        return (props.options as Array<string | number>).map((option) => ({
            display: option,
            value: option,
        }));
    }

    // check for an array of objects with display and value properties
    if (
        typeof props.options[0] === "object" &&
        Object.hasOwn(props.options[0], "display") &&
        Object.hasOwn(props.options[0], "value")
    ) {
        return props.options as DisplayValueObject[];
    }

    return [];
});
</script>

<template>
    <RadioButtonGroup
        v-model="modelValue"
        class="flex flex-wrap gap-4 pt-2"
        :id="id"
        :class="{
            'flex-col': vertical,
        }"
    >
        <div
            v-for="option in optionsComputed"
            :key="option.value"
            class="flex items-center gap-2"
        >
            <RadioButton
                :name="name + '-' + option.value"
                :value="option.value"
                :input-id="id + '-' + option.value"
                :autofocus="autofocus"
                :invalid="hasError"
                :pt="{
                    box: {
                        class: {
                            'border-red-500 dark:border-red-300': hasError,
                        },
                    },
                }"
            />
            <label :for="id + '-' + option.value">{{ option.display }}</label>
        </div>
    </RadioButtonGroup>
</template>
