<script setup lang="ts">
import NavFlyout from "@/Components/Nav/NavFlyout.vue";
import NavFlyoutItem from "@/Components/Nav/NavFlyoutItem.vue";
import type { NavLinkWithHref } from "@/Types/NavLink";
import { vOnClickOutside } from "@vueuse/components";
import { OnClickOutsideOptions, useElementBounding } from "@vueuse/core";
import { computed, PropType, ref, watch } from "vue";

const props = defineProps({
    label: {
        type: String,
        default: "",
    },
    expanded: {
        type: Boolean,
        default: false,
    },
    icon: {
        type: Object,
        default: null,
    },
    active: {
        type: Boolean,
        default: false,
    },
    children: {
        type: Array as PropType<NavLinkWithHref[]>,
        default: () => [],
    },
});

const emit = defineEmits(["click-link"]);

const showingFlyout = ref(false);
const menuButton = ref<HTMLButtonElement>();

const closeFlyout = () => {
    showingFlyout.value = false;
};

const showFlyout = () => {
    showingFlyout.value = true;
};

function toggleShowFlyout() {
    if (showingFlyout.value) {
        closeFlyout();
    } else {
        showFlyout();
    }
}

const menuOpen = ref(false);

watch(
    () => props.expanded,
    (expanded) => {
        if (!expanded) {
            menuOpen.value = false;
        }
    },
);

function barColor(active = false) {
    return active ? "bg-primary-500" : "";
}

const classes = computed(() => (props.active ? "bg-surface-800 font-bold" : "font-light"));

const { top: buttonTop, width: buttonWidth } = useElementBounding(menuButton);

const flyoutStyle = computed(() => ({
    top: `${buttonTop.value}px`,
    left: `${buttonWidth.value}px`,
}));

const onClickOutsideHandler: [(evt: Event) => void, OnClickOutsideOptions] = [
    () => {
        if (showingFlyout.value) {
            showingFlyout.value = false;
        }
    },
    { ignore: [menuButton] },
];

const handleClickFlyoutItem = () => {
    closeFlyout();
    emit("click-link");
};
</script>

<template>
    <div class="">
        <button
            class="relative block w-full text-white hover:bg-surface-700"
            @click.stop="toggleShowFlyout"
            ref="menuButton"
            :class="classes"
        >
            <span
                class="absolute left-0 h-full w-2"
                :class="barColor(active)"
            />

            <span class="block h-16 w-full px-7 py-5">
                <span class="flex items-center justify-start gap-x-3 overflow-clip">
                    <Component
                        :is="icon"
                        class="h-6 w-6 flex-none fill-white"
                    />

                    <span
                        class="font-light transition-all"
                        :class="{ '': expanded, 'opacity-0': !expanded }"
                    >
                        {{ label }}
                    </span>
                </span>
            </span>
        </button>

        <NavFlyout
            v-if="showingFlyout"
            class="absolute z-20 flex flex-col"
            :style="flyoutStyle"
            v-on-click-outside="onClickOutsideHandler"
        >
            <NavFlyoutItem
                v-for="child in children"
                :key="child.href"
                :href="child.href"
                :icon="child.icon"
                :label="child.label"
                @click="handleClickFlyoutItem"
            />
        </NavFlyout>
    </div>
</template>
