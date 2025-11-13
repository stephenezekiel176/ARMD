<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ForumPost extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'thread_id',
        'user_id',
        'parent_id',
        'content',
        'attachments',
        'images',
        'likes',
        'is_solution',
        'is_moderated',
        'moderation_note',
    ];

    protected $casts = [
        'attachments' => 'array',
        'images' => 'array',
        'is_solution' => 'boolean',
        'is_moderated' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($post) {
            // Update thread reply count and activity
            $post->thread->increment('reply_count');
            $post->thread->updateActivity($post->user_id);
        });

        static::deleted(function ($post) {
            // Decrement thread reply count
            $post->thread->decrement('reply_count');
        });
    }

    // Relationships
    public function thread()
    {
        return $this->belongsTo(ForumThread::class, 'thread_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(ForumPost::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(ForumPost::class, 'parent_id');
    }

    public function reactions()
    {
        return $this->hasMany(ForumReaction::class, 'post_id');
    }

    // Scopes
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeSolutions($query)
    {
        return $query->where('is_solution', true);
    }

    // Methods
    public function markAsSolution()
    {
        $this->update(['is_solution' => true]);
        $this->thread->markAsSolved($this->id);
    }

    public function addReaction($userId, $type = 'like')
    {
        return $this->reactions()->firstOrCreate([
            'user_id' => $userId,
            'type' => $type,
        ]);
    }

    public function removeReaction($userId, $type = 'like')
    {
        return $this->reactions()
                    ->where('user_id', $userId)
                    ->where('type', $type)
                    ->delete();
    }

    public function getLikesCount()
    {
        return $this->reactions()->where('type', 'like')->count();
    }

    public function moderate($note = null)
    {
        $this->update([
            'is_moderated' => true,
            'moderation_note' => $note,
        ]);
    }
}
