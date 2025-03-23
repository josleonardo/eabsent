<div class="py-4 w-full flex flex-col gap-4 sm:flex-row sm:items-start sm:gap-2">
    {{-- Searchbar --}}
    <form action="" class="flex items-center w-full sm:w-1/3">
        <div class="relative w-full">
            <div class="absolute flex items-center inset-y-0 left-0 pl-3 text-white pointer-events-none">
                <svg class="size-5 text-gray-500 dark:text-gray-400" aria-hidden="true" fill="currentColor"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                    <path
                        d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z" />
                </svg>
            </div>
            <input id="search" type="search" name="search" placeholder="Search {{ $singleName }} . . ."
                class="block w-full pl-10 p-2.5 bg-gray-50 text-sm text-gray-900 rounded-l-md focus-visible:outline-2 focus-visible:outline-indigo-600 dark:bg-gray-800 dark:placeholder-gray-400 dark:text-white">
        </div>
        <button type="submit"
            class="p-2.5 bg-gray-200 text-sm font-medium text-gray-500 rounded-r-md hover:bg-gray-300 focus-visible:outline-2 focus-visible:outline-indigo-600 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
            Search
        </button>
    </form>

    <div class="w-full flex justify-between">
        {{-- Filter button --}}
        <button type="button"
            class="flex items-center justify-center p-2.5 rounded-md bg-gray-200 text-sm font-medium text-gray-500 hover:bg-gray-300 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
            <svg class="size-5 text-gray-500 dark:text-white" aria-hidden="true" fill="currentColor"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                <path
                    d="M3.9 54.9C10.5 40.9 24.5 32 40 32l432 0c15.5 0 29.5 8.9 36.1 22.9s4.6 30.5-5.2 42.5L320 320.9 320 448c0 12.1-6.8 23.2-17.7 28.6s-23.8 4.3-33.5-3l-64-48c-8.1-6-12.8-15.5-12.8-25.6l0-79.1L9 97.3C-.7 85.4-2.8 68.8 3.9 54.9z" />
            </svg>
        </button>

        <div class="flex items-center gap-2">
            {{-- Add button --}}
            <a href="{{ route($singleName . '.create') }}"
                class="flex items-center justify-center p-2.5 rounded-md bg-green-600 font-semibold text-sm text-white shadow-xs hover:bg-green-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">
                <svg class="size-5 text-white me-2" aria-hidden="true" fill="currentColor"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                    <path
                        d="M64 80c-8.8 0-16 7.2-16 16l0 320c0 8.8 7.2 16 16 16l320 0c8.8 0 16-7.2 16-16l0-320c0-8.8-7.2-16-16-16L64 80zM0 96C0 60.7 28.7 32 64 32l320 0c35.3 0 64 28.7 64 64l0 320c0 35.3-28.7 64-64 64L64 480c-35.3 0-64-28.7-64-64L0 96zM200 344l0-64-64 0c-13.3 0-24-10.7-24-24s10.7-24 24-24l64 0 0-64c0-13.3 10.7-24 24-24s24 10.7 24 24l0 64 64 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-64 0 0 64c0 13.3-10.7 24-24 24s-24-10.7-24-24z" />
                </svg>
                Add {{ $singleName }}
            </a>

            {{-- Delete button --}}
            {{-- <form action="" method="DELETE">
                <button type="submit"
                    class="flex items-center justify-center p-2.5 rounded-md bg-red-600 font-semibold text-sm text-white shadow-xs hover:bg-red-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                    <svg class="size-5 text-white me-2" aria-hidden="true" fill="currentColor"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                        <path
                            d="M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z" />
                    </svg>
                    Delete
                </button>
            </form> --}}
        </div>
    </div>
</div>
