<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <x-page-caption>
        Browse a list of Flowbite products designed to help you work and play, stay organized, get answers, keep in
        touch, grow your business, and more.
    </x-page-caption>

    <div
        class="grid grid-cols-2 gap-4 sm:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 2xl:grid-cols-7">
        @foreach ($menus as $menu)
            <a href="{{ url($menu->menu_url) }}"
                class="flex col-span-1 items-center justify-center rounded-md bg-indigo-600 p-2.5 font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                {{ $menu->menu_name }}
            </a>
        @endforeach
    </div>
</x-layout>
