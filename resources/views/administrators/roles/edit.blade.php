<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <section class="p-3 space-y-6 border border-gray-200 sm:p-4 dark:bg-gray-800 dark:border-gray-700">
        <x-forms.button as="link" href="{{ route('role.index') }}" icon="icon-chevron-left">
            Back
        </x-forms.button>

        <form action="{{ route('role.update', $role) }}" method="POST" class="space-y-6 max-w-7xl">
            @csrf
            @method('PUT')

            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <x-forms.input-field label="Role Name" name="role_name" id="role_name" placeholder="Staff"
                    :isRequired="true" :value="$role->name" />
                <x-forms.input-field label="Priority" name="priority" id="priority" type="number" placeholder="99"
                    :isRequired="true" :value="$role->priority" />
            </div>

            <x-forms.toggle name="active" :checked="$role->active" :trueLabel="__($activeKey[1]['active'])" :falseLabel="__($activeKey[0]['active'])" />

            <x-forms.button type="submit" icon="icon-edit" btnSize="w-full sm:w-40">
                Update
            </x-forms.button>
        </form>
    </section>
</x-layout>
