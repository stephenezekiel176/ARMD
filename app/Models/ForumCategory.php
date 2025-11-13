<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ForumCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    // Relationships
    public function threads()
    {
        return $this->hasMany(ForumThread::class, 'category_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->orderBy('order', 'asc');
    }

    // Methods
    public function getThreadCount()
    {
        return $this->threads()->count();
    }

    public function getPostCount()
    {
        return ForumPost::whereHas('thread', function($query) {
            $query->where('category_id', $this->id);
        })->count();
    }

    public function getLatestThread()
    {
        return $this->threads()->latest('created_at')->first();
    }
}
