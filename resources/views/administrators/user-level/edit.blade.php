<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <section class="p-3 space-y-6 border border-gray-200 sm:p-4 dark:bg-gray-800 dark:border-gray-700">
        <x-forms.button as="link" href="{{ route('user-level.index') }}" icon="icon-chevron-left">
            Back
        </x-forms.button>

        <form action="{{ route('user-level.update', $user->id) }}" method="POST" class="space-y-6 max-w-7xl">
            @csrf
            @method('PUT')

            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <x-forms.input-field label="User ID" name="user_id" id="user_id" :isRequired="true" :isDisabled="true"
                    :value="$user->id" />

                <x-forms.input-field label="Full Name" name="full_name" id="full_name" :isRequired="true"
                    :isDisabled="true" :value="$user->full_name" />

                <x-forms.select label="Level" name="level" id="level" :options="$levels" display="name"
                    :selected="$user->levels->first()->id ?? null" />
            </div>

            <x-forms.toggle name="active" :checked="$user->levels->first()->pivot->active" :trueLabel="__($activeKey[1]['active'])" :falseLabel="__($activeKey[0]['active'])" />

            <x-forms.button type="submit" icon="icon-edit" btnSize="w-full sm:w-40">
                Update
            </x-forms.button>
        </form>
    </section>
</x-layout>
