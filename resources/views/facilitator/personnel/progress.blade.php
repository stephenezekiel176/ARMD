@extends('layouts.facilitator')

@section('content')
<div class="bg-white dark:bg-gray-800 p-6 rounded">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">{{ $personnel->fullname }}</h1>
        <span class="text-sm text-gray-500">{{ $personnel->position }}</span>
    </div>

    <!-- Individual Messaging Section -->
    <div class="mt-6 p-4 border rounded border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-medium mb-3">Send Personal Message</h3>
        <form action="{{ route('facilitator.messages.store') }}" method="POST" class="space-y-3">
            @csrf
            <input type="hidden" name="recipient_id" value="{{ $personnel->id }}">
            <div>
                <input name="subject" placeholder="Subject (optional)" class="w-full rounded border-gray-300 px-3 py-2 dark:bg-gray-700 dark:border-gray-600" />
            </div>
            <div>
                <textarea name="body" rows="4" class="w-full rounded border-gray-300 px-3 py-2 dark:bg-gray-700 dark:border-gray-600" placeholder="Write a personal message to {{ $personnel->fullname }}..." required></textarea>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="this.closest('form').querySelector('textarea').value = ''" class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50">Clear</button>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700">Send Message</button>
            </div>
        </form>
    </div>

    <!-- Progress Overview -->
    <div class="mt-6 p-4 border rounded border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-medium mb-3">Progress Overview</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="text-center">
                <div class="text-2xl font-bold text-primary-600">{{ $personnel->enrollments->count() }}</div>
                <div class="text-sm text-gray-500">Enrolled Courses</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600">{{ $personnel->enrollments->where('status', 'completed')->count() }}</div>
                <div class="text-sm text-gray-500">Completed</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $personnel->points }}</div>
                <div class="text-sm text-gray-500">Points Earned</div>
            </div>
        </div>
        
        @if($personnel->enrollments->count() > 0)
            <div class="mt-4">
                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Course Progress</h4>
                <div class="space-y-2">
                    @foreach($personnel->enrollments as $enrollment)
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $enrollment->course->title }}</span>
                                    <span class="text-sm text-gray-500">{{ $enrollment->progress }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                                    <div class="bg-primary-600 h-2 rounded-full" style="width: {{ $enrollment->progress }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Recent Activity -->
    <div class="mt-6 p-4 border rounded border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-medium mb-3">Recent Activity</h3>
        <div class="space-y-3">
            @forelse($personnel->submissions->take(5) as $submission)
                <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-600 last:border-0">
                    <div>
                        <div class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Submitted: {{ $submission->assessment->title ?? 'Assessment' }}
                        </div>
                        <div class="text-xs text-gray-500">{{ $submission->created_at->diffForHumans() }}</div>
                    </div>
                    <div class="text-sm">
                        @if($submission->score)
                            <span class="px-2 py-1 text-xs rounded {{ $submission->score >= 70 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $submission->score }}%
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-600">Not graded</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-sm text-gray-500">No recent activity.</div>
            @endforelse
        </div>
    </div>

    <div class="mt-6">
        <h2 class="text-lg font-medium">All Submissions</h2>
        <div class="mt-2 divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($personnel->submissions as $sub)
                <div class="py-2 flex items-center justify-between">
                    <div>
                        <div class="font-medium">{{ $sub->assessment->title ?? 'Assessment' }}</div>
                        <div class="text-sm text-gray-500">Score: {{ $sub->score ?? 'Not graded' }}</div>
                    </div>
                    <div class="text-sm text-gray-500">{{ $sub->created_at->diffForHumans() }}</div>
                </div>
            @empty
                <div class="py-4 text-sm text-gray-500">No submissions yet.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
