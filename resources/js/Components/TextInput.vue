<script setup>
import { ref } from "vue";
import InputText from "primevue/inputtext";

const props = defineProps({
    hasError: {
        type: Boolean,
        required: false,
        default: false,
    },
    autofocus: {
        type: Boolean,
        required: false,
        default: false,
    },
});

const modelValue = defineModel({ type: String, default: "" });

const input = ref(null);


defineExpose({ focus: () => input.value.$el.focus() });
</script>

<template>
    <InputText
        :invalid="props.hasError"
        ref="input"
        class="block"
        v-model="modelValue"
        :autofocus="props.autofocus"
    />
</template>
