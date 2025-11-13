<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Submission;

class UserAnalyticsService
{
    /**
     * Get user activity analytics
     */
    public function getUserActivityAnalytics(): array
    {
        $cacheKey = 'user_activity_analytics_' . now()->format('Y-m-d');
        
        return Cache::remember($cacheKey, now()->addHours(6), function () {
            return [
                'total_users' => User::count(),
                'active_users_today' => $this->getActiveUsersToday(),
                'active_users_this_week' => $this->getActiveUsersThisWeek(),
                'active_users_this_month' => $this->getActiveUsersThisMonth(),
                'new_users_this_month' => $this->getNewUsersThisMonth(),
                'user_roles' => $this->getUserRoleDistribution(),
                'department_distribution' => $this->getDepartmentDistribution(),
                'login_trends' => $this->getLoginTrends(),
            ];
        });
    }
    
    /**
     * Get course engagement analytics
     */
    public function getCourseEngagementAnalytics(): array
    {
        $cacheKey = 'course_engagement_analytics_' . now()->format('Y-m-d');
        
        return Cache::remember($cacheKey, now()->addHours(6), function () {
            return [
                'total_courses' => Course::count(),
                'active_courses' => Course::count(), // All courses are active by default
                'total_enrollments' => Enrollment::count(),
                'completion_rate' => $this->getCourseCompletionRate(),
                'popular_departments' => $this->getPopularDepartments(),
                'course_activity' => $this->getCourseActivityTrends(),
                'assessment_completion' => $this->getAssessmentCompletionStats(),
            ];
        });
    }
    
    /**
     * Get platform usage statistics
     */
    public function getPlatformUsageStats(): array
    {
        $cacheKey = 'platform_usage_stats_' . now()->format('Y-m-d-H');
        
        return Cache::remember($cacheKey, now()->addMinutes(30), function () {
            return [
                'current_online_users' => $this->getCurrentOnlineUsers(),
                'peak_concurrent_users' => $this->getPeakConcurrentUsers(),
                'average_session_duration' => $this->getAverageSessionDuration(),
                'page_views_today' => $this->getPageViewsToday(),
                'api_requests_today' => $this->getApiRequestsToday(),
                'storage_usage' => $this->getStorageUsageStats(),
            ];
        });
    }
    
    /**
     * Get active users today
     */
    protected function getActiveUsersToday(): int
    {
        // This would typically track last_login_at or activity logs
        return User::whereDate('updated_at', today())->count();
    }
    
    /**
     * Get active users this week
     */
    protected function getActiveUsersThisWeek(): int
    {
        return User::where('updated_at', '>=', now()->startOfWeek())->count();
    }
    
    /**
     * Get active users this month
     */
    protected function getActiveUsersThisMonth(): int
    {
        return User::where('updated_at', '>=', now()->startOfMonth())->count();
    }
    
    /**
     * Get new users this month
     */
    protected function getNewUsersThisMonth(): int
    {
        return User::where('created_at', '>=', now()->startOfMonth())->count();
    }
    
    /**
     * Get user role distribution
     */
    protected function getUserRoleDistribution(): array
    {
        return User::selectRaw('role, COUNT(*) as count')
            ->groupBy('role')
            ->pluck('count', 'role')
            ->toArray();
    }
    
    /**
     * Get department distribution
     */
    protected function getDepartmentDistribution(): array
    {
        return User::join('departments', 'users.department_id', '=', 'departments.id')
            ->selectRaw('departments.name, COUNT(*) as count')
            ->groupBy('departments.id', 'departments.name')
            ->pluck('count', 'departments.name')
            ->toArray();
    }
    
    /**
     * Get login trends (last 30 days)
     */
    protected function getLoginTrends(): array
    {
        $trends = [];
        
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $count = User::whereDate('updated_at', $date)->count();
            $trends[] = [
                'date' => $date,
                'active_users' => $count,
            ];
        }
        
