<script setup lang="ts">
import CompanyBuilding from "@/Components/Icons/CompanyBuilding.vue";
import ContactIcon from "@/Components/Icons/ContactIcon.vue";
import GearSolid from "@/Components/Icons/GearSolid.vue";
import UsersGearSolid from "@/Components/Icons/UsersGearSolid.vue";
import UserSolid from "@/Components/Icons/UserSolid.vue";
import VoucherIcon from "@/Components/Icons/VoucherIcon.vue";
import NavFlyoutButton from "@/Components/Nav/NavFlyoutButton.vue";
import NavLink from "@/Components/Nav/NavLink.vue";
import useGuard from "@/Composables/useGuard";
import type NavLinkType from "@/Types/NavLink";
import { computed } from "vue";

defineProps({
    expanded: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["click-link"]);

const guard = useGuard();

const navLinks = computed((): NavLinkType[] => [
    {
        label: "Companies",
        icon: CompanyBuilding,
        visible: true,
        active: route().current("companies.*"),
        href: route("companies.index"),
    },
    {
        label: "Contacts",
        icon: ContactIcon,
        visible: true,
        active: route().current("contacts.*"),
        href: route("contacts.index"),
    },
    {
        label: "Projects",
        icon: VoucherIcon,
        visible: true,
        active: route().current("projects.*"),
        href: route("projects.index"),
    },
    {
        label: "Settings",
        icon: GearSolid,
        visible: guard.can("access admin"),
        active: route().current("settings.*"),
        children: [
            {
                label: "Users",
                icon: UserSolid,
                visible: true,
                href: route("settings.users.index"),
            },
            {
                label: "Roles",
                icon: UsersGearSolid,
                visible: true,
                href: route("settings.roles.index"),
            },
        ],
    },
    {
        label: "Vouchers",
        icon: VoucherIcon,
        visible: true,
        active: route().current("vouchers.*"),
        href: route("vouchers.index"),
    },
]);
</script>

<template>
    <div>
        <div
            v-for="link in navLinks"
            :key="link.label"
        >
            <NavLink
                v-if="'href' in link && link.visible"
                :href="link.href"
                :active="link.active ?? false"
                :label="link.label"
                :expanded="expanded"
                :icon="link.icon"
                @click="emit('click-link')"
            />

            <NavFlyoutButton
                v-if="'children' in link && link.visible"
                :label="link.label"
                :expanded="expanded"
                :icon="link.icon"
                :active="link.active"
                :children="link.children"
                @click-link="emit('click-link')"
            />
        </div>
    </div>
</template>
