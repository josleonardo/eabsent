<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <x-page-caption>
        Historical decisions are logged for reference in this correction request history page.
    </x-page-caption>

    <div class="flex items-center justify-between sm:justify-start sm:gap-4">
        <x-forms.button as="link" href="{{ route('approval.index') }}" icon="icon-chevron-left">
            To Approval
        </x-forms.button>

        <x-forms.button as="link" href="{{ route('correction.index') }}">
            View Pending Request
        </x-forms.button>

        <div x-data="{ exportDrop: false }" class="relative">
            <x-forms.button @click="exportDrop = !exportDrop" btnBg="bg-gray-400 dark:bg-gray-600"
                btnHover="hover:bg-gray-500" icon="icon-download" />
            <x-elements.flyout-menu :drop="[
                'name' => 'exportDrop',
                'label' => 'export-menu',
            ]" :items="[
                [
                    'label' => 'Export Excel',
                    'icon' => 'icon-file-type-xls',
                    'route' => 'correction.export.excel',
                ],
                [
                    'label' => 'Export CSV',
                    'icon' => 'icon-file-type-csv',
                    'route' => 'correction.export.csv',
                ],
            ]" />
        </div>
    </div>

    @if ($histories->isEmpty())
        <p class="text-gray-500">No correction requests history.</p>
    @else
        <div class="relative overflow-x-auto shadow-md">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead
                    class="text-gray-700 uppercase whitespace-nowrap bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="p-4">#</th>
                        <th scope="col" class="p-4">User</th>
                        <th scope="col" class="p-4">Level</th>
                        <th scope="col" class="p-4">Attendance ID</th>
                        <th scope="col" class="p-4">Attendance Date</th>
                        <th scope="col" class="p-4">Correct In</th>
                        <th scope="col" class="p-4">Correct Out</th>
                        <th scope="col" class="p-4">Correct Status</th>
                        <th scope="col" class="p-4">Description</th>
                        <th scope="col" class="p-4">Requested At</th>
                        <th scope="col" class="p-4">Requested By</th>
                        <th scope="col" class="p-4">Status</th>
                        <th scope="col" class="p-4">Processed At</th>
                        <th scope="col" class="p-4">Processed By</th>
                        <th scope="col" class="p-4">Revoke</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($histories as $key => $history)
                        <tr
                            class="{{ $history->request_status_color ?? 'bg-gray-50 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700' }} border-b border-gray-200 dark:border-gray-700">
                            <th scope="row"
                                class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $histories->firstItem() + $key }}
                            </th>
                            <td class="px-4 py-3">{{ $history->attendance->users->full_name }}</td>
                            <td class="px-4 py-3">{{ $history->attendance->users->levels->first()->name ?? '' }}</td>
                            <td class="px-4 py-3">{{ $history->attendance_id }}</td>
                            <td class="px-4 py-3">{{ $history->attendance->date }}</td>
                            <td class="px-4 py-3">{{ $history->correct_in }}</td>
                            <td class="px-4 py-3">{{ $history->correct_out }}</td>
                            <td class="px-4 py-3">{{ $history->correct_status_label }}</td>
                            <td class="px-4 py-3">{{ $history->description }}</td>
                            <td class="px-4 py-3">{{ $history->created_at }}</td>
                            <td class="px-4 py-3">{{ $history->requester->full_name }}</td>
                            <td class="px-4 py-3">{{ $history->request_status_label }}</td>
                            <td class="px-4 py-3">{{ $history->processed_at }}</td>
                            <td class="px-4 py-3">{{ $history->processer->full_name }}</td>
                            <td class="px-4 py-3">
                                @if ($history->canBeRevoked())
                                    <form action="{{ route('correction.revoke', $history) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <button type="submit"
                                            class="p-0.5 rounded text-red-500 transition hover:bg-red-500 hover:text-white focus:text-white focus:bg-red-500 focus:shadow-sm focus:outline-0">
                                            <x-icon-restore class="size-5" />
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="my-4">{{ $histories->onEachSide(2)->links() }}</div>
    @endif
</x-layout>
