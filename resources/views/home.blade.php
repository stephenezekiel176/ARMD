@extends('layouts.app')

@section('content')
@include('partials.vanta-bg')

<!-- Preload Critical Resources -->
<link rel="preload" href="/posters/hero.webp" as="image" fetchpriority="high">
<link rel="preload" href="{{ asset('css/video-performance.css') }}" as="style">
<link rel="preload" href="{{ asset('js/lazy-video-loader.js') }}" as="script">

<!-- Performance CSS -->
<link rel="stylesheet" href="{{ asset('css/video-performance.css') }}">

<style>
/* Override the main container for home page to fill full screen */
.main-content > div {
    max-width: none !important;
    padding-left: 0 !important;
    padding-right: 0 !important;
}

/* Prevent horizontal scroll */
body {
    overflow-x: hidden !important;
}

.main-content {
    overflow-x: hidden !important;
}

/* Custom aesthetic dividers */
.wave-divider {
    position: absolute;
    bottom: -1px;
    left: 0;
    width: 100%;
    overflow: hidden;
    line-height: 0;
    z-index: 1;
}

.wave-divider svg {
    position: relative;
    display: block;
    width: 100%;
    height: 120px;
    vertical-align: bottom;
}

/* Smooth color transitions for divider paths */
.wave-divider path {
    transition: all 0.5s ease-in-out;
}

/* Smooth transitions between sections */
section {
    position: relative;
    overflow: hidden;
}

/* Prevent any white space between sections */
section + section {
    border-top: none;
}

/* Optional subtle animation */
@keyframes gentle-wave {
    0%, 100% { 
        transform: translateX(0) translateY(0);
    }
    50% { 
        transform: translateX(-10px) translateY(-5px);
    }
}

.wave-divider svg path:first-child {
    animation: gentle-wave 15s ease-in-out infinite;
}

.wave-divider svg path:nth-child(2) {
    animation: gentle-wave 12s ease-in-out infinite reverse;
}

.wave-divider svg path:nth-child(3) {
    animation: gentle-wave 18s ease-in-out infinite;
}

/* 3D Floating Elements - Enhanced Movement */
@keyframes float {
    0%, 100% { 
        transform: translateY(0) translateX(0) rotateX(0deg) rotateY(0deg) scale(1);
    }
    25% {
        transform: translateY(-50px) translateX(30px) rotateX(15deg) rotateY(15deg) scale(1.05);
    }
    50% { 
        transform: translateY(-25px) translateX(-40px) rotateX(-12deg) rotateY(-12deg) scale(0.95);
    }
    75% { 
        transform: translateY(-60px) translateX(25px) rotateX(10deg) rotateY(-10deg) scale(1.08);
    }
}

@keyframes float-reverse {
    0%, 100% { 
        transform: translateY(0) translateX(0) rotateX(0deg) rotateY(0deg) scale(1);
    }
    25% {
        transform: translateY(-40px) translateX(-35px) rotateX(-15deg) rotateY(-15deg) scale(1.06);
    }
    50% { 
        transform: translateY(-70px) translateX(30px) rotateX(12deg) rotateY(12deg) scale(0.94);
    }
    75% {
        transform: translateY(-20px) translateX(-25px) rotateX(-10deg) rotateY(10deg) scale(1.07);
    }
}

@keyframes rotate3d {
    0% { 
        transform: rotateX(0deg) rotateY(0deg) rotateZ(0deg);
    }
    100% { 
        transform: rotateX(360deg) rotateY(360deg) rotateZ(360deg);
    }
}

@keyframes pulse-glow {
    0%, 100% { 
        opacity: 0.5;
        transform: scale(1);
    }
    50% { 
        opacity: 0.9;
        transform: scale(1.15);
    }
}

.floating-element {
    position: absolute;
    opacity: 0.35;
    animation: float 15s ease-in-out infinite;
    filter: drop-shadow(0 12px 35px rgba(59, 130, 246, 0.4));
    transform-style: preserve-3d;
    transition: all 0.3s ease;
}

.floating-element:hover {
    opacity: 0.65;
    transform: scale(1.2);
}

.floating-element-reverse {
    animation: float-reverse 13s ease-in-out infinite;
}

.gradient-mesh {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    opacity: 0.3;
    animation: pulse-glow 8s ease-in-out infinite;
}

.digital-icon {
    position: absolute;
    opacity: 0.08;
    animation: float 15s ease-in-out infinite;
    perspective: 1000px;
}

.rotate-3d {
    animation: rotate3d 40s linear infinite;
    transform-style: preserve-3d;
}

/* Ultra-Optimized Video Performance Styles */
.lazy-video {
    /* GPU acceleration and hardware optimization */
    transform: translateZ(0);
    backface-visibility: hidden;
    perspective: 1000px;
    will-change: transform, opacity;
    
    /* Smooth rendering optimizations */
    image-rendering: optimizeSpeed;
    image-rendering: -webkit-optimize-contrast;
    image-rendering: optimize-contrast;
    
    /* Prevent layout shifts */
    object-fit: cover;
    object-position: center;
    
    /* Smooth transitions */
    transition: opacity 0.3s ease, filter 0.3s ease;
    
    /* Initial state */
    opacity: 0;
    filter: blur(2px);
}

.lazy-video.loaded {
    opacity: 1;
    filter: none;
}

.lazy-video.quarter-buffered {
    opacity: 0.9;
    filter: blur(1px);
}

.lazy-video.half-buffered {
    opacity: 0.95;
    filter: blur(0.5px);
}

.lazy-video.well-buffered {
    opacity: 1;
    filter: none;
    transform: translateZ(0) scale(1);
}

/* Optimize video containers */
.aspect-video {
    /* Prevent reflow during video loading */
    contain: layout style paint;
    
    /* GPU layer promotion */
    transform: translateZ(0);
    
    /* Smooth container transitions */
    transition: transform 0.3s ease;
}

.aspect-video:hover {
    transform: translateZ(0) scale(1.02);
}

/* Performance optimizations for video sections */
section:has(.lazy-video) {
    /* Contain layout changes */
    contain: layout;
    
    /* Optimize repaints */
    will-change: transform;
}

/* Reduce motion for users who prefer it */
@media (prefers-reduced-motion: reduce) {
    .lazy-video {
        transition: opacity 0.1s ease;
        filter: none !important;
    }
    
    .aspect-video:hover {
        transform: none;
    }
}

/* High refresh rate display optimizations */
@media (min-resolution: 120dpi) {
    .lazy-video {
        image-rendering: -webkit-optimize-contrast;
        image-rendering: crisp-edges;
    }
}

/* Mobile optimizations */
@media (max-width: 768px) {
    .lazy-video {
        /* Reduce quality on mobile for better performance */
        image-rendering: optimizeSpeed;
        
        /* Smaller max dimensions */
        max-width: 100vw;
        max-height: 50vh;
    }
}

/* Low-end device optimizations */
@media (max-width: 480px) {
    .lazy-video {
        /* Further optimize for low-end devices */
        will-change: auto;
        transform: none;
        filter: none !important;
        transition: opacity 0.1s ease;
    }
}
</style>

