@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header with Project Overview -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 rounded-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">Admin Dashboard</h1>
                <p class="mt-2 text-primary-100">Atommart Learning Management System - Complete Administrative Control</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.departments.create') }}" class="px-4 py-2 bg-white text-primary-600 rounded-lg hover:bg-primary-50 font-medium">
                    + New Department
                </a>
                <a href="{{ route('admin.messages.create') }}" class="px-4 py-2 bg-primary-700 text-white rounded-lg hover:bg-primary-800 font-medium">
                    Send Message
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $totalDepartments }}</h3>
                    <p class="text-sm text-gray-500">Departments</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $totalFacilitators }}</h3>
                    <p class="text-sm text-gray-500">Facilitators</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $totalPersonnel }}</h3>
                    <p class="text-sm text-gray-500">Personnel</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-3 bg-orange-100 dark:bg-orange-900 rounded-lg">
                    <svg class="w-6 h-6 text-orange-600 dark:text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $completionRate }}%</h3>
                    <p class="text-sm text-gray-500">Completion Rate</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Facilitators Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Facilitators</h2>
                    <a href="{{ route('admin.facilitators.create') }}" class="text-sm text-primary-600 hover:text-primary-700">Add Facilitator</a>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($facilitators as $facilitator)
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="h-10 w-10 rounded-full bg-primary-600 flex items-center justify-center">
                                    <span class="text-white font-medium">{{ substr($facilitator->fullname, 0, 1) }}</span>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-gray-100">{{ $facilitator->fullname }}</h4>
                                    <p class="text-sm text-gray-500">{{ $facilitator->department->name ?? 'No Department' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $facilitator->courses_count }} courses</div>
                                <div class="text-sm text-gray-500">{{ $facilitator->department_users_count ?? 0 }} personnel</div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.facilitators.index') }}" class="text-sm text-primary-600 hover:text-primary-700">View All Facilitators →</a>
                </div>
            </div>
        </div>

        <!-- Personnel Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Recent Personnel Activity</h2>
                    <a href="{{ route('admin.personnel.index') }}" class="text-sm text-primary-600 hover:text-primary-700">View All</a>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($personnel as $person)
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="h-10 w-10 rounded-full bg-purple-600 flex items-center justify-center">
                                    <span class="text-white font-medium">{{ substr($person->fullname, 0, 1) }}</span>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-gray-100">{{ $person->fullname }}</h4>
                                    <p class="text-sm text-gray-500">{{ $person->department->name ?? 'No Department' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $person->enrollments_count }} enrolled</div>
                                <div class="text-sm text-gray-500">{{ $person->submissions_count }} submissions</div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.personnel.index') }}" class="text-sm text-primary-600 hover:text-primary-700">View All Personnel →</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Departments and Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Departments -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Departments</h2>
                    <a href="{{ route('admin.departments.create') }}" class="text-sm text-primary-600 hover:text-primary-700">Manage Departments</a>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($departments as $department)
                        <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:shadow-md transition">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900 dark:text-gray-100">{{ $department->name }}</h4>
                                    <p class="text-sm text-gray-500 mt-1">{{ $department->code ?? 'No Code' }}</p>
                                    @if($department->slogan)
                                        <p class="text-sm text-gray-600 mt-2 italic">"{{ $department->slogan }}"</p>
                                    @endif
                                </div>
                                @if($department->head_image)
                                    <img src="{{ asset('storage/' . $department->head_image) }}" 
                                         alt="Department Head" 
                                         class="h-12 w-12 rounded-full object-cover">
                                @endif
                            </div>
                            <div class="mt-3 flex items-center justify-between text-sm">
                                <span class="text-gray-500">{{ $department->users_count }} users • {{ $department->courses_count }} courses</span>
                                <a href="{{ route('admin.departments.edit', $department->id) }}" class="text-primary-600 hover:text-primary-700">Edit</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Recent Activity</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <!-- Recent Enrollments -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">Recent Enrollments</h4>
                        <div class="space-y-2">
                            @foreach($recentEnrollments as $enrollment)
                                <div class="text-sm">
                                    <span class="text-gray-700 dark:text-gray-300">{{ $enrollment->user->fullname }}</span>
                                    <span class="text-gray-500"> enrolled in </span>
                                    <span class="text-gray-700 dark:text-gray-300">{{ $enrollment->course->title }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Recent Submissions -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">Recent Submissions</h4>
                        <div class="space-y-2">
                            @foreach($recentSubmissions as $submission)
                                <div class="text-sm">
                                    <span class="text-gray-700 dark:text-gray-300">{{ $submission->user->fullname }}</span>
                                    <span class="text-gray-500"> submitted </span>
                                    <span class="text-gray-700 dark:text-gray-300">{{ $submission->assessment->title }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
