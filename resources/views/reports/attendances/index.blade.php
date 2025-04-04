<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <x-page-caption>
        Browse a list of Flowbite products designed to help you work and play, stay organized, get answers, keep in
        touch, grow your business, and more.
    </x-page-caption>

    {{-- Toast notification --}}
    @if (session('success'))
        <x-toast type="success" :message="session('success')" />
    @endif
    @if (session('failed'))
        <x-toast type="failed" :message="session('failed')" />
    @endif

    {{-- Back button --}}
    <a href="{{ route('reports.index') }}"
        class="inline-flex items-center p-2.5 rounded-md bg-blue-600 font-semibold text-sm text-white shadow-xs hover:bg-blue-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
        <svg class="size-5 text-white me-2" aria-hidden="true" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
            <path
                d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z" />
        </svg>
        Back
    </a>

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
                        <th scope="col" class="p-4">Check In</th>
                        <th scope="col" class="p-4">Check Out</th>
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
                            class="bg-gray-50 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 border-b border-gray-200 dark:border-gray-700">
                            <th scope="row"
                                class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $attendances->firstItem() + $key }}
                            </th>
                            <td class="px-4 py-3">{{ $attendance->users->profile->fullname ?? $attendance->user_id }}</td>
                            <td class="px-4 py-3">{{ $days[$attendance->day_of_week] }}
                            </td>
                            <td class="px-4 py-3">
                                {{ $attendance->date }}
                            </td>
                            <td class="px-4 py-3">{{ $attendance->sched_check_in }}</td>
                            <td class="px-4 py-3">{{ $attendance->sched_check_out }}</td>
                            <td class="px-4 py-3">{{ $attendance->real_check_in }}</td>
                            <td class="px-4 py-3">{{ $attendance->real_check_out }}</td>
                            <td class="px-4 py-3">{{ $attendance->status }}</td>
                            <td class="px-4 py-3">{{ $attendance->updated_at }}</td>
                            <td class="px-4 py-3">{{ $attendance->updated_by }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('attendances.edit', $attendance->id) }}"
                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Correction</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="my-4">{{ $attendances->onEachSide(2)->links() }}</div>
    @endif
</x-layout>
