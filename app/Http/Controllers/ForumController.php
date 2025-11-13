<?php

namespace App\Http\Controllers;

use App\Models\ForumCategory;
use App\Models\ForumThread;
use App\Models\ForumPost;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    public function topics(Request $request)
    {
        $query = ForumThread::with(['user', 'category'])->active();

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $threads = $query->paginate(20);
        $categories = ForumCategory::active()->withCount('threads')->get();

        return view('pages.help-forum.topics', compact('threads', 'categories'));
    }

    public function show(ForumThread $thread)
    {
        $thread->incrementViews();
        $thread->load(['user', 'category', 'posts.user', 'posts.reactions']);

        return view('pages.help-forum.thread-view', compact('thread'));
    }

    public function create()
    {
        $categories = ForumCategory::active()->get();
        return view('pages.help-forum.create-thread', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:forum_categories,id',
            'tags' => 'nullable|array',
        ]);

        $validated['user_id'] = auth()->id();

        $thread = ForumThread::create($validated);

        return redirect()->route('forum.show', $thread)->with('success', 'Thread created successfully!');
    }

    public function storePost(Request $request, ForumThread $thread)
    {
        if ($thread->is_locked) {
            return back()->with('error', 'This thread is locked.');
        }

        $validated = $request->validate([
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:forum_posts,id',
        ]);

        $validated['thread_id'] = $thread->id;
        $validated['user_id'] = auth()->id();

        ForumPost::create($validated);

        return back()->with('success', 'Reply posted successfully!');
    }

    public function guidelines()
    {
        return view('pages.help-forum.guidelines');
    }

    public function profiles()
    {
        $topContributors = \App\Models\User::withCount(['forumPosts', 'forumThreads'])
            ->having('forum_posts_count', '>', 0)
            ->orderByDesc('forum_posts_count')
            ->limit(20)
            ->get();

        return view('pages.help-forum.profiles', compact('topContributors'));
    }

    public function events()
    {
        return view('pages.help-forum.events');
    }
}
