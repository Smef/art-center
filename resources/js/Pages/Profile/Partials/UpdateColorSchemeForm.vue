<script lang="ts" setup>
import { useForm } from "laravel-precognition-vue-inertia";
import FormSection from "@/Components/FormSection.vue";
import LabeledSelectMenu from "@/Components/LabeledSelectMenu.vue";
import { ref } from "vue";
import { Button } from "primevue";
import useColorScheme from "@/Composables/useColorScheme";
import { User } from "@sentry/vue";
import { usePage } from "@inertiajs/vue3";

const page = usePage();
const user = page.props.auth.user as User;
const colorScheme = ref<"light" | "dark" | "auto">(user?.color_scheme ?? "auto");

const form = useForm("put", route("user.color-scheme.update"), {
    color_scheme: colorScheme.value,
});

const { setColorModeToUserPreference } = useColorScheme();

function updateColorScheme() {
    form.submit({
        preserveScroll: true,
        onSuccess: () => {
            console.log("user preference: " + user.color_scheme);
            setColorModeToUserPreference();
        },
    });
    colorScheme.value = form.color_scheme;
}

const options = [
    { display: "Light", value: "light" },
    { display: "Dark", value: "dark" },
    { display: "Auto", value: "auto" },
];
</script>

<template>
    <FormSection @submitted="updateColorScheme">
        <template #title> Color Scheme </template>
        <template #description> Choose your preferred color scheme. </template>
        <template #form>
            <LabeledSelectMenu
                class="w-32"
                id="color-scheme"
                name="color-scheme"
                label=" Color Scheme"
                :options="options"
                v-model="form.color_scheme"
                :error-message="form.errors.color_scheme"
            />
        </template>
        <template #actions>
            <Button
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
                label="Save"
                type="submit"
            />
        </template>
    </FormSection>
</template>
