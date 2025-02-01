<script setup>
import { Link } from "@inertiajs/vue3";
import BrandIcon from "@/Components/Icons/BrandIcon.vue";
</script>

<template>
    <Link :href="'/'">
        <BrandIcon class="w-24" />
    </Link>
</template>
