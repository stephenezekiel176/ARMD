# FFmpeg Video Optimization Commands
## 2025 Best Practices for High-Performance Web Videos

### Prerequisites

Ensure you have FFmpeg with the following encoders:
```bash
# Check available encoders
ffmpeg -encoders | grep -E "(av1|vp9|x264)"

# Required encoders:
# - libaom-av1 (for AV1)
# - libvpx-vp9 (for VP9/WebM)
# - libx264 (for H.264/MP4 fallback)
```

## Hero Video Optimization (Priority 0)
**Target: 4-6 MB, instant playback**

### AV1 Version (Best compression, modern browsers)
```bash
ffmpeg -i resource_data.mp4 \
  -c:v libaom-av1 \
  -crf 35 \
  -b:v 0 \
  -cpu-used 4 \
  -row-mt 1 \
  -tiles 2x2 \
  -g 240 \
  -keyint_min 240 \
  -sc_threshold 0 \
  -pix_fmt yuv420p \
  -movflags +faststart \
  -vf "scale=1920:1080:force_original_aspect_ratio=decrease,pad=1920:1080:(ow-iw)/2:(oh-ih)/2" \
  -an \
  hero.av1.mp4
```

### WebM Version (VP9, broader compatibility)
```bash
ffmpeg -i resource_data.mp4 \
  -c:v libvpx-vp9 \
  -crf 35 \
  -b:v 0 \
  -cpu-used 2 \
  -row-mt 1 \
  -tile-columns 2 \
  -tile-rows 1 \
  -g 240 \
  -keyint_min 240 \
  -pix_fmt yuv420p \
  -vf "scale=1920:1080:force_original_aspect_ratio=decrease,pad=1920:1080:(ow-iw)/2:(oh-ih)/2" \
  -an \
  hero.webm
```

### MP4 Fallback (H.264, universal compatibility)
```bash
ffmpeg -i resource_data.mp4 \
  -c:v libx264 \
  -crf 28 \
  -preset medium \
  -profile:v main \
  -level 4.0 \
  -g 240 \
  -keyint_min 240 \
  -sc_threshold 0 \
  -pix_fmt yuv420p \
  -movflags +faststart \
  -vf "scale=1920:1080:force_original_aspect_ratio=decrease,pad=1920:1080:(ow-iw)/2:(oh-ih)/2" \
  -an \
  hero.mp4
```

## Section Videos Optimization (Priority 1-3)
**Target: 2-4 MB each, smooth playback**

### Batch Processing Script for All Section Videos
```bash
#!/bin/bash

# Array of video files to process
videos=(
  "real-time_analytics.mp4:analytics"
  "performance_metrics.mp4:metrics"
  "completion_rates.mp4:completion"
  "engagement_tracking.mp4:engagement"
  "forum_discussions.mp4:forums"
  "Live_chat.mp4:chat"
  "file_sharing.mp4:sharing"
  "group_projects.mp4:projects"
  "centralized_management.mp4:management"
  "real_time_progress_monitoring.mp4:monitoring"
  "interactive_assessment.mp4:assessment"
)

# Process each video
for video_info in "${videos[@]}"; do
  IFS=':' read -r input_file output_name <<< "$video_info"
  
  echo "Processing $input_file -> $output_name"
  
  # AV1 version
  ffmpeg -i "$input_file" \
    -c:v libaom-av1 \
    -crf 38 \
    -b:v 0 \
    -cpu-used 6 \
    -row-mt 1 \
    -tiles 1x1 \
    -g 240 \
    -keyint_min 240 \
    -sc_threshold 0 \
    -pix_fmt yuv420p \
    -movflags +faststart \
    -vf "scale=1280:720:force_original_aspect_ratio=decrease,pad=1280:720:(ow-iw)/2:(oh-ih)/2" \
    -an \
    -y "${output_name}.av1.mp4"
  
  # WebM version
  ffmpeg -i "$input_file" \
    -c:v libvpx-vp9 \
    -crf 38 \
    -b:v 0 \
    -cpu-used 3 \
    -row-mt 1 \
    -tile-columns 1 \
    -tile-rows 0 \
    -g 240 \
    -keyint_min 240 \
    -pix_fmt yuv420p \
    -vf "scale=1280:720:force_original_aspect_ratio=decrease,pad=1280:720:(ow-iw)/2:(oh-ih)/2" \
    -an \
    -y "${output_name}.webm"
  
  echo "Completed $output_name"
done
```

