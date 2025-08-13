<div x-show="{{ $drop['name'] }}" @click.away="{{ $drop['name'] }} = false"
    x-transition:enter="transition ease-out duration-100 transform" x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75 transform"
    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
    class="absolute right-16 z-10 mt-2 w-fit origin-top-right list-none rounded-sm bg-white ring-1 divide-y divide-gray-100 shadow-lg ring-black/5 focus:outline-hidden dark:bg-gray-700 dark:divide-gray-600"
    role="menu" aria-orientation="vertical" aria-labelledby="{{ $drop['label'] }}-button" tabindex="-1">
    <ul class="py-1" role="none">
        @foreach ($items as $item)
            <li>
                <a href="{{ route($item['route']) }}"
                    class="flex items-center justify-start px-4 py-2 text-sm text-gray-700 cursor-pointer hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                    id="item-{{ $item['label'] }}-btn"
                    role="menuitem" tabindex="-1">
                    <x-dynamic-component :component="$item['icon']" class="inline-flex mr-2 size-4" />
                    {{ $item['label'] }}
                </a>
            </li>
        @endforeach
    </ul>
</div>