<!-- Hero Section -->
    <section class="relative min-h-screen flex flex-col items-center justify-center bg-white overflow-hidden pb-20 pt-4">
        <!-- Animated Gradient Mesh Background -->
        <div class="gradient-mesh bg-gradient-to-br from-blue-400 to-purple-500" style="width: 600px; height: 600px; top: -200px; left: -200px; animation-delay: 0s;"></div>
        <div class="gradient-mesh bg-gradient-to-br from-cyan-400 to-blue-500" style="width: 500px; height: 500px; bottom: -150px; right: -150px; animation-delay: 2s;"></div>
        <div class="gradient-mesh bg-gradient-to-br from-indigo-400 to-pink-400" style="width: 400px; height: 400px; top: 50%; right: 10%; animation-delay: 4s;"></div>
        
        <!-- Floating 3D Digital Product Icons -->
        <!-- Book Icon -->
        <div class="floating-element" style="top: 15%; left: 8%; animation-delay: 0s;">
            <svg class="w-32 h-32 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2L4 5v6.09c0 5.05 3.41 9.76 8 10.91 4.59-1.15 8-5.86 8-10.91V5l-8-3zm6 9.09c0 4-2.55 7.7-6 8.83-3.45-1.13-6-4.82-6-8.83V6.31l6-2.12 6 2.12v4.78z"/>
                <path d="M9 8h6v2H9zm0 3h6v2H9zm0 3h4v2H9z"/>
            </svg>
        </div>
        
        <!-- Video Icon -->
        <div class="floating-element floating-element-reverse" style="top: 25%; right: 12%; animation-delay: 1.5s;">
            <svg class="w-28 h-28 text-purple-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z"/>
            </svg>
        </div>
        
        <!-- Document Icon -->
        <div class="floating-element" style="bottom: 30%; left: 5%; animation-delay: 3s;">
            <svg class="w-24 h-24 text-cyan-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
            </svg>
        </div>
        
        <!-- Audio Icon -->
        <div class="floating-element floating-element-reverse" style="top: 60%; right: 8%; animation-delay: 2.5s;">
            <svg class="w-26 h-26 text-pink-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 3v9.28c-.47-.17-.97-.28-1.5-.28C8.01 12 6 14.01 6 16.5S8.01 21 10.5 21c2.31 0 4.2-1.75 4.45-4H15V6h4V3h-7z"/>
            </svg>
        </div>
        
        <!-- Code Icon -->
        <div class="floating-element" style="bottom: 20%; right: 15%; animation-delay: 4s;">
            <svg class="w-30 h-30 text-indigo-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M9.4 16.6L4.8 12l4.6-4.6L8 6l-6 6 6 6 1.4-1.4zm5.2 0l4.6-4.6-4.6-4.6L16 6l6 6-6 6-1.4-1.4z"/>
            </svg>
        </div>
        
        <!-- Database Icon -->
        <div class="floating-element floating-element-reverse" style="top: 40%; left: 10%; animation-delay: 1s;">
            <svg class="w-28 h-28 text-emerald-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 3C7.58 3 4 4.79 4 7s3.58 4 8 4 8-1.79 8-4-3.58-4-8-4zm8 6c0 2.21-3.58 4-8 4s-8-1.79-8-4v3c0 2.21 3.58 4 8 4s8-1.79 8-4V9zm0 5c0 2.21-3.58 4-8 4s-8-1.79-8-4v3c0 2.21 3.58 4 8 4s8-1.79 8-4v-3z"/>
            </svg>
        </div>
        
        <!-- Chart Icon -->
        <div class="floating-element" style="top: 70%; left: 15%; animation-delay: 3.5s;">
            <svg class="w-24 h-24 text-amber-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
            </svg>
        </div>
        
        <!-- Certificate Icon -->
        <div class="floating-element floating-element-reverse" style="bottom: 35%; right: 20%; animation-delay: 2s;">
            <svg class="w-26 h-26 text-rose-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-1 9h-4v4h-2v-4H9V9h4V5h2v4h4v2z"/>
            </svg>
        </div>
        
        <!-- 3D Rotating Geometric Shapes -->
        <div class="digital-icon rotate-3d" style="top: 10%; right: 25%; width: 100px; height: 100px;">
            <div style="width: 100%; height: 100%; background: linear-gradient(135deg, rgba(59, 130, 246, 0.3), rgba(147, 51, 234, 0.3)); transform: rotateX(45deg) rotateY(45deg); border-radius: 20px;"></div>
        </div>
        
        <div class="digital-icon rotate-3d" style="bottom: 25%; left: 20%; width: 80px; height: 80px; animation-delay: 5s;">
            <div style="width: 100%; height: 100%; background: linear-gradient(135deg, rgba(236, 72, 153, 0.3), rgba(59, 130, 246, 0.3)); transform: rotateX(30deg) rotateZ(30deg); clip-path: polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%);"></div>
        </div>
        
        <!-- Hero Content Card with Rounded Background -->
        <div class="relative z-10 text-center px-4 md:px-8 lg:px-12 max-w-[90%] mb-0 w-full">
            <div class="bg-white/30 backdrop-blur-xl rounded-[15px] px-12 md:px-16 lg:px-24 py-4 md:py-5 lg:py-6 shadow-2xl border border-white/50">
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold text-gray-900 mb-2 tracking-tight">
                    Atommart Resource Management
                    <span class="block text-3xl md:text-4xl lg:text-5xl font-light text-blue-600 mt-1">Database</span>
                </h1>
                <p class="text-lg md:text-xl lg:text-2xl text-gray-700 mb-4 max-w-4xl mx-auto leading-relaxed">
                    Enabling your ability to learn and empowering your learning journey with comprehensive resources, analytics, and seamless management tools with our corporate-driven, and principle-based management resources refined to aid your corporate goals.
                </p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    @guest
                        <a href="{{ route('register') }}" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white text-base md:text-lg font-semibold rounded-lg transition-all duration-300 transform hover:scale-105 hover:shadow-xl">
                            Get Started
                        </a>
                    @else
                        <a href="{{ route('resources.index') }}" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white text-base md:text-lg font-semibold rounded-lg transition-all duration-300 transform hover:scale-105 hover:shadow-xl">
                            Browse Resources
                        </a>
                    @endguest
                    <a href="#features" class="px-8 py-3 border-2 border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white text-base md:text-lg font-semibold rounded-lg transition-all duration-300">
                        Learn More
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Background Video Frame Beneath Hero -->
        <div class="relative z-10 flex justify-center px-4 md:px-8 lg:px-12 mb-12 mt-8">
            <div class="max-w-4xl w-full bg-white/20 backdrop-blur-sm rounded-3xl p-4 shadow-2xl border-2 border-white/50">
                <div class="rounded-2xl overflow-hidden shadow-xl">
                    <div class="video-bg priority-0 aspect-video" data-video-name="hero">
                        <img 
                            src="/posters/hero.webp" 
                            srcset="/posters/hero.webp 1x"
                            alt="" 
                            class="video-poster"
                            loading="eager"
                            fetchpriority="high"
                        >
                        <video
                            class="lazy-video"
                            muted
                            loop
                            playsinline
                            preload="none"
                            poster="/posters/hero.webp"
                        >
                            <source data-src="https://res.cloudinary.com/dy99e0hzr/video/upload/v1763052749/01-hero_asq1w4.webm" type="video/webm">
                            <source data-src="{{ config('app.cdn_url', asset('')) }}resource_data.mp4" type="video/mp4">
                        </video>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce z-20">
            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
        
        <!-- Two-Sided Organic Flowing Waves -->
        <div class="wave-divider" style="position: relative; z-index: 5;">
            <svg viewBox="0 0 1440 120" preserveAspectRatio="none" class="w-full" style="display: block; animation-play-state: running;">
                <!-- Right side curves -->
                <path d="M0,60 C240,100 480,20 720,60 C960,100 1200,20 1440,60 L1440,120 L0,120 Z" 
                      class="fill-white" style="opacity: 0.3; animation-play-state: running; display: block;"></path>
                <path d="M0,80 C360,40 720,100 1080,60 C1260,40 1380,80 1440,70 L1440,120 L0,120 Z" 
                      class="fill-white" style="animation-play-state: running; display: block;"></path>
                <!-- Left side curves (mirrored) -->
                <path d="M0,70 C120,100 240,50 360,80 C480,110 600,40 720,75 L0,75 Z" 
                      class="fill-white" style="opacity: 0.2; animation-play-state: running; display: block;"></path>
                <path d="M0,50 C180,20 360,90 540,55 C660,35 780,70 900,60 L0,60 Z" 
                      class="fill-white" style="opacity: 0.15; animation-play-state: running; display: block;"></path>
            </svg>
        </div>
    </section>

    <!-- Features Carousel -->
    <section id="features" class="relative py-20 bg-white" style="margin-top: -1px;">
        <!-- Subtle Background Pattern -->
        <div class="absolute inset-0 opacity-5">
            <svg width="100%" height="100%">
                <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                    <circle cx="20" cy="20" r="1" fill="currentColor" class="text-blue-500"/>
                </pattern>
                <rect width="100%" height="100%" fill="url(#grid)"/>
            </svg>
        </div>
        
        <!-- Floating Orbs -->
        <div class="gradient-mesh bg-gradient-to-br from-blue-300 to-cyan-300" style="width: 300px; height: 300px; top: 10%; left: 5%; animation-delay: 1s;"></div>
        <div class="gradient-mesh bg-gradient-to-br from-purple-300 to-pink-300" style="width: 250px; height: 250px; bottom: 10%; right: 8%; animation-delay: 3s;"></div>
        
        <!-- Floating Digital Product Icons -->
        <div class="floating-element" style="top: 8%; right: 8%; animation-delay: 0.5s;">
            <svg class="w-20 h-20 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
            </svg>
        </div>
        
        <div class="floating-element floating-element-reverse" style="bottom: 15%; left: 8%; animation-delay: 2s;">
            <svg class="w-24 h-24 text-purple-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20 6h-2.18c.11-.31.18-.65.18-1 0-1.66-1.34-3-3-3-1.05 0-1.96.54-2.5 1.35l-.5.67-.5-.68C10.96 2.54 10.05 2 9 2 7.34 2 6 3.34 6 5c0 .35.07.69.18 1H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-5-2c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zM9 4c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm11 15H4v-2h16v2zm0-5H4V8h5.08L7 10.83 8.62 12 11 8.76l1-1.36 1 1.36L15.38 12 17 10.83 14.92 8H20v6z"/>
            </svg>
        </div>
        
        <div class="floating-element" style="top: 45%; right: 12%; animation-delay: 3.5s;">
            <svg class="w-22 h-22 text-cyan-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/>
            </svg>
        </div>
        
        <div class="floating-element floating-element-reverse" style="top: 25%; left: 10%; animation-delay: 1.8s;">
            <svg class="w-26 h-26 text-green-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
        </div>
        
        <div class="floating-element" style="bottom: 30%; right: 18%; animation-delay: 4.2s;">
            <svg class="w-24 h-24 text-pink-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
            </svg>
        </div>
        
        <div class="floating-element floating-element-reverse" style="top: 68%; right: 22%; animation-delay: 5s;">
            <svg class="w-20 h-20 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
            </svg>
        </div>
        
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Powerful Learning Management Tools
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Everything you need to manage, track, and enhance your learning ecosystem
                </p>
            </div>

            <!-- Interactive Feature Slider -->
            <div class="relative" id="carousel-container">
                
                <!-- Slide Navigation -->
                <div class="flex justify-center mb-8 space-x-2" id="carousel-dots">
                    <button onclick="goToSlide(0)" id="dot-0"
                            class="h-2 w-8 bg-blue-600 rounded-full transition-all duration-300"></button>
                    <button onclick="goToSlide(1)" id="dot-1"
                            class="h-2 w-2 bg-gray-300 rounded-full transition-all duration-300"></button>
                </div>

                <!-- Slide Content -->
                <div class="relative overflow-hidden rounded-2xl" style="min-height: 600px;">
                    <!-- Slide 1: Progress Tracking -->
                    <div class="absolute inset-0 px-4 slide-content" id="slide-0" data-direction="fade-in">
                            <div class="bg-white/30 backdrop-blur-xl rounded-2xl shadow-lg p-8 md:p-12 border border-white/50">
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                    <!-- Feature Items Grid -->
                                    <div class="grid grid-cols-2 gap-6">
                                        <!-- Real-time Analytics -->
                                        <div class="text-center">
                                            <div class="video-bg priority-1 aspect-video rounded-lg overflow-hidden mb-3" data-video-name="analytics">
                                                <img 
                                                    src="/posters/analytics.webp" 
                                                    srcset="/posters/analytics.webp 1x"
                                                    alt="" 
                                                    class="video-poster"
                                                    loading="lazy"
                                                >
                                                <video class="lazy-video" muted loop playsinline preload="none">
                                                    <source data-src="https://res.cloudinary.com/dy99e0hzr/video/upload/v1763052768/02-analytics_rr0dkt.webm" type="video/webm">
                                                    <source data-src="{{ asset('real-time_analytics.mp4') }}" type="video/mp4">
                                                </video>
                                            </div>
                                            <p class="text-sm font-medium text-gray-700">Real-time Analytics</p>
                                        </div>
                                        
                                        <!-- Performance Metrics -->
                                        <div class="text-center">
                                            <div class="video-bg priority-1 aspect-video rounded-lg overflow-hidden mb-3" data-video-name="metrics">
                                                <img 
                                                    src="/posters/metrics.webp" 
                                                    srcset="/posters/metrics.webp 1x"
                                                    alt="" 
                                                    class="video-poster"
                                                    loading="lazy"
                                                >
                                                <video class="lazy-video" muted loop playsinline preload="none">
                                                    <source data-src="https://res.cloudinary.com/dy99e0hzr/video/upload/v1763053837/metrics_elrsbs.webm" type="video/webm">
                                                    <source data-src="{{ asset('performance_metrics.mp4') }}" type="video/mp4">
                                                </video>
                                            </div>
                                            <p class="text-sm font-medium text-gray-700">Performance Metrics</p>
                                        </div>
                                        
                                        <!-- Completion Rates -->
                                         <div class="text-center">
                                            <div class="video-bg priority-1 aspect-video rounded-lg overflow-hidden mb-3" data-video-name="completion">
                                                <img 
                                                    src="/posters/completion.webp" 
                                                    srcset="/posters/completion.webp 1x"
                                                    alt="" 
                                                    class="video-poster"
                                                    loading="lazy"
                                                >
                                                <video class="lazy-video" muted loop playsinline preload="none">
                                                    <source data-src="https://res.cloudinary.com/dy99e0hzr/video/upload/v1763052738/05-completion_e6wghc.webm" type="video/webm">
                                                    <source data-src="{{ asset('completion_rates.mp4') }}" type="video/mp4">
                                                </video>
                                            </div>
                                            <p class="text-sm font-medium text-gray-700">Completion Rates</p>
                                        </div>
                                        
                                        <!-- Engagement Tracking -->
                                        <div class="text-center">
                                            <div class="video-bg priority-1 aspect-video rounded-lg overflow-hidden mb-3" data-video-name="engagement">
                                                <img 
                                                    src="/posters/engagement.webp" 
                                                    srcset="/posters/engagement.webp 1x"
                                                    alt="" 
                                                    class="video-poster"
                                                    loading="lazy"
                                                >
                                                <video class="lazy-video" muted loop playsinline preload="none">
                                                    <source data-src="https://res.cloudinary.com/dy99e0hzr/video/upload/v1763052742/06-engagement_mzemfc.webm" type="video/webm">
                                                    <source data-src="{{ asset('engagement_tracking.mp4') }}" type="video/mp4">
                                                </video>
                                            </div>
                                            <p class="text-sm font-medium text-gray-700">Engagement Tracking</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Card with Background Image -->
                                    <div class="relative rounded-2xl overflow-hidden min-h-[400px] flex items-center justify-center">
                                        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('Progress_tracking.jpg') }}');"></div>
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/50 to-black/30"></div>
                                        <div class="relative z-10 text-center text-white p-8">
                                            <h3 class="text-3xl font-bold mb-4">Progress Tracking</h3>
                                            <p class="text-lg text-gray-200">Monitor learning journey and achievements with real-time progress tracking and detailed analytics.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    
                    <!-- Slide 2: Team Collaboration -->
                    <div class="absolute inset-0 px-4 slide-content opacity-0" id="slide-1" data-direction="fade-in">
                            <div class="bg-white/30 backdrop-blur-xl rounded-2xl shadow-lg p-8 md:p-12 border border-white/50">
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                    <!-- Feature Items Grid -->
                                    <div class="grid grid-cols-2 gap-6">
                                        <!-- Discussion Forums -->
                                        <div class="text-center">
                                            <div class="video-bg priority-2 aspect-video rounded-lg overflow-hidden mb-3" data-video-name="forums">
                                                <img 
                                                    src="/posters/forums.webp" 
                                                    srcset="/posters/forums.webp 1x, /posters/forums-2x.webp 2x"
                                                    alt="" 
                                                    class="video-poster"
                                                    loading="lazy"
                                                >
                                                <video class="lazy-video" muted loop playsinline preload="none">
                                                    <source data-src="https://res.cloudinary.com/dy99e0hzr/video/upload/v1763052737/07-forums_ieixcf.webm" type="video/webm">
                                                    <source data-src="{{ asset('forum_discussions.mp4') }}" type="video/mp4">
                                                </video>
                                            </div>
                                            <p class="text-sm font-medium text-gray-700">Discussion Forums</p>
                                        </div>
                                        
                                        <!-- Live Chat -->
                                        <div class="text-center">
                                            <div class="video-bg priority-2 aspect-video rounded-lg overflow-hidden mb-3" data-video-name="chat">
                                                <img 
                                                    src="/posters/chat.webp" 
                                                    srcset="/posters/chat.webp 1x, /posters/chat-2x.webp 2x"
                                                    alt="" 
                                                    class="video-poster"
                                                    loading="lazy"
                                                >
                                                <video class="lazy-video" muted loop playsinline preload="none">
                                                    <source data-src="https://res.cloudinary.com/dy99e0hzr/video/upload/v1763052736/04-chat_ab4dqd.webm" type="video/webm">
                                                    <source data-src="{{ asset('Live_chat.mp4') }}" type="video/mp4">
                                                </video>
                                            </div>
                                            <p class="text-sm font-medium text-gray-700">Live Chat</p>
                                        </div>
                                        
                                        <!-- File Sharing -->
                                       <div class="text-center">
                                            <div class="video-bg priority-2 aspect-video rounded-lg overflow-hidden mb-3" data-video-name="sharing">
                                                <img 
                                                    src="/posters/sharing.webp" 
                                                    srcset="/posters/sharing.webp 1x, /posters/sharing-2x.webp 2x"
                                                    alt="" 
                                                    class="video-poster"
                                                    loading="lazy"
                                                >
                                                <video class="lazy-video" muted loop playsinline preload="none">
                                                    <source data-src="https://res.cloudinary.com/dy99e0hzr/video/upload/v1763052745/11-sharing_ixrr4i.webm" type="video/webm">
                                                    <source data-src="{{ asset('file_sharing.mp4') }}" type="video/mp4">
                                                </video>
                                            </div>
                                            <p class="text-sm font-medium text-gray-700">File Sharing</p>
                                        </div>
                                        
                                        <!-- Group Projects -->
                                        <div class="text-center">
                                            <div class="video-bg priority-2 aspect-video rounded-lg overflow-hidden mb-3" data-video-name="projects">
                                                <img 
                                                    src="/posters/projects.webp" 
                                                    srcset="/posters/projects.webp 1x, /posters/projects-2x.webp 2x"
                                                    alt="" 
                                                    class="video-poster"
                                                    loading="lazy"
                                                >
                                                <video class="lazy-video" muted loop playsinline preload="none">
                                                    <source data-src="https://res.cloudinary.com/dy99e0hzr/video/upload/v1763052760/10-projects_d6icym.webm" type="video/webm">
                                                    <source data-src="{{ asset('group_projects.mp4') }}" type="video/mp4">
                                                </video>
                                            </div>
                                            <p class="text-sm font-medium text-gray-700">Group Projects</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Card with Background Image -->
                                    <div class="relative rounded-2xl overflow-hidden min-h-[400px] flex items-center justify-center">
                                        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('team_collaboration.jpg') }}');"></div>
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/50 to-black/30"></div>
                                        <div class="relative z-10 text-center text-white p-8">
                                            <h3 class="text-3xl font-bold mb-4">Team Collaboration</h3>
                                            <p class="text-lg text-gray-200">Connect learners and facilitators seamlessly with built-in communication and collaboration tools.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    
                </div>

                <!-- Arrow Navigation -->
                <button onclick="previousSlide()" 
                        class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white/30 backdrop-blur-xl p-3 rounded-full shadow-lg hover:shadow-xl transition-shadow z-10 border border-white/50">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button onclick="nextSlide()" 
                        class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white/30 backdrop-blur-xl p-3 rounded-full shadow-lg hover:shadow-xl transition-shadow z-10 border border-white/50">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Option 4: Layered Parallax Curves -->
        <div class="wave-divider">
            <svg viewBox="0 0 1440 120" preserveAspectRatio="none" class="w-full" style="display: block;">
                <path d="M0,50 Q360,80 720,50 T1440,50 L1440,120 L0,120 Z" 
                      class="fill-white" style="opacity: 0.2;"></path>
                <path d="M0,65 Q360,30 720,65 T1440,65 L1440,120 L0,120 Z" 
                      class="fill-white" style="opacity: 0.4;"></path>
                <path d="M0,80 Q360,110 720,80 T1440,80 L1440,120 L0,120 Z" 
                      class="fill-white"></path>
            </svg>
        </div>
    </section>

    <!-- Platform Overview -->
    <section class="relative py-20 bg-white overflow-hidden" style="margin-top: -1px;">
        <!-- Floating Digital Product Icons -->
        <div class="floating-element" style="top: 10%; left: 7%; animation-delay: 1s;">
            <svg class="w-24 h-24 text-indigo-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M21 3H3c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h5v2h8v-2h5c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 14H3V5h18v12z"/>
            </svg>
        </div>
        
        <div class="floating-element floating-element-reverse" style="top: 50%; right: 10%; animation-delay: 2.5s;">
            <svg class="w-20 h-20 text-emerald-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M18 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 4h5v8l-2.5-1.5L6 12V4z"/>
            </svg>
        </div>
        
        <div class="floating-element" style="bottom: 20%; right: 15%; animation-delay: 4s;">
            <svg class="w-18 h-18 text-pink-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
        </div>
        
        <div class="floating-element floating-element-reverse" style="top: 25%; right: 18%; animation-delay: 1.5s;">
            <svg class="w-26 h-26 text-orange-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
            </svg>
        </div>
        
        <div class="floating-element" style="top: 65%; left: 8%; animation-delay: 3.2s;">
            <svg class="w-22 h-22 text-violet-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm0 4c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm6 12H6v-1.4c0-2 4-3.1 6-3.1s6 1.1 6 3.1V19z"/>
            </svg>
        </div>
        
        <div class="floating-element floating-element-reverse" style="bottom: 35%; right: 8%; animation-delay: 4.8s;">
            <svg class="w-24 h-24 text-teal-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z"/>
            </svg>
        </div>
        
        <div class="max-w-7xl mx-auto px-6">
            <!-- Centered Header Section -->
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">
                    Comprehensive Learning Platform
                </h2>
                <p class="text-lg text-gray-600 max-w-4xl mx-auto">
                    Complete ecosystem for educational content management with advanced features designed for modern organizations.
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 gap-16 items-center">
                <div>
                    <div class="space-y-8">
                        <!-- Centralized Resource Management -->
                        <div class="bg-white/30 backdrop-blur-xl rounded-xl p-6 border border-white/50 shadow-lg hover:shadow-xl transition-all duration-300">
                            <div class="flex flex-col lg:flex-row items-start space-y-4 lg:space-y-0 lg:space-x-6">
                                <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Centralized Resource Management</h3>
                                    <p class="text-gray-600 mb-4">All learning materials organized in one accessible location</p>
                                    <div class="video-bg priority-2 h-32 rounded-lg overflow-hidden" data-video-name="management">
                                        <img 
                                            src="/posters/management.webp" 
                                            srcset="/posters/management.webp 1x, /posters/management-2x.webp 2x"
                                            alt="" 
                                            class="video-poster"
                                            loading="lazy"
                                        >
                                        <video class="lazy-video" muted loop playsinline preload="none">
                                            <source data-src="https://res.cloudinary.com/dy99e0hzr/video/upload/v1763052748/08-management_uru8ym.webm" type="video/webm">
                                            <source data-src="{{ asset('centralized_management.mp4') }}" type="video/mp4">
                                        </video>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Real-Time Progress Monitoring -->
                        <div class="bg-white/30 backdrop-blur-xl rounded-xl p-6 border border-white/50 shadow-lg hover:shadow-xl transition-all duration-300">
                            <div class="flex flex-col lg:flex-row items-start space-y-4 lg:space-y-0 lg:space-x-6">
                                <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Real-Time Progress Monitoring</h3>
                                    <p class="text-gray-600 mb-4">Track completion rates and engagement metrics</p>
                                    <div class="video-bg priority-2 h-32 rounded-lg overflow-hidden" data-video-name="monitoring">
                                        <img 
                                            src="/posters/monitoring.webp" 
                                            srcset="/posters/monitoring.webp 1x, /posters/monitoring-2x.webp 2x"
                                            alt="" 
                                            class="video-poster"
                                            loading="lazy"
                                        >
                                        <video class="lazy-video" muted loop playsinline preload="none">
                                            <source data-src="https://res.cloudinary.com/dy99e0hzr/video/upload/v1763052743/09-monitoring_cfvixw.webm" type="video/webm">
                                            <source data-src="{{ asset('real_time_progress_monitoring.mp4') }}" type="video/mp4">
                                        </video>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Interactive Assessment System -->
                        <div class="bg-white/30 backdrop-blur-xl rounded-xl p-6 border border-white/50 shadow-lg hover:shadow-xl transition-all duration-300">
                            <div class="flex flex-col lg:flex-row items-start space-y-4 lg:space-y-0 lg:space-x-6">
                                <div class="flex-shrink-0 w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Interactive Assessment System</h3>
                                    <p class="text-gray-600 mb-4">Comprehensive testing and feedback mechanisms</p>
                                    <div class="video-bg priority-3 h-32 rounded-lg overflow-hidden" data-video-name="assessment">
                                        <img 
                                            src="/posters/assessment.webp" 
                                            srcset="/posters/assessment.webp 1x, /posters/assessment-2x.webp 2x"
                                            alt="" 
                                            class="video-poster"
                                            loading="lazy"
                                        >
                                        <video class="lazy-video" muted loop playsinline preload="none">
                                            <source data-src="https://res.cloudinary.com/dy99e0hzr/video/upload/v1763052739/03-assessment_h1uva6.webm" type="video/webm">
                                            <source data-src="{{ asset('interactive_assessment.mp4') }}" type="video/mp4">
                                        </video>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="relative">
                    <div class="h-96 bg-gray-100 rounded-2xl overflow-hidden shadow-xl relative">
                        <img src="{{ asset('image.jpg') }}" alt="24/7 Platform Access" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                            <div class="text-center text-white">
                                <div class="text-6xl font-bold mb-4">24/7</div>
                                <div class="text-2xl font-semibold mb-2">Platform Access</div>
                                <div class="text-lg opacity-90">Always available when you need it</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Option 6: Tilt/Diagonal Slice -->
        <div class="wave-divider">
            <svg viewBox="0 0 1440 120" preserveAspectRatio="none" class="w-full" style="display: block;">
                <path d="M0,120 L0,20 L1440,100 L1440,120 Z" 
                      class="fill-white"></path>
            </svg>
        </div>
    </section>

    <!-- Organization Types -->
    <section class="relative py-20 bg-white" style="margin-top: -1px;">
        <!-- Abstract Background Shapes -->
        <div class="absolute inset-0 overflow-hidden opacity-10">
            <div style="position: absolute; top: 15%; left: -5%; width: 400px; height: 400px; background: linear-gradient(135deg, #3b82f6, #8b5cf6); border-radius: 50%; filter: blur(60px);"></div>
            <div style="position: absolute; bottom: 20%; right: -5%; width: 350px; height: 350px; background: linear-gradient(135deg, #10b981, #3b82f6); border-radius: 50%; filter: blur(60px);"></div>
        </div>
        
        <!-- Decorative Lines -->
        <div class="absolute inset-0 opacity-5">
            <svg width="100%" height="100%">
                <defs>
                    <pattern id="diagonalLines" patternUnits="userSpaceOnUse" width="50" height="50" patternTransform="rotate(45)">
                        <line x1="0" y1="0" x2="0" y2="50" stroke="currentColor" stroke-width="1" class="text-blue-500"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#diagonalLines)"/>
            </svg>
        </div>
        
        <!-- Floating Digital Product Icons -->
        <div class="floating-element" style="top: 12%; right: 10%; animation-delay: 1.5s;">
            <svg class="w-22 h-22 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/>
            </svg>
        </div>
        
        <div class="floating-element floating-element-reverse" style="bottom: 25%; left: 10%; animation-delay: 3s;">
            <svg class="w-20 h-20 text-teal-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 9h12v2H6V9zm8 5H6v-2h8v2zm4-6H6V6h12v2z"/>
            </svg>
        </div>
        
        <div class="floating-element" style="top: 60%; right: 15%; animation-delay: 4.5s;">
            <svg class="w-24 h-24 text-violet-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
            </svg>
        </div>
        
        <div class="floating-element floating-element-reverse" style="top: 35%; left: 8%; animation-delay: 2.2s;">
            <svg class="w-26 h-26 text-rose-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
            </svg>
        </div>
        
        <div class="floating-element" style="bottom: 45%; right: 18%; animation-delay: 3.8s;">
            <svg class="w-22 h-22 text-cyan-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M19 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.11 0 2-.9 2-2V5c0-1.1-.89-2-2-2zm-9 14l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
            </svg>
        </div>
        
        <div class="floating-element floating-element-reverse" style="top: 75%; right: 20%; animation-delay: 5.2s;">
            <svg class="w-24 h-24 text-lime-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20 6h-2.18c.11-.31.18-.65.18-1 0-1.66-1.34-3-3-3-1.05 0-1.96.54-2.5 1.35l-.5.67-.5-.68C10.96 2.54 10.05 2 9 2 7.34 2 6 3.34 6 5c0 .35.07.69.18 1H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2z"/>
            </svg>
        </div>
        
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">
                    Perfect for Organizations
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Scalable solutions for all teams and their skillset
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="w-20 h-20 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <span class="text-3xl">🔧</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Engineering Teams</h3>
                    <p class="text-gray-600">Technical skill development</p>
                </div>
                
                <div class="text-center">
                    <div class="w-20 h-20 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <span class="text-3xl">📈</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Marketing Departments</h3>
                    <p class="text-gray-600">Creative training programs</p>
                </div>
                
                <div class="text-center">
                    <div class="w-20 h-20 bg-purple-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <span class="text-3xl">🔬</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">R&D Groups</h3>
                    <p class="text-gray-600">Research methodologies</p>
                </div>
                
                <div class="text-center">
                    <div class="w-20 h-20 bg-red-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <span class="text-3xl">👥</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">HR & Training</h3>
                    <p class="text-gray-600">Employee development</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Creative Morphing Circle Design with Certification Outcome -->
    <section id="pillars-section" class="py-32 relative overflow-hidden bg-white" style="min-height: 120vh;">
      <!-- Glass Morphism Background -->
      <div class="absolute inset-0 bg-white/30 backdrop-blur-3xl"></div>
      
      <div class="max-w-7xl mx-auto px-6 relative z-10">
        <div class="text-center mb-24">
          <h2 class="text-5xl font-bold text-gray-900 mb-4 tracking-tight">Integrated Learning Approach</h2>
        </div>
        
        <!-- Two Column Layout: Dashboard + Certification -->
        <div class="grid lg:grid-cols-2 gap-12 items-start">
          
          <!-- Left: Analytics Dashboard with View Toggle -->
          <div class="relative backdrop-blur-xl bg-white/30 rounded-3xl p-6 shadow-2xl border border-white/50 min-h-[700px]" 
               x-data="{ viewMode: 'mobile', autoSwitch: true }" 
               x-init="setInterval(() => { if(autoSwitch) { viewMode = viewMode === 'mobile' ? 'tablet' : 'mobile' } }, 6000)">
            
            <!-- Toggle Switch with Progress Indicator -->
            <div class="flex justify-center mb-6">
              <div class="inline-flex flex-col items-center gap-2">
                <div class="inline-flex items-center gap-1 bg-gradient-to-r from-gray-200/60 to-gray-100/60 backdrop-blur-md rounded-full p-0.5 shadow-lg border border-white/50">
                  <button @click="viewMode = 'mobile'; autoSwitch = false" 
                          :class="viewMode === 'mobile' ? 'bg-gradient-to-r from-white to-gray-50 shadow-xl scale-105' : 'text-gray-500 hover:text-gray-700'"
                          class="px-5 py-2 rounded-full text-xs font-semibold transition-all duration-500 ease-out flex items-center gap-1.5">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M7 2a2 2 0 00-2 2v12a2 2 0 002 2h6a2 2 0 002-2V4a2 2 0 00-2-2H7zm3 14a1 1 0 100-2 1 1 0 000 2z"/>
                    </svg>
                    Mobile
                  </button>
                  <button @click="viewMode = 'tablet'; autoSwitch = false" 
                          :class="viewMode === 'tablet' ? 'bg-gradient-to-r from-white to-gray-50 shadow-xl scale-105' : 'text-gray-500 hover:text-gray-700'"
                          class="px-5 py-2 rounded-full text-xs font-semibold transition-all duration-500 ease-out flex items-center gap-1.5">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4V5h12v10z"/>
                    </svg>
                    Tablet
                  </button>
                </div>
                <!-- Auto-switch indicator -->
                <div x-show="autoSwitch" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-90"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="flex items-center gap-1.5 text-[9px] text-gray-500">
                  <div class="w-1 h-1 rounded-full bg-green-500 animate-pulse"></div>
                  <span>Auto-switching</span>
                </div>
              </div>
            </div>

            <!-- Dashboard Views Container with Absolute Positioning -->
            <div class="relative min-h-[600px]">
              <!-- Mobile View Dashboard -->
              <div x-show="viewMode === 'mobile'" 
                   x-cloak
                   x-transition:enter="transition ease-in-out duration-600"
                   x-transition:enter-start="opacity-0 translate-x-full"
                   x-transition:enter-end="opacity-100 translate-x-0"
                   x-transition:leave="transition ease-in-out duration-600"
                   x-transition:leave-start="opacity-100 translate-x-0"
                   x-transition:leave-end="opacity-0 -translate-x-full"
                   class="absolute inset-0 space-y-3">
              
              <!-- Header -->
              <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-bold text-gray-900">Analytics</h3>
                <div class="flex gap-0.5">
                  <div class="w-0.5 h-0.5 rounded-full bg-gray-400"></div>
                  <div class="w-0.5 h-0.5 rounded-full bg-gray-400"></div>
                  <div class="w-0.5 h-0.5 rounded-full bg-gray-400"></div>
                </div>
              </div>

              <!-- Stats Grid -->
              <div class="grid grid-cols-2 gap-2">
                <div class="bg-white/30 backdrop-blur-sm rounded-lg p-3 border border-white/50">
                  <div class="text-[10px] text-gray-600 mb-0.5">Active Users</div>
                  <div class="text-xl font-bold text-gray-900 counter" data-target="2847">0</div>
                  <div class="text-[10px] text-green-600 mt-0.5">↑ 12.5%</div>
                </div>
                <div class="bg-white/30 backdrop-blur-sm rounded-lg p-3 border border-white/50">
                  <div class="text-[10px] text-gray-600 mb-0.5">Completion</div>
                  <div class="text-xl font-bold text-gray-900 counter" data-target="87">0</div>
                  <div class="text-[10px] text-green-600 mt-0.5">↑ 8.2%</div>
                </div>
              </div>

              <!-- Chart Visualization -->
              <div class="bg-white/30 backdrop-blur-sm rounded-lg p-3 border border-white/50">
                <div class="text-[10px] font-medium text-gray-600 mb-2">Weekly Activity</div>
                <div class="flex items-end justify-between gap-1 h-28">
                  <div class="w-full bg-gradient-to-t from-purple-500 to-purple-300 rounded-t bar-chart" style="height: 45%"></div>
                  <div class="w-full bg-gradient-to-t from-purple-500 to-purple-300 rounded-t bar-chart" style="height: 68%"></div>
                  <div class="w-full bg-gradient-to-t from-purple-500 to-purple-300 rounded-t bar-chart" style="height: 82%"></div>
                  <div class="w-full bg-gradient-to-t from-purple-500 to-purple-300 rounded-t bar-chart" style="height: 91%"></div>
                  <div class="w-full bg-gradient-to-t from-purple-500 to-purple-300 rounded-t bar-chart" style="height: 76%"></div>
                  <div class="w-full bg-gradient-to-t from-purple-500 to-purple-300 rounded-t bar-chart" style="height: 58%"></div>
                  <div class="w-full bg-gradient-to-t from-purple-500 to-purple-300 rounded-t bar-chart" style="height: 95%"></div>
                </div>
                <div class="flex justify-between text-[9px] text-gray-500 mt-1.5">
                  <span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span><span>Sun</span>
                </div>
              </div>

              <!-- Recent Activity -->
              <div class="bg-white/30 backdrop-blur-sm rounded-lg p-3 border border-white/50 space-y-2">
                <div class="text-[10px] font-medium text-gray-600 mb-2">Recent Activity</div>
                <div class="flex items-center gap-2">
                  <div class="w-1.5 h-1.5 rounded-full bg-green-500"></div>
                  <div class="flex-1 text-[10px] text-gray-700">New user registered</div>
                  <div class="text-[9px] text-gray-500">2m</div>
                </div>
                <div class="flex items-center gap-2">
                  <div class="w-1.5 h-1.5 rounded-full bg-blue-500"></div>
                  <div class="flex-1 text-[10px] text-gray-700">Course completed</div>
                  <div class="text-[9px] text-gray-500">5m</div>
                </div>
                <div class="flex items-center gap-2">
                  <div class="w-1.5 h-1.5 rounded-full bg-purple-500"></div>
                  <div class="flex-1 text-[10px] text-gray-700">Assessment passed</div>
                  <div class="text-[9px] text-gray-500">12m</div>
                </div>
              </div>

              <!-- Additional Stats -->
              <div class="grid grid-cols-3 gap-2">
                <div class="bg-white/30 backdrop-blur-sm rounded-lg p-2 border border-white/50">
                  <div class="text-[9px] text-gray-600">Courses</div>
                  <div class="text-lg font-bold text-gray-900 counter" data-target="124">0</div>
                </div>
                <div class="bg-white/30 backdrop-blur-sm rounded-lg p-2 border border-white/50">
                  <div class="text-[9px] text-gray-600">Enrolled</div>
                  <div class="text-lg font-bold text-gray-900 counter" data-target="5680">0</div>
                </div>
                <div class="bg-white/30 backdrop-blur-sm rounded-lg p-2 border border-white/50">
                  <div class="text-[9px] text-gray-600">Pass Rate</div>
                  <div class="text-lg font-bold text-gray-900 counter" data-target="92">0</div>
                </div>
              </div>
              </div>

              <!-- Tablet View Dashboard -->
              <div x-show="viewMode === 'tablet'"
                   x-cloak
                   x-transition:enter="transition ease-in-out duration-600"
                   x-transition:enter-start="opacity-0 translate-x-full"
                   x-transition:enter-end="opacity-100 translate-x-0"
                   x-transition:leave="transition ease-in-out duration-600"
                   x-transition:leave-start="opacity-100 translate-x-0"
                   x-transition:leave-end="opacity-0 -translate-x-full"
                   class="absolute inset-0 space-y-3">
              
              <!-- Header -->
              <div class="flex items-center justify-between mb-2">
                <h3 class="text-base font-bold text-gray-900">Analytics Dashboard</h3>
                <div class="text-[10px] text-gray-500">Last updated: 2m ago</div>
              </div>

              <!-- Stats Grid -->
              <div class="grid grid-cols-3 gap-2">
                <div class="bg-white/30 backdrop-blur-sm rounded-lg p-3 border border-white/50">
                  <div class="text-[10px] text-gray-600 mb-1">Total Learners</div>
                  <div class="text-2xl font-bold text-gray-900 counter" data-target="12450">0</div>
                  <div class="text-[10px] text-green-600 mt-1">↑ 24.3%</div>
                </div>
                <div class="bg-white/30 backdrop-blur-sm rounded-lg p-3 border border-white/50">
                  <div class="text-[10px] text-gray-600 mb-1">Course Views</div>
                  <div class="text-2xl font-bold text-gray-900 counter" data-target="48620">0</div>
                  <div class="text-[10px] text-green-600 mt-1">↑ 18.7%</div>
                </div>
                <div class="bg-white/30 backdrop-blur-sm rounded-lg p-3 border border-white/50">
                  <div class="text-[10px] text-gray-600 mb-1">Certificates</div>
                  <div class="text-2xl font-bold text-gray-900 counter" data-target="3298">0</div>
                  <div class="text-[10px] text-green-600 mt-1">↑ 31.2%</div>
                </div>
              </div>

              <!-- Chart Section -->
              <div class="grid grid-cols-2 gap-2">
                <div class="bg-white/30 backdrop-blur-sm rounded-lg p-3 border border-white/50">
                  <div class="text-[10px] font-medium text-gray-600 mb-2">Engagement Rate</div>
                  <div class="flex items-end justify-between gap-1 h-32">
                    <div class="w-full bg-gradient-to-t from-blue-500 to-blue-300 rounded-t bar-chart" style="height: 55%"></div>
                    <div class="w-full bg-gradient-to-t from-blue-500 to-blue-300 rounded-t bar-chart" style="height: 72%"></div>
                    <div class="w-full bg-gradient-to-t from-blue-500 to-blue-300 rounded-t bar-chart" style="height: 88%"></div>
                    <div class="w-full bg-gradient-to-t from-blue-500 to-blue-300 rounded-t bar-chart" style="height: 65%"></div>
                    <div class="w-full bg-gradient-to-t from-blue-500 to-blue-300 rounded-t bar-chart" style="height: 92%"></div>
                    <div class="w-full bg-gradient-to-t from-blue-500 to-blue-300 rounded-t bar-chart" style="height: 78%"></div>
                    <div class="w-full bg-gradient-to-t from-blue-500 to-blue-300 rounded-t bar-chart" style="height: 95%"></div>
                    <div class="w-full bg-gradient-to-t from-blue-500 to-blue-300 rounded-t bar-chart" style="height: 88%"></div>
                  </div>
                </div>
                <div class="bg-white/30 backdrop-blur-sm rounded-lg p-3 border border-white/50">
                  <div class="text-[10px] font-medium text-gray-600 mb-2">Performance Metrics</div>
                  <div class="space-y-2">
                    <div>
                      <div class="flex justify-between text-[9px] mb-1">
                        <span class="text-gray-700">Training</span>
                        <span class="font-medium counter" data-target="94">0</span>
                      </div>
                      <div class="h-1.5 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-purple-500 to-purple-600 rounded-full progress-bar" style="width: 0%" data-width="94"></div>
                      </div>
                    </div>
                    <div>
                      <div class="flex justify-between text-[9px] mb-1">
                        <span class="text-gray-700">Performance</span>
                        <span class="font-medium counter" data-target="88">0</span>
                      </div>
                      <div class="h-1.5 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-green-500 to-green-600 rounded-full progress-bar" style="width: 0%" data-width="88"></div>
                      </div>
                    </div>
                    <div>
                      <div class="flex justify-between text-[9px] mb-1">
                        <span class="text-gray-700">Assessment</span>
                        <span class="font-medium counter" data-target="96">0</span>
                      </div>
                      <div class="h-1.5 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-blue-500 to-blue-600 rounded-full progress-bar" style="width: 0%" data-width="96"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Additional Metrics Row -->
              <div class="grid grid-cols-4 gap-2">
                <div class="bg-white/30 backdrop-blur-sm rounded-lg p-2 border border-white/50">
                  <div class="text-[9px] text-gray-600">Avg. Score</div>
                  <div class="text-lg font-bold text-gray-900 counter" data-target="86">0</div>
                </div>
                <div class="bg-white/30 backdrop-blur-sm rounded-lg p-2 border border-white/50">
                  <div class="text-[9px] text-gray-600">Hours</div>
                  <div class="text-lg font-bold text-gray-900 counter" data-target="1247">0</div>
                </div>
                <div class="bg-white/30 backdrop-blur-sm rounded-lg p-2 border border-white/50">
                  <div class="text-[9px] text-gray-600">Modules</div>
                  <div class="text-lg font-bold text-gray-900 counter" data-target="342">0</div>
                </div>
                <div class="bg-white/30 backdrop-blur-sm rounded-lg p-2 border border-white/50">
                  <div class="text-[9px] text-gray-600">Active</div>
                  <div class="text-lg font-bold text-gray-900 counter" data-target="98">0</div>
                </div>
              </div>
              </div>
            </div>
            <!-- End Dashboard Views Container -->
             <!-- Outcome Badge -->
              <div class="p-6 bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 rounded-2xl text-white text-center">
                <div class="text-5xl mb-3">✓</div>
                <h4 class="text-2xl font-bold mb-2">Industry-Certified Professional</h4>
                <p class="text-blue-100">Ready to excel in your field with confidence and competence</p>
              </div>
            
          </div>

          <!-- Right: Certification Outcome (Glass Morphism) -->
          <div class="backdrop-blur-xl bg-white/30 rounded-3xl p-8 shadow-2xl border border-white/50 space-y-8">
            <!-- Graduate Image -->
            <div class="relative rounded-2xl overflow-hidden shadow-2xl" style="max-width: 500px;">
              <img src="{{ asset('get ready.jpg') }}" alt="Ready to Dive in?" class="w-full h-auto object-cover" style="aspect-ratio: 4/3;">
            </div>

            <!-- Certification Text -->
            <div class="space-y-6">
              <div>
                <h3 class="text-3xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                  <span class="flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 text-white text-xl">🎓</span>
                  Ready to set off?
                </h3>
                <p class="text-lg text-gray-600 leading-relaxed">
                  When these pillars work together, they create a comprehensive learning experience that produces certified professionals ready for real-world challenges.
                </p>
              </div>

              <!-- The Integration Process -->
              <div class="space-y-4">
                <div class="flex items-start gap-4 p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl">
                  <div class="flex-shrink-0 w-10 h-10 rounded-full bg-purple-600 text-white flex items-center justify-center font-bold">1</div>
                  <div>
                    <h4 class="font-semibold text-gray-900 mb-1">Expert Training</h4>
                    <p class="text-sm text-gray-600">Industry-standard skills and knowledge foundation</p>
                  </div>
                </div>

                <div class="flex items-start gap-4 p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-xl">
                  <div class="flex-shrink-0 w-10 h-10 rounded-full bg-green-600 text-white flex items-center justify-center font-bold">2</div>
                  <div>
                    <h4 class="font-semibold text-gray-900 mb-1">Proven Performance</h4>
                    <p class="text-sm text-gray-600">Hands-on experience and measurable results</p>
                  </div>
                </div>

                <div class="flex items-start gap-4 p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl">
                  <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">3</div>
                  <div>
                    <h4 class="font-semibold text-gray-900 mb-1">Validated Assessment</h4>
                    <p class="text-sm text-gray-600">Official certification of competence and readiness</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- JavaScript for Counter and Progress Bar Animations -->
      <script>
        document.addEventListener('DOMContentLoaded', () => {
          // Looping Counter Animation
          const animateCounters = () => {
            const counters = document.querySelectorAll('.counter');
            counters.forEach(counter => {
              const target = parseInt(counter.getAttribute('data-target'));
              const duration = 2000; // Count up duration
              const pauseDuration = 1500; // Pause at target before resetting
              const step = target / (duration / 16);
              
              let current = 0;
              let animationId;
              
              const updateCounter = () => {
                current += step;
                if (current < target) {
                  counter.textContent = Math.floor(current);
                  animationId = requestAnimationFrame(updateCounter);
                } else {
                  counter.textContent = target;
                  // Pause, then reset and start again
                  setTimeout(() => {
                    current = 0;
                    counter.textContent = '0';
                    // Small delay before starting next loop
                    setTimeout(() => {
                      updateCounter();
                    }, 300);
                  }, pauseDuration);
                }
              };
              
              updateCounter();
            });
          };
          
          // Looping Progress Bar Animation
          const animateProgressBars = () => {
            const progressBars = document.querySelectorAll('.progress-bar');
            
            const animateBars = () => {
              progressBars.forEach(bar => {
                const width = bar.getAttribute('data-width');
                // Reset to 0
                bar.style.transition = 'none';
                bar.style.width = '0%';
                
                // Animate to target
                setTimeout(() => {
                  bar.style.transition = 'width 1.5s ease-out';
                  bar.style.width = width + '%';
                }, 50);
              });
              
              // Loop the animation (reset after fill + pause duration)
              setTimeout(animateBars, 4000); // 1.5s animation + 2.5s pause
            };
            
            animateBars();
          };
          
          // Initial animation
          setTimeout(() => {
            animateCounters();
            animateProgressBars();
          }, 100);
        });
      </script>

      <!-- Styles -->
      <style>
        /* Enhanced backdrop with animated gradients for glass morphism */
        #pillars-section::before {
          content: '';
          position: absolute;
          top: 0;
          left: 0;
          right: 0;
          bottom: 0;
          background: 
            radial-gradient(circle at 50% 50%, rgba(139, 92, 246, 0.08), transparent 60%),
            radial-gradient(circle at 30% 70%, rgba(16, 185, 129, 0.08), transparent 60%),
            radial-gradient(circle at 70% 30%, rgba(59, 130, 246, 0.08), transparent 60%);
          pointer-events: none;
          z-index: 1;
          animation: gradientShift 15s ease-in-out infinite;
        }

        @keyframes gradientShift {
          0%, 100% {
            opacity: 0.6;
          }
          50% {
            opacity: 1;
          }
        }
        
        #pillars-section > div {
          position: relative;
          z-index: 10;
        }

        /* Bar Chart Animations */
        .bar-chart {
          animation: barGrow 2s ease-out forwards;
          transform-origin: bottom;
        }

        @keyframes barGrow {
          from {
            transform: scaleY(0);
            opacity: 0;
          }
          to {
            transform: scaleY(1);
            opacity: 1;
          }
        }

        /* Glass morphism hover effects */
        .backdrop-blur-xl {
          transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .backdrop-blur-xl:hover {
          background: rgba(255, 255, 255, 0.5);
          transform: translateY(-2px);
          box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .dark .backdrop-blur-xl:hover {
          background: rgba(15, 23, 42, 0.5);
        }

        /* Smooth content transitions */
        [x-cloak] {
          display: none !important;
        }

        /* Enhanced button transitions */
        button {
          transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        /* Pulse animation for auto-switch indicator */
        @keyframes gentlePulse {
          0%, 100% {
            opacity: 1;
            transform: scale(1);
          }
          50% {
            opacity: 0.6;
            transform: scale(1.1);
          }
        }
        
        @media (prefers-reduced-motion: reduce) {
          #pillars-section::before,
          .bar-chart,
          .backdrop-blur-xl,
          button {
            animation: none !important;
            transition: none !important;
          }
        }
      </style>
    </section>

    <!-- Training Section -->
    <section class="relative py-20 bg-white overflow-hidden">
        <!-- Floating Digital Product Icons -->
        <div class="floating-element" style="top: 15%; left: 8%; animation-delay: 0.8s;">
            <svg class="w-20 h-20 text-orange-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3zm6.82 6L12 12.72 5.18 9 12 5.28 18.82 9zM17 15.99l-5 2.73-5-2.73v-3.72L12 15l5-2.73v3.72z"/>
            </svg>
        </div>
        
        <div class="floating-element floating-element-reverse" style="top: 55%; right: 12%; animation-delay: 2.2s;">
            <svg class="w-22 h-22 text-sky-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
            </svg>
        </div>
        
        <div class="floating-element" style="bottom: 18%; right: 16%; animation-delay: 3.8s;">
            <svg class="w-24 h-24 text-rose-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M11 17h2v-6h-2v6zm1-15C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zM11 9h2V7h-2v2z"/>
            </svg>
        </div>
        
        <div class="floating-element floating-element-reverse" style="top: 35%; left: 8%; animation-delay: 1.6s;">
            <svg class="w-26 h-26 text-purple-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M18 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 4h5v8l-2.5-1.5L6 12V4z"/>
            </svg>
        </div>
        
        <div class="floating-element" style="bottom: 40%; right: 10%; animation-delay: 4.5s;">
            <svg class="w-22 h-22 text-emerald-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
        </div>
        
        <div class="floating-element floating-element-reverse" style="top: 68%; right: 22%; animation-delay: 5.3s;">
            <svg class="w-24 h-24 text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M9 11.75c-.69 0-1.25.56-1.25 1.25s.56 1.25 1.25 1.25 1.25-.56 1.25-1.25-.56-1.25-1.25-1.25zm6 0c-.69 0-1.25.56-1.25 1.25s.56 1.25 1.25 1.25 1.25-.56 1.25-1.25-.56-1.25-1.25-1.25zM12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8 0-.29.02-.58.05-.86 2.36-1.05 4.23-2.98 5.21-5.37C11.07 8.33 14.05 10 17.42 10c.78 0 1.53-.09 2.25-.26.21.71.33 1.47.33 2.26 0 4.41-3.59 8-8 8z"/>
            </svg>
        </div>
        
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-16 items-center">
                <div class="relative">
                    <!-- Animated Background Elements -->
                    <div class="absolute -top-20 -left-20 w-40 h-40 bg-gradient-to-br from-blue-400/20 to-purple-400/20 rounded-full blur-3xl animate-pulse"></div>
                    <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-gradient-to-br from-green-400/20 to-cyan-400/20 rounded-full blur-2xl animate-pulse" style="animation-delay: 1s;"></div>
                    
                    <!-- Floating Icons -->
                    <div class="absolute top-0 right-0 opacity-10">
                        <svg class="w-24 h-24 text-blue-500 animate-spin" style="animation-duration: 20s;" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <div class="absolute bottom-0 left-0 opacity-10">
                        <svg class="w-20 h-20 text-purple-500 animate-bounce" style="animation-duration: 3s;" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm0 4c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm6 12H6v-1.4c0-2 4-3.1 6-3.1s6 1.1 6 3.1V19z"/>
                        </svg>
                    </div>
                    
                    <h2 class="text-4xl font-bold text-gray-900 mb-6 relative z-10">
                        Transform Your Team Through Expert Training
                    </h2>
                    <p class="text-lg text-gray-600 mb-8 relative z-10">
                        Experience the power of professional development with our comprehensive training programs designed to elevate your team's capabilities.
                    </p>
                    
                    <div class="space-y-8 relative z-10">
                        <!-- Professional Development Card -->
                        <div class="group relative overflow-hidden transform-gpu perspective-1000">
                            <div class="relative px-10 py-8 bg-gradient-to-br from-blue-50/90 via-blue-100/80 to-indigo-50/90 backdrop-blur-2xl rounded-3xl shadow-2xl border border-blue-200/60 hover:shadow-blue-500/25 transition-all duration-700 hover:scale-[1.03] hover:rotate-x-2 hover:rotate-y-1 transform-style-preserve-3d">
                                <!-- Animated Progress Bar -->
                                <div class="absolute top-0 left-0 h-2 bg-gradient-to-r from-blue-500 via-cyan-500 to-blue-600 rounded-t-3xl transition-all duration-1000 w-4/5 opacity-80"></div>
                                <div class="absolute top-0 left-0 h-2 bg-gradient-to-r from-blue-400 to-cyan-400 rounded-t-3xl transition-all duration-1500 group-hover:w-full w-0 animate-pulse"></div>
                                
                                <!-- 3D Floating Elements -->
                                <div class="absolute -top-4 -right-4 w-16 h-16 bg-gradient-to-br from-blue-400/20 to-cyan-500/30 rounded-2xl flex items-center justify-center group-hover:scale-125 group-hover:rotate-12 transition-all duration-500 shadow-lg backdrop-blur-sm border border-blue-300/40">
                                    <svg class="w-8 h-8 text-blue-600 animate-pulse" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                    </svg>
                                </div>
                                <!-- Particle Effects -->
                                <div class="absolute top-4 right-8 w-2 h-2 bg-blue-400 rounded-full animate-ping opacity-75"></div>
                                <div class="absolute top-8 right-12 w-1 h-1 bg-cyan-400 rounded-full animate-ping opacity-60" style="animation-delay: 0.5s;"></div>
                                <div class="absolute top-12 right-6 w-1.5 h-1.5 bg-blue-300 rounded-full animate-ping opacity-50" style="animation-delay: 1s;"></div>
                                
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <!-- 3D Animated Icon -->
                                        <div class="relative w-20 h-20 bg-gradient-to-br from-blue-500/15 via-cyan-500/10 to-blue-600/15 rounded-2xl flex items-center justify-center group-hover:bg-gradient-to-br group-hover:from-blue-500/25 group-hover:via-cyan-500/20 group-hover:to-blue-600/25 transition-all duration-500 shadow-inner border border-blue-300/30">
                                            <!-- Glow Effect -->
                                            <div class="absolute inset-0 bg-blue-400/20 rounded-2xl blur-md group-hover:blur-lg transition-all duration-500"></div>
                                            <svg class="relative w-10 h-10 text-blue-600 group-hover:scale-125 group-hover:rotate-6 transition-all duration-500" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-bold text-2xl text-gray-900 group-hover:text-blue-700 transition-colors duration-500 mb-2">Professional Development</div>
                                            <div class="text-base text-gray-600 group-hover:text-gray-700 transition-colors duration-300 mb-4">Enhance skills with industry-tailored programs</div>
                                            
                                            <!-- Advanced Progress Visualization -->
                                            <div class="space-y-3">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-sm font-medium text-gray-700">Success Rate</span>
                                                    <span class="text-sm font-bold text-blue-600">85%</span>
                                                </div>
                                                <div class="relative h-3 bg-blue-100/50 rounded-full overflow-hidden">
                                                    <div class="absolute inset-0 bg-gradient-to-r from-blue-500 via-cyan-500 to-blue-600 rounded-full transition-all duration-2000 w-4/5"></div>
                                                    <div class="absolute inset-0 bg-gradient-to-r from-blue-400/50 to-cyan-400/50 rounded-full animate-pulse"></div>
                                                </div>
                                                
                                                <!-- Feature Tags -->
                                                <div class="flex flex-wrap gap-2 mt-4">
                                                    <span class="px-3 py-1 bg-blue-100/80 text-blue-700 text-xs font-medium rounded-full border border-blue-200/50">Industry Certified</span>
                                                    <span class="px-3 py-1 bg-cyan-100/80 text-cyan-700 text-xs font-medium rounded-full border border-cyan-200/50">Expert Led</span>
                                                    <span class="px-3 py-1 bg-indigo-100/80 text-indigo-700 text-xs font-medium rounded-full border border-indigo-200/50">Hands-on</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- 3D Status Indicator -->
                                    <div class="relative">
                                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500/20 to-cyan-500/30 rounded-xl flex items-center justify-center group-hover:scale-110 group-hover:rotate-12 transition-all duration-500 shadow-lg">
                                            <div class="w-6 h-6 bg-blue-500 rounded-full animate-pulse shadow-lg"></div>
                                        </div>
                                        <div class="absolute -top-1 -right-1 w-4 h-4 bg-green-400 rounded-full border-2 border-white animate-bounce"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Technical Excellence Card -->
                        <div class="group relative overflow-hidden transform-gpu perspective-1000">
                            <div class="relative px-10 py-8 bg-gradient-to-br from-green-50/90 via-emerald-100/80 to-teal-50/90 backdrop-blur-2xl rounded-3xl shadow-2xl border border-green-200/60 hover:shadow-green-500/25 transition-all duration-700 hover:scale-[1.03] hover:rotate-x-2 hover:rotate-y-1 transform-style-preserve-3d">
                                <!-- Animated Progress Bar -->
                                <div class="absolute top-0 left-0 h-2 bg-gradient-to-r from-green-500 via-emerald-500 to-teal-600 rounded-t-3xl transition-all duration-1000 w-11/12 opacity-80"></div>
                                <div class="absolute top-0 left-0 h-2 bg-gradient-to-r from-green-400 to-emerald-400 rounded-t-3xl transition-all duration-1500 group-hover:w-full w-0 animate-pulse"></div>
                                
                                <!-- 3D Floating Elements -->
                                <div class="absolute -top-4 -right-4 w-16 h-16 bg-gradient-to-br from-green-400/20 to-emerald-500/30 rounded-2xl flex items-center justify-center group-hover:scale-125 group-hover:rotate-12 transition-all duration-500 shadow-lg backdrop-blur-sm border border-green-300/40">
                                    <svg class="w-8 h-8 text-green-600 animate-pulse" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M9.4 16.6L4.8 12l4.6-4.6L8 6l-6 6 6 6 1.4-1.4zm5.2 0l4.6-4.6-4.6-4.6L16 6l6 6-6 6-1.4-1.4z"/>
                                    </svg>
                                </div>
                                <!-- Particle Effects -->
                                <div class="absolute top-4 right-8 w-2 h-2 bg-green-400 rounded-full animate-ping opacity-75"></div>
                                <div class="absolute top-8 right-12 w-1 h-1 bg-emerald-400 rounded-full animate-ping opacity-60" style="animation-delay: 0.5s;"></div>
                                <div class="absolute top-12 right-6 w-1.5 h-1.5 bg-teal-300 rounded-full animate-ping opacity-50" style="animation-delay: 1s;"></div>
                                
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <!-- 3D Animated Icon -->
                                        <div class="relative w-20 h-20 bg-gradient-to-br from-green-500/15 via-emerald-500/10 to-teal-600/15 rounded-2xl flex items-center justify-center group-hover:bg-gradient-to-br group-hover:from-green-500/25 group-hover:via-emerald-500/20 group-hover:to-teal-600/25 transition-all duration-500 shadow-inner border border-green-300/30">
                                            <!-- Glow Effect -->
                                            <div class="absolute inset-0 bg-green-400/20 rounded-2xl blur-md group-hover:blur-lg transition-all duration-500"></div>
                                            <svg class="relative w-10 h-10 text-green-600 group-hover:scale-125 group-hover:rotate-6 transition-all duration-500" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M9.4 16.6L4.8 12l4.6-4.6L8 6l-6 6 6 6 1.4-1.4zm5.2 0l4.6-4.6-4.6-4.6L16 6l6 6-6 6-1.4-1.4z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-bold text-2xl text-gray-900 group-hover:text-green-700 transition-colors duration-500 mb-2">Technical Excellence</div>
                                            <div class="text-base text-gray-600 group-hover:text-gray-700 transition-colors duration-300 mb-4">Master cutting-edge technologies</div>
                                            
                                            <!-- Advanced Progress Visualization -->
                                            <div class="space-y-3">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-sm font-medium text-gray-700">Completion Rate</span>
                                                    <span class="text-sm font-bold text-green-600">92%</span>
                                                </div>
                                                <div class="relative h-3 bg-green-100/50 rounded-full overflow-hidden">
                                                    <div class="absolute inset-0 bg-gradient-to-r from-green-500 via-emerald-500 to-teal-600 rounded-full transition-all duration-2000 w-11/12"></div>
                                                    <div class="absolute inset-0 bg-gradient-to-r from-green-400/50 to-emerald-400/50 rounded-full animate-pulse"></div>
                                                </div>
                                                
                                                <!-- Feature Tags -->
                                                <div class="flex flex-wrap gap-2 mt-4">
                                                    <span class="px-3 py-1 bg-green-100/80 text-green-700 text-xs font-medium rounded-full border border-green-200/50">AI/ML Ready</span>
                                                    <span class="px-3 py-1 bg-emerald-100/80 text-emerald-700 text-xs font-medium rounded-full border border-emerald-200/50">Cloud Native</span>
                                                    <span class="px-3 py-1 bg-teal-100/80 text-teal-700 text-xs font-medium rounded-full border border-teal-200/50">DevOps</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- 3D Status Indicator -->
                                    <div class="relative">
                                        <div class="w-12 h-12 bg-gradient-to-br from-green-500/20 to-emerald-500/30 rounded-xl flex items-center justify-center group-hover:scale-110 group-hover:rotate-12 transition-all duration-500 shadow-lg">
                                            <div class="w-6 h-6 bg-green-500 rounded-full animate-pulse shadow-lg"></div>
                                        </div>
                                        <div class="absolute -top-1 -right-1 w-4 h-4 bg-orange-400 rounded-full border-2 border-white animate-bounce"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Leadership Growth Card -->
                        <div class="group relative overflow-hidden transform-gpu perspective-1000">
                            <div class="relative px-10 py-8 bg-gradient-to-br from-purple-50/90 via-violet-100/80 to-fuchsia-50/90 backdrop-blur-2xl rounded-3xl shadow-2xl border border-purple-200/60 hover:shadow-purple-500/25 transition-all duration-700 hover:scale-[1.03] hover:rotate-x-2 hover:rotate-y-1 transform-style-preserve-3d">
                                <!-- Animated Progress Bar -->
                                <div class="absolute top-0 left-0 h-2 bg-gradient-to-r from-purple-500 via-violet-500 to-fuchsia-600 rounded-t-3xl transition-all duration-1000 w-4/5 opacity-80"></div>
                                <div class="absolute top-0 left-0 h-2 bg-gradient-to-r from-purple-400 to-violet-400 rounded-t-3xl transition-all duration-1500 group-hover:w-full w-0 animate-pulse"></div>
                                
                                <!-- 3D Floating Elements -->
                                <div class="absolute -top-4 -right-4 w-16 h-16 bg-gradient-to-br from-purple-400/20 to-violet-500/30 rounded-2xl flex items-center justify-center group-hover:scale-125 group-hover:rotate-12 transition-all duration-500 shadow-lg backdrop-blur-sm border border-purple-300/40">
                                    <svg class="w-8 h-8 text-purple-600 animate-pulse" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                </div>
                                <!-- Particle Effects -->
                                <div class="absolute top-4 right-8 w-2 h-2 bg-purple-400 rounded-full animate-ping opacity-75"></div>
                                <div class="absolute top-8 right-12 w-1 h-1 bg-violet-400 rounded-full animate-ping opacity-60" style="animation-delay: 0.5s;"></div>
                                <div class="absolute top-12 right-6 w-1.5 h-1.5 bg-fuchsia-300 rounded-full animate-ping opacity-50" style="animation-delay: 1s;"></div>
                                
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <!-- 3D Animated Icon -->
                                        <div class="relative w-20 h-20 bg-gradient-to-br from-purple-500/15 via-violet-500/10 to-fuchsia-600/15 rounded-2xl flex items-center justify-center group-hover:bg-gradient-to-br group-hover:from-purple-500/25 group-hover:via-violet-500/20 group-hover:to-fuchsia-600/25 transition-all duration-500 shadow-inner border border-purple-300/30">
                                            <!-- Glow Effect -->
                                            <div class="absolute inset-0 bg-purple-400/20 rounded-2xl blur-md group-hover:blur-lg transition-all duration-500"></div>
                                            <svg class="relative w-10 h-10 text-purple-600 group-hover:scale-125 group-hover:rotate-6 transition-all duration-500" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-bold text-2xl text-gray-900 group-hover:text-purple-700 transition-colors duration-500 mb-2">Leadership Growth</div>
                                            <div class="text-base text-gray-600 group-hover:text-gray-700 transition-colors duration-300 mb-4">Develop strong management capabilities</div>
                                            
                                            <!-- Advanced Progress Visualization -->
                                            <div class="space-y-3">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-sm font-medium text-gray-700">Growth Rate</span>
                                                    <span class="text-sm font-bold text-purple-600">78%</span>
                                                </div>
                                                <div class="relative h-3 bg-purple-100/50 rounded-full overflow-hidden">
                                                    <div class="absolute inset-0 bg-gradient-to-r from-purple-500 via-violet-500 to-fuchsia-600 rounded-full transition-all duration-2000 w-4/5"></div>
                                                    <div class="absolute inset-0 bg-gradient-to-r from-purple-400/50 to-violet-400/50 rounded-full animate-pulse"></div>
                                                </div>
                                                
                                                <!-- Feature Tags -->
                                                <div class="flex flex-wrap gap-2 mt-4">
                                                    <span class="px-3 py-1 bg-purple-100/80 text-purple-700 text-xs font-medium rounded-full border border-purple-200/50">Strategic</span>
                                                    <span class="px-3 py-1 bg-violet-100/80 text-violet-700 text-xs font-medium rounded-full border border-violet-200/50">Team Building</span>
                                                    <span class="px-3 py-1 bg-fuchsia-100/80 text-fuchsia-700 text-xs font-medium rounded-full border border-fuchsia-200/50">Executive</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- 3D Status Indicator -->
                                    <div class="relative">
                                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500/20 to-violet-500/30 rounded-xl flex items-center justify-center group-hover:scale-110 group-hover:rotate-12 transition-all duration-500 shadow-lg">
                                            <div class="w-6 h-6 bg-purple-500 rounded-full animate-pulse shadow-lg"></div>
                                        </div>
                                        <div class="absolute -top-1 -right-1 w-4 h-4 bg-pink-400 rounded-full border-2 border-white animate-bounce"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Stats Section -->
                        <div class="mt-8 grid grid-cols-3 gap-4">
                            <div class="text-center p-4 bg-white/20 backdrop-blur-sm rounded-xl border border-white/30">
                                <div class="text-2xl font-bold text-blue-600 animate-pulse">500+</div>
                                <div class="text-xs text-gray-600">Courses</div>
                            </div>
                            <div class="text-center p-4 bg-white/20 backdrop-blur-sm rounded-xl border border-white/30">
                                <div class="text-2xl font-bold text-green-600 animate-pulse" style="animation-delay: 0.5s;">10k+</div>
                                <div class="text-xs text-gray-600">Students</div>
                            </div>
                            <div class="text-center p-4 bg-white/20 backdrop-blur-sm rounded-xl border border-white/30">
                                <div class="text-2xl font-bold text-purple-600 animate-pulse" style="animation-delay: 1s;">95%</div>
                                <div class="text-xs text-gray-600">Satisfaction</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="relative">
                    <img src="{{ asset('image.jpeg') }}" alt="Training Session" class="rounded-2xl shadow-2xl w-full">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent rounded-2xl"></div>
                    <div class="absolute bottom-6 left-6 right-6">
                        <div class="bg-white/30 backdrop-blur-xl rounded-lg p-4 border border-white/50">
                            <div class="text-sm font-semibold text-gray-900">Interactive Training Sessions</div>
                            <div class="text-xs text-gray-600 mt-1">Real-time collaboration and feedback</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Slide Panel -->
    <div id="slidePanel" class="fixed inset-y-0 right-0 w-full max-w-md bg-white/30 backdrop-blur-xl shadow-2xl transform translate-x-full transition-transform duration-300 z-50 border-l border-white/50">
        <div class="flex flex-col h-full">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 id="slideTitle" class="text-lg font-semibold text-gray-900"></h3>
                <button onclick="closeSlide()" class="p-2 rounded-md hover:bg-white/50 backdrop-blur-sm transition-colors">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div id="slideContent" class="flex-1 overflow-y-auto p-6">
                <!-- Dynamic content will be inserted here -->
            </div>
        </div>
    </div>

    <!-- Overlay -->
    <div id="slideOverlay" class="fixed inset-0 bg-black/50 hidden z-40" onclick="closeSlide()"></div>
</div>

<script>
function openSlide(type) {
    const panel = document.getElementById('slidePanel');
    const overlay = document.getElementById('slideOverlay');
    const title = document.getElementById('slideTitle');
    const content = document.getElementById('slideContent');
    
    // Check if all required elements exist
    if (!panel || !overlay || !title || !content) {
        console.warn('Slide panel elements not found');
        return;
    }
    
    const slideData = {
        'engineering': {
            title: 'Engineering Teams',
            content: `
                <div class="space-y-6">
                    <div class="bg-blue-50 rounded-lg p-6">
                        <h4 class="font-semibold text-blue-900 mb-3">Technical Excellence</h4>
                        <p class="text-sm text-blue-800">Specialized training for engineering professionals with focus on technical skill development and certification tracking.</p>
                    </div>
                    <div class="space-y-4">
                        <h5 class="font-medium text-gray-900">Key Features:</h5>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li>• Advanced technical workshops</li>
                            <li>• Certification preparation programs</li>
                            <li>• Code review and best practices</li>
                            <li>• System architecture training</li>
                        </ul>
                    </div>
                </div>
            `
        },
        'marketing': {
            title: 'Marketing Departments',
            content: `
                <div class="space-y-6">
                    <div class="bg-green-50 rounded-lg p-6">
                        <h4 class="font-semibold text-green-900 mb-3">Creative Development</h4>
                        <p class="text-sm text-green-800">Marketing-focused training programs covering digital marketing, brand management, and creative strategies.</p>
                    </div>
                </div>
            `
        },
        'rd': {
            title: 'R&D Groups',
            content: `
                <div class="space-y-6">
                    <div class="bg-purple-50 rounded-lg p-6">
                        <h4 class="font-semibold text-purple-900 mb-3">Research Innovation</h4>
                        <p class="text-sm text-purple-800">Research methodologies and innovation training for R&D professionals.</p>
                    </div>
                </div>
            `
        },
        'hr': {
            title: 'HR & Training',
            content: `
                <div class="space-y-6">
                    <div class="bg-red-50 rounded-lg p-6">
                        <h4 class="font-semibold text-red-900 mb-3">People Development</h4>
                        <p class="text-sm text-red-800">HR and employee development programs for organizational growth.</p>
                    </div>
                </div>
            `
        },
        'professional-development': {
            title: 'Professional Development',
            content: `
                <div class="space-y-6">
                    <div class="bg-blue-50 rounded-lg p-6">
                        <h4 class="font-semibold text-blue-900 mb-3">Career Growth</h4>
                        <p class="text-sm text-blue-800">Enhance your team's professional skills with our comprehensive development programs tailored to your industry needs.</p>
                    </div>
                    <div class="space-y-4">
                        <h5 class="font-medium text-gray-900">Program Highlights:</h5>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li>• Industry-specific skill development</li>
                            <li>• Soft skills training</li>
                            <li>• Leadership workshops</li>
                            <li>• Career path planning</li>
                        </ul>
                    </div>
                </div>
            `
        },
        'technical-excellence': {
            title: 'Technical Excellence',
            content: `
                <div class="space-y-6">
                    <div class="bg-green-50 rounded-lg p-6">
                        <h4 class="font-semibold text-green-900 mb-3">Master Technology</h4>
                        <p class="text-sm text-green-800">Master cutting-edge technologies and methodologies with our expert-led technical training sessions.</p>
                    </div>
                    <div class="space-y-4">
                        <h5 class="font-medium text-gray-900">Technical Areas:</h5>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li>• Latest technology trends</li>
                            <li>• Hands-on project experience</li>
                            <li>• Best practices and patterns</li>
                            <li>• Performance optimization</li>
                        </ul>
                    </div>
                </div>
            `
        },
        'leadership-growth': {
            title: 'Leadership Growth',
            content: `
                <div class="space-y-6">
                    <div class="bg-purple-50 rounded-lg p-6">
                        <h4 class="font-semibold text-purple-900 mb-3">Lead with Confidence</h4>
                        <p class="text-sm text-purple-800">Develop strong leadership capabilities and management skills to drive your organization forward.</p>
                    </div>
                    <div class="space-y-4">
                        <h5 class="font-medium text-gray-900">Leadership Focus:</h5>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li>• Strategic thinking</li>
                            <li>• Team management</li>
                            <li>• Decision making</li>
                            <li>• Communication skills</li>
                        </ul>
                    </div>
                </div>
            `
        }
    };
    
    const data = slideData[type];
    if (data) {
        title.textContent = data.title;
        content.innerHTML = data.content;
        panel.classList.remove('translate-x-full');
        overlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function closeSlide() {
    const panel = document.getElementById('slidePanel');
    const overlay = document.getElementById('slideOverlay');
    
    // Check if elements exist before manipulating them
    if (!panel || !overlay) {
        console.warn('Slide panel elements not found');
        return;
    }
    
    panel.classList.add('translate-x-full');
    overlay.classList.add('hidden');
    document.body.style.overflow = 'auto';
}
</script>
    </div>

    <!-- Platform Capabilities Section -->
    <div class="relative bg-gray-50 py-16 overflow-hidden">
        <!-- Floating Digital Product Icons -->
        <div class="floating-element" style="top: 10%; left: 10%; animation-delay: 1.2s;">
            <svg class="w-20 h-20 text-lime-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
            </svg>
        </div>
        
        <div class="floating-element floating-element-reverse" style="top: 50%; right: 10%; animation-delay: 2.8s;">
            <svg class="w-22 h-22 text-fuchsia-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
            </svg>
        </div>
        
        <div class="floating-element" style="bottom: 15%; right: 14%; animation-delay: 4.2s;">
            <svg class="w-24 h-24 text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20 6h-2.18c.11-.31.18-.65.18-1 0-1.66-1.34-3-3-3-1.05 0-1.96.54-2.5 1.35l-.5.67-.5-.68C10.96 2.54 10.05 2 9 2 7.34 2 6 3.34 6 5c0 .35.07.69.18 1H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2z"/>
            </svg>
        </div>
        
        <div class="floating-element floating-element-reverse" style="top: 30%; left: 8%; animation-delay: 1.9s;">
            <svg class="w-26 h-26 text-red-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/>
            </svg>
        </div>
        
        <div class="floating-element" style="bottom: 35%; right: 15%; animation-delay: 3.5s;">
            <svg class="w-22 h-22 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
            </svg>
        </div>
        
        <div class="floating-element floating-element-reverse" style="top: 70%; right: 20%; animation-delay: 5s;">
            <svg class="w-24 h-24 text-green-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
        </div>
        
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <p class="text-lg text-gray-600 mb-4">
                    Discover how our platform transforms organizational learning and development
                </p>
                <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                    Built for Modern Learning Needs
                </h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="relative rounded-xl shadow-lg hover:shadow-xl transition-shadow capability-card overflow-hidden">
                    <div class="relative h-96 bg-gray-100 p-2">
                        <div class="h-full w-full bg-contain bg-center bg-no-repeat rounded-lg" style="background-image: url('{{ asset('download_3.png') }}');">
                        </div>
                    </div>
                    <div class="bg-white p-8 text-center">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Department-Based Structure</h3>
                        <p class="text-gray-600">
                            Organize content and users by departments for targeted learning experiences and better resource management.
                        </p>
                    </div>
                </div>
                
                <div class="relative rounded-xl shadow-lg hover:shadow-xl transition-shadow capability-card overflow-hidden">
                    <div class="relative h-96 bg-gray-100 p-2">
                        <div class="h-full w-full bg-contain bg-center bg-no-repeat rounded-lg" style="background-image: url('{{ asset('interactive_learning.png') }}');">
                        </div>
                    </div>
                    <div class="bg-white p-8 text-center">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Interactive Learning</h3>
                        <p class="text-gray-600">
                            Engage users with dynamic content, assessments, and real-time feedback to enhance knowledge retention.
                        </p>
                    </div>
                </div>
                
                <div class="relative rounded-xl shadow-lg hover:shadow-xl transition-shadow capability-card overflow-hidden">
                    <div class="relative h-96 bg-gray-100 p-2">
                        <div class="h-full w-full bg-contain bg-center bg-no-repeat rounded-lg" style="background-image: url('{{ asset('progress analytics.jpg') }}');">
                        </div>
                    </div>
                    <div class="bg-white p-8 text-center">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Progress Analytics</h3>
                        <p class="text-gray-600">
                            Track learning progress, completion rates, and engagement metrics to optimize your training programs.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Call to Action Section -->
            <div class="mt-16 bg-gradient-to-r from-primary-600 to-primary-700 rounded-2xl p-8 text-center text-white">
                <h3 class="text-2xl font-bold mb-4">Ready to Transform Your Learning Experience?</h3>
                <p class="text-primary-100 mb-8 max-w-2xl mx-auto">
                    Join organizations that are already using Atommart to streamline their learning management and drive better results.
                </p>
                @guest
                    <div class="flex justify-center">
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-md bg-white px-6 py-3 text-base font-semibold text-primary-600 shadow-sm hover:bg-primary-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white transition-colors">
                            Get Started Today
                        </a>
                    </div>
                @else
                    <div class="flex justify-center">
                        <a href="{{ route('resources.index') }}" class="inline-flex items-center justify-center rounded-md bg-white px-6 py-3 text-base font-semibold text-primary-600 shadow-sm hover:bg-primary-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white transition-colors">
                            Browse Resources
                        </a>
                    </div>
                @endguest
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Value proposition scroll-triggered animation
    const valueProposition = document.getElementById('valueProposition');
    if (!valueProposition) return; // Exit if element doesn't exist
    
    const valueText = valueProposition.querySelector('.value-text');
    if (!valueText) return; // Exit if value-text doesn't exist
    
    let hasAnimated = false;
    
    const animateValueProposition = () => {
        if (!hasAnimated) {
            valueText.classList.remove('opacity-0', 'translate-y-8');
            valueText.classList.add('opacity-100', 'translate-y-0');
            hasAnimated = true;
        }
    };
    
    // Intersection Observer for value proposition
    const valueObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && entry.intersectionRatio > 0.3) {
                animateValueProposition();
            }
        });
    }, {
        threshold: 0.3,
        rootMargin: '0px 0px -100px 0px'
    });
    
    valueObserver.observe(valueProposition);
    
    // Add smooth scroll behavior for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Add dynamic hover effects to analytics cards
    const analyticsCards = document.querySelectorAll('.bg-white.dark\\:bg-gray-800.rounded-xl.shadow-lg');
    analyticsCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px)';
            this.style.boxShadow = '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '';
        });
    });
});
</script>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes slideUp {
    from { opacity: 0; transform: translateY(32px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Value proposition animations */
.value-text {
    transition: all 1s cubic-bezier(0.4, 0, 0.2, 1);
}

.value-text.opacity-100 {
    animation: slideUp 1s ease-out;
}

/* Feature cards animations */
.feature-card {
    animation: fadeIn 0.8s ease-out forwards;
    opacity: 0;
}

.feature-card:nth-child(1) { animation-delay: 0.1s; }
.feature-card:nth-child(2) { animation-delay: 0.2s; }
.feature-card:nth-child(3) { animation-delay: 0.3s; }
.feature-card:nth-child(4) { animation-delay: 0.4s; }

/* Platform capabilities animations */
.capability-card {
    animation: fadeIn 0.8s ease-out forwards;
    opacity: 0;
}

.capability-card:nth-child(1) { animation-delay: 0.1s; }
.capability-card:nth-child(2) { animation-delay: 0.2s; }
.capability-card:nth-child(3) { animation-delay: 0.3s; }

/* Smooth scroll behavior */
html {
    scroll-behavior: smooth;
}

/* Enhanced hover effects */
.feature-card:hover,
.capability-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

/* Simplified Slide Animations */
.slide-content {
    transition: opacity 0.6s ease-in-out, transform 0.6s ease-in-out;
    contain: layout style paint;
}

/* Simplified fade animations */
.slide-content[data-direction="fade-in"] {
    opacity: 0;
    transform: translateY(20px);
}

/* Active slide states - smooth and elegant */
.slide-content.active {
    opacity: 1 !important;
    transform: translateX(0) translateY(0) scale(1) !important;
    filter: blur(0px) !important;
}

/* Seamless child element animations */
.slide-content .grid > div {
    transition: all 0.6s cubic-bezier(0.4, 0.0, 0.2, 1);
    will-change: transform, opacity;
}

.slide-content:not(.active) .grid > div {
    opacity: 0.3;
    transform: translateY(20px) scale(0.95);
}

.slide-content.active .grid > div {
    opacity: 1;
    transform: translateY(0) scale(1);
}

.slide-content.active .grid > div:nth-child(1) { 
    transition-delay: 0.1s; 
}
.slide-content.active .grid > div:nth-child(2) { 
    transition-delay: 0.15s; 
}
.slide-content.active .grid > div:nth-child(3) { 
    transition-delay: 0.2s; 
}
.slide-content.active .grid > div:nth-child(4) { 
    transition-delay: 0.25s; 
}

/* Simple slide transitions */
.slide-content:not(.active) {
    pointer-events: none;
}

.slide-content.active {
    pointer-events: auto;
}


/* Smooth dot indicator animations */
#carousel-dots button {
    transition: all 0.4s cubic-bezier(0.4, 0.0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

#carousel-dots button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
    transition: left 0.5s ease-out;
}

#carousel-dots button.active::before {
    left: 100%;
}

/* Ensure smooth initial state */
.slide-content:not(.active) {
    opacity: 0;
}

/* Prevent layout shifts during transitions */
#carousel-container {
    contain: layout style paint;
}

/* Optimized video performance */
video.lazy-video {
    will-change: auto;
    transform: translateZ(0);
    backface-visibility: hidden;
    -webkit-backface-visibility: hidden;
    -moz-backface-visibility: hidden;
    contain: layout style paint;
    image-rendering: optimizeSpeed;
    image-rendering: -webkit-optimize-contrast;
    transition: opacity 0.3s ease;
}

video.lazy-video:not(.loaded) {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    background-size: 400% 400%;
    animation: shimmer 2s ease-in-out infinite;
}

@keyframes shimmer {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

/* Smooth container transitions */
.slide-content .aspect-video {
    transition: transform 0.6s cubic-bezier(0.4, 0.0, 0.2, 1);
    contain: layout style paint;
}

.slide-content:not(.active) .aspect-video {
    transform: scale(0.98);
}

.slide-content.active .aspect-video {
    transform: scale(1);
}

/* Scroll performance optimization - targeted approach */
.slide-content, video.lazy-video, .tilt-card {
    -webkit-transform: translateZ(0);
    -moz-transform: translateZ(0);
    -ms-transform: translateZ(0);
    -o-transform: translateZ(0);
    transform: translateZ(0);
}

/* Reduce paint operations */
.slide-content .grid > div {
    contain: layout style;
    will-change: transform, opacity;
}

/* Optimize text rendering */
body, .slide-content {
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-rendering: optimizeSpeed;
}
</style>

<script>
// Simplified Slide Carousel
let currentSlide = 0;
let isTransitioning = false;
let autoSlideInterval;
const totalSlides = 2;
const slideInterval = 8000; // 8 seconds per slide for better readability

// Animation directions for each slide
const slideDirections = ['fade-in', 'fade-in'];

function updateCarousel() {
    // Smoothly hide all slides first
    for (let i = 0; i < totalSlides; i++) {
        const slide = document.getElementById(`slide-${i}`);
        const dot = document.getElementById(`dot-${i}`);
        
        if (slide && i !== currentSlide) {
            slide.classList.remove('active');
        }
        
        // Update dots with smooth animation
        if (dot) {
            if (i === currentSlide) {
                dot.className = 'h-2 w-8 bg-blue-600 rounded-full transition-all duration-300 active';
            } else {
                dot.className = 'h-2 w-2 bg-gray-300 rounded-full transition-all duration-300';
            }
        }
    }
    
    // Activate current slide with seamless transition
    setTimeout(() => {
        const currentSlideEl = document.getElementById(`slide-${currentSlide}`);
        if (currentSlideEl) {
            currentSlideEl.classList.add('active');
        }
    }, 100);
}

function nextSlide() {
    if (isTransitioning) return;
    isTransitioning = true;
    
    currentSlide = (currentSlide + 1) % totalSlides;
    updateCarousel();
    
    setTimeout(() => {
        isTransitioning = false;
    }, 1000);
}

function previousSlide() {
    if (isTransitioning) return;
    isTransitioning = true;
    
    currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
    updateCarousel();
    
    setTimeout(() => {
        isTransitioning = false;
    }, 1000);
}

function goToSlide(slideIndex) {
    if (isTransitioning || slideIndex === currentSlide) return;
    isTransitioning = true;
    
    currentSlide = slideIndex;
    updateCarousel();
    
    // Reset auto-slide timer
    clearInterval(autoSlideInterval);
    startAutoSlide();
    
    setTimeout(() => {
        isTransitioning = false;
    }, 1000);
}

function startAutoSlide() {
    autoSlideInterval = setInterval(() => {
        if (!isTransitioning) {
            nextSlide();
        }
    }, slideInterval);
}

// Initialize carousel when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Set initial slide as active
    const initialSlide = document.getElementById('slide-0');
    if (initialSlide) {
        initialSlide.classList.add('active');
        initialSlide.style.opacity = '1';
    }
    
    updateCarousel();
    startAutoSlide();
    
    // Pause auto-slide on hover
    const carouselContainer = document.getElementById('carousel-container');
    if (carouselContainer) {
        carouselContainer.addEventListener('mouseenter', () => {
            clearInterval(autoSlideInterval);
        });
        
        carouselContainer.addEventListener('mouseleave', () => {
            startAutoSlide();
        });
    }
});

