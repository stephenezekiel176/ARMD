<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PracticalGuide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminPracticalGuideController extends Controller
{
    public function index(Request $request)
    {
        $query = PracticalGuide::with('author')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('difficulty_level')) {
            $query->where('difficulty_level', $request->difficulty_level);
        }

        $guides = $query->paginate(20);
        $categories = PracticalGuide::distinct('category')->pluck('category');

        return view('admin.guides.index', compact('guides', 'categories'));
    }

    public function create()
    {
        $categories = PracticalGuide::distinct('category')->pluck('category');
        return view('admin.guides.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'required|string',
            'steps' => 'nullable|array',
            'category' => 'required|string|max:100',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced',
            'estimated_time' => 'nullable|integer',
            'prerequisites' => 'nullable|array',
            'tools_required' => 'nullable|array',
            'images.*' => 'nullable|image|max:5120',
            'videos' => 'nullable|array',
            'attachments.*' => 'nullable|file|max:10240',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
        ]);

        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('guides/images', 'public');
            }
            $validated['images'] = $images;
        }

        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $attachments[] = [
                    'path' => $file->store('guides/attachments', 'public'),
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

        PracticalGuide::create($validated);

        return redirect()->route('admin.guides.index')->with('success', 'Practical guide created successfully.');
    }

    public function edit(PracticalGuide $guide)
    {
        $categories = PracticalGuide::distinct('category')->pluck('category');
        return view('admin.guides.edit', compact('guide', 'categories'));
    }

    public function update(Request $request, PracticalGuide $guide)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'required|string',
            'steps' => 'nullable|array',
            'category' => 'required|string|max:100',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced',
            'estimated_time' => 'nullable|integer',
            'prerequisites' => 'nullable|array',
            'tools_required' => 'nullable|array',
            'images.*' => 'nullable|image|max:5120',
            'videos' => 'nullable|array',
            'attachments.*' => 'nullable|file|max:10240',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
        ]);

        if ($request->hasFile('images')) {
            $images = $guide->images ?? [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('guides/images', 'public');
            }
            $validated['images'] = $images;
        }

        if ($request->hasFile('attachments')) {
            $attachments = $guide->attachments ?? [];
            foreach ($request->file('attachments') as $file) {
                $attachments[] = [
                    'path' => $file->store('guides/attachments', 'public'),
                    'name' => $file->getClientOriginalName(),
                ];
            }
            $validated['attachments'] = $attachments;
        }

        $validated['slug'] = Str::slug($validated['title']);

        if ($validated['status'] === 'published' && !$guide->published_at && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $guide->update($validated);

        return redirect()->route('admin.guides.index')->with('success', 'Practical guide updated successfully.');
    }

    public function destroy(PracticalGuide $guide)
    {
        if ($guide->images) {
            foreach ($guide->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        if ($guide->attachments) {
            foreach ($guide->attachments as $attachment) {
                Storage::disk('public')->delete($attachment['path']);
            }
        }

        $guide->delete();

        return redirect()->route('admin.guides.index')->with('success', 'Practical guide deleted successfully.');
    }
}
