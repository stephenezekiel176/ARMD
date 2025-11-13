<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rating extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'rater_id',
        'rated_id',
        'rating_type',
        'overall_rating',
        'communication_rating',
        'knowledge_rating',
        'professionalism_rating',
        'responsiveness_rating',
        'comment',
        'strengths',
        'improvements',
        'course_id',
        'assessment_id',
        'is_anonymous',
        'is_approved',
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'is_approved' => 'boolean',
    ];

    // Relationships
    public function rater()
    {
        return $this->belongsTo(User::class, 'rater_id');
    }

    public function rated()
    {
        return $this->belongsTo(User::class, 'rated_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopePersonnelToFacilitator($query)
    {
        return $query->where('rating_type', 'personnel_to_facilitator');
    }

    public function scopeFacilitatorToPersonnel($query)
    {
        return $query->where('rating_type', 'facilitator_to_personnel');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('rated_id', $userId);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('rater_id', $userId);
    }

    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    // Methods
    public function getAverageRating()
    {
        $ratings = array_filter([
            $this->overall_rating,
            $this->communication_rating,
            $this->knowledge_rating,
            $this->professionalism_rating,
            $this->responsiveness_rating,
        ]);

        return !empty($ratings) ? round(array_sum($ratings) / count($ratings), 2) : 0;
    }

    public function approve()
    {
        $this->update(['is_approved' => true]);
    }

    public function reject()
    {
        $this->update(['is_approved' => false]);
    }

    // Static methods for calculating user averages
    public static function getAverageForUser($userId, $ratingType = null)
    {
        $query = static::approved()->forUser($userId);
        
        if ($ratingType) {
            $query->where('rating_type', $ratingType);
        }

        return $query->avg('overall_rating') ?? 0;
    }

    public static function getUserRatingStats($userId, $ratingType = null)
    {
        $query = static::approved()->forUser($userId);
        
        if ($ratingType) {
            $query->where('rating_type', $ratingType);
        }

        return [
            'total_ratings' => $query->count(),
            'average_overall' => round($query->avg('overall_rating') ?? 0, 2),
            'average_communication' => round($query->avg('communication_rating') ?? 0, 2),
            'average_knowledge' => round($query->avg('knowledge_rating') ?? 0, 2),
            'average_professionalism' => round($query->avg('professionalism_rating') ?? 0, 2),
            'average_responsiveness' => round($query->avg('responsiveness_rating') ?? 0, 2),
            'five_star' => $query->where('overall_rating', 5)->count(),
            'four_star' => $query->where('overall_rating', 4)->count(),
            'three_star' => $query->where('overall_rating', 3)->count(),
            'two_star' => $query->where('overall_rating', 2)->count(),
            'one_star' => $query->where('overall_rating', 1)->count(),
        ];
    }
}