// Pause auto-slide when page is not visible
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        clearInterval(autoSlideInterval);
    } else {
        startAutoSlide();
    }
});

// Load optimized video system
document.addEventListener('DOMContentLoaded', function() {
    // Initialize the new video loader
    if (window.lazyVideoLoader) {
        console.log('Video loader initialized:', window.lazyVideoLoader.getStats());
    }
    
    // Legacy fallback for browsers without module support
    const legacyVideoInit = () => {
        console.log('Using legacy video initialization');
    
    // Detect network speed for adaptive loading
    if ('connection' in navigator) {
        const connection = navigator.connection;
        if (connection.effectiveType === 'slow-2g' || connection.effectiveType === '2g') {
            networkSpeed = 'slow';
        } else if (connection.effectiveType === '3g') {
            networkSpeed = 'medium';
        }
    }
    
    // GPU acceleration for all videos
    function enableGPUAcceleration(video) {
        video.style.transform = 'translateZ(0)';
        video.style.backfaceVisibility = 'hidden';
        video.style.perspective = '1000px';
        video.style.willChange = 'transform, opacity';
    }
    
    // Preload the hero video with maximum optimization
    const heroVideo = document.querySelector('video.lazy-video');
    if (heroVideo) {
        // Set hero video to highest priority
        heroVideo.style.zIndex = '10';
        heroVideo.preload = 'auto';
        heroVideo.setAttribute('playsinline', '');
        heroVideo.setAttribute('webkit-playsinline', '');
        enableGPUAcceleration(heroVideo);
        
        // Optimize hero video for instant playback
        heroVideo.style.objectFit = 'cover';
        heroVideo.style.objectPosition = 'center';
        
        loadVideo(heroVideo, true);
        
        // Enhanced hero video loading
        heroVideo.addEventListener('loadstart', function() {
            heroVideo.style.filter = 'blur(0px)';
        });
        
        heroVideo.addEventListener('loadeddata', function() {
            heroVideo.currentTime = 0;
            heroVideo.style.opacity = '1';
            if (!heroVideo.paused) {
                heroVideo.play().catch(e => console.log('Hero video autoplay prevented'));
            }
        });
        
        // Preload buffer for smooth playback
        heroVideo.addEventListener('progress', function() {
            if (heroVideo.buffered.length > 0) {
                const bufferedEnd = heroVideo.buffered.end(heroVideo.buffered.length - 1);
                const duration = heroVideo.duration;
                if (bufferedEnd / duration > 0.3) { // 30% buffered
                    heroVideo.classList.add('well-buffered');
                }
            }
        });
    }
    
    function loadVideo(video, immediate = false) {
        if (loadedVideos.has(video)) return;
        
        const source = video.querySelector('source[data-src]');
        if (!source) return;
        
        loadedVideos.add(video);
        
        // Apply GPU acceleration immediately
        enableGPUAcceleration(video);
        
        // Adaptive loading based on network speed
        const videoSrc = source.dataset.src;
        
        // Cache video URL for faster subsequent loads
        if (!videoCache.has(videoSrc)) {
            videoCache.set(videoSrc, {
                url: videoSrc,
                loadTime: Date.now(),
                priority: immediate ? 'high' : 'normal'
            });
        }
        
        // Direct loading approach for better performance
        source.src = videoSrc;
        source.removeAttribute('data-src');
        
        // Conservative preload strategy to prevent overwhelming
        video.preload = 'metadata';
        video.muted = true;
        video.playsInline = true;
        video.setAttribute('playsinline', '');
        video.setAttribute('webkit-playsinline', '');
        
        // Optimize video rendering
        video.style.objectFit = 'cover';
        video.style.objectPosition = 'center';
        video.style.imageRendering = 'optimizeSpeed';
        video.style.imageRendering = '-webkit-optimize-contrast';
        
        // Mark video as loaded for styling
        video.classList.add('loaded');
        
        // Enhanced loading events with performance monitoring
        let loadStartTime = Date.now();
        
        video.addEventListener('loadstart', function() {
            video.style.opacity = '0.8';
            video.style.filter = 'blur(1px)';
            loadStartTime = Date.now();
        });
        
        video.addEventListener('loadedmetadata', function() {
            // Optimize video dimensions for performance
            if (video.videoWidth > 1920 || video.videoHeight > 1080) {
                video.style.maxWidth = '1920px';
                video.style.maxHeight = '1080px';
            }
        });
        
        video.addEventListener('canplay', function() {
            const loadTime = Date.now() - loadStartTime;
            console.log(`Video loaded in ${loadTime}ms`);
            
            video.style.opacity = '1';
            video.style.filter = 'none';
            
            if (immediate || isVideoInViewport(video)) {
                // Use double requestAnimationFrame for ultra-smooth playback
                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        video.play().catch(function(error) {
                            console.log('Video autoplay prevented:', error);
                        });
                    });
                });
            }
        }, { once: true });
        
        // Advanced buffering management
        video.addEventListener('progress', function() {
            if (video.buffered.length > 0) {
                const bufferedEnd = video.buffered.end(video.buffered.length - 1);
                const duration = video.duration;
                const bufferedPercent = (bufferedEnd / duration) * 100;
                
                // Add buffering classes for CSS optimizations
                if (bufferedPercent > 25) video.classList.add('quarter-buffered');
                if (bufferedPercent > 50) video.classList.add('half-buffered');
                if (bufferedPercent > 75) video.classList.add('well-buffered');
            }
        });
        
        // Error handling with retry mechanism
        video.addEventListener('error', function(e) {
            console.log('Video error, attempting retry:', e);
            setTimeout(() => {
                if (!video.src) {
                    source.src = videoSrc;
                    video.load();
                }
            }, 1000);
        });
        
        // Load the video with timeout protection
        try {
            video.load();
        } catch (error) {
            console.log('Video load error:', error);
        }
    }
    
    function isVideoInViewport(video) {
        const rect = video.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }
    
    function processVideoQueue() {
        if (isProcessingQueue || videoLoadQueue.length === 0) return;
        
        isProcessingQueue = true;
        const video = videoLoadQueue.shift();
        
        loadVideo(video);
        
        // Process next video with longer delay to prevent overwhelming
        setTimeout(() => {
            isProcessingQueue = false;
            processVideoQueue();
        }, 500);
    }
    
    // Optimized Intersection Observer for carousel videos
    if ('IntersectionObserver' in window) {
        const videoObserver = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                const video = entry.target;
                
                if (entry.isIntersecting) {
                    // Add to queue if not already loaded
                    if (!loadedVideos.has(video) && !videoLoadQueue.includes(video)) {
                        videoLoadQueue.push(video);
                        processVideoQueue();
                    }
                    
                    // Play if already loaded
                    if (loadedVideos.has(video) && video.readyState >= 3) {
                        video.play().catch(function(error) {
                            console.log('Video play prevented:', error);
                        });
                    }
                } else {
                    // Pause when not visible to save resources
                    if (!video.paused) {
                        video.pause();
                    }
                }
            });
        }, {
            threshold: 0.3,
            rootMargin: '50px 0px'
        });

        // Observe all lazy videos except the hero video
        lazyVideos.forEach(function(video, index) {
            if (index > 0) { // Skip hero video (already loaded)
                videoObserver.observe(video);
            }
        });
    }
    
    // Carousel-specific video management
    function manageCarouselVideos() {
        const currentSlideEl = document.getElementById(`slide-${currentSlide}`);
        if (!currentSlideEl) return;
        
        // Load and play videos in current slide
        const currentVideos = currentSlideEl.querySelectorAll('video');
        currentVideos.forEach(video => {
            if (!loadedVideos.has(video)) {
                loadVideo(video);
            } else if (video.readyState >= 3) {
                video.play().catch(error => console.log('Video play prevented:', error));
            }
        });
        
        // Pause videos in other slides
        for (let i = 0; i < totalSlides; i++) {
            if (i !== currentSlide) {
                const slide = document.getElementById(`slide-${i}`);
                if (slide) {
                    const videos = slide.querySelectorAll('video');
                    videos.forEach(video => {
                        if (!video.paused) {
                            video.pause();
                        }
                    });
                }
            }
        }
    }
    
    // Hook into carousel updates
    const originalUpdateCarousel = window.updateCarousel;
    window.updateCarousel = function() {
        if (originalUpdateCarousel) {
            originalUpdateCarousel();
        }
        // Manage videos after carousel update
        setTimeout(manageCarouselVideos, 100);
    };
    
    // Advanced Performance Monitoring and Adaptive Quality System
    let performanceMetrics = {
        frameDrops: 0,
        avgLoadTime: 0,
        memoryUsage: 0,
        cpuUsage: 'unknown'
    };
    
    function optimizeVideoPlayback() {
        const videos = document.querySelectorAll('video.lazy-video');
        
        // Detect device performance level
        const deviceMemory = navigator.deviceMemory || 4; // Default to 4GB
        const hardwareConcurrency = navigator.hardwareConcurrency || 4; // Default to 4 cores
        
        let qualityLevel = 'high';
        if (deviceMemory < 4 || hardwareConcurrency < 4) {
            qualityLevel = 'medium';
        }
        if (deviceMemory < 2 || hardwareConcurrency < 2) {
            qualityLevel = 'low';
        }
        
        videos.forEach((video, index) => {
            // Apply GPU acceleration
            enableGPUAcceleration(video);
            
            // Adaptive quality based on device performance
            switch (qualityLevel) {
                case 'low':
                    video.style.maxWidth = '720px';
                    video.style.maxHeight = '480px';
                    video.preload = 'metadata';
                    break;
                case 'medium':
                    video.style.maxWidth = '1280px';
                    video.style.maxHeight = '720px';
                    video.preload = 'auto';
                    break;
                case 'high':
                default:
                    video.style.maxWidth = '1920px';
                    video.style.maxHeight = '1080px';
                    video.preload = 'auto';
                    break;
            }
            
            // Stagger video initialization to prevent overwhelming the browser
            setTimeout(() => {
                // Monitor video performance
                monitorVideoPerformance(video);
                
                // Optimize based on viewport position
                if (index === 0) {
                    // Hero video gets highest priority
                    video.style.zIndex = '10';
                    video.setAttribute('priority', 'high');
                } else {
                    video.setAttribute('priority', 'normal');
                }
            }, index * 100);
        });
        
        // Global performance optimization
        if (qualityLevel === 'low') {
            // Disable some animations on low-end devices
            document.documentElement.style.setProperty('--animation-duration', '0.1s');
        }
    }
    
    function monitorVideoPerformance(video) {
        let frameCount = 0;
        let lastTime = performance.now();
        
        function checkFrameRate() {
            const currentTime = performance.now();
            const deltaTime = currentTime - lastTime;
            
            if (deltaTime >= 1000) { // Check every second
                const fps = Math.round((frameCount * 1000) / deltaTime);
                
                if (fps < 24) { // If FPS drops below 24
                    performanceMetrics.frameDrops++;
                    
                    // Reduce quality automatically
                    if (video.style.maxWidth !== '720px') {
                        video.style.maxWidth = '720px';
                        video.style.maxHeight = '480px';
                        console.log('Reduced video quality due to performance');
                    }
                }
                
                frameCount = 0;
                lastTime = currentTime;
            }
            
            frameCount++;
            
            if (!video.paused && !video.ended) {
                requestAnimationFrame(checkFrameRate);
            }
        }
        
        video.addEventListener('play', () => {
            requestAnimationFrame(checkFrameRate);
        });
    }
    
    // Memory management for videos
    function manageVideoMemory() {
        const videos = document.querySelectorAll('video.lazy-video');
        const visibleVideos = [];
        const hiddenVideos = [];
        
        videos.forEach(video => {
            if (isVideoInViewport(video)) {
                visibleVideos.push(video);
            } else {
                hiddenVideos.push(video);
            }
        });
        
        // Limit concurrent video playback more conservatively
        const deviceMemory = navigator.deviceMemory || 4;
        const maxConcurrentVideos = Math.max(1, Math.floor(deviceMemory / 4));
        
        if (visibleVideos.length > maxConcurrentVideos) {
            // Pause excess videos
            visibleVideos.slice(maxConcurrentVideos).forEach(video => {
                if (!video.paused) {
                    video.pause();
                }
            });
        }
        
        // Unload videos that are far from viewport
        hiddenVideos.forEach(video => {
            const rect = video.getBoundingClientRect();
            const distanceFromViewport = Math.abs(rect.top - window.innerHeight);
            
            if (distanceFromViewport > window.innerHeight * 2) {
                // Unload video to free memory
                if (video.src && !video.paused) {
                    video.pause();
                    video.currentTime = 0;
                }
            }
        });
    }
    
    // Initialize video optimization
    optimizeVideoPlayback();
    
    // Throttle scroll events for better performance with memory management
    let scrollTimeout;
    let memoryTimeout;
    
    window.addEventListener('scroll', function() {
        if (scrollTimeout) {
            clearTimeout(scrollTimeout);
        }
        
        scrollTimeout = setTimeout(function() {
            // Re-evaluate video visibility after scroll
            document.querySelectorAll('video.lazy-video.loaded').forEach(video => {
                if (isVideoInViewport(video) && video.paused) {
                    video.play().catch(error => console.log('Video play prevented:', error));
                } else if (!isVideoInViewport(video) && !video.paused) {
                    video.pause();
                }
            });
            
            // Manage memory every few scroll events
            if (memoryTimeout) {
                clearTimeout(memoryTimeout);
            }
            memoryTimeout = setTimeout(manageVideoMemory, 1000);
        }, 200);
    }, { passive: true });
    
    // Periodic memory cleanup - less frequent
    setInterval(manageVideoMemory, 30000); // Every 30 seconds
    
    // Page visibility optimization
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            // Pause all videos when page is hidden
            document.querySelectorAll('video.lazy-video').forEach(video => {
                if (!video.paused) {
                    video.pause();
                    video.setAttribute('data-was-playing', 'true');
                }
            });
        } else {
            // Resume videos that were playing when page becomes visible
            document.querySelectorAll('video.lazy-video[data-was-playing="true"]').forEach(video => {
                if (isVideoInViewport(video)) {
                    video.play().catch(error => console.log('Video resume prevented:', error));
                }
                video.removeAttribute('data-was-playing');
            });
        }
    });
    
    // Ensure wave animations are active
    document.querySelectorAll('.wave-divider svg path').forEach((path, index) => {
        path.style.animationPlayState = 'running';
        path.style.display = 'block';
    });
    
    // Initialize legacy fallback if needed
    if (!window.lazyVideoLoader) {
        legacyVideoInit();
    }
    
    // Emergency video loading fix
    setTimeout(() => {
        const videoContainers = document.querySelectorAll('.video-bg');
        videoContainers.forEach((container) => {
            const video = container.querySelector('.lazy-video');
            const poster = container.querySelector('.video-poster');
            
            if (video) {
                // Load video sources
                const sources = video.querySelectorAll('source[data-src]');
                sources.forEach(source => {
                    if (source.getAttribute('data-src')) {
                        source.src = source.getAttribute('data-src');
                        source.removeAttribute('data-src');
                    }
                });
                
                video.load();
                
                // Set up video ready event
                video.addEventListener('canplay', () => {
                    video.style.opacity = '1';
                    if (poster) poster.style.opacity = '0';
                });
                
                // Auto-play when in view
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting && video.readyState >= 3) {
                            video.play().catch(() => {});
                        }
                    });
                }, { threshold: 0.3 });
                
                observer.observe(container);
            }
        });
    }, 1000);
    
    }; // Close legacyVideoInit function
});

