<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <x-page-caption>
        Review and manage all pending requests for approval. You can view request details, accept or deny submissions, and track processed approvals in the history tab.
    </x-page-caption>

    <div
        class="grid grid-cols-2 gap-4 sm:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 2xl:grid-cols-7">
        <a href="{{ route('leaves.index') }}"
            class="flex col-span-1 items-center justify-center rounded-md bg-orange-600 p-2.5 font-semibold text-white shadow-xs hover:bg-orange-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-600">
            Leave
        </a>

        <a href="{{ route('corrections.index') }}"
            class="flex col-span-1 items-center justify-center rounded-md bg-blue-600 p-2.5 font-semibold text-white shadow-xs hover:bg-blue-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
            Correction
        </a>
    </div>
</x-layout>
