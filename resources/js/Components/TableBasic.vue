<script setup lang="ts"></script>

<template>
    <div class="overflow-x-auto">
        <div class="inline-block min-w-full align-middle">
            <table class="min-h-full min-w-full table-auto">
                <thead class="border-b border-surface-950 py-4 text-sm font-bold">
                    <tr>
                        <slot name="thead-tr" />
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-300">
                    <slot />
                </tbody>
            </table>
        </div>
    </div>
</template>
