<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageOptimizationService
{
    protected ImageManager $manager;
    
    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }
    
    /**
     * Optimize and store uploaded image
     */
    public function optimizeAndStore(UploadedFile $file, string $path = 'images', array $options = []): string
    {
        $defaultOptions = [
            'width' => 1200,
            'height' => 1200,
            'quality' => 85,
            'format' => 'webp'
        ];
        
        $options = array_merge($defaultOptions, $options);
        
        // Generate unique filename
        $filename = uniqid() . '.' . $options['format'];
        $fullPath = $path . '/' . $filename;
        
        // Optimize image
        $image = $this->manager->read($file);
        
        // Resize if needed
        if ($image->width() > $options['width'] || $image->height() > $options['height']) {
            $image->scale($options['width'], $options['height']);
        }
        
        // Convert format and optimize
        if ($options['format'] === 'webp') {
            $encodedImage = $image->toWebp($options['quality']);
        } else {
            $encodedImage = $image->toJpeg($options['quality']);
        }
        
        // Store optimized image
        Storage::disk('public')->put($fullPath, $encodedImage);
        
        return $fullPath;
    }
    
    /**
     * Create thumbnails for different sizes
     */
    public function createThumbnails(UploadedFile $file, string $originalPath): array
    {
        $thumbnails = [];
        $sizes = [
            'small' => ['width' => 150, 'height' => 150],
            'medium' => ['width' => 300, 'height' => 300],
            'large' => ['width' => 800, 'height' => 800]
        ];
        
        foreach ($sizes as $size => $dimensions) {
            $path = dirname($originalPath) . '/thumbnails/' . $size;
            $thumbnailPath = $this->optimizeAndStore($file, $path, [
                'width' => $dimensions['width'],
                'height' => $dimensions['height'],
                'quality' => 80,
                'format' => 'webp'
            ]);
            
            $thumbnails[$size] = $thumbnailPath;
        }
        
        return $thumbnails;
    }
    
    /**
     * Get optimized image URL
     */
    public function getOptimizedUrl(string $path, string $size = null): string
    {
        if ($size && $this->hasThumbnail($path, $size)) {
            $thumbnailPath = dirname($path) . '/thumbnails/' . $size . '/' . basename($path);
            return Storage::disk('public')->url($thumbnailPath);
        }
        
        return Storage::disk('public')->url($path);
    }
    
    /**
     * Check if thumbnail exists
     */
    protected function hasThumbnail(string $path, string $size): bool
    {
        $thumbnailPath = dirname($path) . '/thumbnails/' . $size . '/' . basename($path);
        return Storage::disk('public')->exists($thumbnailPath);
    }
    
    /**
     * Delete original image and all thumbnails
     */
    public function deleteWithThumbnails(string $path): void
    {
        Storage::disk('public')->delete($path);
        
        // Delete all thumbnail sizes
        $sizes = ['small', 'medium', 'large'];
        foreach ($sizes as $size) {
            $thumbnailPath = dirname($path) . '/thumbnails/' . $size . '/' . basename($path);
            Storage::disk('public')->delete($thumbnailPath);
        }
    }
}
