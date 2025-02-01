<?php

namespace App\Models;

use GearboxSolutions\HasOneFile\Traits\HasOneFile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectDocument extends Model
{
    use HasFactory;
    use HasOneFile;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $appends = [
        'file_url',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
