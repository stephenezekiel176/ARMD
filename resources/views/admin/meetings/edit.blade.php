@extends('layouts.admin')

@section('header', 'Meetings')

@section('content')
<div class="max-w-4xl mx-auto">
    <form method="POST" action="{{ route('admin.meetings.update', $meetings) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 space-y-6">
            <!-- Title -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Title <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" value="{{ old('title', $meetings->title) }}" required
                       class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Excerpt -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Excerpt
                </label>
                <textarea name="excerpt" rows="3"
                          class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500">{{ old('excerpt', $meetings->excerpt) }}</textarea>
                @error('excerpt')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Content -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Content <span class="text-red-500">*</span>
                </label>
                <textarea name="content" rows="15" required
                          class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500">{{ old('content', $meetings->content) }}</textarea>
                @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Current Featured Image -->
            @if($meetings->featured_image)
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Current Featured Image
                </label>
                <img src="{{ Storage::url($meetings->featured_image) }}" alt="Featured" class="w-48 h-32 object-cover rounded-lg">
            </div>
            @endif

            <!-- Featured Image -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ $meetings->featured_image ? 'Replace Featured Image' : 'Featured Image' }}
                </label>
                <input type="file" name="featured_image" accept="image/*"
                       class="w-full text-sm text-gray-500 dark:text-gray-400
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-md file:border-0
                              file:text-sm file:font-semibold
                              file:bg-primary-50 file:text-primary-700
                              hover:file:bg-primary-100
                              dark:file:bg-primary-900 dark:file:text-primary-200">
                @error('featured_image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Current Additional Images -->
            @if($meetings->images && count($meetings->images) > 0)
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Current Additional Images
                </label>
                <div class="grid grid-cols-4 gap-4">
                    @foreach($meetings->images as $index => $image)
                    <div class="relative">
                        <img src="{{ Storage::url($image) }}" alt="Image {{ $index + 1 }}" class="w-full h-24 object-cover rounded-lg">
                        <form action="{{ route('admin.meetings.image.delete', [$meetings, $index]) }}" method="POST" class="absolute top-1 right-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Delete this image?')"
                                    class="bg-red-500 text-white p-1 rounded-full hover:bg-red-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Additional Images -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Add More Images
                </label>
                <input type="file" name="images[]" accept="image/*" multiple
                       class="w-full text-sm text-gray-500 dark:text-gray-400
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-md file:border-0
                              file:text-sm file:font-semibold
                              file:bg-primary-50 file:text-primary-700
                              hover:file:bg-primary-100
                              dark:file:bg-primary-900 dark:file:text-primary-200">
            </div>

            <!-- Videos -->
            <div x-data="{ videos: {{ json_encode(old('videos', $meetings->videos ?? [''])) }} }">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Video URLs
                </label>
                <template x-for="(video, index) in videos" :key="index">
                    <div class="flex gap-2 mb-2">
                        <input type="url" :name="'videos[' + index + ']'" x-model="videos[index]" placeholder="https://youtube.com/watch?v=..."
                               class="flex-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500">
                        <button type="button" @click="videos.splice(index, 1)" x-show="videos.length > 1"
                                class="px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">
                            Remove
                        </button>
                    </div>
                </template>
                <button type="button" @click="videos.push('')"
                        class="mt-2 px-4 py-2 bg-gray-500 text-white rounded-md text-sm hover:bg-gray-600">
                    Add Video URL
                </button>
            </div>

            <!-- Category -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Category
                </label>
                <input type="text" name="category" value="{{ old('category', $meetings->category) }}" list="categories"
                       class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500">
                <datalist id="categories">
                    @foreach($categories as $cat)
                    <option value="{{ $cat }}">
                    @endforeach
                </datalist>
                @error('category')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tags -->
            <div x-data="{ tags: {{ json_encode(old('tags', $meetings->tags ?? [])) }}, tagInput: '' }">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Tags
                </label>
                <div class="flex flex-wrap gap-2 mb-2">
                    <template x-for="(tag, index) in tags" :key="index">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-primary-100 text-primary-800 dark:bg-primary-900 dark:text-primary-200">
                            <span x-text="tag"></span>
                            <input type="hidden" :name="'tags[' + index + ']'" :value="tag">
                            <button type="button" @click="tags.splice(index, 1)" class="ml-2 text-primary-600 hover:text-primary-800">Ã—</button>
                        </span>
                    </template>
                </div>
                <div class="flex gap-2">
                    <input type="text" x-model="tagInput" @keydown.enter.prevent="if(tagInput.trim()) { tags.push(tagInput.trim()); tagInput = ''; }" 
                           placeholder="Type a tag and press Enter"
                           class="flex-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500">
                    <button type="button" @click="if(tagInput.trim()) { tags.push(tagInput.trim()); tagInput = ''; }"
                            class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700">
                        Add Tag
                    </button>
                </div>
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Status <span class="text-red-500">*</span>
                </label>
                <select name="status" required
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500">
                    <option value="draft" {{ old('status', $meetings->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status', $meetings->status) === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="archived" {{ old('status', $meetings->status) === 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Published Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Published Date
                </label>
                <input type="datetime-local" name="published_at" 
                       value="{{ old('published_at', $meetings->published_at ? $meetings->published_at->format('Y-m-d\TH:i') : '') }}"
                       class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500">
                @error('published_at')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Stats -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Statistics</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Views:</span>
                        <span class="font-medium text-gray-900 dark:text-white ml-2">{{ number_format($meetings->views) }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Created:</span>
                        <span class="font-medium text-gray-900 dark:text-white ml-2">{{ $meetings->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4 pt-4">
                <a href="{{ route('admin.meetings.index') }}" 
                   class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-primary-600 text-white rounded-md text-sm font-medium hover:bg-primary-700 transition">
                    Update meetings Post
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

