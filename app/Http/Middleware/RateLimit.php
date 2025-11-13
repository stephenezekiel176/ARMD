<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\RateLimitService;
use Symfony\Component\HttpFoundation\Response;

class RateLimit
{
    protected $rateLimitService;

    public function __construct(RateLimitService $rateLimitService)
    {
        $this->rateLimitService = $rateLimitService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $key = null): Response
    {
        // Check if rate limiting is enabled
        if (!config('rate_limiting.enabled', true)) {
            return $next($request);
        }

        // Check whitelist
        if ($this->isWhitelisted($request)) {
            return $next($request);
        }

        // Check blacklist
        if ($this->isBlacklisted($request)) {
            return $this->rateLimitResponse($request, 'Access denied');
        }

        // Check rate limit
        if (!$this->rateLimitService->isAllowed($request, $key)) {
            // Log violation
            if (config('rate_limiting.log_violations', true)) {
                \Log::warning('Rate limit exceeded', [
                    'ip' => $request->ip(),
                    'url' => $request->fullUrl(),
                    'user_agent' => $request->userAgent(),
                ]);
            }

            return $this->rateLimitResponse($request, 'Too many requests');
        }

        // Increment counter
        $this->rateLimitService->increment($request, $key);

        $response = $next($request);

        // Add rate limit headers if enabled
        if (config('rate_limiting.include_headers', true)) {
            $rateLimitInfo = $this->rateLimitService->getRateLimitInfo($request, $key);
            $response->headers->set('X-RateLimit-Limit', $rateLimitInfo['limit']);
            $response->headers->set('X-RateLimit-Remaining', $rateLimitInfo['remaining']);
            
            if ($rateLimitInfo['reset_time']) {
                $response->headers->set('X-RateLimit-Reset', $rateLimitInfo['reset_time']->timestamp);
            }
        }

        return $response;
    }

    /**
     * Check if the request is whitelisted
     */
    protected function isWhitelisted(Request $request): bool
    {
        $whitelist = config('rate_limiting.whitelist', []);
        
        // Check IP whitelist
        if (in_array($request->ip(), $whitelist)) {
            return true;
        }

        // Check user whitelist if authenticated
        if (auth()->check() && in_array(auth()->id(), $whitelist)) {
            return true;
        }

        return false;
    }

    /**
     * Check if the request is blacklisted
     */
    protected function isBlacklisted(Request $request): bool
    {
        $blacklist = config('rate_limiting.blacklist', []);
        
        // Check IP blacklist
        if (in_array($request->ip(), $blacklist)) {
            return true;
        }

        // Check user blacklist if authenticated
        if (auth()->check() && in_array(auth()->id(), $blacklist)) {
            return true;
        }

        return false;
    }

    /**
     * Return rate limit response
     */
    protected function rateLimitResponse(Request $request, string $message): Response
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'error' => 'RATE_LIMIT_EXCEEDED'
            ], 429);
        }

        return response()->view('errors.rate-limit', [], 429);
    }
}
