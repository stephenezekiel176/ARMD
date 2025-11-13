@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section with Department and Facilitator Info -->
    <div class="bg-gradient-to-r from-primary-500 to-primary-600 p-6 rounded-lg text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Welcome, {{ auth()->user()->fullname }}!</h1>
                <p class="mt-1 text-primary-100">
                    {{ auth()->user()->department->name }} Department • 
                    Position: {{ auth()->user()->position }} • 
                    Facilitator: {{ auth()->user()->department->users()->where('role', 'facilitator')->first()->fullname ?? 'Not Assigned' }}
                </p>
            </div>
            <div class="text-right">
                <div class="text-3xl font-bold">{{ auth()->user()->points }}</div>
                <div class="text-sm text-primary-100">Points Earned</div>
            </div>
        </div>
    </div>

    <!-- Navigation Buttons -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Facilitator Instructions Button -->
        <a href="{{ route('personnel.instructions') }}" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm hover:shadow-md transition border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="mr-4">
                    <svg class="h-8 w-8 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Facilitator Instructions</h3>
                    <p class="text-sm text-gray-500">View messages and guidance from your facilitator</p>
                </div>
            </div>
            @if($hasNewMessages ?? false)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mt-2">
                    New Messages
                </span>
            @endif
        </a>

        <!-- Resources Button -->
        <a href="{{ route('personnel.resources.index') }}" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm hover:shadow-md transition border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="mr-4">
                    <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Resources</h3>
                    <p class="text-sm text-gray-500">Browse videos and e-books, enroll in courses</p>
                </div>
            </div>
        </a>

        <!-- Trainings Button -->
        <a href="{{ route('personnel.trainings.index') }}" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm hover:shadow-md transition border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="mr-4">
                    <svg class="h-8 w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Trainings</h3>
                    <p class="text-sm text-gray-500">Access company guidelines and training seminars</p>
                </div>
            </div>
            @if($hasNewTrainings ?? false)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 mt-2">
                    New Trainings
                </span>
            @endif
        </a>

        <!-- Assessments Button -->
        <a href="{{ route('personnel.assessments.index') }}" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm hover:shadow-md transition border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="mr-4">
                    <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Assessments</h3>
                    <p class="text-sm text-gray-500">Complete assessments and submit your work</p>
                </div>
            </div>
            @if($hasNewAssessments ?? false)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-2">
                    New Assessments
                </span>
            @endif
        </a>
    </div>

    <!-- My Courses Section -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">My Enrolled Courses</h2>
            <a href="{{ route('personnel.courses') }}" class="text-sm text-primary-600 hover:underline">View All</a>
        </div>

        @if($enrollments->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($enrollments->take(6) as $enrollment)
                    <div class="border rounded-lg border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-800 dark:text-gray-100">{{ $enrollment->course->title }}</h3>
                                <p class="text-sm text-gray-500 mt-1">{{ ucfirst($enrollment->course->type) }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded {{ $enrollment->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($enrollment->status) }}
                            </span>
                        </div>
                        
                        <div class="mt-4">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Progress</span>
                                <span class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $enrollment->progress }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                                <div class="bg-primary-600 h-2 rounded-full transition-all duration-300" style="width: {{ $enrollment->progress }}%"></div>
                            </div>
                        </div>

                        <div class="mt-4 flex items-center justify-between">
                            <span class="text-xs text-gray-500">
                                Enrolled {{ $enrollment->created_at->diffForHumans() }}
                            </span>
                            <a href="{{ route('personnel.resources.show', $enrollment->course->id) }}" class="text-sm text-primary-600 hover:underline">
                                {{ $enrollment->status === 'completed' ? 'Review' : 'Continue' }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No courses enrolled yet</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by browsing available resources.</p>
                <div class="mt-6">
                    <a href="{{ route('personnel.resources.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700">
                        Browse Resources
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Recent Activity -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4">Recent Activity</h2>
        <div class="space-y-3">
            @forelse(auth()->user()->submissions()->latest()->take(5)->get() as $submission)
                <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700 last:border-0">
                    <div>
                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                            Submitted: {{ $submission->assessment->title }}
                        </div>
                        <div class="text-sm text-gray-500">{{ $submission->created_at->diffForHumans() }}</div>
                    </div>
                    <div class="text-sm">
                        @if($submission->score)
                            <span class="px-2 py-1 text-xs rounded {{ $submission->score >= 70 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                Score: {{ $submission->score }}%
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-600">Pending Review</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-4 text-sm text-gray-500">
                    No recent activity. Start by enrolling in a course!
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
