<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class MagazineIssue extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'issue_number',
        'description',
        'content',
        'cover_image',
        'sections',
        'images',
        'pdf_file',
        'editor_id',
        'status',
        'type',
        'published_at',
        'issue_date',
        'downloads',
    ];

    protected $casts = [
        'sections' => 'array',
        'images' => 'array',
        'published_at' => 'datetime',
        'issue_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($issue) {
            if (empty($issue->slug)) {
                $issue->slug = Str::slug($issue->title);
            }
        });
    }

    // Relationships
    public function editor()
    {
        return $this->belongsTo(User::class, 'editor_id');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    public function scopeLatest($query)
    {
        return $query->published()
                    ->orderBy('issue_date', 'desc');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Methods
    public function incrementDownloads()
    {
        $this->increment('downloads');
    }

    public function isPublished()
    {
        return $this->status === 'published' && 
               $this->published_at && 
               $this->published_at->lte(now());
    }
}
