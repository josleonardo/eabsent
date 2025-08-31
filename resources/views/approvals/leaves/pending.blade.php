@if ($pendings->isEmpty())
    <p class="text-gray-500">No pending leave requests.</p>
@else
    <div class="relative overflow-x-auto shadow-md">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-gray-700 uppercase whitespace-nowrap bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="p-4">#</th>
                    <th scope="col" class="p-4">Requested By</th>
                    <th scope="col" class="p-4">Level</th>
                    <th scope="col" class="p-4">Start Date</th>
                    <th scope="col" class="p-4">End Date</th>
                    <th scope="col" class="p-4">Reason</th>
                    <th scope="col" class="p-4">File</th>
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
                        <td class="px-4 py-3">{{ $pending->requester->full_name }}</td>
                        <td class="px-4 py-3">{{ $pending->requester->levels->first()->name ?? '' }}</td>
                        <td class="px-4 py-3">{{ $pending->start_date }}</td>
                        <td class="px-4 py-3">{{ $pending->end_date }}</td>
                        <td class="px-4 py-3">{{ $pending->reason }}</td>
                        <td class="px-4 py-3">
                            @if ($pending->files->isEmpty())
                                <span class="text-gray-500">No files attached.</span>
                            @else
                                @foreach ($pending->files as $file)
                                    <a href="{{ asset('storage/private/' . $file->path) }}" target="_blank"
                                        class="text-blue-600 hover:underline">
                                        {{ $file->original_name }}
                                    </a><br>
                                @endforeach
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $pending->created_at }}</td>
                        <td class="px-4 py-3">
                            <form action="{{ route('leave.update', $pending->id) }}" method="POST" class="flex gap-4">
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

    <div class="my-4">{{ $pendings->appends(['tab' => 'pending'])->onEachSide(2)->links() }}</div>
@endif
