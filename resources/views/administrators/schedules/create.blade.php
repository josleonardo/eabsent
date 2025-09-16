<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <section class="p-3 space-y-6 border border-gray-200 sm:p-4 dark:bg-gray-800 dark:border-gray-700">
        <x-forms.button as="link" href="{{ route('schedule.index') }}" icon="icon-chevron-left">
            Back
        </x-forms.button>

        <form action="{{ route('schedule.store') }}" method="POST" class="space-y-6 max-w-7xl">
            @csrf

            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <x-forms.input-field label="Group" name="group" id="group" type="number" placeholder="1"
                    :isRequired="true" />

                <div class="w-full">
                    <label for="day_of_week" class="block mb-2 text-sm/6 font-medium text-gray-900 dark:text-white">
                        Day of Week
                    </label><select name="day_of_week" id="day_of_week" required
                        class="block w-full rounded-md bg-white px-2 py-2 text-base text-gray-900 border border-gray-200 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @foreach ($days as $key => $label)
                            <option value="{{ $key }}" {{ old('day_of_week') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('day_of_week')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500"> {{ $message }}</p>
                    @enderror
                </div>

                <x-forms.input-field label="Check In Time" name="check_in_time" id="check_in_time" type="time"
                    :isRequired="true" value="00:00" />

                <x-forms.input-field label="Check Out Time" name="check_out_time" id="check_out_time" type="time"
                    :isRequired="true" value="00:00" />
            </div>

            <x-forms.toggle name="active" :checked="true" :trueLabel="__($activeKey[1]['active'])" :falseLabel="__($activeKey[0]['active'])" />

            <x-forms.button type="submit" btnBg="bg-green-400 dark:bg-green-600" btnHover="hover:bg-green-500"
                icon="icon-square-plus" btnSize="w-full sm:w-40">
                Create
            </x-forms.button>
        </form>
    </section>
</x-layout>
