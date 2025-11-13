@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">System Settings</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Configure system-wide settings and preferences</p>
        </div>
    </div>

    <!-- Settings Form -->
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        
        <!-- Support Settings -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg mb-6">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Support Settings</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Configure user support and help system</p>
            </div>
            
            <div class="p-6 space-y-6">
                <!-- Help Email -->
                <div>
                    <label for="help_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Help Support Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" 
                           id="help_email" 
                           name="help_email" 
                           value="{{ $settings['help_email']->value ?? 'support@atommart.com' }}" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                           required>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        This email address will receive all user help requests from the "Get Help" page
                    </p>
                    @error('help_email')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- System Information -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg mb-6">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">System Information</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Current system configuration</p>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Application Name:</span>
                        <span class="ml-2 font-medium text-gray-900 dark:text-gray-100">{{ config('app.name') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Environment:</span>
                        <span class="ml-2 font-medium text-gray-900 dark:text-gray-100">{{ config('app.env') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Laravel Version:</span>
                        <span class="ml-2 font-medium text-gray-900 dark:text-gray-100">{{ app()->version() }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">PHP Version:</span>
                        <span class="ml-2 font-medium text-gray-900 dark:text-gray-100">{{ PHP_VERSION }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="flex justify-end pt-6">
            <button type="submit" 
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Save Settings
            </button>
        </div>
    </form>
</div>
@endsection
