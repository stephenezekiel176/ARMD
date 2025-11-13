<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class PerformanceOptimization
{
    /**
     * Handle an incoming request for 500k concurrent users
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Apply rate limiting per user/IP
        $this->applyRateLimiting($request);

        // Add performance headers
        $response = $next($request);

        // Optimize response for high concurrency
        return $this->optimizeResponse($response, $request);
    }

    /**
     * Apply intelligent rate limiting
     */
    private function applyRateLimiting(Request $request): void
    {
        $key = $this->getRateLimitKey($request);
        
        // Different limits for different request types
        $limits = $this->getRateLimits($request);
        
        foreach ($limits as $limitKey => $maxAttempts) {
            $executed = RateLimiter::attempt(
                $key . ':' . $limitKey,
                $maxAttempts,
                function () {
                    // Allow the request
                },
                60 // 1 minute window
            );

            if (!$executed) {
                abort(429, "Too many {$limitKey} requests. Please slow down.");
            }
        }
    }

    /**
     * Get rate limit key based on user/IP
     */
    private function getRateLimitKey(Request $request): string
    {
        if ($user = $request->user()) {
            return 'user:' . $user->id;
        }

        return 'ip:' . $request->ip();
    }

    /**
     * Get rate limits based on request type
     */
    private function getRateLimits(Request $request): array
    {
        $path = $request->path();
        
        // Video streaming - more restrictive
        if (str_contains($path, 'stream') || str_contains($path, 'video')) {
            return [
                'video' => 30, // 30 video requests per minute
                'general' => 200,
            ];
        }

        // API endpoints - moderate limits
        if (str_starts_with($path, 'api/')) {
            return [
                'api' => 100, // 100 API calls per minute
                'general' => 300,
            ];
        }

        // File downloads - restrictive
        if (str_contains($path, 'download') || str_contains($path, 'file')) {
            return [
                'download' => 20, // 20 downloads per minute
                'general' => 200,
            ];
        }

        // General web requests - generous
        return [
            'general' => 500, // 500 general requests per minute
        ];
    }

    /**
     * Optimize response for high concurrency
     */
    private function optimizeResponse(Response $response, Request $request): Response
    {
        // Add caching headers for static content
        if ($this->isStaticContent($request)) {
            $response->headers->set('Cache-Control', 'public, max-age=31536000'); // 1 year
            $response->headers->set('Expires', gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
        }

        // Add performance headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Enable compression
        if (!$response->headers->has('Content-Encoding')) {
            $response->headers->set('Vary', 'Accept-Encoding');
        }

        // Add connection keep-alive for HTTP/1.1
        $response->headers->set('Connection', 'keep-alive');
        $response->headers->set('Keep-Alive', 'timeout=5, max=100');

        return $response;
    }

    /**
     * Check if request is for static content
     */
    private function isStaticContent(Request $request): bool
    {
        $path = $request->path();
        $staticExtensions = ['css', 'js', 'png', 'jpg', 'jpeg', 'gif', 'svg', 'ico', 'woff', 'woff2', 'ttf'];
        
        foreach ($staticExtensions as $ext) {
            if (str_ends_with($path, '.' . $ext)) {
                return true;
            }
        }

        return false;
    }
}
