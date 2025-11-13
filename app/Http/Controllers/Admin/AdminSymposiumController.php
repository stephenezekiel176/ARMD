<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Symposium;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminSymposiumController extends Controller
{
    public function index(Request $request)
    {
        $query = Symposium::latest('symposium_date');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $symposiums = $query->paginate(20);

        return view('admin.symposiums.index', compact('symposiums'));
    }

    public function create()
    {
        $users = User::whereIn('role', ['facilitator', 'personnel'])->get();
        return view('admin.symposiums.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'required|string',
            'theme' => 'nullable|string',
            'keynote_speakers' => 'nullable|array',
            'panelists' => 'nullable|array',
            'sessions' => 'nullable|array',
            'symposium_date' => 'required|date',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'venue' => 'nullable|string|max:255',
            'outcomes' => 'nullable|string',
            'images.*' => 'nullable|image|max:5120',
            'proceedings.*' => 'nullable|file|max:10240',
            'recordings' => 'nullable|array',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
        ]);

        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('symposiums/images', 'public');
            }
            $validated['images'] = $images;
        }

        if ($request->hasFile('proceedings')) {
            $proceedings = [];
            foreach ($request->file('proceedings') as $file) {
                $proceedings[] = [
                    'path' => $file->store('symposiums/proceedings', 'public'),
                    'name' => $file->getClientOriginalName(),
                ];
            }
            $validated['proceedings'] = $proceedings;
        }

        $validated['slug'] = Str::slug($validated['title']);

        Symposium::create($validated);

        return redirect()->route('admin.symposiums.index')->with('success', 'Symposium created successfully.');
    }

    public function edit(Symposium $symposium)
    {
        $users = User::whereIn('role', ['facilitator', 'personnel'])->get();
        return view('admin.symposiums.edit', compact('symposium', 'users'));
    }

    public function update(Request $request, Symposium $symposium)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'required|string',
            'theme' => 'nullable|string',
            'keynote_speakers' => 'nullable|array',
            'panelists' => 'nullable|array',
            'sessions' => 'nullable|array',
            'symposium_date' => 'required|date',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'venue' => 'nullable|string|max:255',
            'outcomes' => 'nullable|string',
            'images.*' => 'nullable|image|max:5120',
            'proceedings.*' => 'nullable|file|max:10240',
            'recordings' => 'nullable|array',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
        ]);

        if ($request->hasFile('images')) {
            $images = $symposium->images ?? [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('symposiums/images', 'public');
            }
            $validated['images'] = $images;
        }

        if ($request->hasFile('proceedings')) {
            $proceedings = $symposium->proceedings ?? [];
            foreach ($request->file('proceedings') as $file) {
                $proceedings[] = [
                    'path' => $file->store('symposiums/proceedings', 'public'),
                    'name' => $file->getClientOriginalName(),
                ];
            }
            $validated['proceedings'] = $proceedings;
        }

        $validated['slug'] = Str::slug($validated['title']);

        $symposium->update($validated);

        return redirect()->route('admin.symposiums.index')->with('success', 'Symposium updated successfully.');
    }

    public function destroy(Symposium $symposium)
    {
        if ($symposium->images) {
            foreach ($symposium->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        if ($symposium->proceedings) {
            foreach ($symposium->proceedings as $proceeding) {
                Storage::disk('public')->delete($proceeding['path']);
            }
        }

        $symposium->delete();

        return redirect()->route('admin.symposiums.index')->with('success', 'Symposium deleted successfully.');
    }
}
