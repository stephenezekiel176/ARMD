# CDN URLs and Poster Images Configuration

## Required CDN Structure

Upload all optimized videos to your CDN (Bunny.net or Cloudflare R2) with the following structure:

```
https://your-cdn.b-cdn.net/videos/
├── hero.av1.mp4          (4-6 MB, AV1 codec)
├── hero.webm             (5-7 MB, VP9 codec)
├── analytics.av1.mp4     (2-4 MB)
├── analytics.webm        (3-5 MB)
├── metrics.av1.mp4       (2-4 MB)
├── metrics.webm          (3-5 MB)
├── completion.av1.mp4    (2-4 MB)
├── completion.webm       (3-5 MB)
├── engagement.av1.mp4    (2-4 MB)
├── engagement.webm       (3-5 MB)
├── forums.av1.mp4        (2-4 MB)
├── forums.webm           (3-5 MB)
├── chat.av1.mp4          (2-4 MB)
├── chat.webm             (3-5 MB)
├── sharing.av1.mp4       (2-4 MB)
├── sharing.webm          (3-5 MB)
├── projects.av1.mp4      (2-4 MB)
├── projects.webm         (3-5 MB)
├── management.av1.mp4    (2-4 MB)
├── management.webm       (3-5 MB)
├── monitoring.av1.mp4    (2-4 MB)
├── monitoring.webm       (3-5 MB)
├── assessment.av1.mp4    (2-4 MB)
└── assessment.webm       (3-5 MB)
```

## Required Poster Images

Create the following poster images in your `/public/posters/` directory:

### Standard Resolution Posters (AVIF format, < 150 KB each)
```
/public/posters/
├── hero.avif             (1920x1080, < 250 KB - hero gets larger budget)
├── hero.webp             (1920x1080, < 300 KB - WebP fallback)
├── hero-2x.avif          (3840x2160, < 500 KB - Retina displays)
├── analytics.avif        (800x450, < 150 KB)
├── analytics.webp        (800x450, < 180 KB)
├── analytics-2x.avif     (1600x900, < 250 KB)
├── metrics.avif          (800x450, < 150 KB)
├── metrics.webp          (800x450, < 180 KB)
├── metrics-2x.avif       (1600x900, < 250 KB)
├── completion.avif       (800x450, < 150 KB)
├── completion.webp       (800x450, < 180 KB)
├── completion-2x.avif    (1600x900, < 250 KB)
├── engagement.avif       (800x450, < 150 KB)
├── engagement.webp       (800x450, < 180 KB)
├── engagement-2x.avif    (1600x900, < 250 KB)
├── forums.avif           (800x450, < 150 KB)
├── forums.webp           (800x450, < 180 KB)
├── forums-2x.avif        (1600x900, < 250 KB)
├── chat.avif             (800x450, < 150 KB)
├── chat.webp             (800x450, < 180 KB)
├── chat-2x.avif          (1600x900, < 250 KB)
├── sharing.avif          (800x450, < 150 KB)
├── sharing.webp          (800x450, < 180 KB)
├── sharing-2x.avif       (1600x900, < 250 KB)
├── projects.avif         (800x450, < 150 KB)
├── projects.webp         (800x450, < 180 KB)
├── projects-2x.avif      (1600x900, < 250 KB)
├── management.avif       (800x450, < 150 KB)
├── management.webp       (800x450, < 180 KB)
├── management-2x.avif    (1600x900, < 250 KB)
├── monitoring.avif       (800x450, < 150 KB)
├── monitoring.webp       (800x450, < 180 KB)
├── monitoring-2x.avif    (1600x900, < 250 KB)
├── assessment.avif       (800x450, < 150 KB)
├── assessment.webp       (800x450, < 180 KB)
└── assessment-2x.avif    (1600x900, < 250 KB)
```

## Poster Generation Process

For each video, extract the most representative frame (usually frame 1 or a key moment):

