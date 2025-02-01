<script setup lang="ts">
import FormSubmitButton from "@/Components/FormSubmitButton.vue";
import LabeledTextInput from "@/Components/LabeledTextInput.vue";
import SelectableCheckboxList from "@/Components/SelectableCheckboxList.vue";
import Permission from "@/Types/Models/Permission";
import Role from "@/Types/Models/Role";
import { Head, Link } from "@inertiajs/vue3";
import { useForm } from "laravel-precognition-vue-inertia";
import { PropType } from "vue";
import Button from "primevue/button";

const props = defineProps({
    role: {
        type: Object as PropType<Role>,
        required: true,
    },
    permissions: {
        type: Array as PropType<Permission[]>,
        required: true,
    },
});

const method = props.role?.id ? "put" : "post";
const submitRoute = props.role?.id ? route("settings.roles.update", props.role.id) : route("settings.roles.store");
const form = useForm(method, submitRoute, {
    name: props.role.name,
    permissions: props.role.permissions.map((permission) => permission.name),
});

function submit() {
    form.submit();
}

function deleteRole() {
    form.delete(route("settings.roles.destroy", props.role.id));
}

const getPermissionOptions = () =>
    props.permissions.map((permission) => ({
        label: permission.name,
        description: permission.description,
        value: permission.name,
        id: `permission-${permission.name}`,
        name: `permission-${permission.name}`,
    }));
</script>

<template>
    <div>
        <Head :title="role.name" />

        <h1 class="mb-5 text-4xl">{{ role.id ? role.name : "New Role" }}</h1>

        <form @submit.prevent="submit">
            <LabeledTextInput
                label="Name"
                id="name_first"
                v-model="form.name"
                type="text"
                required
                :error-message="form.errors.name"
                @change="form.validate('name')"
            />

            <h2 class="text-lg">Permissions</h2>

            <SelectableCheckboxList
                legend="Permissions"
                :options="getPermissionOptions()"
                v-model="form.permissions"
            />

            <div class="mt-5 flex items-center justify-between">
                <div class="flex items-center gap-x-2">
                    <Button
                        severity="secondary"
                        label="Cancel"
                        :as="Link"
                        :href="route('settings.roles.index')"
                    />

                    <FormSubmitButton
                        :loading="form.processing"
                        :recently-successful="form.recentlySuccessful"
                        :has-errors="form.hasErrors"
                    />
                </div>

                <Button
                    severity="danger"
                    :as="Link"
                    v-if="props.role?.id"
                    :href="route('settings.roles.destroy', props.role.id)"
                    @click="deleteRole"
                    label="Delete"
                >
                </Button>
            </div>
        </form>
    </div>
</template>
