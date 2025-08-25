<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <x-page-caption>
        Manage attendance correction requests. Review the reasons and details for each correction, take action to accept
        or deny, and access a record of all history requests.
    </x-page-caption>

    <x-forms.button as="link" href="{{ route('approval.index') }}" icon="icon-chevron-left">
        Back
    </x-forms.button>

    <div x-data="{ tab: '{{ $activeTab }}' }" x-init="$watch('tab', value => {
        const url = new URL(window.location.href);
        url.searchParams.set('tab', value);
        history.replaceState(null, '', url);
        if (value === 'history' && !document.getElementById('history-container').hasChildNodes()) {
            fetch('{{ route('correction.history') }}')
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
                            fetch('{{ route('correction.history') }}')
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

        <div x-show="tab === 'pending'">
            @include('approvals.corrections.pending', ['pendings' => $pendings])
        </div>

        <div x-show="tab === 'history'" x-cloak>
            <div id="history-container" class="text-gray-500">Loading...</div>
        </div>
    </div>
</x-layout>
