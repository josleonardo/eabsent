<x-default>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <div x-data="{ sidebarOpen: false }">
        <x-navbar />
        <x-sidebar />
    </div>

    <main class="px-4 py-4 mt-16 space-y-4 sm:ml-64">
        <x-header>
            {{ $pageName }}
        </x-header>

        {{-- Toast notification --}}
        @if (session('success'))
            <x-toast type="success" :message="session('success')" />
        @endif
        @if (session('error'))
            <x-toast type="error" :message="session('error')" />
        @endif

        <div class="mx-auto max-w-7xl space-y-4">
            <!-- Main content -->
            {{ $slot }}
        </div>
    </main>
</x-default>
