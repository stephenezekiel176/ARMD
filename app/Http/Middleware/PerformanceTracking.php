<?php

namespace App\Http\Middleware;

use App\Services\PerformanceMonitoringService;
use App\Services\UserAnalyticsService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PerformanceTracking
{
    protected PerformanceMonitoringService $performanceService;
    protected UserAnalyticsService $analyticsService;
    
    public function __construct(
        PerformanceMonitoringService $performanceService,
        UserAnalyticsService $analyticsService
    ) {
        $this->performanceService = $performanceService;
        $this->analyticsService = $analyticsService;
    }
    
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage(true);
        
        // Process the request
        $response = $next($request);
        
        // Calculate metrics
        $endTime = microtime(true);
        $responseTime = round(($endTime - $startTime) * 1000, 2); // in milliseconds
        $memoryUsage = memory_get_usage(true) - $startMemory;
        
        // Record performance metrics
        $this->performanceService->recordRequestMetrics($responseTime);
        
        // Log slow requests
        if ($responseTime > 2000) { // 2 seconds
            Log::warning('Slow request detected', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'response_time' => $responseTime,
                'memory_usage' => $memoryUsage,
                'user_id' => auth()->id(),
            ]);
        }
        
        // Track user activity
        if (auth()->check()) {
            $this->analyticsService->trackUserActivity(auth()->id(), 'page_visit');
        }
        
        // Track page views
        if ($request->isMethod('GET')) {
            $this->analyticsService->trackPageView($request->path());
        }
        
        // Track API requests
        if ($request->expectsJson()) {
            $this->analyticsService->trackApiRequest($request->path());
        }
        
        // Add performance headers in development
        if (app()->environment('local', 'testing')) {
            $response->headers->set('X-Response-Time', $responseTime . 'ms');
            $response->headers->set('X-Memory-Usage', $this->formatBytes($memoryUsage));
        }
        
        return $response;
    }
    
    /**
     * Format bytes to human readable format
     */
    protected function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
