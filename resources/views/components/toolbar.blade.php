<div class="py-4 w-full flex flex-col gap-4 sm:flex-row sm:items-start sm:gap-2">
    {{-- Searchbar --}}
    <form action="" class="flex items-center w-full sm:w-1/3">
        <div class="relative w-full">
            <div class="absolute flex items-center inset-y-0 left-0 pl-3 text-white pointer-events-none">
                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                </svg>
            </div>
            <input id="search" type="search" name="search" placeholder="Search {{ $singleName }} . . ."
                class="block w-full pl-10 p-2.5 bg-gray-50 text-sm text-gray-900 rounded-l-md focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:bg-gray-800 dark:placeholder-gray-400 dark:text-white">
        </div>
        <button type="submit"
            class="p-2.5 bg-gray-200 text-sm font-medium text-gray-500 rounded-r-md hover:bg-gray-300 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
            Search
        </button>
    </form>

    <div class="w-full flex justify-between">
        {{-- Filter button --}}
        <button type="button"
            class="block p-2.5 rounded-md bg-gray-200 text-sm font-medium text-gray-500 hover:bg-gray-300 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
            Filter
        </button>

        <div class="flex items-center gap-2">
            {{-- Add button --}}
            <a href="{{ route($singleName . '.create') }}"
                class="block p-2.5 rounded-md bg-green-600 font-semibold text-sm text-white shadow-xs hover:bg-green-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">
                Add {{ $singleName }}
            </a>

            {{-- Delete button --}}
            {{-- <form action="" method="DELETE">
                <button type="submit"
                    class="block p-2.5 rounded-md bg-red-600 font-semibold text-sm text-white shadow-xs hover:bg-red-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                    Delete {{ $singleName }}
                </button>
            </form> --}}
        </div>
    </div>
</div>