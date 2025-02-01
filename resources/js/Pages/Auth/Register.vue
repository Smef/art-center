<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import AuthenticationCard from "@/Components/AuthenticationCard.vue";
import AuthenticationCardLogo from "@/Components/AuthenticationCardLogo.vue";
import Checkbox from "primevue/checkbox";
import Button from "primevue/button";
import LabeledTextInput from "@/Components/LabeledTextInput.vue";
import EmptyLayout from "@/Layouts/EmptyLayout.vue";

defineOptions({
    layout: EmptyLayout,
});

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    terms: false,
});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <Head title="Register" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <form @submit.prevent="submit">
            <LabeledTextInput
                v-model="form.name"
                label="Name"
                name="name"
                id="name"
                type="text"
                :error-message="form.errors.name"
                required
                autofocus
                autocomplete="name"
            />

            <LabeledTextInput
                v-model="form.email"
                label="Email"
                name="email"
                id="email"
                type="email"
                :error-message="form.errors.email"
                class="mt-4"
                required
                autocomplete="username"
            />

            <LabeledTextInput
                v-model="form.password"
                label="Password"
                name="password"
                id="password"
                type="password"
                :error-message="form.errors.password"
                class="mt-4"
                required
                autocomplete="new-password"
            />

            <LabeledTextInput
                v-model="form.password_confirmation"
                label="Confirm Password"
                name="password_confirmation"
                id="password_confirmation"
                type="password"
                :error-message="form.errors.password_confirmation"
                class="mt-4"
                required
                autocomplete="new-password"
            />

            <div
                v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature"
                class="mt-4"
            >
                <div class="flex items-center">
                    <Checkbox
                        v-model="form.terms"
                        :binary="true"
                        name="terms"
                        required
                    />

                    <div class="ml-2">
                        I agree to the
                        <a
                            target="_blank"
                            :href="route('terms.show')"
                            class="text-sm text-surface-600 underline rounded-border hover:text-surface-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >Terms of Service</a
                        >

                        and
                        <a
                            target="_blank"
                            :href="route('policy.show')"
                            class="text-sm text-surface-600 underline rounded-border hover:text-surface-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >Privacy Policy</a
                        >
                    </div>
                </div>
                <small
                    class="p-error"
                    v-if="form.errors.terms"
                    >{{ form.errors.terms }}</small
                >
            </div>

            <div class="mt-4 flex items-center justify-end">
                <Link
                    :href="route('login')"
                    class="text-sm text-surface-500 hover:underline dark:text-surface-300"
                >
                    Already registered?
                </Link>

                <Button
                    class="ml-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    label="Register"
                    type="submit"
                />
            </div>
        </form>
    </AuthenticationCard>
</template>
