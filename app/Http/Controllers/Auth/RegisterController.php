<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use App\Mail\DepartmentAccessCodeMail;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        $departments = Department::all();
        return view('auth.register', compact('departments'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'department_id' => ['required', 'exists:departments,id'],
            'position' => ['required', 'string', 'max:255'],
            'role' => ['required', Rule::in(['facilitator', 'personnel'])],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'department_id' => $request->department_id,
            'position' => $request->position,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'points' => 0,
            'badges' => [],
        ]);

        Auth::login($user);

        // Send department access code to the user via email
        try {
            $department = Department::find($user->department_id);
            if ($department) {
                // Queue the access code email so registration isn't blocked by mail latency
                Mail::to($user->email)->queue(new DepartmentAccessCodeMail($user, $department));
            }
        } catch (\Throwable $e) {
            // Don't block registration on mail failures; log if needed
            logger()->warning('Failed to queue department access code email: ' . $e->getMessage());
        }

        // Redirect users to their role-specific dashboard if no intended URL is set
        $role = $user->role;

        if ($role === 'facilitator') {
            $dashboardRoute = 'facilitator.dashboard';
        } elseif ($role === 'personnel') {
            $dashboardRoute = 'personnel.dashboard';
        } else {
            // Fallback to home if the role does not have a dashboard route
            $dashboardRoute = 'home';
        }

        return redirect()->intended(route($dashboardRoute));
    }
}
