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

    <div x-data="{ tab: 'pending' }">
        {{-- Menu Tabs --}}
        <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400">
                <li class="me-2">
                    <a href="#pending-corrections" @click="tab = 'pending'"
                        :class="{ 'border-blue-600 text-blue-600 dark:text-blue-500 dark:border-blue-500': tab === 'pending' }"
                        class="inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group">
                        Pending
                    </a>
                </li>
                <li class="me-2">
                    <a href="#processed-corrections" @click="tab = 'processed'"
                        :class="{ 'border-blue-600 text-blue-600 dark:text-blue-500 dark:border-blue-500': tab === 'processed' }"
                        class="inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group"
                        aria-current="page">
                        Processed
                    </a>
                </li>
            </ul>
        </div>

        {{-- Pending correction requests --}}
        <div id="pending_corrections" x-show="tab === 'pending'">
            @if ($pendingCorrections->isEmpty())
                <p class="text-gray-500">No pending correction requests.</p>
            @else
                <div class="relative overflow-x-auto shadow-md">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead
                            class="text-gray-700 uppercase whitespace-nowrap bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="p-4">#</th>
                                <th scope="col" class="p-4">Requested By</th>
                                <th scope="col" class="p-4">Level</th>
                                <th scope="col" class="p-4">Date</th>
                                <th scope="col" class="p-4">Start Time</th>
                                <th scope="col" class="p-4">End Time</th>
                                <th scope="col" class="p-4">Reason</th>
                                <th scope="col" class="p-4">Requested At</th>
                                <th scope="col" class="p-4">
                                    <span class="sr-only">Approve/Reject</span>
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($pendingCorrections as $key => $pendingCorrection)
                                <tr
                                    class="bg-gray-50 border-b border-gray-200 hover:bg-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                                    <th scope="row"
                                        class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $pendingCorrections->firstItem() + $key }}
                                    </th>
                                    <td class="px-4 py-3">
                                        {{ $pendingCorrection->requester->profile->fullname ?? $pendingCorrection->created_by }}
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ $pendingCorrection->requester->levels->first()->level_name ?? '' }}
                                    </td>
                                    <td class="px-4 py-3">{{ $pendingCorrection->correction_date }}</td>
                                    <td class="px-4 py-3">{{ $pendingCorrection->correction_start_time }}</td>
                                    <td class="px-4 py-3">{{ $pendingCorrection->correction_end_time }}</td>
                                    <td class="px-4 py-3">{{ $pendingCorrection->reason }}</td>
                                    <td class="px-4 py-3">{{ $pendingCorrection->created_at }}</td>
                                    <td class="px-4 py-3">
                                        <form action="{{ route('corrections.update', $pendingCorrection->id) }}"
                                            method="POST" class="flex gap-4">
                                            @csrf
                                            @method('PUT')

                                            <button type="submit" name="approve_status" value="1"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Approve</button>
                                            <button type="submit" name="approve_status" value="0"
                                                class="font-medium text-red-600 dark:text-red-500 hover:underline">Reject</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="my-4">{{ $pendingCorrections->onEachSide(2)->links() }}</div>
            @endif
        </div>

        {{-- Processed correction requests --}}
        <div id="processed_corrections" x-show="tab === 'processed'" x-cloak>
            @if ($processedCorrections->isEmpty())
                <p class="text-gray-500">No processed correction requests.</p>
            @else
                <div class="relative overflow-x-auto shadow-md">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead
                            class="text-gray-700 uppercase whitespace-nowrap bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="p-4">#</th>
                                <th scope="col" class="p-4">Requested By</th>
                                <th scope="col" class="p-4">Level</th>
                                <th scope="col" class="p-4">Date</th>
                                <th scope="col" class="p-4">Start Time</th>
                                <th scope="col" class="p-4">End Time</th>
                                <th scope="col" class="p-4">Reason</th>
                                <th scope="col" class="p-4">Requested At</th>
                                <th scope="col" class="p-4">Status</th>
                                <th scope="col" class="p-4">Processed At</th>
                                <th scope="col" class="p-4">Processed By</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($processedCorrections as $key => $processedCorrection)
                                <tr
                                    class="{{ $processedCorrection->approve_status == 0 ? 'bg-red-300 hover:bg-red-400 dark:bg-red-900 dark:hover:bg-red-800' : 'bg-gray-50 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700' }} border-b border-gray-200 dark:border-gray-700">
                                    <th scope="row"
                                        class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $processedCorrections->firstItem() + $key }}
                                    </th>
                                    <td class="px-4 py-3">
                                        {{ $processedCorrection->requester->profile->fullname ?? $processedCorrection->created_by }}
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ $processedCorrection->requester->levels->first()->level_name ?? '' }}
                                    </td>
                                    <td class="px-4 py-3">{{ $processedCorrection->correction_date }}</td>
                                    <td class="px-4 py-3">{{ $processedCorrection->correction_start_time }}</td>
                                    <td class="px-4 py-3">{{ $processedCorrection->correction_end_time }}</td>
                                    <td class="px-4 py-3">{{ $processedCorrection->reason }}</td>
                                    <td class="px-4 py-3">{{ $processedCorrection->created_at }}</td>
                                    <td class="px-4 py-3">{{ $status[$processedCorrection->approve_status] }}</td>
                                    <td class="px-4 py-3">{{ $processedCorrection->approved_at }}</td>
                                    <td class="px-4 py-3">
                                        {{ $processedCorrection->approver->profile->fullname ?? $processedCorrection->approved_by }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="my-4">{{ $processedCorrections->onEachSide(2)->links() }}</div>
            @endif
        </div>
    </div>
</x-layout>
