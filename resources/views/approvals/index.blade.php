<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <x-page-caption>
        Review and manage all pending requests for approval. You can view request details, accept or deny submissions,
        and track processed approvals in the history tab.
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
