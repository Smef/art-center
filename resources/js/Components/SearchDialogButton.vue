<script setup lang="ts" generic="T extends { [key: string]: any }">
import PencilSquare from "@/Components/Icons/PencilSquare.vue";
import SearchDialog from "@/Components/SearchDialog.vue";
import Button from "primevue/button";
import { PropType, ref, watch } from "vue";

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

const showDialog = ref(false);
const selectedItem = ref<T | null>(null);

function openSearchDialog() {
    showDialog.value = true;
    selectedItem.value = props.displayData[0] ?? null;
}

const emit = defineEmits(["update-search", "selected"]);

function handleSelected(entry: T) {
    emit("selected", entry);
}
function handleSearchChanged(query: string) {
    emit("update-search", query);
}

watch(
    () => props.displayData,
    () => {
        selectedItem.value = props.displayData[0] ?? null;
    },
);
</script>
<template>
    <div>
        <Button
            severity="secondary"
            @click.prevent="openSearchDialog"
            size="small"
        >
            <template #icon>
                <PencilSquare />
            </template>
        </Button>
        <SearchDialog
            v-model:visible="showDialog"
            :display-data="displayData"
            :dialog-header="dialogHeader"
            @update-search="handleSearchChanged"
            @selected="handleSelected"
            :display-attribute="displayAttribute"
            :value-attribute="valueAttribute"
        >
            <template
                #default="{ option }"
                v-if="$slots.default"
            >
                <slot :option="option" />
            </template>
        </SearchDialog>
    </div>
</template>
