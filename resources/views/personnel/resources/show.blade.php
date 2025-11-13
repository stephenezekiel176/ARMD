@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 p-6 rounded">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">{{ $resource->title }}</h1>
        <a href="{{ route('personnel.resources.index') }}" class="text-sm text-primary-600 hover:underline">Back to Resources</a>
    </div>

    <!-- Resource Info -->
    <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <span class="text-sm text-gray-500">Type</span>
                <div class="font-medium">{{ ucfirst($resource->type) }}</div>
            </div>
            <div>
                <span class="text-sm text-gray-500">Facilitator</span>
                <div class="font-medium">{{ $resource->facilitator->fullname }}</div>
            </div>
            <div>
                <span class="text-sm text-gray-500">Duration</span>
                <div class="font-medium">{{ $resource->duration ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Resource Content -->
    <div class="mb-6">
        @if($resource->type === 'video')
            <div class="aspect-w-16 aspect-h-9 bg-black rounded-lg overflow-hidden">
                <video controls class="w-full h-full" preload="metadata">
                    <source src="{{ route('personnel.resources.stream', $resource->id) }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
        @elseif($resource->type === 'ebook')
            <div class="border rounded-lg border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium">E-book Viewer</h3>
                    <a href="{{ route('personnel.resources.file', $resource->id) }}" 
                       download="{{ $resource->title }}.pdf"
                       class="px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700 text-sm">
                        Download PDF
                    </a>
                </div>
                <div class="bg-gray-100 dark:bg-gray-800 rounded p-4 h-96 overflow-auto">
                    <iframe src="{{ route('personnel.resources.file', $resource->id) }}" 
                            class="w-full h-full border-0"
                            title="E-book viewer">
                        <p>Your browser does not support PDF viewing. <a href="{{ route('personnel.resources.file', $resource->id) }}" download>Download the PDF</a> instead.</p>
                    </iframe>
                </div>
            </div>
        @endif
    </div>

    <!-- Description -->
    @if($resource->description)
        <div class="mb-6">
            <h3 class="text-lg font-medium mb-2">Description</h3>
            <p class="text-gray-700 dark:text-gray-300">{{ $resource->description }}</p>
        </div>
    @endif

    <!-- Enrollment Section -->
    <div class="border-t pt-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-medium">Course Enrollment</h3>
                <p class="text-sm text-gray-500">Enroll to track your progress and access assessments</p>
            </div>
            
            @if($isEnrolled)
                <div class="text-center">
                    <span class="px-4 py-2 bg-green-100 text-green-700 rounded-full">
                        ✓ Already Enrolled
                    </span>
                    <div class="mt-2">
                        <a href="{{ route('personnel.dashboard') }}" class="text-sm text-primary-600 hover:underline">
                            View in Dashboard
                        </a>
                    </div>
                </div>
            @else
                <form action="{{ route('personnel.enroll.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="course_id" value="{{ $resource->id }}">
                    <button type="submit" 
                            onclick="return confirm('Are you sure you want to enroll in this course? This will add it to your dashboard.')"
                            class="px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 font-medium">
                        Enroll in This Course
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Related Resources -->
    <div class="mt-8">
        <h3 class="text-lg font-medium mb-4">Related Resources in {{ $resource->department->name }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($relatedResources as $related)
                <div class="border rounded-lg border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-800 dark:text-gray-100">{{ $related->title }}</h4>
                            <p class="text-sm text-gray-500">{{ ucfirst($related->type) }} • {{ $related->duration ?? 'N/A' }}</p>
                        </div>
                        <a href="{{ route('personnel.resources.show', $related->id) }}" 
                           class="px-3 py-1 text-sm text-primary-600 hover:underline">
                            View
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
