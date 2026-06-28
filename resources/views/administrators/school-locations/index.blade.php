<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <x-page-caption>
        Configure school locations for the application. You can add, edit, or delete school locations as needed.
    </x-page-caption>

    <x-toolbar :search="true" :create="true" :createRoute="'school-location.create'" />

    @if ($schoolLocations->isEmpty())
        <p class="text-gray-500">No school locations to display.</p>
    @else
        <div class="relative overflow-x-auto shadow-md">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-gray-700 uppercase whitespace-nowrap bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="p-4">#</th>
                        <th scope="col" class="p-4">School Location Name</th>
                        <th scope="col" class="p-4">Key</th>
                        <th scope="col" class="p-4">Latitude</th>
                        <th scope="col" class="p-4">Longitude</th>
                        <th scope="col" class="p-4">Radius (Meter)</th>
                        <th scope="col" class="p-4">Active</th>
                        <th scope="col" class="p-4">Created At</th>
                        <th scope="col" class="p-4">Created By</th>
                        <th scope="col" class="p-4">Updated At</th>
                        <th scope="col" class="p-4">Updated By</th>
                        <th scope="col" class="p-4">
                            <span class="sr-only">Edit</span>
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($schoolLocations as $key => $schoolLocation)
                        <tr
                            class="{{ $activeKey[$schoolLocation->active]['color'] ?? 'bg-gray-50 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700' }} border-b border-gray-200 dark:border-gray-700">
                            <th scope="row"
                                class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $schoolLocations->firstItem() + $key }}
                            </th>
                            <td class="px-4 py-3">{{ $schoolLocation->name }}</td>
                            <td class="px-4 py-3">{{ $schoolLocation->key }}</td>
                            <td class="px-4 py-3">{{ $schoolLocation->latitude }}</td>
                            <td class="px-4 py-3">{{ $schoolLocation->longitude }}</td>
                            <td class="px-4 py-3">{{ $schoolLocation->radius }}</td>
                            <td class="px-4 py-3">
                                {{ $yesNoKey[$schoolLocation->active] ? __($yesNoKey[$schoolLocation->active]) : __('Unknown') }}
                            </td>
                            <td class="px-4 py-3">{{ $schoolLocation->created_at }}</td>
                            <td class="px-4 py-3 text-center">{{ $schoolLocation->created_by }}</td>
                            <td class="px-4 py-3">{{ $schoolLocation->updated_at }}</td>
                            <td class="px-4 py-3 text-center">{{ $schoolLocation->updated_by }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('school-location.edit', $schoolLocation) }}"
                                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="my-4">{{ $schoolLocations->onEachSide(2)->links() }}</div>
    @endif
</x-layout>
