<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class NewsArticle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'summary',
        'content',
        'featured_image',
        'images',
        'videos',
        'hyperlinks',
        'author_id',
        'type',
        'status',
        'category',
        'published_at',
        'is_breaking',
        'views',
    ];

    protected $casts = [
        'images' => 'array',
        'videos' => 'array',
        'hyperlinks' => 'array',
        'published_at' => 'datetime',
        'is_breaking' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
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

    public function scopeBreaking($query)
    {
        return $query->where('is_breaking', true)
                    ->published()
                    ->orderBy('published_at', 'desc');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeLatest($query, $limit = 10)
    {
        return $query->published()
                    ->orderBy('published_at', 'desc')
                    ->limit($limit);
    }

    // Methods
    public function incrementViews()
    {
        $this->increment('views');
    }

    public function markAsBreaking()
    {
        $this->update(['is_breaking' => true]);
    }

    public function unmarkAsBreaking()
    {
        $this->update(['is_breaking' => false]);
    }
}
