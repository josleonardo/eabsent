<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <section class="p-3 space-y-6 border border-gray-200 sm:p-4 dark:bg-gray-800 dark:border-gray-700">
        {{-- Back button --}}
        <x-forms.button as="link" href="{{ route('role.index') }}" icon="icon-chevron-left">
            Back
        </x-forms.button>

        {{-- Create form --}}
        <form action="{{ route('role.store') }}" method="POST" class="space-y-6 max-w-7xl">
            @csrf

            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <x-forms.input-field label="Role Name" name="role_name" id="role_name" placeholder="Staff"
                    :isRequired="true" />
                <x-forms.input-field label="Priority" name="priority" id="priority" type="number" placeholder="99"
                    :isRequired="true" />
            </div>

            {{-- Active toggle --}}
            <x-forms.toggle name="active" :checked="true" :trueLabel="__($activeKey[1]['active'])" :falseLabel="__($activeKey[0]['active'])" />

            {{-- Submit button --}}
            <x-forms.button type="submit" btnBg="bg-green-400 dark:bg-green-600" btnHover="hover:bg-green-500" icon="icon-square-plus" btnSize="w-full sm:w-40">
                Create
            </x-forms.button>
        </form>
    </section>
</x-layout>
