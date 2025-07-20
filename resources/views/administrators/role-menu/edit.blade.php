<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <section class="p-3 space-y-6 border border-gray-200 sm:p-4 dark:bg-gray-800 dark:border-gray-700">
        {{-- Back button --}}
        <x-forms.button as="link" href="{{ route('role-menu.index') }}" icon="icon-chevron-left">
            Back
        </x-forms.button>

        {{-- Edit form --}}
        <form action="{{ route('role-menu.update', [$role->id, $currMenu->id]) }}" method="POST"
            class="space-y-6 max-w-7xl">
            @csrf
            @method('PUT')

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                {{-- Role, Platform, and Menu fields --}}
                <x-forms.input-field label="Role" name="role" id="role" :isDisabled="true" :value="$role->name" />

                <x-forms.input-field label="Platform" name="platform" id="platform" :isDisabled="true"
                    :value="$platforms[$currMenu->platform] ? __($platforms[$currMenu->platform]) : __('Unknown')" />

                <x-forms.select label="Menu" name="menu" id="menu" :options="$menus" :selected="$currMenu->id ?? null"
                    display="name" />
            </div>

            {{-- Active toggle --}}
            <x-forms.toggle name="active" :checked="$pivotData->active" :trueLabel="__($activeKey[1]['active'])" :falseLabel="__($activeKey[0]['active'])" />

            {{-- Submit button --}}
            <x-forms.button type="submit" icon="icon-edit" btnSize="w-full sm:w-40">
                Update
            </x-forms.button>
        </form>
    </section>
</x-layout>
