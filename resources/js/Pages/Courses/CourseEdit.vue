<script setup lang="ts">
import FormSubmitButton from "@/Components/FormSubmitButton.vue";
import LabeledTextInput from "@/Components/LabeledTextInput.vue";
import LabeledSelectMenu from "@/Components/LabeledSelectMenu.vue";
import Course from "@/Types/Models/Course";
import Location from "@/Types/Models/Location";
import { Head, Link } from "@inertiajs/vue3";
import { useForm } from "laravel-precognition-vue-inertia";
import Button from "primevue/button";
import Card from "primevue/card";
import DataView from "primevue/dataview";
import { PropType } from "vue";
import User from "@/Types/Models/User";

const props = defineProps({
    course: {
        type: Object as PropType<Course>,
        required: true,
    },
    locations: {
        type: Array as PropType<Location[]>,
        required: true,
    },
    teachers: {
        type: Array as PropType<User[]>,
        required: true,
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

function searchTeachers(search: string) {
    // fetch teachers from the server
}

function handleTeacherSelected(teacher: Teacher) {
    // add the teacher to the course
}
</script>

<template>
    <div>
        <Head :title="course.id ? course.name : 'New Course'" />

        <h1 class="mb-4 text-4xl">{{ course.id ? course.name : "New Course" }}</h1>

        <div class="flex flex-col gap-x-8 gap-y-8 lg:flex-row">
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

            <div v-if="course.id">
                <Card class="mb-8">
                    <template #title>
                        <div class="flex justify-between">
                            <div>Teachers</div>
                            <SearchDialogButton
                                @update-search="searchTeachers"
                                :display-data="teachers"
                                @selected="handleTeacherSelected"
                                v-slot="{ option }: { option: Teacher }"
                            >
                                <div>{{ option.name }}</div>

                                <div class="text-sm text-surface-400">
                                    {{ option.address_city }}, {{ option.address_state }}
                                </div>
                            </SearchDialogButton>
                        </div>
                    </template>
                    <template #content>
                        <DataView
                            :value="course.teachers"
                            data-key="id"
                        >
                            <template #list="{ items: teachers }">
                                <ul
                                    role="list"
                                    class="divide-y lg:max-w-lg dark:divide-surface-700"
                                >
                                    <li
                                        v-for="teacher in teachers"
                                        :key="teacher.id"
                                    >
                                        <Link
                                            class="flex justify-between gap-x-6 px-5 py-5 hover:bg-surface-100 dark:hover:bg-surface-800"
                                        >
                                            <div class="flex min-w-0 gap-x-4">
                                                <div class="min-w-0 flex-auto">
                                                    <p class="text-sm font-semibold leading-6 text-color-emphasis">
                                                        {{ teacher.name_full }}
                                                    </p>
                                                    <p class="mt-1 truncate text-xs leading-5 text-muted-color">
                                                        {{ teacher.email }}
                                                    </p>
                                                </div>
                                            </div>
                                        </Link>
                                    </li>
                                </ul>
                            </template>
                        </DataView>
                    </template>
                </Card>

                <Card>
                    <template #title>Students</template>
                    <template #content>
                        <DataView
                            :value="course.students"
                            data-key="id"
                        >
                            <template #list="{ items: students }">
                                <ul
                                    role="list"
                                    class="divide-y lg:max-w-lg dark:divide-surface-700"
                                >
                                    <li
                                        v-for="student in students"
                                        :key="student.id"
                                    >
                                        <Link
                                            class="flex justify-between gap-x-6 px-5 py-5 hover:bg-surface-100 dark:hover:bg-surface-800"
                                        >
                                            <div class="flex min-w-0 gap-x-4">
                                                <div class="min-w-0 flex-auto">
                                                    <p class="text-sm font-semibold leading-6 text-color-emphasis">
                                                        {{ student.name_full }}
                                                    </p>
                                                    <p class="mt-1 truncate text-xs leading-5 text-muted-color">
                                                        {{ student.email }}
                                                    </p>
                                                </div>
                                            </div>
                                        </Link>
                                    </li>
                                </ul>
                            </template>
                        </DataView>
                    </template>
                </Card>
            </div>
        </div>
    </div>
</template>
