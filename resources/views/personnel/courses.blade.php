@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 p-6 rounded">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">My Enrolled Courses</h1>
        <a href="{{ route('personnel.resources.index') }}" class="text-sm text-primary-600 hover:underline">Browse More Resources</a>
    </div>

    @if($enrollments->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($enrollments as $enrollment)
                <div class="border rounded-lg border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg transition">
                    <!-- Course Header -->
                    <div class="p-4 bg-gray-50 dark:bg-gray-900">
                        <div class="flex items-center justify-between mb-2">
                            <span class="px-2 py-1 text-xs rounded font-medium 
                                {{ $enrollment->course->type === 'video' ? 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' : 
                                   ($enrollment->course->type === 'ebook' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 
                                   ($enrollment->course->type === 'audio' ? 'bg-purple-100 text-purple-700 dark:bg-purple-900 dark:text-purple-300' : 
                                   ($enrollment->course->type === 'image' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 
                                   'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300'))) }}">
                                {{ $enrollment->course->type === 'video' ? '📹 Video' : 
                                   ($enrollment->course->type === 'ebook' ? '📚 E-Book' : 
                                   ($enrollment->course->type === 'audio' ? '🎵 Audio' : 
                                   ($enrollment->course->type === 'image' ? '🖼️ Image' : '🎙️ Podcast'))) }}
                            </span>
                            <span class="px-2 py-1 text-xs rounded {{ $enrollment->status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' }}">
                                {{ ucfirst($enrollment->status) }}
                            </span>
                        </div>
                        <h3 class="font-semibold text-gray-800 dark:text-gray-100 mb-2">{{ $enrollment->course->title }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $enrollment->course->description ? Str::limit($enrollment->course->description, 80) : 'No description available' }}
                        </p>
                    </div>

                    <!-- Course Details -->
                    <div class="p-4">
                        <div class="text-sm text-gray-500 mb-3">
                            <div>Facilitator: {{ $enrollment->course->facilitator->fullname }}</div>
                            <div>Duration: {{ $enrollment->course->duration ?? 'N/A' }}</div>
                            <div>Enrolled: {{ $enrollment->created_at->format('M d, Y') }}</div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Progress</span>
                                <span class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $enrollment->progress }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                                <div class="bg-primary-600 h-2 rounded-full transition-all duration-300" style="width: {{ $enrollment->progress }}%"></div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-2">
                            <a href="{{ route('personnel.resources.show', $enrollment->course->id) }}" 
                               class="flex-1 text-center px-3 py-2 bg-primary-600 text-white rounded hover:bg-primary-700 text-sm">
                                {{ $enrollment->status === 'completed' ? 'Review' : 'Continue' }}
                            </a>
                            
                            @if($enrollment->course->assessments->count() > 0)
                                <a href="{{ route('personnel.assessments.index') }}" 
                                   class="px-3 py-2 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 text-sm">
                                    Assessments
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $enrollments->links() }}
        </div>
    @else
        <div class="text-center py-8">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No courses enrolled yet</h3>
            <p class="mt-1 text-sm text-gray-500">Start by browsing available resources and enrolling in courses.</p>
            <div class="mt-6">
                <a href="{{ route('personnel.resources.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700">
                    Browse Resources
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
