<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <x-page-caption>
        Manage attendance correction requests. Review the reasons and details for each correction, take action to accept
        or deny.
    </x-page-caption>

    <div class="flex items-center justify-between sm:justify-start sm:gap-4">
        <x-forms.button as="link" href="{{ route('approval.index') }}" icon="icon-chevron-left">
            Back
        </x-forms.button>

        <x-forms.button as="link" href="{{ route('correction.history') }}">
            View Correction Hitory
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

    @if ($pendings->isEmpty())
        <p class="text-gray-500">No pending correction requests.</p>
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
                        <th scope="col" class="p-4">
                            <span class="sr-only">Action</span>
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
                            <td class="px-4 py-3">{{ $pending->attendance->users->full_name }}</td>
                            <td class="px-4 py-3">{{ $pending->attendance->users->levels->first()->name ?? '' }}</td>
                            <td class="px-4 py-3">{{ $pending->attendance_id }}</td>
                            <td class="px-4 py-3">{{ $pending->attendance->date }}</td>
                            <td class="px-4 py-3">{{ $pending->correct_in }}</td>
                            <td class="px-4 py-3">{{ $pending->correct_out }}</td>
                            <td class="px-4 py-3">{{ $pending->correct_status_label }}</td>
                            <td class="px-4 py-3">{{ $pending->description }}</td>
                            <td class="px-4 py-3">{{ $pending->created_at }}</td>
                            <td class="px-4 py-3">{{ $pending->requester->full_name }}</td>
                            <td class="px-4 py-3">
                                <form action="{{ route('correction.update', $pending->id) }}" method="POST"
                                    class="flex gap-4">
                                    @csrf
                                    @method('PUT')

                                    <button type="submit" name="action" value="approve"
                                        class="p-0.5 rounded text-blue-500 transition hover:bg-blue-500 hover:text-white focus:text-white focus:bg-blue-500 focus:shadow-sm focus:outline-0">
                                        <x-icon-check class="size-5" />
                                    </button>
                                    <button type="submit" name="action" value="reject"
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

        <div class="my-4">{{ $pendings->onEachSide(2)->links() }}</div>
    @endif

</x-layout>
