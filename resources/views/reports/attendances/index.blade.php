<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <x-page-caption>
        View detailed logs of user attendance. Use this page to manage attendance data, including check-in and check-out
        times, and status updates.
    </x-page-caption>

    {{-- Back button --}}
    <x-forms.button as="link" href="{{ route('report.index') }}" icon="icon-chevron-left">
        Back
    </x-forms.button>

    {{-- Table --}}
    @if ($attendances->isEmpty())
        <p class="text-gray-500">No attendances to display.</p>
    @else
        <div class="relative overflow-x-auto shadow-md">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead
                    class="text-gray-700 uppercase whitespace-nowrap bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="p-4">#</th>
                        <th scope="col" class="p-4">Fullname</th>
                        <th scope="col" class="p-4">Day</th>
                        <th scope="col" class="p-4">Date</th>
                        <th scope="col" class="p-4">Scheduled In</th>
                        <th scope="col" class="p-4">Scheduled Out</th>
                        <th scope="col" class="p-4">Actual In</th>
                        <th scope="col" class="p-4">Actual Out</th>
                        <th scope="col" class="p-4">Status</th>
                        <th scope="col" class="p-4">Updated At</th>
                        <th scope="col" class="p-4">Updated By</th>
                        <th scope="col" class="p-4">
                            <span class="sr-only">Correction</span>
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($attendances as $key => $attendance)
                        <tr
                            class="{{ $statusKey[$attendance->status]['color'] ?? 'bg-gray-50 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700' }} border-b border-gray-200 dark:border-gray-700">
                            <th scope="row"
                                class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $attendances->firstItem() + $key }}
                            </th>
                            <td class="px-4 py-3">{{ $attendance->users->full_name }}</td>
                            <td class="px-4 py-3">{{ $attendance->day_name }}</td>
                            <td class="px-4 py-3">{{ $attendance->date }}</td>
                            <td class="px-4 py-3">{{ $attendance->sched_in }}</td>
                            <td class="px-4 py-3">{{ $attendance->sched_out }}</td>
                            <td class="px-4 py-3">{{ $attendance->actual_in }}</td>
                            <td class="px-4 py-3">{{ $attendance->actual_out }}</td>
                            <td class="px-4 py-3">{{ $statusKey[$attendance->status] ? __($statusKey[$attendance->status]['status']) : __('Unknown') }}</td>
                            <td class="px-4 py-3">{{ $attendance->updated_at }}</td>
                            <td class="px-4 py-3">{{ $attendance->updated_by }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('attendance.edit', $attendance->id) }}"
                                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Correction</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="my-4">{{ $attendances->onEachSide(2)->links() }}</div>
    @endif
</x-layout>
