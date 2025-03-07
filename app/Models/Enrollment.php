<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
