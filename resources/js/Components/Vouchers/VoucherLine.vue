<script setup lang="ts">
import type VoucherLine from "@/Types/Models/VoucherLine";

const line = defineModel<VoucherLine>();
</script>

<template>
    <div class="flex items-center">
        <input v-model="line.description" />

        <input v-model="line.quantity" />

        <input v-model="line.each" />
    </div>
</template>
