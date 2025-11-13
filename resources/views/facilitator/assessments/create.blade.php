@extends('layouts.facilitator')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Create Assessment</h2>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Create comprehensive assessments with multiple question types</p>
    </div>

    <form action="{{ route('facilitator.assessments.store') }}" method="POST" 
          enctype="multipart/form-data" class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        @csrf
        
        <!-- Basic Information -->
        <div class="space-y-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        Assessment Title *
                    </label>
                    <input type="text" name="title" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                           placeholder="Enter assessment title">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        Assessment Type *
                    </label>
                    <select name="type" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                        <option value="">Select type</option>
                        <option value="quiz">Quiz</option>
                        <option value="assignment">Assignment</option>
                        <option value="practical">Practical Assessment</option>
                        <option value="mixed">Mixed Assessment</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        Course *
                    </label>
                    <select name="course_id" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                        <option value="">Select course</option>
                        @foreach(App\Models\Course::where('facilitator_id', auth()->id())->get() as $course)
                            <option value="{{ $course->id }}">{{ $course->title }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        Due Date
                    </label>
                    <input type="datetime-local" name="due_date" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                    Instructions
                </label>
                <textarea name="instructions" rows="3" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                          placeholder="Provide instructions for this assessment"></textarea>
            </div>
        </div>

        <!-- Questions Section -->
        <div class="border-t border-gray-200 dark:border-gray-700 pt-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Assessment Questions</h3>
                <button type="button" onclick="addQuestion()" 
                        class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Question
                </button>
            </div>

            <div id="questions-container" class="space-y-6">
                <!-- Questions will be added here dynamically -->
            </div>
        </div>

        <!-- Submit Button -->
        <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-8">
            <div class="flex justify-end space-x-4">
                <a href="{{ route('facilitator.assessments.index') }}" 
                   class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    Create Assessment
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
let questionCount = 0;

function addQuestion() {
    questionCount++;
    const questionHtml = `
        <div class="question-card border border-gray-200 dark:border-gray-700 rounded-lg p-6" data-question-id="${questionCount}">
            <div class="flex justify-between items-start mb-4">
                <h4 class="text-md font-medium text-gray-900 dark:text-gray-100">Question ${questionCount}</h4>
                <button type="button" onclick="removeQuestion(${questionCount})" 
                        class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        Question Text *
                    </label>
                    <textarea name="questions[${questionCount}][text]" required rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                              placeholder="Enter your question"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                            Question Type *
                        </label>
                        <select name="questions[${questionCount}][type]" required onchange="updateQuestionType(${questionCount})"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                            <option value="">Select type</option>
                            <option value="text">Text Answer</option>
                            <option value="multiple_choice">Multiple Choice</option>
                            <option value="video">Video Upload</option>
                            <option value="image">Image Upload</option>
                            <option value="audio">Audio Upload</option>
                            <option value="file">File Upload</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                            Points
                        </label>
                        <input type="number" name="questions[${questionCount}][points]" min="1" value="10"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                    </div>
                </div>

                <div id="question-options-${questionCount}" class="hidden">
                    <!-- Dynamic options based on question type -->
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        Correct Answer / Rubric
                    </label>
                    <textarea name="questions[${questionCount}][answer]" rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                              placeholder="Enter correct answer or grading rubric"></textarea>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('questions-container').insertAdjacentHTML('beforeend', questionHtml);
}

function removeQuestion(questionId) {
    const questionCard = document.querySelector(`[data-question-id="${questionId}"]`);
    if (questionCard) {
        questionCard.remove();
    }
}

function updateQuestionType(questionId) {
    const type = document.querySelector(`select[name="questions[${questionId}][type]"]`).value;
    const optionsContainer = document.getElementById(`question-options-${questionId}`);
    
    let optionsHtml = '';
    
    switch(type) {
        case 'multiple_choice':
            optionsHtml = `
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Options</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        <input type="text" name="questions[${questionId}][options][]" placeholder="Option A" class="px-3 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                        <input type="text" name="questions[${questionId}][options][]" placeholder="Option B" class="px-3 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                        <input type="text" name="questions[${questionId}][options][]" placeholder="Option C" class="px-3 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                        <input type="text" name="questions[${questionId}][options][]" placeholder="Option D" class="px-3 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                    </div>
                </div>
            `;
            break;
        case 'video':
            optionsHtml = `
                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                    <p class="text-sm text-blue-800 dark:text-blue-200">
                        <strong>Video Requirements:</strong> Students will upload a video file. 
                        Supported formats: MP4, AVI, MOV (Max: 100MB)
                    </p>
                </div>
            `;
            break;
        case 'image':
            optionsHtml = `
                <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                    <p class="text-sm text-green-800 dark:text-green-200">
                        <strong>Image Requirements:</strong> Students will upload an image file. 
                        Supported formats: JPG, PNG, GIF (Max: 10MB)
                    </p>
                </div>
            `;
            break;
        case 'audio':
            optionsHtml = `
                <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg">
                    <p class="text-sm text-purple-800 dark:text-purple-200">
                        <strong>Audio Requirements:</strong> Students will upload an audio file. 
                        Supported formats: MP3, WAV, M4A (Max: 50MB)
                    </p>
                </div>
            `;
            break;
        case 'file':
            optionsHtml = `
                <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg">
                    <p class="text-sm text-yellow-800 dark:text-yellow-200">
                        <strong>File Requirements:</strong> Students will upload any file. 
                        Supported formats: PDF, DOC, DOCX, PPT, PPTX (Max: 25MB)
                    </p>
                </div>
            `;
            break;
    }
    
    optionsContainer.innerHTML = optionsHtml;
    optionsContainer.classList.remove('hidden');
}

// Add first question on page load
document.addEventListener('DOMContentLoaded', function() {
    addQuestion();
});
</script>
@endpush
@endsection
