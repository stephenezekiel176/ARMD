@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-12 text-center">
        <h1 class="text-5xl font-bold text-gray-900 dark:text-white mb-4">Training Workshops</h1>
        <p class="text-xl text-gray-600 dark:text-gray-400">Professional development and skill enhancement programs</p>
    </div>

    <!-- Upcoming Workshops -->
    @if($upcomingWorkshops->count() > 0)
    <div class="mb-12">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Upcoming Workshops</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($upcomingWorkshops as $workshop)
            <article class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 hover:shadow-lg transition">
                <div class="flex justify-between items-start mb-4">
                    <span class="px-3 py-1 text-xs font-semibold bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200 rounded-full">
                        Upcoming
                    </span>
                    <span class="text-sm font-medium text-gray-500">{{ $workshop->workshop_date->format('M d, Y') }}</span>
                </div>
                
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">{{ $workshop->title }}</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-4">{{ Str::limit($workshop->description, 150) }}</p>
                
                <div class="space-y-2 mb-4">
                    @if($workshop->location)
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ $workshop->location }}
                    </div>
                    @endif
                    
                    @if($workshop->speakers && count($workshop->speakers) > 0)
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        {{ count($workshop->speakers) }} Speaker(s)
                    </div>
                    @endif
                </div>
                
                <a href="{{ route('training.workshops.show', $workshop) }}" 
                   class="inline-flex items-center text-primary-600 hover:text-primary-700 font-medium">
                    Learn More
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </article>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Completed Workshops -->
    <div>
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Past Workshops</h2>
        @if($completedWorkshops->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            @foreach($completedWorkshops as $workshop)
            <article class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition">
                @if($workshop->images && count($workshop->images) > 0)
                <img src="{{ Storage::url($workshop->images[0]) }}" alt="{{ $workshop->title }}" class="w-full h-40 object-cover">
                @endif
                
                <div class="p-6">
                    <span class="px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 rounded-full">
                        {{ $workshop->workshop_date->format('M Y') }}
                    </span>
                    
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mt-3 mb-2">{{ $workshop->title }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">{{ Str::limit($workshop->overview, 100) }}</p>
                    
                    @if($workshop->attendee_count > 0)
                    <div class="text-sm text-gray-500 mb-3">
                        {{ $workshop->attendee_count }} attendees
                    </div>
                    @endif
                    
                    <a href="{{ route('training.workshops.show', $workshop) }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                        View Details →
                    </a>
                </div>
            </article>
            @endforeach
        </div>
        {{ $completedWorkshops->links() }}
        @else
        <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No completed workshops yet</h3>
            <p class="mt-2 text-gray-500">Check back soon for workshop recordings and materials.</p>
        </div>
        @endif
    </div>
</div>
@endsection
