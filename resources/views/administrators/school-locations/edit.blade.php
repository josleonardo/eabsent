<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <section class="p-3 space-y-6 border border-gray-200 sm:p-4 dark:bg-gray-800 dark:border-gray-700">
        <x-forms.button as="link" href="{{ route('school-location.index') }}" icon="icon-chevron-left">
            Back
        </x-forms.button>

        <form action="{{ route('school-location.update', $schoolLocation) }}" method="POST" class="space-y-6 max-w-7xl">
            @csrf
            @method('PUT')

            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <x-forms.input-field label="School Location Name" name="school_location_name" id="school_location_name" placeholder="schoolLocationName"
                    :isRequired="true" :value="$schoolLocation->name" />

                <x-forms.input-field label="Key" name="key" id="key" :value="$schoolLocation->key" />

                <x-forms.input-field label="Latitude" name="latitude" id="latitude" :value="$schoolLocation->latitude" />

                <x-forms.input-field label="Longitude" name="longitude" id="longitude" :value="$schoolLocation->longitude" />

                <x-forms.input-field label="Radius (Meter)" name="radius" id="radius" type="number" :value="$schoolLocation->radius" />
            </div>

            <x-forms.toggle name="active" :checked="$schoolLocation->active" :trueLabel="__($activeKey[1]['active'])" :falseLabel="__($activeKey[0]['active'])" />

            <x-forms.button type="submit" icon="icon-edit" btnSize="w-full sm:w-40">
                Update
            </x-forms.button>
        </form>
    </section>
</x-layout>
