<script setup lang="ts">
defineProps({
    sidenavOpen: {
        type: Boolean,
        reqired: true,
        default: false,
    },
});
</script>

<template>
    <button
        id="burger"
        :class="{ active: sidenavOpen }"
    >
        <span
            class="burger-button"
            title="Menu"
        >
            <span class="burger-bar burger-bar--1" />

            <span class="burger-bar burger-bar--2" />

            <span class="burger-bar burger-bar--3" />
        </span>
    </button>
</template>

<style scoped>
/* width */
::-webkit-scrollbar {
    @apply w-2;
}

/* Track */
::-webkit-scrollbar-track {
    @apply bg-transparent;
}

/* Handle */
::-webkit-scrollbar-thumb {
    @apply bg-surface-600;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
    @apply bg-surface-800;
}

.burger-button {
    position: relative;
    height: 30px;
    width: 32px;
    display: block;
    border: 0;
    border-radius: 0;
    background-color: transparent;
    pointer-events: all;
    transition: transform 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
}

.burger-bar {
    @apply bg-white;
    position: absolute;
    top: 50%;
    right: 6px;
    left: 6px;
    height: 2px;
    width: auto;
    margin-top: -1px;
    transition:
        transform 0.5s cubic-bezier(0.165, 0.84, 0.44, 1),
        opacity 0.3s cubic-bezier(0.165, 0.84, 0.44, 1),
        background-color 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
}

.burger-bar--1 {
    -webkit-transform: translateY(-6px);
    transform: translateY(-6px);
}

.burger-bar--2 {
    transform-origin: 100% 50%;
    transform: scaleX(0.8);
}

.burger-button:hover .burger-bar--2 {
    transform: scaleX(1);
}

.no-touchevents .burger-bar--2:hover {
    transform: scaleX(1);
}

.burger-bar--3 {
    transform: translateY(6px);
}

#burger.active .burger-button {
    transform: rotate(-180deg);
}

#burger.active .burger-bar {
    @apply bg-white;
}

#burger.active .burger-bar--1 {
    transform: rotate(45deg);
}

#burger.active .burger-bar--2 {
    opacity: 0;
}

#burger.active .burger-bar--3 {
    transform: rotate(-45deg);
}
</style>
