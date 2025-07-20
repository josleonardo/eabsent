<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <section class="p-3 space-y-6 border border-gray-200 sm:p-4 dark:bg-gray-800 dark:border-gray-700">
        {{-- Edit form --}}
        <form action="{{ route('settings.profile.update') }}" method="POST" class="space-y-6 max-w-7xl"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid gap-4 sm:grid-cols-2 sm:gap-4 lg:grid-cols-3">
                <div class="flex flex-col col-span-1">
                    {{-- Avatar component --}}
                    <label for="avatar" id="avatar"
                        class="block mb-2 text-sm/6 font-medium text-gray-900 dark:text-white">
                        Profile Picture
                    </label>
                    <span class="flex items-center justify-center">
                        <x-elements.avatar size="size-48" textSize="text-8xl" :editable="true" :user="$user" />
                    </span>
                </div>

                <div
                    class="space-y-4 col-span-1 lg:grid lg:grid-cols-2 lg:col-start-2 lg:col-end-4 lg:space-y-0 lg:gap-4">
                    <x-forms.input-field label="First Name" name="first_name" id="first_name" placeholder="John"
                        :isRequired="true" :value="$user->profile->first_name" />

                    <x-forms.input-field label="Last Name" name="last_name" id="last_name" placeholder="Doe"
                        :value="$user->profile->last_name" />

                    <x-forms.input-field label="NIK" name="nik" id="nik" placeholder="1234567890123456"
                        :isRequired="true" :value="$user->profile->nik" />

                    <x-forms.input-field label="NUPTK" name="nuptk" id="nuptk" placeholder="2368934567893653"
                        :value="$user->profile->nuptk" />

                    <x-forms.input-field label="Position" name="position" id="position" placeholder="Teacher"
                        :value="$user->profile->position" />

                    <x-forms.input-field label="Current Address" name="address" id="address"
                        placeholder="Jl. Abc 1/2, Jakarta, Indonesia" :value="$user->profile->address" />

                    <x-forms.input-field label="Phone Number" name="phone_number" id="phone_number"
                        placeholder="081212345678" :value="$user->profile->phone_number" />
                </div>
            </div>

            {{-- Submit button --}}
            <x-forms.button type="submit" icon="icon-edit" btnSize="w-full sm:w-52">
                Update Profile
            </x-forms.button>
        </form>
    </section>
</x-layout>