### 1. Extract Frame from Video
```bash
# Extract frame 1 (or specific timestamp)
ffmpeg -i input_video.mp4 -vf "select=eq(n\,0)" -vsync vfr -q:v 2 frame.png

# Or extract at specific time (e.g., 2 seconds)
ffmpeg -i input_video.mp4 -ss 00:00:02 -vframes 1 -q:v 2 frame.png
```

### 2. Convert to Optimized Formats
```bash
# Convert to AVIF (best compression)
ffmpeg -i frame.png -c:v libaom-av1 -crf 35 -b:v 0 -pix_fmt yuv420p poster.avif

# Convert to WebP (fallback)
ffmpeg -i frame.png -c:v libwebp -quality 85 -preset picture poster.webp

# Create 2x version for retina displays
ffmpeg -i frame.png -vf "scale=1600:900" -c:v libaom-av1 -crf 38 -b:v 0 poster-2x.avif
```

## CDN Configuration Requirements

### Bunny.net Settings
```
- Enable Gzip/Brotli compression
- Set Cache TTL to 31536000 (1 year)
- Enable HTTP/2 and HTTP/3
- Configure CORS headers:
  Access-Control-Allow-Origin: *
  Access-Control-Allow-Methods: GET, HEAD
```

### Cloudflare R2 + CDN Settings
```
- Enable Auto-minify for images
- Set Browser Cache TTL: 1 year
- Set Edge Cache TTL: 1 month
- Enable Polish (image optimization)
- Configure Custom Rules for video files:
  Cache Level: Cache Everything
  Edge Cache TTL: 1 month
```

## Video Mapping Configuration

Update your Laravel configuration to use CDN URLs:

### config/app.php
```php
'cdn_url' => env('CDN_URL', 'https://your-cdn.b-cdn.net'),
'video_cdn_url' => env('VIDEO_CDN_URL', 'https://your-cdn.b-cdn.net/videos'),
```

### .env
```
CDN_URL=https://your-cdn.b-cdn.net
VIDEO_CDN_URL=https://your-cdn.b-cdn.net/videos
```

## Performance Validation Checklist

After uploading all assets, verify:

- [ ] Hero poster loads in < 200ms (preloaded)
- [ ] All poster images are < 150 KB (except hero)
- [ ] Videos are served with proper MIME types
- [ ] CDN returns proper cache headers
- [ ] AVIF format is served to supporting browsers
- [ ] WebP fallback works for older browsers
- [ ] All videos have keyframes every 240 frames
- [ ] Video bitrates are optimized for target file sizes

## Dominant Color Extraction (Optional Enhancement)

For instant background colors while posters load:

```bash
# Extract dominant color from poster
convert poster.avif -resize 1x1! -format "%[pixel:u]" info:
```

Add to HTML as:
```html
<div class="video-bg" data-dominant-color="#f1f5f9" style="--dominant-color: #f1f5f9;">
```

## Testing Commands

### Test CDN Response Times
```bash
curl -w "@curl-format.txt" -o /dev/null -s "https://your-cdn.b-cdn.net/posters/hero.avif"
```

### Validate Image Compression
```bash
# Check file sizes
ls -lah /public/posters/*.avif
ls -lah /public/posters/*.webp
```

### Test Video Streaming
```bash
# Test range requests (important for video streaming)
curl -H "Range: bytes=0-1023" "https://your-cdn.b-cdn.net/videos/hero.av1.mp4"
```

## Expected Performance Metrics

After implementation:
- **LCP (Largest Contentful Paint)**: < 2.5s
- **Hero poster load**: < 200ms
- **First video play**: < 500ms after intersection
- **Subsequent videos**: < 300ms after intersection
- **Memory usage**: < 100MB for all videos combined
- **CPU usage**: < 30% during peak playback

## Monitoring and Analytics

Track these metrics in your analytics:
- Video load times by device type
- Poster vs video load success rates
- Network speed impact on loading
- Browser compatibility for AVIF/WebM
- CDN cache hit rates
- Bandwidth usage per user session