        return $trends;
    }
    
    /**
     * Get course completion rate
     */
    protected function getCourseCompletionRate(): float
    {
        $totalEnrollments = Enrollment::count();
        $completedEnrollments = Enrollment::where('status', 'completed')->count();
        
        return $totalEnrollments > 0 ? round(($completedEnrollments / $totalEnrollments) * 100, 2) : 0;
    }
    
    /**
     * Get popular departments
     */
    protected function getPopularDepartments(): array
    {
        return Course::join('departments', 'courses.department_id', '=', 'departments.id')
            ->selectRaw('departments.name, COUNT(*) as course_count')
            ->groupBy('departments.id', 'departments.name')
            ->orderBy('course_count', 'desc')
            ->limit(10)
            ->pluck('course_count', 'departments.name')
            ->toArray();
    }
    
    /**
     * Get course activity trends
     */
    protected function getCourseActivityTrends(): array
    {
        $trends = [];
        
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $enrollments = Enrollment::whereDate('enrolled_at', $date)->count();
            $submissions = Submission::whereDate('submitted_at', $date)->count();
            
            $trends[] = [
                'date' => $date,
                'enrollments' => $enrollments,
                'submissions' => $submissions,
            ];
        }
        
        return $trends;
    }
    
    /**
     * Get assessment completion statistics
     */
    protected function getAssessmentCompletionStats(): array
    {
        $totalAssessments = DB::table('assessments')->count();
        $totalSubmissions = Submission::count();
        
        return [
            'total_assessments' => $totalAssessments,
            'total_submissions' => $totalSubmissions,
            'completion_rate' => $totalAssessments > 0 ? round(($totalSubmissions / $totalAssessments) * 100, 2) : 0,
        ];
    }
    
    /**
     * Get current online users
     */
    protected function getCurrentOnlineUsers(): int
    {
        // This would typically use a last_activity timestamp or session tracking
        return Cache::get('online_users_count', 0);
    }
    
    /**
     * Get peak concurrent users today
     */
    protected function getPeakConcurrentUsers(): int
    {
        return Cache::get('peak_concurrent_users_' . today()->format('Y-m-d'), 0);
    }
    
    /**
     * Get average session duration
     */
    protected function getAverageSessionDuration(): string
    {
        // This would require session tracking implementation
        return Cache::get('avg_session_duration', '0 minutes');
    }
    
    /**
     * Get page views today
     */
    protected function getPageViewsToday(): int
    {
        return Cache::get('page_views_' . today()->format('Y-m-d'), 0);
    }
    
    /**
     * Get API requests today
     */
    protected function getApiRequestsToday(): int
    {
        return Cache::get('api_requests_' . today()->format('Y-m-d'), 0);
    }
    
    /**
     * Get storage usage statistics
     */
    protected function getStorageUsageStats(): array
    {
        $storagePath = storage_path('app/public');
        
        return [
            'total_files' => $this->countFiles($storagePath),
            'total_size' => $this->formatBytes($this->getDirectorySize($storagePath)),
            'profile_pictures' => $this->countFiles($storagePath . '/profile_pictures'),
            'course_materials' => $this->countFiles($storagePath . '/courses'),
            'documents' => $this->countFiles($storagePath . '/documents'),
        ];
    }
    
    /**
     * Count files in directory
     */
    protected function countFiles(string $directory): int
    {
        if (!is_dir($directory)) {
            return 0;
        }
        
        $files = glob($directory . '/*');
        return count($files);
    }
    
    /**
     * Get directory size
     */
    protected function getDirectorySize(string $directory): int
    {
        if (!is_dir($directory)) {
            return 0;
        }
        
        $size = 0;
        $files = glob(rtrim($directory, '/') . '/*', GLOB_NOSORT);
        
        foreach ($files as $file) {
            if (is_file($file)) {
                $size += filesize($file);
            } elseif (is_dir($file)) {
                $size += $this->getDirectorySize($file);
            }
        }
        
        return $size;
    }
    
    /**
     * Format bytes to human readable format
     */
    protected function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
    
    /**
     * Track user activity
     */
    public function trackUserActivity(int $userId, string $activity): void
    {
        $today = today()->format('Y-m-d');
        $cacheKey = "user_activity_{$today}";
        
        $activities = Cache::get($cacheKey, []);
        $activities[] = [
            'user_id' => $userId,
            'activity' => $activity,
            'timestamp' => now()->toISOString(),
        ];
        
        // Keep only last 1000 activities
        if (count($activities) > 1000) {
            $activities = array_slice($activities, -1000);
        }
        
        Cache::put($cacheKey, $activities, now()->addDays(2));
    }
    
    /**
     * Track page view
     */
    public function trackPageView(string $page): void
    {
        $today = today()->format('Y-m-d');
        Cache::increment('page_views_' . $today);
        
        // Track page-specific views
        $pageKey = 'page_views_' . str_replace('/', '_', $page) . '_' . $today;
        Cache::increment($pageKey);
    }
    
    /**
     * Track API request
     */
    public function trackApiRequest(string $endpoint): void
    {
        $today = today()->format('Y-m-d');
        Cache::increment('api_requests_' . $today);
        
        // Track endpoint-specific requests
        $endpointKey = 'api_requests_' . str_replace('/', '_', $endpoint) . '_' . $today;
        Cache::increment($endpointKey);
    }
}
