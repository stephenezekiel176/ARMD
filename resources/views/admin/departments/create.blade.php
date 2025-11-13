@extends('layouts.admin')

@section('content')
<div class="bg-white dark:bg-gray-800 p-6 rounded">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Create Department</h2>
        <a href="{{ route('admin.departments.index') }}" class="text-sm text-primary-600 hover:text-primary-700">Back to Departments</a>
    </div>

    <form action="{{ route('admin.departments.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Basic Information -->
        <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Basic Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Department Name *</label>
                    <input name="name" required 
                           class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 focus:ring-primary-500 focus:border-primary-500"
                           placeholder="e.g., Information Technology">
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Department Code *</label>
                    <input name="code" required maxlength="20"
                           class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 focus:ring-primary-500 focus:border-primary-500"
                           placeholder="e.g., IT, HR, FIN">
                    @error('code')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Department Slogan</label>
                <input name="slogan" maxlength="255"
                       class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 focus:ring-primary-500 focus:border-primary-500"
                       placeholder="e.g., Innovating Tomorrow, Today">
                @error('slogan')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <!-- Department Details -->
        <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Department Details</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Department Overview</label>
                    <textarea name="overview" rows="4" maxlength="2000"
                              class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 focus:ring-primary-500 focus:border-primary-500"
                              placeholder="Provide a comprehensive overview of the department's purpose and function..."></textarea>
                    @error('overview')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Core Responsibilities</label>
                    <textarea name="core_responsibilities" rows="4" maxlength="2000"
                              class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 focus:ring-primary-500 focus:border-primary-500"
                              placeholder="List the core responsibilities and key functions of this department..."></textarea>
                    @error('core_responsibilities')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Impact and Value</label>
                    <textarea name="impact" rows="3" maxlength="2000"
                              class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 focus:ring-primary-500 focus:border-primary-500"
                              placeholder="Describe the impact this department has on the organization..."></textarea>
                    @error('impact')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Department Head -->
        <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Department Head</h3>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Department Head Photo</label>
                <div class="flex items-center space-x-4">
                    <div class="flex-1">
                        <input type="file" name="head_image" accept="image/*" 
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                        <p class="mt-1 text-xs text-gray-500">Upload a photo of the department head (JPEG, PNG, JPG, GIF - Max 2MB)</p>
                        @error('head_image')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Department Icon -->
        <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Department Icon (Optional)</h3>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">SVG Icon</label>
                <textarea name="icon_svg" rows="3"
                          class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 focus:ring-primary-500 focus:border-primary-500"
                          placeholder="&lt;svg ...&gt;...&lt;/svg&gt;"></textarea>
                <p class="mt-1 text-xs text-gray-500">Paste SVG markup or upload an SVG file below</p>
            </div>
            
            <div class="mt-3">
                <input type="file" name="icon_file" accept=".svg" 
                       class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100">
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
            <a href="{{ route('admin.departments.index') }}" 
               class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100">
                Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-primary-600 text-white text-sm font-medium rounded-md hover:bg-primary-700 focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                Create Department
            </button>
        </div>
    </form>
</div>
@endsection

