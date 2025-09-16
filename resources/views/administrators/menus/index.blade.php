<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <x-page-caption>
        Customize app's and web's navigation by managing menus and submenus.
        Control visibility and structure to improve user experience.
    </x-page-caption>

    <x-toolbar :search="true" :create="true" :createRoute="'menu.create'" />

    @if ($menus->isEmpty())
        <p class="text-gray-500">No menus to display.</p>
    @else
        <div class="relative overflow-x-auto shadow-md">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-gray-700 uppercase whitespace-nowrap bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="p-4">#</th>
                        <th scope="col" class="p-4">Menu Group</th>
                        <th scope="col" class="p-4">Menu Name</th>
                        <th scope="col" class="p-4">URL</th>
                        <th scope="col" class="p-4">Platform</th>
                        <th scope="col" class="p-4">Order</th>
                        <th scope="col" class="p-4">Icon</th>
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
                    @foreach ($menus as $key => $menu)
                        <tr
                            class="{{ $activeKey[$menu->active]['color'] ?? 'bg-gray-50 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700' }} border-b border-gray-200 dark:border-gray-700">
                            <th scope="row"
                                class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $menus->firstItem() + $key }}
                            </th>
                            <td class="px-4 py-3 text-center">{{ $menu->menu_group }}</td>
                            <td class="px-4 py-3">{{ $menu->name }}</td>
                            <td class="px-4 py-3">{{ $menu->url }}</td>
                            <td class="px-4 py-3">
                                {{ $platforms[$menu->platform] ? __($platforms[$menu->platform]) : __('Unknown') }}</td>
                            <td class="px-4 py-3">{{ $menu->order }}</td>
                            <td class="px-4 py-3">{{ $menu->icon }}</td>
                            <td class="px-4 py-3">
                                {{ $yesNoKey[$menu->active] ? __($yesNoKey[$menu->active]) : __('Unknown') }}</td>
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
    @endif
</x-layout>
