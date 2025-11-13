<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Workshop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminWorkshopController extends Controller
{
    public function index(Request $request)
    {
        $query = Workshop::latest('workshop_date');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $workshops = $query->paginate(20);

        return view('admin.workshops.index', compact('workshops'));
    }

    public function create()
    {
        $users = User::whereIn('role', ['facilitator', 'personnel'])->get();
        return view('admin.workshops.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'overview' => 'required|string',
            'outcome' => 'nullable|string',
            'reason' => 'required|string',
            'speakers' => 'nullable|array',
            'workshop_date' => 'required|date',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'venue' => 'nullable|string|max:255',
            'recording_url' => 'nullable|url',
            'images.*' => 'nullable|image|max:5120',
            'materials.*' => 'nullable|file|max:10240',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
        ]);

        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('workshops/images', 'public');
            }
            $validated['images'] = $images;
        }

        if ($request->hasFile('materials')) {
            $materials = [];
            foreach ($request->file('materials') as $material) {
                $materials[] = [
                    'path' => $material->store('workshops/materials', 'public'),
                    'name' => $material->getClientOriginalName(),
                ];
            }
            $validated['materials'] = $materials;
        }

        $validated['slug'] = Str::slug($validated['title']);

        Workshop::create($validated);

        return redirect()->route('admin.workshops.index')->with('success', 'Workshop created successfully.');
    }

    public function edit(Workshop $workshop)
    {
        $users = User::whereIn('role', ['facilitator', 'personnel'])->get();
        return view('admin.workshops.edit', compact('workshop', 'users'));
    }

    public function update(Request $request, Workshop $workshop)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'overview' => 'required|string',
            'outcome' => 'nullable|string',
            'reason' => 'required|string',
            'speakers' => 'nullable|array',
            'workshop_date' => 'required|date',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'venue' => 'nullable|string|max:255',
            'recording_url' => 'nullable|url',
            'images.*' => 'nullable|image|max:5120',
            'materials.*' => 'nullable|file|max:10240',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
        ]);

        if ($request->hasFile('images')) {
            $images = $workshop->images ?? [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('workshops/images', 'public');
            }
            $validated['images'] = $images;
        }

        if ($request->hasFile('materials')) {
            $materials = $workshop->materials ?? [];
            foreach ($request->file('materials') as $material) {
                $materials[] = [
                    'path' => $material->store('workshops/materials', 'public'),
                    'name' => $material->getClientOriginalName(),
                ];
            }
            $validated['materials'] = $materials;
        }

        $validated['slug'] = Str::slug($validated['title']);

        $workshop->update($validated);

        return redirect()->route('admin.workshops.index')->with('success', 'Workshop updated successfully.');
    }

    public function destroy(Workshop $workshop)
    {
        if ($workshop->images) {
            foreach ($workshop->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        if ($workshop->materials) {
            foreach ($workshop->materials as $material) {
                Storage::disk('public')->delete($material['path']);
            }
        }

        $workshop->delete();

        return redirect()->route('admin.workshops.index')->with('success', 'Workshop deleted successfully.');
    }

    public function addAttendee(Request $request, Workshop $workshop)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $workshop->addAttendee($request->user_id);

        return back()->with('success', 'Attendee added successfully.');
    }
}
