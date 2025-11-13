<?php

namespace App\Http\Controllers;

use App\Models\HelpArticle;
use App\Models\HelpFeedback;
use Illuminate\Http\Request;

class HelpCenterController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        $results = HelpArticle::published()
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%")
                  ->orWhere('summary', 'like', "%{$query}%");
            })
            ->get();

        $popularArticles = HelpArticle::published()->popular(5)->get();

        return view('pages.help-center.search', compact('results', 'query', 'popularArticles'));
    }

    public function guides(Request $request)
    {
        $query = HelpArticle::published();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $articles = $query->orderBy('helpful_count', 'desc')->paginate(12);
        $categories = ['Account Settings', 'Troubleshooting', 'Getting Started', 'Features', 'Billing', 'Security'];

        return view('pages.help-center.guides', compact('articles', 'categories'));
    }

    public function show(HelpArticle $article)
    {
        if (!$article->isPublished()) {
            abort(404);
        }

        $article->incrementViews();

        $relatedArticles = HelpArticle::published()
            ->where('id', '!=', $article->id)
            ->where('category', $article->category)
            ->limit(4)
            ->get();

        return view('pages.help-center.article', compact('article', 'relatedArticles'));
    }

    public function contact()
    {
        return view('pages.help-center.contact');
    }

    public function feedback(Request $request)
    {
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'help_article_id' => 'required|exists:help_articles,id',
                'is_helpful' => 'required|boolean',
                'comment' => 'nullable|string',
                'suggestion' => 'nullable|string',
            ]);

            $validated['user_id'] = auth()->id();

            HelpFeedback::create($validated);

            $article = HelpArticle::find($validated['help_article_id']);
            if ($validated['is_helpful']) {
                $article->markAsHelpful();
            } else {
                $article->markAsNotHelpful();
            }

            return back()->with('success', 'Thank you for your feedback!');
        }

        return view('pages.help-center.feedback');
    }
}
