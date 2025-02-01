<script setup lang="ts">
import FormSubmitButton from "@/Components/FormSubmitButton.vue";
import SplitIcon from "@/Components/Icons/SplitIcon.vue";
import XCircle from "@/Components/Icons/XCircle.vue";
import LabeledFileInput from "@/Components/LabeledFileInput.vue";
import LabeledTextInput from "@/Components/LabeledTextInput.vue";
import type Voucher from "@/Types/Models/Voucher";
import { Head, Link } from "@inertiajs/vue3";
import { useForm } from "laravel-precognition-vue-inertia";
import Button from "primevue/button";
import Column from "primevue/column";
import DataTable from "primevue/datatable";
import InputNumber from "primevue/inputnumber";
import InputText from "primevue/inputtext";
import { computed, PropType, ref, watch } from "vue";
import Card from "primevue/card";

const props = defineProps({
    voucher: {
        type: Object as PropType<Voucher>,
        required: true,
    },
});

const placeholderFile = new File([], props.voucher.file_name ?? "");

const newVoucherLine = ref({
    id: null,
    description: "",
    quantity: 1,
    each: 0,
});

// we have to POST to upload a file, but can spoof put for Laravel actions
const spoofMethod = props.voucher?.id ? "PUT" : "POST";
const submitRoute = props.voucher?.id ? route("vouchers.update", props.voucher.id) : route("vouchers.store");
const form = useForm<{
    _method: "POST" | "PUT";
    number?: string;
    po_number?: string;
    company_id?: number;
    date: string;
    file: File | null;
    lines: {
        id?: number | null;
        description?: string;
        quantity: number | null;
        each?: number;
    }[];
    errors?: { [key: string]: string };
}>("post", submitRoute, {
    _method: spoofMethod,
    number: props.voucher.number,
    po_number: props.voucher.po_number,
    company_id: props.voucher.company_id,
    date: props.voucher.date,
    file: null,
    lines:
        props.voucher.lines?.length === 0
            ? [
                  {
                      id: null,
                      description: "",
                      quantity: 1,
                      each: 0,
                  },
              ]
            : props.voucher.lines,
}).validateFiles();

// ref the logo from here instead of the form so that we don't accidentally submit the placeholder file
const file = ref(props.voucher.file_url ? placeholderFile : undefined);
const splitView = ref(false);

function saveVoucher() {
    form.submit({ preserveScroll: true });
}

function deleteVoucher() {
    form.delete(route("vouchers.destroy", props.voucher.id));
}

function handleFileChange(newVoucherFile: File) {
    form.file = newVoucherFile || null;
    form.validate("file");
}

function addNewVoucherLine() {
    form.lines.push(newVoucherLine.value);
    newVoucherLine.value = {
        id: null,
        description: "",
        quantity: 1,
        each: 0,
    };
}

function deleteVoucherLine(index: number) {
    form.lines.splice(index, 1);
}

const parseVoucherForm = useForm("post", route("voucher-parse", props.voucher.id), {
    file: form.file,
    id: props.voucher.id,
});

function parseVoucher() {
    parseVoucherForm.file = form.file;
    parseVoucherForm.submit({
        preserveScroll: true,
        preserveState: false,
    });
}

function formatCurrency(value: number) {
    return new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: "USD",
    }).format(value);
}

const splitViewClasses = computed(() => {
    if (!splitView.value) {
        return "w-0";
    }
    return "hidden sm:block flex-1";
});
</script>

