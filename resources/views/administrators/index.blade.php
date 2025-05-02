<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <x-page-caption>
        Central hub for system management. Use this page to manage user accounts, assign roles and levels, configure schedules, organize menus, and update default application settings.
    </x-page-caption>

    <div
        class="grid grid-cols-2 gap-4 sm:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 2xl:grid-cols-7">
        @foreach ($menus as $menu)
            <a href="{{ url($menu->url) }}"
                class="flex col-span-1 items-center justify-center rounded-md bg-indigo-600 p-2.5 font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                {{ $menu->name }}
            </a>
        @endforeach
    </div>
</x-layout>
