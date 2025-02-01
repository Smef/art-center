import type Model from "@/Types/Models/Model";
import Permission from "@/Types/Models/Permission";

export default interface Role extends Model {
    name: string;
    permissions: Permission[];
}
