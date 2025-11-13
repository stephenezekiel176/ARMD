<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use App\Models\User;
use App\Models\Course;
use App\Models\Department;

class CacheService
{
    private const DEFAULT_TTL = 3600; // 1 hour
    private const SHORT_TTL = 900; // 15 minutes
    private const LONG_TTL = 86400; // 24 hours

    /**
     * Cache user data with optimized structure
     */
    public function cacheUserData(User $user): void
    {
        $userData = [
            'id' => $user->id,
            'fullname' => $user->fullname,
            'email' => $user->email,
            'role' => $user->role,
            'department_id' => $user->department_id,
            'points' => $user->points,
            'badges' => $user->badges,
        ];

        Cache::put("user_data_{$user->id}", $userData, self::DEFAULT_TTL);
        
        // Cache user permissions
        $this->cacheUserPermissions($user);
    }

    /**
     * Get cached user data
     */
    public function getCachedUserData(int $userId): ?array
    {
        return Cache::get("user_data_{$userId}");
    }

    /**
     * Cache user permissions for quick access control
     */
    public function cacheUserPermissions(User $user): void
    {
        $permissions = [
            'can_create_courses' => $user->role === 'facilitator',
            'can_manage_users' => $user->role === 'admin',
            'department_courses' => $this->getUserDepartmentCourses($user),
        ];

        Cache::put("user_permissions_{$user->id}", $permissions, self::DEFAULT_TTL);
    }

    /**
     * Cache course data with relationships
     */
    public function cacheCourseData(Course $course): void
    {
        $courseData = [
            'id' => $course->id,
            'title' => $course->title,
            'type' => $course->type,
            'description' => $course->description,
            'department_id' => $course->department_id,
            'facilitator_id' => $course->facilitator_id,
            'is_previewable' => $course->is_previewable,
            'enrollment_count' => $course->enrollments()->count(),
        ];

        Cache::put("course_data_{$course->id}", $courseData, self::DEFAULT_TTL);
    }

    /**
     * Cache department courses for quick access
     */
    public function cacheDepartmentCourses(int $departmentId): void
    {
        $courses = Course::where('department_id', $departmentId)
            ->select(['id', 'title', 'type', 'is_previewable', 'facilitator_id'])
            ->get()
            ->toArray();

        Cache::put("department_courses_{$departmentId}", $courses, self::DEFAULT_TTL);
    }

    /**
     * Get cached department courses
     */
    public function getCachedDepartmentCourses(int $departmentId): array
    {
        return Cache::remember("department_courses_{$departmentId}", self::DEFAULT_TTL, function() use ($departmentId) {
            return Course::where('department_id', $departmentId)
                ->select(['id', 'title', 'type', 'is_previewable', 'facilitator_id'])
                ->get()
                ->toArray();
        });
    }

    /**
     * Cache popular courses for homepage
     */
    public function cachePopularCourses(): void
    {
        $popularCourses = Course::withCount('enrollments')
            ->orderBy('enrollments_count', 'desc')
            ->limit(10)
            ->select(['id', 'title', 'type', 'description', 'is_previewable'])
            ->get()
            ->toArray();

        Cache::put('popular_courses', $popularCourses, self::LONG_TTL);
    }

    /**
     * Get cached popular courses
     */
    public function getCachedPopularCourses(): array
    {
        return Cache::remember('popular_courses', self::LONG_TTL, function() {
            return Course::withCount('enrollments')
                ->orderBy('enrollments_count', 'desc')
                ->limit(10)
                ->select(['id', 'title', 'type', 'description', 'is_previewable'])
                ->get()
                ->toArray();
        });
    }

    /**
     * Cache user enrollment status for quick checks
     */
    public function cacheUserEnrollments(int $userId): void
    {
        $enrollments = \App\Models\Enrollment::where('user_id', $userId)
            ->pluck('course_id')
            ->toArray();

        Cache::put("user_enrollments_{$userId}", $enrollments, self::SHORT_TTL);
    }

    /**
     * Check if user is enrolled in course (cached)
     */
    public function isUserEnrolled(int $userId, int $courseId): bool
    {
        $enrollments = Cache::remember("user_enrollments_{$userId}", self::SHORT_TTL, function() use ($userId) {
            return \App\Models\Enrollment::where('user_id', $userId)
                ->pluck('course_id')
                ->toArray();
        });

        return in_array($courseId, $enrollments);
    }

    /**
     * Invalidate user-related caches
     */
    public function invalidateUserCache(int $userId): void
    {
        $keys = [
            "user_data_{$userId}",
            "user_permissions_{$userId}",
            "user_enrollments_{$userId}",
            "user.{$userId}.department",
            "user.{$userId}.enrollment_count",
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }

    /**
     * Warm up critical caches for better performance
     */
    public function warmUpCaches(): void
    {
        // Cache popular courses
        $this->cachePopularCourses();

        // Cache all departments
        $departments = Department::select(['id', 'name'])->get();
        foreach ($departments as $department) {
            $this->cacheDepartmentCourses($department->id);
        }

        // Cache system statistics
        $this->cacheSystemStats();
    }

    /**
     * Cache system statistics
     */
    private function cacheSystemStats(): void
    {
        $stats = [
            'total_users' => User::count(),
            'total_courses' => Course::count(),
            'total_enrollments' => \App\Models\Enrollment::count(),
            'active_users_today' => User::whereDate('updated_at', today())->count(),
        ];

        Cache::put('system_stats', $stats, self::LONG_TTL);
    }

    /**
     * Get user department courses (helper method)
     */
    private function getUserDepartmentCourses(User $user): array
    {
        return Course::where('department_id', $user->department_id)
            ->pluck('id')
            ->toArray();
    }

    /**
     * Batch cache multiple items for efficiency
     */
    public function batchCache(array $items, int $ttl = self::DEFAULT_TTL): void
    {
        if (empty($items)) {
            return;
        }

        // Use Redis pipeline for batch operations
        Redis::pipeline(function ($pipe) use ($items, $ttl) {
            foreach ($items as $key => $value) {
                $pipe->setex($key, $ttl, serialize($value));
            }
        });
    }
}
