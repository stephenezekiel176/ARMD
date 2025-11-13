<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;

class AdminFacilitatorController extends Controller
{
    public function index()
    {
        $facilitators = User::where('role','facilitator')
            ->with('department')
            ->withCount(['courses'])
            ->withCount(['personnel'])
            ->latest()
            ->get()
            ->map(function ($facilitator) {
                $facilitator->department_users_count = User::where('department_id', $facilitator->department_id)
                    ->where('role', 'personnel')
                    ->count();
                return $facilitator;
            });
        return view('admin.facilitators.index', compact('facilitators'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();
        return view('admin.facilitators.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'position' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
        ]);

        User::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'position' => $request->position,
            'department_id' => $request->department_id,
            'role' => 'facilitator',
        ]);

        return redirect()->route('admin.facilitators.index')
            ->with('success', 'Facilitator created successfully!');
    }

    public function edit(User $facilitator)
    {
        if ($facilitator->role !== 'facilitator') {
            return redirect()->route('admin.facilitators.index')
                ->with('error', 'User is not a facilitator');
        }

        $departments = Department::orderBy('name')->get();
        return view('admin.facilitators.edit', compact('facilitator', 'departments'));
    }

    public function update(Request $request, User $facilitator)
    {
        if ($facilitator->role !== 'facilitator') {
            return redirect()->route('admin.facilitators.index')
                ->with('error', 'User is not a facilitator');
        }

        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $facilitator->id,
            'position' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $updateData = [
            'fullname' => $request->fullname,
            'email' => $request->email,
            'position' => $request->position,
            'department_id' => $request->department_id,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = bcrypt($request->password);
        }

        $facilitator->update($updateData);

        return redirect()->route('admin.facilitators.index')
            ->with('success', 'Facilitator updated successfully!');
    }

    public function destroy(User $facilitator)
    {
        if ($facilitator->role !== 'facilitator') {
            return redirect()->route('admin.facilitators.index')
                ->with('error', 'User is not a facilitator');
        }

        // Check if facilitator has courses
        if ($facilitator->courses()->count() > 0) {
            return redirect()->route('admin.facilitators.index')
                ->with('error', 'Cannot delete facilitator with assigned courses. Please reassign or delete courses first.');
        }

        $facilitator->delete();

        return redirect()->route('admin.facilitators.index')
            ->with('success', 'Facilitator deleted successfully!');
    }
}
