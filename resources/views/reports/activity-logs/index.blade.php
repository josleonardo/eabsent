<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <x-page-caption>
        View the history of actions performed by users in the system.
    </x-page-caption>

    <x-forms.button as="link" href="{{ route('report.index') }}" icon="icon-chevron-left">
        Back
    </x-forms.button>

    @if ($logs->isEmpty())
        <p class="text-gray-500">No logs to display.</p>
    @else
        <div class="relative overflow-x-auto shadow-md">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-gray-700 uppercase whitespace-nowrap bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Date / Time</th>
                        <th class="px-4 py-3">User (Role)</th>
                        <th class="px-4 py-3">Action</th>
                        <th class="px-4 py-3">Target</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>

                @foreach ($logs as $log)
                    <tbody x-data="{ open: false }">
                        <tr
                            class="bg-gray-50 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 border-b border-gray-200 dark:border-gray-700">
                            <td class="px-4 py-3">{{ $log->created_at }}</td>
                            <td class="px-4 py-3">
                                <span class="font-medium">{{ $log->users->full_name ?? 'System' }}</span>
                                <span class="ml-1 text-xs text-gray-400">({{ $log->role ?? '-' }})</span>
                            </td>
                            <td class="px-4 py-3 capitalize">{{ $log->action }}</td>
                            <td class="px-4 py-3">{{ ucfirst($log->model_type) }} #{{ $log->model_id }}</td>
                            <td class="px-4 py-3">
                                <button @click="open = !open"
                                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                    Details
                                </button>
                            </td>
                        </tr>

                        <!-- Expandable Row -->
                        <tr x-show="open"
                            class="bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                            <td colspan="6" class="px-6 py-3 text-sm">
                                <div class="space-y-2">
                                    <div><strong>User :</strong> {{ $log->users->email ?? '-' }}</div>
                                    <div><strong>IP Address :</strong> {{ $log->ip }}</div>
                                    <div><strong>User Agent :</strong> {{ Str::limit($log->user_agent, 80) }}</div>

                                    @if (!empty($log->changes))
                                        <div class="mt-2">
                                            <strong>Changes :</strong>
                                            <ul class="list-disc list-inside space-y-1">
                                                @foreach ($log->changes['after'] ?? [] as $field => $newValue)
                                                    @php
                                                        $oldValue = $log->changes['before'][$field] ?? null;
                                                    @endphp

                                                    <li>
                                                        <span class="font-medium">{{ ucfirst($field) }}</span>:
                                                        <span class="text-red-500">{{ $oldValue }}</span>
                                                        →
                                                        <span class="text-green-600">{{ $newValue }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    </tbody>
                @endforeach
            </table>
        </div>

        <div class="my-4">{{ $logs->onEachSide(2)->links() }}</div>
    @endif
</x-layout>
