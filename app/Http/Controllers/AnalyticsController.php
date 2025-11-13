<?php

namespace App\Http\Controllers;

use App\Services\UserAnalyticsService;
use App\Services\PerformanceMonitoringService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    protected UserAnalyticsService $analyticsService;
    protected PerformanceMonitoringService $performanceService;
    
    public function __construct(
        UserAnalyticsService $analyticsService,
        PerformanceMonitoringService $performanceService
    ) {
        $this->analyticsService = $analyticsService;
        $this->performanceService = $performanceService;
    }
    
    /**
     * Get comprehensive analytics dashboard
     */
    public function dashboard()
    {
        return view('admin.analytics.dashboard');
    }
    
    /**
     * Get comprehensive analytics API data
     */
    public function dashboardApi(): JsonResponse
    {
        return response()->json([
            'user_activity' => $this->analyticsService->getUserActivityAnalytics(),
            'course_engagement' => $this->analyticsService->getCourseEngagementAnalytics(),
            'platform_usage' => $this->analyticsService->getPlatformUsageStats(),
            'system_performance' => $this->performanceService->getSystemMetrics(),
            'timestamp' => now()->toISOString(),
        ]);
    }
    
    /**
     * Get user activity analytics
     */
    public function userActivity(): JsonResponse
    {
        return response()->json([
            'data' => $this->analyticsService->getUserActivityAnalytics(),
            'timestamp' => now()->toISOString(),
        ]);
    }
    
    /**
     * Get course engagement analytics
     */
    public function courseEngagement(): JsonResponse
    {
        return response()->json([
            'data' => $this->analyticsService->getCourseEngagementAnalytics(),
            'timestamp' => now()->toISOString(),
        ]);
    }
    
    /**
     * Get platform usage statistics
     */
    public function platformUsage(): JsonResponse
    {
        return response()->json([
            'data' => $this->analyticsService->getPlatformUsageStats(),
            'timestamp' => now()->toISOString(),
        ]);
    }
    
    /**
     * Get system performance metrics
     */
    public function systemPerformance(): JsonResponse
    {
        return response()->json([
            'data' => $this->performanceService->getSystemMetrics(),
            'timestamp' => now()->toISOString(),
        ]);
    }
    
    /**
     * Track custom event
     */
    public function trackEvent(Request $request): JsonResponse
    {
        $request->validate([
            'event_type' => 'required|string',
            'event_data' => 'array',
        ]);
        
        $eventType = $request->input('event_type');
        $eventData = $request->input('event_data', []);
        
        // Track different types of events
        switch ($eventType) {
            case 'user_activity':
                if (isset($eventData['user_id']) && isset($eventData['activity'])) {
                    $this->analyticsService->trackUserActivity($eventData['user_id'], $eventData['activity']);
                }
                break;
                
            case 'page_view':
                if (isset($eventData['page'])) {
                    $this->analyticsService->trackPageView($eventData['page']);
                }
                break;
                
            case 'api_request':
                if (isset($eventData['endpoint'])) {
                    $this->analyticsService->trackApiRequest($eventData['endpoint']);
                }
                break;
        }
        
        return response()->json([
            'message' => 'Event tracked successfully',
            'timestamp' => now()->toISOString(),
        ]);
    }
    
    /**
     * Get analytics for specific date range
     */
    public function dateRangeAnalytics(Request $request): JsonResponse
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'metrics' => 'array',
            'metrics.*' => 'string|in:user_activity,course_engagement,platform_usage,system_performance',
        ]);
        
        $metrics = $request->input('metrics', ['user_activity', 'course_engagement', 'platform_usage', 'system_performance']);
        $result = [];
        
        foreach ($metrics as $metric) {
            switch ($metric) {
                case 'user_activity':
                    $result['user_activity'] = $this->analyticsService->getUserActivityAnalytics();
                    break;
                case 'course_engagement':
                    $result['course_engagement'] = $this->analyticsService->getCourseEngagementAnalytics();
                    break;
                case 'platform_usage':
                    $result['platform_usage'] = $this->analyticsService->getPlatformUsageStats();
                    break;
                case 'system_performance':
                    $result['system_performance'] = $this->performanceService->getSystemMetrics();
                    break;
            }
        }
        
        return response()->json([
            'data' => $result,
            'date_range' => [
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
            ],
            'timestamp' => now()->toISOString(),
        ]);
    }
    
    /**
     * Export analytics data
     */
    public function export(Request $request): JsonResponse
    {
        $request->validate([
            'format' => 'string|in:json,csv',
            'metrics' => 'array',
        ]);
        
        $format = $request->input('format', 'json');
        $metrics = $request->input('metrics', ['user_activity', 'course_engagement', 'platform_usage']);
        
        $data = [
            'user_activity' => $this->analyticsService->getUserActivityAnalytics(),
            'course_engagement' => $this->analyticsService->getCourseEngagementAnalytics(),
            'platform_usage' => $this->analyticsService->getPlatformUsageStats(),
            'system_performance' => $this->performanceService->getSystemMetrics(),
        ];
        
        // Filter metrics if specified
        if (!empty($metrics)) {
            $data = array_intersect_key($data, array_flip($metrics));
        }
        
        $exportData = [
            'exported_at' => now()->toISOString(),
            'data' => $data,
        ];
        
        if ($format === 'csv') {
            // Convert to CSV format (simplified for demonstration)
            $csvData = $this->convertToCsv($exportData);
            return response($csvData, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="analytics.csv"',
            ]);
        }
        
        return response()->json($exportData);
    }
    
    /**
     * Convert analytics data to CSV format
     */
    protected function convertToCsv(array $data): string
    {
        $csv = "Metric,Value,Date\n";
        
        foreach ($data['data'] as $category => $metrics) {
            foreach ($metrics as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $subKey => $subValue) {
                        $csv .= "{$category}.{$key}.{$subKey},{$subValue}," . now()->format('Y-m-d') . "\n";
                    }
                } else {
                    $csv .= "{$category}.{$key},{$value}," . now()->format('Y-m-d') . "\n";
                }
            }
        }
        
        return $csv;
    }
}
