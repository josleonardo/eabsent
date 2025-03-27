<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <x-page-caption>
        Configure system-wide settings to customize the application's behavior.
        Adjust preferences, permissions, and other key options.
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
                    <th scope="col" class="p-4">Setting Name</th>
                    <th scope="col" class="p-4">Key</th>
                    <th scope="col" class="p-4">Value 1</th>
                    <th scope="col" class="p-4">Value 2</th>
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
                @if ($settings->count() == 0)
                    <tr>
                        <td colspan="11">No settings to display.</td>
                    </tr>
                @endif

                @foreach ($settings as $key => $setting)
                    <tr
                        class="{{ $setting->active != 1 ? 'bg-red-300 hover:bg-red-400 dark:bg-red-900 dark:hover:bg-red-800' : 'bg-gray-50 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700' }} border-b border-gray-200 dark:border-gray-700">
                        <th scope="row"
                            class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $settings->firstItem() + $key }}
                        </th>
                        <td class="px-4 py-3">{{ $setting->setting_name }}</td>
                        <td class="px-4 py-3">{{ $setting->key }}</td>
                        <td class="px-4 py-3">{{ $setting->value_1 }}</td>
                        <td class="px-4 py-3">{{ $setting->value_2 }}</td>
                        <td class="px-4 py-3">
                            @if ($setting->active == 1)
                                Active
                            @else
                                Inactive
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $setting->created_at }}</td>
                        <td class="px-4 py-3 text-center">{{ $setting->created_by }}</td>
                        <td class="px-4 py-3">{{ $setting->updated_at }}</td>
                        <td class="px-4 py-3 text-center">{{ $setting->updated_by }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('setting.edit', $setting) }}"
                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="my-4">{{ $settings->onEachSide(2)->links() }}</div>
</x-layout>
