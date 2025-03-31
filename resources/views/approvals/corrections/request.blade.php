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
    <a href="{{ route('approvals.index') }}"
        class="inline-flex items-center p-2.5 rounded-md bg-blue-600 font-semibold text-sm text-white shadow-xs hover:bg-blue-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
        <svg class="size-5 text-white me-2" aria-hidden="true" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
            <path
                d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z" />
        </svg>
        Back
    </a>

    {{-- Correction request list --}}
    <div class="relative overflow-x-auto shadow-md">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-gray-700 uppercase whitespace-nowrap bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="p-4">#</th>
                    <th scope="col" class="p-4">User ID</th>
                    <th scope="col" class="p-4">Fullname</th>
                    <th scope="col" class="p-4">Level</th>
                    <th scope="col" class="p-4">Date</th>
                    <th scope="col" class="p-4">Start Time</th>
                    <th scope="col" class="p-4">End Time</th>
                    <th scope="col" class="p-4">Reason</th>
                    <th scope="col" class="p-4">Requested At</th>
                    <th scope="col" class="p-4">
                        <span class="sr-only">Approve</span>
                    </th>
                    <th scope="col" class="p-4">
                        <span class="sr-only">Reject</span>
                    </th>
                </tr>
            </thead>

            <tbody>
                @if ($corrections->count() == 0)
                    <tr>
                        <td colspan="11">No correction requests to display.</td>
                    </tr>
                @endif

                @foreach ($corrections as $key => $correction)
                    <tr
                        class="{{ $correction->active != 1 ? 'bg-red-300 hover:bg-red-400 dark:bg-red-900 dark:hover:bg-red-800' : 'bg-gray-50 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700' }} border-b border-gray-200 dark:border-gray-700">
                        <th scope="row"
                            class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $corrections->firstItem() + $key }}
                        </th>
                        <td class="px-4 py-3">{{ $correction->user_id }}</td>
                        <td class="px-4 py-3">{{ $users[$correction->user_id] }}</td>
                        <td class="px-4 py-3">{{ $levels[$correction->level_id] }}</td>
                        <td class="px-4 py-3">{{ $correction->correction_date }}</td>
                        <td class="px-4 py-3">{{ $correction->correction_start_time }}</td>
                        <td class="px-4 py-3">{{ $correction->correction_end_time }}</td>
                        <td class="px-4 py-3">{{ $correction->reason }}</td>
                        <td class="px-4 py-3">{{ $correction->created_at }}</td>
                        <td class="px-4 py-3">
                            <a href=""
                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Approve</a>
                        </td>
                        <td class="px-4 py-3">
                            <a href=""
                                class="font-medium text-yellow-600 dark:text-yellow-500 hover:underline">Reject</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="my-4">{{ $corrections->onEachSide(2)->links() }}</div>
</x-layout>
