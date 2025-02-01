<script setup lang="ts">
import { PropType, ref } from "vue";
import Project from "@/Types/Models/Project";
import FormSubmitButton from "@/Components/FormSubmitButton.vue";
import LabeledTextInput from "@/Components/LabeledTextInput.vue";
import Button from "primevue/button";
import Card from "primevue/card";
import { useForm } from "laravel-precognition-vue-inertia";
import LabeledSelectMenu from "@/Components/LabeledSelectMenu.vue";
import LabeledNumberInput from "@/Components/LabeledNumberInput.vue";
import stateHelper from "@/Utilities/stateHelper";
import { Head, Link, router } from "@inertiajs/vue3";
import SearchDialogButton from "@/Components/SearchDialogButton.vue";
import Divider from "primevue/divider";
import type Company from "@/Types/Models/Company";
import ProjectDocuments from "@/Pages/Projects/Partials/ProjectDocuments.vue";
import { useConfirm } from "primevue/useconfirm";

const props = defineProps({
    project: {
        type: Object as PropType<Project>,
        required: true,
    },
    companies: {
        type: Array as PropType<Company[]>,
        required: false,
        default: () => [],
    },
    projectStatuses: {
        type: Array as PropType<string[]>,
        required: false,
        default: () => [],
    },
});

const method = props.project?.id ? "put" : "post";
const submitRoute = props.project?.id ? route("projects.update", props.project.id) : route("projects.store");
const form = useForm(method, submitRoute, {
    name: props.project.name,
    description: props.project.description,
    company_id: props.project.company_id,
    address_street: props.project.address_street,
    address_city: props.project.address_city,
    address_state: props.project.address_state,
    address_zip: props.project.address_zip,
    start_date: props.project.start_date,
    status: props.project.status,
});

// keep track of the company separately so that we can update when the user picks a new company
const company = ref<Company | undefined>(props.project.company);

const states = stateHelper.stateList;

function submit() {
    form.submit({ preserveScroll: true });
}

function deleteProject() {
    form.delete(route("projects.destroy", props.project.id));
}

// reload on search
function searchCompanies(searchInput: string) {
    router.reload({
        only: ["companies"],
        data: {
            filter: {
                name: searchInput,
            },
        },
    });
}

function handleCompanySelected(entry: Company) {
    form.company_id = entry.id;
    company.value = entry;
    form.validate("company_id");
}

const confirmProjectDelete = useConfirm();

const requireProjectDeleteConfirmation = () => {
    confirmProjectDelete.require({
        header: "Delete Project?",
        message: "Are you sure you want to delete this project?",
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
            deleteProject();
        },
        reject: () => {
            // do nothing
        },
    });
};
</script>

<template>
    <div>
        <h1 class="mb-5 text-4xl">{{ project.id ? project.name : "New Project" }}</h1>

        <div class="flex flex-col gap-x-8 gap-y-8 lg:flex-row">
            <Head :title="project.name" />

            <div class="lg:w-2/3">
                <Card>
                    <template #content>
                        <form @submit.prevent="submit">
                            <div class="space-y-6">
                                <LabeledTextInput
                                    id="name"
                                    name="name"
                                    label="Name"
                                    v-model="form.name"
                                    type="text"
                                    required
                                    :autofocus="!project.id"
                                    :error-message="form.errors.name"
                                    @change="form.validate('name')"
                                />

                                <LabeledTextInput
                                    id="description"
                                    name="description"
                                    label="Description"
                                    v-model="form.description"
                                    type="text"
                                    required
                                    :error-message="form.errors.description"
                                />

                                <LabeledTextInput
                                    id="address_street"
                                    name="address_street"
                                    label="Street"
                                    v-model="form.address_street"
                                    class=""
                                />

                                <div class="grid grid-cols-1 gap-x-4 gap-y-4 lg:grid-cols-3">
                                    <LabeledTextInput
                                        id="address_city"
                                        name="address_city"
                                        label="City"
                                        v-model="form.address_city"
                                        :error-message="form.errors.address_city"
                                    />

                                    <LabeledSelectMenu
                                        id="address_state"
                                        label="State"
                                        :options="states"
                                        display-property="name"
                                        value-property="abbr"
                                        v-model="form.address_state"
                                        :error-message="form.errors.address_state"
                                    />

                                    <LabeledNumberInput
                                        id="address_zip"
                                        name="address_zip"
                                        label="Zip"
                                        v-model="form.address_zip"
                                        :error-message="form.errors.address_zip"
                                    />
                                </div>

                                <LabeledTextInput
                                    id="date"
                                    name="date"
                                    label="Start Date"
                                    v-model="form.start_date"
                                    type="date"
                                    :error-message="form.errors.start_date"
                                    class="lg:w-48"
                                />

                                <LabeledSelectMenu
                                    id="status"
                                    label="Status"
                                    :options="projectStatuses"
                                    display-property="name"
                                    v-model="form.status"
                                    :error-message="form.errors.status"
                                    class="lg:w-48"
                                />
                            </div>

                            <div class="mt-5 flex items-center justify-between">
                                <div>
                                    <Button
                                        severity="danger"
                                        v-if="props.project?.id"
                                        @click="requireProjectDeleteConfirmation"
                                        label="Delete"
                                    />
                                </div>

                                <div class="flex items-center gap-x-2">
                                    <Button
                                        severity="secondary"
                                        :href="route('projects.index')"
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
                        </form>
                    </template>
                </Card>
            </div>

            <div class="lg:w-1/3">
                <Card>
                    <template #title>
                        <div class="flex justify-between">
                            <div>Company</div>
                            <SearchDialogButton
                                @update-search="searchCompanies"
                                :display-data="companies"
                                @selected="handleCompanySelected"
                                v-slot="{ entry }"
                            >
                                <div>{{ entry.name }}</div>

                                <div class="text-sm text-gray-400 hover:text-white">
                                    {{ entry.address_city }}, {{ entry.address_state }}
                                </div>
                            </SearchDialogButton>
                        </div>
                        <div
                            v-if="form.errors.company_id && !company"
                            class="text-sm text-red-600 dark:text-red-300"
                        >
                            {{ form.errors.company_id }}
                        </div>
                    </template>
                    <template #content>
                        <div v-if="company">
                            <Divider />
                            <div class="flex flex-col gap-2">
                                <Link
                                    :href="route('companies.show', company.id)"
                                    class="mt-1 text-base font-semibold leading-6 text-surface-900 hover:underline dark:text-surface-0"
                                    v-if="company"
                                >
                                    {{ company.name }}
                                </Link>
                                <p class="text-sm font-medium leading-6 text-surface-900 dark:text-surface-0">
                                    {{ company.phone }}
                                </p>

                                <a
                                    target="_blank"
                                    :href="company.website"
                                    class="text-sm leading-6 text-primary-600 hover:underline dark:text-primary-400"
                                >
                                    {{ company.website }}</a
                                >
                            </div>
                        </div>
                    </template>
                </Card>
            </div>
        </div>

        <ProjectDocuments
            v-if="project.id"
            :project-id="project.id"
            :documents="project.project_documents"
        />
    </div>
</template>
