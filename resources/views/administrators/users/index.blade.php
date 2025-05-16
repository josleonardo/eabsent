<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <x-page-caption>
        Manage login accounts, passwords, personal details, and authentication settings for all users.
        You can create, edit, and deactivate user accounts and informations as needed.
    </x-page-caption>

    {{-- Toast notification --}}
    @if (session('success'))
        <x-toast type="success" :message="session('success')" />
    @endif
    @if (session('failed'))
        <x-toast type="failed" :message="session('failed')" />
    @endif

    {{-- Toolbar --}}
    <x-toolbar :search="true" :create="true" :createRoute="'user.create'">
        <x-slot:pageName>{{ $pageName }}</x-slot>
    </x-toolbar>

    {{-- Table --}}
    @if ($users->isEmpty())
        <p class="text-gray-500">No users to display.</p>
    @else
        <div class="relative overflow-x-auto shadow-md">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-200 text-gray-700 uppercase whitespace-nowrap dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="p-4">#</th>
                        <th scope="col" class="p-4">Email</th>
                        <th scope="col" class="p-4">Username</th>
                        <th scope="col" class="p-4">Active</th>
                        <th scope="col" class="p-4">Created At</th>
                        <th scope="col" class="p-4">Created By</th>
                        <th scope="col" class="p-4">Updated At</th>
                        <th scope="col" class="p-4">Updated By</th>
                        <th scope="col" class="p-4">
                            <span class="sr-only">Edit</span>
                        </th>
                        <th scope="col" class="p-4">
                            <span class="sr-only">Detail</span>
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($users as $key => $user)
                        <tr
                            class="{{ !$user->active ? 'bg-red-300 hover:bg-red-400 dark:bg-red-900 dark:hover:bg-red-800' : 'bg-gray-50 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700' }} border-b border-gray-200 dark:border-gray-700">
                            <th scope="row"
                                class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $users->firstItem() + $key }}
                            </th>
                            <td class="px-4 py-3">{{ $user->email }}</td>
                            <td class="px-4 py-3">{{ $user->username }}</td>
                            <td class="px-4 py-3">{{ $user->active ? 'Yes' : 'No' }}</td>
                            <td class="px-4 py-3">{{ $user->created_at }}</td>
                            <td class="px-4 py-3 text-center">{{ $user->created_by }}</td>
                            <td class="px-4 py-3">{{ $user->updated_at }}</td>
                            <td class="px-4 py-3 text-center">{{ $user->updated_by }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('user.edit', $user->id) }}"
                                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('user.show', $user->id) }}"
                                    class="font-medium text-yellow-600 dark:text-yellow-500 hover:underline">Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="my-4">{{ $users->onEachSide(2)->links() }}</div>
    @endif
</x-layout>
