@extends('layouts.facilitator')

@section('content')
<div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 p-6 rounded">
    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Upload Resource</h2>

    <form action="{{ route('facilitator.resources.store') }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Title</label>
            <input name="title" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700" />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Type</label>
            <select name="type" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                <option value="video">Video (MP4)</option>
                <option value="ebook">eBook (PDF)</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">File</label>
            <input type="file" name="file" accept="video/mp4,application/pdf" required class="mt-1 block w-full" />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Department</label>
            <select name="department_id" class="mt-1 block w-full rounded-md">
                @foreach(App\Models\Department::all() as $d)
                    <option value="{{ $d->id }}">{{ $d->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="flex items-center gap-2">
                <input type="checkbox" name="is_previewable" value="1" />
                <span class="text-sm text-gray-700 dark:text-gray-200">Is Previewable</span>
            </label>
        </div>
        <div>
            <button class="rounded bg-primary-600 text-white px-4 py-2">Upload</button>
        </div>
    </form>
</div>
@endsection
