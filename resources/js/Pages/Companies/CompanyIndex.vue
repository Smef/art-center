<script setup lang="ts">
import ModelIndexTable from "@/Components/ModelIndexTable.vue";
import SearchInput from "@/Components/SearchInput.vue";
import ModelIndexTableColumn from "@/Types/ModelIndexTableColumn";
import Company from "@/Types/Models/Company";
import PaginatedData from "@/Types/PaginatedData";
import { Head, Link, router } from "@inertiajs/vue3";
import Button from "primevue/button";
import Card from "primevue/card";
import Paginator from "primevue/paginator";
import { PropType, ref, watch } from "vue";

const props = defineProps({
    paginatedData: {
        type: Object as PropType<PaginatedData<Company>>,
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
        label: "Logo",
        width: "200px",
        slotName: "logo",
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
    router.get(props.paginatedData.links[page + 1].url ?? "/companies");
}
</script>

<template>
    <div>
        <Head title="Companies" />

        <div class="flex items-center justify-between">
            <h1 class="text-4xl">Companies</h1>

            <Button
                :href="route('companies.create')"
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
                        route-name="companies.show"
                        :columns="columns"
                    >
                        <template #logo="{ model: company }: { model: Company }">
                            <img
                                :src="company.logo_url"
                                class="max-h-10 w-full object-contain"
                                :alt="company.name + ' company logo'"
                                v-if="company.logo_url"
                            />
                        </template>
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
