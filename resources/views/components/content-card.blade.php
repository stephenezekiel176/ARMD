@props(['header' => null, 'actions' => null])

<div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg overflow-hidden">
    @if($header)
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                    {{ $header }}
                </h3>
                @if(isset($subtitle))
                    <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>
            @if($actions)
                <div class="flex space-x-3">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif

    <div class="px-4 py-5 sm:p-6">
        {{ $slot }}
    </div>
</div>
