import type Model from "@/Types/Models/Model";
import Role from "@/Types/Models/Role";

export default interface User extends Model {
    archived_at: string | null;
    color_scheme: "light" | "dark" | "auto";
    email: string;
    fax: string;
    group_ids: number[];
    is_admin: boolean;
    name_first: string;
    name_last: string;
    name_full: string;
    permissions: string[];
    profile_photo_url: string;
    role: string;
    roles: Role[];
}
