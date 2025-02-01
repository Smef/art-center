import type Company from "@/Types/Models/Company";
import type Model from "@/Types/Models/Model";
import type VoucherLine from "@/Types/Models/VoucherLine";

type Voucher = Model & {
    lines: VoucherLine[];
    company?: Company;
    po_number?: string;
    number?: string;
    date: string;
    total: number;
    company_id?: number;
    file_url?: string;
    file_name?: string;
};

export default Voucher;
