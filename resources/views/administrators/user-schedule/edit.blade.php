<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <section class="p-3 space-y-6 border border-gray-200 sm:p-4 dark:bg-gray-800 dark:border-gray-700">
        {{-- Back button --}}
        <x-forms.button as="link" href="{{ route('user-schedule.index') }}" icon="icon-chevron-left">
            Back
        </x-forms.button>

        {{-- Edit form --}}
        <form action="{{ route('user-schedule.update', [$user->id, $currSchedule->id]) }}" method="POST"
            class="space-y-6 max-w-7xl">
            @csrf
            @method('PUT')

            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <x-forms.input-field label="User" name="user" id="user" :isDisabled="true" :value="$user->full_name" />

                <x-forms.input-field label="Day" name="day_of_week" id="day_of_week" :isDisabled="true"
                    :value="$currSchedule->day_name" />

                <x-forms.select label="Schedule" name="schedule" id="schedule" :options="$schedules" :selected="$currSchedule->id ?? null" />
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
