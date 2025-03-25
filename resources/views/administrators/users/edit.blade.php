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

        {{-- Edit form --}}
        <form action="{{ route('user.update', $user->id) }}" method="POST" class="space-y-6 max-w-7xl">
            @csrf
            @method('PUT')

            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <div class="w-full">
                    <label for="email" class="block mb-2 text-sm/6 font-medium text-gray-900 dark:text-white">
                        Email address
                    </label>
                    <input type="email" name="email" id="email" placeholder="user@example.com" required
                        value="{{ old('email', $user->email) }}"
                        class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 border border-gray-200 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500"> {{ $message }}</p>
                    @enderror
                </div>

                <div class="w-full">
                    <label for="fullname" class="block mb-2 text-sm/6 font-medium text-gray-900 dark:text-white">
                        Full Name
                    </label>
                    <input type="text" name="fullname" id="fullname" placeholder="John Doe" required
                        value="{{ old('fullname', $user->profile->fullname) }}"
                        class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 border border-gray-200 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('fullname')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500"> {{ $message }}</p>
                    @enderror
                </div>

                <div class="w-full">
                    <label for="username" class="block mb-2 text-sm/6 font-medium text-gray-900 dark:text-white">
                        Username
                    </label>
                    <input type="text" name="username" id="username" placeholder="JohnDoe123"
                        value="{{ old('username', $user->username) }}"
                        class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 border border-gray-200 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('username')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500"> {{ $message }}</p>
                    @enderror
                </div>

                <div class="w-full">
                    <label for="password" class="block mb-2 text-sm/6 font-medium text-gray-900 dark:text-white">
                        Password
                    </label>
                    <input type="password" name="password" id="password" aria-label="disabled input"
                        placeholder="Disabled input" disabled
                        class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 border border-gray-200 cursor-not-allowed placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <div class="w-full">
                    <label for="nik" class="block mb-2 text-sm/6 font-medium text-gray-900 dark:text-white">
                        NIK
                    </label>
                    <input type="text" name="nik" id="nik" placeholder="1234567890123456" required
                        value="{{ old('nik', $user->profile->nik) }}"
                        class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 border border-gray-200 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('nik')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500"> {{ $message }}</p>
                    @enderror
                </div>

                <div class="w-full">
                    <label for="nuptk" class="block mb-2 text-sm/6 font-medium text-gray-900 dark:text-white">
                        NUPTK
                    </label>
                    <input type="text" name="nuptk" id="nuptk" placeholder="2368934567893653"
                        value="{{ old('nuptk', $user->profile->nuptk) }}"
                        class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 border border-gray-200 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('nuptk')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500"> {{ $message }}</p>
                    @enderror
                </div>

                <div class="w-full">
                    <label for="position" class="block mb-2 text-sm/6 font-medium text-gray-900 dark:text-white">
                        Position
                    </label>
                    <input type="text" name="position" id="position" placeholder="Teacher"
                        value="{{ old('position', $user->profile->position) }}"
                        class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 border border-gray-200 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('position')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500"> {{ $message }}</p>
                    @enderror
                </div>

                <div class="w-full">
                    <label for="address" class="block mb-2 text-sm/6 font-medium text-gray-900 dark:text-white">
                        Current Address
                    </label>
                    <input type="text" name="address" id="address" placeholder="Jl. Abc 1/2, Jakarta, Indonesia"
                        value="{{ old('address', $user->profile->address) }}"
                        class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 border border-gray-200 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('address')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500"> {{ $message }}</p>
                    @enderror
                </div>

                <div class="w-full">
                    <label for="phone_number" class="block mb-2 text-sm/6 font-medium text-gray-900 dark:text-white">
                        Phone Number
                    </label>
                    <input type="text" name="phone_number" id="phone_number" placeholder="081212345678"
                        value="{{ old('phone_number', $user->profile->phone_number) }}"
                        class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 border border-gray-200 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('phone_number')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500"> {{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="role" class="block mb-2 text-sm/6 font-medium text-gray-900 dark:text-white">
                        Role
                    </label>
                    <select name="role" id="role"
                        class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 border border-gray-200 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="" selected>Select role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}"
                                {{ $user->role->contains($role->id) ? 'selected' : '' }}>
                                {{ $role->role_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500"> {{ $message }}</p>
                    @enderror
                </div>

                <div class="w-full">
                    <label for="employment_start"
                        class="block mb-2 text-sm/6 font-medium text-gray-900 dark:text-white">
                        Employment Start
                    </label>
                    <input type="date" name="employment_start" id="employment_start"
                        value="{{ old('employment_start', $user->profile->employment_start) }}"
                        class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 border border-gray-200 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('employment_start')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500"> {{ $message }}</p>
                    @enderror
                </div>

                <div class="w-full">
                    <label for="employment_end"
                        class="block mb-2 text-sm/6 font-medium text-gray-900 dark:text-white">
                        Employment End
                    </label>
                    <input type="date" name="employment_end" id="employment_end"
                        value="{{ old('employment_end', $user->profile->employment_end) }}"
                        class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 border border-gray-200 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('employment_end')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500"> {{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Active/inactive button --}}
            <x-btn-active :active="$user->active" />

            {{-- Add data button --}}
            <button type="submit"
                class="flex w-40 items-center justify-center rounded-md bg-indigo-600 p-2.5 font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                <svg class="size-5 text-white me-2" aria-hidden="true" fill="currentColor"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                    <path
                        d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160L0 416c0 53 43 96 96 96l256 0c53 0 96-43 96-96l0-96c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 96c0 17.7-14.3 32-32 32L96 448c-17.7 0-32-14.3-32-32l0-256c0-17.7 14.3-32 32-32l96 0c17.7 0 32-14.3 32-32s-14.3-32-32-32L96 64z" />
                </svg>
                {{ $pageName }}
            </button>
        </form>
    </section>
</x-layout>
