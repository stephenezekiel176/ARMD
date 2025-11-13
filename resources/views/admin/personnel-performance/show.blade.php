@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $user->fullname }} - Performance Details</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">
                    {{ $user->email }} • {{ $user->department->name ?? 'No Department' }}
                </p>
            </div>
            <div class="text-right">
                <div class="text-3xl font-bold text-primary-600 dark:text-primary-400">{{ $averageScore }}%</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Overall Average</div>
            </div>
        </div>
    </div>

    <!-- Performance Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 dark:bg-blue-900 rounded-lg p-3">
                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <div class="flex-shrink-0 bg-green-100 dark:bg-green-900 rounded-lg p-3">
                    <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
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

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-100 dark:bg-yellow-900 rounded-lg p-3">
                    <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        {{ $gradedSubmissions->first() ? $gradedSubmissions->first()->graded_at->diffForHumans() : 'N/A' }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Last Activity</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Trends -->
    @if(!empty($weeklyAverages) || !empty($monthlyAverages))
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Weekly Performance -->
        @if(!empty($weeklyAverages))
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Weekly Performance Trend</h3>
            <div class="space-y-3">
                @foreach(array_slice(array_reverse($weeklyAverages), 0, 6) as $week)
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $week['period'] }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $week['total_assessments'] }} assessments</div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-24 bg-gray-200 dark:bg-gray-700 rounded-full h-2 mr-3">
                            <div class="bg-{{ $week['average'] >= 80 ? 'green' : ($week['average'] >= 60 ? 'yellow' : 'red') }}-500 h-2 rounded-full" 
                                 style="width: {{ $week['average'] }}%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $week['average'] }}%</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Monthly Performance -->
        @if(!empty($monthlyAverages))
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Monthly Performance Trend</h3>
            <div class="space-y-3">
                @foreach(array_slice(array_reverse($monthlyAverages), 0, 6) as $month)
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $month['period'] }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $month['total_assessments'] }} assessments</div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-24 bg-gray-200 dark:bg-gray-700 rounded-full h-2 mr-3">
                            <div class="bg-{{ $month['average'] >= 80 ? 'green' : ($month['average'] >= 60 ? 'yellow' : 'red') }}-500 h-2 rounded-full" 
                                 style="width: {{ $month['average'] }}%"></div>
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

    <!-- Recent Assessment History -->
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
                                {{ $submission->graded_at->format('M j, Y g:i A') }}
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
                                    <p class="mt-1">Assessment results will appear here once graded by facilitators</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Stored Assessment Records -->
    @if(!empty($assessmentHistory))
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Stored Assessment Records</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Official records stored in personnel admin folder</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Assessment
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Score
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Facilitator
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Graded At
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($assessmentHistory as $record)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $record['assessment_title'] }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $record['course_title'] }} • {{ ucfirst($record['assessment_type']) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        {{ $record['score'] >= 80 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                           ($record['score'] >= 60 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 
                                           'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200') }}">
                                        {{ $record['percentage'] }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $record['facilitator_name'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ \Carbon\Carbon::parse($record['graded_at'])->format('M j, Y g:i A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <button onclick="showStoredRecord({{ $record['submission_id'] }})" 
                                        class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300">
                                    View Record
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

<!-- Feedback Modal -->
<div id="feedbackModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Assessment Feedback</h3>
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

<!-- Stored Record Modal -->
<div id="storedRecordModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Stored Assessment Record</h3>
                <button onclick="closeStoredRecordModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div id="storedRecordContent" class="text-gray-700 dark:text-gray-300">
                <!-- Stored record content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
const submissions = @json($gradedSubmissions->toArray());
const assessmentHistory = @json($assessmentHistory);

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

function showStoredRecord(submissionId) {
    const record = assessmentHistory.find(r => r.submission_id === submissionId);
    if (!record) return;

    const modal = document.getElementById('storedRecordModal');
    const content = document.getElementById('storedRecordContent');
    
    content.innerHTML = `
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-2">Assessment Details</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <strong>Title:</strong> ${record.assessment_title}<br>
                        <strong>Course:</strong> ${record.course_title}<br>
                        <strong>Type:</strong> ${record.assessment_type}<br>
                        <strong>Score:</strong> ${record.percentage}
                    </p>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-2">Grading Information</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <strong>Facilitator:</strong> ${record.facilitator_name}<br>
                        <strong>Graded At:</strong> ${new Date(record.graded_at).toLocaleString()}<br>
                        <strong>Submitted At:</strong> ${new Date(record.submitted_at).toLocaleString()}
                    </p>
                </div>
            </div>
            ${record.feedback ? `
                <div>
                    <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-2">Feedback</h4>
                    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                        <p class="whitespace-pre-wrap">${record.feedback}</p>
                    </div>
                </div>
            ` : ''}
            <div>
                <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-2">Personnel Information</h4>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    <strong>Name:</strong> ${record.personnel_name}<br>
                    <strong>Email:</strong> ${record.personnel_email}<br>
                    <strong>ID:</strong> ${record.personnel_id}
                </p>
            </div>
        </div>
    `;
    
    modal.classList.remove('hidden');
}

function closeFeedbackModal() {
    document.getElementById('feedbackModal').classList.add('hidden');
}

function closeStoredRecordModal() {
    document.getElementById('storedRecordModal').classList.add('hidden');
}

// Close modals when clicking outside
window.onclick = function(event) {
    const feedbackModal = document.getElementById('feedbackModal');
    const storedRecordModal = document.getElementById('storedRecordModal');
    
    if (event.target == feedbackModal) {
        closeFeedbackModal();
    }
    if (event.target == storedRecordModal) {
        closeStoredRecordModal();
    }
}
</script>
@endsection
