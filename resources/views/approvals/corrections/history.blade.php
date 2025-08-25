@if ($histories->isEmpty())
    <p class="text-gray-500">No correction requests history.</p>
@else
    <div class="relative overflow-x-auto shadow-md">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-gray-700 uppercase whitespace-nowrap bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
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
                        class="{{ $statusKey[$history->status]['color'] ?? 'bg-gray-50 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700' }} border-b border-gray-200 dark:border-gray-700">
                        <th scope="row"
                            class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $histories->firstItem() + $key }}
                        </th>
                        <td class="px-4 py-3">{{ $history->requester->full_name }}</td>
                        <td class="px-4 py-3">{{ $history->requester->levels->first()->name ?? '' }}</td>
                        <td class="px-4 py-3">{{ $history->date }}</td>
                        <td class="px-4 py-3">{{ $history->actual_in }}</td>
                        <td class="px-4 py-3">{{ $history->actual_out }}</td>
                        <td class="px-4 py-3">{{ $history->reason }}</td>
                        <td class="px-4 py-3">{{ $history->created_at }}</td>
                        <td class="px-4 py-3">
                            {{ $statusKey[$history->status] ? __($statusKey[$history->status]['status']) : __('Unknown') }}
                        </td>
                        <td class="px-4 py-3">{{ $history->approved_at }}</td>
                        <td class="px-4 py-3">{{ $history->approver->full_name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="my-4">{{ $histories->appends(['tab' => 'history'])->onEachSide(2)->links() }}</div>
@endif
