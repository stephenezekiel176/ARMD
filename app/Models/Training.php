<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type',
        'scope',
        'status',
        'file_path',
        'file_type',
        'file_size',
        'department_id',
        'facilitator_id',
        'admin_id',
        'published_at',
        'expires_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the department that owns the training.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the facilitator who created the training.
     */
    public function facilitator()
    {
        return $this->belongsTo(User::class, 'facilitator_id');
    }

    /**
     * Get the admin who created the training.
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Scope a query to only include published trainings.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope a query to only include company-wide trainings.
     */
    public function scopeCompany($query)
    {
        return $query->where('scope', 'company');
    }

    /**
     * Scope a query to only include department-specific trainings.
     */
    public function scopeDepartment($query)
    {
        return $query->where('scope', 'department');
    }

    /**
     * Scope a query to only include guidelines.
     */
    public function scopeGuidelines($query)
    {
        return $query->where('type', 'guideline');
    }

    /**
     * Scope a query to only include seminars.
     */
    public function scopeSeminars($query)
    {
        return $query->where('type', 'seminar');
    }

    /**
     * Get the file type icon based on file_type.
     */
    public function getFileTypeIcon()
    {
        return match($this->file_type) {
            'video' => '🎥',
            'audio' => '🎵',
            'image' => '🖼️',
            'ebook', 'pdf' => '📚',
            'podcast' => '🎙️',
            'document' => '📄',
            default => '📁',
        };
    }

    /**
     * Get the file type color class.
     */
    public function getFileTypeColor()
    {
        return match($this->file_type) {
            'video' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
            'audio' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
            'image' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            'ebook', 'pdf' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
            'podcast' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            'document' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
        };
    }

    /**
     * Format file size for display.
     */
    public function getFormattedFileSize()
    {
        if (!$this->file_size) {
            return 'Unknown';
        }

        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
