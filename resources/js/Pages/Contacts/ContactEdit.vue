<script setup lang="ts">
import FormSubmitButton from "@/Components/FormSubmitButton.vue";
import LabeledTextInput from "@/Components/LabeledTextInput.vue";
import SearchDialogButton from "@/Components/SearchDialogButton.vue";
import type Company from "@/Types/Models/Company";
import Contact from "@/Types/Models/Contact";
import { Head, Link, router } from "@inertiajs/vue3";
import { useForm } from "laravel-precognition-vue-inertia";
import Button from "primevue/button";
import Card from "primevue/card";
import Divider from "primevue/divider";
import { PropType, ref } from "vue";

const props = defineProps({
    contact: {
        type: Object as PropType<Contact>,
        required: true,
    },
    companies: {
        type: Array as PropType<Company[]>,
        required: false,
        default: () => [],
    },
});

const method = props.contact?.id ? "put" : "post";
const submitRoute = props.contact?.id ? route("contacts.update", props.contact.id) : route("contacts.store");
const form = useForm(method, submitRoute, {
    name_first: props.contact.name_first,
    name_last: props.contact.name_last,
    role: props.contact.role,
    email: props.contact.email,
    phone: props.contact.phone,
    company_id: props.contact.company_id,
});

// keep track of the company separately so that we can update when the user picks a new company
const company = ref<Company | undefined>(props.contact.company);

function submit() {
    form.submit({ preserveScroll: true });
}

function deleteContact() {
    form.delete(route("contacts.destroy", props.contact.id));
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
}
</script>

<template>
    <div>
        <h1 class="mb-5 text-4xl">{{ contact.id ? contact.name_full : "New Contact" }}</h1>

        <div class="flex flex-col gap-x-8 gap-y-8 lg:flex-row">
            <Head :title="contact.name_full" />

            <Card>
                <template #content>
                    <form @submit.prevent="submit">
                        <div class="flex flex-col gap-6">
                            <div class="flex items-center gap-6">
                                <LabeledTextInput
                                    label="First Name"
                                    id="name_first"
                                    name="name_first"
                                    v-model="form.name_first"
                                    type="text"
                                    required
                                    :autofocus="!contact.id"
                                    autocomplete="name"
                                    :error-message="form.errors.name_first"
                                />

                                <LabeledTextInput
                                    label="Last Name"
                                    name="name_last"
                                    id="name_last"
                                    v-model="form.name_last"
                                    type="text"
                                    required
                                    autocomplete="name"
                                    :error-message="form.errors.name_last"
                                />
                            </div>

                            <LabeledTextInput
                                id="email"
                                label="Email"
                                name="email"
                                v-model="form.email"
                                class=""
                            />

                            <LabeledTextInput
                                id="role"
                                label="Role"
                                name="role"
                                v-model="form.role"
                                class=""
                            />

                            <LabeledTextInput
                                id="phone"
                                label="Phone Number"
                                name="phone"
                                v-model="form.phone"
                                class=""
                            />
                        </div>

                        <div class="mt-5 flex items-center justify-between">
                            <div>
                                <Button
                                    severity="danger"
                                    v-if="props.contact?.id"
                                    :href="route('contacts.destroy', props.contact.id)"
                                    @click="deleteContact"
                                    label="Delete"
                                />
                            </div>

                            <div class="flex items-center gap-x-2">
                                <Button
                                    severity="secondary"
                                    :href="route('contacts.index')"
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
            <div class="lg:min-w-72">
                <Card>
                    <template #title>
                        <div class="flex justify-between">
                            <div>Company</div>
                            <SearchDialogButton
                                @update-search="searchCompanies"
                                :display-data="companies"
                                @selected="handleCompanySelected"
                                v-slot="{ option }: { option: Company }"
                            >
                                <div>{{ option.name }}</div>

                                <div class="text-sm text-surface-400">
                                    {{ option.address_city }}, {{ option.address_state }}
                                </div>
                            </SearchDialogButton>
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
    </div>
</template>
