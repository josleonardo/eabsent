<div class="w-full flex flex-col gap-4 sm:flex-row sm:gap-2">
    @if ($search)
        {{-- Search bar --}}
        <form action="" class="w-full min-w-52 sm:max-w-56 lg:max-w-2xs">
            <div class="relative">
                <input id="search" type="search" name="search" placeholder="Search . . ."
                    class="w-full bg-gray-50 placeholder:text-gray-400 text-gray-700 text-sm rounded-md pl-3 pr-28 py-2.5 shadow-sm focus-visible:outline-2 focus-visible:outline-indigo-600 dark:bg-gray-800 dark:text-white">
                <button
                    class="absolute right-1 top-1 rounded p-1.5 text-gray-500 transition hover:bg-gray-200 hover:text-gray-900 focus:text-gray-900 focus:bg-gray-200 focus:shadow-sm focus:outline-0 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:text-white dark:focus:bg-gray-700"
                    type="submit" aria-label="Search">
                    <x-icon-search class="size-5" />
                </button>
            </div>
        </form>
    @endif

    <div class="w-full flex items-center">
        {{-- Filter button --}}
        @if ($filter)
            <x-forms.button btnBg="bg-gray-200 dark:bg-gray-700" btnHover="hover:bg-gray-300 dark:hover:bg-gray-600"
                icon="icon-filter" textColor="text-gray-500 dark:text-white" />
        @endif

        <div class="flex-1"></div>

        {{-- Actions --}}
        <div class="flex items-center gap-2">
            @if ($export && !empty($exportItems))
                <div x-data="{ exportDrop: false }">
                    <x-forms.button @click="exportDrop = !exportDrop" btnBg="bg-gray-400 dark:bg-gray-600"
                        btnHover="hover:bg-gray-500" icon="icon-download" />
                    <x-elements.flyout-menu :drop="[
                        'name' => 'exportDrop',
                        'label' => 'export-menu',
                    ]" :items="$exportItems" />
                </div>
            @endif

            @if ($create && !empty($createRoute))
                <x-forms.button as="link" btnBg="bg-green-400 dark:bg-green-600" btnHover="hover:bg-green-500"
                    href="{{ route($createRoute) }}" icon="icon-square-plus" />
            @endif

            @if ($delete)
                <x-forms.button btnBg="bg-red-400 dark:bg-red-600" btnHover="hover:bg-red-500" icon="icon-trash" />
            @endif
        </div>
    </div>
</div>
