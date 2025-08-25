<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <x-page-caption>
        View, approve, or reject employee leave requests. Each request includes detailed information, and history
        decisions are logged for reference in the request history section.
    </x-page-caption>

    {{-- Back button --}}
    <x-forms.button as="link" href="{{ route('approval.index') }}" icon="icon-chevron-left">
        Back
    </x-forms.button>

    {{-- Menu Tabs --}}
    <div x-data="{ tab: '{{ $activeTab }}' }" x-init="$watch('tab', value => {
        const url = new URL(window.location.href);
        url.searchParams.set('tab', value);
        history.replaceState(null, '', url);
        if (value === 'history' && !document.getElementById('history-container').hasChildNodes()) {
            fetch('{{ route('leave.history') }}')
                .then(res => res.text())
                .then(html => document.getElementById('history-container').innerHTML = html);
        }
    })">
        <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400">
                <li class="me-2">
                    <a @click="tab = 'pending'"
                        :class="{ 'border-blue-600 text-blue-600 dark:text-blue-500 dark:border-blue-500': tab === 'pending' }"
                        class="inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group"
                        role="tab" aria-current="page">
                        Pending
                    </a>
                </li>
                <li class="me-2">
                    <a @click="tab = 'history';
                        if (tab === 'history') {
                            fetch('{{ route('leave.history') }}')
                                .then(res => res.text())
                                .then(html => document.getElementById('history-container').innerHTML = html);
                        }"
                        :class="{ 'border-blue-600 text-blue-600 dark:text-blue-500 dark:border-blue-500': tab === 'history' }"
                        class="inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group"
                        role="tab" aria-current="page">
                        History
                    </a>
                </li>
            </ul>
        </div>

        {{-- Pending leave requests --}}
        <div x-show="tab === 'pending'">
            {{-- @if ($pendings->isEmpty())
                <p class="text-gray-500">No pending leave requests.</p>
            @else
                @include('approvals.leaves.pending', ['pendings' => $pendings])
            @endif --}}

            @include('approvals.leaves.pending', ['pendings' => $pendings])
        </div>

        {{-- Leave requests history --}}
        <div x-show="tab === 'history'" x-cloak>
            {{-- @if ($histories->isEmpty())
                <p class="text-gray-500">No leave requests history.</p>
            @else
                <div id="history-container" class="text-gray-500">Loading...</div>
            @endif --}}

            <div id="history-container" class="text-gray-500">Loading...</div>
        </div>
    </div>
</x-layout>
