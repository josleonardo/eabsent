<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <section class="p-3 space-y-6 border border-gray-200 sm:p-4 dark:bg-gray-800 dark:border-gray-700">
        <x-forms.button as="link" href="{{ route('user.index') }}" icon="icon-chevron-left">
            Back
        </x-forms.button>

        <form action="{{ route('user.store') }}" method="POST" class="space-y-6 max-w-7xl" enctype="multipart/form-data">
            @csrf

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div class="flex flex-col col-span-1">
                    {{-- Avatar component --}}
                    <label for="avatar" id="avatar"
                        class="block mb-2 text-sm/6 font-medium text-gray-900 dark:text-white">
                        Profile Picture
                    </label>
                    <span class="flex items-center justify-center">
                        <x-elements.avatar size="size-48" :editable="true" />
                    </span>
                </div>

                <div
                    class="space-y-4 col-span-1 lg:grid lg:grid-cols-2 lg:col-start-2 lg:col-end-4 lg:space-y-0 lg:gap-4">
                    <x-forms.input-field label="First Name" name="first_name" id="first_name" placeholder="John"
                        :isRequired="true" />

                    <x-forms.input-field label="Last Name" name="last_name" id="last_name" placeholder="Doe" />

                    <x-forms.input-field label="Email Address" name="email" id="email" type="email"
                        placeholder="user@example.com" :isRequired="true" />

                    <x-forms.input-field label="Username" name="username" id="username" placeholder="JohnDoe123" />

                    <x-forms.input-field label="Password" name="password" id="password" type="password"
                        :isRequired="true" />
                </div>

                <div
                    class="space-y-4 col-span-1 sm:grid sm:grid-cols-2 sm:col-start-1 sm:col-end-3 sm:space-y-0 sm:gap-4 lg:grid-cols-3 lg:col-end-4">
                    <x-forms.input-field label="NIK" name="nik" id="nik" placeholder="1234567890123456"
                        :isRequired="true" />

                    <x-forms.input-field label="NUPTK" name="nuptk" id="nuptk" placeholder="2368934567893653" />

                    <x-forms.input-field label="Position" name="position" id="position" placeholder="Teacher" />

                    <x-forms.input-field label="Current Address" name="address" id="address"
                        placeholder="Jl. Abc 1/2, Jakarta, Indonesia" />

                    <x-forms.input-field label="Phone Number" name="phone_number" id="phone_number"
                        placeholder="081212345678" />

                    <x-forms.select label="Role" name="role" id="role" :options="$roles" display="name" />

                    <x-forms.select label="Level" name="level" id="level" :options="$levels" display="name" />

                    <x-forms.select label="Schedule" name="schedule" id="schedule" :options="$schedules" />

                    <x-forms.input-field label="Employment Start" name="employment_start" id="employment_start"
                        type="date" :isRequired="true" />

                    <x-forms.input-field label="Employment End" name="employment_end" id="employment_end"
                        type="date" />
                </div>
            </div>

            <x-forms.toggle name="active" :checked="true" :trueLabel="__($activeKey[1]['active'])" :falseLabel="__($activeKey[0]['active'])" />

            <x-forms.button type="submit" btnBg="bg-green-400 dark:bg-green-600" btnHover="hover:bg-green-500"
                icon="icon-square-plus" btnSize="w-full sm:w-40">
                Create
            </x-forms.button>
        </form>
    </section>
</x-layout>
