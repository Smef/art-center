<script setup lang="ts">
import ModelIndexTable from "@/Components/ModelIndexTable.vue";
import SearchInput from "@/Components/SearchInput.vue";
import ModelIndexTableColumn from "@/Types/ModelIndexTableColumn";
import Contact from "@/Types/Models/Contact";
import PaginatedData from "@/Types/PaginatedData";
import { Head, Link, router } from "@inertiajs/vue3";
import Button from "primevue/button";
import Card from "primevue/card";
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
        width: "5rem",
    },
    {
        label: "#",
        key: "number",
        bold: true,
        width: "8rem",
    },
    {
        label: "PO",
        key: "po_number",
        width: "8rem",
    },
    {
        slotName: "total",
        label: "Total",
        textAlign: "right",
        key: "total",
        bold: true,
        dataFormatCallback: (value: number) =>
            new Intl.NumberFormat("en-US", {
                style: "currency",
                currency: "USD",
            }).format(value),
    },
];

function changePage({ page }: { page: number }) {
    router.get(props.paginatedData.links[page + 1].url ?? "/vouchers");
}
</script>

<template>
    <div>
        <Head title="Vouchers" />

        <div class="flex items-center justify-between">
            <h1 class="text-4xl">Vouchers</h1>

            <Button
                :href="route('vouchers.create')"
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
                        :columns="columns"
                        route-name="vouchers.edit"
                        :data="paginatedData.data"
                    />

                    <Paginator
                        :rows="paginatedData.data.length"
                        :total-records="paginatedData.total"
                        :page="paginatedData.current_page"
                        :first="paginatedData.from"
                        @page="changePage"
                    />
                </template>
            </Card>
        </div>
    </div>
</template>
