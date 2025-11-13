@extends('layouts.app')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">My Ratings</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-semibold mb-4">Ratings Received</h2>
            <div class="text-center">
                <div class="text-5xl font-bold text-primary-600 mb-2">{{ number_format($stats['average_overall'], 1) }}</div>
                <div class="text-gray-500">Average Rating</div>
                <div class="text-sm text-gray-400 mt-2">Based on {{ $stats['total_ratings'] }} ratings</div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-semibold mb-4">Rating Breakdown</h2>
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <span>Communication</span>
                    <span class="font-semibold">{{ number_format($stats['average_communication'], 1) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span>Knowledge</span>
                    <span class="font-semibold">{{ number_format($stats['average_knowledge'], 1) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span>Professionalism</span>
                    <span class="font-semibold">{{ number_format($stats['average_professionalism'], 1) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span>Responsiveness</span>
                    <span class="font-semibold">{{ number_format($stats['average_responsiveness'], 1) }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Ratings Given</h2>
        <div class="space-y-4">
            @forelse($ratingsGiven as $rating)
            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h3 class="font-semibold">{{ $rating->rated->fullname }}</h3>
                        <p class="text-sm text-gray-500">{{ $rating->created_at->format('M d, Y') }}</p>
                    </div>
                    <span class="text-2xl font-bold text-primary-600">{{ $rating->overall_rating }}/5</span>
                </div>
                @if($rating->comment)
                <p class="text-gray-600 dark:text-gray-400">{{ $rating->comment }}</p>
                @endif
            </div>
            @empty
            <p class="text-gray-500">You haven't given any ratings yet.</p>
            @endforelse
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <h2 class="text-xl font-semibold mb-4">Ratings Received</h2>
        <div class="space-y-4">
            @forelse($ratingsReceived as $rating)
            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h3 class="font-semibold">{{ $rating->is_anonymous ? 'Anonymous' : $rating->rater->fullname }}</h3>
                        <p class="text-sm text-gray-500">{{ $rating->created_at->format('M d, Y') }}</p>
                    </div>
                    <span class="text-2xl font-bold text-primary-600">{{ $rating->overall_rating }}/5</span>
                </div>
                @if($rating->comment)
                <p class="text-gray-600 dark:text-gray-400">{{ $rating->comment }}</p>
                @endif
            </div>
            @empty
            <p class="text-gray-500">No ratings received yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
