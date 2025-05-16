<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <section class="p-3 space-y-6 border border-gray-200 sm:p-4 dark:bg-gray-800 dark:border-gray-700">
        {{-- Back button --}}
        <x-forms.button as="link" href="{{ route('attendance.index') }}" icon="icon-chevron-left">
            Back
        </x-forms.button>

        {{-- Edit form --}}
        <form action="{{ route('attendance.update', $attendance->id) }}" method="POST" class="space-y-6 max-w-7xl">
            @csrf
            @method('PUT')

            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <x-forms.input-field label="User ID" name="user_id" id="user_id" :isDisabled="true"
                    :value="$attendance->user_id" />

                <x-forms.input-field label="Fullname" name="fullname" id="fullname" :isDisabled="true"
                    :value=" $attendance->users->profile->first_name && $attendance->users->profile->last_name
                    ? $attendance->users->profile->first_name . ' ' . $attendance->users->profile->last_name
                    : ''" />

                <x-forms.input-field label="Day" name="day_of_week" id="day_of_week" :isDisabled="true"
                    :value="$days[$attendance->day_of_week]" />

                <x-forms.input-field label="Date" name="date" id="date" type="date" :isDisabled="true"
                    :value="$attendance->date" />

                <x-forms.input-field label="Check In Time" name="real_check_in" id="real_check_in" type="time"
                    :isRequired="true" :value="$attendance->real_check_in" />

                <x-forms.input-field label="Check Out Time" name="real_check_out" id="real_check_out" type="time"
                    :isRequired="true" :value="$attendance->real_check_out" />

                <x-forms.input-field label="Status" name="status" id="status" :isRequired="true"
                    :value="$attendance->status" />
            </div>

            {{-- Submit button --}}
            <x-forms.button type="submit" icon="icon-edit" btnSize="w-full sm:w-40">
                Update
            </x-forms.button>
        </form>
    </section>
</x-layout>
