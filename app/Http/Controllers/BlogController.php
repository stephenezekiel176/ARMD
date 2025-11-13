<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogPost::with('author')->published();

        // Featured post (latest published)
        $featuredPost = BlogPost::published()
            ->latest('published_at')
            ->first();

        // Regular posts (excluding featured)
        if ($featuredPost) {
            $query->where('id', '!=', $featuredPost->id);
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        $posts = $query->latest('published_at')->paginate(9);

        return view('pages.blog.index', compact('posts', 'featuredPost'));
    }

    public function show(BlogPost $blog)
    {
        // Only show published posts to public
        if (!$blog->isPublished()) {
            abort(404);
        }

        $blog->incrementViews();

        // Related posts
        $relatedPosts = BlogPost::published()
            ->where('id', '!=', $blog->id)
            ->when($blog->category, function($q) use ($blog) {
                $q->where('category', $blog->category);
            })
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('pages.blog.show', compact('blog', 'relatedPosts'));
    }

    public function latestPosts()
    {
        $posts = BlogPost::published()
            ->latest('published_at')
            ->paginate(12);

        return view('pages.blog.latest-posts', compact('posts'));
    }

    public function categories()
    {
        $categories = BlogPost::published()
            ->select('category')
            ->distinct()
            ->whereNotNull('category')
            ->get()
            ->pluck('category')
            ->map(function($category) {
                return [
                    'name' => $category,
                    'count' => BlogPost::published()->where('category', $category)->count(),
                ];
            });

        return view('pages.blog.categories', compact('categories'));
    }

    public function archive(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $month = $request->input('month');

        $query = BlogPost::published()
            ->whereYear('published_at', $year);

        if ($month) {
            $query->whereMonth('published_at', $month);
        }

        $posts = $query->latest('published_at')->paginate(12);

        // Get available years
        $years = BlogPost::published()
            ->selectRaw('YEAR(published_at) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        return view('pages.blog.archive', compact('posts', 'years', 'year', 'month'));
    }
}
