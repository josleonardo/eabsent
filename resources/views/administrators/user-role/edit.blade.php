<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <section class="p-3 space-y-6 border border-gray-200 sm:p-4 dark:bg-gray-800 dark:border-gray-700">
        {{-- Back button --}}
        <x-forms.button as="link" href="{{ route('user-role.index') }}" icon="icon-chevron-left">
            Back
        </x-forms.button>

        {{-- Edit form --}}
        <form action="{{ route('user-role.update', $user->id) }}" method="POST" class="space-y-6 max-w-7xl">
            @csrf
            @method('PUT')

            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <x-forms.input-field label="User ID" name="user_id" id="user_id" :isRequired="true" :isDisabled="true"
                    :value="$user->id" />

                <x-forms.input-field label="Fullname" name="fullname" id="fullname" :isRequired="true" :isDisabled="true"
                    :value="$user->profile->first_name && $user->profile->last_name
                        ? $user->profile->first_name . ' ' . $user->profile->last_name
                        : ''" />

                <x-forms.select label="Role" name="role" id="role" :options="$roles" display="name"
                    :selected="$user->roles->first()->id ?? null" />
            </div>

            {{-- Active toggle --}}
            <x-forms.toggle name="active" :checked="$user->roles->first()->pivot->active" trueLabel="Active" falseLabel="Inactive" />

            {{-- Submit button --}}
            <x-forms.button type="submit" icon="icon-edit" btnSize="w-full sm:w-40">
                Update
            </x-forms.button>
        </form>
    </section>
</x-layout>
