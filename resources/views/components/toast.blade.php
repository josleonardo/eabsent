@props(['type' => 'success', 'message' => ''])

@if ($message)
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition.opacity.duration.300ms
        id="toast_{{ $type }}"
        class="fixed top-10/12 right-1/2 translate-1/2 z-40 flex items-center w-full max-w-xs p-2 mb-4 text-gray-500 bg-white rounded-lg border border-gray-400 shadow-sm dark:text-gray-400 dark:bg-gray-800 dark:border-gray-600 sm:top-24 sm:right-4 sm:translate-0"
        role="alert">
        <div
            class="inline-flex items-center justify-center shrink-0 size-8 rounded-lg {{ $type == 'success' ? 'bg-green-100 text-green-500 dark:bg-green-800' : 'bg-red-100 text-red-500 dark:bg-red-800' }}">

            @if ($type == 'success')
                <x-icon-check class="size-5" />
            @else
                <x-icon-alert-triangle class="size-5" />
            @endif

            <span class="sr-only">Check icon</span>
        </div>

        <div class="ms-3 text-sm">{{ $message }}</div>

        <button type="button"
            class="inline-flex items-center justify-center size-8 ms-auto p-1.5 bg-white text-gray-500 rounded-lg focus:ring-2 focus:ring-gray-300 hover:bg-gray-100 hover:text-gray-800 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white"
            @click="show = false" aria-label="Close">
            <span class="sr-only">Close</span>
            <x-icon-x class="size-5" />
        </button>
    </div>
@endif
