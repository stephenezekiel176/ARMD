/**
 * Ultra-High Performance Video Loader with Poster-First Technique
 * Optimized for < 2.5s LCP and zero-lag video playback
 * 2025 Best Practices Implementation
 */

class LazyVideoLoader {
    constructor() {
        this.loadedVideos = new Set();
        this.loadingQueue = [];
        this.isProcessing = false;
        this.maxConcurrentLoads = 2;
        this.networkSpeed = this.detectNetworkSpeed();
        this.deviceCapability = this.detectDeviceCapability();
        
        this.init();
    }

    detectNetworkSpeed() {
        if ('connection' in navigator) {
            const conn = navigator.connection;
            if (conn.effectiveType === 'slow-2g' || conn.effectiveType === '2g') return 'slow';
            if (conn.effectiveType === '3g') return 'medium';
            return 'fast';
        }
        return 'fast';
    }

    detectDeviceCapability() {
        const memory = navigator.deviceMemory || 4;
        const cores = navigator.hardwareConcurrency || 4;
        
        if (memory >= 8 && cores >= 8) return 'high';
        if (memory >= 4 && cores >= 4) return 'medium';
        return 'low';
    }

    init() {
        // Preload hero poster with highest priority
        this.preloadHeroPoster();
        
        // Setup intersection observers with priority-based margins
        this.setupIntersectionObservers();
        
        // Initialize hero video immediately
        this.initializeHeroVideo();
        
        // Setup performance monitoring
        this.setupPerformanceMonitoring();
        
        // Setup page visibility handling
        this.setupVisibilityHandling();
    }

    preloadHeroPoster() {
        const heroContainer = document.querySelector('.video-bg.priority-0');
        if (heroContainer) {
            const poster = heroContainer.querySelector('.video-poster');
            if (poster && poster.src) {
                // Create preload link for hero poster
                const link = document.createElement('link');
                link.rel = 'preload';
                link.as = 'image';
                link.href = poster.src;
                link.fetchPriority = 'high';
                document.head.appendChild(link);
            }
        }
    }

