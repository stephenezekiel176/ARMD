@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Assessment Results</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">View your graded assessments and performance statistics</p>
            </div>
            <div class="text-right">
                <div class="text-3xl font-bold text-primary-600 dark:text-primary-400">{{ $averageScore }}%</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Overall Average</div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 dark:bg-green-900 rounded-lg p-3">
                    <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $totalAssessments }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Total Assessments</div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 dark:bg-blue-900 rounded-lg p-3">
                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        {{ !empty($weeklyAverages) ? end($weeklyAverages)['average'] . '%' : 'N/A' }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">This Week's Average</div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-100 dark:bg-purple-900 rounded-lg p-3">
                    <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        {{ !empty($monthlyAverages) ? end($monthlyAverages)['average'] . '%' : 'N/A' }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">This Month's Average</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Weekly & Monthly Trends -->
    @if(!empty($weeklyAverages) || !empty($monthlyAverages))
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Weekly Trends -->
        @if(!empty($weeklyAverages))
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Weekly Performance</h3>
            <div class="space-y-3">
                @foreach(array_slice(array_reverse($weeklyAverages), 0, 4) as $week)
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $week['period'] }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $week['total_assessments'] }} assessments</div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-24 bg-gray-200 dark:bg-gray-700 rounded-full h-2 mr-3">
                            <div class="bg-primary-600 h-2 rounded-full" style="width: {{ $week['average'] }}%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $week['average'] }}%</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Monthly Trends -->
        @if(!empty($monthlyAverages))
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Monthly Performance</h3>
            <div class="space-y-3">
                @foreach(array_slice(array_reverse($monthlyAverages), 0, 4) as $month)
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $month['period'] }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $month['total_assessments'] }} assessments</div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-24 bg-gray-200 dark:bg-gray-700 rounded-full h-2 mr-3">
                            <div class="bg-primary-600 h-2 rounded-full" style="width: {{ $month['average'] }}%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $month['average'] }}%</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    @endif

    <!-- Assessment Results -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Assessment History</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Assessment
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Course
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Facilitator
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Score
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Graded
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Feedback
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($gradedSubmissions as $submission)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $submission->assessment->title }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ ucfirst($submission->assessment->type) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $submission->assessment->course->title }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $submission->assessment->course->facilitator->fullname }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $submission->score }}%
                                    </div>
                                    <div class="ml-2 w-16 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                        <div class="bg-{{ $submission->score >= 80 ? 'green' : ($submission->score >= 60 ? 'yellow' : 'red') }}-500 h-2 rounded-full" 
                                             style="width: {{ $submission->score }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $submission->graded_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4">
                                @if($submission->feedback)
                                    <button onclick="showFeedback({{ $submission->id }})" 
                                            class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300 text-sm">
                                        View Feedback
                                    </button>
                                @else
                                    <span class="text-sm text-gray-400 italic">No feedback</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-gray-500 dark:text-gray-400">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="mt-2 text-lg font-medium">No graded assessments yet</p>
                                    <p class="mt-1">Your graded assessments will appear here once your facilitator reviews them</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Feedback Modal -->
<div id="feedbackModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Facilitator Feedback</h3>
                <button onclick="closeFeedbackModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div id="feedbackContent" class="text-gray-700 dark:text-gray-300">
                <!-- Feedback content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
const submissions = @json($gradedSubmissions->toArray());

function showFeedback(submissionId) {
    const submission = submissions.find(s => s.id === submissionId);
    if (!submission || !submission.feedback) return;

    const modal = document.getElementById('feedbackModal');
    const content = document.getElementById('feedbackContent');
    
    content.innerHTML = `
        <div class="space-y-4">
            <div>
                <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-2">Assessment: ${submission.assessment.title}</h4>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Score: ${submission.score}% | Graded: ${new Date(submission.graded_at).toLocaleDateString()}
                </p>
            </div>
            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                <p class="whitespace-pre-wrap">${submission.feedback}</p>
            </div>
        </div>
    `;
    
    modal.classList.remove('hidden');
}

function closeFeedbackModal() {
    document.getElementById('feedbackModal').classList.add('hidden');
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('feedbackModal');
    if (event.target == modal) {
        closeFeedbackModal();
    }
}
</script>
@endsection
