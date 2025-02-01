import type Model from "@/Types/Models/Model";
import type Voucher from "@/Types/Models/Voucher";

type VoucherLine = Model & {
    voucher?: Voucher;
    description: string;
    code?: string;
    quantity: number;
    each: number;
    total: number;
};

export default VoucherLine;
