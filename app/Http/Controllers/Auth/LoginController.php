<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use App\Mail\DepartmentAccessCodeMail;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm(Request $request)
    {
        try {
            // Get the login type from URL parameter
            $loginType = $request->get('type', 'personnel');
            
            // First try to load all departments
            $departments = Department::query()->get();
            \Log::info('Departments loaded:', ['count' => $departments->count()]);

            $department = null;
            $showPasswordForm = false;

            // If this is a facilitator login, show department selection
            if ($loginType === 'facilitator') {
                $showPasswordForm = false;
                $deptMode = true;
                
                // If dept parameter is provided, try to load that specific department
                if ($request->has('dept')) {
                    $department = Department::find($request->get('dept'));
                    \Log::info('Department requested:', [
                        'dept_id' => $request->get('dept'),
                        'found' => !is_null($department)
                    ]);

                    // If we found the department, we want to show its login form
                    if ($department) {
                        return view('auth.login', compact('department', 'departments', 'showPasswordForm', 'deptMode', 'loginType'));
                    }
                }
                
                return view('auth.login', compact('departments', 'department', 'showPasswordForm', 'deptMode', 'loginType'));
            }

            // For personnel login, always show email/password form
            if ($loginType === 'personnel') {
                $showPasswordForm = true;
                $deptMode = false;
                return view('auth.login', compact('departments', 'department', 'showPasswordForm', 'deptMode', 'loginType'));
            }

            // Fallback to original logic for backward compatibility
            // If no departments exist or password mode is explicitly requested
            $showPasswordForm = $request->get('mode') === 'password'
                || $request->get('show') === 'password'
                || $departments->isEmpty();
            
            // Determine if we should start in department mode
            $deptMode = !$showPasswordForm && $departments->isNotEmpty() && !$request->has('dept');

            \Log::info('Login form parameters:', [
                'showPasswordForm' => $showPasswordForm,
                'hasDepartments' => $departments->isNotEmpty(),
                'mode' => $request->get('mode'),
                'show' => $request->get('show')
            ]);

            return view('auth.login', compact('departments', 'department', 'showPasswordForm', 'deptMode', 'loginType'));
        } catch (\Exception $e) {
            \Log::error('Error in showLoginForm:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return view('auth.login', [
                'departments' => collect(),
                'department' => null,
                'showPasswordForm' => true,
                'deptMode' => false,
                'loginType' => 'personnel'
            ]);
        }
    }

    /**
     * Resend the department access code to a user's registered email.
     */
    public function resendDepartmentCode(Request $request)
    {
        $request->validate([
            'department_id' => ['required', 'exists:departments,id'],
            'email' => ['required', 'email'],
        ]);

        $department = Department::findOrFail($request->department_id);

        $user = \App\Models\User::where('email', $request->email)
                    ->where('department_id', $department->id)
                    ->first();

        if (! $user) {
            return redirect()->back()->withErrors(['email' => 'No user found with that email in the selected department.'])->withInput();
        }

        try {
            Mail::to($user->email)->queue(new DepartmentAccessCodeMail($user, $department));
        } catch (\Throwable $e) {
            logger()->warning('Failed to queue department access code email: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to send email at this time. Please try again later.');
        }

        return redirect()->back()->with('status', 'Department access code resent to your email.');
    }

    public function login(Request $request)
    {
        // Handle facilitator login
        if ($request->input('login_type') === 'facilitator') {
            $request->validate([
                'facilitator_name' => ['required', 'string'],
                'facilitator_code' => ['required', 'string'],
            ]);

            // Find facilitator by name and code
            $facilitator = User::where('fullname', $request->facilitator_name)
                               ->where('role', 'facilitator')
                               ->where('facilitator_code', $request->facilitator_code)
                               ->first();

            if (!$facilitator) {
                throw ValidationException::withMessages([
                    'facilitator_code' => ['Invalid facilitator name or code.'],
                ]);
            }

            // Clear any existing authenticated session
            if (Auth::check()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                try {
                    Cookie::queue(Cookie::forget(Auth::getRecallerName()));
                } catch (\Throwable $e) {
                    // ignore if recaller name not available
                }
            }

            Auth::login($facilitator);
            $request->session()->regenerate();
            return $this->redirectBasedOnRole();
        }

        // Handle regular email/password login
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Ensure any existing authenticated session is cleared before attempting a new login
        if (Auth::check()) {
            // logout and clear session
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            // also remove any "remember me" cookie so Laravel won't auto-login on next request
            try {
                Cookie::queue(Cookie::forget(Auth::getRecallerName()));
            } catch (\Throwable $e) {
                // ignore if recaller name not available
            }
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return $this->redirectBasedOnRole();
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    /**
     * Show dedicated admin login form (email/password only)
     */
    public function showAdminLogin(Request $request)
    {
        return view('admin.auth.login');
    }

    /**
     * Handle admin login and ensure user is a secondary_admin
     */
    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Clear any existing authenticated session so failed attempts don't leave a previous user logged in
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            try {
                Cookie::queue(Cookie::forget(Auth::getRecallerName()));
            } catch (\Throwable $e) {
            }
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            if ($user->role !== 'secondary_admin') {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => ['You do not have admin access.'],
                ]);
            }
            return redirect()->route('admin.dashboard');
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    public function departmentLogin(Request $request)
    {
        $request->validate([
            'fullname' => ['required', 'string'],
            'special_code' => ['required', 'string'],
            'department_id' => ['required', 'exists:departments,id'],
        ]);

        $department = Department::findOrFail($request->department_id);

        if ($department->special_code !== $request->special_code) {
            throw ValidationException::withMessages([
                'special_code' => ['The special code is incorrect.'],
            ]);
        }

        $user = User::where('fullname', $request->fullname)
                   ->where('department_id', $department->id)
                   ->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'fullname' => ['User not found in this department.'],
            ]);
        }

        // Clear any existing session to avoid accidentally keeping another account logged in
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            try {
                Cookie::queue(Cookie::forget(Auth::getRecallerName()));
            } catch (\Throwable $e) {
            }
        }

        Auth::login($user);
        $request->session()->regenerate();

        return $this->redirectBasedOnRole();
    }

    protected function redirectBasedOnRole()
    {
        $user = Auth::user();

        return match($user->role) {
            'facilitator' => redirect()->route('facilitator.dashboard'),
            'personnel' => redirect()->route('personnel.dashboard'),
            'secondary_admin' => redirect()->route('admin.dashboard'),
            default => redirect()->route('home'),
        };
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
