@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 p-6 rounded">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">Assessments</h1>
        <div class="flex items-center space-x-4">
            <a href="{{ route('personnel.assessments.results') }}" 
               class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 text-sm font-medium">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                View Results
            </a>
            <a href="{{ route('personnel.dashboard') }}" class="text-sm text-primary-600 hover:underline">Back to Dashboard</a>
        </div>
    </div>

    @if($assessments->count() > 0)
        <div class="space-y-4">
            @foreach($assessments as $assessment)
                <div class="border rounded-lg border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-2">
                                <h3 class="font-semibold text-gray-800 dark:text-gray-100">{{ $assessment->title }}</h3>
                                @if($assessment->due_date && $assessment->due_date->isPast())
                                    <span class="text-xs px-2 py-1 rounded bg-red-100 text-red-700">Overdue</span>
                                @elseif($assessment->due_date && $assessment->due_date->diffInDays(now()) <= 3)
                                    <span class="text-xs px-2 py-1 rounded bg-yellow-100 text-yellow-700">Due Soon</span>
                                @endif
                            </div>
                            
                            <div class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                Course: {{ $assessment->course->title }} • 
                                Facilitator: {{ $assessment->course->facilitator->fullname }}
                            </div>
                            
                            <div class="text-sm text-gray-700 dark:text-gray-300 mb-3">
                                {{ $assessment->description ?? 'Complete this assessment to test your knowledge.' }}
                            </div>

                            @if($assessment->due_date)
                                <div class="text-sm text-gray-500 mb-3">
                                    <strong>Due Date:</strong> {{ $assessment->due_date->format('M d, Y \a\t g:i A') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Submission Status -->
                    <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-600">
                        @php
                            $submission = auth()->user()->submissions()->where('assessment_id', $assessment->id)->first();
                        @endphp
                        
                        @if($submission)
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-sm text-gray-600">Status: </span>
                                    @if($submission->score)
                                        <span class="text-sm font-medium text-green-600">Graded - Score: {{ $submission->score }}%</span>
                                    @else
                                        <span class="text-sm font-medium text-yellow-600">Submitted - Pending Review</span>
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500">
                                    Submitted {{ $submission->created_at->diffForHumans() }}
                                </div>
                            </div>
                        @else
                            <form action="{{ route('personnel.assessments.store') }}" method="POST" class="flex items-center justify-between">
                                @csrf
                                <input type="hidden" name="assessment_id" value="{{ $assessment->id }}">
                                <div class="flex-1 mr-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Your Answer/Response
                                    </label>
                                    <textarea name="answer" rows="3" required
                                              placeholder="Enter your answer or response here..."
                                              class="w-full rounded border-gray-300 px-3 py-2 dark:bg-gray-700 dark:border-gray-600"></textarea>
                                </div>
                                <div class="flex flex-col space-y-2">
                                    <button type="submit" 
                                            class="px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700 text-sm whitespace-nowrap">
                                        Submit Assessment
                                    </button>
                                    @if($assessment->due_date)
                                        <span class="text-xs text-gray-500 whitespace-nowrap">
                                            {{ $assessment->due_date->diffForHumans() }}
                                        </span>
                                    @endif
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-8">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No assessments available</h3>
            <p class="mt-1 text-sm text-gray-500">No assessments have been assigned for your enrolled courses yet.</p>
            <div class="mt-6">
                <a href="{{ route('personnel.resources.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700">
                    Browse Resources
                </a>
            </div>
        </div>
    @endif
</div>
@endsection