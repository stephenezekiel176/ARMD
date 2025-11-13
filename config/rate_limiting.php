<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration controls the rate limiting functionality for your
    | application to prevent abuse and ensure fair usage.
    |
    */

    'enabled' => env('RATE_LIMITING_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Rate Limit Attempts
    |--------------------------------------------------------------------------
    |
    | Maximum number of requests allowed within the time window.
    |
    */

    'attempts' => env('RATE_LIMIT_ATTEMPTS', 60),

    /*
    |--------------------------------------------------------------------------
    | Rate Limit Time Window
    |--------------------------------------------------------------------------
    |
    | Time window in minutes for the rate limit. Default is 1 minute.
    |
    */

    'minutes' => env('RATE_LIMIT_MINUTES', 1),

    /*
    |--------------------------------------------------------------------------
    | Custom Rate Limits
    |--------------------------------------------------------------------------
    |
    | You can define custom rate limits for specific routes or user types.
    | These will override the global settings.
    |
    */

    'custom_limits' => [
        // 'api' => ['attempts' => 100, 'minutes' => 1],
        // 'auth' => ['attempts' => 5, 'minutes' => 1],
        // 'upload' => ['attempts' => 10, 'minutes' => 5],
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limit Strategy
    |--------------------------------------------------------------------------
    |
    | Strategy for identifying users for rate limiting:
    | - ip: Use IP address
    | - user: Use authenticated user ID
    | - combined: Use both IP and user ID
    |
    */

    'strategy' => 'ip',

    /*
    |--------------------------------------------------------------------------
    | Whitelist
    |--------------------------------------------------------------------------
    |
    | IP addresses or user IDs that are exempt from rate limiting.
    |
    */

    'whitelist' => [
        // '127.0.0.1',
        // '192.168.1.1',
    ],

    /*
    |--------------------------------------------------------------------------
    | Blacklist
    |--------------------------------------------------------------------------
    |
    | IP addresses or user IDs that are always blocked.
    |
    */

    'blacklist' => [
        // 'malicious.ip.address',
    ],

    /*
    |--------------------------------------------------------------------------
    | Response Headers
    |--------------------------------------------------------------------------
    |
    | Include rate limit information in response headers.
    |
    */

    'include_headers' => true,

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    |
    | Log rate limit violations for monitoring and analysis.
    |
    */

    'log_violations' => true,

];