## Mobile-Optimized Versions (Optional)
**For devices with limited bandwidth**

### Low-Resolution Versions (480p)
```bash
# Template for mobile versions
ffmpeg -i input.mp4 \
  -c:v libx264 \
  -crf 32 \
  -preset fast \
  -profile:v baseline \
  -level 3.0 \
  -g 120 \
  -keyint_min 120 \
  -pix_fmt yuv420p \
  -movflags +faststart \
  -vf "scale=854:480:force_original_aspect_ratio=decrease,pad=854:480:(ow-iw)/2:(oh-ih)/2" \
  -an \
  output_mobile.mp4
```

## Quality Control and Validation

### Check Video Properties
```bash
# Analyze encoded video
ffprobe -v quiet -print_format json -show_format -show_streams video.mp4

# Check file size
ls -lah *.mp4 *.webm

# Verify keyframe intervals
ffprobe -v quiet -select_streams v:0 -show_entries packet=flags -of csv=p=0 video.mp4 | grep -n K
```

### Bitrate Analysis
```bash
# Calculate average bitrate
ffprobe -v quiet -select_streams v:0 -show_entries format=bit_rate -of default=noprint_wrappers=1:nokey=1 video.mp4
```

## Advanced Optimization Parameters

### For High-Motion Content
```bash
# Increase bitrate for complex scenes
-crf 32 -maxrate 2M -bufsize 4M
```

### For Static/Presentation Content
```bash
# Lower bitrate for simple content
-crf 42 -cpu-used 8
```

### For Ultra-Fast Encoding (Development)
```bash
# Quick encoding for testing
-c:v libx264 -preset ultrafast -crf 28
```

## Poster Frame Extraction

### Extract Optimal Poster Frames
```bash
#!/bin/bash

# Extract poster from each video
videos=(
  "hero.av1.mp4"
  "analytics.av1.mp4"
  "metrics.av1.mp4"
  "completion.av1.mp4"
  "engagement.av1.mp4"
  "forums.av1.mp4"
  "chat.av1.mp4"
  "sharing.av1.mp4"
  "projects.av1.mp4"
  "management.av1.mp4"
  "monitoring.av1.mp4"
  "assessment.av1.mp4"
)

for video in "${videos[@]}"; do
  name=$(basename "$video" .av1.mp4)
  
  # Extract frame at 1 second (usually good representative frame)
  ffmpeg -i "$video" -ss 00:00:01 -vframes 1 -q:v 2 "${name}_temp.png"
  
  # Convert to AVIF (best compression)
  ffmpeg -i "${name}_temp.png" \
    -c:v libaom-av1 \
    -crf 35 \
    -b:v 0 \
    -pix_fmt yuv420p \
    "${name}.avif"
  
  # Convert to WebP (fallback)
  ffmpeg -i "${name}_temp.png" \
    -c:v libwebp \
    -quality 85 \
    -preset picture \
    "${name}.webp"
  
  # Create 2x version for retina
  ffmpeg -i "${name}_temp.png" \
    -vf "scale=1600:900" \
    -c:v libaom-av1 \
    -crf 38 \
    -b:v 0 \
    -pix_fmt yuv420p \
    "${name}-2x.avif"
  
  # Clean up temp file
  rm "${name}_temp.png"
  
  echo "Generated posters for $name"
done
```

## Performance Validation Commands

### Test Streaming Performance
```bash
# Simulate network conditions
ffmpeg -re -i video.mp4 -c copy -f mpegts udp://localhost:1234

# Test seek performance
ffmpeg -ss 00:00:30 -i video.mp4 -t 5 -c copy test_seek.mp4
```

### Measure Encoding Efficiency
```bash
# Compare file sizes and quality
for file in *.mp4; do
  echo "File: $file"
  echo "Size: $(du -h "$file" | cut -f1)"
  echo "Bitrate: $(ffprobe -v quiet -select_streams v:0 -show_entries format=bit_rate -of default=noprint_wrappers=1:nokey=1 "$file") bps"
  echo "---"
done
```

## Automation Script for Complete Pipeline

