<script setup lang="ts">
import FormSubmitButton from "@/Components/FormSubmitButton.vue";
import LabeledSelectMenu from "@/Components/LabeledSelectMenu.vue";
import LabeledTextInput from "@/Components/LabeledTextInput.vue";
import User from "@/Types/Models/User";
import { Head, Link } from "@inertiajs/vue3";
import { useForm } from "laravel-precognition-vue-inertia";
import Button from "primevue/button";
import { PropType } from "vue";

const props = defineProps({
    user: {
        type: Object as PropType<User>,
        required: true,
    },
    roles: {
        type: Array as PropType<string[]>,
        required: true,
    },
});

const method = props.user?.id ? "put" : "post";
const submitRoute = props.user?.id ? route("settings.users.update", props.user.id) : route("settings.users.store");
const form = useForm(method, submitRoute, {
    name: props.user.name,
    email: props.user.email,
    role: props.user.roles[0]?.name,
});

function submit() {
    form.submit();
}
</script>

<template>
    <div>
        <Head :title="user.name" />

        <h1 class="mb-5 text-4xl">{{ user.id ? user.name : "New User" }}</h1>

        <form @submit.prevent="submit">
            <LabeledTextInput
                label="Name"
                id="name"
                name="name"
                v-model="form.name"
                type="text"
                required
                autofocus
                :error-message="form.errors.name"
                @change="form.validate('name')"
            />

            <LabeledTextInput
                id="email"
                label="Email"
                name="email"
                v-model="form.email"
                class=""
                required
                :error-message="form.errors.email"
                @change="form.validate('email')"
            />

            <LabeledSelectMenu
                id="role"
                label="Role"
                v-model="form.role"
                class=""
                :options="roles"
                display-property="name"
                value-property="name"
                :error-message="form.errors.role"
                with-clear
            />

            <div class="mt-5">
                <div class="flex items-center gap-x-2">
                    <Button
                        severity="secondary"
                        :href="route('settings.users.index')"
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
    </div>
</template>
