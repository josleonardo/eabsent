<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <x-page-caption>
        Historical decisions are logged for reference in this leave request history page.
    </x-page-caption>

    <div class="flex items-center justify-between sm:justify-start sm:gap-4">
        <x-forms.button as="link" href="{{ route('approval.index') }}" icon="icon-chevron-left">
            To Approval
        </x-forms.button>

        <x-forms.button as="link" href="{{ route('leave.index') }}">
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
                    'route' => 'leave.export.excel',
                ],
                [
                    'label' => 'Export CSV',
                    'icon' => 'icon-file-type-csv',
                    'route' => 'leave.export.csv',
                ],
            ]" />
        </div>
    </div>

    @if ($histories->isEmpty())
        <p class="text-gray-500">No leave requests history.</p>
    @else
        <div class="relative overflow-x-auto shadow-md">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead
                    class="text-gray-700 uppercase whitespace-nowrap bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="p-4">#</th>
                        <th scope="col" class="p-4">User</th>
                        <th scope="col" class="p-4">Level</th>
                        <th scope="col" class="p-4">Leave Type</th>
                        <th scope="col" class="p-4">Date</th>
                        <th scope="col" class="p-4">Description</th>
                        <th scope="col" class="p-4">File</th>
                        <th scope="col" class="p-4">Requested By</th>
                        <th scope="col" class="p-4">Requested At</th>
                        <th scope="col" class="p-4">Status</th>
                        <th scope="col" class="p-4">Processed By</th>
                        <th scope="col" class="p-4">Processed At</th>
                        <th scope="col" class="p-4">Updated By</th>
                        <th scope="col" class="p-4">Updated At</th>
                        <th scope="col" class="p-4">Revoke</th>
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
                            <td class="px-4 py-3">{{ $history->user->full_name }}</td>
                            <td class="px-4 py-3">{{ $history->user->levels->first()->name ?? '' }}</td>
                            <td class="px-4 py-3">{{ $history->leaveType->name }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $history->start_date->format('d M Y') }} to
                                {{ $history->end_date->format('d M Y') }}</td>
                            <td class="px-4 py-3">{{ $history->description }}</td>
                            <td class="px-4 py-3">
                                @if ($history->files->isEmpty())
                                    <span class="text-gray-500">No files attached.</span>
                                @else
                                    @foreach ($history->files as $file)
                                        <a href="{{ asset('storage/private/' . $file->path) }}" target="_blank"
                                            class="text-blue-600 hover:underline">
                                            {{ $file->original_name }}
                                        </a><br>
                                    @endforeach
                                @endif
                            </td>
                            <td class="px-4 py-3">{{ $history->requester->full_name }}</td>
                            <td class="px-4 py-3">{{ $history->created_at }}</td>
                            <td class="px-4 py-3">
                                {{ $statusKey[$history->status] ? __($statusKey[$history->status]['status']) : __('Unknown') }}
                            </td>
                            <td class="px-4 py-3">{{ $history->processer->full_name }}</td>
                            <td class="px-4 py-3">{{ $history->processed_at }}</td>
                            <td class="px-4 py-3">{{ $history->updater->full_name }}</td>
                            <td class="px-4 py-3">{{ $history->updated_at }}</td>
                            <td class="px-4 py-3">
                                @if ($history->canBeRevoked())
                                    <form action="{{ route('leave.revoke', $history) }}" method="POST">
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
