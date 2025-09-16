<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <x-page-caption>
        Manage role information where you can create, edit, and deactivate roles as needed.
        Each role determines what actions and pages a user can access.
    </x-page-caption>

    <x-toolbar :search="true" :create="true" :createRoute="'role.create'" />

    @if ($roles->isEmpty())
        <p class="text-gray-500">No roles to display.</p>
    @else
        <div class="relative overflow-x-auto shadow-md">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-gray-700 uppercase whitespace-nowrap bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="p-4">#</th>
                        <th scope="col" class="p-4">Role Name</th>
                        <th scope="col" class="p-4">Priority</th>
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
                    @foreach ($roles as $key => $role)
                        <tr
                            class="{{ $activeKey[$role->active]['color'] ?? 'bg-gray-50 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700' }} border-b border-gray-200 dark:border-gray-700">
                            <th scope="row"
                                class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $roles->firstItem() + $key }}
                            </th>
                            <td class="px-4 py-3">{{ $role->name }}</td>
                            <td class="px-4 py-3">{{ $role->priority }}</td>
                            <td class="px-4 py-3">
                                {{ $yesNoKey[$role->active] ? __($yesNoKey[$role->active]) : __('Unknown') }}</td>
                            <td class="px-4 py-3">{{ $role->created_at }}</td>
                            <td class="px-4 py-3 text-center">{{ $role->created_by }}</td>
                            <td class="px-4 py-3">{{ $role->updated_at }}</td>
                            <td class="px-4 py-3 text-center">{{ $role->updated_by }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('role.edit', $role) }}"
                                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="my-4">{{ $roles->onEachSide(2)->links() }}</div>
    @endif
</x-layout>
