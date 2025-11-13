@extends('layouts.app')

@section('content')
<div class="flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
            @if(request()->get('type') === 'facilitator')
                Sign in as Facilitator
            @elseif(request()->get('type') === 'personnel')
                Sign in as Personnel
            @else
                Sign in to your account
            @endif
        </h2>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white dark:bg-gray-800 px-4 py-8 shadow sm:rounded-lg sm:px-10">
            @php
                $loginType = request()->get('type', 'personnel');
                $isFacilitator = $loginType === 'facilitator';
                $isPersonnel = $loginType === 'personnel';
                
                // If validation errors for department-login exist or the request explicitly asks for department mode,
                // show the department form by default. Otherwise default to email/password.
                $deptMode = old('special_code') || old('department_id') || $errors->has('special_code') || $errors->has('department_id') || $errors->has('fullname') || request()->get('mode') === 'department';
            @endphp

            @if($isFacilitator)
                <!-- Facilitator Login Form -->
                <div class="space-y-6">
                    <div class="text-center mb-4">
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            Enter your facilitator name and code to sign in
                        </p>
                    </div>
                    
                    <form action="{{ route('login') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="login_type" value="facilitator">
                        
                        <div>
                            <label for="facilitator_name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                Facilitator Name
                            </label>
                            <div class="mt-1">
                                <input id="facilitator_name" name="facilitator_name" type="text" required autocomplete="off"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                    placeholder="Enter your full name">
                            </div>
                            @error('facilitator_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="facilitator_code" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                Facilitator Login Code
                            </label>
                            <div class="mt-1">
                                <input id="facilitator_code" name="facilitator_code" type="text" required autocomplete="off"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                    placeholder="Enter your unique facilitator code">
                            </div>
                            @error('facilitator_code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <button type="submit"
                                class="flex w-full justify-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                                Sign in as Facilitator
                            </button>
                        </div>
                    </form>
                </div>
            @elseif($isPersonnel)
                <!-- Personnel Login Form -->
                <div class="space-y-6">
                    <div class="text-center mb-4">
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            Sign in with your email and password
                        </p>
                    </div>

                    <form id="emailLoginForm" class="space-y-6" action="{{ route('login') }}" method="POST">
                        @csrf

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                Email address
                            </label>
                            <div class="mt-1">
                                <input id="email" name="email" type="email" autocomplete="email" required
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                            </div>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                Password
                            </label>
                            <div class="mt-1">
                                <input id="password" name="password" type="password" required
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                            </div>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input id="remember" name="remember" type="checkbox"
                                    class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-primary-600 focus:ring-primary-500">
                                <label for="remember" class="ml-2 block text-sm text-gray-700 dark:text-gray-200">
                                    Remember me
                                </label>
                            </div>
                        </div>

                        <div>
                            <button type="submit"
                                class="flex w-full justify-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                                Sign in
                            </button>
                        </div>
                    </form>

                    <!-- Department access fallback -->
                    <div class="mt-6 border-t pt-6">
                        <div class="text-center mb-4">
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                Or use department access code
                            </p>
                        </div>
                        
                        <div id="deptFallbackForm" class="space-y-6" style="display:none;">
                            <form action="{{ route('login.department') }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label for="dept_fullname" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Full name</label>
                                    <div class="mt-1">
                                        <input id="dept_fullname" name="fullname" type="text" required value="{{ old('fullname') }}"
                                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                    </div>
                                    @error('fullname')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="dept_department_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Department</label>
                                    <div class="mt-1">
                                        <select id="dept_department_id" name="department_id" required
                                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                            <option value="">Select a department</option>
                                            @if(isset($departments) && $departments->count())
                                                @foreach($departments as $dpt)
                                                    <option value="{{ $dpt->id }}" {{ old('department_id') == $dpt->id ? 'selected' : '' }}>{{ $dpt->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    @error('department_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="dept_special_code" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Department special code</label>
                                    <div class="mt-1">
                                        <input id="dept_special_code" name="special_code" type="text" required value="{{ old('special_code') }}"
                                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                    </div>
                                    @error('special_code')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <button type="submit"
                                        class="flex w-full justify-center rounded-md border border-primary-600 bg-white text-primary-600 px-3 py-2 text-sm font-semibold shadow-sm hover:bg-primary-50">
                                        Sign in with department access
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <div class="mt-4 text-center">
                            <button type="button" onclick="toggleDepartmentMode()" class="text-sm text-primary-600 hover:text-primary-500">
                                Use department access code
                            </button>
                        </div>
                    </div>
                </div>
            @elseif(isset($department))
                <form class="space-y-6" action="{{ route('login.department') }}" method="POST">
                    @csrf
                    <input type="hidden" name="department_id" value="{{ $department->id }}">

                    <div>
                        <label for="fullname" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                            Full name
                        </label>
                        <div class="mt-1">
                            <input id="fullname" name="fullname" type="text" required
                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                        </div>
                        @error('fullname')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="special_code" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                            Department Special Code
                        </label>
                        <div class="mt-1">
                            <input id="special_code" name="special_code" type="text" required
                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                        </div>
                        @error('special_code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <button type="submit"
                            class="flex w-full justify-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                            Sign in to {{ $department->name }}
                        </button>
                    </div>
                </form>
                <div class="mt-4">
                    <details class="text-sm text-gray-600 dark:text-gray-300">
                        <summary class="cursor-pointer font-medium">Resend department code to your email</summary>
                        <form action="{{ route('department.resend') }}" method="POST" class="mt-3">
                            @csrf
                            <input type="hidden" name="department_id" value="{{ $department->id }}">
                            <label for="resend_email" class="block text-sm">Registered email</label>
                            <input id="resend_email" name="email" type="email" required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                            <div class="mt-3">
                                <button type="submit" class="inline-flex items-center rounded-md bg-white text-primary-600 px-4 py-2 text-sm font-semibold hover:bg-primary-50">
                                    Resend Code
                                </button>
                            </div>
                        </form>
                    </details>
                    @if(session('status'))
                        <p class="mt-2 text-sm text-green-600">{{ session('status') }}</p>
                    @endif
                    @if(session('error'))
                        <p class="mt-2 text-sm text-red-600">{{ session('error') }}</p>
                    @endif
                </div>
            @elseif(isset($departments) && $departments->count() && empty($showPasswordForm))
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Choose your department</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Select the department you belong to to continue with department access.</p>

                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($departments as $dept)
                            @php
                                $funcs = json_decode($dept->functionalities ?? '[]', true) ?: [];
                                $short = array_slice($funcs, 0, 3);
                                $initial = strtoupper(mb_substr($dept->name ?? 'D', 0, 1));
                            @endphp
                            <div class="rounded-lg border bg-white dark:bg-gray-800 p-4 shadow-sm">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 flex items-center justify-center rounded-full bg-primary-600 text-white font-semibold">{{ $initial }}</div>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $dept->name }}</h4>
                                        @if(!empty($dept->slogan))
                                            <p class="mt-1 text-xs text-gray-600 dark:text-gray-300">{{ $dept->slogan }}</p>
                                        @endif
                                        @if(count($short))
                                            <ul class="mt-2 text-xs text-gray-600 dark:text-gray-300 space-y-1">
                                                @foreach($short as $item)
                                                    <li class="flex items-start gap-2">
                                                        <span class="mt-0.5 inline-block h-2 w-2 rounded-full bg-gray-400"></span>
                                                        <span class="truncate">{{ $item }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-3 grid grid-cols-2 gap-2">
                                    <a href="{{ route('login', ['dept' => $dept->id]) }}"
                                       class="inline-flex items-center justify-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white hover:bg-primary-500">
                                        Continue to {{ $dept->name }}
                                    </a>
                                    @if(count($funcs))
                                        <button type="button" onclick='showFuncs({{ $dept->id }}, @json($funcs), @json($dept->name))' class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                            View responsibilities
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 text-sm">
                        <a href="{{ route('login', ['mode' => 'password']) }}" class="text-primary-600 hover:underline">Or sign in with email and password</a>
                    </div>
                </div>
            @else
                @php
                    $loginType = request()->get('type', 'personnel');
                    $isFacilitator = $loginType === 'facilitator';
                    $isPersonnel = $loginType === 'personnel';
                    
                    // If validation errors for department-login exist or the request explicitly asks for department mode,
                    // show the department form by default. Otherwise default to email/password.
                    $deptMode = old('special_code') || old('department_id') || $errors->has('special_code') || $errors->has('department_id') || $errors->has('fullname') || request()->get('mode') === 'department';
                @endphp

                @if($isFacilitator)
                    <!-- Facilitator Login Form -->
                    <div class="space-y-6">
                        <div class="text-center mb-4">
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                Enter your facilitator name and code to sign in
                            </p>
                        </div>
                        
                        <form action="{{ route('login') }}" method="POST" class="space-y-6">
                            @csrf
                            <input type="hidden" name="login_type" value="facilitator">
                            
                            <div>
                                <label for="facilitator_name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                    Facilitator Name
                                </label>
                                <div class="mt-1">
                                    <input id="facilitator_name" name="facilitator_name" type="text" required autocomplete="off"
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                        placeholder="Enter your full name">
                                </div>
                                @error('facilitator_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="facilitator_code" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                    Facilitator Login Code
                                </label>
                                <div class="mt-1">
                                    <input id="facilitator_code" name="facilitator_code" type="text" required autocomplete="off"
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                        placeholder="Enter your unique facilitator code">
                                </div>
                                @error('facilitator_code')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <button type="submit"
                                    class="flex w-full justify-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                                    Sign in as Facilitator
                                </button>
                            </div>
                        </form>
                    </div>
                @elseif($isPersonnel)
                    <!-- Personnel Login Form -->
                    <div class="space-y-6">
                        <div class="text-center mb-4">
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                Sign in with your email and password
                            </p>
                        </div>

                        <form id="emailLoginForm" class="space-y-6" action="{{ route('login') }}" method="POST">
                            @csrf

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                    Email address
                                </label>
                                <div class="mt-1">
                                    <input id="email" name="email" type="email" autocomplete="email" required
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                </div>
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                    Password
                                </label>
                                <div class="mt-1">
                                    <input id="password" name="password" type="password" required
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                </div>
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <input id="remember" name="remember" type="checkbox"
                                        class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-primary-600 focus:ring-primary-500">
                                    <label for="remember" class="ml-2 block text-sm text-gray-700 dark:text-gray-200">
                                        Remember me
                                    </label>
                                </div>
                            </div>

                            <div>
                                <button type="submit"
                                    class="flex w-full justify-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                                    Sign in
                                </button>
                            </div>
                        </form>

                        <!-- Department access fallback -->
                        <div class="mt-6 border-t pt-6">
                            <div class="text-center mb-4">
                                <p class="text-sm text-gray-600 dark:text-gray-300">
                                    Or use department access code
                                </p>
                            </div>
                            
                            <div id="deptFallbackForm" class="space-y-6" style="display:none;">
                                <form action="{{ route('login.department') }}" method="POST" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label for="dept_fullname" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Full name</label>
                                        <div class="mt-1">
                                            <input id="dept_fullname" name="fullname" type="text" required value="{{ old('fullname') }}"
                                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                        </div>
                                        @error('fullname')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="dept_department_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Department</label>
                                        <div class="mt-1">
                                            <select id="dept_department_id" name="department_id" required
                                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                                <option value="">Select a department</option>
                                                @if(isset($departments) && $departments->count())
                                                    @foreach($departments as $dpt)
                                                        <option value="{{ $dpt->id }}" {{ old('department_id') == $dpt->id ? 'selected' : '' }}>{{ $dpt->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        @error('department_id')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="dept_special_code" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Department special code</label>
                                        <div class="mt-1">
                                            <input id="dept_special_code" name="special_code" type="text" required value="{{ old('special_code') }}"
                                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                        </div>
                                        @error('special_code')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <button type="submit"
                                            class="flex w-full justify-center rounded-md border border-primary-600 bg-white text-primary-600 px-3 py-2 text-sm font-semibold shadow-sm hover:bg-primary-50">
                                            Sign in with department access
                                        </button>
                                    </div>
                                </form>
                            </div>
                            
                            <div class="mt-4 text-center">
                                <button type="button" onclick="toggleDepartmentMode()" class="text-sm text-primary-600 hover:text-primary-500">
                                    Use department access code
                                </button>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Default Login Form (show both options) -->
                    <div class="mb-4 flex items-center gap-2" role="tablist" aria-label="Login method selector">
                        <button type="button" id="tab-email" onclick="toggleLoginMode('email')" class="px-4 py-2 rounded-md text-sm font-medium"
                            aria-controls="emailLoginForm" aria-selected="true">Email &amp; password</button>
                        <button type="button" id="tab-department" onclick="toggleLoginMode('department')" class="px-4 py-2 rounded-md text-sm font-medium"
                            aria-controls="deptFallbackForm" aria-selected="false">Department access</button>
                    </div>

                    <form id="emailLoginForm" class="space-y-6" action="{{ route('login') }}" method="POST" style="{{ $deptMode ? 'display:none;' : '' }}">
                        @csrf

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                Email address
                            </label>
                            <div class="mt-1">
                                <input id="email" name="email" type="email" autocomplete="email" required
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                            </div>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                Password
                            </label>
                            <div class="mt-1">
                                <input id="password" name="password" type="password" required
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                            </div>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input id="remember" name="remember" type="checkbox"
                                    class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-primary-600 focus:ring-primary-500">
                                <label for="remember" class="ml-2 block text-sm text-gray-700 dark:text-gray-200">
                                    Remember me
                                </label>
                            </div>
                        </div>

                        <div>
                            <button type="submit"
                                class="flex w-full justify-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                                Sign in
                            </button>
                        </div>
                    </form>

                    <!-- Department access fallback: allow selecting department and using special code from the same page -->
                    <div id="deptFallbackForm" class="mt-6 border-t pt-6" style="{{ $deptMode ? '' : 'display:none;' }}">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Department access</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">If your department uses a special access code, enter your full name, select your department and provide the department special code.</p>

                        <form action="{{ route('login.department') }}" method="POST" class="mt-4 space-y-4">
                            @csrf
                            <div>
                                <label for="dept_fullname" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Full name</label>
                                <div class="mt-1">
                                    <input id="dept_fullname" name="fullname" type="text" required value="{{ old('fullname') }}"
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                </div>
                                @error('fullname')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="dept_department_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Department</label>
                                <div class="mt-1">
                                    <select id="dept_department_id" name="department_id" required
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                        <option value="">Select a department</option>
                                        @if(isset($departments) && $departments->count())
                                            @foreach($departments as $dpt)
                                                <option value="{{ $dpt->id }}" {{ old('department_id') == $dpt->id ? 'selected' : '' }}>{{ $dpt->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                @error('department_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="dept_special_code" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Department special code</label>
                                <div class="mt-1">
                                    <input id="dept_special_code" name="special_code" type="text" required value="{{ old('special_code') }}"
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                </div>
                                @error('special_code')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <button type="submit"
                                    class="flex w-full justify-center rounded-md border border-primary-600 bg-white text-primary-600 px-3 py-2 text-sm font-semibold shadow-sm hover:bg-primary-50">
                                    Sign in with department access
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            @endif

            <div class="mt-6">
                <div class="relative">
                    <div class="relative flex justify-center text-sm">
                        <span class="bg-white dark:bg-gray-800 px-2 text-gray-500">
                            Don't have an account?
                            <a href="{{ route('register') }}" class="font-semibold text-primary-600 hover:text-primary-500">
                                Register
                            </a>
                        </span>
                    </div>
                </div>
            </div>
            </div>
                    <!-- Responsibilities modal -->
                    <div id="deptModal" class="fixed inset-0 z-50 hidden items-center justify-center">
                        <div class="absolute inset-0 bg-black opacity-50" onclick="hideFuncs()"></div>
                        <div class="relative max-w-xl w-full bg-white dark:bg-gray-800 rounded shadow-lg p-6 z-10">
                            <div class="flex items-start justify-between">
                                <h3 id="deptModalTitle" class="text-lg font-semibold text-gray-900 dark:text-white">Responsibilities</h3>
                                <button onclick="hideFuncs()" class="text-gray-500 hover:text-gray-700">Close</button>
                            </div>
                            <div id="deptModalBody" class="mt-4 text-sm text-gray-700 dark:text-gray-300 max-h-64 overflow-auto"></div>
                        </div>
                    </div>

                    <script>
                        // Toggle between department responsibilities modal (existing) and login mode switching
                        function toggleLoginMode(mode) {
                            const emailForm = document.getElementById('emailLoginForm');
                            const deptForm = document.getElementById('deptFallbackForm');
                            const tabEmail = document.getElementById('tab-email');
                            const tabDept = document.getElementById('tab-department');

                            if (!emailForm || !deptForm) return;

                            if (mode === 'department') {
                                emailForm.style.display = 'none';
                                deptForm.style.display = '';
                                tabEmail.classList.remove('bg-primary-600','text-white');
                                tabEmail.classList.add('bg-white','text-primary-600');
                                tabDept.classList.remove('bg-white','text-primary-600');
                                tabDept.classList.add('bg-primary-600','text-white');
                                tabEmail.setAttribute('aria-selected', 'false');
                                tabDept.setAttribute('aria-selected', 'true');
                            } else {
                                emailForm.style.display = '';
                                deptForm.style.display = 'none';
                                tabDept.classList.remove('bg-primary-600','text-white');
                                tabDept.classList.add('bg-white','text-primary-600');
                                tabEmail.classList.remove('bg-white','text-primary-600');
                                tabEmail.classList.add('bg-primary-600','text-white');
                                tabEmail.setAttribute('aria-selected', 'true');
                                tabDept.setAttribute('aria-selected', 'false');
                            }
                        }

                        // Toggle department mode for personnel login
                        function toggleDepartmentMode() {
                            const emailForm = document.getElementById('emailLoginForm');
                            const deptForm = document.getElementById('deptFallbackForm');
                            
                            if (emailForm.style.display === 'none') {
                                emailForm.style.display = '';
                                deptForm.style.display = 'none';
                            } else {
                                emailForm.style.display = 'none';
                                deptForm.style.display = '';
                            }
                        }

                        // Initialize tabs based on server-sent preference
                        document.addEventListener('DOMContentLoaded', function () {
                            const loginType = {{ request()->get('type', 'personnel') === 'facilitator' ? "'facilitator'" : (request()->get('type', 'personnel') === 'personnel' ? "'personnel'" : "'default'") }};
                            
                            // Only initialize tabs for default login mode
                            if (loginType === 'default') {
                                const initial = {{ $deptMode ? "'department'" : "'email'" }};
                                // apply Tailwind-ish classes for initial state
                                const tabEmail = document.getElementById('tab-email');
                                const tabDept = document.getElementById('tab-department');
                                if (tabEmail && tabDept) {
                                    tabEmail.classList.add('px-4','py-2','rounded-md','text-sm','font-medium');
                                    tabDept.classList.add('px-4','py-2','rounded-md','text-sm','font-medium');
                                }
                                toggleLoginMode(initial);
                            }
                        });

                        function showFuncs(id, funcs, name) {
                            const modal = document.getElementById('deptModal');
                            const title = document.getElementById('deptModalTitle');
                            const body = document.getElementById('deptModalBody');
                            title.textContent = name + ' — Responsibilities';
                            if (!funcs || !funcs.length) {
                                body.innerHTML = '<p class="text-sm text-gray-500">No responsibilities listed.</p>';
                            } else {
                                body.innerHTML = '<ul class="space-y-2">' + funcs.map(f => '<li class="flex items-start gap-2"><span class="inline-block h-2 w-2 rounded-full bg-gray-400 mt-1"></span><span>' + escapeHtml(f) + '</span></li>').join('') + '</ul>';
                            }
                            modal.classList.remove('hidden');
                            modal.classList.add('flex');
                        }

                        function hideFuncs() {
                            const modal = document.getElementById('deptModal');
                            modal.classList.add('hidden');
                            modal.classList.remove('flex');
                        }

                        function escapeHtml(unsafe) {
                            return unsafe
                              .replace(/&/g, "&amp;")
                              .replace(/</g, "&lt;")
                              .replace(/>/g, "&gt;")
                              .replace(/\"/g, "&quot;")
                              .replace(/'/g, "&#039;");
                        }
                    </script>
        </div>
    </div>
</div>
@endsection
