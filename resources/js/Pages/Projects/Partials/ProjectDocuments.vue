<script setup lang="ts">
import dayjs from "dayjs";
import Card from "primevue/card";
import { router } from "@inertiajs/vue3";
import Button from "primevue/button";
import { PropType, ref } from "vue";
import type ProjectDocument from "@/Types/Models/ProjectDocument";
import DocumentsCreateForm from "@/Pages/Projects/Partials/DocumentsCreateForm.vue";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import XCircle from "@/Components/Icons/XCircle.vue";
import Divider from "primevue/divider";
import { useConfirm } from "primevue/useconfirm";

defineProps({
    projectId: {
        type: Number,
        required: true,
    },
    documents: {
        type: Array as PropType<ProjectDocument[]>,
        required: true,
    },
});

const selectedDocument = ref<number>(0);

function confirmDelete(documentId: number) {
    selectedDocument.value = documentId;
    requireDeleteConfirmation();
}

const confirm = useConfirm();

const requireDeleteConfirmation = () => {
    confirm.require({
        header: "Delete Document?",
        message: "Are you sure you want to delete this document?",
        acceptProps: {
            label: "Delete",
            severity: "danger",
        },
        rejectProps: {
            label: "Cancel",
            severity: "secondary",
            outlined: true,
        },
        accept: () => {
            deleteDocument(selectedDocument.value);
        },
        reject: () => {
            selectedDocument.value = 0;
        },
    });
};

function deleteDocument(documentId: number) {
    router.delete(route("project-documents.destroy", documentId), {
        preserveScroll: true,
    });
}

const showCreateForm = ref(false);
function addDocument() {
    showCreateForm.value = true;
}
</script>

<template>
    <div class="pt-10">
        <Card>
            <template #content>
                <div class="flex items-center justify-between">
                    <div class="text-2xl">Documents</div>
                    <div class="flex">
                        <Button
                            @click="addDocument"
                            label="Add Document"
                            size="small"
                        >
                        </Button>
                    </div>
                </div>
                <Divider />
                <DataTable
                    :value="documents"
                    data-key="id"
                >
                    <Column
                        field="description"
                        header="Description"
                        style="width: 30%"
                    />
                    <Column
                        field="file_url"
                        header="File"
                        style="width: 30%"
                    >
                        <template #body="{ data }: { data: ProjectDocument }">
                            <a
                                v-if="data.file_name"
                                class="w-40 text-primary-600 hover:underline sm:truncate dark:text-primary-400"
                                :href="data.file_url"
                                target="_blank"
                                >{{ data.file_name ?? "-" }}</a
                            >
                            <div
                                v-if="!data.file_name"
                                class="w-40 text-xs"
                            >
                                -
                            </div>
                        </template>
                    </Column>
                    <Column
                        field="created_at"
                        header="Uploaded"
                        style="width: 20%"
                    >
                        <template #body="{ data }">
                            {{ dayjs(data.created_at).format("MMM D, YYYY h:mm A") }}
                        </template>
                    </Column>
                    <Column style="width: 10%">
                        <template #body="slotProps">
                            <Button
                                severity="danger"
                                @click="confirmDelete(slotProps.data.id)"
                                text
                            >
                                <XCircle class="size-6" />
                            </Button>
                        </template>
                    </Column>
                </DataTable>
            </template>
        </Card>

        <DocumentsCreateForm
            :visible="showCreateForm"
            :project-id="projectId"
            @close="showCreateForm = false"
        />
    </div>
</template>
