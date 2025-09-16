<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <section class="p-3 space-y-6 border border-gray-200 sm:p-4 dark:bg-gray-800 dark:border-gray-700">
        <x-forms.button as="link" href="{{ route('user-schedule.index') }}" icon="icon-chevron-left">
            Back
        </x-forms.button>

        <form action="{{ route('user-schedule.store') }}" method="POST" class="space-y-6 max-w-7xl">
            @csrf

            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <x-forms.select label="User" name="user" id="user" :isRequired="true" :options="$users"
                    display="full_name" />

                <x-forms.select label="Schedule" name="schedule" id="schedule" :isRequired="true" :options="$schedules" />
            </div>

            <x-forms.toggle name="active" :checked="true" :trueLabel="__($activeKey[1]['active'])" :falseLabel="__($activeKey[0]['active'])" />

            <x-forms.button type="submit" btnBg="bg-green-400 dark:bg-green-600" btnHover="hover:bg-green-500"
                icon="icon-square-plus" btnSize="w-full sm:w-40">
                Create
            </x-forms.button>
        </form>
    </section>
</x-layout>
