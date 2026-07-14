<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <x-page-caption>
        Store and update leave types for leave management as well as deactivate leave types as needed.
    </x-page-caption>

    <x-toolbar :search="true" :create="true" :createRoute="'leave-type.create'" />

    @if ($leaveTypes->isEmpty())
        <p class="text-gray-500">No leave type to display.</p>
    @else
        <div class="relative overflow-x-auto shadow-md">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-gray-700 uppercase whitespace-nowrap bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="p-4">#</th>
                        <th scope="col" class="p-4">Type Name</th>
                        <th scope="col" class="p-4">Requires Description</th>
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
                    @foreach ($leaveTypes as $key => $leaveType)
                        <tr
                            class="{{ $activeKey[$leaveType->active]['color'] ?? 'bg-gray-50 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700' }} border-b border-gray-200 dark:border-gray-700">
                            <th scope="row"
                                class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $leaveTypes->firstItem() + $key }}
                            </th>
                            <td class="px-4 py-3">{{ $leaveType->name }}</td>
                            <td class="px-4 py-3">
                                {{ $yesNoKey[$leaveType->requires_description] ? __($yesNoKey[$leaveType->requires_description]) : __('Unknown') }}
                            </td>
                            <td class="px-4 py-3">
                                {{ $yesNoKey[$leaveType->active] ? __($yesNoKey[$leaveType->active]) : __('Unknown') }}</td>
                            <td class="px-4 py-3">{{ $leaveType->created_at }}</td>
                            <td class="px-4 py-3 text-center">{{ $leaveType->creator->full_name }}</td>
                            <td class="px-4 py-3">{{ $leaveType->updated_at }}</td>
                            <td class="px-4 py-3 text-center">{{ $leaveType->updater->full_name }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('leave-type.edit', $leaveType) }}"
                                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="my-4">{{ $leaveTypes->onEachSide(2)->links() }}</div>
    @endif
</x-layout>