<template>
    <div class="flex flex-1 flex-col">
        <Head :title="voucher.number" />

        <div
            class="flex w-full flex-1"
            :class="{
                'gap-x-8': splitView,
            }"
        >
            <div class="flex-1 transition-all">
                <div class="flex items-center justify-between">
                    <h1 class="mb-4 text-4xl">{{ voucher.id ? "Voucher " + voucher.id : "New Voucher" }}</h1>

                    <Button
                        @click.prevent="splitView = !splitView"
                        label="Split View"
                        severity="secondary"
                    >
                        <template #icon>
                            <SplitIcon class="size-6" />
                        </template>
                    </Button>
                </div>

                <Card class="mt-5">
                    <template #content>
                        <form @submit.prevent="saveVoucher">
                            <div class="flex gap-x-8">
                                <div class="flex flex-col items-center">
                                    <div>
                                        <LabeledFileInput
                                            v-model="file"
                                            class="h-52 w-52"
                                            @change="handleFileChange"
                                            :error-message="form.errors.file"
                                            id="file"
                                            label="File"
                                            name="file"
                                            hint="File up to 1MB"
                                            :size-limit-kb="1024"
                                            @file-too-large="form.setErrors({ logo: 'File is too large.' })"
                                        />

                                        <div class="flex justify-center">
                                            <Button
                                                class="w-36"
                                                @click.prevent="parseVoucher"
                                                :loading="parseVoucherForm.processing"
                                                :label="parseVoucherForm.processing ? '' : 'Parse with AI'"
                                            />
                                        </div>
                                    </div>

                                    <div
                                        v-if="$page.props.errors.ParseError"
                                        class="max-w-48 text-balance text-center text-red-600 dark:text-red-300"
                                    >
                                        {{ $page.props.errors.ParseError }}
                                    </div>
                                </div>

                                <div class="flex-1">
                                    <LabeledTextInput
                                        label="Number"
                                        id="number"
                                        v-model="form.number"
                                        type="text"
                                        required
                                        :autofocus="!voucher.id"
                                        :error-message="form.errors.number"
                                        @change="form.validate('number')"
                                        name="number"
                                    />

                                    <LabeledTextInput
                                        id="po_number"
                                        label="PO"
                                        v-model="form.po_number"
                                        class=""
                                        :error-message="form.errors.po_number"
                                        name="po_number"
                                    />

                                    <LabeledTextInput
                                        id="date"
                                        label="Date"
                                        v-model="form.date"
                                        class=""
                                        type="date"
                                        :error-message="form.errors.date"
                                        name="date"
                                    />
                                </div>
                            </div>

                            <h2 class="py-8 text-lg">Lines</h2>

                            <DataTable
                                :value="form.lines"
                                class="bg-green-200"
                            >
                                <Column header="Description">
                                    <template #body="{ data, index }">
                                        <InputText
                                            class="w-full"
                                            :invalid="form.errors[`lines.${index}.description`] ?? false"
                                            v-model="data.description"
                                        />
                                    </template>
                                </Column>

                                <Column
                                    header="Quantity"
                                    class="w-24"
                                    :pt="{
                                        columnHeaderContent: {
                                            class: 'justify-end',
                                        },
                                    }"
                                >
                                    <template #body="{ data, index }">
                                        <InputNumber
                                            v-model="data.quantity"
                                            fluid
                                            class=""
                                            :invalid="form.errors[`lines.${index}.quantity`]"
                                            pt:pc-input:root:class="text-right"
                                        />
                                    </template>
                                </Column>
                                <Column
                                    header="Each"
                                    :pt="{
                                        columnHeaderContent: {
                                            class: 'justify-end',
                                        },
                                    }"
                                    class="w-36"
                                >
                                    <template #body="{ data, index }">
                                        <InputNumber
                                            v-model="data.each"
                                            mode="currency"
                                            currency="USD"
                                            locale="en-US"
                                            fluid
                                            class=""
                                            :invalid="form.errors[`linesm,    .${index}.each`]"
                                            pt:pc-input:root:class="text-right"
                                        />
                                    </template>
                                </Column>
                                <Column
                                    header="Total"
                                    class="w-24 text-right"
                                    :pt="{
                                        columnHeaderContent: {
                                            class: 'justify-end',
                                        },
                                    }"
                                >
                                    <template #body="{ data }">
                                        {{ formatCurrency((data.quantity ?? 0) * (data.each ?? 0)) }}
                                    </template>
                                </Column>
                                <Column class="w-12">
                                    <template #body="{ index }">
                                        <Button
                                            severity="danger"
                                            @click.prevent="deleteVoucherLine(index)"
                                            size="small"
                                            text
                                        >
                                            <template #icon>
                                                <XCircle class="size-4" />
                                            </template>
                                        </Button>
                                    </template>
                                </Column>
                            </DataTable>

                            <div class="py-4">
                                <Button
                                    severity="primary"
                                    size="small"
                                    @click.prevent="addNewVoucherLine"
                                    label="Add Row"
                                />
                            </div>
                            <div class="flex justify-end">
                                <div class="grid grid-cols-2 pr-14 text-xl font-semibold">
                                    <div class="">Total</div>

                                    <div class="">
                                        {{ formatCurrency(props.voucher.total) }}
                                    </div>
                                </div>
                            </div>
                            <div class="mt-5 flex items-center justify-between">
                                <div>
                                    <Button
                                        severity="danger"
                                        v-if="props.voucher?.id"
                                        :href="route('companies.destroy', props.voucher.id)"
                                        @click="deleteVoucher"
                                        label="Delete"
                                    />
                                </div>

                                <div class="flex items-center gap-x-2">
                                    <Button
                                        severity="secondary"
                                        :href="route('vouchers.index')"
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
                    </template>
                </Card>
            </div>

            <div
                :class="splitViewClasses"
                class="self-stretch overflow-hidden bg-surface-700 transition-all rounded-border"
            >
                <iframe
                    v-if="splitView"
                    class="h-full w-full border-4 border-solid border-surface-700"
                    :src="props.voucher.file_url"
                />
            </div>
        </div>
    </div>
</template>
