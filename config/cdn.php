<?php

return [
    /*
    |--------------------------------------------------------------------------
    | CDN Configuration
    |--------------------------------------------------------------------------
    |
    | Configure your Content Delivery Network settings for optimal performance.
    | This supports AWS CloudFront, Cloudflare, and other CDN providers.
    |
    */
    
    'enabled' => env('CDN_ENABLED', false),
    
    'url' => env('CDN_URL', ''),
    
    'storage_disk' => env('CDN_STORAGE_DISK', 's3'),
    
    'optimize_images' => env('CDN_OPTIMIZE_IMAGES', true),
    
    'cache_ttl' => env('CDN_CACHE_TTL', 31536000), // 1 year in seconds
    
    'preload_resources' => [
        [
            'path' => 'css/app.css',
            'as' => 'style',
        ],
        [
            'path' => 'js/app.js',
            'as' => 'script',
        ],
        [
            'path' => 'images/logo.svg',
            'as' => 'image',
            'type' => 'image/svg+xml',
        ],
    ],
    
    'image_optimization' => [
        'default_quality' => 80,
        'formats' => ['webp', 'avif', 'jpeg'],
        'responsive_breakpoints' => [
            'small' => ['width' => 640],
            'medium' => ['width' => 768],
            'large' => ['width' => 1024],
            'xlarge' => ['width' => 1280],
        ],
    ],
    
    'security' => [
        'signed_urls' => env('CDN_SIGNED_URLS', false),
        'private_content' => env('CDN_PRIVATE_CONTENT', false),
        'allowed_origins' => explode(',', env('CDN_ALLOWED_ORIGINS', '*')),
    ],
];
