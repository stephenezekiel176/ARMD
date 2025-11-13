<?php

namespace App\Http\Controllers;

use App\Models\NewsArticle;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = NewsArticle::with('author')->published();

        // Breaking news
        $breakingNews = NewsArticle::published()
            ->where('is_breaking', true)
            ->latest('published_at')
            ->limit(3)
            ->get();

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $articles = $query->latest('published_at')->paginate(12);
        $categories = ['world', 'sports', 'business', 'entertainment', 'technology', 'health', 'science', 'politics'];

        return view('pages.news.index', compact('articles', 'breakingNews', 'categories'));
    }

    public function show(NewsArticle $news)
    {
        if (!$news->status === 'published') {
            abort(404);
        }

        $news->increment('views');

        $relatedNews = NewsArticle::published()
            ->where('id', '!=', $news->id)
            ->where('category', $news->category)
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('pages.news.show', compact('news', 'relatedNews'));
    }

    public function latest()
    {
        $articles = NewsArticle::published()
            ->latest('published_at')
            ->paginate(15);

        return view('pages.news.latest', compact('articles'));
    }

    public function categories()
    {
        $categories = ['world', 'sports', 'business', 'entertainment', 'technology', 'health', 'science', 'politics'];
        
        $categoryData = collect($categories)->map(function($category) {
            return [
                'name' => $category,
                'count' => NewsArticle::published()->where('category', $category)->count(),
                'latest' => NewsArticle::published()->where('category', $category)->latest()->first()
            ];
        })->filter(function($item) {
            return $item['count'] > 0;
        });

        return view('pages.news.categories', compact('categoryData'));
    }

    public function multimedia()
    {
        $articles = NewsArticle::published()
            ->where(function($q) {
                $q->whereNotNull('images')
                  ->orWhereNotNull('videos')
                  ->orWhereNotNull('hyperlinks');
            })
            ->latest('published_at')
            ->paginate(12);

        return view('pages.news.multimedia', compact('articles'));
    }
}
