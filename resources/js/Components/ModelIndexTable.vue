<script setup lang="ts" generic="T extends Model">
import ModelIndexTableColumn from "@/Types/ModelIndexTableColumn";
import type Model from "@/Types/Models/Model";
import { Link } from "@inertiajs/vue3";
import get from "lodash/get";
import Column from "primevue/column";
import DataTable from "primevue/datatable";
import { computed, type PropType } from "vue";

const props = defineProps({
    data: {
        type: Array as PropType<T[]>,
        required: true,
    },
    routeName: {
        type: String,
        required: false,
        default: undefined,
    },
    routeCallback: {
        type: Function as PropType<(entry: T) => string>,
        required: false,
        default: undefined,
    },
    columns: {
        type: Array as PropType<ModelIndexTableColumn[]>,
        required: true,
    },
    onSortFieldChange: {
        type: Function as PropType<(event: string) => void>,
        required: false,
        default: undefined,
    },
    onSortOrderChange: {
        type: Function as PropType<(event: number | undefined) => void>,
        required: false,
        default: undefined,
    },
    sortField: {
        type: String,
        required: false,
        default: null,
    },
    sortOrder: {
        type: Number,
        required: false,
        default: 1,
    },
    pointerOnHover: {
        type: Boolean,
        required: false,
        default: false,
    },
    size: {
        type: String as PropType<"small" | "large">,
        required: false,
        default: undefined,
    },
    lazy: {
        type: Boolean,
        required: false,
        default: false,
    },
});

defineEmits(["row-click"]);

const rowElementType = computed(() => {
    if (props.routeName || props.routeCallback) {
        return Link;
    }

    return "div";
});
</script>

<template>
    <DataTable
        :value="data"
        :sort-field="sortField"
        :sort-order="sortOrder"
        scroll-height="flex"
        @update:sort-field="onSortFieldChange ? onSortFieldChange($event) : undefined"
        @update:sort-order="onSortOrderChange ? onSortOrderChange($event) : undefined"
        @row-click="$emit('row-click', $event)"
        :removable-sort="false"
        :size="size"
        :lazy="lazy"
    >
        <Column
            class=""
            v-for="(column, index) in columns"
            :key="column.key"
            :field="column.key"
            :header="column.label"
            :sortable="column.sortable"
            :style="column.width ? { width: column.width } : undefined"
            :pt="{
                root: {
                    class: [
                        {
                            'text-center': column.textAlign === 'center',
                            'text-right': column.textAlign === 'right',
                            'text-left': column.textAlign === 'left',
                            'whitespace-nowrap': !column.allowTextWrap,
                            'cursor-pointer': props.pointerOnHover,
                        },
                        'p-0',
                    ],
                },

                columnHeaderContent: {
                    class: [
                        {
                            'justify-center': column.textAlign === 'center',
                            'justify-end': column.textAlign === 'right',
                            'justify-start': column.textAlign === 'left',
                        },
                        'px-4 py-3',
                    ],
                },
            }"
        >
            <template #body="{ data: slotData }: { data: T }">
                <component
                    :is="rowElementType"
                    :href="
                        routeName ? route(routeName, slotData.id) : routeCallback ? routeCallback(slotData) : undefined
                    "
                    class="flex w-full items-stretch px-4 py-3"
                    :class="{
                        'text-center': column.textAlign === 'center',
                        'text-right': column.textAlign === 'right',
                        // default to left if not right or center
                        'text-left': column.textAlign !== 'right' && column.textAlign !== 'center',
                        'font-bold': column.bold,
                        'whitespace-nowrap': !column.allowTextWrap,
                    }"
                    :tabindex="index > 0 ? -1 : undefined"
                >
                    <span class="flex grow items-center">
                        <slot
                            :name="column.slotName"
                            :model="slotData"
                            :index="index"
                        >
                            <div class="grow">
                                {{
                                    column.dataFormatCallback
                                        ? column.dataFormatCallback(get(slotData, column.key ?? "id"))
                                        : get(slotData, column.key ?? "id")
                                }}
                            </div>
                        </slot>
                    </span>
                </component>
            </template>
        </Column>
    </DataTable>
</template>
