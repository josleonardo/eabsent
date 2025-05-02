<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <x-page-caption>
        Access all records and reports data. View detailed logs of user data, compliance, and record-keeping.
    </x-page-caption>

    <div
        class="grid grid-cols-2 gap-4 sm:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 2xl:grid-cols-7">
        @foreach ($menus as $menu)
            <a href="{{ url($menu->url) }}"
                class="flex col-span-1 items-center justify-center rounded-md bg-indigo-600 p-2.5 font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                {{ $menu->name }}
            </a>
        @endforeach
        {{-- <a href="#"
            class="flex col-span-1 items-center justify-center rounded-md bg-orange-600 p-2.5 font-semibold text-white shadow-xs hover:bg-orange-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-600">
            History
        </a>

        <a href="{{ route('attendance.index') }}"
            class="flex col-span-1 items-center justify-center rounded-md bg-blue-600 p-2.5 font-semibold text-white shadow-xs hover:bg-blue-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
            Attendance
        </a> --}}
    </div>
</x-layout>
