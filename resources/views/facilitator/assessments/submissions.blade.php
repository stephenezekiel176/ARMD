@extends('layouts.facilitator')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Assessment Submissions</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Review and grade assessment submissions</p>
            </div>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                {{ $submissions->total() }} total submissions
            </div>
        </div>
    </div>

    <!-- Submissions List -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Personnel
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Assessment
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Type
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Submitted
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Score
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($submissions as $submission)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center">
                                            <span class="text-primary-600 dark:text-primary-400 font-medium">
                                                {{ substr($submission->user->fullname, 0, 1) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $submission->user->fullname }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $submission->user->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $submission->assessment->title }}
                                </div>
                                @if($submission->assessment->course)
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $submission->assessment->course->title }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    {{ $submission->assessment->type === 'quiz' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' }}">
                                    {{ ucfirst($submission->assessment->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $submission->created_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($submission->graded_at)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        Graded
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($submission->graded_at)
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $submission->score }}/100
                                    </div>
                                @else
                                    <span class="text-sm text-gray-500 dark:text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <button onclick="showSubmissionDetails({{ $submission->id }})" 
                                            class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300">
                                        View Details
                                    </button>
                                    @if(!$submission->graded_at)
                                        <button onclick="showGradingForm({{ $submission->id }})" 
                                                class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                            Grade
                                        </button>
                                    @else
                                        <span class="text-green-600 dark:text-green-400">
                                            Graded {{ $submission->score }}%
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-500 dark:text-gray-400">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="mt-2 text-lg font-medium">No submissions yet</p>
                                    <p class="mt-1">Submissions will appear here when personnel complete assessments</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($submissions->hasPages())
            <div class="bg-white dark:bg-gray-800 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $submissions->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Grading Modal -->
<div id="gradingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Grade Assessment</h3>
                <button onclick="closeGradingModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="gradingForm" method="POST" action="">
                @csrf
                <input type="hidden" id="gradingSubmissionId" name="submission_id">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Score (0-100)
                    </label>
                    <input type="number" name="score" min="0" max="100" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                           placeholder="Enter score">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">This will be shown as a percentage to the personnel</p>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Feedback (Optional)
                    </label>
                    <textarea name="feedback" rows="4" maxlength="1000"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                              placeholder="Provide feedback on the personnel's performance..."></textarea>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">This feedback will be sent to the personnel and stored in their records</p>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeGradingModal()"
                            class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-700 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Submit Grade
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const submissions = @json($submissions->items());

function showSubmissionDetails(submissionId) {
    const submission = submissions.find(s => s.id === submissionId);
    if (!submission) return;

    const modal = document.getElementById('submissionModal');
    const content = document.getElementById('submissionContent');
    
    let answersHtml = '';
    
    try {
        const answerData = JSON.parse(submission.answer);
        const questions = JSON.parse(submission.assessment.questions);
        
        questions.forEach((question, index) => {
            const answer = answerData.answers[index];
            
            answersHtml += `
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <div class="flex justify-between items-start mb-2">
                        <h4 class="font-medium text-gray-900 dark:text-gray-100">Question ${index + 1}</h4>
                        <span class="text-sm text-gray-500 dark:text-gray-400">${question.points || 10} points</span>
                    </div>
                    <p class="text-gray-700 dark:text-gray-300 mb-3">${question.text}</p>
                    <div class="bg-gray-50 dark:bg-gray-900 rounded p-3">
            `;
            
            if (answer && answer.startsWith('file:')) {
                const filePath = answer.replace('file:', '');
                answersHtml += `
                    <div class="flex items-center space-x-2">
                        <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        <a href="/storage/${filePath}" target="_blank" class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">
                            View Uploaded File
                        </a>
                    </div>
                `;
            } else if (answer) {
                answersHtml += `<p class="text-gray-700 dark:text-gray-300">${answer}</p>`;
            } else {
                answersHtml += `<p class="text-gray-500 dark:text-gray-400 italic">No answer provided</p>`;
            }
            
            answersHtml += `</div></div>`;
        });
        
    } catch (e) {
        answersHtml = `<p class="text-gray-500 dark:text-gray-400">Unable to parse submission data</p>`;
    }
    
    content.innerHTML = `
        <div class="mb-4">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                <strong>Personnel:</strong> ${submission.user.fullname}<br>
                <strong>Email:</strong> ${submission.user.email}<br>
                <strong>Submitted:</strong> ${new Date(submission.created_at).toLocaleString()}
            </p>
        </div>
        <div class="space-y-4">
            ${answersHtml}
        </div>
    `;
    
    modal.classList.remove('hidden');
}

function showGradingForm(submissionId) {
    const submission = submissions.find(s => s.id === submissionId);
    if (!submission) return;

    const modal = document.getElementById('gradingModal');
    const form = document.getElementById('gradingForm');
    
    // Set the form action
    form.action = `/facilitator/assessments/grade/${submissionId}`;
    document.getElementById('gradingSubmissionId').value = submissionId;
    
    modal.classList.remove('hidden');
}

function closeSubmissionModal() {
    document.getElementById('submissionModal').classList.add('hidden');
}

function closeGradingModal() {
    document.getElementById('gradingModal').classList.add('hidden');
}

// Close modals when clicking outside
window.onclick = function(event) {
    const submissionModal = document.getElementById('submissionModal');
    const gradingModal = document.getElementById('gradingModal');
    
    if (event.target == submissionModal) {
        closeSubmissionModal();
    }
    if (event.target == gradingModal) {
        closeGradingModal();
    }
}
</script>
@endsection
