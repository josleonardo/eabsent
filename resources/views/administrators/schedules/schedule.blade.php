@php
    $daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
@endphp

<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <x-page-caption>
        Create and manage work schedules, including check-in/out times and off days for users.
        Assign schedules to ensure proper attendance tracking.
    </x-page-caption>

    {{-- Toast notification --}}
    @if (session('success'))
        <x-toast type="success" :message="session('success')" />
    @endif
    @if (session('failed'))
        <x-toast type="failed" :message="session('failed')" />
    @endif

    {{-- Toolbar contain search bar, filter, action buttons --}}
    <x-toolbar>
        <x-slot:pageName>{{ $pageName }}</x-slot>
        <x-slot:singleName>{{ $singleName }}</x-slot>
    </x-toolbar>

    {{-- Table --}}
    <div class="relative overflow-x-auto shadow-md">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-gray-700 uppercase whitespace-nowrap bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="p-4">#</th>
                    <th scope="col" class="p-4">Schedule Name</th>
                    <th scope="col" class="p-4">Day of Week</th>
                    <th scope="col" class="p-4">Check In Time</th>
                    <th scope="col" class="p-4">Check Out Time</th>
                    <th scope="col" class="p-4">Active</th>
                    <th scope="col" class="p-4">Created At</th>
                    <th scope="col" class="p-4">Created By</th>
                    <th scope="col" class="p-4">Updated At</th>
                    <th scope="col" class="p-4">Updated By</th>
                    <th scope="col" class="p-4">
                        <span class="sr-only">Edit</span>
                    </th>
                    <th scope="col" class="p-4">
                        <span class="sr-only">Detail</span>
                    </th>
                </tr>
            </thead>

            <tbody>
                @if ($schedules->count() == 0)
                    <tr>
                        <td colspan="12">No schedules to display.</td>
                    </tr>
                @endif

                @foreach ($schedules as $key => $schedule)
                    <tr
                        class="{{ $schedule->active != 1 ? 'bg-red-300 hover:bg-red-400 dark:bg-red-900 dark:hover:bg-red-800' : 'bg-gray-50 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700' }} border-b border-gray-200 dark:border-gray-700">
                        <th scope="row"
                            class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $schedules->firstItem() + $key }}
                        </th>
                        <td class="px-4 py-3">{{ $schedule->schedule_name }}</td>
                        <td class="px-4 py-3 text-center">{{ $daysOfWeek[$schedule->day_of_week] }}</td>
                        <td class="px-4 py-3">{{ $schedule->check_in_time }}</td>
                        <td class="px-4 py-3">{{ $schedule->check_out_time }}</td>
                        <td class="px-4 py-3">
                            @if ($schedule->active == 1)
                                Active
                            @else
                                Inactive
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $schedule->created_at }}</td>
                        <td class="px-4 py-3 text-center">{{ $schedule->created_by }}</td>
                        <td class="px-4 py-3">{{ $schedule->updated_at }}</td>
                        <td class="px-4 py-3 text-center">{{ $schedule->updated_by }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="#"
                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                        </td>
                        <td class="px-4 py-3">
                            <a href="#"
                                class="font-medium text-yellow-600 dark:text-yellow-500 hover:underline">Detail</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="my-4">{{ $schedules->onEachSide(2)->links() }}</div>
</x-layout>
