<script lang="ts" setup>
import FileUnknown from "@/Components/Icons/FileIcon.vue";
import FilePdf from "@/Components/Icons/FilePdf.vue";
import { useDropZone, type UseDropZoneOptions } from "@vueuse/core";
import Button from "primevue/button";
import { computed, PropType, ref } from "vue";

const props = defineProps({
    hasError: {
        type: Boolean,
        default: false,
    },
    modelValue: {
        type: [File],
        required: false,
        default: null,
    },
    dataTypes: {
        type: Array as PropType<UseDropZoneOptions["dataTypes"]>,
        default: null,
        required: false,
    },
    name: {
        type: String,
        default: null,
        required: false,
    },
    id: {
        type: String,
        default: null,
        required: false,
    },
    hint: {
        type: String,
        default: null,
        required: false,
    },
    sizeLimitKb: {
        type: Number,
        default: null,
        required: false,
    },
    class: {
        type: String,
        default: null,
        required: false,
    },
    thumbnail: {
        type: String,
        default: null,
        required: false,
    },
});

const emit = defineEmits(["update:modelValue", "change", "fileTooLarge"]);
const dropZoneRef = ref<HTMLDivElement>();
const file = ref<File | null>();
const fileHasChanged = ref(false);

function onFileChange(files: File[] | null) {
    // called when files are dropped on zone
    file.value = files?.[0];

    // check if the file is too large
    if (file.value && file.value?.size > props.sizeLimitKb * 1024 && file.value !== undefined) {
        file.value = undefined;
        emit("update:modelValue", file.value);
        emit("change", file.value);
        emit("fileTooLarge");
        return;
    }
    emit("update:modelValue", file.value);
    emit("change", file.value);

    fileHasChanged.value = true;
}

function handleRemove() {
    file.value = null;
    emit("update:modelValue", file.value);
    emit("change", file.value);
}

const { isOverDropZone } = useDropZone(dropZoneRef, {
    onDrop: onFileChange,
    dataTypes: props.dataTypes,
});

const fileIcon = computed(() => {
    switch (props.modelValue?.type) {
        case "application/pdf":
            return FilePdf;
        default:
            return FileUnknown;
    }
});

const dropZoneClasses = computed(() => {
    const classArray = [];

    if (isOverDropZone.value) {
        classArray.push("bg-surface-200 dark:bg-surface-800");
    }
    if (props.hasError) {
        classArray.push("outline-red-500 dark:outline-red-300");
    } else {
        classArray.push("outline-surface-300 dark:outline-surface-700");
    }
    return classArray.join(" ");
});

const imageThumbnail = computed(() => {
    // if the file has been changed, use the file as the thumbnail
    if (fileHasChanged.value) {
        return URL.createObjectURL(props.modelValue);
    }

    // if there is a thumbnail, use that instead
    if (props.thumbnail) {
        return props.thumbnail;
    }

    // otherwise there is no thumbnail
    return null;
});

const fileIsImage = computed(() => {
    if (props.modelValue?.type?.startsWith("image/") || props.thumbnail) {
        return true;
    }

    return false;
});

function handleInputClick(event: Event) {
    const target = event.target as HTMLInputElement;

    if (!target.files || target.files.length === 0) {
        onFileChange([]);
        return;
    }

    onFileChange([target.files[0]]);
}
</script>

<template>
    <div
        class="flex flex-col items-center overflow-clip bg-surface-0 outline outline-1 outline-surface-200 rounded-border dark:bg-surface-950 dark:outline-surface-700"
        :class="props.class"
    >
        <div
            ref="dropZoneRef"
            :class="dropZoneClasses"
            class="flex flex-1 items-center justify-center self-stretch"
        >
            <!-- No file present -->
            <div
                class="flex items-center justify-center"
                v-if="!modelValue"
            >
                <div>
                    <div class="text-center text-sm leading-6">
                        <label
                            :for="id"
                            class="cursor-pointer rounded-xl font-semibold text-primary-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-primary-600 focus-within:ring-offset-2 hover:text-primary-500 dark:text-primary"
                        >
                            <span>Upload a file</span>

                            <input
                                :id="id"
                                :name="name"
                                type="file"
                                class="sr-only"
                                @change="handleInputClick"
                            />
                        </label>

                        <span class=""> <br />or drag and drop </span>
                    </div>

                    <p
                        class="text-center text-xs leading-5 text-surface-600 dark:text-surface-400"
                        v-if="hint"
                    >
                        {{ hint }}
                    </p>
                </div>
            </div>

            <!-- File is present -->
            <div
                v-if="modelValue"
                class="relative flex max-w-full flex-1 items-center justify-center self-stretch"
            >
                <img
                    class="absolute h-full w-full object-contain p-2"
                    alt="Thumbnail of uploaded image"
                    :src="imageThumbnail"
                    v-if="fileIsImage"
                />

                <div
                    v-if="(!fileIsImage && fileHasChanged) || (!thumbnail && !fileIsImage)"
                    class="max-w-full items-center space-y-2"
                >
                    <Component
                        :is="fileIcon"
                        class="mx-auto h-10 w-10 fill-surface-500"
                    />

                    <div
                        class="min-h-0 min-w-0 max-w-full hyphens-auto text-wrap px-2 text-center"
                        style="overflow-wrap: break-word"
                    >
                        {{ modelValue.name }}
                    </div>
                </div>
            </div>
        </div>
        <div
            class="flex w-full justify-center border-t p-2 dark:border-surface-700"
            v-if="modelValue"
        >
            <Button
                severity="secondary"
                @click.stop.prevent="handleRemove"
                size="small"
                class="mx-auto"
                label="Delete"
            />
        </div>
    </div>
</template>
