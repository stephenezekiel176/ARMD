@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Training Seminars</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Training videos, presentations, and workshop materials</p>
            </div>
            <a href="{{ route('personnel.trainings.index') }}" 
               class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">
                ← Back to Trainings
            </a>
        </div>
    </div>

    <!-- Filter by Content Type -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4">
        <div class="flex flex-wrap gap-2">
            <button onclick="filterByType('all')" 
                    class="filter-btn px-4 py-2 rounded-lg text-sm font-medium bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-300">
                All Content
            </button>
            <button onclick="filterByType('video')" 
                    class="filter-btn px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700">
                🎥 Training Videos
            </button>
            <button onclick="filterByType('audio')" 
                    class="filter-btn px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700">
                🎙️ Podcasts
            </button>
            <button onclick="filterByType('ebook')" 
                    class="filter-btn px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700">
                📚 E-books
            </button>
            <button onclick="filterByType('presentation')" 
                    class="filter-btn px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700">
                📊 Presentations
            </button>
        </div>
    </div>

    <!-- Seminars Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($seminars as $seminar)
            <div class="seminar-card bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden hover:shadow-lg transition-shadow" 
                 data-file-type="{{ $seminar->file_type }}">
                @if($seminar->file_type === 'video')
                    <div class="aspect-video bg-gray-200 dark:bg-gray-700 relative">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center">
                                <svg class="w-16 h-16 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-gray-500 dark:text-gray-400 mt-2">Training Video</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                <div class="p-6">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 text-3xl">
                            {{ $seminar->getFileTypeIcon() }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ $seminar->title }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                {{ Str::limit($seminar->description, 100) }}
                            </p>
                            
                            <div class="mt-4 flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $seminar->getFileTypeColor() }}">
                                        {{ ucfirst($seminar->file_type) }}
                                    </span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $seminar->getFormattedFileSize() }}
                                    </span>
                                </div>
                            </div>

                            <div class="mt-4 flex space-x-2">
                                @if($seminar->file_type === 'video')
                                    <a href="{{ route('personnel.trainings.show', $seminar->id) }}" 
                                       class="flex-1 text-center px-3 py-2 bg-primary-600 text-white text-sm rounded-lg hover:bg-primary-700 transition-colors">
                                        Watch Seminar
                                    </a>
                                @elseif($seminar->file_type === 'audio')
                                    <a href="{{ route('personnel.trainings.show', $seminar->id) }}" 
                                       class="flex-1 text-center px-3 py-2 bg-primary-600 text-white text-sm rounded-lg hover:bg-primary-700 transition-colors">
                                        Listen Now
                                    </a>
                                @else
                                    <a href="{{ route('personnel.trainings.getFile', $seminar->id) }}" 
                                       class="flex-1 text-center px-3 py-2 bg-primary-600 text-white text-sm rounded-lg hover:bg-primary-700 transition-colors">
                                        Download
                                    </a>
                                @endif
                                <a href="{{ route('personnel.trainings.show', $seminar->id) }}" 
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <p class="mt-2 text-lg font-medium">No seminars available</p>
                    <p class="mt-1">Training seminars will appear here when published by admin</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($seminars->hasPages())
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg px-6 py-4">
            {{ $seminars->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
function filterByType(type) {
    const cards = document.querySelectorAll('.seminar-card');
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
