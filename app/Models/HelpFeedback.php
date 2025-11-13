<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpFeedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'help_article_id',
        'user_id',
        'is_helpful',
        'comment',
        'suggestion',
    ];

    protected $casts = [
        'is_helpful' => 'boolean',
    ];

    // Relationships
    public function article()
    {
        return $this->belongsTo(HelpArticle::class, 'help_article_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeHelpful($query)
    {
        return $query->where('is_helpful', true);
    }

    public function scopeNotHelpful($query)
    {
        return $query->where('is_helpful', false);
    }

    public function scopeWithComments($query)
    {
        return $query->whereNotNull('comment')
                    ->orWhereNotNull('suggestion');
    }
}
