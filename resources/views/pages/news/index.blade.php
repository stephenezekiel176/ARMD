@extends('layouts.app')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Breaking News -->
    @if($breakingNews->count() > 0)
    <div class="mb-12">
        <div class="bg-red-600 text-white px-4 py-2 rounded-t-lg">
            <h2 class="text-lg font-bold">🔴 BREAKING NEWS</h2>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-b-lg shadow-lg p-6">
            @foreach($breakingNews as $news)
            <div class="mb-4 last:mb-0 pb-4 last:pb-0 border-b last:border-0 border-gray-200 dark:border-gray-700">
                <a href="{{ route('news.show', $news) }}" class="hover:text-primary-600">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $news->title }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">{{ Str::limit($news->summary, 150) }}</p>
                    <span class="text-sm text-gray-500">{{ $news->published_at->diffForHumans() }}</span>
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-8">Latest News</h1>
    
    <!-- Filters -->
    <div class="mb-8 flex gap-4">
        @foreach($categories as $cat)
        <a href="?category={{ $cat }}" 
           class="px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-primary-100 dark:hover:bg-primary-900 {{ request('category') == $cat ? 'bg-primary-500 text-white' : '' }}">
            {{ ucfirst($cat) }}
        </a>
        @endforeach
    </div>

    <!-- News Grid -->
    @if($articles->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
        @foreach($articles as $article)
        <article class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition">
            @if($article->featured_image)
            <img src="{{ Storage::url($article->featured_image) }}" alt="{{ $article->title }}" class="w-full h-48 object-cover">
            @endif
            <div class="p-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="px-3 py-1 text-xs font-semibold bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-200 rounded-full">
                        {{ ucfirst($article->category) }}
                    </span>
                    <span class="text-xs text-gray-500">{{ $article->type }}</span>
                </div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-3">
                    <a href="{{ route('news.show', $article) }}" class="hover:text-primary-600">{{ $article->title }}</a>
                </h2>
                <p class="text-gray-600 dark:text-gray-400 mb-4">{{ Str::limit($article->summary, 100) }}</p>
                <div class="flex items-center justify-between text-sm text-gray-500">
                    <span>{{ $article->author->fullname ?? 'Admin' }}</span>
                    <span>{{ $article->published_at->format('M d, Y') }}</span>
                </div>
            </div>
        </article>
        @endforeach
    </div>
    {{ $articles->links() }}
    @else
    <div class="text-center py-20">
        <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
        </svg>
        <h3 class="mt-4 text-xl font-medium text-gray-900 dark:text-white">No news articles yet</h3>
        <p class="mt-2 text-gray-500">Check back soon for the latest news and updates.</p>
    </div>
    @endif
</div>
@endsection
