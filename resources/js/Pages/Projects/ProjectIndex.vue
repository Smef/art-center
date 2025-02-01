<script setup lang="ts">
import { Head, Link, router } from "@inertiajs/vue3";
import Button from "primevue/button";
import { PropType, ref, watch } from "vue";
import type PaginatedData from "@/Types/PaginatedData";
import type Company from "@/Types/Models/Company";
import type ModelIndexTableColumn from "@/Types/ModelIndexTableColumn";
import ModelIndexTable from "@/Components/ModelIndexTable.vue";
import SearchInput from "@/Components/SearchInput.vue";
import Paginator from "primevue/paginator";
import Card from "primevue/card";
import dayjs from "dayjs";
import Project from "@/Types/Models/Project";
import Tag from "primevue/tag";

const props = defineProps({
    paginatedData: {
        type: Object as PropType<PaginatedData<Company>>,
        required: true,
    },
});

// get query parameters on page load
const query = new URLSearchParams(window.location.search);
const searchInput = ref(query.get("search") ?? "");
watch(searchInput, () => {
    router.reload({
        data: {
            search: searchInput.value,
            page: 1,
        },
    });
});

const columns: ModelIndexTableColumn[] = [
    {
        label: "ID",
        key: "id",
        textAlign: "right",
    },
    {
        label: "Name",
        key: "name",
        bold: true,
    },
    {
        label: "Company",
        key: "company.name",
    },
    {
        label: "Start Date",
        key: "start_date",
        dataFormatCallback: (value: string) => dayjs(value).format("M/DD/YYYY"),
    },
    {
        label: "Status",
        key: "status",
        slotName: "statusSlot",
    },
];

function changePage({ page }: { page: number }) {
    router.get(props.paginatedData.links[page + 1].url ?? "/projects");
}
</script>

<template>
    <div>
        <Head title="Projects" />

        <div class="flex items-center justify-between">
            <h1 class="text-4xl">Projects</h1>

            <Button
                :href="route('projects.create')"
                label="Create New"
                :as="Link"
            />
        </div>

        <div class="pt-8">
            <card>
                <template #content>
                    <SearchInput
                        class="mb-8 mt-4"
                        v-model="searchInput"
                    />

                    <Paginator
                        :rows="paginatedData.per_page"
                        :total-records="paginatedData.total"
                        :first="paginatedData.from"
                        @page="changePage"
                    />

                    <ModelIndexTable
                        :columns="columns"
                        route-name="projects.edit"
                        :data="paginatedData.data"
                    >
                        <template #statusSlot="{ model: project }: { model: Project }">
                            <Tag
                                class="w-full text-xs"
                                rounded
                                :value="project.status"
                                :severity="
                                    project.status === 'Active'
                                        ? 'info'
                                        : project.status === 'Completed'
                                          ? 'success'
                                          : 'secondary'
                                "
                            />
                        </template>
                    </ModelIndexTable>

                    <Paginator
                        :rows="paginatedData.per_page"
                        :total-records="paginatedData.total"
                        :first="paginatedData.from"
                        @page="changePage"
                    />
                </template>
            </card>
        </div>
    </div>
</template>
