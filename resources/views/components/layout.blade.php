<x-default>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <div x-data="{ sidebarOpen: false }">
        <x-navbar />
        <x-sidebar />
    </div>

    <main class="px-4 py-4 mt-16 sm:ml-64">
        <x-header>
            {{ $pageName }}
        </x-header>
        <div class="mx-auto max-w-7xl pt-4">
            <!-- Main content -->
            {{ $slot }}
        </div>
    </main>
</x-default>