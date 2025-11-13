@extends('layouts.app')

@section('title', $department->name . ' - ' . config('app.name'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Department Header -->
    <div class="bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-2xl p-8 mb-8">
        <div class="flex items-center space-x-4">
            @if($department->head_image)
                <img src="{{ asset('storage/' . $department->head_image) }}" 
                     alt="Department Head" 
                     class="h-16 w-16 rounded-full object-cover border-2 border-white/30">
            @elseif($department->icon)
                <div class="h-16 w-16 bg-white/20 rounded-lg flex items-center justify-center text-2xl">
                    {{ $department->icon }}
                </div>
            @else
                <div class="h-16 w-16 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            @endif
            <div>
                <h1 class="text-3xl font-bold">{{ $department->name }}</h1>
                @if($department->special_code)
                    <p class="text-primary-100">Code: {{ $department->special_code }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Department Description -->
    @if($department->description)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">About {{ $department->name }}</h2>
        <div class="prose prose-gray dark:prose-invert max-w-none">
            <p class="text-gray-600 dark:text-gray-300 leading-relaxed whitespace-pre-wrap">{{ $department->description }}</p>
        </div>
    </div>
    @endif

    <!-- Department Functionalities -->
    @if($department->functionalities)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Core Responsibilities</h2>
        <div class="prose prose-gray dark:prose-invert max-w-none">
            <div class="text-gray-600 dark:text-gray-300 leading-relaxed whitespace-pre-wrap">{{ $department->functionalities }}</div>
        </div>
    </div>
    @endif

    <!-- Department Impact Statement -->
    @if($department->impact_statement)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Our Impact</h2>
        <div class="prose prose-gray dark:prose-invert max-w-none">
            <p class="text-gray-600 dark:text-gray-300 leading-relaxed whitespace-pre-wrap">{{ $department->impact_statement }}</p>
        </div>
    </div>
    @endif

    <!-- Department Team -->
    @if($department->users->count() > 0)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Department Team</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($department->users as $user)
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center">
                                <span class="text-sm font-medium text-primary-600 dark:text-primary-400">
                                    {{ substr($user->fullname, 0, 2) }}
                                </span>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                {{ $user->fullname }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ ucfirst($user->role) }}
                            </p>
                            @if($user->position)
                                <p class="text-xs text-gray-400 dark:text-gray-500">
                                    {{ $user->position }}
                                </p>
                            @endif
                        </div>
                    </div>
                    @if($user->courses_count > 0 || $user->enrollments_count > 0)
                        <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400">
                                <span>{{ $user->courses_count }} courses</span>
                                <span>{{ $user->enrollments_count }} enrollments</span>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Recent Courses/Resources -->
    @if($department->courses->count() > 0)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Recent Resources</h2>
            <a href="{{ route('departments.resources.index', $department->id) }}" 
               class="text-primary-600 dark:text-primary-400 hover:text-primary-500 text-sm font-medium">
                View All Resources →
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($department->courses as $course)
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">{{ $course->title }}</h3>
                    @if($course->description)
                        <p class="text-gray-600 dark:text-gray-300 text-sm mb-3">
                            {{ Str::limit($course->description, 100) }}
                        </p>
                    @endif
                    <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                        <span>{{ $course->enrollments_count }} enrolled</span>
                        <span>{{ $course->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
