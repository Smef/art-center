<script setup lang="ts">
import FormSubmitButton from "@/Components/FormSubmitButton.vue";
import LabeledSelectMenu from "@/Components/LabeledSelectMenu.vue";
import LabeledTextInput from "@/Components/LabeledTextInput.vue";
import stateHelper from "@/Utilities/stateHelper";
import { Head, Link } from "@inertiajs/vue3";
import { useForm } from "laravel-precognition-vue-inertia";
import Button from "primevue/button";
import Card from "primevue/card";
import DataView from "primevue/dataview";
import { PropType } from "vue";
import Location from "@/Types/Models/Location";

const props = defineProps({
    location: {
        type: Object as PropType<Location>,
        required: true,
    },
});

const states = stateHelper.stateList;

// const placeholderFile = new File([], "thumbnail");

// we have to POST to upload a file, but can spoof put for Laravel actions
const spoofMethod = props.location?.id ? "PUT" : "POST";
const submitRoute = props.location?.id ? route("locations.update", props.location.id) : route("locations.store");
const form = useForm<{
    _method: string;
    name: string;
    website?: string;
    phone?: string;
    address_street?: string;
    address_city?: string;
    address_state?: string;
    address_zip?: string;
}>("post", submitRoute, {
    _method: spoofMethod,
    name: props.location.name,
    website: props.location.website,
    phone: props.location.phone,
    address_street: props.location.address_street,
    address_city: props.location.address_city,
    address_state: props.location.address_state,
    address_zip: props.location.address_zip,
});

function submit() {
    form.submit({ preserveScroll: true });
}

function deleteLocation() {
    form.delete(route("locations.destroy", props.location.id));
}
</script>

<template>
    <div>
        <Head :title="location.name" />

        <h1 class="mb-4 text-4xl">{{ location.id ? location.name : "New Location" }}</h1>

        <div class="flex flex-col gap-x-8 gap-y-8 lg:flex-row">
            <form @submit.prevent="submit">
                <Card>
                    <template #content>
                        <div class="flex flex-col gap-6">
                            <LabeledTextInput
                                label="Name"
                                id="name"
                                name="name"
                                v-model="form.name"
                                type="text"
                                required
                                :autofocus="!location.id"
                                autocomplete="name"
                                :error-message="form.errors.name"
                                @change="form.validate('name')"
                            />

                            <LabeledTextInput
                                id="website"
                                label="Website"
                                v-model="form.website"
                                class=""
                                name="website"
                            />

                            <LabeledTextInput
                                id="phone"
                                label="Phone Number"
                                v-model="form.phone"
                                class=""
                                name="phone"
                            />

                            <LabeledTextInput
                                id="address_street"
                                name="address_street"
                                label="Street"
                                v-model="form.address_street"
                                class=""
                            />

                            <div class="gap-4 lg:flex">
                                <LabeledTextInput
                                    id="address_city"
                                    name="address_city"
                                    label="City"
                                    v-model="form.address_city"
                                    :error-message="form.errors.address_city"
                                />

                                <LabeledSelectMenu
                                    :options="states"
                                    display-property="name"
                                    value-property="abbr"
                                    v-model="form.address_state"
                                    label="State"
                                    id="address_state"
                                    :error-message="form.errors.address_state"
                                />

                                <LabeledTextInput
                                    id="address_zip"
                                    name="address_zip"
                                    label="Zip"
                                    v-model="form.address_zip"
                                    :error-message="form.errors.address_zip"
                                />
                            </div>
                        </div>

                        <div class="mt-5 flex items-center justify-between">
                            <div>
                                <Button
                                    severity="danger"
                                    v-if="props.location?.id"
                                    :href="route('locations.destroy', props.location.id)"
                                    @click="deleteLocation"
                                    label="Delete"
                                />
                            </div>

                            <div class="flex items-center gap-x-2">
                                <Button
                                    severity="secondary"
                                    :href="route('locations.index')"
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

            <div>
                <Card>
                    <template #title> Contacts </template>
                    <template #content>
                        <DataView
                            :value="location.contacts"
                            data-key="id"
                        >
                            <template #list="{ items: contacts }">
                                <ul
                                    role="list"
                                    class="divide-y lg:max-w-lg dark:divide-surface-700"
                                >
                                    <li
                                        v-for="contact in contacts"
                                        :key="contact.id"
                                    >
                                        <Link
                                            :href="route('contacts.edit', contact.id)"
                                            class="flex justify-between gap-x-6 px-5 py-5 hover:bg-surface-100 dark:hover:bg-surface-800"
                                        >
                                            <div class="flex min-w-0 gap-x-4">
                                                <div class="min-w-0 flex-auto">
                                                    <p class="text-sm font-semibold leading-6 text-color-emphasis">
                                                        {{ contact.name_full }}
                                                    </p>

                                                    <p class="mt-1 truncate text-xs leading-5 text-muted-color">
                                                        {{ contact.email }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">
                                                <p class="text-sm leading-6">{{ contact.role }}</p>
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
