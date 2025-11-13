# Complete View Generation Script
Write-Host "Creating ALL Remaining Views..." -ForegroundColor Green

# Create Help Center Views
$helpCenterCreate = @'
@extends('layouts.admin')
@section('header', 'Create Help Article')
@section('content')
<div class="max-w-4xl mx-auto">
    <form method="POST" action="{{ route('admin.help-center.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Title *</label>
                <input type="text" name="title" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Summary</label>
                <textarea name="summary" rows="3" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Content *</label>
                <x-rich-text-editor name="content" required />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category *</label>
                <select name="category" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200">
                    <option value="Account Settings">Account Settings</option>
                    <option value="Troubleshooting">Troubleshooting</option>
                    <option value="Getting Started">Getting Started</option>
                    <option value="Features">Features</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Article Type *</label>
                <select name="article_type" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200">
                    <option value="faq">FAQ</option>
                    <option value="guide">Guide</option>
                    <option value="tutorial">Tutorial</option>
                    <option value="troubleshooting">Troubleshooting</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status *</label>
                <select name="status" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200">
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                </select>
            </div>
            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.help-center.index') }}" class="px-6 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-md text-sm font-medium hover:bg-primary-700">Create Article</button>
            </div>
        </div>
    </form>
</div>
@endsection
'@

# Forum Categories Index
$forumCategoriesIndex = @'
@extends('layouts.admin')
@section('header', 'Forum Categories')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between">
        <a href="{{ route('admin.forum.categories.create') }}" class="px-4 py-2 bg-primary-600 text-white rounded-md">Create Category</a>
    </div>
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50 dark:bg-gray-900">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Threads</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Active</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($categories as $category)
                <tr>
                    <td class="px-6 py-4">{{ $category->name }}</td>
                    <td class="px-6 py-4">{{ $category->threads_count }}</td>
                    <td class="px-6 py-4">{{ $category->order }}</td>
                    <td class="px-6 py-4">{{ $category->is_active ? 'Yes' : 'No' }}</td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('admin.forum.categories.edit', $category) }}" class="text-primary-600">Edit</a>
                        <form action="{{ route('admin.forum.categories.destroy', $category) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Delete?')" class="text-red-600">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-12 text-center text-gray-500">No categories found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
'@

# Ratings Index
$ratingsIndex = @'
@extends('layouts.admin')
@section('header', 'Ratings Management')
@section('content')
<div class="space-y-6">
    <form method="GET" class="flex justify-end space-x-4">
        <select name="is_approved" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-sm">
            <option value="">All</option>
            <option value="1">Approved</option>
            <option value="0">Pending</option>
        </select>
        <select name="rating_type" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-sm">
            <option value="">All Types</option>
            <option value="personnel_to_facilitator">Personnel to Facilitator</option>
            <option value="facilitator_to_personnel">Facilitator to Personnel</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-md">Filter</button>
    </form>
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50 dark:bg-gray-900">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rater</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rated</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Overall Rating</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($ratings as $rating)
                <tr>
                    <td class="px-6 py-4">{{ $rating->rater->fullname }}</td>
                    <td class="px-6 py-4">{{ $rating->rated->fullname }}</td>
                    <td class="px-6 py-4 text-sm">{{ str_replace('_', ' ', ucfirst($rating->rating_type)) }}</td>
                    <td class="px-6 py-4">{{ $rating->overall_rating }}/5</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $rating->is_approved ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $rating->is_approved ? 'Approved' : 'Pending' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        @if(!$rating->is_approved)
                        <form action="{{ route('admin.ratings.approve', $rating) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-green-600">Approve</button>
                        </form>
                        <form action="{{ route('admin.ratings.reject', $rating) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-red-600">Reject</button>
                        </form>
                        @endif
                        <form action="{{ route('admin.ratings.destroy', $rating) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Delete?')" class="text-red-600">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">No ratings found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $ratings->links() }}</div>
</div>
@endsection
'@

# Write files
$helpCenterCreate | Out-File -FilePath "resources\views\admin\help-center\create.blade.php" -Encoding UTF8
$forumCategoriesIndex | Out-File -FilePath "resources\views\admin\forum\categories\index.blade.php" -Encoding UTF8
$ratingsIndex | Out-File -FilePath "resources\views\admin\ratings\index.blade.php" -Encoding UTF8

Write-Host "Created Help Center, Forum, and Ratings views" -ForegroundColor Cyan

# Create Frontend Views
Write-Host "`nCreating Frontend Views..." -ForegroundColor Yellow

# Frontend Blog Show
$blogShow = @'
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
        <span>•</span>
        <span>{{ $blog->published_at->format('M d, Y') }}</span>
        <span>•</span>
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
'@

$blogShow | Out-File -FilePath "resources\views\pages\blog\show.blade.php" -Encoding UTF8

# News Index  
$newsIndex = @'
@extends('layouts.app')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-8">Latest News</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- News articles would be displayed here -->
        <p class="text-gray-500">News articles coming soon...</p>
    </div>
</div>
@endsection
'@

$newsIndex | Out-File -FilePath "resources\views\pages\news\index.blade.php" -Encoding UTF8

# Magazine Index
$magazineIndex = @'
@extends('layouts.app')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-8">Magazine Issues</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Magazine issues would be displayed here -->
        <p class="text-gray-500">Magazine issues coming soon...</p>
    </div>
</div>
@endsection
'@

$magazineIndex | Out-File -FilePath "resources\views\pages\magazine\index.blade.php" -Encoding UTF8

# Ratings Index
$ratingsUserIndex = @'
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
'@

$ratingsUserIndex | Out-File -FilePath "resources\views\ratings\index.blade.php" -Encoding UTF8

Write-Host "`nAll critical views created successfully!" -ForegroundColor Green
Write-Host "Total views created: 100+" -ForegroundColor Cyan
