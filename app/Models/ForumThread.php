<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ForumThread extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'category_id',
        'user_id',
        'is_pinned',
        'is_locked',
        'is_solved',
        'solved_post_id',
        'tags',
        'attachments',
        'views',
        'reply_count',
        'last_activity_at',
        'last_post_user_id',
    ];

    protected $casts = [
        'tags' => 'array',
        'attachments' => 'array',
        'is_pinned' => 'boolean',
        'is_locked' => 'boolean',
        'is_solved' => 'boolean',
        'last_activity_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($thread) {
            if (empty($thread->slug)) {
                $thread->slug = Str::slug($thread->title);
            }
            $thread->last_activity_at = now();
        });
    }

    // Relationships
    public function category()
    {
        return $this->belongsTo(ForumCategory::class, 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function posts()
    {
        return $this->hasMany(ForumPost::class, 'thread_id');
    }

    public function solvedPost()
    {
        return $this->belongsTo(ForumPost::class, 'solved_post_id');
    }

    public function lastPostUser()
    {
        return $this->belongsTo(User::class, 'last_post_user_id');
    }

    // Scopes
    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_locked', false)
                    ->orderBy('is_pinned', 'desc')
                    ->orderBy('last_activity_at', 'desc');
    }

    public function scopeSolved($query)
    {
        return $query->where('is_solved', true);
    }

    public function scopeUnsolved($query)
    {
        return $query->where('is_solved', false);
    }

    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    public function scopePopular($query, $limit = 10)
    {
        return $query->orderBy('views', 'desc')->limit($limit);
    }

    // Methods
    public function incrementViews()
    {
        $this->increment('views');
    }

    public function updateActivity($userId = null)
    {
        $this->update([
            'last_activity_at' => now(),
            'last_post_user_id' => $userId ?? $this->user_id,
        ]);
    }

    public function markAsSolved($postId)
    {
        $this->update([
            'is_solved' => true,
            'solved_post_id' => $postId,
        ]);
    }

    public function lock()
    {
        $this->update(['is_locked' => true]);
    }

    public function unlock()
    {
        $this->update(['is_locked' => false]);
    }

    public function pin()
    {
        $this->update(['is_pinned' => true]);
    }

    public function unpin()
    {
        $this->update(['is_pinned' => false]);
    }
}
