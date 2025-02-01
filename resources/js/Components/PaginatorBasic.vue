<script setup>
import { Link } from "@inertiajs/vue3";
import ChevronDown from "@/Components/Icons/ChevronDown.vue";
import { computed } from "vue";

const props = defineProps({
    links: {
        type: Array,
        required: true,
    },
    currentPage: {
        type: Number,
        required: true,
    },
    lastPage: {
        type: Number,
        required: true,
    },
    from: {
        type: Number,
        default: null,
        required: false,
    },
    to: {
        type: Number,
        default: null,
        required: false,
    },
    of: {
        type: Number,
        required: true,
    },
    nextPageUrl: {
        type: String,
        required: false,
        default: null,
    },
    previousPageUrl: {
        type: String,
        required: false,
        default: null,
    },
    itemCountName: {
        type: String,
        required: false,
        default: "results",
    },
});

function getTrimmedPageLinks(currentPage, lastPage) {
    const onEachSide = 2;
    const leftLimit = currentPage - onEachSide;
    const rightLimit = currentPage + onEachSide + 1;
    const pageNumbers = [];
    const pageLinks = [];

    // create an array of page numbers we want to show in pagination
    for (let i = 1; i <= lastPage; i += 1) {
        if (i === 1 || i === lastPage || (i >= leftLimit && i < rightLimit)) {
            pageNumbers.push(i);
        }
    }

    // add dots
    let previousPage = 0;
    pageNumbers.forEach((thisPage) => {
        // insert dots before this page if there is a gap
        if (thisPage - previousPage !== 1) {
            pageLinks.push({ url: null, label: "...", active: false });
        }
        pageLinks.push(props.links[thisPage]);
        previousPage = thisPage;
    });

    return pageLinks;
}

const pageLinks = computed(() => getTrimmedPageLinks(props.currentPage, props.lastPage));
</script>

<template>
    <div class="flex items-center justify-between">
        <div class="flex flex-1 justify-between sm:hidden">
            <Link
                v-if="previousPageUrl"
                :href="props.previousPageUrl"
                class="relative inline-flex items-center border border-surface-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 rounded-border hover:bg-surface-50"
                >Previous
            </Link>

            <div
                v-if="!previousPageUrl"
                class="relative inline-flex items-center border border-surface-300 bg-white px-4 py-2 text-sm font-medium text-gray-300 rounded-border hover:bg-surface-50"
            >
                Previous
            </div>

            <Link
                v-if="nextPageUrl"
                :href="nextPageUrl"
                class="relative ml-3 inline-flex items-center border border-surface-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 rounded-border hover:bg-surface-50"
            >
                Next
            </Link>

            <div
                v-if="!nextPageUrl"
                class="relative ml-3 inline-flex items-center border border-surface-300 bg-white px-4 py-2 text-sm font-medium text-gray-300 rounded-border hover:bg-surface-50"
            >
                Next
            </div>
        </div>

        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Showing
                    {{ " " }}
                    <span class="font-medium">{{ from }}</span>
                    {{ " " }}
                    to
                    {{ " " }}
                    <span class="font-medium">{{ to }}</span>
                    {{ " " }}
                    of
                    {{ " " }}
                    <span class="font-medium">{{ of }}</span>
                    {{ " " }}
                    {{ itemCountName }}
                </p>
            </div>

            <div>
                <nav
                    class="isolate inline-flex -space-x-px shadow-sm rounded-border"
                    aria-label="Pagination"
                >
                    <!--Left arrow -->
                    <Link
                        v-if="previousPageUrl"
                        :href="previousPageUrl"
                        class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-surface-50 focus:z-20 focus:outline-offset-0"
                    >
                        <span class="sr-only">Previous</span>

                        <ChevronDown
                            class="h-5 w-5 rotate-90"
                            aria-hidden="true"
                        />
                    </Link>

                    <div
                        v-if="!previousPageUrl"
                        class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-300 ring-1 ring-inset ring-gray-300 hover:bg-surface-50 focus:z-20 focus:outline-offset-0"
                    >
                        <span class="sr-only">Previous</span>

                        <ChevronDown
                            class="h-5 w-5 rotate-90 fill-gray-300"
                            aria-hidden="true"
                        />
                    </div>

                    <template
                        v-for="link in pageLinks"
                        :key="link.url"
                    >
                        <Link
                            v-if="link.active"
                            :href="link.url"
                            aria-current="page"
                            class="relative z-10 inline-flex items-center bg-primary-700 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-600 focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-700"
                        >
                            {{ link.label }}
                        </Link>

                        <!-- inactive -->
                        <Link
                            v-if="!link.active && link.url"
                            :href="link.url"
                            class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-surface-50 focus:z-20 focus:outline-offset-0"
                        >
                            {{ link.label }}
                        </Link>

                        <!-- dots -->
                        <span
                            v-if="link.url === null"
                            class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 focus:outline-offset-0"
                            >...</span
                        >
                    </template>
                    <!--Right arrow-->
                    <Link
                        v-if="nextPageUrl"
                        :href="nextPageUrl"
                        class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-surface-50 focus:z-20 focus:outline-offset-0"
                    >
                        <span class="sr-only">Next</span>

                        <ChevronDown
                            class="h-5 w-5 -rotate-90"
                            aria-hidden="true"
                        />
                    </Link>

                    <div
                        v-if="!nextPageUrl"
                        class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-300 ring-1 ring-inset ring-gray-300 hover:bg-surface-50 focus:z-20 focus:outline-offset-0"
                    >
                        <span class="sr-only">Next</span>

                        <ChevronDown
                            class="h-5 w-5 -rotate-90 fill-gray-300"
                            aria-hidden="true"
                        />
                    </div>
                </nav>
            </div>
        </div>
    </div>
</template>
