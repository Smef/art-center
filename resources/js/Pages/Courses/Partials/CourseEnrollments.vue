<script setup lang="ts">
import dayjs from "dayjs";
import Card from "primevue/card";
import { router } from "@inertiajs/vue3";
import Button from "primevue/button";
import { PropType, ref } from "vue";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import XCircle from "@/Components/Icons/XCircle.vue";
import Divider from "primevue/divider";
import { useConfirm } from "primevue/useconfirm";
import User from "@/Types/Models/User";
import CourseEnrollmentForm from "@/Pages/Courses/Partials/CourseEnrollmentForm.vue";
import Enrollment from "@/Types/Models/Enrollment";

defineProps({
    enrollments: {
        type: Array as PropType<User[]>,
        required: true,
    },
    courseId: {
        type: Number,
        required: true,
    },
});

const selectedStudent = ref<number>(0);

function confirmDelete(studentId: number) {
    selectedStudent.value = studentId;
    requireDeleteConfirmation();
}

const confirm = useConfirm();

const requireDeleteConfirmation = () => {
    confirm.require({
        header: "Delete Student?",
        message: "Are you sure you want to delete this student?",
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
            deleteStudent(selectedStudent.value);
        },
        reject: () => {
            selectedStudent.value = 0;
        },
    });
};

function deleteStudent(id: number) {
    router.delete(route("enrollments.destroy", id), {
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
                    <div class="text-2xl">Students</div>

                    <Button
                        @click="addDocument"
                        label="Add Student"
                        size="small"
                    >
                    </Button>
                </div>
                <Divider />
                <DataTable
                    :value="enrollments"
                    data-key="id"
                >
                    <Column
                        field="student.name_full"
                        header="Name"
                        style="width: 30%"
                    />
                    <Column
                        field="student.email"
                        header="Email"
                        style="width: 30%"
                    >
                        <template #body="{ data }: { data: Enrollment }">
                            <a
                                v-if="data.student?.email"
                                class="text-primary-emphasis hover:underline"
                                :href="'mailto:' + data.student?.email"
                                >{{ data.student?.email ?? "-" }}
                            </a>
                            <div
                                v-if="!data.student?.email"
                                class="text-xs"
                            >
                                -
                            </div>
                        </template>
                    </Column>
                    <Column
                        field="created_at"
                        header="Enrolled"
                        style="width: 20%"
                    >
                        <template #body="{ data }">
                            {{ dayjs(data.created_at).format("MMM D, YYYY") }}
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

        <CourseEnrollmentForm
            :visible="showCreateForm"
            :course-id="courseId"
            @close="showCreateForm = false"
        />
    </div>
</template>
