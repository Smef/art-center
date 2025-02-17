<script setup lang="ts">
import { Link, router } from "@inertiajs/vue3";
import { useForm } from "laravel-precognition-vue-inertia";
import Button from "primevue/button";
import Card from "primevue/card";
import { PropType } from "vue";
import User from "@/Types/Models/User";
import SearchDialogButton from "@/Components/SearchDialogButton.vue";
import CourseTeacherBase from "@/Types/Models/CourseTeacher";
import XCircle from "@/Components/Icons/XCircle.vue";
import WithRequired from "@/Types/WithRequired";

// Use our WithRequired type to ensure the teacher is always present, since this should be sent from the server
type CourseTeacher = WithRequired<CourseTeacherBase, "teacher">;

const props = defineProps({
    courseTeachers: {
        type: Array as PropType<CourseTeacher[]>,
        required: true,
    },
    courseId: {
        type: Number,
        required: true,
    },
    teachers: {
        type: Array as PropType<User[]>,
        required: true,
    },
});

const teacherForm = useForm<{
    course_id: number;
    user_id: number | null;
}>("post", route("course-teachers.store"), {
    course_id: props.courseId,
    user_id: null,
});

function searchTeachers(search: string) {
    router.get(
        route("courses.edit", props.courseId),
        {
            "search-teachers": search,
        },
        {
            only: ["teachers"],
            preserveScroll: true,
            preserveState: true,
        },
    );
}

function handleTeacherSelected(teacher: User) {
    teacherForm.user_id = teacher.id;
    teacherForm.submit({
        preserveScroll: true,
        preserveState: true,
    });
}

function removeTeacher(teacherCourseId: number) {
    teacherForm.delete(route("course-teachers.destroy", teacherCourseId), {
        preserveScroll: true,
        preserveState: true,
    });
}
</script>

<template>
    <Card class="mb-8 w-72">
        <template #title>
            <div class="flex justify-between">
                <div>Teachers</div>
                <SearchDialogButton
                    @update-search="searchTeachers"
                    :display-data="teachers"
                    @selected="handleTeacherSelected"
                    v-slot="data: { option: User }"
                >
                    <div>{{ data?.option.name_full }}</div>
                </SearchDialogButton>
            </div>
        </template>
        <template #content>
            <ul
                role="list"
                class="divide-y lg:max-w-lg dark:divide-surface-700"
            >
                <li
                    v-for="courseTeacher in courseTeachers"
                    :key="courseTeacher.id"
                    class="flex items-center justify-between gap-x-6 pr-2 hover:bg-surface-100 dark:hover:bg-surface-800"
                >
                    <Link
                        :href="route('settings.users.show', courseTeacher.teacher.id)"
                        class="flex min-w-0 gap-x-4 px-5 py-5"
                    >
                        <div class="min-w-0 flex-auto">
                            <p class="text-sm font-semibold leading-6 text-color-emphasis">
                                {{ courseTeacher.teacher.name_full }}
                            </p>
                            <p class="mt-1 truncate text-xs leading-5 text-muted-color">
                                {{ courseTeacher.teacher.email }}
                            </p>
                        </div>
                    </Link>
                    <Button
                        severity="danger"
                        @click="removeTeacher(courseTeacher.id)"
                        text
                    >
                        <XCircle class="size-6" />
                    </Button>
                </li>
            </ul>
            <div v-if="courseTeachers.length === 0">
                <p class="text-sm text-muted-color">No teachers assigned</p>
            </div>
        </template>
    </Card>
</template>
