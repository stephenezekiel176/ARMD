<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    // Note: Laravel Sanctum may not be installed in this environment. Remove the
    // HasApiTokens trait reference to avoid a fatal "Trait not found" error
    // while keeping the model functional. If you later add Sanctum, re-add
    // the import and trait (`Laravel\Sanctum\HasApiTokens`).
    use HasFactory, Notifiable;

    protected $fillable = [
        'fullname',
        'email',
        'department_id',
        'position',
        'role',
        'profile_picture',
        'avatar',
        'points',
        'badges',
        'password',
        'facilitator_code',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'badges' => 'array',
        'password' => 'hashed',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class)->select(['id', 'name']);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'facilitator_id')
            ->select(['id', 'title', 'type', 'facilitator_id', 'department_id', 'is_previewable']);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class)
            ->select(['id', 'user_id', 'course_id', 'status', 'progress']);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class)
            ->select(['id', 'user_id', 'assessment_id', 'score', 'created_at']);
    }

    // Cached department relationship
    public function getCachedDepartment()
    {
        return Cache::remember("user.{$this->id}.department", 3600, function () {
            return $this->department;
        });
    }

    // Cached enrollment count
    public function getCachedEnrollmentCount()
    {
        return Cache::remember("user.{$this->id}.enrollment_count", 1800, function () {
            return $this->enrollments()->count();
        });
    }

    public function personnel(): HasMany
    {
        return $this->hasMany(Personnel::class, 'facilitator_id');
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class, 'facilitator_id');
    }

    public function forumThreads(): HasMany
    {
        return $this->hasMany(\App\Models\ForumThread::class);
    }

    public function forumPosts(): HasMany
    {
        return $this->hasMany(\App\Models\ForumPost::class);
    }

    public function awardPoints(int $points): void
    {
        $this->increment('points', $points);
        $this->checkBadges();
    }

    public function checkBadges(): void
    {
        // Cache badges for 1 hour to reduce database queries
        $badges = Cache::remember('all_badges', 3600, function () {
            return Badge::select(['id', 'name', 'criteria'])->get();
        });
        
        $earnedBadges = $this->badges ?? [];

        foreach ($badges as $badge) {
            $criteria = json_decode($badge->criteria, true);

            if (!in_array($badge->name, $earnedBadges) && $this->meetsRequirements($criteria)) {
                $earnedBadges[] = $badge->name;
            }
        }

        if ($earnedBadges !== $this->badges) {
            $this->badges = $earnedBadges;
            $this->save();
            
            // Clear user cache when badges change
            Cache::forget("user.{$this->id}.badges");
        }
    }

    private function meetsRequirements(array $criteria): bool
    {
        if (isset($criteria['points_required']) && $this->points < $criteria['points_required']) {
            return false;
        }

        if (isset($criteria['courses_completed'])) {
            $completedCourses = $this->enrollments()
                ->where('status', 'completed')
                ->count();

            if ($completedCourses < $criteria['courses_completed']) {
                return false;
            }
        }

        if (isset($criteria['min_score'])) {
            $averageScore = $this->submissions()
                ->whereNotNull('score')
                ->avg('score');

            if ($averageScore < $criteria['min_score']) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the user's avatar URL
     */
    public function getAvatarUrl(): string
    {
        // If user has a custom profile picture, use it
        if ($this->profile_picture) {
            return Storage::url($this->profile_picture);
        }

        // Otherwise use the unique avatar system
        return \App\Services\AvatarService::getAvatarUrl($this->avatar);
    }

    /**
     * Get the user's avatar display name
     */
    public function getAvatarDisplayName(): string
    {
        return \App\Services\AvatarService::getAvatarDisplayName($this->avatar ?? 'default');
    }

    /**
     * Assign a unique avatar to the user
     */
    public function assignUniqueAvatar(): string
    {
        return \App\Services\AvatarService::assignUniqueAvatar($this);
    }

    /**
     * Change user's avatar to a different unique one
     */
    public function changeAvatar(): string
    {
        return \App\Services\AvatarService::changeAvatar($this);
    }

    /**
     * Boot method to automatically assign avatar on user creation
     */
    protected static function boot()
    {
        parent::boot();

        // Assign unique avatar when user is created
        static::created(function ($user) {
            if (!$user->avatar) {
                $user->assignUniqueAvatar();
            }
        });
    }
}
