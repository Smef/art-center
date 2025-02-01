<script setup>
import { computed } from "vue";

const emit = defineEmits(["update:modelValue"]);

const props = defineProps({
    modelValue: {
        type: [Array, Boolean, Number],
        required: true,
    },
    value: {
        type: [String, Boolean, Number],
        default: true,
    },
});

const model = computed({
    get() {
        if (Array.isArray(props.modelValue)) {
            return props.modelValue.includes(props.value);
        }

        return props.modelValue;
    },

    set(value) {
        let newValue = props.modelValue;

        if (Array.isArray(props.modelValue)) {
            if (value) {
                newValue.push(props.value);
            } else {
                const index = newValue.findIndex((v) => v === props.value);

                newValue.splice(index, 1);
            }
        } else {
            newValue = value;
        }
        emit("update:modelValue", newValue);
    },
});
</script>

<template>
    <input
        v-model="model"
        type="checkbox"
        :value="value"
        class="h-5 w-5 border-2 border-surface-900 focus:ring focus:ring-yellow-500 focus:ring-opacity-50"
    />
</template>
