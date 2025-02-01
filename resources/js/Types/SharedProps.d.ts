import type User from "@/Types/Models/User";
import type { Page, PageProps } from "@inertiajs/core";

interface SharedProps extends PageProps {
    auth: {
        user: User;
    };
}

export default SharedProps;

declare module "@inertiajs/vue3" {
    export function usePage(): Page<SharedProps>;
}
