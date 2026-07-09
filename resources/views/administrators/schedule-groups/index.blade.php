<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <x-page-caption>
        Manage schedule information where you can create, edit, and deactivate roles as needed.
        Each schedule determines what actions and pages a user can access.
    </x-page-caption>

    <x-toolbar :search="true" :create="true" :createRoute="'schedule-group.create'" />

    @if ($scheduleGroups->isEmpty())
        <p class="text-gray-500">No schedule group to display.</p>
    @else
        <div class="relative overflow-x-auto shadow-md">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-gray-700 uppercase whitespace-nowrap bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="p-4">#</th>
                        <th scope="col" class="p-4">Group</th>
                        <th scope="col" class="p-4">Schedules</th>
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
                    @foreach ($scheduleGroups as $key => $scheduleGroup)
                        <tr
                            class="{{ $activeKey[$scheduleGroup->active]['color'] ?? 'bg-gray-50 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700' }} border-b border-gray-200 dark:border-gray-700">
                            <th scope="row"
                                class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $scheduleGroups->firstItem() + $key }}
                            </th>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $scheduleGroup->name }}</td>
                            <td class="px-4 py-3">
                                @forelse ($scheduleGroup->schedules as $schedule)
                                    <div class="mb-1 last:mb-0">
                                        {{ __($days[$schedule->day_of_week]) }}
                                        ({{ $schedule->check_in_time }} - {{ $schedule->check_out_time }})
                                    </div>
                                @empty
                                    <span class="text-gray-500 italic">
                                        {{ __('No schedules assigned') }}
                                    </span>
                                @endforelse
                            </td>
                            <td class="px-4 py-3">{{ $scheduleGroup->created_at }}</td>
                            <td class="px-4 py-3 text-center">{{ $scheduleGroup->created_by }}</td>
                            <td class="px-4 py-3">{{ $scheduleGroup->updated_at }}</td>
                            <td class="px-4 py-3 text-center">{{ $scheduleGroup->updated_by }}</td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('schedule-group.edit', $scheduleGroup) }}"
                                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="my-4">{{ $scheduleGroups->onEachSide(2)->links() }}</div>
    @endif
</x-layout>
