<?php

namespace App\Models;

use GearboxSolutions\HasOneFile\Traits\HasOneFile;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;
    use HasOneFile;

    protected static function booted(): void
    {
        static::saving(function (Voucher $voucher) {
            $voucher->total = $voucher->lines->sum('total') ?? 0;
        });
    }

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'parse_results' => 'array',
    ];

    protected $appends = [
        'file_url',
    ];

    public function lines()
    {
        return $this->hasMany(VoucherLine::class);
    }

    public function getfileUrlAttribute()
    {
        return $this->fileUrl();
    }

    protected function parseResults(): Attribute
    {
        return Attribute::make(
            // get: fn (string $value) => ucfirst($value),
            set: function (?array $value) {
                if (! $value) {
                    return null;
                }

                return json_encode($value);
            }
        );
    }
}
