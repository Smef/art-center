<script setup lang="ts">
import ModelIndexTable from "@/Components/ModelIndexTable.vue";
import SearchInput from "@/Components/SearchInput.vue";
import ModelIndexTableColumn from "@/Types/ModelIndexTableColumn";
import Location from "@/Types/Models/Location";
import PaginatedData from "@/Types/PaginatedData";
import { Head, Link, router } from "@inertiajs/vue3";
import Button from "primevue/button";
import Card from "primevue/card";
import Paginator from "primevue/paginator";
import { PropType, ref, watch } from "vue";

const props = defineProps({
    paginatedData: {
        type: Object as PropType<PaginatedData<Location>>,
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
    },
    {
        label: "Name",
        key: "name",
        bold: true,
    },
    {
        label: "Website",
        key: "website",
    },
    {
        label: "Phone",
        key: "phone",
    },
];

function changePage({ page }: { page: number }) {
    router.get(props.paginatedData.links[page + 1].url ?? "/locations");
}
</script>

<template>
    <div>
        <Head title="Locations" />

        <div class="flex items-center justify-between">
            <h1 class="text-4xl">Locations</h1>

            <Button
                :href="route('locations.create')"
                label="Create New"
                :as="Link"
            />
        </div>

        <div class="pt-8">
            <Card>
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
                        :data="paginatedData.data"
                        route-name="locations.show"
                        :columns="columns"
                    >
                    </ModelIndexTable>

                    <Paginator
                        :rows="paginatedData.data.length"
                        :total-records="paginatedData.total"
                        :first="paginatedData.from"
                        @page="changePage"
                    />
                </template>
            </Card>
        </div>
    </div>
</template>