    setupIntersectionObservers() {
        const priorities = [
            { selector: '.video-bg.priority-0', rootMargin: '200px', immediate: true },
            { selector: '.video-bg.priority-1', rootMargin: '400px', immediate: false },
            { selector: '.video-bg.priority-2', rootMargin: '800px', immediate: false },
            { selector: '.video-bg.priority-3', rootMargin: '800px', immediate: false }
        ];

        priorities.forEach(({ selector, rootMargin, immediate }) => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const container = entry.target;
                        const video = container.querySelector('.lazy-video');
                        if (video && !this.loadedVideos.has(video)) {
                            if (immediate) {
                                this.loadVideo(video, container);
                            } else {
                                this.queueVideo(video, container);
                            }
                        }
                    }
                });
            }, {
                rootMargin,
                threshold: 0.1
            });

            document.querySelectorAll(selector).forEach(container => {
                observer.observe(container);
            });
        });
    }

    initializeHeroVideo() {
        const heroContainer = document.querySelector('.video-bg.priority-0');
        if (heroContainer) {
            const video = heroContainer.querySelector('.lazy-video');
            if (video) {
                // Load hero video immediately after DOM ready
                setTimeout(() => this.loadVideo(video, heroContainer), 100);
            }
        }
    }

    queueVideo(video, container) {
        if (!this.loadingQueue.find(item => item.video === video)) {
            this.loadingQueue.push({ video, container });
            this.processQueue();
        }
    }

    async processQueue() {
        if (this.isProcessing || this.loadingQueue.length === 0) return;
        
        this.isProcessing = true;
        const currentLoads = [];
        
        // Process up to maxConcurrentLoads videos
        for (let i = 0; i < Math.min(this.maxConcurrentLoads, this.loadingQueue.length); i++) {
            const item = this.loadingQueue.shift();
            currentLoads.push(this.loadVideo(item.video, item.container));
        }
        
        await Promise.allSettled(currentLoads);
        
        // Small delay to prevent overwhelming the browser
        setTimeout(() => {
            this.isProcessing = false;
            this.processQueue();
        }, 300);
    }

    async loadVideo(video, container) {
        if (this.loadedVideos.has(video)) return;
        
        this.loadedVideos.add(video);
        const poster = container.querySelector('.video-poster');
        
        try {
            // Get optimal video source based on browser support
            const source = this.selectOptimalSource(video);
            if (!source) return;

            // Apply GPU acceleration
            this.enableGPUAcceleration(video);
            
            // Set up video attributes for optimal performance
            this.optimizeVideoAttributes(video);
            
            // Load video source
            source.src = source.dataset.src;
            source.removeAttribute('data-src');
            
            // Setup video event handlers
            this.setupVideoEvents(video, container, poster);
            
            // Start loading
            video.load();
            
        } catch (error) {
            console.warn('Video loading failed:', error);
            this.handleVideoError(video, container);
        }
    }

    selectOptimalSource(video) {
        const sources = video.querySelectorAll('source[data-src]');
        
        // Prefer AV1 for modern browsers, fallback to WebM, then MP4
        for (const source of sources) {
            const src = source.dataset.src;
            if (src.includes('.av1.mp4') && video.canPlayType('video/mp4; codecs="av01.0.08M.08"')) {
                return source;
            }
        }
        
        for (const source of sources) {
            const src = source.dataset.src;
            if (src.includes('.webm') && video.canPlayType('video/webm; codecs="vp9"')) {
                return source;
            }
        }
        
        // Fallback to MP4
        return sources[sources.length - 1];
    }

    enableGPUAcceleration(video) {
        video.style.transform = 'translateZ(0)';
        video.style.backfaceVisibility = 'hidden';
        video.style.perspective = '1000px';
        video.style.willChange = 'transform, opacity';
    }

    optimizeVideoAttributes(video) {
        video.muted = true;
        video.loop = true;
        video.playsInline = true;
        video.preload = 'metadata';
        video.setAttribute('playsinline', '');
        video.setAttribute('webkit-playsinline', '');
        
        // Adaptive quality based on device capability
        if (this.deviceCapability === 'low') {
            video.style.maxWidth = '720px';
            video.style.maxHeight = '480px';
        } else if (this.deviceCapability === 'medium') {
            video.style.maxWidth = '1280px';
            video.style.maxHeight = '720px';
        }
    }

    setupVideoEvents(video, container, poster) {
        let loadStartTime = performance.now();
        
        video.addEventListener('loadstart', () => {
            loadStartTime = performance.now();
        });

        video.addEventListener('canplaythrough', () => {
            const loadTime = performance.now() - loadStartTime;
            console.log(`Video loaded in ${loadTime}ms`);
            
            // Start seamless transition
            this.startVideoPlayback(video, container, poster);
        }, { once: true });

        video.addEventListener('playing', () => {
            // Fade out poster, fade in video
            this.transitionToPoster(video, poster, false);
        }, { once: true });

        video.addEventListener('error', () => {
            this.handleVideoError(video, container);
        });
    }

    async startVideoPlayback(video, container, poster) {
        try {
            // Ensure video is ready
            if (video.readyState >= 3) {
                video.classList.add('playing');
                await video.play();
            }
        } catch (error) {
            console.warn('Video autoplay prevented:', error);
            // Keep poster visible if autoplay fails
        }
    }

    transitionToPoster(video, poster, showPoster) {
        if (!poster) return;
        
        if (showPoster) {
            // Show poster, hide video
            poster.style.opacity = '1';
            video.style.opacity = '0';
        } else {
            // Show video, hide poster
            video.style.opacity = '1';
            poster.style.opacity = '0';
        }
    }

    handleVideoError(video, container) {
        const poster = container.querySelector('.video-poster');
        if (poster) {
            // Keep poster visible on error
            poster.style.opacity = '1';
            video.style.opacity = '0';
        }
        
        // Retry after delay
        setTimeout(() => {
            if (!video.src) {
                const source = video.querySelector('source[data-src]');
                if (source) {
                    source.src = source.dataset.src;
                    video.load();
                }
            }
        }, 2000);
    }

    setupPerformanceMonitoring() {
        // Monitor frame drops and adjust quality
        let frameDropCount = 0;
        
        const checkPerformance = () => {
            const videos = document.querySelectorAll('.lazy-video.playing');
            
            videos.forEach(video => {
                if (!video.paused) {
                    // Simple performance check based on video dimensions vs viewport
                    const rect = video.getBoundingClientRect();
                    const pixelRatio = window.devicePixelRatio || 1;
                    const actualPixels = rect.width * rect.height * pixelRatio;
                    
                    // If video is much larger than needed, reduce quality
                    if (actualPixels > 1920 * 1080 && this.deviceCapability === 'low') {
                        video.style.maxWidth = '720px';
                        video.style.maxHeight = '480px';
                    }
                }
            });
        };
        
        // Check performance every 5 seconds
        setInterval(checkPerformance, 5000);
    }

    setupVisibilityHandling() {
        document.addEventListener('visibilitychange', () => {
            const videos = document.querySelectorAll('.lazy-video');
            
            if (document.hidden) {
                // Pause all videos when page hidden
                videos.forEach(video => {
                    if (!video.paused) {
                        video.pause();
                        video.dataset.wasPlaying = 'true';
                    }
                });
            } else {
                // Resume videos when page visible
                videos.forEach(video => {
                    if (video.dataset.wasPlaying === 'true') {
                        video.play().catch(() => {});
                        delete video.dataset.wasPlaying;
                    }
                });
            }
        });
    }

    // Public method to manually load a video
    loadVideoById(videoName) {
        const container = document.querySelector(`[data-video-name="${videoName}"]`);
        if (container) {
            const video = container.querySelector('.lazy-video');
            if (video && !this.loadedVideos.has(video)) {
                this.loadVideo(video, container);
            }
        }
    }

    // Public method to get loading stats
    getStats() {
        return {
            loadedVideos: this.loadedVideos.size,
            queueLength: this.loadingQueue.length,
            networkSpeed: this.networkSpeed,
            deviceCapability: this.deviceCapability
        };
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.lazyVideoLoader = new LazyVideoLoader();
});

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = LazyVideoLoader;
}
