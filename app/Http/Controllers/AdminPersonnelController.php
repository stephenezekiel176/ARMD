<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use App\Models\Personnel;

class AdminPersonnelController extends Controller
{
    public function index()
    {
        $personnel = User::where('role', 'personnel')
            ->with('department')
            ->withCount(['enrollments', 'submissions'])
            ->latest()
            ->get();
        return view('admin.personnel.index', compact('personnel'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();
        $facilitators = User::where('role', 'facilitator')->orderBy('fullname')->get();
        return view('admin.personnel.create', compact('departments', 'facilitators'));
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

        $user = User::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'position' => $request->position,
            'department_id' => $request->department_id,
            'role' => 'personnel',
        ]);

        return redirect()->route('admin.personnel.index')
            ->with('success', 'Personnel created successfully!');
    }

    public function edit(User $personnel)
    {
        if ($personnel->role !== 'personnel') {
            return redirect()->route('admin.personnel.index')
                ->with('error', 'User is not personnel');
        }

        $departments = Department::orderBy('name')->get();
        return view('admin.personnel.edit', compact('personnel', 'departments'));
    }

    public function update(Request $request, User $personnel)
    {
        if ($personnel->role !== 'personnel') {
            return redirect()->route('admin.personnel.index')
                ->with('error', 'User is not personnel');
        }

        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $personnel->id,
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

        $personnel->update($updateData);

        return redirect()->route('admin.personnel.index')
            ->with('success', 'Personnel updated successfully!');
    }

    public function destroy(User $personnel)
    {
        if ($personnel->role !== 'personnel') {
            return redirect()->route('admin.personnel.index')
                ->with('error', 'User is not personnel');
        }

        // Check if personnel has enrollments or submissions
        if ($personnel->enrollments()->count() > 0) {
            return redirect()->route('admin.personnel.index')
                ->with('error', 'Cannot delete personnel with active enrollments. Please remove enrollments first.');
        }

        if ($personnel->submissions()->count() > 0) {
            return redirect()->route('admin.personnel.index')
                ->with('error', 'Cannot delete personnel with assessment submissions. Please remove submissions first.');
        }

        $personnel->delete();

        return redirect()->route('admin.personnel.index')
            ->with('success', 'Personnel deleted successfully!');
    }
}
