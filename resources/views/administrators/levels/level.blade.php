<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <div class="p-5 bg-gray-50 dark:bg-gray-800">
        <h1 class="text-lg font-semibold text-left rtl:text-right text-gray-900 dark:text-white">
            Table {{ $pageName }}
        </h1>
        <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">
            Browse a list of Flowbite products designed to help you work and play, stay organized, get answers, keep in touch, grow your business, and more.
        </p>
    </div>

    <div class="relative overflow-x-auto shadow-md">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">

            <thead class="text-gray-700 uppercase whitespace-nowrap bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="p-4">
                        ID
                    </th>
                    <th scope="col" class="p-4">
                        Level Name
                    </th>
                    <th scope="col" class="p-4">
                        Active
                    </th>
                    <th scope="col" class="p-4">
                        Created At
                    </th>
                    <th scope="col" class="p-4">
                        Created By
                    </th>
                    <th scope="col" class="p-4">
                        Updated At
                    </th>
                    <th scope="col" class="p-4">
                        Updated By
                    </th>
                    <th scope="col" class="p-4">
                        <span class="sr-only">Edit</span>
                    </th>
                </tr>
            </thead>

            <tbody>
                @foreach ($levels as $level)
                    <tr class="{{ $level->active == 0 ? 'bg-red-300 dark:bg-red-800 hover:bg-red-400 dark:hover:bg-red-700' : 'bg-gray-50 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700'}} border-b border-gray-200 dark:border-gray-700">
                        <th scope="row" class="p-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $level->id }}
                        </th>
                        <td class="p-4">
                            {{ $level->level_name }}
                        </td>
                        <td class="p-4">
                            @if ($level->active == 1)
                                Active
                            @else
                                Inactive
                            @endif
                        </td>
                        <td class="p-4">
                            {{ $level->created_at }}
                        </td>
                        <td class="p-4 text-center">
                            {{ $level->created_by }}
                        </td>
                        <td class="p-4">
                            {{ $level->updated_at }}
                        </td>
                        <td class="p-4 text-center">
                            {{ $level->updated_by }}
                        </td>
                        <td class="p-4 text-right">
                            <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</x-layout>