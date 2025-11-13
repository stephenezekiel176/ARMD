<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class RateLimitService
{
    protected $enabled;
    protected $attempts;
    protected $minutes;
    protected $keyPrefix;

    public function __construct()
    {
        $this->enabled = config('rate_limiting.enabled', true);
        $this->attempts = config('rate_limiting.attempts', 60);
        $this->minutes = config('rate_limiting.minutes', 1);
        $this->keyPrefix = 'rate_limit:';
    }

    /**
     * Check if the request is allowed based on rate limiting
     */
    public function isAllowed(Request $request, $key = null)
    {
        if (!$this->enabled) {
            return true;
        }

        $identifier = $this->getIdentifier($request, $key);
        return $this->checkRateLimit($identifier);
    }

    /**
     * Get rate limit information for a request
     */
    public function getRateLimitInfo(Request $request, $key = null)
    {
        if (!$this->enabled) {
            return [
                'allowed' => true,
                'remaining' => $this->attempts,
                'reset_time' => null,
                'limit' => $this->attempts
            ];
        }

        $identifier = $this->getIdentifier($request, $key);
        return $this->getRateLimitData($identifier);
    }

    /**
     * Increment the rate limit counter
     */
    public function increment(Request $request, $key = null)
    {
        if (!$this->enabled) {
            return true;
        }

        $identifier = $this->getIdentifier($request, $key);
        return $this->incrementCounter($identifier);
    }

    /**
     * Check rate limit for a specific identifier
     */
    protected function checkRateLimit($identifier)
    {
        $cacheKey = $this->keyPrefix . $identifier;
        
        if (config('cache.default') === 'redis') {
            return $this->checkRedisRateLimit($cacheKey);
        } else {
            return $this->checkCacheRateLimit($cacheKey);
        }
    }

    /**
     * Check rate limit using Redis
     */
    protected function checkRedisRateLimit($cacheKey)
    {
        $current = Redis::get($cacheKey);
        
        if ($current === null) {
            Redis::setex($cacheKey, $this->minutes * 60, 1);
            return true;
        }

        if ($current >= $this->attempts) {
            return false;
        }

        Redis::incr($cacheKey);
        return true;
    }

    /**
     * Check rate limit using Cache
     */
    protected function checkCacheRateLimit($cacheKey)
    {
        $current = Cache::get($cacheKey, 0);
        
        if ($current >= $this->attempts) {
            return false;
        }

        Cache::put($cacheKey, $current + 1, $this->minutes * 60);
        return true;
    }

    /**
     * Get rate limit data
     */
    protected function getRateLimitData($identifier)
    {
        $cacheKey = $this->keyPrefix . $identifier;
        
        if (config('cache.default') === 'redis') {
            $current = Redis::get($cacheKey) ?? 0;
            $ttl = Redis::ttl($cacheKey);
        } else {
            $current = Cache::get($cacheKey, 0);
            $ttl = $this->getCacheTtl($cacheKey);
        }

        return [
            'allowed' => $current < $this->attempts,
            'remaining' => max(0, $this->attempts - $current),
            'reset_time' => $ttl > 0 ? now()->addSeconds($ttl) : null,
            'limit' => $this->attempts,
            'current' => $current
        ];
    }

    /**
     * Increment counter
     */
    protected function incrementCounter($identifier)
    {
        $cacheKey = $this->keyPrefix . $identifier;
        
        if (config('cache.default') === 'redis') {
            $current = Redis::incr($cacheKey);
            if ($current === 1) {
                Redis::expire($cacheKey, $this->minutes * 60);
            }
            return $current < $this->attempts;
        } else {
            $current = Cache::increment($cacheKey, 1, $this->minutes * 60);
            return $current < $this->attempts;
        }
    }

    /**
     * Get identifier for rate limiting
     */
    protected function getIdentifier(Request $request, $key = null)
    {
        if ($key) {
            return $key;
        }

        // Use IP address as default identifier
        return $request->ip();
    }

    /**
     * Get cache TTL (approximate)
     */
    protected function getCacheTtl($cacheKey)
    {
        // This is approximate since Laravel's cache doesn't expose TTL directly
        return $this->minutes * 60;
    }

    /**
     * Clear rate limit for a specific identifier
     */
    public function clear(Request $request, $key = null)
    {
        $identifier = $this->getIdentifier($request, $key);
        $cacheKey = $this->keyPrefix . $identifier;

        if (config('cache.default') === 'redis') {
            Redis::del($cacheKey);
        } else {
            Cache::forget($cacheKey);
        }

        return true;
    }

    /**
     * Get rate limit statistics
     */
    public function getStatistics()
    {
        if (!$this->enabled) {
            return [
                'enabled' => false,
                'total_requests' => 0,
                'blocked_requests' => 0,
                'active_keys' => 0
            ];
        }

        // This would require additional tracking for production use
        return [
            'enabled' => true,
            'attempts_limit' => $this->attempts,
            'time_window' => $this->minutes . ' minutes',
            'cache_driver' => config('cache.default')
        ];
    }
}
