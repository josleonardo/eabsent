<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <section class="p-3 space-y-6 border border-gray-200 sm:p-4 dark:bg-gray-800 dark:border-gray-700">
        <x-forms.button as="link" href="{{ route('schedule-group.index') }}" icon="icon-chevron-left">
            Back
        </x-forms.button>

        <form action="{{ route('schedule-group.update', $scheduleGroup) }}" method="POST"
            class="space-y-6 max-w-7xl">
            @csrf
            @method('PUT')

            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <x-forms.input-field label="Group Name" name="group_name" id="group_name" :isRequired="true"
                    :value="old('group_name', $scheduleGroup->name)" />
            </div>

            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                @foreach ($schedules as $day => $daySchedules)
                    <div
                        class="rounded-md bg-white p-3 text-base border border-gray-200 dark:bg-gray-700 dark:border-gray-600">
                        <h3 class="mb-3 font-semibold text-gray-900 dark:text-white">
                            {{ __($days[$day]) }}
                        </h3>

                        <div class="space-y-2">
                            @foreach ($daySchedules as $schedule)
                                <label class="flex items-center gap-2 text-gray-900 dark:text-white">
                                    <input type="checkbox" name="schedule_ids[]" value="{{ $schedule->id }}"
                                        class="rounded" @checked(in_array($schedule->id, old('schedule_ids', $scheduleGroup->schedule_ids)))>

                                    <span>
                                        {{ $schedule->check_in_time }}
                                        -
                                        {{ $schedule->check_out_time }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <x-forms.toggle name="active" :checked="$scheduleGroup->active" :trueLabel="__($activeKey[1]['active'])" :falseLabel="__($activeKey[0]['active'])" />

            <x-forms.button type="submit" icon="icon-edit" btnSize="w-full sm:w-40">
                Update
            </x-forms.button>
        </form>
    </section>
</x-layout>
