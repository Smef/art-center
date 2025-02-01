import type Contact from "@/Types/Models/Contact";
import type Model from "@/Types/Models/Model";

type Company = Model & {
    name: string;
    contacts?: Contact[];
    website?: string;
    phone?: string;
    address_street?: string;
    address_city?: string;
    address_state?: string;
    address_zip?: string;
    logo_url?: string;
};

export default Company;
