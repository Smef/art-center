<script setup lang="ts" generic="T extends { [key: string]: any }">
import SearchInput from "@/Components/SearchInput.vue";
import { whenever } from "@vueuse/core";
import Dialog from "primevue/dialog";
import { nextTick, PropType, ref, useTemplateRef, watch } from "vue";

const props = defineProps({
    displayData: {
        type: Array as PropType<T[]>,
        required: false,
        default: () => [],
    },
    dialogHeader: {
        type: String,
        required: false,
        default: "Search",
    },
    displayAttribute: {
        type: String,
        required: false,
        default: "name",
    },
    valueAttribute: {
        type: String,
        required: false,
        default: "id",
    },
});

const visible = defineModel<boolean>("visible");
const query = ref("");
const selectedItem = ref<T | null>(null);

function openSearchDialog() {
    query.value = "";
    focusIndex.value = 0;
    selectedItem.value = props.displayData[0] ?? {};
}

const emit = defineEmits(["update-search", "selected"]);

function handleItemSelected(entry: T) {
    visible.value = false;
    emit("selected", entry);
}
function handleSearchChanged() {
    focusIndex.value = 0;
    emit("update-search", query.value);
}

watch(query, () => {
    handleSearchChanged();
});

whenever(visible, () => openSearchDialog());

watch(
    () => props.displayData,
    () => {
        selectedItem.value = props.displayData[0];
    },
);

const ulElement = ref<HTMLUListElement | null>(null);
const searchInput = useTemplateRef<typeof SearchInput>("searchInput");
const focusIndex = ref(0);

function handleKeyDown(event: KeyboardEvent) {
    if (event.key === "ArrowDown") {
        event.preventDefault();

        // don't do anything if we're at the bottom of the list
        if (focusIndex.value >= props.displayData.length - 1) {
            return;
        }

        focusIndex.value++;

        // track the new index as the selected item
        selectedItem.value = props.displayData[focusIndex.value];

        // focus the button by nth child of the ul
        const button = ulElement.value?.querySelector(
            `li:nth-child(${focusIndex.value + 1}) button`,
        ) as HTMLButtonElement;
        button.focus();

        // then focus the input again
        nextTick(() => {
            searchInput.value?.$refs.inputText.$el.focus();
        });
    } else if (event.key === "ArrowUp") {
        event.preventDefault();

        // don't do anything if we're at the top of the list
        if (focusIndex.value === 0) {
            return;
        }

        focusIndex.value--;
        selectedItem.value = props.displayData[focusIndex.value] ?? props.displayData[props.displayData.length - 1];

        // focus the button by nth child of the ul
        const button = ulElement.value?.querySelector(
            `li:nth-child(${focusIndex.value + 1}) button`,
        ) as HTMLButtonElement;
        button.focus();

        // then focus the input again
        nextTick(() => {
            searchInput.value?.$refs.inputText.$el.focus();
        });
    } else if (event.key === "Enter") {
        event.preventDefault();
        handleItemSelected(selectedItem.value);
    }
}
</script>
<template>
    <Dialog
        modal
        :draggable="false"
        position="top"
        :header="dialogHeader"
        dismissable-mask
        v-model:visible="visible"
        :pt="{ header: { class: 'items-start gap-x-4' } }"
    >
        <template #header>
            <div class="pt-2">
                <div>{{ dialogHeader }}</div>
                <SearchInput
                    ref="searchInput"
                    autofocus
                    class="w-full pb-2 pt-2"
                    v-model="query"
                    @input="handleSearchChanged"
                    @keydown="handleKeyDown"
                />
            </div>
        </template>
        <div class="min-w-64">
            <ul ref="ulElement">
                <li
                    v-for="item in displayData"
                    :key="item[valueAttribute]"
                    class="py-0.5"
                >
                    <button
                        @click="handleItemSelected(item)"
                        class="block w-full rounded p-2 text-start"
                        :class="{
                            'bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-white':
                                item === selectedItem,
                            'hover:bg-surface-100 dark:hover:bg-surface-800': item !== selectedItem,
                        }"
                    >
                        <slot :option="item">
                            {{ item[displayAttribute] ?? "" }}
                        </slot>
                    </button>
                </li>
            </ul>
        </div>
    </Dialog>
</template>
