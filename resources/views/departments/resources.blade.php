@extends('layouts.app')

@section('title', $department->name . ' Resources - ' . config('app.name'))

@section('content')
<div class="relative isolate">
    <!-- Header -->
    <div class="bg-gradient-to-r from-primary-500 to-primary-600 text-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex items-center space-x-4">
                @if($department->icon)
                    <div class="text-4xl">{{ $department->icon }}</div>
                @endif
                <div>
                    <h1 class="text-3xl font-bold">{{ $department->name }} Resources</h1>
                    @if($department->slogan)
                        <p class="mt-2 text-primary-100">{{ $department->slogan }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Resources Grid -->
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
        @if($resources->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($resources as $resource)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                        @if($resource->file_path && str_ends_with($resource->file_path, '.jpg') || str_ends_with($resource->file_path, '.png') || str_ends_with($resource->file_path, '.jpeg'))
                            <div class="aspect-video bg-gray-200 dark:bg-gray-700">
                                <img src="{{ asset('storage/' . $resource->file_path) }}" 
                                     alt="{{ $resource->title }}" 
                                     class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="aspect-video bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center">
                                <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        @endif
                        
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                {{ $resource->title }}
                            </h3>
                            
                            @if($resource->description)
                                <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 line-clamp-2">
                                    {{ Str::limit($resource->description, 100) }}
                                </p>
                            @endif
                            
                            <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 mb-4">
                                <span>{{ $resource->enrollments_count }} enrolled</span>
                                <span>{{ $resource->created_at->format('M d, Y') }}</span>
                            </div>
                            
                            <div class="flex space-x-2">
                                <a href="{{ route('resources.show', $resource->id) }}" 
                                   class="flex-1 bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700 transition-colors text-center text-sm">
                                    View Resource
                                </a>
                                @auth
                                    @if(!auth()->user()->enrollments()->where('course_id', $resource->id)->exists())
                                        <form method="POST" action="{{ route('resources.enroll', $resource->id) }}" class="flex-1">
                                            @csrf
                                            <button type="submit" 
                                                    class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors text-sm">
                                                Enroll
                                            </button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-8">
                {{ $resources->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No resources available</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    No resources have been added to the {{ $department->name }} department yet.
                </p>
                <div class="mt-6">
                    <a href="{{ route('resources.index') }}" class="text-primary-600 dark:text-primary-400 hover:text-primary-500">
                        Browse all resources →
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
