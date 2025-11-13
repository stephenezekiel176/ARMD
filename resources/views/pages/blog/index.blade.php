@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="mb-12 text-center">
        <h1 class="text-5xl font-bold text-gray-900 dark:text-white mb-4">
            Atommart Blog
        </h1>
        <p class="text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
            Insights, updates, and stories from the Atommart team
        </p>
    </div>

    <!-- Featured Post -->
    @if(isset($featuredPost))
    <div class="mb-16">
        <article class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden group hover:shadow-2xl transition-shadow">
            @if($featuredPost->featured_image)
            <div class="relative h-96 overflow-hidden">
                <img src="{{ Storage::url($featuredPost->featured_image) }}" 
                     alt="{{ $featuredPost->title }}" 
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
            </div>
            @endif
            
            <div class="absolute bottom-0 left-0 right-0 p-8 text-white">
                <span class="inline-block px-3 py-1 text-xs font-semibold bg-primary-500 rounded-full mb-3">
                    Featured
                </span>
                <h2 class="text-3xl font-bold mb-3">
                    <a href="{{ route('blog.show', $featuredPost) }}" class="hover:text-primary-300 transition">
                        {{ $featuredPost->title }}
                    </a>
                </h2>
                <p class="text-gray-200 text-lg mb-4">
                    {{ Str::limit($featuredPost->excerpt ?? strip_tags($featuredPost->content), 200) }}
                </p>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <span class="text-sm">{{ $featuredPost->author->fullname }}</span>
                        <span class="text-sm">{{ $featuredPost->published_at->format('M d, Y') }}</span>
                    </div>
                    <a href="{{ route('blog.show', $featuredPost) }}" 
                       class="inline-flex items-center text-sm font-medium hover:text-primary-300 transition">
                        Read More
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
            </div>
        </article>
    </div>
    @endif

    <!-- Blog Posts Grid -->
    @if(isset($posts) && $posts->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
        @foreach($posts as $post)
        <article class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden group hover:shadow-lg transition-shadow">
            @if($post->featured_image)
            <div class="relative h-48 overflow-hidden">
                <img src="{{ Storage::url($post->featured_image) }}" 
                     alt="{{ $post->title }}" 
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            </div>
            @endif
            
            <div class="p-6">
                @if($post->category)
                <span class="inline-block px-3 py-1 text-xs font-semibold bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-200 rounded-full mb-3">
                    {{ $post->category }}
                </span>
                @endif
                
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-3 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition">
                    <a href="{{ route('blog.show', $post) }}">
                        {{ $post->title }}
                    </a>
                </h2>
                
                <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-3">
                    {{ Str::limit($post->excerpt ?? strip_tags($post->content), 150) }}
                </p>
                
                <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>{{ $post->author->fullname }}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>{{ $post->published_at->format('M d, Y') }}</span>
                    </div>
                </div>

                @if($post->tags && count($post->tags) > 0)
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach(array_slice($post->tags, 0, 3) as $tag)
                    <span class="inline-block px-2 py-1 text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded">
                        #{{ $tag }}
                    </span>
                    @endforeach
                </div>
                @endif
            </div>
        </article>
        @endforeach
    </div>

    <!-- Pagination -->
    @if(isset($posts))
    <div class="mt-12">
        {{ $posts->links() }}
    </div>
    @endif
    @else
    <!-- Empty State -->
    <div class="text-center py-20">
        <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
        </svg>
        <h3 class="mt-4 text-xl font-medium text-gray-900 dark:text-white">No blog posts yet</h3>
        <p class="mt-2 text-gray-500 dark:text-gray-400">Check back soon for the latest articles and updates.</p>
    </div>
    @endif
</div>
@endsection
