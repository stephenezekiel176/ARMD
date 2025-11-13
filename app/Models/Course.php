<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'file_path',
        'description',
        'department_id',
        'facilitator_id',
        'duration',
        'is_previewable',
        'scope',
    ];

    protected $casts = [
        'is_previewable' => 'boolean',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function facilitator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'facilitator_id');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class);
    }

    /**
     * Scope a query to only include department-specific courses.
     */
    public function scopeDepartment($query)
    {
        return $query->where('scope', 'department');
    }

    /**
     * Scope a query to only include company-wide courses.
     */
    public function scopeCompany($query)
    {
        return $query->where('scope', 'company');
    }

    /**
     * Check if course is company-wide.
     */
    public function isCompanyWide()
    {
        return $this->scope === 'company';
    }

    /**
     * Check if course is department-specific.
     */
    public function isDepartmentSpecific()
    {
        return $this->scope === 'department';
    }
}