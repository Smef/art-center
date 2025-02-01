<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import AuthenticationCard from "@/Components/AuthenticationCard.vue";
import AuthenticationCardLogo from "@/Components/AuthenticationCardLogo.vue";
import InputError from "@/Components/InputError.vue";
import EmptyLayout from "@/Layouts/EmptyLayout.vue";
import Button from "primevue/button";
import LabeledTextInput from "@/Components/LabeledTextInput.vue";

defineOptions({
    layout: EmptyLayout,
});

defineProps({
    status: String,
});

const form = useForm({
    email: "",
});

const submit = () => {
    form.post(route("password.email"));
};
</script>

<template>
    <Head title="Forgot Password" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <div class="mb-4 text-sm text-surface-600 dark:text-surface-300">
            Forgot your password? No problem. Just let us know your email address and we will email you a password reset
            link that will allow you to choose a new one.
        </div>

        <div
            v-if="status"
            class="mb-4 text-sm font-medium text-green-600"
        >
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <LabeledTextInput
                    label="Email"
                    id="email"
                    name="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                    required
                    autofocus
                    autocomplete="username"
                    :error-message="form.errors.email"
                />
            </div>

            <div class="mt-4 flex items-center justify-end">
                <Button
                    size="small"
                    label="Email Password Reset Link"
                    :loading="form.processing"
                    type="submit"
                />
            </div>
        </form>
    </AuthenticationCard>
</template>
