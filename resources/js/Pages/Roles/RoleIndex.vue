<script setup lang="ts">
import ModelIndexTable from "@/Components/ModelIndexTable.vue";
import SearchInput from "@/Components/SearchInput.vue";
import ModelIndexTableColumn from "@/Types/ModelIndexTableColumn";
import Role from "@/Types/Models/Role";
import PaginatedData from "@/Types/PaginatedData";
import { Head, Link, router } from "@inertiajs/vue3";
import { PropType, ref, watch } from "vue";
import Paginator from "primevue/paginator";
import Button from "primevue/button";

const props = defineProps({
    paginatedData: {
        type: Object as PropType<PaginatedData<Role>>,
        required: true,
    },
});

// get query parameters on page load
const query = new URLSearchParams(window.location.search);
const searchInput = ref(query.get("filter[name]"));
watch(searchInput, () => {
    router.reload({
        data: {
            filter: {
                name: searchInput.value,
            },
        },
    });
});

const columns: ModelIndexTableColumn[] = [
    {
        label: "ID",
        key: "id",
        textAlign: "right",
        width: "100px",
    },
    {
        label: "Name",
        key: "name",
        bold: true,
    },
];

function changePage({ page }: { page: number }) {
    router.get(props.paginatedData.links[page + 1].url ?? "/settings/roles");
}
</script>

<template>
    <div>
        <Head title="Roles" />

        <div class="flex items-center justify-between">
            <h1 class="text-4xl">Roles</h1>

            <Button
                :as="Link"
                :href="route('settings.roles.create')"
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
            route-name="settings.roles.edit"
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
