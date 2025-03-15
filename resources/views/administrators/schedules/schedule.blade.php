@php
    $daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
@endphp

<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <div class="p-5 bg-white dark:bg-gray-800">
        <h1 class="text-lg font-semibold text-left rtl:text-right text-gray-900 dark:text-white">
            Table {{ $pageName }}
        </h1>
        <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">
            Browse a list of Flowbite products designed to help you work and play, stay organized, get answers, keep in touch, grow your business, and more.
        </p>
    </div>

    <div class="relative overflow-x-auto shadow-md">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">

            <thead class="text-gray-700 uppercase whitespace-nowrap bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="p-4">
                        ID
                    </th>
                    <th scope="col" class="p-4">
                        Schedule Name
                    </th>
                    <th scope="col" class="p-4">
                        Day of Week
                    </th>
                    <th scope="col" class="p-4">
                        Check In Time
                    </th>
                    <th scope="col" class="p-4">
                        Check Out Time
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
                @foreach ($schedules as $schedule)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row" class="p-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $schedule->id }}
                        </th>
                        <td class="p-4">
                            {{ $schedule->schedule_name }}
                        </td>
                        <td class="p-4 text-center">
                            {{ $daysOfWeek[$schedule->day_of_week] }}
                        </td>
                        <td class="p-4">
                            {{ $schedule->check_in_time }}
                        </td>
                        <td class="p-4">
                            {{ $schedule->check_out_time }}
                        </td>
                        <td class="p-4">
                            @if ($schedule->active == 1)
                                Active
                            @else
                                Inactive
                            @endif
                        </td>
                        <td class="p-4">
                            {{ $schedule->created_at }}
                        </td>
                        <td class="p-4 text-center">
                            {{ $schedule->created_by }}
                        </td>
                        <td class="p-4">
                            {{ $schedule->updated_at }}
                        </td>
                        <td class="p-4 text-center">
                            {{ $schedule->updated_by }}
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