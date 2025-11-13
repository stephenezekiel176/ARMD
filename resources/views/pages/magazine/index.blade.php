@extends('layouts.app')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-12 text-center">
        <h1 class="text-5xl font-bold text-gray-900 dark:text-white mb-4">Atommart Magazine</h1>
        <p class="text-xl text-gray-600 dark:text-gray-400">Company stories, updates, and insights</p>
    </div>

    @if($issues->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
        @foreach($issues as $issue)
        <article class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition group">
            @if($issue->cover_image)
            <div class="relative h-80 overflow-hidden">
                <img src="{{ Storage::url($issue->cover_image) }}" 
                     alt="{{ $issue->title }}" 
                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                <div class="absolute top-4 right-4 bg-primary-600 text-white px-3 py-1 rounded-lg text-sm font-semibold">
                    {{ $issue->issue_number }}
                </div>
            </div>
            @endif
            
            <div class="p-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="px-3 py-1 text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200 rounded-full">
                        {{ ucfirst($issue->type) }}
                    </span>
                    <span class="text-sm text-gray-500">{{ $issue->issue_date->format('M Y') }}</span>
                </div>
                
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-3 group-hover:text-primary-600 transition">
                    <a href="{{ route('magazine.show', $issue) }}">{{ $issue->title }}</a>
                </h2>
                
                <p class="text-gray-600 dark:text-gray-400 mb-4">{{ Str::limit($issue->description, 120) }}</p>
                
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">{{ number_format($issue->downloads) }} downloads</span>
                    @if($issue->pdf_file)
                    <a href="{{ Storage::url($issue->pdf_file) }}" target="_blank" 
                       class="inline-flex items-center text-sm text-primary-600 hover:text-primary-700">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Download PDF
                    </a>
                    @endif
                </div>
            </div>
        </article>
        @endforeach
    </div>
    
    {{ $issues->links() }}
    @else
    <div class="text-center py-20">
        <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
        </svg>
        <h3 class="mt-4 text-xl font-medium text-gray-900 dark:text-white">No magazine issues yet</h3>
        <p class="mt-2 text-gray-500">Check back soon for the latest magazine issues.</p>
    </div>
    @endif
</div>
@endsection
