<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class HelpArticle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'summary',
        'content',
        'category',
        'tags',
        'related_articles',
        'attachments',
        'video_tutorials',
        'article_type',
        'author_id',
        'status',
        'published_at',
        'views',
        'helpful_count',
        'not_helpful_count',
    ];

    protected $casts = [
        'tags' => 'array',
        'related_articles' => 'array',
        'attachments' => 'array',
        'video_tutorials' => 'array',
        'published_at' => 'datetime',
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

    public function feedbacks()
    {
        return $this->hasMany(HelpFeedback::class);
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

    public function scopeByType($query, $type)
    {
        return $query->where('article_type', $type);
    }

    public function scopePopular($query, $limit = 10)
    {
        return $query->published()
                    ->orderBy('views', 'desc')
                    ->limit($limit);
    }

    public function scopeMostHelpful($query, $limit = 10)
    {
        return $query->published()
                    ->orderBy('helpful_count', 'desc')
                    ->limit($limit);
    }

    // Methods
    public function incrementViews()
    {
        $this->increment('views');
    }

    public function markAsHelpful()
    {
        $this->increment('helpful_count');
    }

    public function markAsNotHelpful()
    {
        $this->increment('not_helpful_count');
    }

    public function getHelpfulPercentage()
    {
        $total = $this->helpful_count + $this->not_helpful_count;
        return $total > 0 ? round(($this->helpful_count / $total) * 100, 1) : 0;
    }

    public function isPublished()
    {
        return $this->status === 'published' && 
               $this->published_at && 
               $this->published_at->lte(now());
    }
}
