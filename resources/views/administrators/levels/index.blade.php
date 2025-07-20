<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <x-page-caption>
        Store and update school levels for user access as well as deactivate levels as needed.
    </x-page-caption>

    {{-- Toolbar --}}
    <x-toolbar :search="true" :create="true" :createRoute="'level.create'">
        <x-slot:pageName>{{ $pageName }}</x-slot>
    </x-toolbar>

    {{-- Table --}}
    @if ($levels->isEmpty())
        <p class="text-gray-500">No levels to display.</p>
    @else
        <div class="relative overflow-x-auto shadow-md">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-gray-700 uppercase whitespace-nowrap bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="p-4">#</th>
                        <th scope="col" class="p-4">Level Name</th>
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
                    @foreach ($levels as $key => $level)
                        <tr
                            class="{{ $activeKey[$level->active]['color'] ?? 'bg-gray-50 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700' }} border-b border-gray-200 dark:border-gray-700">
                            <th scope="row"
                                class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $levels->firstItem() + $key }}
                            </th>
                            <td class="px-4 py-3">{{ $level->name }}</td>
                            <td class="px-4 py-3">{{ $yesNoKey[$level->active] ? __($yesNoKey[$level->active]) : __('Unknown') }}</td>
                            <td class="px-4 py-3">{{ $level->created_at }}</td>
                            <td class="px-4 py-3 text-center">{{ $level->created_by }}</td>
                            <td class="px-4 py-3">{{ $level->updated_at }}</td>
                            <td class="px-4 py-3 text-center">{{ $level->updated_by }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('level.edit', $level) }}"
                                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="my-4">{{ $levels->onEachSide(2)->links() }}</div>
    @endif
</x-layout>
