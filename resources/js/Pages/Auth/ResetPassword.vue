<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import AuthenticationCard from "@/Components/AuthenticationCard.vue";
import AuthenticationCardLogo from "@/Components/AuthenticationCardLogo.vue";
import EmptyLayout from "@/Layouts/EmptyLayout.vue";
import LabeledTextInput from "@/Components/LabeledTextInput.vue";
import Button from "primevue/button";

defineOptions({
    layout: EmptyLayout,
});

const props = defineProps({
    email: String,
    token: String,
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: "",
    password_confirmation: "",
});

const submit = () => {
    form.post(route("password.update"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <Head title="Reset Password" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <form @submit.prevent="submit">
            <LabeledTextInput
                label="Email"
                id="email"
                v-model="form.email"
                type="email"
                class="mt-1 block w-full"
                required
                autofocus
                autocomplete="username"
                :error-message="form.errors.email"
                name="email"
            />

            <div class="mt-4">
                <LabeledTextInput
                    label="Password"
                    name="password"
                    id="password"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full"
                    required
                    autocomplete="new-password"
                    :error-message="form.errors.password"
                />
            </div>

            <div class="mt-4">
                <LabeledTextInput
                    name="password_confirmation"
                    label="Confirm Password"
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    required
                    autocomplete="new-password"
                    :error-message="form.errors.password_confirmation"
                />
            </div>

            <div class="mt-4 flex items-center justify-end">
                <Button
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    type="submit"
                    size="small"
                >
                    Reset Password
                </Button>
            </div>
        </form>
    </AuthenticationCard>
</template>
