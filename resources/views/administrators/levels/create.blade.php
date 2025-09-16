<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <section class="p-3 space-y-6 border border-gray-200 sm:p-4 dark:bg-gray-800 dark:border-gray-700">
        <x-forms.button as="link" href="{{ route('level.index') }}" icon="icon-chevron-left">
            Back
        </x-forms.button>

        <form action="{{ route('level.store') }}" method="POST" class="space-y-6 max-w-7xl">
            @csrf

            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <x-forms.input-field label="Level Name" name="level_name" id="level_name" placeholder="Staff"
                    :isRequired="true" />
            </div>

            <x-forms.toggle name="active" :checked="true" :trueLabel="__($activeKey[1]['active'])" :falseLabel="__($activeKey[0]['active'])" />

            <x-forms.button type="submit" btnBg="bg-green-400 dark:bg-green-600" btnHover="hover:bg-green-500"
                icon="icon-square-plus" btnSize="w-full sm:w-40">
                Create
            </x-forms.button>
        </form>
    </section>
</x-layout>
