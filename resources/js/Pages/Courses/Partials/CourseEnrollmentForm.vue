<script setup lang="ts">
import LabeledTextInput from "@/Components/LabeledTextInput.vue";
import FormSubmitButton from "@/Components/FormSubmitButton.vue";
import Card from "primevue/card";
import Divider from "primevue/divider";
import Button from "primevue/button";
import { useForm } from "laravel-precognition-vue-inertia";
import LabeledFileInput from "@/Components/LabeledFileInput.vue";
import Dialog from "primevue/dialog";

const props = defineProps({
    visible: {
        type: Boolean,
        required: false,
    },
    courseId: {
        type: Number,
        required: false,
        default: null,
    },
});

const form = useForm("post", route("enrollments.store"), {
    course_id: props.courseId,
    user_id: "",
}).validateFiles();

const emit = defineEmits(["close"]);
function close() {
    form.reset();
    emit("close");
}

function submit() {
    form.submit({
        preserveScroll: true,
        onSuccess: () => {
            close();
        },
    });
}
</script>

<template>
    <Dialog
        :visible="visible"
        modal
        class="sm:w-1/3"
    >
        <template #container>
            <Card>
                <template #title> Upload Document </template>
                <template #content>
                    <Divider />
                    <form @submit.prevent="submit">
                        <div class="flex flex-col gap-4">
                            <LabeledFileInput
                                class="h-40 w-full"
                                id="`document-${document.id}-file`"
                                label="File"
                                name="file"
                                hint="File up to 100MB"
                                :size-limit-kb="102400"
                                @file-too-large="form.setErrors({ file: 'File is too large.' })"
                                v-model="form.file"
                                :error-message="form.errors.file"
                                @change="form.validate('file')"
                            />
                            <LabeledTextInput
                                v-model="form.description"
                                label="Description"
                                name="description"
                                required
                                id="description"
                                :error-message="form.errors.description"
                                @change="form.validate('description')"
                            />
                            <div class="flex justify-end space-x-4">
                                <Button
                                    severity="secondary"
                                    @click="close"
                                    label="Cancel"
                                />
                                <FormSubmitButton
                                    @click="submit"
                                    :loading="form.processing"
                                >
                                    Upload
                                </FormSubmitButton>
                            </div>
                        </div>
                    </form>
                </template>
            </Card>
        </template>
    </Dialog>
</template>
