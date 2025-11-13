<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    /**
     * Display a listing of all users.
     */
    public function index(Request $request)
    {
        $query = User::with('department')
            ->where('role', '!=', 'secondary_admin'); // Hide admin users from regular list

        // Filter by role if specified
        if ($request->has('role') && in_array($request->role, ['facilitator', 'personnel'])) {
            $query->where('role', $request->role);
        }

        // Filter by department if specified
        if ($request->has('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        // Search by name or email
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('fullname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15);
        $departments = Department::all();

        return view('admin.users.index', compact('users', 'departments'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $departments = Department::all();
        return view('admin.users.create', compact('departments'));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => ['required', Rule::in(['facilitator', 'personnel'])],
            'department_id' => 'required|exists:departments,id',
            'position' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'role' => $request->role,
            'department_id' => $request->department_id,
            'position' => $request->position,
            'password' => Hash::make($request->password),
            'points' => 0,
            'badges' => [],
        ]);

        // Avatar will be automatically assigned via User model boot method

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created successfully!');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        // Prevent viewing admin users
        if ($user->role === 'secondary_admin') {
            abort(403);
        }

        $user->load(['department', 'enrollments.course', 'submissions.assessment']);
        
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        // Prevent editing admin users
        if ($user->role === 'secondary_admin') {
            abort(403);
        }

        $departments = Department::all();
        return view('admin.users.edit', compact('user', 'departments'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        // Prevent editing admin users
        if ($user->role === 'secondary_admin') {
            abort(403);
        }

        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in(['facilitator', 'personnel'])],
            'department_id' => 'required|exists:departments,id',
            'position' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $updateData = [
            'fullname' => $request->fullname,
            'email' => $request->email,
            'role' => $request->role,
            'department_id' => $request->department_id,
            'position' => $request->position,
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated successfully!');
    }

    /**
     * Change user role (facilitator <-> personnel)
     */
    public function changeRole(Request $request, User $user)
    {
        // Prevent changing admin users
        if ($user->role === 'secondary_admin') {
            abort(403);
        }

        $request->validate([
            'role' => ['required', Rule::in(['facilitator', 'personnel'])],
        ]);

        $oldRole = $user->role;
        $user->update(['role' => $request->role]);

        return redirect()
            ->back()
            ->with('success', "User role changed from {$oldRole} to {$request->role} successfully!");
    }

    /**
     * Change user avatar
     */
    public function changeAvatar(Request $request, User $user)
    {
        // Prevent changing admin users
        if ($user->role === 'secondary_admin') {
            abort(403);
        }

        $oldAvatar = $user->getAvatarDisplayName();
        $newAvatar = $user->changeAvatar();

        return redirect()
            ->back()
            ->with('success', "User avatar changed from '{$oldAvatar}' to '{$user->getAvatarDisplayName()}' successfully!");
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        // Prevent deleting admin users
        if ($user->role === 'secondary_admin') {
            abort(403);
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }
}
