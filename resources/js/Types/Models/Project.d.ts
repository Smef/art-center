import type Model from "@/Types/Models/Model";
import type Company from "@/Types/Models/Company";
import type ProjectDocument from "@/Types/Models/ProjectDocument";

export default interface Project extends Model {
    company?: Company;
    name: string;
    description?: string;
    company_id?: number;
    start_date?: string;
    address_street?: string;
    address_city?: string;
    address_state?: string;
    address_zip?: string;
    status?: ["Active", "Cancelled", "Completed"];
    project_documents?: ProjectDocument[];
}
