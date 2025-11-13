@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Resource Header -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('resources.index') }}" class="text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 mr-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $resource->title }}</h1>
                    <div class="flex items-center space-x-2 mt-1">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                            {{ $resource->type === 'video' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' }}">
                            {{ ucfirst($resource->type) }}
                        </span>
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $resource->duration ?? 'N/A' }} minutes
                        </span>
                        @if($resource->is_previewable)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                Preview Available
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Resource Content -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Course Content</h2>
                
                @if($resource->type === 'video')
                    <div class="aspect-video bg-black rounded-lg overflow-hidden mb-4">
                        @if(auth()->check() && $isEnrolled)
                            <video controls class="w-full h-full" preload="metadata">
                                <source src="{{ route('resources.file', $resource->id) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-900">
                                <div class="text-center">
                                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-gray-400 mb-4">Video Preview</p>
                                    @if($resource->is_previewable)
                                        <p class="text-sm text-gray-500">Enroll to access the full video content</p>
                                    @else
                                        <p class="text-sm text-gray-500">Please enroll to access this course</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                @elseif($resource->type === 'ebook')
                    <div class="border rounded-lg border-gray-200 dark:border-gray-700 p-4">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium">E-book Viewer</h3>
                            @if(auth()->check() && $isEnrolled)
                                <a href="{{ route('resources.file', $resource->id) }}" 
                                   download="{{ $resource->title }}.pdf"
                                   class="px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700 text-sm">
                                    Download PDF
                                </a>
                            @endif
                        </div>
                        @if(auth()->check() && $isEnrolled)
                            <div class="bg-gray-100 dark:bg-gray-800 rounded p-4 h-96 overflow-auto">
                                <iframe src="{{ route('resources.file', $resource->id) }}" 
                                        class="w-full h-full border-0"
                                        title="E-book viewer">
                                    <p>Your browser does not support PDF viewing. <a href="{{ route('resources.file', $resource->id) }}" download>Download the PDF</a> instead.</p>
                                </iframe>
                            </div>
                        @else
                            <div class="bg-gray-100 dark:bg-gray-800 rounded p-4 h-96 flex items-center justify-center">
                                <div class="text-center">
                                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    <p class="text-gray-400 mb-4">E-book Preview</p>
                                    @if($resource->is_previewable)
                                        <p class="text-sm text-gray-500">Enroll to access the full e-book content</p>
                                    @else
                                        <p class="text-sm text-gray-500">Please enroll to access this e-book</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Description -->
            @if($resource->description)
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Course Description</h2>
                    <div class="prose prose-sm dark:prose-invert max-w-none">
                        <p class="text-gray-600 dark:text-gray-400">{{ $resource->description }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Course Info -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Course Information</h2>
                <div class="space-y-3">
                    <div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Type</span>
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100 capitalize">{{ $resource->type }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Department</span>
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $resource->department->name }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Duration</span>
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $resource->duration ?? 'N/A' }} minutes</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Facilitator</span>
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $resource->facilitator->fullname }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Created</span>
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $resource->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Enrollment Section -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Enrollment</h2>
                
                @guest
                    <div class="text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Login to enroll in this course</p>
                        <a href="{{ route('login') }}" 
                           class="w-full px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 font-medium block text-center mb-2">
                            Sign In
                        </a>
                        <a href="{{ route('register') }}" 
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 font-medium block text-center">
                            Create Account
                        </a>
                    </div>
                @else
                    @if($isEnrolled)
                        <div class="text-center">
                            <span class="inline-flex items-center px-4 py-2 rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Enrolled
                            </span>
                            <div class="mt-4">
                                <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="text-sm text-primary-600 hover:underline">
                                    View in Dashboard
                                </a>
                            </div>
                        </div>
                    @else
                        <form action="{{ route('resources.enroll', $resource->id) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    onclick="return confirm('Are you sure you want to enroll in this course?')"
                                    class="w-full px-4 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 font-medium">
                                Enroll Now
                            </button>
                        </form>
                        @if($resource->is_previewable)
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 text-center">
                                You can preview this course without enrolling
                            </p>
                        @endif
                    @endif
                @endguest
            </div>
        </div>
    </div>
</div>
@endsection
