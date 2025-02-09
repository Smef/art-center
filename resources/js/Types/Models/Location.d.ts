import type Model from "@/Types/Models/Model";

type Location = Model & {
    name: string;
    website?: string;
    phone?: string;
    address_street?: string;
    address_city?: string;
    address_state?: string;
    address_zip?: string;
};

export default Location;
