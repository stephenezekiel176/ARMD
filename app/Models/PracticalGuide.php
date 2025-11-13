<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class PracticalGuide extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'steps',
        'category',
        'difficulty_level',
        'estimated_time',
        'prerequisites',
        'tools_required',
        'images',
        'videos',
        'attachments',
        'author_id',
        'status',
        'published_at',
        'views',
        'average_rating',
        'rating_count',
    ];

    protected $casts = [
        'steps' => 'array',
        'prerequisites' => 'array',
        'tools_required' => 'array',
        'images' => 'array',
        'videos' => 'array',
        'attachments' => 'array',
        'published_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($guide) {
            if (empty($guide->slug)) {
                $guide->slug = Str::slug($guide->title);
            }
        });
    }

    // Relationships
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByDifficulty($query, $level)
    {
        return $query->where('difficulty_level', $level);
    }

    public function scopePopular($query, $limit = 10)
    {
        return $query->published()
                    ->orderBy('views', 'desc')
                    ->limit($limit);
    }

    public function scopeTopRated($query, $limit = 10)
    {
        return $query->published()
                    ->where('rating_count', '>', 0)
                    ->orderBy('average_rating', 'desc')
                    ->limit($limit);
    }

    // Methods
    public function incrementViews()
    {
        $this->increment('views');
    }

    public function updateRating($newRating)
    {
        $totalRating = ($this->average_rating * $this->rating_count) + $newRating;
        $newCount = $this->rating_count + 1;
        $newAverage = $totalRating / $newCount;

        $this->update([
            'average_rating' => round($newAverage, 2),
            'rating_count' => $newCount,
        ]);
    }

    public function isPublished()
    {
        return $this->status === 'published' && 
               $this->published_at && 
               $this->published_at->lte(now());
    }
}
