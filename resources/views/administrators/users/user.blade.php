<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <x-page-caption>
        Manage login accounts, passwords, personal details, and authentication settings for all users. 
        You can create, edit, and deactivate user accounts and informations as needed.
    </x-page-caption>

    <div class="relative overflow-x-auto shadow-md">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">

            <thead class="text-gray-700 uppercase whitespace-nowrap bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="p-4">
                        ID
                    </th>
                    <th scope="col" class="p-4">
                        Username
                    </th>
                    <th scope="col" class="p-4">
                        Email
                    </th>
                    <th scope="col" class="p-4">
                        Active
                    </th>
                    <th scope="col" class="p-4">
                        Created At
                    </th>
                    <th scope="col" class="p-4">
                        Created By
                    </th>
                    <th scope="col" class="p-4">
                        Updated At
                    </th>
                    <th scope="col" class="p-4">
                        Updated By
                    </th>
                    <th scope="col" class="p-4">
                        <span class="sr-only">Edit</span>
                    </th>
                </tr>
            </thead>

            <tbody>
                @foreach ($users as $user)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row" class="p-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $user->id }}
                        </th>
                        <td class="p-4">
                            {{ $user->username }}
                        </td>
                        <td class="p-4">
                            {{ $user->email }}
                        </td>
                        <td class="p-4">
                            @if ($user->active == 1)
                                Active
                            @else
                                Inactive
                            @endif
                        </td>
                        <td class="p-4">
                            {{ $user->created_at }}
                        </td>
                        <td class="p-4 text-center">
                            {{ $user->created_by }}
                        </td>
                        <td class="p-4">
                            {{ $user->updated_at }}
                        </td>
                        <td class="p-4 text-center">
                            {{ $user->updated_by }}
                        </td>
                        <td class="p-4 text-right">
                            <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</x-layout>