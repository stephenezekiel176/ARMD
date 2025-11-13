@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Company Guidelines</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Official company policies, procedures, and best practices</p>
            </div>
            <a href="{{ route('personnel.trainings.index') }}" 
               class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">
                ← Back to Trainings
            </a>
        </div>
    </div>

    <!-- Filter by File Type -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4">
        <div class="flex flex-wrap gap-2">
            <button onclick="filterByType('all')" 
                    class="filter-btn px-4 py-2 rounded-lg text-sm font-medium bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-300">
                All Files
            </button>
            <button onclick="filterByType('pdf')" 
                    class="filter-btn px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700">
                📄 PDF Documents
            </button>
            <button onclick="filterByType('video')" 
                    class="filter-btn px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700">
                🎥 Videos
            </button>
            <button onclick="filterByType('audio')" 
                    class="filter-btn px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700">
                🎵 Audio Files
            </button>
            <button onclick="filterByType('image')" 
                    class="filter-btn px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700">
                🖼️ Images
            </button>
        </div>
    </div>

    <!-- Guidelines Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($guidelines as $guideline)
            <div class="guideline-card bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden hover:shadow-lg transition-shadow" 
                 data-file-type="{{ $guideline->file_type }}">
                <div class="p-6">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 text-3xl">
                            {{ $guideline->getFileTypeIcon() }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ $guideline->title }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                {{ Str::limit($guideline->description, 100) }}
                            </p>
                            
                            <div class="mt-4 flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $guideline->getFileTypeColor() }}">
                                        {{ ucfirst($guideline->file_type) }}
                                    </span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $guideline->getFormattedFileSize() }}
                                    </span>
                                </div>
                            </div>

                            <div class="mt-4 flex space-x-2">
                                @if($guideline->file_type === 'video')
                                    <a href="{{ route('personnel.trainings.show', $guideline->id) }}" 
                                       class="flex-1 text-center px-3 py-2 bg-primary-600 text-white text-sm rounded-lg hover:bg-primary-700 transition-colors">
                                        Watch Video
                                    </a>
                                @else
                                    <a href="{{ route('personnel.trainings.getFile', $guideline->id) }}" 
                                       class="flex-1 text-center px-3 py-2 bg-primary-600 text-white text-sm rounded-lg hover:bg-primary-700 transition-colors">
                                        Download
                                    </a>
                                @endif
                                <a href="{{ route('personnel.trainings.show', $guideline->id) }}" 
                                   class="px-3 py-2 border border-gray-300 text-gray-700 text-sm rounded-lg hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                                    Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <div class="text-gray-500 dark:text-gray-400">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="mt-2 text-lg font-medium">No guidelines available</p>
                    <p class="mt-1">Company guidelines will appear here when published by admin</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($guidelines->hasPages())
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg px-6 py-4">
            {{ $guidelines->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
function filterByType(type) {
    const cards = document.querySelectorAll('.guideline-card');
    const buttons = document.querySelectorAll('.filter-btn');
    
    // Update button styles
    buttons.forEach(btn => {
        btn.classList.remove('bg-primary-100', 'text-primary-700', 'dark:bg-primary-900', 'dark:text-primary-300');
        btn.classList.add('text-gray-600', 'hover:bg-gray-100', 'dark:text-gray-400', 'dark:hover:bg-gray-700');
    });
    
    event.target.classList.remove('text-gray-600', 'hover:bg-gray-100', 'dark:text-gray-400', 'dark:hover:bg-gray-700');
    event.target.classList.add('bg-primary-100', 'text-primary-700', 'dark:bg-primary-900', 'dark:text-primary-300');
    
    // Filter cards
    cards.forEach(card => {
        if (type === 'all' || card.dataset.fileType === type) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>
@endpush
@endsection
