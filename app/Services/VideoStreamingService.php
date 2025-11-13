<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VideoStreamingService
{
    private const CHUNK_SIZE = 1024 * 1024; // 1MB chunks
    private const CACHE_TTL = 3600; // 1 hour

    /**
     * Stream video with range support for 500k concurrent users
     */
    public function streamVideo(string $filePath, array $headers = []): StreamedResponse
    {
        $fullPath = Storage::disk('public')->path($filePath);
        
        if (!file_exists($fullPath)) {
            abort(404, 'Video file not found');
        }

        $fileSize = filesize($fullPath);
        $start = 0;
        $end = $fileSize - 1;

        // Handle range requests for efficient streaming
        if (isset($_SERVER['HTTP_RANGE'])) {
            $range = $_SERVER['HTTP_RANGE'];
            if (preg_match('/bytes=(\d+)-(\d*)/', $range, $matches)) {
                $start = intval($matches[1]);
                if (!empty($matches[2])) {
                    $end = intval($matches[2]);
                }
            }
        }

        $length = $end - $start + 1;

        return new StreamedResponse(function() use ($fullPath, $start, $length) {
            $this->streamFileChunk($fullPath, $start, $length);
        }, 206, array_merge([
            'Content-Type' => 'video/mp4',
            'Content-Length' => $length,
            'Accept-Ranges' => 'bytes',
            'Content-Range' => "bytes $start-$end/$fileSize",
            'Cache-Control' => 'public, max-age=3600',
            'Expires' => gmdate('D, d M Y H:i:s', time() + 3600) . ' GMT',
        ], $headers));
    }

    /**
     * Stream file in optimized chunks
     */
    private function streamFileChunk(string $filePath, int $start, int $length): void
    {
        $handle = fopen($filePath, 'rb');
        
        if ($handle === false) {
            return;
        }

        fseek($handle, $start);
        $remaining = $length;

        while ($remaining > 0 && !feof($handle)) {
            $chunkSize = min(self::CHUNK_SIZE, $remaining);
            $chunk = fread($handle, $chunkSize);
            
            if ($chunk === false) {
                break;
            }

            echo $chunk;
            flush();
            
            $remaining -= strlen($chunk);
            
            // Prevent memory exhaustion
            if (ob_get_level()) {
                ob_flush();
            }
        }

        fclose($handle);
    }

    /**
     * Get cached video metadata
     */
    public function getVideoMetadata(string $filePath): array
    {
        return Cache::remember("video_metadata_{$filePath}", self::CACHE_TTL, function() use ($filePath) {
            $fullPath = Storage::disk('public')->path($filePath);
            
            if (!file_exists($fullPath)) {
                return [];
            }

            return [
                'size' => filesize($fullPath),
                'mime_type' => 'video/mp4',
                'last_modified' => filemtime($fullPath),
            ];
        });
    }

    /**
     * Generate optimized video thumbnail
     */
    public function generateThumbnail(string $filePath): ?string
    {
        $thumbnailKey = "video_thumbnail_{$filePath}";
        
        return Cache::remember($thumbnailKey, self::CACHE_TTL * 24, function() use ($filePath) {
            // Implementation would use FFmpeg or similar
            // For now, return a placeholder
            return null;
        });
    }

    /**
     * Check if user can access video (with caching)
     */
    public function canUserAccessVideo(int $userId, int $courseId): bool
    {
        $cacheKey = "user_video_access_{$userId}_{$courseId}";
        
        return Cache::remember($cacheKey, 1800, function() use ($userId, $courseId) {
            // Check enrollment or preview permissions
            $user = \App\Models\User::find($userId);
            $course = \App\Models\Course::find($courseId);
            
            if (!$user || !$course) {
                return false;
            }

            // Check if course is previewable
            if ($course->is_previewable) {
                return true;
            }

            // Check enrollment
            return $user->enrollments()
                ->where('course_id', $courseId)
                ->where('status', 'active')
                ->exists();
        });
    }
}
