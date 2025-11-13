@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Assessment Header -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $assessment->title }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">
                    Course: {{ $assessment->course->title }}
                </p>
                @if($assessment->instructions)
                    <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <h3 class="text-sm font-medium text-blue-900 dark:text-blue-100 mb-2">Instructions</h3>
                        <p class="text-sm text-blue-800 dark:text-blue-200">{{ $assessment->instructions }}</p>
                    </div>
                @endif
            </div>
            <div class="text-right">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    {{ $assessment->type === 'quiz' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' }}">
                    {{ ucfirst($assessment->type) }}
                </span>
                @if($assessment->due_date)
                    <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        Due: {{ $assessment->due_date->format('M j, Y g:i A') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Assessment Form -->
    <form action="{{ route('personnel.assessments.store', $assessment) }}" 
          method="POST" enctype="multipart/form-data" 
          class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        @csrf
        
        @php
            $questions = json_decode($assessment->questions, true);
        @endphp

        @forelse($questions as $index => $question)
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-6 mb-6">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Question {{ $index + 1 }}
                    </h3>
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $question['points'] ?? 10 }} points
                    </span>
                </div>

                <div class="mb-4">
                    <p class="text-gray-700 dark:text-gray-300">{{ $question['text'] }}</p>
                </div>

                <!-- Answer Input Based on Question Type -->
                <div class="space-y-4">
                    @switch($question['type'])
                        @case('text')
                            <textarea name="answers[{{ $index }}]" 
                                    rows="4" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                                    placeholder="Enter your answer here..."></textarea>
                            @break

                        @case('multiple_choice')
                            <div class="space-y-2">
                                @foreach($question['options'] as $optionIndex => $option)
                                    <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                                        <input type="radio" name="answers[{{ $index }}]" value="{{ $optionIndex }}" 
                                               class="mr-3 text-primary-600 focus:ring-primary-500">
                                        <span class="text-gray-700 dark:text-gray-300">{{ $option }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @break

                        @case('video')
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                                <div class="mb-4">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <input type="file" name="files[{{ $index }}]" 
                                       accept="video/mp4,video/avi,video/mov" 
                                       class="hidden" id="video_{{ $index }}">
                                <label for="video_{{ $index }}" 
                                       class="cursor-pointer inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                                    Choose Video File
                                </label>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                    MP4, AVI, MOV (Max: 100MB)
                                </p>
                                <div id="video_preview_{{ $index }}" class="mt-4 hidden">
                                    <p class="text-sm text-green-600 dark:text-green-400">File selected: <span class="filename"></span></p>
                                </div>
                            </div>
                            @break

                        @case('image')
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                                <div class="mb-4">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <input type="file" name="files[{{ $index }}]" 
                                       accept="image/jpeg,image/jpg,image/png,image/gif" 
                                       class="hidden" id="image_{{ $index }}">
                                <label for="image_{{ $index }}" 
                                       class="cursor-pointer inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                                    Choose Image File
                                </label>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                    JPG, PNG, GIF (Max: 10MB)
                                </p>
                                <div id="image_preview_{{ $index }}" class="mt-4 hidden">
                                    <img class="mx-auto max-h-48 rounded-lg" alt="Preview">
                                    <p class="text-sm text-green-600 dark:text-green-400 mt-2">File selected: <span class="filename"></span></p>
                                </div>
                            </div>
                            @break

                        @case('audio')
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                                <div class="mb-4">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                                    </svg>
                                </div>
                                <input type="file" name="files[{{ $index }}]" 
                                       accept="audio/mp3,audio/wav,audio/m4a" 
                                       class="hidden" id="audio_{{ $index }}">
                                <label for="audio_{{ $index }}" 
                                       class="cursor-pointer inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                                    Choose Audio File
                                </label>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                    MP3, WAV, M4A (Max: 50MB)
                                </p>
                                <div id="audio_preview_{{ $index }}" class="mt-4 hidden">
                                    <p class="text-sm text-green-600 dark:text-green-400">File selected: <span class="filename"></span></p>
                                </div>
                            </div>
                            @break

                        @case('file')
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                                <div class="mb-4">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <input type="file" name="files[{{ $index }}]" 
                                       accept=".pdf,.doc,.docx,.ppt,.pptx" 
                                       class="hidden" id="file_{{ $index }}">
                                <label for="file_{{ $index }}" 
                                       class="cursor-pointer inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                                    Choose Document File
                                </label>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                    PDF, DOC, DOCX, PPT, PPTX (Max: 25MB)
                                </p>
                                <div id="file_preview_{{ $index }}" class="mt-4 hidden">
                                    <p class="text-sm text-green-600 dark:text-green-400">File selected: <span class="filename"></span></p>
                                </div>
                            </div>
                            @break
                    @endswitch
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <p class="text-gray-500 dark:text-gray-400">No questions available for this assessment.</p>
            </div>
        @endforelse

        <!-- Submit Button -->
        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
            <div class="flex justify-end">
                <button type="submit" 
                        class="px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors font-medium">
                    Submit Assessment
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
// File preview handlers
document.addEventListener('DOMContentLoaded', function() {
    // Image preview
    document.querySelectorAll('input[type="file"][accept*="image"]').forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('image_preview_' + input.id.replace('image_', ''));
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.querySelector('img').src = e.target.result;
                    preview.querySelector('.filename').textContent = file.name;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
    });

    // File name display for other types
    document.querySelectorAll('input[type="file"]:not([accept*="image"])').forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const type = input.id.includes('video') ? 'video' : 
                         input.id.includes('audio') ? 'audio' : 'file';
            const preview = document.getElementById(type + '_preview_' + input.id.replace(type + '_', ''));
            
            if (file) {
                preview.querySelector('.filename').textContent = file.name;
                preview.classList.remove('hidden');
            }
        });
    });
});
</script>
@endpush
@endsection
