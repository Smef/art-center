<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import AuthenticationCard from "@/Components/AuthenticationCard.vue";
import AuthenticationCardLogo from "@/Components/AuthenticationCardLogo.vue";
import EmptyLayout from "@/Layouts/EmptyLayout.vue";
import GoogleOAuthSignInButton from "@/Components/GoogleOAuthSignInButton.vue";
import { ref } from "vue";
import CollapsibleContainer from "@/Components/CollapsibleContainer.vue";
import MicrosoftSignInButton from "@/Components/MicrosoftSignInButton.vue";
import Checkbox from "primevue/checkbox";
import Divider from "primevue/divider";
import LabeledTextInput from "@/Components/LabeledTextInput.vue";
import Button from "primevue/button";

defineProps({
    canResetPassword: {
        type: Boolean,
        required: false,
        default: false,
    },
    status: {
        type: String,
        required: false,
        default: null,
    },
});

defineOptions({
    layout: EmptyLayout,
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.transform((data) => ({
        ...data,
        remember: form.remember ? "on" : "",
    })).post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};

const showPasswordInput = ref(false);
</script>

<template>
    <Head title="Log in" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <div
            v-if="status"
            class="mb-4 text-sm font-medium text-green-600"
        >
            {{ status }}
        </div>

        <div class="flex flex-col items-center justify-center gap-y-5 py-5">
            <GoogleOAuthSignInButton :href="route('auth.redirect', 'google')" />

            <MicrosoftSignInButton :href="route('auth.redirect', 'azure')" />
        </div>

        <div class="mb-5 flex items-center justify-between gap-x-5">
            <div class="h-[1px] grow bg-surface-300"></div>

            <Divider />or<Divider />

            <div class="h-[1px] grow bg-surface-300"></div>
        </div>

        <CollapsibleContainer
            class="flex justify-center"
            :open="!showPasswordInput"
        >
            <button
                class="text-sm text-surface-500 hover:underline dark:text-surface-300"
                @click="showPasswordInput = true"
            >
                Log in with password
            </button>
        </CollapsibleContainer>

        <form @submit.prevent="submit">
            <CollapsibleContainer
                class=""
                :open="showPasswordInput"
            >
                <LabeledTextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    label="Email"
                    class="block"
                    required
                    autocomplete="username"
                    :error-message="form.errors.email"
                />

                <LabeledTextInput
                    id="password"
                    v-model="form.password"
                    type="password"
                    label="Password"
                    class="mt-4 block"
                    required
                    autocomplete="current-password"
                    :error-message="form.errors.password"
                />

                <div class="mt-4 block">
                    <label class="flex items-center">
                        <Checkbox
                            v-model="form.remember"
                            name="remember"
                            :binary="true"
                        />

                        <span class="ml-2 text-sm text-surface-600 dark:text-surface-400">Remember me</span>
                    </label>
                </div>

                <div class="mt-4 flex items-center justify-end">
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-sm text-surface-500 hover:underline dark:text-surface-300"
                    >
                        Forgot your password?
                    </Link>

                    <Button
                        class="ml-4"
                        :loading="form.processing"
                        label="Log in"
                        type="submit"
                    />
                </div>
            </CollapsibleContainer>
        </form>
    </AuthenticationCard>
</template>
