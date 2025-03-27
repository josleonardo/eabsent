@php
    $menuTypes = ['Web', 'Android'];
@endphp

<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <x-page-caption>
        Customize app's and web's navigation by managing menus and submenus.
        Control visibility and structure to improve user experience.
    </x-page-caption>

    {{-- Toast notification --}}
    @if (session('success'))
        <x-toast type="success" :message="session('success')" />
    @endif
    @if (session('failed'))
        <x-toast type="failed" :message="session('failed')" />
    @endif

    {{-- Toolbar contain search bar, filter, action buttons --}}
    <x-toolbar>
        <x-slot:pageName>{{ $pageName }}</x-slot>
        <x-slot:singleName>{{ $singleName }}</x-slot>
    </x-toolbar>

    {{-- Table --}}
    <div class="relative overflow-x-auto shadow-md">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-gray-700 uppercase whitespace-nowrap bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="p-4">#</th>
                    <th scope="col" class="p-4">Menu Name</th>
                    <th scope="col" class="p-4">Menu URL</th>
                    <th scope="col" class="p-4">Type</th>
                    <th scope="col" class="p-4">Main Menu ID</th>
                    <th scope="col" class="p-4">Icon</th>
                    <th scope="col" class="p-4">Order</th>
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
                @if ($menus->count() == 0)
                    <tr>
                        <td colspan="13">No menus to display.</td>
                    </tr>
                @endif

                @foreach ($menus as $key => $menu)
                    <tr
                        class="{{ $menu->active != 1 ? 'bg-red-300 hover:bg-red-400 dark:bg-red-900 dark:hover:bg-red-800' : 'bg-gray-50 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700' }} border-b border-gray-200 dark:border-gray-700">
                        <th scope="row"
                            class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $menus->firstItem() + $key }}
                        </th>
                        <td class="px-4 py-3">{{ $menu->menu_name }}</td>
                        <td class="px-4 py-3">{{ $menu->menu_url }}</td>
                        <td class="px-4 py-3">{{ $menuTypes[$menu->type] }}</td>
                        <td class="px-4 py-3 text-center">{{ $menu->main_menu_id }}</td>
                        <td class="px-4 py-3">{{ $menu->icon }}</td>
                        <td class="px-4 py-3">{{ $menu->order }}</td>
                        <td class="px-4 py-3">
                            @if ($menu->active == 1)
                                Active
                            @else
                                Inactive
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $menu->created_at }}</td>
                        <td class="px-4 py-3 text-center">{{ $menu->created_by }}</td>
                        <td class="px-4 py-3">{{ $menu->updated_at }}</td>
                        <td class="px-4 py-3 text-center">{{ $menu->updated_by }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('menu.edit', $menu) }}"
                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="my-4">{{ $menus->onEachSide(2)->links() }}</div>
</x-layout>
