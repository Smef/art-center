import type Company from "@/Types/Models/Company";
import type Model from "@/Types/Models/Model";

export default interface Contact extends Model {
    company?: Company;
    company_id?: number;
    email?: string;
    name_first: string;
    name_full: string;
    name_last: string;
    phone?: string;
    role?: string;
}
