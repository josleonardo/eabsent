<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <section class="p-3 space-y-6 border border-gray-200 sm:p-4 dark:bg-gray-800 dark:border-gray-700">
        {{-- Back button --}}
        <a href="{{ route('user.index') }}"
            class="inline-flex items-center p-2.5 rounded-md bg-blue-600 font-semibold text-sm text-white shadow-xs hover:bg-blue-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
            <svg class="size-5 text-white me-2" aria-hidden="true" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                <path
                    d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z" />
            </svg>
            Back
        </a>

        {{-- Create form --}}
        <form action="{{ route('user.store') }}" method="POST" class="space-y-6 max-w-7xl">
            @csrf

            <div class="grid gap-4 md:grid-cols-2 md:gap-6">
                <x-forms.input-field label="Email Address" name="email" id="email" type="email"
                    placeholder="user@example.com" :isRequired="true" />

                <x-forms.input-field label="Full Name" name="fullname" id="fullname" placeholder="John Doe"
                    :isRequired="true" />

                <x-forms.input-field label="Username" name="username" id="username" placeholder="JohnDoe123" />

                <x-forms.input-field label="Password" name="password" id="password" type="password" />

                <x-forms.input-field label="NIK" name="nik" id="nik" placeholder="1234567890123456"
                    :isRequired="true" />

                <x-forms.input-field label="NUPTK" name="nuptk" id="nuptk" placeholder="2368934567893653" />

                <x-forms.input-field label="Position" name="position" id="position" placeholder="Teacher" />

                <x-forms.input-field label="Current Address" name="address" id="address"
                    placeholder="Jl. Abc 1/2, Jakarta, Indonesia" />

                <x-forms.input-field label="Phone Number" name="phone_number" id="phone_number"
                    placeholder="081212345678" />

                <x-forms.select label="Role" name="role" id="role" :options="$roles" display="role_name" />

                <x-forms.select label="Level" name="level" id="level" :options="$levels" display="level_name" />

                <x-forms.select label="Schedule" name="schedule" id="schedule" :options="$schedules" />

                <x-forms.input-field label="Employment Start" name="employment_start" id="employment_start"
                    type="date" :isRequired="true" />

                <x-forms.input-field label="Employment End" name="employment_end" id="employment_end" type="date" />
            </div>

            <x-forms.toggle name="active" :checked="true" trueLabel="Active" falseLabel="Inactive" />

            {{-- Add data button --}}
            <button type="submit"
                class="flex w-40 items-center justify-center rounded-md bg-indigo-600 p-2.5 font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                <svg class="size-5 text-white me-2" aria-hidden="true" fill="currentColor"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                    <path
                        d="M64 80c-8.8 0-16 7.2-16 16l0 320c0 8.8 7.2 16 16 16l320 0c8.8 0 16-7.2 16-16l0-320c0-8.8-7.2-16-16-16L64 80zM0 96C0 60.7 28.7 32 64 32l320 0c35.3 0 64 28.7 64 64l0 320c0 35.3-28.7 64-64 64L64 480c-35.3 0-64-28.7-64-64L0 96zM200 344l0-64-64 0c-13.3 0-24-10.7-24-24s10.7-24 24-24l64 0 0-64c0-13.3 10.7-24 24-24s24 10.7 24 24l0 64 64 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-64 0 0 64c0 13.3-10.7 24-24 24s-24-10.7-24-24z" />
                </svg>
                {{ $pageName }}
            </button>
        </form>
    </section>
</x-layout>
