<script setup lang="ts">
import FormSubmitButton from "@/Components/FormSubmitButton.vue";
import LabeledTextInput from "@/Components/LabeledTextInput.vue";
import LabeledSelectMenu from "@/Components/LabeledSelectMenu.vue";
import Course from "@/Types/Models/Course";
import Location from "@/Types/Models/Location";
import { Head, Link, router } from "@inertiajs/vue3";
import { useForm } from "laravel-precognition-vue-inertia";
import Button from "primevue/button";
import Card from "primevue/card";
import { PropType } from "vue";
import User from "@/Types/Models/User";
import SearchDialogButton from "@/Components/SearchDialogButton.vue";
import CourseTeacherBase from "@/Types/Models/CourseTeacher";
import CourseEnrollments from "@/Pages/Courses/Partials/CourseEnrollments.vue";
import XCircle from "@/Components/Icons/XCircle.vue";
import WithRequired from "@/Types/WithRequired";
import CourseTeachers from "@/Pages/Courses/Partials/CourseTeachers.vue";

// Use our WithRequired type to ensure the teacher is always present, since this should be sent from the server
type CourseTeacher = WithRequired<CourseTeacherBase, "teacher">;

const props = defineProps({
    course: {
        type: Object as PropType<Course>,
        required: true,
    },
    courseTeachers: {
        type: Array as PropType<CourseTeacher[]>,
        required: true,
    },
    enrollments: {
        type: Array as PropType<User[]>,
        required: true,
    },
    locations: {
        type: Array as PropType<Location[]>,
        required: true,
    },
    teachers: {
        type: Array as PropType<User[]>,
        required: false,
        default: () => [],
    },
});

// we have to POST to upload a file, but can spoof put for Laravel actions
const spoofMethod = props.course?.id ? "PUT" : "POST";
const submitRoute = props.course?.id ? route("courses.update", props.course.id) : route("courses.store");

const form = useForm("post", submitRoute, {
    _method: spoofMethod,
    name: props.course.name,
    location_id: props.course.location_id,
    start_date: props.course.start_date,
    end_date: props.course.end_date,
});

function submit() {
    form.submit({ preserveScroll: true });
}

function deleteCourse() {
    form.delete(route("courses.destroy", props.course.id));
}
</script>

<template>
    <div>
        <Head :title="course.id ? course.name : 'New Course'" />

        <h1 class="mb-4 text-4xl">{{ course.id ? course.name : "New Course" }}</h1>

        <div class="flex flex-col items-start gap-x-8 gap-y-8 lg:flex-row">
            <form @submit.prevent="submit">
                <Card class="w-96">
                    <template #content>
                        <div class="flex flex-col gap-6">
                            <LabeledTextInput
                                label="Name"
                                id="name"
                                name="name"
                                v-model="form.name"
                                type="text"
                                required
                                :autofocus="!course.id"
                                autocomplete="name"
                                :error-message="form.errors.name"
                                @change="form.validate('name')"
                            />

                            <LabeledSelectMenu
                                :options="locations"
                                display-property="name"
                                value-property="id"
                                v-model="form.location_id"
                                label="Location"
                                id="location_id"
                                :error-message="form.errors.location_id"
                            />

                            <LabeledTextInput
                                label="Start Date"
                                id="start_date"
                                name="start_date"
                                v-model="form.start_date"
                                type="date"
                                required
                                :error-message="form.errors.start_date"
                                @change="form.validate('start_date')"
                            />

                            <LabeledTextInput
                                label="End Date"
                                id="end_date"
                                name="end_date"
                                v-model="form.end_date"
                                type="date"
                                required
                                :error-message="form.errors.end_date"
                                @change="form.validate('end_date')"
                            />
                        </div>

                        <div class="mt-5 flex items-center justify-between">
                            <div>
                                <Button
                                    severity="danger"
                                    v-if="props.course?.id"
                                    :href="route('courses.destroy', props.course.id)"
                                    @click="deleteCourse"
                                    label="Delete"
                                />
                            </div>

                            <div class="flex items-center gap-x-2">
                                <Button
                                    severity="secondary"
                                    :href="route('courses.index')"
                                    label="Cancel"
                                    :as="Link"
                                />

                                <FormSubmitButton
                                    :loading="form.processing"
                                    :recently-successful="form.recentlySuccessful"
                                    :has-errors="form.hasErrors"
                                />
                            </div>
                        </div>
                    </template>
                </Card>
            </form>

            <CourseTeachers
                :course-teachers="courseTeachers"
                :course-id="course.id"
                :teachers="teachers"
            />
        </div>
        <div class="pt-8">
            <CourseEnrollments
                :enrollments="enrollments"
                :course-id="course.id"
            />
        </div>
    </div>
</template>
