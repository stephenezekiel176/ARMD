@extends('layouts.admin')
@section('header', 'Create Help Article')
@section('content')
<div class="max-w-4xl mx-auto">
    <form method="POST" action="{{ route('admin.help-center.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Title *</label>
                <input type="text" name="title" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Summary</label>
                <textarea name="summary" rows="3" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Content *</label>
                <x-rich-text-editor name="content" required />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category *</label>
                <select name="category" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200">
                    <option value="Account Settings">Account Settings</option>
                    <option value="Troubleshooting">Troubleshooting</option>
                    <option value="Getting Started">Getting Started</option>
                    <option value="Features">Features</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Article Type *</label>
                <select name="article_type" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200">
                    <option value="faq">FAQ</option>
                    <option value="guide">Guide</option>
                    <option value="tutorial">Tutorial</option>
                    <option value="troubleshooting">Troubleshooting</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status *</label>
                <select name="status" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200">
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                </select>
            </div>
            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.help-center.index') }}" class="px-6 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-md text-sm font-medium hover:bg-primary-700">Create Article</button>
            </div>
        </div>
    </form>
</div>
@endsection
