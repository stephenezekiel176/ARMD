@extends('layouts.app')

@section('content')
<div class="flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
            Create your account
        </h2>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white dark:bg-gray-800 px-4 py-8 shadow sm:rounded-lg sm:px-10">
            <form class="space-y-6" action="{{ route('register') }}" method="POST">
                @csrf

                <div>
                    <label for="fullname" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                        Full name
                    </label>
                    <div class="mt-1">
                        <input id="fullname" name="fullname" type="text" required value="{{ old('fullname') }}"
                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                    </div>
                    @error('fullname')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                        Email address
                    </label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                    </div>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="department_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                        Department
                    </label>
                    <div class="mt-1">
                        <select id="department_id" name="department_id" required
                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                            <option value="">Select a department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('department_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="position" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                        Position
                    </label>
                    <div class="mt-1">
                        <input id="position" name="position" type="text" required value="{{ old('position') }}"
                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                    </div>
                    @error('position')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                        Role
                    </label>
                    <div class="mt-1 space-y-2">
                        <div class="flex items-center">
                            <input id="role_facilitator" name="role" type="radio" value="facilitator" {{ old('role') == 'facilitator' ? 'checked' : '' }}
                                class="h-4 w-4 border-gray-300 dark:border-gray-600 text-primary-600 focus:ring-primary-500">
                            <label for="role_facilitator" class="ml-3 block text-sm text-gray-700 dark:text-gray-200">
                                Facilitator
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input id="role_personnel" name="role" type="radio" value="personnel" {{ old('role') == 'personnel' ? 'checked' : '' }}
                                class="h-4 w-4 border-gray-300 dark:border-gray-600 text-primary-600 focus:ring-primary-500">
                            <label for="role_personnel" class="ml-3 block text-sm text-gray-700 dark:text-gray-200">
                                Personnel
                            </label>
                        </div>
                    </div>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div id="inviteCodeContainer" style="display: {{ old('role') === 'facilitator' ? '' : 'none' }};">
                    <label for="invite_code" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                        Facilitator invite code
                    </label>
                    <div class="mt-1">
                        <input id="invite_code" name="invite_code" type="text" value="{{ old('invite_code') }}"
                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                    </div>
                    @error('invite_code')
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

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                        Confirm password
                    </label>
                    <div class="mt-1">
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                    </div>
                </div>

                <div>
                    <button type="submit"
                        class="flex w-full justify-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                        Register
                    </button>
                </div>
            </form>

            <script>
                (function(){
                    const facRadio = document.getElementById('role_facilitator');
                    const perRadio = document.getElementById('role_personnel');
                    const inviteContainer = document.getElementById('inviteCodeContainer');

                    function updateInviteVisibility() {
                        if (!inviteContainer) return;
                        if (facRadio && facRadio.checked) {
                            inviteContainer.style.display = '';
                        } else {
                            inviteContainer.style.display = 'none';
                        }
                    }

                    if (facRadio) facRadio.addEventListener('change', updateInviteVisibility);
                    if (perRadio) perRadio.addEventListener('change', updateInviteVisibility);

                    document.addEventListener('DOMContentLoaded', updateInviteVisibility);
                })();
            </script>

            <div class="mt-6">
                <div class="relative">
                    <div class="relative flex justify-center text-sm">
                        <span class="bg-white dark:bg-gray-800 px-2 text-gray-500">
                            Already have an account?
                            <a href="{{ route('login') }}" class="font-semibold text-primary-600 hover:text-primary-500">
                                Sign in
                            </a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
