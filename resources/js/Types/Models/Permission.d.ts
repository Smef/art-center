import type Model from "@/Types/Models/Model";

export default interface Permission extends Model {
    name: string;
    description: string;
}
