@extends('layouts.facilitator')

@section('content')
<div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 p-6 rounded">
    <h2 class="text-xl font-semibold">Edit Resource</h2>

    <form action="{{ route('facilitator.resources.update', $resource->id) }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
        @csrf
        @method('PATCH')
        <div>
            <label class="block text-sm font-medium">Title</label>
            <input name="title" value="{{ old('title', $resource->title) }}" required class="mt-1 block w-full rounded-md" />
        </div>
        <div>
            <label class="block text-sm font-medium">Type</label>
            <select name="type" class="mt-1 block w-full rounded-md">
                <option value="video" {{ $resource->type === 'video' ? 'selected' : '' }}>Video</option>
                <option value="ebook" {{ $resource->type === 'ebook' ? 'selected' : '' }}>E-Book</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium">File (leave blank to keep existing)</label>
            <input type="file" name="file" accept="video/mp4,application/pdf" class="mt-1 block w-full" />
        </div>
        <div>
            <label class="block text-sm font-medium">Description</label>
            <textarea name="description" class="mt-1 block w-full rounded-md">{{ old('description', $resource->description) }}</textarea>
        </div>
        <div>
            <button class="rounded bg-primary-600 text-white px-4 py-2">Save</button>
        </div>
    </form>
</div>
@endsection
