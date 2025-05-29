<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <x-page-caption>
        Manage attendance correction requests. Review the reasons and details for each correction, take action to accept
        or deny, and access a record of all history requests.
    </x-page-caption>

    {{-- Toast notification --}}
    @if (session('success'))
        <x-toast type="success" :message="session('success')" />
    @endif
    @if (session('failed'))
        <x-toast type="failed" :message="session('failed')" />
    @endif

    {{-- Back button --}}
    <x-forms.button as="link" href="{{ route('approval.index') }}" icon="icon-chevron-left">
        Back
    </x-forms.button>

    <div x-data="{ tab: '{{ $activeTab }}' }" x-init="$watch('tab', value => {
        const url = new URL(window.location.href);
        url.searchParams.set('tab', value);
        history.replaceState(null, '', url);
    })">
        {{-- Menu Tabs --}}
        <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400">
                <li class="me-2">
                    <a @click="tab = 'pending'"
                        :class="{ 'border-blue-600 text-blue-600 dark:text-blue-500 dark:border-blue-500': tab === 'pending' }"
                        class="inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group">
                        Pending
                    </a>
                </li>
                <li class="me-2">
                    <a @click="tab = 'history'"
                        :class="{ 'border-blue-600 text-blue-600 dark:text-blue-500 dark:border-blue-500': tab === 'history' }"
                        class="inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group"
                        aria-current="page">
                        History
                    </a>
                </li>
            </ul>
        </div>

        {{-- Pending correction requests --}}
        <div x-show="tab === 'pending'">
            @if ($pendings->isEmpty())
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
                            @foreach ($pendings as $key => $pending)
                                <tr
                                    class="bg-gray-50 border-b border-gray-200 hover:bg-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                                    <th scope="row"
                                        class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $pendings->firstItem() + $key }}
                                    </th>
                                    <td class="px-4 py-3">
                                        {{ $pending->requester->full_name }}
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ $pending->requester->levels->first()->name ?? '' }}
                                    </td>
                                    <td class="px-4 py-3">{{ $pending->correction_date }}</td>
                                    <td class="px-4 py-3">{{ $pending->correction_start_time }}</td>
                                    <td class="px-4 py-3">{{ $pending->correction_end_time }}</td>
                                    <td class="px-4 py-3">{{ $pending->reason }}</td>
                                    <td class="px-4 py-3">{{ $pending->created_at }}</td>
                                    <td class="px-4 py-3">
                                        <form action="{{ route('correction.update', $pending->id) }}" method="POST"
                                            class="flex gap-4">
                                            @csrf
                                            @method('PUT')

                                            <button type="submit" name="approve_status" value="1"
                                                class="p-0.5 rounded text-blue-500 transition hover:bg-blue-500 hover:text-white focus:text-white focus:bg-blue-500 focus:shadow-sm focus:outline-0">
                                                <x-icon-check class="size-5" />
                                            </button>
                                            <button type="submit" name="approve_status" value="0"
                                                class="p-0.5 rounded text-red-500 transition hover:bg-red-500 hover:text-white focus:text-white focus:bg-red-500 focus:shadow-sm focus:outline-0">
                                                <x-icon-x class="size-5" />
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="my-4">{{ $pendings->appends(['tab' => 'pending'])->onEachSide(2)->links() }}
                </div>
            @endif
        </div>

        {{-- Correction requests history --}}
        <div x-show="tab === 'history'" x-cloak>
            @if ($histories->isEmpty())
                <p class="text-gray-500">No correction requests history.</p>
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
                            @foreach ($histories as $key => $history)
                                <tr
                                    class="{{ $statusKey[$history->approve_status]['color'] ?? 'bg-gray-50 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700' }} border-b border-gray-200 dark:border-gray-700">
                                    <th scope="row"
                                        class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $histories->firstItem() + $key }}
                                    </th>
                                    <td class="px-4 py-3">
                                        {{ $history->requester->full_name }}
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ $history->requester->levels->first()->name ?? '' }}
                                    </td>
                                    <td class="px-4 py-3">{{ $history->correction_date }}</td>
                                    <td class="px-4 py-3">{{ $history->correction_start_time }}</td>
                                    <td class="px-4 py-3">{{ $history->correction_end_time }}</td>
                                    <td class="px-4 py-3">{{ $history->reason }}</td>
                                    <td class="px-4 py-3">{{ $history->created_at }}</td>
                                    <td class="px-4 py-3">{{ $statusKey[$history->approve_status] ? __($statusKey[$history->approve_status]['status']) : __('Unknown') }}</td>
                                    <td class="px-4 py-3">{{ $history->approved_at }}</td>
                                    <td class="px-4 py-3">
                                        {{ $history->approver->full_name }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="my-4">
                    {{ $histories->appends(['tab' => 'history'])->onEachSide(2)->links() }}</div>
            @endif
        </div>
    </div>
</x-layout>
