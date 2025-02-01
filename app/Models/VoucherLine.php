<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherLine extends Model
{
    use HasFactory;

    protected $casts = [
        'date' => 'date:Y-m-d',
        'parse_results' => 'array',
        'quantity' => 'float',
        'total' => 'float',
        'each' => 'float',
    ];

    protected static function booted(): void
    {
        static::saving(function (VoucherLine $voucherLine) {
            $voucherLine->total = $voucherLine->quantity * $voucherLine->each;
        });

        static::saved(function (VoucherLine $voucherLine) {
            // touch the parent to make sure the total is updated any time a line item is changed

            $voucher = $voucherLine->voucher;
            $voucher->total = $voucher->lines->sum('total');
            $voucher->save();
        }
        );
    }

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }
}
