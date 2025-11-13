<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CDNService
{
    /**
     * Get CDN URL for assets
     */
    public function getCdnUrl(string $path): string
    {
        $cdnUrl = config('cdn.url');
        
        if (!$cdnUrl) {
            return asset($path);
        }
        
        // Remove leading slash if present
        $path = ltrim($path, '/');
        
        return rtrim($cdnUrl, '/') . '/' . $path;
    }
    
    /**
     * Get optimized image URL with CDN
     */
    public function getOptimizedImageUrl(string $path, array $options = []): string
    {
        $defaultOptions = [
            'width' => null,
            'height' => null,
            'quality' => 80,
            'format' => 'auto',
        ];
        
        $options = array_merge($defaultOptions, $options);
        
        $cdnUrl = config('cdn.url');
        
        if (!$cdnUrl) {
            return asset($path);
        }
        
        // Build CDN URL with optimization parameters
        $url = rtrim($cdnUrl, '/') . '/' . ltrim($path, '/');
        
        $params = [];
        
        if ($options['width']) {
            $params[] = 'w=' . $options['width'];
        }
        
        if ($options['height']) {
            $params[] = 'h=' . $options['height'];
        }
        
        if ($options['quality'] !== 80) {
            $params[] = 'q=' . $options['quality'];
        }
        
        if ($options['format'] !== 'auto') {
            $params[] = 'f=' . $options['format'];
        }
        
        if (!empty($params)) {
            $url .= '?' . implode('&', $params);
        }
        
        return $url;
    }
    
    /**
     * Upload file to CDN storage
     */
    public function uploadToCdn(string $localPath, string $remotePath): string
    {
        $disk = config('cdn.storage_disk', 's3');
        
        // Upload file to CDN storage
        Storage::disk($disk)->put($remotePath, file_get_contents($localPath));
        
        // Return CDN URL
        return $this->getCdnUrl($remotePath);
    }
    
    /**
     * Delete file from CDN
     */
    public function deleteFromCdn(string $path): bool
    {
        $disk = config('cdn.storage_disk', 's3');
        
        return Storage::disk($disk)->delete($path);
    }
    
    /**
     * Get responsive image sources
     */
    public function getResponsiveImageSources(string $path, array $breakpoints = []): array
    {
        $defaultBreakpoints = [
            'small' => ['width' => 640, 'media' => '(max-width: 640px)'],
            'medium' => ['width' => 768, 'media' => '(max-width: 768px)'],
            'large' => ['width' => 1024, 'media' => '(max-width: 1024px)'],
            'xlarge' => ['width' => 1280, 'media' => '(max-width: 1280px)'],
        ];
        
        $breakpoints = array_merge($defaultBreakpoints, $breakpoints);
        $sources = [];
        
        foreach ($breakpoints as $name => $config) {
            $sources[] = [
                'srcset' => $this->getOptimizedImageUrl($path, [
                    'width' => $config['width'],
                    'format' => 'webp',
                ]),
                'media' => $config['media'],
                'type' => 'image/webp',
            ];
        }
        
        return $sources;
    }
    
    /**
     * Generate picture element HTML
     */
    public function generatePictureHtml(string $path, array $options = []): string
    {
        $defaultOptions = [
            'alt' => '',
            'class' => '',
            'loading' => 'lazy',
            'breakpoints' => [],
        ];
        
        $options = array_merge($defaultOptions, $options);
        
        $sources = $this->getResponsiveImageSources($path, $options['breakpoints']);
        
        $html = '<picture>';
        
        foreach ($sources as $source) {
            $html .= sprintf(
                '<source srcset="%s" media="%s" type="%s">',
                htmlspecialchars($source['srcset']),
                htmlspecialchars($source['media']),
                htmlspecialchars($source['type'])
            );
        }
        
        // Fallback image
        $fallbackUrl = $this->getCdnUrl($path);
        $html .= sprintf(
            '<img src="%s" alt="%s" class="%s" loading="%s">',
            htmlspecialchars($fallbackUrl),
            htmlspecialchars($options['alt']),
            htmlspecialchars($options['class']),
            htmlspecialchars($options['loading'])
        );
        
        $html .= '</picture>';
        
        return $html;
    }
    
    /**
     * Cache busting URL
     */
    public function cacheBust(string $path): string
    {
        $version = config('app.version', time());
        
        if (Str::contains($path, '?')) {
            return $path . '&v=' . $version;
        }
        
        return $path . '?v=' . $version;
    }
    
    /**
     * Preload critical resources
     */
    public function getPreloadLinks(): array
    {
        $criticalResources = config('cdn.preload_resources', []);
        $links = [];
        
        foreach ($criticalResources as $resource) {
            $links[] = [
                'href' => $this->getCdnUrl($resource['path']),
                'as' => $resource['as'] ?? 'script',
                'type' => $resource['type'] ?? null,
            ];
        }
        
        return $links;
    }
    
    /**
     * Get CDN configuration
     */
    public function getCdnConfig(): array
    {
        return [
            'url' => config('cdn.url'),
            'storage_disk' => config('cdn.storage_disk', 's3'),
            'enabled' => config('cdn.enabled', false),
            'optimize_images' => config('cdn.optimize_images', true),
            'cache_ttl' => config('cdn.cache_ttl', 31536000), // 1 year
        ];
    }
}
