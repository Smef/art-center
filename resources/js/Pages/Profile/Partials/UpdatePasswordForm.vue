<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import ActionMessage from "@/Components/ActionMessage.vue";
import FormSection from "@/Components/FormSection.vue";
import LabeledTextInput from "@/Components/LabeledTextInput.vue";
import Button from "primevue/button";

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: "",
    password: "",
    password_confirmation: "",
});

const updatePassword = () => {
    form.put(route("user-password.update"), {
        errorBag: "updatePassword",
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset("password", "password_confirmation");
                passwordInput.value.focus();
            }

            if (form.errors.current_password) {
                form.reset("current_password");
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <FormSection @submitted="updatePassword">
        <template #title> Update Password</template>

        <template #description> Ensure your account is using a long, random password to stay secure.</template>

        <template #form>
            <div class="col-span-6 sm:col-span-4">
                <LabeledTextInput
                    id="current_password"
                    ref="currentPasswordInput"
                    v-model="form.current_password"
                    type="password"
                    label="Current Password"
                    :error-message="form.errors.current_password"
                    class="mt-1 block w-full"
                    autocomplete="current-password"
                />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <LabeledTextInput
                    id="password"
                    ref="passwordInput"
                    v-model="form.password"
                    type="password"
                    label="New Password"
                    :error-message="form.errors.password"
                    class="mt-1 block w-full"
                    autocomplete="new-password"
                />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <LabeledTextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    label="Confirm Password"
                    :error-message="form.errors.password_confirmation"
                    class="mt-1 block w-full"
                    autocomplete="new-password"
                />
            </div>
        </template>

        <template #actions>
            <ActionMessage
                :on="form.recentlySuccessful"
                class="mr-3"
            >
                Saved.
            </ActionMessage>

            <Button
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
                label="Save"
                type="submit"
            />
        </template>
    </FormSection>
</template>
