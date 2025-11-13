<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ForumCategory;
use App\Models\ForumThread;
use App\Models\ForumPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminForumController extends Controller
{
    // Categories Management
    public function categories()
    {
        $categories = ForumCategory::withCount('threads')->orderBy('order')->get();
        return view('admin.forum.categories.index', compact('categories'));
    }

    public function createCategory()
    {
        return view('admin.forum.categories.create');
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:7',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        ForumCategory::create($validated);

        return redirect()->route('admin.forum.categories')->with('success', 'Category created successfully.');
    }

    public function editCategory(ForumCategory $category)
    {
        return view('admin.forum.categories.edit', compact('category'));
    }

    public function updateCategory(Request $request, ForumCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:7',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        $category->update($validated);

        return redirect()->route('admin.forum.categories')->with('success', 'Category updated successfully.');
    }

    public function destroyCategory(ForumCategory $category)
    {
        $category->delete();
        return redirect()->route('admin.forum.categories')->with('success', 'Category deleted successfully.');
    }

    // Threads Management
    public function threads(Request $request)
    {
        $query = ForumThread::with(['user', 'category'])->latest();

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('is_pinned')) {
            $query->where('is_pinned', $request->is_pinned);
        }

        if ($request->filled('is_locked')) {
            $query->where('is_locked', $request->is_locked);
        }

        $threads = $query->paginate(20);
        $categories = ForumCategory::active()->get();

        return view('admin.forum.threads.index', compact('threads', 'categories'));
    }

    public function showThread(ForumThread $thread)
    {
        $thread->load(['user', 'category', 'posts.user']);
        return view('admin.forum.threads.show', compact('thread'));
    }

    public function pinThread(ForumThread $thread)
    {
        $thread->pin();
        return back()->with('success', 'Thread pinned successfully.');
    }

    public function unpinThread(ForumThread $thread)
    {
        $thread->unpin();
        return back()->with('success', 'Thread unpinned successfully.');
    }

    public function lockThread(ForumThread $thread)
    {
        $thread->lock();
        return back()->with('success', 'Thread locked successfully.');
    }

    public function unlockThread(ForumThread $thread)
    {
        $thread->unlock();
        return back()->with('success', 'Thread unlocked successfully.');
    }

    public function destroyThread(ForumThread $thread)
    {
        $thread->delete();
        return redirect()->route('admin.forum.threads')->with('success', 'Thread deleted successfully.');
    }

    // Posts Moderation
    public function posts(Request $request)
    {
        $query = ForumPost::with(['user', 'thread'])->latest();

        if ($request->filled('is_moderated')) {
            $query->where('is_moderated', $request->is_moderated);
        }

        $posts = $query->paginate(20);

        return view('admin.forum.posts.index', compact('posts'));
    }

    public function moderatePost(Request $request, ForumPost $post)
    {
        $request->validate([
            'moderation_note' => 'nullable|string',
        ]);

        $post->moderate($request->moderation_note);

        return back()->with('success', 'Post moderated successfully.');
    }

    public function destroyPost(ForumPost $post)
    {
        $post->delete();
        return back()->with('success', 'Post deleted successfully.');
    }
}
