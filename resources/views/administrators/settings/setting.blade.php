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
                        Setting Name
                    </th>
                    <th scope="col" class="p-4">
                        Key
                    </th>
                    <th scope="col" class="p-4">
                        Value 1
                    </th>
                    <th scope="col" class="p-4">
                        Value 2
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
                @foreach ($settings as $setting)
                    <tr class="{{ $setting->active == 0 ? 'bg-red-300 dark:bg-red-800 hover:bg-red-400 dark:hover:bg-red-700' : 'bg-gray-50 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700'}} border-b border-gray-200 dark:border-gray-700">
                        <th scope="row" class="p-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $setting->id }}
                        </th>
                        <td class="p-4">
                            {{ $setting->setting_name }}
                        </td>
                        <td class="p-4">
                            {{ $setting->key }}
                        </td>
                        <td class="p-4">
                            {{ $setting->value_1 }}
                        </td>
                        <td class="p-4 text-center">
                            {{ $setting->value_2 }}
                        </td>
                        <td class="p-4">
                            @if ($setting->active == 1)
                                Active
                            @else
                                Inactive
                            @endif
                        </td>
                        <td class="p-4">
                            {{ $setting->created_at }}
                        </td>
                        <td class="p-4 text-center">
                            {{ $setting->created_by }}
                        </td>
                        <td class="p-4">
                            {{ $setting->updated_at }}
                        </td>
                        <td class="p-4 text-center">
                            {{ $setting->updated_by }}
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