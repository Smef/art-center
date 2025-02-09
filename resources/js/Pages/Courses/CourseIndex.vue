<script setup lang="ts">
import ModelIndexTable from "@/Components/ModelIndexTable.vue";
import SearchInput from "@/Components/SearchInput.vue";
import ModelIndexTableColumn from "@/Types/ModelIndexTableColumn";
import Course from "@/Types/Models/Course";
import PaginatedData from "@/Types/PaginatedData";
import { Head, Link, router } from "@inertiajs/vue3";
import Button from "primevue/button";
import Card from "primevue/card";
import Paginator from "primevue/paginator";
import { PropType, ref, watch } from "vue";
import dayjs from "dayjs";

const props = defineProps({
    paginatedData: {
        type: Object as PropType<PaginatedData<Course>>,
        required: true,
    },
});

// get query parameters on page load
const query = new URLSearchParams(window.location.search);
const searchInput = ref(query.get("filter[name]") ?? "");
watch(searchInput, () => {
    router.reload({
        data: {
            filter: {
                name: searchInput.value,
            },
            page: 1,
        },
    });
});

const columns: ModelIndexTableColumn[] = [
    {
        label: "ID",
        key: "id",
        textAlign: "right",
        width: "5rem",
    },
    {
        label: "Name",
        key: "name",
        bold: true,
    },
    {
        label: "Location",
        key: "location.name",
    },
    {
        label: "Start Date",
        key: "start_date",
        dataFormatCallback: (value: string) => dayjs(value).format("M/D/YYYY"),
    },
    {
        label: "End Date",
        key: "end_date",
        dataFormatCallback: (value: string) => dayjs(value).format("M/D/YYYY"),
    },
];

function changePage({ page }: { page: number }) {
    router.get(props.paginatedData.links[page + 1].url ?? "/courses");
}
</script>

<template>
    <div>
        <Head title="Courses" />

        <div class="flex items-center justify-between">
            <h1 class="text-4xl">Courses</h1>

            <Button
                :as="Link"
                :href="route('courses.create')"
                label="Create New"
            />
        </div>

        <SearchInput
            class="mb-8 mt-4"
            v-model="searchInput"
        />

        <Paginator
            :rows="paginatedData.data.length"
            :total-records="paginatedData.total"
            :first="paginatedData.from"
            @page="changePage"
        />

        <ModelIndexTable
            :data="paginatedData.data"
            route-name="courses.edit"
            :columns="columns"
        />

        <Paginator
            :rows="paginatedData.data.length"
            :total-records="paginatedData.total"
            :first="paginatedData.from"
            @page="changePage"
        />
    </div>
</template>
