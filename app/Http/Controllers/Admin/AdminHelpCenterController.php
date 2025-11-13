<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HelpArticle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminHelpCenterController extends Controller
{
    public function index(Request $request)
    {
        $query = HelpArticle::with('author')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('article_type')) {
            $query->where('article_type', $request->article_type);
        }

        $articles = $query->paginate(20);
        $categories = ['Account Settings', 'Troubleshooting', 'Getting Started', 'Features', 'Billing', 'Security'];

        return view('admin.help-center.index', compact('articles', 'categories'));
    }

    public function create()
    {
        $categories = ['Account Settings', 'Troubleshooting', 'Getting Started', 'Features', 'Billing', 'Security'];
        return view('admin.help-center.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string|max:500',
            'content' => 'required|string',
            'category' => 'required|string|max:100',
            'tags' => 'nullable|array',
            'related_articles' => 'nullable|array',
            'attachments.*' => 'nullable|file|max:10240',
            'video_tutorials' => 'nullable|array',
            'article_type' => 'required|in:faq,guide,tutorial,troubleshooting,announcement',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
        ]);

        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $attachments[] = [
                    'path' => $file->store('help/attachments', 'public'),
                    'name' => $file->getClientOriginalName(),
                ];
            }
            $validated['attachments'] = $attachments;
        }

        $validated['author_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['title']);

        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        HelpArticle::create($validated);

        return redirect()->route('admin.help-center.index')->with('success', 'Help article created successfully.');
    }

    public function edit(HelpArticle $helpCenter)
    {
        $categories = ['Account Settings', 'Troubleshooting', 'Getting Started', 'Features', 'Billing', 'Security'];
        return view('admin.help-center.edit', compact('helpCenter', 'categories'));
    }

    public function update(Request $request, HelpArticle $helpCenter)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string|max:500',
            'content' => 'required|string',
            'category' => 'required|string|max:100',
            'tags' => 'nullable|array',
            'related_articles' => 'nullable|array',
            'attachments.*' => 'nullable|file|max:10240',
            'video_tutorials' => 'nullable|array',
            'article_type' => 'required|in:faq,guide,tutorial,troubleshooting,announcement',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
        ]);

        if ($request->hasFile('attachments')) {
            $attachments = $helpCenter->attachments ?? [];
            foreach ($request->file('attachments') as $file) {
                $attachments[] = [
                    'path' => $file->store('help/attachments', 'public'),
                    'name' => $file->getClientOriginalName(),
                ];
            }
            $validated['attachments'] = $attachments;
        }

        $validated['slug'] = Str::slug($validated['title']);

        if ($validated['status'] === 'published' && !$helpCenter->published_at && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $helpCenter->update($validated);

        return redirect()->route('admin.help-center.index')->with('success', 'Help article updated successfully.');
    }

    public function destroy(HelpArticle $helpCenter)
    {
        if ($helpCenter->attachments) {
            foreach ($helpCenter->attachments as $attachment) {
                Storage::disk('public')->delete($attachment['path']);
            }
        }

        $helpCenter->delete();

        return redirect()->route('admin.help-center.index')->with('success', 'Help article deleted successfully.');
    }

    public function feedbacks(HelpArticle $helpCenter)
    {
        $feedbacks = $helpCenter->feedbacks()->with('user')->latest()->paginate(20);
        
        return view('admin.help-center.feedbacks', compact('helpCenter', 'feedbacks'));
    }
}
