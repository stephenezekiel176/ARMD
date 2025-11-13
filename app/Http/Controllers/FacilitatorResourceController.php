<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Storage;

class FacilitatorResourceController extends Controller
{
    public function videos()
    {
        $videos = Course::where('type', 'video')
            ->where('scope', 'department')  // Only department-specific content
            ->where('facilitator_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('facilitator.resources.videos', compact('videos'));
    }

    public function ebooks()
    {
        $ebooks = Course::where('type', 'ebook')
            ->where('scope', 'department')  // Only department-specific content
            ->where('facilitator_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('facilitator.resources.ebooks', compact('ebooks'));
    }

    public function audios()
    {
        $audios = Course::where('type', 'audio')
            ->where('scope', 'department')  // Only department-specific content
            ->where('facilitator_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('facilitator.resources.audios', compact('audios'));
    }

    public function images()
    {
        $images = Course::where('type', 'image')
            ->where('scope', 'department')  // Only department-specific content
            ->where('facilitator_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('facilitator.resources.images', compact('images'));
    }

    public function podcasts()
    {
        $podcasts = Course::where('type', 'podcast')
            ->where('scope', 'department')  // Only department-specific content
            ->where('facilitator_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('facilitator.resources.podcasts', compact('podcasts'));
    }

    public function index()
    {
        $resources = Course::where('facilitator_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('facilitator.resources.index', compact('resources'));
    }
    public function create()
    {
        return view('facilitator.resources.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:video,ebook,audio,image,podcast',
            'file' => 'required|file|mimes:mp4,pdf,mp3,wav,jpg,jpeg,png,m4a|max:200000',
            'description' => 'nullable|string',
            'is_previewable' => 'nullable|boolean',
            'duration' => 'nullable|integer',
            'department_id' => 'required|exists:departments,id',
        ]);

        $path = $request->file('file')->store('resources', 'public');

        $course = Course::create([
            'title' => $request->title,
            'type' => $request->type,
            'file_path' => $path,
            'description' => $request->description ?? '',
            'department_id' => $request->department_id,
            'facilitator_id' => auth()->id(),
            'duration' => $request->duration ?? 0,
            'is_previewable' => boolval($request->is_previewable),
            'scope' => 'department',  // Facilitator content is department-specific
        ]);

        return redirect()->route('facilitator.dashboard')->with('success', 'Resource uploaded.');
    }

    public function show(Course $resource)
    {
        // Ensure facilitator owns this resource
        if ($resource->facilitator_id !== auth()->id()) {
            abort(403);
        }

        return view('facilitator.resources.show', ['resource' => $resource]);
    }

    public function edit(Course $resource)
    {
        if ($resource->facilitator_id !== auth()->id()) {
            abort(403);
        }

        return view('facilitator.resources.edit', ['resource' => $resource]);
    }

    public function update(Request $request, Course $resource)
    {
        if ($resource->facilitator_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:video,ebook,audio,image,podcast',
            'file' => 'nullable|file|mimes:mp4,pdf,mp3,wav,jpg,jpeg,png,m4a|max:200000',
        ]);

        if ($request->hasFile('file')) {
            // remove old file
            if ($resource->file_path && Storage::disk('public')->exists($resource->file_path)) {
                Storage::disk('public')->delete($resource->file_path);
            }
            $resource->file_path = $request->file('file')->store('resources', 'public');
        }

        $resource->title = $request->title;
        $resource->description = $request->description ?? '';
        $resource->type = $request->type;
        $resource->save();

        return redirect()->route('facilitator.resources.index')->with('success', 'Resource updated.');
    }

    public function destroy(Course $resource)
    {
        if ($resource->facilitator_id !== auth()->id()) {
            abort(403);
        }

        if ($resource->file_path && Storage::disk('public')->exists($resource->file_path)) {
            Storage::disk('public')->delete($resource->file_path);
        }

        $resource->delete();

        return redirect()->route('facilitator.resources.index')->with('success', 'Resource deleted.');
    }
}
