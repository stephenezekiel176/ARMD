@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Hero Section -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">Our Departments</h1>
        <p class="text-lg text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
            Atommart's success is built on the synergy between our specialized departments.
            Each department plays a crucial role in driving innovation, growth, and excellence in everything we do.
        </p>
    </div>

    <!-- Departments List -->
    <div class="space-y-12">
        @foreach($departments as $department)
            <section class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-3">
                    <!-- Department Info -->
                    <div class="p-8 lg:p-12 lg:col-span-2">
                        <div class="flex items-center gap-x-3 mb-6">
                            <div class="flex-none rounded-lg bg-primary-50 p-3 dark:bg-primary-900/50">
                                @if($department->head_image)
                                    <img src="{{ asset('storage/' . $department->head_image) }}" 
                                         alt="Department Head" 
                                         class="h-8 w-8 rounded-full object-cover">
                                @elseif($department->icon && str_starts_with($department->icon, '/storage'))
                                    <img src="{{ $department->icon }}" 
                                         alt="{{ $department->name }} icon" 
                                         class="h-8 w-8">
                                @elseif($department->icon)
                                    <div class="h-8 w-8">{!! $department->icon !!}</div>
                                @else
                                    <div class="h-8 w-8 text-primary-600 dark:text-primary-400">
                                        <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $department->name }}</h2>
                                <p class="mt-1 text-sm font-medium text-primary-600 dark:text-primary-400">{{ $department->code }}</p>
                            </div>
                        </div>

                        @php
                            // Tools mapping per department (friendly defaults)
                            $toolsMap = [
                                'Engineering' => ['Git', 'Docker', 'AWS', 'PHP / Laravel'],
                                'Marketing' => ['Google Analytics', 'HubSpot', 'Figma', 'Social Media Platforms'],
                                'Research & Development' => ['Jupyter', 'Python', 'Prototyping Tools', 'Data Analysis'],
                                'Human Resources' => ['BambooHR', 'Workday', 'Slack', 'L&D Platforms'],
                            ];
                            $deptTools = $toolsMap[$department->name] ?? ['Internal tools & systems'];
                        @endphp

                        <!-- Department Information -->
                        <div class="space-y-6">
                            @if($department->overview)
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Overview</h3>
                                    <p class="text-gray-600 dark:text-gray-300 mt-2">{{ $department->overview }}</p>
                                </div>
                            @endif

                            @if($department->slogan)
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white">Slogan</h4>
                                    <p class="text-gray-600 dark:text-gray-300 italic mt-2">"{{ $department->slogan }}"</p>
                                </div>
                            @endif

                            @if($department->core_responsibilities)
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white">Core Responsibilities</h4>
                                    <div class="mt-2 text-gray-600 dark:text-gray-300">
                                        {{ nl2br(e($department->core_responsibilities)) }}
                                    </div>
                                </div>
                            @endif

                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-white">Tools & Technologies</h4>
                                <ul class="list-disc list-inside mt-2 text-gray-600 dark:text-gray-300">
                                    @foreach($deptTools as $tool)
                                        <li>{{ $tool }}</li>
                                    @endforeach
                                </ul>
                            </div>

                            @if($department->impact)
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white">Impact</h4>
                                    <p class="text-gray-600 dark:text-gray-300 mt-2">{{ $department->impact }}</p>
                                </div>
                            @endif

                            <!-- Department Head -->
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-white">Department Head</h4>
                                <div class="mt-3 flex items-center space-x-4">
                                    @if($department->head_image)
                                        <img src="{{ asset('storage/' . $department->head_image) }}" 
                                             alt="Department Head - {{ $department->name }}" 
                                             class="h-16 w-16 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600">
                                    @else
                                        <div class="h-16 w-16 rounded-full bg-gray-200 dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 flex items-center justify-center">
                                            <svg class="h-8 w-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Department Leadership</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $department->users_count }} team members</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Department Stats -->
                    <div class="bg-gradient-to-br from-primary-500 to-primary-700 p-8 lg:p-12 flex flex-col justify-between">
                        <div class="text-center text-white mb-8">
                            <div class="mb-4">
                                <span class="text-4xl font-bold">{{ $department->users_count }}</span>
                                <span class="text-lg block text-primary-100">Team Members</span>
                            </div>
                            @if($department->courses_count > 0)
                                <div class="mb-4">
                                    <span class="text-2xl font-bold">{{ $department->courses_count }}</span>
                                    <span class="text-sm block text-primary-100">Available Courses</span>
                                </div>
                            @endif
                            <p class="text-primary-100">Join our dynamic team and be part of something extraordinary.</p>
                        </div>

                        <div class="text-center">
                            <p class="mt-4 text-sm text-primary-100">Department Code: {{ $department->code }}</p>
                        </div>
                    </div>
                </div>
                <!-- Access button below the department topics -->
                <div class="p-6 bg-white dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700 rounded-b-2xl text-center">
                    <a href="{{ route('login', ['dept' => $department->id]) }}"
                       class="inline-block bg-primary-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-500 transition-colors">
                        Access Department
                    </a>
                </div>
            </section>
        @endforeach
    </div>

    @if($departments->count() === 0)
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No departments available</h3>
            <p class="mt-1 text-sm text-gray-500">Please check back later for available departments.</p>
        </div>
    @endif
</div>
@endsection
