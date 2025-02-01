import type User from "@/Types/Models/User";
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const page = usePage();

export default function useGuard() {
    const user = computed((): User => page.props.auth.user);

    const can = (...permissions: string[]) => {
        const neededPermissions = permissions.flat(Infinity);

        // make sure that every needed permission is in the user permissions
        const userHasAllNeededPermissions = neededPermissions.every((eachPermission) =>
            user.value.permissions.includes(eachPermission),
        );

        return userHasAllNeededPermissions;
    };

    return {
        user,
        can,
    };
}
