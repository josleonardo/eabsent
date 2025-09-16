<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <section class="p-3 space-y-6 border border-gray-200 sm:p-4 dark:bg-gray-800 dark:border-gray-700">
        <x-forms.button as="link" href="{{ route('attendance.index') }}" icon="icon-chevron-left">
            Back
        </x-forms.button>

        <form action="{{ route('attendance.update', $attendance->id) }}" method="POST" class="space-y-6 max-w-7xl">
            @csrf
            @method('PUT')

            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <x-forms.input-field label="User ID" name="user_id" id="user_id" :isDisabled="true" :value="$attendance->user_id" />

                <x-forms.input-field label="Full Name" name="full_name" id="full_name" :isDisabled="true"
                    :value="$attendance->users->full_name" />

                <x-forms.input-field label="Day" name="day_of_week" id="day_of_week" :isDisabled="true"
                    :value="$attendance->day_name" />

                <x-forms.input-field label="Date" name="date" id="date" type="date" :isDisabled="true"
                    :value="$attendance->date" />

                <x-forms.input-field label="Actual Check In" name="actual_in" id="actual_in" type="time"
                    :value="$attendance->formatted_actual_in" />

                <x-forms.input-field label="Actual Check Out" name="actual_out" id="actual_out" type="time"
                    :value="$attendance->formatted_actual_out" />

                <div>
                    <label for="status" class="block mb-2 text-sm/6 font-medium text-gray-900 dark:text-white">
                        Status
                    </label>
                    <select name="status" id="status" required
                        class="block w-full rounded-md bg-white px-2 py-2 text-base text-gray-900 border border-gray-200 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white"">
                        @foreach ($statusKey as $key => $label)
                            <option value="{{ $key }}"
                                {{ old('status', $attendance->status) == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500"> {{ $message }}</p>
                    @enderror
                </div>
            </div>

            <x-forms.button type="submit" icon="icon-edit" btnSize="w-full sm:w-40">
                Update
            </x-forms.button>
        </form>
    </section>
</x-layout>
