<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsArticle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminNewsController extends Controller
{
    public function index(Request $request)
    {
        $query = NewsArticle::with('author')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        $articles = $query->paginate(20);
        $categories = ['world', 'sports', 'business', 'entertainment', 'technology', 'health', 'science', 'politics'];

        return view('admin.news.index', compact('articles', 'categories'));
    }

    public function create()
    {
        $categories = ['world', 'sports', 'business', 'entertainment', 'technology', 'health', 'science', 'politics'];
        return view('admin.news.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:5120',
            'images.*' => 'nullable|image|max:5120',
            'videos' => 'nullable|array',
            'videos.*' => 'nullable|url',
            'hyperlinks' => 'nullable|array',
            'hyperlinks.*' => 'nullable|url',
            'type' => 'required|in:news,report,opinion,press_release',
            'category' => 'required|string|max:100',
            'status' => 'required|in:draft,published,breaking,archived',
            'is_breaking' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ]);

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('news/featured', 'public');
        }

        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('news/images', 'public');
            }
            $validated['images'] = $images;
        }

        $validated['author_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_breaking'] = $request->has('is_breaking');

        if (in_array($validated['status'], ['published', 'breaking']) && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        NewsArticle::create($validated);

        return redirect()->route('admin.news.index')->with('success', 'News article created successfully.');
    }

    public function edit(NewsArticle $news)
    {
        $categories = ['world', 'sports', 'business', 'entertainment', 'technology', 'health', 'science', 'politics'];
        return view('admin.news.edit', compact('news', 'categories'));
    }

    public function update(Request $request, NewsArticle $news)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:5120',
            'images.*' => 'nullable|image|max:5120',
            'videos' => 'nullable|array',
            'videos.*' => 'nullable|url',
            'hyperlinks' => 'nullable|array',
            'hyperlinks.*' => 'nullable|url',
            'type' => 'required|in:news,report,opinion,press_release',
            'category' => 'required|string|max:100',
            'status' => 'required|in:draft,published,breaking,archived',
            'is_breaking' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ]);

        if ($request->hasFile('featured_image')) {
            if ($news->featured_image) {
                Storage::disk('public')->delete($news->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('news/featured', 'public');
        }

        if ($request->hasFile('images')) {
            $images = $news->images ?? [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('news/images', 'public');
            }
            $validated['images'] = $images;
        }

        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_breaking'] = $request->has('is_breaking');

        if (in_array($validated['status'], ['published', 'breaking']) && !$news->published_at && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $news->update($validated);

        return redirect()->route('admin.news.index')->with('success', 'News article updated successfully.');
    }

    public function destroy(NewsArticle $news)
    {
        if ($news->featured_image) {
            Storage::disk('public')->delete($news->featured_image);
        }

        if ($news->images) {
            foreach ($news->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'News article deleted successfully.');
    }
}
