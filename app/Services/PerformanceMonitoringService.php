<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PerformanceMonitoringService
{
    /**
     * Record query performance metrics
     */
    public function recordQueryPerformance(string $query, float $executionTime): void
    {
        $metrics = Cache::get('performance_metrics', []);
        
        $metrics['queries'][] = [
            'query' => $query,
            'execution_time' => $executionTime,
            'timestamp' => now()->toISOString(),
        ];
        
        // Keep only last 1000 queries
        if (count($metrics['queries']) > 1000) {
            $metrics['queries'] = array_slice($metrics['queries'], -1000);
        }
        
        Cache::put('performance_metrics', $metrics, now()->addHours(24));
    }
    
    /**
     * Get system performance metrics
     */
    public function getSystemMetrics(): array
    {
        return [
            'database' => [
                'connections' => $this->getDatabaseConnections(),
                'slow_queries' => $this->getSlowQueries(),
                'cache_hit_rate' => $this->getCacheHitRate(),
            ],
            'memory' => [
                'usage' => memory_get_usage(true),
                'peak_usage' => memory_get_peak_usage(true),
                'limit' => $this->getMemoryLimit(),
            ],
            'storage' => [
                'disk_usage' => $this->getDiskUsage(),
                'cache_size' => $this->getCacheSize(),
            ],
            'application' => [
                'uptime' => $this->getUptime(),
                'request_count' => $this->getRequestCount(),
                'average_response_time' => $this->getAverageResponseTime(),
            ],
        ];
    }
    
    /**
     * Get database connection count
     */
    protected function getDatabaseConnections(): int
    {
        try {
            return DB::select('SHOW STATUS LIKE "Threads_connected"')[0]->Value ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    /**
     * Get slow queries count
     */
    protected function getSlowQueries(): int
    {
        try {
            return DB::select('SHOW STATUS LIKE "Slow_queries"')[0]->Value ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    /**
     * Get cache hit rate
     */
    protected function getCacheHitRate(): float
    {
        try {
            $info = Cache::getRedis()->info();
            $hits = $info['keyspace_hits'] ?? 0;
            $misses = $info['keyspace_misses'] ?? 0;
            $total = $hits + $misses;
            
            return $total > 0 ? round(($hits / $total) * 100, 2) : 0;
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    /**
     * Get memory limit
     */
    protected function getMemoryLimit(): string
    {
        return ini_get('memory_limit');
    }
    
    /**
     * Get disk usage
     */
    protected function getDiskUsage(): array
    {
        $total = disk_total_space('/');
        $free = disk_free_space('/');
        $used = $total - $free;
        
        return [
            'total' => $total,
            'used' => $used,
            'free' => $free,
            'usage_percentage' => $total > 0 ? round(($used / $total) * 100, 2) : 0,
        ];
    }
    
    /**
     * Get cache size
     */
    protected function getCacheSize(): string
    {
        try {
            $info = Cache::getRedis()->info('memory');
            return $info['used_memory_human'] ?? '0B';
        } catch (\Exception $e) {
            return '0B';
        }
    }
    
    /**
     * Get application uptime
     */
    protected function getUptime(): string
    {
        $startTime = Cache::get('app_start_time', now());
        return $startTime->diffForHumans(now(), true);
    }
    
    /**
     * Get request count (last hour)
     */
    protected function getRequestCount(): int
    {
        return Cache::get('request_count_' . now()->format('Y-m-d-H'), 0);
    }
    
    /**
     * Get average response time (last hour)
     */
    protected function getAverageResponseTime(): float
    {
        $times = Cache::get('response_times_' . now()->format('Y-m-d-H'), []);
        return count($times) > 0 ? round(array_sum($times) / count($times), 2) : 0;
    }
    
    /**
     * Record request metrics
     */
    public function recordRequestMetrics(float $responseTime): void
    {
        $hourKey = now()->format('Y-m-d-H');
        
        // Increment request count
        Cache::increment('request_count_' . $hourKey);
        
        // Store response time
        $times = Cache::get('response_times_' . $hourKey, []);
        $times[] = $responseTime;
        
        // Keep only last 1000 response times
        if (count($times) > 1000) {
            $times = array_slice($times, -1000);
        }
        
        Cache::put('response_times_' . $hourKey, $times, now()->addHours(2));
    }
    
    /**
     * Check system health
     */
    public function checkSystemHealth(): array
    {
        $health = [
            'status' => 'healthy',
            'checks' => [],
        ];
        
        // Check database connection
        try {
            DB::select('SELECT 1');
            $health['checks']['database'] = ['status' => 'healthy', 'message' => 'Database connected'];
        } catch (\Exception $e) {
            $health['checks']['database'] = ['status' => 'unhealthy', 'message' => 'Database connection failed'];
            $health['status'] = 'unhealthy';
        }
        
        // Check cache connection
        try {
            Cache::put('health_check', 'ok', 60);
            $health['checks']['cache'] = ['status' => 'healthy', 'message' => 'Cache connected'];
        } catch (\Exception $e) {
            $health['checks']['cache'] = ['status' => 'unhealthy', 'message' => 'Cache connection failed'];
            $health['status'] = 'unhealthy';
        }
        
        // Check disk space
        $diskUsage = $this->getDiskUsage();
        if ($diskUsage['usage_percentage'] > 90) {
            $health['checks']['disk'] = ['status' => 'critical', 'message' => 'Disk usage critically high'];
            $health['status'] = 'critical';
        } elseif ($diskUsage['usage_percentage'] > 80) {
            $health['checks']['disk'] = ['status' => 'warning', 'message' => 'Disk usage high'];
            if ($health['status'] === 'healthy') $health['status'] = 'warning';
        } else {
            $health['checks']['disk'] = ['status' => 'healthy', 'message' => 'Disk usage normal'];
        }
        
        // Check memory usage
        $memoryUsage = memory_get_usage(true);
        $memoryLimit = $this->parseMemoryLimit(ini_get('memory_limit'));
        if ($memoryLimit > 0) {
            $memoryPercentage = ($memoryUsage / $memoryLimit) * 100;
            if ($memoryPercentage > 90) {
                $health['checks']['memory'] = ['status' => 'critical', 'message' => 'Memory usage critically high'];
                $health['status'] = 'critical';
            } elseif ($memoryPercentage > 80) {
                $health['checks']['memory'] = ['status' => 'warning', 'message' => 'Memory usage high'];
                if ($health['status'] === 'healthy') $health['status'] = 'warning';
            } else {
                $health['checks']['memory'] = ['status' => 'healthy', 'message' => 'Memory usage normal'];
            }
        }
        
        return $health;
    }
    
    /**
     * Parse memory limit string to bytes
     */
    protected function parseMemoryLimit(string $limit): int
    {
        $limit = trim($limit);
        $last = strtolower($limit[strlen($limit)-1]);
        $value = (int) $limit;
        
        switch ($last) {
            case 'g':
                $value *= 1024;
            case 'm':
                $value *= 1024;
            case 'k':
                $value *= 1024;
        }
        
        return $value;
    }
}
