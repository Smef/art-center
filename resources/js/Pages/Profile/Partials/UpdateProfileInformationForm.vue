<script setup>
import { ref } from "vue";
import { Link, router, useForm } from "@inertiajs/vue3";
import ActionMessage from "@/Components/ActionMessage.vue";
import FormSection from "@/Components/FormSection.vue";
import LabeledTextInput from "@/Components/LabeledTextInput.vue";
import Button from "primevue/button";

const props = defineProps({
    user: Object,
});

const form = useForm({
    _method: "PUT",
    name: props.user.name,
    email: props.user.email,
    photo: null,
});

const verificationLinkSent = ref(null);
const photoPreview = ref(null);
const photoInput = ref(null);

const updateProfileInformation = () => {
    console.log("updateProfileInformation");
    if (photoInput.value) {
        form.photo = photoInput.value.files[0];
    }

    form.post(route("user-profile-information.update"), {
        errorBag: "updateProfileInformation",
        preserveScroll: true,
        onSuccess: () => clearPhotoFileInput(),
    });
};

const sendEmailVerification = () => {
    verificationLinkSent.value = true;
};

const selectNewPhoto = () => {
    photoInput.value.click();
};

const updatePhotoPreview = () => {
    const photo = photoInput.value.files[0];

    if (!photo) return;

    const reader = new FileReader();

    reader.onload = (e) => {
        photoPreview.value = e.target.result;
    };

    reader.readAsDataURL(photo);
};

const deletePhoto = () => {
    router.delete(route("current-user-photo.destroy"), {
        preserveScroll: true,
        onSuccess: () => {
            photoPreview.value = null;
            clearPhotoFileInput();
        },
    });
};

const clearPhotoFileInput = () => {
    if (photoInput.value?.value) {
        photoInput.value.value = null;
    }
};
</script>

<template>
    <FormSection @submitted="updateProfileInformation">
        <template #title> Profile Information </template>

        <template #description> Update your account's profile information and email address. </template>

        <template #form>
            <!-- Profile Photo -->
            <div
                v-if="$page.props.jetstream.managesProfilePhotos"
                class="col-span-6 sm:col-span-4"
            >
                <!-- Profile Photo File Input -->
                <input
                    id="photo"
                    ref="photoInput"
                    type="file"
                    class="hidden"
                    @change="updatePhotoPreview"
                />

                <LabeledTextInput
                    id="photo"
                    type="file"
                    label="Photo"
                    class="mt-1 block w-full"
                    @change="updatePhotoPreview"
                    :error-message="form.errors.photo"
                />

                <!-- Current Profile Photo -->
                <div
                    v-show="!photoPreview"
                    class="mt-2"
                >
                    <img
                        :src="user.profile_photo_url"
                        :alt="user.name"
                        class="h-20 w-20 rounded-full object-cover"
                    />
                </div>

                <!-- New Profile Photo Preview -->
                <div
                    v-show="photoPreview"
                    class="mt-2"
                >
                    <span
                        class="block h-20 w-20 rounded-full bg-cover bg-center bg-no-repeat"
                        :style="'background-image: url(\'' + photoPreview + '\');'"
                    />
                </div>

                <Button
                    severity="secondary"
                    class="me-2 mt-2"
                    type="button"
                    @click.prevent="selectNewPhoto"
                    label="Select A New Photo"
                >
                </Button>

                <Button
                    severity="secondary"
                    v-if="user.profile_photo_path"
                    class="mt-2"
                    @click.prevent="deletePhoto"
                    label="Remove Photo"
                />
            </div>

            <!-- Name -->
            <div class="col-span-6 sm:col-span-4">
                <LabeledTextInput
                    id="name"
                    v-model="form.name"
                    type="text"
                    label="Name"
                    class="mt-1 block w-full"
                    required
                    autocomplete="name"
                    :error-message="form.errors.name"
                />
            </div>

            <!-- Email -->
            <div class="col-span-6 sm:col-span-4">
                <LabeledTextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    label="Email"
                    class="mt-1 block w-full"
                    required
                    autocomplete="username"
                    :error-message="form.errors.email"
                />

                <div v-if="$page.props.jetstream.hasEmailVerification && user.email_verified_at === null">
                    <p class="mt-2 text-sm">
                        Your email address is unverified.

                        <Button
                            :as="Link"
                            :href="route('verification.send')"
                            method="post"
                            class="rounded-md text-sm underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                            @click.prevent="sendEmailVerification"
                            label="Click here to re-send the verification email."
                        />
                    </p>

                    <div
                        v-show="verificationLinkSent"
                        class="mt-2 text-sm font-medium text-green-600 dark:text-green-400"
                    >
                        A new verification link has been sent to your email address.
                    </div>
                </div>
            </div>
        </template>

        <template #actions>
            <ActionMessage
                :on="form.recentlySuccessful"
                class="me-3"
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
