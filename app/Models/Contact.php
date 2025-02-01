<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contact extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $appends = ['name_full'];

    public function getNameFullAttribute()
    {
        return "$this->name_first $this->name_last";
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