// Load the optimized video loader
const script = document.createElement('script');
script.src = '{{ asset('js/lazy-video-loader.js') }}';
script.async = true;
document.head.appendChild(script);

// Fix video quality and performance immediately
setTimeout(() => {
    // Remove blur and improve quality
    document.querySelectorAll('.lazy-video, .video-poster').forEach(element => {
        element.style.filter = 'none';
        element.style.imageRendering = 'high-quality';
        element.style.imageRendering = '-webkit-optimize-contrast';
        element.style.backfaceVisibility = 'hidden';
        element.style.transform = 'translate3d(0,0,0)';
    });
    
    // Optimize video attributes for performance
    document.querySelectorAll('.lazy-video').forEach(video => {
        video.setAttribute('playsinline', '');
        video.setAttribute('preload', 'metadata');
        video.style.objectFit = 'cover';
        video.style.objectPosition = 'center';
        
        // Force reload if already loaded
        if (video.src || video.querySelector('source[src]')) {
            video.load();
        }
    });
    
    console.log('✓ Video quality optimized');
}, 500);

// Prevent video memory leaks and performance degradation
let videoCleanupInterval;
let performanceMonitor = {
    frameDrops: 0,
    lastTime: performance.now(),
    
    monitor() {
        const now = performance.now();
        const delta = now - this.lastTime;
        
        // Check for performance issues
        if (delta > 50) { // Slower than 20fps
            this.frameDrops++;
            
            // If consistent performance issues, optimize
            if (this.frameDrops > 5) {
                document.querySelectorAll('.lazy-video').forEach(video => {
                    // Reduce quality temporarily
                    video.style.filter = 'blur(0.5px)';
                    video.preload = 'none';
                });
                
                // Reset after a moment
                setTimeout(() => {
                    document.querySelectorAll('.lazy-video').forEach(video => {
                        video.style.filter = 'none';
                        video.preload = 'metadata';
                    });
                    this.frameDrops = 0;
                }, 2000);
            }
        } else {
            this.frameDrops = Math.max(0, this.frameDrops - 1);
        }
        
        this.lastTime = now;
        requestAnimationFrame(() => this.monitor());
    }
};

// Start performance monitoring
setTimeout(() => {
    performanceMonitor.monitor();
    
    // Clean up video resources periodically
    videoCleanupInterval = setInterval(() => {
        document.querySelectorAll('.lazy-video').forEach(video => {
            // Pause videos not in viewport to save memory
            const rect = video.getBoundingClientRect();
            const isVisible = rect.top < window.innerHeight && rect.bottom > 0;
            
            if (!isVisible && !video.paused) {
                video.pause();
            } else if (isVisible && video.paused && video.muted) {
                video.play().catch(() => {});
            }
            
            // Clear buffer if video has been paused too long
            if (video.paused && video.currentTime > 0) {
                setTimeout(() => {
                    if (video.paused) {
                        video.currentTime = 0;
                    }
                }, 10000);
            }
        });
    }, 5000); // Every 5 seconds
    
    console.log('🔄 Video performance monitoring and cleanup active');
}, 1000);
</script>
@endsection
