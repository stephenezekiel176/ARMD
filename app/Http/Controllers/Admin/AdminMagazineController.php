<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MagazineIssue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminMagazineController extends Controller
{
    public function index(Request $request)
    {
        $query = MagazineIssue::with('editor')->latest('issue_date');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('issue_number', 'like', '%' . $request->search . '%');
            });
        }

        $issues = $query->paginate(20);

        return view('admin.magazine.index', compact('issues'));
    }

    public function create()
    {
        $categories = ['Technology', 'Business', 'Education', 'Healthcare', 'Finance', 'Science', 'Arts', 'Sports'];
        return view('admin.magazine.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'issue_number' => 'required|string|max:50|unique:magazine_issues',
            'description' => 'required|string',
            'content' => 'required|string',
            'cover_image' => 'nullable|image|max:5120',
            'images.*' => 'nullable|image|max:5120',
            'pdf_file' => 'nullable|file|mimes:pdf|max:20480',
            'sections' => 'nullable|array',
            'type' => 'required|in:internal,external,both',
            'status' => 'required|in:draft,published,archived',
            'issue_date' => 'required|date',
            'published_at' => 'nullable|date',
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('magazine/covers', 'public');
        }

        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('magazine/images', 'public');
            }
            $validated['images'] = $images;
        }

        if ($request->hasFile('pdf_file')) {
            $validated['pdf_file'] = $request->file('pdf_file')->store('magazine/pdfs', 'public');
        }

        $validated['editor_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['title']);

        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        MagazineIssue::create($validated);

        return redirect()->route('admin.magazine.index')->with('success', 'Magazine issue created successfully.');
    }

    public function edit(MagazineIssue $magazine)
    {
        $categories = ['Technology', 'Business', 'Education', 'Healthcare', 'Finance', 'Science', 'Arts', 'Sports'];
        return view('admin.magazine.edit', compact('magazine', 'categories'));
    }

    public function update(Request $request, MagazineIssue $magazine)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'issue_number' => 'required|string|max:50|unique:magazine_issues,issue_number,' . $magazine->id,
            'description' => 'required|string',
            'content' => 'required|string',
            'cover_image' => 'nullable|image|max:5120',
            'images.*' => 'nullable|image|max:5120',
            'pdf_file' => 'nullable|file|mimes:pdf|max:20480',
            'sections' => 'nullable|array',
            'type' => 'required|in:internal,external,both',
            'status' => 'required|in:draft,published,archived',
            'issue_date' => 'required|date',
            'published_at' => 'nullable|date',
        ]);

        if ($request->hasFile('cover_image')) {
            if ($magazine->cover_image) {
                Storage::disk('public')->delete($magazine->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('magazine/covers', 'public');
        }

        if ($request->hasFile('images')) {
            $images = $magazine->images ?? [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('magazine/images', 'public');
            }
            $validated['images'] = $images;
        }

        if ($request->hasFile('pdf_file')) {
            if ($magazine->pdf_file) {
                Storage::disk('public')->delete($magazine->pdf_file);
            }
            $validated['pdf_file'] = $request->file('pdf_file')->store('magazine/pdfs', 'public');
        }

        $validated['slug'] = Str::slug($validated['title']);

        if ($validated['status'] === 'published' && !$magazine->published_at && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $magazine->update($validated);

        return redirect()->route('admin.magazine.index')->with('success', 'Magazine issue updated successfully.');
    }

    public function destroy(MagazineIssue $magazine)
    {
        if ($magazine->cover_image) {
            Storage::disk('public')->delete($magazine->cover_image);
        }

        if ($magazine->images) {
            foreach ($magazine->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        if ($magazine->pdf_file) {
            Storage::disk('public')->delete($magazine->pdf_file);
        }

        $magazine->delete();

        return redirect()->route('admin.magazine.index')->with('success', 'Magazine issue deleted successfully.');
    }
}
