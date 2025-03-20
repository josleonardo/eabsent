<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <section class="bg-gray-50 border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <form action="#" class="p-3 max-w-5xl sm:p-4">
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <div class="w-full">
                    <label for="levelName" class="block mb-2 text-sm/6 font-medium text-gray-900 dark:text-white">
                        Level Name
                    </label>
                    <input type="text" name="levelName" id="levelName" required value="{{ old('levelName') }}"
                        class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
            </div>

            <label class="mt-4 inline-flex items-center cursor-pointer">
                <input type="checkbox" value="" class="sr-only peer" checked>
                <div
                    class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600 dark:peer-checked:bg-blue-600">
                </div>
                <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Active</span>
            </label>

            <button type="submit"
                class="flex w-40 justify-center mt-6 rounded-md bg-indigo-600 px-3 py-2 font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Add new level
            </button>
        </form>
    </section>
</x-layout>
