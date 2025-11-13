@extends('layouts.facilitator')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section with Facilitator Info -->
    <div class="bg-gradient-to-r from-primary-500 to-primary-600 p-6 rounded-lg text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Welcome, {{ auth()->user()->fullname }}!</h1>
                <p class="mt-1 text-primary-100">
                    {{ auth()->user()->department->name }} Department • 
                    {{ auth()->user()->position }}
                </p>
            </div>
            <div class="text-right">
                <div class="text-3xl font-bold">{{ auth()->user()->points }}</div>
                <div class="text-sm text-primary-100">Points Earned</div>
            </div>
        </div>
    </div>

    <!-- Department Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow-sm">
            <div class="flex items-center">
                <div class="flex-1">
                    <div class="text-2xl font-bold text-primary-600">{{ $statistics['total_personnel'] }}</div>
                    <div class="text-sm text-gray-500">Total Personnel</div>
                </div>
                <div class="ml-4">
                    <svg class="h-8 w-8 text-primary-200" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow-sm">
            <div class="flex items-center">
                <div class="flex-1">
                    <div class="text-2xl font-bold text-green-600">{{ $statistics['active_enrollments'] }}</div>
                    <div class="text-sm text-gray-500">Active Enrollments</div>
                </div>
                <div class="ml-4">
                    <svg class="h-8 w-8 text-green-200" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow-sm">
            <div class="flex items-center">
                <div class="flex-1">
                    <div class="text-2xl font-bold text-blue-600">{{ $statistics['completion_rate'] }}%</div>
                    <div class="text-sm text-gray-500">Completion Rate</div>
                </div>
                <div class="ml-4">
                    <svg class="h-8 w-8 text-blue-200" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow-sm">
            <div class="flex items-center">
                <div class="flex-1">
                    <div class="text-2xl font-bold text-yellow-600">{{ $statistics['pending_grading'] }}</div>
                    <div class="text-sm text-gray-500">Pending Grading</div>
                </div>
                <div class="ml-4">
                    <svg class="h-8 w-8 text-yellow-200" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded shadow-sm">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('facilitator.messages.create') }}" class="flex items-center p-3 border rounded border-gray-200 dark:border-gray-700 hover:shadow-md transition">
                <div class="mr-3">
                    <svg class="h-6 w-6 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <div class="font-medium text-gray-800 dark:text-gray-100">Send Message</div>
                    <div class="text-sm text-gray-500">Communicate with personnel</div>
                </div>
            </a>

            <a href="{{ route('facilitator.assessments.create') }}" class="flex items-center p-3 border rounded border-gray-200 dark:border-gray-700 hover:shadow-md transition">
                <div class="mr-3">
                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <div class="font-medium text-gray-800 dark:text-gray-100">Create Assessment</div>
                    <div class="text-sm text-gray-500">Evaluate personnel progress</div>
                </div>
            </a>

            <a href="{{ route('facilitator.courses.index') }}" class="flex items-center p-3 border rounded border-gray-200 dark:border-gray-700 hover:shadow-md transition">
                <div class="mr-3">
                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div>
                    <div class="font-medium text-gray-800 dark:text-gray-100">Manage Courses</div>
                    <div class="text-sm text-gray-500">Upload videos, ebooks, audio, images, podcasts</div>
                </div>
            </a>
        </div>
    </div>

	<div class="bg-white dark:bg-gray-800 p-6 rounded shadow-sm">
		<div class="flex items-center justify-between">
			<h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Your Courses</h2>
			<div class="space-x-2">
				<a href="{{ route('facilitator.assessments.create') }}" class="inline-flex items-center px-3 py-2 bg-primary-600 text-white rounded hover:bg-primary-700 hover:scale-105 transition">Create Assessment</a>
				<a href="{{ route('facilitator.courses.index') }}" class="inline-flex items-center px-3 py-2 border rounded text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 transition">Manage Courses</a>
			</div>
		</div>

		<div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
			@forelse($courses as $c)
				<a href="{{ route('facilitator.courses.show', $c->id) }}" class="block p-3 border rounded border-gray-200 dark:border-gray-700 hover:shadow-md transition hover:scale-101">
					<div class="flex items-center justify-between">
						<h3 class="font-semibold text-gray-800 dark:text-gray-100">{{ $c->title }}</h3>
						<span class="text-sm text-gray-500">{{ ucfirst($c->type) }}</span>
					</div>
					@if($c->description)
						<p class="mt-2 text-sm text-gray-500">{{ Str::limit($c->description, 140) }}</p>
					@endif
				</a>
			@empty
				<div class="col-span-full text-sm text-gray-500">No courses yet. <a href="{{ route('facilitator.courses.create') }}" class="text-primary-600 hover:underline">Create your first course</a></div>
			@endforelse
		</div>
	</div>
</div>
@endsection
