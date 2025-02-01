<script lang="ts" setup>
import CollapsibleContainer from "@/Components/CollapsibleContainer.vue";
import ChevronDown from "@/Components/Icons/ChevronDown.vue";
import { computed, ref } from "vue";

const props = defineProps({
    iconClasses: {
        type: [String, Array],
        default: "h-5",
    },

    open: {
        type: Boolean,
        default: false,
    },

    withoutBorders: {
        type: Boolean,
        default: false,
    },

    iconPosition: {
        type: String,
        default: "left",
    },

    titleWrapperClasses: {
        type: [String, Array, Object],
        default: "",
    },
});

defineEmits(["toggleOpen"]);

const body = ref();

defineExpose({
    body,
});

const computedIconClasses = computed(() => ({
    "-rotate-180": props.open,
}));
</script>

<template>
    <div class="flex flex-col py-6 pl-4">
    <button
            class="relative flex cursor-pointer items-center justify-between"
            :class="titleWrapperClasses"
            @click="() => $emit('toggleOpen')"
        >
            <ChevronDown
                class="h-12 w-12 fill-white transition-all"
                :class="computedIconClasses"
                v-if="iconPosition === 'left'"
            />

            <span>
                <slot name="title" />
            </span>

            <ChevronDown
                class="m-3 h-6 w-6 fill-white transition-all"
                :class="computedIconClasses"
                v-if="iconPosition === 'right'"
            />
        </button>

        <CollapsibleContainer :open="open">
            <div
                ref="body"
                class="mt-4"
            >
                <slot />
            </div>
        </CollapsibleContainer>
    </div>
</template>
