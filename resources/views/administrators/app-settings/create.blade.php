<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <section class="p-3 space-y-6 border border-gray-200 sm:p-4 dark:bg-gray-800 dark:border-gray-700">
        {{-- Back button --}}
        <x-forms.button as="link" href="{{ route('app-setting.index') }}" icon="icon-chevron-left">
            Back
        </x-forms.button>

        {{-- Create form --}}
        <form action="{{ route('app-setting.store') }}" method="POST" class="space-y-6 max-w-7xl">
            @csrf

            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <x-forms.input-field label="Setting Name" name="setting_name" id="setting_name"
                    placeholder="settingName" :isRequired="true" />

                <x-forms.input-field label="Key" name="key" id="key" />

                <x-forms.input-field label="Value 1" name="value_1" id="value_1" />

                <x-forms.input-field label="Value 2" name="value_2" id="value_2" />
            </div>

            {{-- Active toggle --}}
            <x-forms.toggle name="active" :checked="true" trueLabel="Active" falseLabel="Inactive" />

            {{-- Submit button --}}
            <x-forms.button type="submit" btnBg="bg-green-400 dark:bg-green-600" btnHover="hover:bg-green-500" icon="icon-square-plus" btnSize="w-full sm:w-40">
                Create
            </x-forms.button>
        </form>
    </section>
</x-layout>
