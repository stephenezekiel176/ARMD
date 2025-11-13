<?php

namespace App\Http\Controllers;

use App\Services\PerformanceMonitoringService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HealthCheckController extends Controller
{
    protected PerformanceMonitoringService $performanceService;
    
    public function __construct(PerformanceMonitoringService $performanceService)
    {
        $this->performanceService = $performanceService;
    }
    
    /**
     * Basic health check endpoint
     */
    public function health(): JsonResponse
    {
        $health = $this->performanceService->checkSystemHealth();
        
        return response()->json([
            'status' => $health['status'],
            'timestamp' => now()->toISOString(),
            'version' => config('app.version', '1.0.0'),
            'checks' => $health['checks'],
        ], $health['status'] === 'critical' ? 503 : 200);
    }
    
    /**
     * Detailed system metrics
     */
    public function metrics(Request $request): JsonResponse
    {
        // Basic authentication for metrics endpoint
        if (!$request->hasHeader('X-Metrics-Token') || 
            $request->header('X-Metrics-Token') !== config('app.metrics_token', 'secret')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $metrics = $this->performanceService->getSystemMetrics();
        
        return response()->json([
            'timestamp' => now()->toISOString(),
            'metrics' => $metrics,
        ]);
    }
    
    /**
     * Performance dashboard data
     */
    public function dashboard(): JsonResponse
    {
        $metrics = $this->performanceService->getSystemMetrics();
        $health = $this->performanceService->checkSystemHealth();
        
        return response()->json([
            'health' => $health,
            'metrics' => $metrics,
            'timestamp' => now()->toISOString(),
        ]);
    }
}
