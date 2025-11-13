@extends('layouts.facilitator')

@section('content')
<div class="bg-white dark:bg-gray-800 p-6 rounded">
    <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">Resources</h1>
    <p class="mt-2 text-sm text-gray-500">Manage your videos and e-books.</p>

    <div class="flex items-center justify-between">
        <div class="mt-4 text-sm text-gray-500">Showing {{ $resources->total() }} resources</div>
        <div>
            <a href="{{ route('facilitator.resources.create') }}" class="inline-flex items-center px-3 py-2 bg-primary-600 text-white rounded">Upload</a>
        </div>
    </div>

    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
        @foreach($resources as $res)
            <div class="p-3 border rounded">
                <div class="flex items-center justify-between">
                    <div class="font-medium">{{ $res->title }}</div>
                    <span class="text-sm text-gray-500">{{ ucfirst($res->type) }}</span>
                </div>
                <p class="text-sm text-gray-500">{{ Str::limit($res->description, 120) }}</p>
                <div class="mt-3 flex items-center space-x-2">
                    <a href="{{ route('facilitator.resources.show', $res->id) }}" class="text-sm text-primary-600">View</a>
                    <a href="{{ route('facilitator.resources.edit', $res->id) }}" class="text-sm text-gray-600">Edit</a>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">{{ $resources->links() }}</div>
</div>
@endsection
