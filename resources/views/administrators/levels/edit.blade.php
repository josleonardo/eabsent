<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <section class="p-3 space-y-6 border border-gray-200 sm:p-4 dark:bg-gray-800 dark:border-gray-700">
        {{-- Back button --}}
        <x-forms.button as="link" href="{{ route('level.index') }}" icon="icon-chevron-left">
            Back
        </x-forms.button>

        {{-- Edit form --}}
        <form action="{{ route('level.update', $level) }}" method="POST" class="space-y-6 max-w-7xl">
            @csrf
            @method('PUT')

            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <x-forms.input-field label="Level Name" name="level_name" id="level_name" placeholder="Staff"
                    :isRequired="true" :value="$level->name" />
            </div>

            {{-- Active toggle --}}
            <x-forms.toggle name="active" :checked="$level->active" trueLabel="Active" falseLabel="Inactive" />

            {{-- Submit button --}}
            <x-forms.button type="submit" icon="icon-edit" btnSize="w-full sm:w-40">
                Update
            </x-forms.button>
        </form>
    </section>
</x-layout>
