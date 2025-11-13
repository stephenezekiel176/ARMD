@extends('layouts.admin')

@section('content')
<div class="bg-white dark:bg-gray-800 p-6 rounded">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Departments</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage all departments and their information</p>
        </div>
        <a href="{{ route('admin.departments.create') }}" class="inline-flex items-center rounded-md bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Create Department
        </a>
    </div>

    @if($departments->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($departments as $department)
                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg transition">
                    <!-- Department Header -->
                    <div class="p-4 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    @if($department->head_image)
                                        <img src="{{ asset('storage/' . $department->head_image) }}" 
                                             alt="Department Head" 
                                             class="h-12 w-12 rounded-full object-cover border-2 border-primary-200">
                                    @elseif($department->icon && str_starts_with($department->icon, '/storage'))
                                        <img src="{{ $department->icon }}" 
                                             alt="{{ $department->name }} icon" 
                                             class="h-12 w-12 rounded-full" />
                                    @elseif($department->icon)
                                        <div class="h-12 w-12">{!! $department->icon !!}</div>
                                    @else
                                        <div class="h-12 w-12 flex items-center justify-center rounded-full bg-primary-600 text-white font-semibold">
                                            {{ strtoupper(mb_substr($department->name ?? 'D', 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 dark:text-gray-100">{{ $department->name }}</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $department->code }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-1">
                                <a href="{{ route('admin.departments.edit', $department->id) }}" 
                                   class="p-1 text-gray-400 hover:text-primary-600 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.departments.destroy', $department->id) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete {{ $department->name }} department? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 text-gray-400 hover:text-red-600 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        @if($department->slogan)
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-300 italic">"{{ $department->slogan }}"</p>
                        @endif
                    </div>

                    <!-- Department Content -->
                    <div class="p-4">
                        @if($department->overview)
                            <div class="mb-3">
                                <h4 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Overview</h4>
                                <p class="text-sm text-gray-700 dark:text-gray-300 line-clamp-2">{{ Str::limit($department->overview, 100) }}</p>
                            </div>
                        @endif

                        @if($department->core_responsibilities)
                            <div class="mb-3">
                                <h4 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Core Responsibilities</h4>
                                <p class="text-sm text-gray-700 dark:text-gray-300 line-clamp-2">{{ Str::limit($department->core_responsibilities, 80) }}</p>
                            </div>
                        @endif

                        @if($department->impact)
                            <div class="mb-4">
                                <h4 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Impact</h4>
                                <p class="text-sm text-gray-700 dark:text-gray-300 line-clamp-2">{{ Str::limit($department->impact, 80) }}</p>
                            </div>
                        @endif

                        <!-- Statistics -->
                        <div class="pt-3 border-t border-gray-200 dark:border-gray-600">
                            <div class="grid grid-cols-2 gap-4 text-center">
                                <div>
                                    <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $department->users_count }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Users</div>
                                </div>
                                <div>
                                    <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $department->courses_count ?? 0 }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Courses</div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-4 flex space-x-2">
                            <a href="{{ route('admin.departments.edit', $department->id) }}" 
                               class="flex-1 text-center px-3 py-2 bg-primary-600 text-white text-sm rounded hover:bg-primary-700 transition">
                                Edit Department
                            </a>
                            <a href="{{ route('admin.facilitators.index') }}?department={{ $department->id }}" 
                               class="px-3 py-2 bg-gray-100 text-gray-700 text-sm rounded hover:bg-gray-200 transition">
                                View Users
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No departments</h3>
            <p class="mt-1 text-sm text-gray-500">Get started by creating your first department.</p>
            <div class="mt-6">
                <a href="{{ route('admin.departments.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700">
                    Create Department
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

