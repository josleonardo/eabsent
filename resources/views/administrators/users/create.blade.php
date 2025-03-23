<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <section class="p-3 space-y-6 border border-gray-200 sm:p-4 dark:bg-gray-800 dark:border-gray-700">
        {{-- Back button --}}
        <a href="{{ route('user.index') }}"
            class="inline-flex items-center p-2.5 rounded-md bg-blue-600 font-semibold text-sm text-white shadow-xs hover:bg-blue-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
            <svg class="size-5 text-white me-2" aria-hidden="true" fill="currentColor"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                <path
                    d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z" />
            </svg>
            Back
        </a>

        {{-- Create form --}}
        <form action="{{ route('user.store') }}" method="POST" class="space-y-6 max-w-7xl">
            @csrf

            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <div class="w-full">
                    <label for="email" class="block mb-2 text-sm/6 font-medium text-gray-900 dark:text-white">
                        Email address
                    </label>
                    <input type="email" name="email" id="email" placeholder="user@example.com" required
                        value="{{ old('email') }}"
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
                        value="{{ old('fullname') }}"
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
                        value="{{ old('username') }}"
                        class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 border border-gray-200 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('username')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500"> {{ $message }}</p>
                    @enderror
                </div>

                <div class="w-full">
                    <label for="password" class="block mb-2 text-sm/6 font-medium text-gray-900 dark:text-white">
                        Password
                    </label>
                    <input type="password" name="password" id="password"
                        class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 border border-gray-200 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('password')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500"> {{ $message }}</p>
                    @enderror
                </div>

                <div class="w-full">
                    <label for="nik" class="block mb-2 text-sm/6 font-medium text-gray-900 dark:text-white">
                        NIK
                    </label>
                    <input type="text" name="nik" id="nik" placeholder="1234567890123456" required
                        value="{{ old('nik') }}"
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
                        value="{{ old('nuptk') }}"
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
                        value="{{ old('position') }}"
                        class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 border border-gray-200 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('position')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500"> {{ $message }}</p>
                    @enderror
                </div>

                <div class="w-full">
                    <label for="address" class="block mb-2 text-sm/6 font-medium text-gray-900 dark:text-white">
                        Current Address
                    </label>
                    <input type="text" name="address" id="address"
                        placeholder="Jl. Abc 1/2, Jakarta, Indonesia" value="{{ old('address') }}"
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
                        value="{{ old('phone_number') }}"
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
                        value="{{ old('role') }}"
                        class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 border border-gray-200 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="" selected>Select role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->role_name }}</option>
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
                        value="{{ old('employment_start') }}"
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
                        value="{{ old('employment_end') }}"
                        class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 border border-gray-200 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('employment_end')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500"> {{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Active/inactive button --}}
            <label class="inline-flex items-center cursor-pointer">
                <input type="checkbox" name="active_checkbox" id="active_checkbox" class="sr-only peer" checked
                    onclick="toggleActive()">
                <input type="hidden" name="active" id="active" value="1">
                <div
                    class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600 dark:peer-checked:bg-blue-600">
                </div>
                <span id="active_text" class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Active</span>
            </label>

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

<script>
    function toggleActive() {
        const checkbox = document.getElementById('active_checkbox');
        const hiddenInput = document.getElementById('active');
        const activeText = document.getElementById('active_text');

        if (checkbox.checked) {
            hiddenInput.value = "1";
            activeText.textContent = "Active";
        } else {
            hiddenInput.value = "0";
            activeText.textContent = "Inactive";
        }
    }
</script>