### Complete Processing Pipeline
```bash
#!/bin/bash

# Complete video optimization pipeline
process_video() {
  local input="$1"
  local output_name="$2"
  local priority="$3"
  
  echo "Processing $input with priority $priority"
  
  # Set quality based on priority
  case $priority in
    0) # Hero video
      crf_av1=35
      crf_vp9=35
      scale="1920:1080"
      ;;
    1) # High priority sections
      crf_av1=37
      crf_vp9=37
      scale="1280:720"
      ;;
    *) # Lower priority
      crf_av1=40
      crf_vp9=40
      scale="1280:720"
      ;;
  esac
  
  # AV1 encoding
  ffmpeg -i "$input" \
    -c:v libaom-av1 \
    -crf $crf_av1 \
    -b:v 0 \
    -cpu-used 4 \
    -row-mt 1 \
    -tiles 2x2 \
    -g 240 \
    -keyint_min 240 \
    -sc_threshold 0 \
    -pix_fmt yuv420p \
    -movflags +faststart \
    -vf "scale=${scale}:force_original_aspect_ratio=decrease,pad=${scale}:(ow-iw)/2:(oh-ih)/2" \
    -an \
    -y "${output_name}.av1.mp4"
  
  # WebM encoding
  ffmpeg -i "$input" \
    -c:v libvpx-vp9 \
    -crf $crf_vp9 \
    -b:v 0 \
    -cpu-used 2 \
    -row-mt 1 \
    -tile-columns 2 \
    -tile-rows 1 \
    -g 240 \
    -keyint_min 240 \
    -pix_fmt yuv420p \
    -vf "scale=${scale}:force_original_aspect_ratio=decrease,pad=${scale}:(ow-iw)/2:(oh-ih)/2" \
    -an \
    -y "${output_name}.webm"
  
  # Generate poster
  ffmpeg -i "${output_name}.av1.mp4" -ss 00:00:01 -vframes 1 -q:v 2 "${output_name}_temp.png"
  
  ffmpeg -i "${output_name}_temp.png" \
    -c:v libaom-av1 -crf 35 -b:v 0 -pix_fmt yuv420p \
    "${output_name}.avif"
  
  ffmpeg -i "${output_name}_temp.png" \
    -c:v libwebp -quality 85 -preset picture \
    "${output_name}.webp"
  
  ffmpeg -i "${output_name}_temp.png" \
    -vf "scale=1600:900" \
    -c:v libaom-av1 -crf 38 -b:v 0 -pix_fmt yuv420p \
    "${output_name}-2x.avif"
  
  rm "${output_name}_temp.png"
  
  echo "Completed $output_name"
}

# Process all videos
process_video "resource_data.mp4" "hero" 0
process_video "real-time_analytics.mp4" "analytics" 1
process_video "performance_metrics.mp4" "metrics" 1
process_video "completion_rates.mp4" "completion" 1
process_video "engagement_tracking.mp4" "engagement" 1
process_video "forum_discussions.mp4" "forums" 2
process_video "Live_chat.mp4" "chat" 2
process_video "file_sharing.mp4" "sharing" 2
process_video "group_projects.mp4" "projects" 2
process_video "centralized_management.mp4" "management" 2
process_video "real_time_progress_monitoring.mp4" "monitoring" 2
process_video "interactive_assessment.mp4" "assessment" 3

echo "All videos processed successfully!"
```

## Expected Output Sizes

After optimization, expect these file sizes:
- **Hero video**: 4-6 MB (AV1), 5-7 MB (WebM)
- **Priority 1 videos**: 2-4 MB (AV1), 3-5 MB (WebM)
- **Priority 2+ videos**: 1.5-3 MB (AV1), 2-4 MB (WebM)
- **Poster images**: 50-150 KB (AVIF), 80-200 KB (WebP)

## Troubleshooting

### Common Issues and Solutions

1. **AV1 encoding too slow**:
   ```bash
   # Use faster preset
   -cpu-used 8 -row-mt 1 -tiles 4x4
   ```

2. **File size too large**:
   ```bash
   # Increase CRF value
   -crf 42
   ```

3. **Quality too low**:
   ```bash
   # Decrease CRF value
   -crf 32
   ```

4. **Keyframe issues**:
   ```bash
   # Force keyframe interval
   -g 240 -keyint_min 240 -sc_threshold 0
   ```
