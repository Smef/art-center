<script setup lang="ts">
import BrandIcon from "@/Components/Icons/BrandIcon.vue";

import BrandLogoFull from "@/Components/Icons/BrandLogoFull.vue";
import ExpandIcon from "@/Components/Nav/ExpandIcon.vue";
import Divider from "primevue/divider";

import NavLinkList from "@/Components/Nav/NavLinkList.vue";
import NavProfileButton from "@/Components/Nav/NavProfileButton.vue";
import ColorSchemeToggle from "@/Components/ColorSchemeToggle.vue";
import { Link } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";

const props = defineProps({
    expanded: {
        type: Boolean,
        default: false,
    },
});

const appEnv = import.meta.env.VITE_APP_ENV;

const visible = defineModel("visible", {
    type: Boolean,
    default: true,
});

// prevent scrolling when sidenav is visible on mobile
watch(visible, () => {
    if (visible.value) {
        document.body.style.overflow = "hidden";
    } else {
        document.body.style.overflow = "";
    }
});

const emit = defineEmits(["change-expanded"]);

function hidesidenav() {
    visible.value = false;
}

const sidenavContainer = ref<HTMLDivElement>();

const navClasses = computed(() => {
    const classes = [props.expanded ? "w-48" : "w-20"];
    if (!visible.value) {
        classes.push("-translate-x-full lg:translate-x-0");
    }

    return classes.join(" ");
});
</script>

<template>
    <nav
        class="fixed h-dvh select-none"
        :class="{ 'pointer-events-none lg:pointer-events-auto': !visible }"
    >
        <Transition>
            <div
                class="fixed bottom-0 left-0 right-0 top-0 grow bg-black opacity-50 lg:hidden"
                v-if="visible"
                @click="hidesidenav"
            />
        </Transition>

        <div class="fixed top-0 flex h-full lg:static">
            <!-- main side nav -->
            <div
                class="flex h-full flex-col bg-surface-900 transition-all"
                :class="navClasses"
            >
                <!-- nav top -->
                <div class="flex min-h-0 flex-1 flex-col justify-between gap-y-8">
                    <!--logo-->
                    <div class="flex flex-none items-center justify-start overflow-x-clip bg-black px-4 py-3">
                        <Link
                            :href="route('dashboard')"
                            class="w-full overflow-x-clip"
                        >
                            <!-- Option 1 - slide and cut off the logo -->
                            <!--<BrandLogoFull class="h-10 w-[116px]" />-->

                            <!-- Option 2 - swap the logo between small and large versions -->
                            <div class="relative flex h-10 w-full justify-center">
                                <Transition>
                                    <BrandLogoFull
                                        class="absolute h-10 w-[116px]"
                                        v-if="expanded"
                                    />

                                    <BrandIcon
                                        class="absolute h-10 w-10"
                                        v-else
                                    />
                                </Transition>
                            </div>
                        </Link>
                    </div>

                    <!-- Navigation Links -->
                    <div
                        class="basis-90 flex-1 overflow-y-auto"
                        ref="sidenavContainer"
                    >
                        <NavLinkList
                            :expanded="expanded"
                            @click-link="hidesidenav()"
                        />

                        <div class="px-6">
                            <Divider class="" />
                        </div>

                        <div class="flex justify-end px-4">
                            <button
                                @click="emit('change-expanded', !expanded)"
                                class="p-3 hover:bg-surface-800"
                            >
                                <ExpandIcon
                                    class="h-6 w-6 fill-white transition-all"
                                    :class="{ '-rotate-90': !expanded, 'rotate-90': expanded }"
                                />
                            </button>
                        </div>
                    </div>

                    <ColorSchemeToggle
                        v-if="appEnv === 'local'"
                        :expanded="expanded"
                    />
                </div>

                <!-- User profile button -->
                <NavProfileButton
                    @click-link="hidesidenav()"
                    :expanded="expanded"
                />
            </div>
        </div>
    </nav>
</template>

<style scoped>
.v-enter-active,
.v-leave-active {
    transition: opacity 350ms ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}
</style>
