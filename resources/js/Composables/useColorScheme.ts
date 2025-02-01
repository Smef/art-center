import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

export default function useColorScheme() {
    const page = usePage();

    const userColorScheme = computed(() => page.props.auth.user?.color_scheme ?? "auto");

    const isDarkMode = computed(() => {
        if (userColorScheme.value === "dark") return true;
        if (userColorScheme.value === "light") return false;
        return window.matchMedia("(prefers-color-scheme: dark)").matches;
    });

    // load the user's color scheme
    function setColorModeToUserPreference() {
        if (isDarkMode.value) {
            document.documentElement.classList.add("dark");
        } else {
            document.documentElement.classList.remove("dark");
        }
    }

    function toggleDarkMode() {
        document.documentElement.classList.toggle("dark");
    }

    return {
        setColorModeToUserPreference,
        toggleDarkMode,
        isDarkMode,
    };
}
