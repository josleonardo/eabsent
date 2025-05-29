<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <x-page-caption>
        Manage schedule information where you can create, edit, and deactivate roles as needed.
        Each schedule determines what actions and pages a user can access.
    </x-page-caption>

    {{-- Toast notification --}}
    @if (session('success'))
        <x-toast type="success" :message="session('success')" />
    @endif
    @if (session('failed'))
        <x-toast type="failed" :message="session('failed')" />
    @endif

    {{-- Toolbar --}}
    <x-toolbar :search="true" :create="true" :createRoute="'user-schedule.create'">
        <x-slot:pageName>{{ $pageName }}</x-slot>
    </x-toolbar>

    {{-- Table --}}
    @if ($userSchedules->isEmpty())
        <p class="text-gray-500">No user schedule to display.</p>
    @else
        <div class="relative overflow-x-auto shadow-md">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-gray-700 uppercase whitespace-nowrap bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="p-4">#</th>
                        <th scope="col" class="p-4">User</th>
                        <th scope="col" class="p-4">Day</th>
                        <th scope="col" class="p-4">Check In</th>
                        <th scope="col" class="p-4">Check Out</th>
                        <th scope="col" class="p-4">Active</th>
                        <th scope="col" class="p-4">Created At</th>
                        <th scope="col" class="p-4">Created By</th>
                        <th scope="col" class="p-4">Updated At</th>
                        <th scope="col" class="p-4">Updated By</th>
                        <th scope="col" class="p-4">
                            <span class="sr-only">Edit</span>
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($userSchedules as $key => $item)
                        <tr
                            class="{{ $activeKey[$item->active]['color'] ?? 'bg-gray-50 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700' }} border-b border-gray-200 dark:border-gray-700">
                            <th scope="row"
                                class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $userSchedules->firstItem() + $key }}
                            </th>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $item->first_name }} {{ $item->last_name }}</td>
                            <td class="px-4 py-3">{{ __($days[$item->day_of_week]) ?? null }}</td>
                            <td class="px-4 py-3 text-center">
                                {{ $item->check_in_time }}</td>
                            <td class="px-4 py-3 text-center">
                                {{ $item->check_out_time }}</td>
                            <td class="px-4 py-3 text-center">{{ $yesNoKey[$item->active] ? __($yesNoKey[$item->active]) : __('Unknown') }}</td>
                            <td class="px-4 py-3">{{ $item->created_at }}</td>
                            <td class="px-4 py-3 text-center">{{ $item->created_by }}</td>
                            <td class="px-4 py-3">{{ $item->updated_at }}</td>
                            <td class="px-4 py-3 text-center">{{ $item->updated_by }}</td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('user-schedule.edit', [$item->user_id, $item->schedule_id]) }}"
                                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="my-4">{{ $userSchedules->onEachSide(2)->links() }}</div>
    @endif
</x-layout>
