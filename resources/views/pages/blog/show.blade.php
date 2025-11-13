@extends('layouts.app')
@section('content')
<article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    @if($blog->featured_image)
    <img src="{{ Storage::url($blog->featured_image) }}" alt="{{ $blog->title }}" class="w-full h-96 object-cover rounded-2xl mb-8">
    @endif
    
    <div class="mb-8">
        @if($blog->category)
        <span class="inline-block px-3 py-1 text-sm font-semibold bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-200 rounded-full">
            {{ $blog->category }}
        </span>
        @endif
    </div>
    
    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">{{ $blog->title }}</h1>
    
    <div class="flex items-center space-x-4 text-gray-600 dark:text-gray-400 mb-8">
        <span>By {{ $blog->author->fullname }}</span>
        <span>â€¢</span>
        <span>{{ $blog->published_at->format('M d, Y') }}</span>
        <span>â€¢</span>
        <span>{{ number_format($blog->views) }} views</span>
    </div>
    
    <div class="prose dark:prose-invert max-w-none mb-12">
        {!! $blog->content !!}
    </div>
    
    @if($blog->tags && count($blog->tags) > 0)
    <div class="flex flex-wrap gap-2 mb-8">
        @foreach($blog->tags as $tag)
        <span class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-full text-sm">#{{ $tag }}</span>
        @endforeach
    </div>
    @endif
    
    @if($relatedPosts->count() > 0)
    <div class="border-t border-gray-200 dark:border-gray-700 pt-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Related Posts</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($relatedPosts as $related)
            <a href="{{ route('blog.show', $related) }}" class="group">
                @if($related->featured_image)
                <img src="{{ Storage::url($related->featured_image) }}" class="w-full h-40 object-cover rounded-lg mb-3 group-hover:opacity-80 transition">
                @endif
                <h3 class="font-semibold text-gray-900 dark:text-white group-hover:text-primary-600">{{ $related->title }}</h3>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</article>
@endsection
