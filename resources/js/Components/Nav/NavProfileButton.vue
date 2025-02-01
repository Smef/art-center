<script setup lang="ts">
import LogOut from "@/Components/Icons/LogOut.vue";
import UserCircleSolid from "@/Components/Icons/UserCircleSolid.vue";
import NavFlyout from "@/Components/Nav/NavFlyout.vue";
import NavFlyoutItem from "@/Components/Nav/NavFlyoutItem.vue";
import { router, usePage } from "@inertiajs/vue3";
import { vOnClickOutside } from "@vueuse/components";
import { OnClickOutsideOptions, useElementBounding } from "@vueuse/core";
import { computed, ref } from "vue";

defineProps({
    expanded: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["click-link"]);

const menuButton = ref<HTMLButtonElement>();
const showingFlyout = ref(false);

const page = usePage();
const { width: buttonWidth } = useElementBounding(menuButton);

const user = computed(() => page.props.auth.user);

const logout = () => {
    router.post(route("logout"));
};

const flyoutStyle = computed(() => ({
    bottom: `0`,
    left: `${buttonWidth.value}px`,
}));

const onClickOutsideHandler: [(evt: Event) => void, OnClickOutsideOptions] = [
    () => {
        if (showingFlyout.value) {
            showingFlyout.value = false;
        }
    },
    { ignore: [menuButton] },
];

const handleClickFlyoutItem = () => {
    showingFlyout.value = false;
    emit("click-link");
};
</script>

<template>
    <div class="flex flex-none items-center bg-surface-800 py-8">
        <button
            class="relative block w-full font-light text-white"
            @click="showingFlyout = !showingFlyout"
            ref="menuButton"
        >
            <span class="flex items-center justify-start gap-x-3 overflow-clip px-4 text-left transition-all">
                <img
                    class="h-12 w-12 rounded-full object-cover"
                    :src="user.profile_photo_url"
                    :alt="user.name"
                />

                <span
                    class="transition-all"
                    :class="{ '': expanded, 'opacity-0': !expanded }"
                >
                    <span class="">{{ user.name }}</span>
                </span>
            </span>
        </button>

        <NavFlyout
            v-if="showingFlyout"
            class="absolute flex flex-col"
            :style="flyoutStyle"
            v-on-click-outside="onClickOutsideHandler"
        >
            <NavFlyoutItem
                :href="route('profile.show')"
                :icon="UserCircleSolid"
                label="Profile"
                @click="handleClickFlyoutItem"
            />

            <form @submit.prevent="logout">
                <button class="flex w-full items-center gap-x-3 px-6 py-5 hover:bg-surface-700">
                    <LogOut class="h-6 w-6 fill-white" />

                    <span class="text-nowrap font-light text-white">Log Out</span>
                </button>
            </form>
        </NavFlyout>
    </div>
</template>
