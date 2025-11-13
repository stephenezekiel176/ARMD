<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $departments = Department::all();
        return view('auth.register', compact('departments'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'department_id' => ['required', 'exists:departments,id'],
            'position' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'in:facilitator,personnel'],
            // invite_code is required when role is facilitator
            'invite_code' => ['nullable', 'string'],
        ]);

        // If the user is registering as a facilitator, ensure the invite code matches the configured secret
        if ($request->role === 'facilitator') {
            $expected = env('FACILITATOR_INVITE_CODE');
            if (empty($expected) || $request->invite_code !== $expected) {
                return back()->withInput()->withErrors(['invite_code' => 'Invalid facilitator invite code.']);
            }
        }

        $user = User::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'department_id' => $request->department_id,
            'position' => $request->position,
            'role' => $request->role,
            'points' => 0,
            'badges' => [],
        ]);

        event(new Registered($user));

        Auth::login($user);
        // Redirect users to the appropriate dashboard based on role
        if ($user->role === 'facilitator') {
            return redirect()->route('facilitator.dashboard');
        }

        if ($user->role === 'personnel') {
            return redirect()->route('personnel.dashboard');
        }

        if ($user->role === 'secondary_admin') {
            return redirect()->route('admin.dashboard');
        }

        // Fallback to home if no specific dashboard exists
        return redirect()->route('home');
    }
}
