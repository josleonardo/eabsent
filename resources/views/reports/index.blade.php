<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <x-page-caption>
        Access all records and reports data. View detailed logs of user data, compliance, and record-keeping.
    </x-page-caption>

    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 2xl:grid-cols-7">
        @foreach ($menus as $menu)
            <x-forms.button as="link" href="{{ url($menu->url) }}" btnSize="col-span-1" btnBg="{{ $menu->color }}"
                btnHover="{{ $menu->hover }}" icon="{{ $menu->icon }}">
                {{ $menu->name }}
            </x-forms.button>
        @endforeach
    </div>
</x-layout>
