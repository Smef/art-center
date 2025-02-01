<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    public const PROJECT_STATUSES = [
        ProjectStatus::Active,
        ProjectStatus::Completed,
        ProjectStatus::Cancelled,
    ];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'start_date' => 'datetime:Y-m-d',
        'status' => ProjectStatus::class,
    ];

    protected $attributes = [
        'status' => ProjectStatus::Active->value,
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function projectDocuments(): HasMany
    {
        return $this->hasMany(ProjectDocument::class);
    }
}
