<script setup lang="ts">
import ModelIndexTable from "@/Components/ModelIndexTable.vue";
import SearchInput from "@/Components/SearchInput.vue";
import ModelIndexTableColumn from "@/Types/ModelIndexTableColumn";
import Contact from "@/Types/Models/Contact";
import PaginatedData from "@/Types/PaginatedData";
import { Head, router } from "@inertiajs/vue3";
import Paginator from "primevue/paginator";
import { PropType, ref, watch } from "vue";

const props = defineProps({
    paginatedData: {
        type: Object as PropType<PaginatedData<Contact>>,
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
        label: "Email",
        key: "email",
    },
    {
        label: "Role",
        key: "role",
    },
];

function changePage({ page }: { page: number }) {
    router.get(props.paginatedData.links[page + 1].url ?? "/users");
}
</script>

<template>
    <div>
        <Head title="Users" />

        <h1 class="text-4xl">Users</h1>

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
            :columns="columns"
            route-name="settings.users.edit"
            :data="paginatedData.data"
        />

        <Paginator
            :rows="paginatedData.data.length"
            :total-records="paginatedData.total"
            :first="paginatedData.from"
            @page="changePage"
        />
    </div>
</template>
