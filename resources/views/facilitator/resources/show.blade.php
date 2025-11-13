@extends('layouts.facilitator')

@section('content')
<div x-data="{ tab: 'details' }" class="bg-white dark:bg-gray-800 p-6 rounded">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">{{ $resource->title }}</h1>
        <div class="space-x-2">
            <button @click="tab = 'details'"
                    :class="{ 'bg-primary-600 text-white': tab === 'details', 'bg-gray-200 text-gray-700': tab !== 'details' }"
                    class="px-3 py-2 rounded transition-colors duration-150 ease-in-out">
                Details
            </button>
            @if($resource->file_path)
                <button @click="tab = 'preview'"
                        :class="{ 'bg-primary-600 text-white': tab === 'preview', 'bg-gray-200 text-gray-700': tab !== 'preview' }"
                        class="px-3 py-2 rounded transition-colors duration-150 ease-in-out">
                    Preview
                </button>
            @endif
        </div>
    </div>

    <div x-show="tab === 'details'">
        <p class="text-sm text-gray-500">Type: {{ ucfirst($resource->type) }}</p>
        <p class="mt-2 text-sm text-gray-500">{{ $resource->description }}</p>

        <div class="mt-4 space-x-2">
            <a href="{{ route('facilitator.resources.edit', $resource->id) }}"
               class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-150 ease-in-out">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
            <form action="{{ route('facilitator.resources.destroy', $resource->id) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button class="inline-flex items-center px-4 py-2 border border-gray-300 rounded text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Delete
                </button>
            </form>
            @if($resource->file_path)
                <a href="{{ asset('storage/' . $resource->file_path) }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-150 ease-in-out"
                   target="_blank">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Download
                </a>
            @endif
        </div>
    </div>

    @if($resource->file_path)
        <div x-show="tab === 'preview'" class="mt-4">
            @php
                $fileExtension = pathinfo($resource->file_path, PATHINFO_EXTENSION);
                $isVideo = in_array($fileExtension, ['mp4', 'webm', 'ogg']);
                $isPDF = $fileExtension === 'pdf';
            @endphp

            @if($isVideo)
                <div class="aspect-w-16 aspect-h-9">
                    <video class="w-full rounded shadow" controls>
                        <source src="{{ asset('storage/' . $resource->file_path) }}" type="video/{{ $fileExtension }}">
                        Your browser does not support the video tag.
                    </video>
                </div>
            @elseif($isPDF)
                <div class="w-full h-[800px]">
                    <iframe src="{{ asset('storage/' . $resource->file_path) }}"
                            class="w-full h-full rounded shadow"
                            type="application/pdf">
                    </iframe>
                </div>
            @else
                <div class="bg-gray-100 p-4 rounded text-center">
                    <p class="text-gray-600">Preview not available for this file type.</p>
                    <a href="{{ asset('storage/' . $resource->file_path) }}"
                       class="inline-flex items-center px-4 py-2 mt-2 bg-primary-600 text-white rounded hover:bg-primary-700"
                       target="_blank">
                        Download to View
                    </a>
                </div>
            @endif
        </div>
    @endif
</div>

@push('styles')
<style>
    .aspect-w-16 {
        position: relative;
        padding-bottom: 56.25%;
    }
    .aspect-h-9 {
        position: absolute;
        height: 100%;
        width: 100%;
        left: 0;
        top: 0;
    }
</style>
@endpush
@endsection
