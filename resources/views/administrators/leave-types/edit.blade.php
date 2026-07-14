<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <section class="p-3 space-y-6 border border-gray-200 sm:p-4 dark:bg-gray-800 dark:border-gray-700">
        <x-forms.button as="link" href="{{ route('leave-type.index') }}" icon="icon-chevron-left">
            Back
        </x-forms.button>

        <form action="{{ route('leave-type.update', $leaveType) }}" method="POST" class="space-y-6 max-w-7xl">
            @csrf
            @method('PUT')

            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <x-forms.input-field label="Type name" name="name" id="name" :isRequired="true" :value="$leaveType->name" />
            </div>
            
            <div class="grid gap-4">
                <x-forms.toggle name="requires_description" :checked="$leaveType->requires_description" :trueLabel="__($yesNoKey[1])" :falseLabel="__($yesNoKey[0])" />

                <x-forms.toggle name="active" :checked="$leaveType->active" :trueLabel="__($activeKey[1]['active'])" :falseLabel="__($activeKey[0]['active'])" />
            </div>

            <x-forms.button type="submit" btnBg="bg-green-400 dark:bg-green-600" btnHover="hover:bg-green-500"
                icon="icon-square-plus" btnSize="w-full sm:w-40">
                Create
            </x-forms.button>
        </form>
    </section>
</x-layout>
