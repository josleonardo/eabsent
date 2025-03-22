<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <section class="p-3 border border-gray-200 sm:p-4 dark:bg-gray-800 dark:border-gray-700">
        <a href="{{ route('user.index') }}"
            class="inline-flex items-center mb-4 p-2.5 rounded-md bg-blue-600 font-semibold text-sm text-white shadow-xs hover:bg-blue-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
            <svg class="w-3.5 h-3.5 text-white me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 16">
                <path d="m10.036 8.278 9.258-7.79A1.979 1.979 0 0 0 18 0H2A1.987 1.987 0 0 0 .641.541l9.395 7.737Z"/>
                <path d="M11.241 9.817c-.36.275-.801.425-1.255.427-.428 0-.845-.138-1.187-.395L0 2.6V14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2.5l-8.759 7.317Z"/>
            </svg>
            Back
        </a>
        
        <form action="#" method="POST" class="max-w-7xl">
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <div class="w-full">
                    <label for="email" class="block mb-2 text-sm/6 font-medium text-gray-900 dark:text-white">
                        Email address
                    </label>
                    <input type="email" name="email" id="email" placeholder="user@example.com" required
                        value="{{ old('email') }}"
                        class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 border border-gray-200 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div class="w-full">
                    <label for="fullname" class="block mb-2 text-sm/6 font-medium text-gray-900 dark:text-white">
                        Full Name
                    </label>
                    <input type="text" name="fullname" id="fullname" placeholder="John Doe" required
                        value="{{ old('fullname') }}"
                        class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 border border-gray-200 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div class="w-full">
                    <label for="username" class="block mb-2 text-sm/6 font-medium text-gray-900 dark:text-white">
                        Username
                    </label>
                    <input type="text" name="username" id="username" placeholder="JohnDoe123"
                        value="{{ old('username') }}"
                        class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 border border-gray-200 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div class="w-full">
                    <label for="password" class="block mb-2 text-sm/6 font-medium text-gray-900 dark:text-white">
                        Password
                    </label>
                    <input type="password" name="password" id="password"
                        class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 border border-gray-200 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div class="w-full">
                    <label for="nik" class="block mb-2 text-sm/6 font-medium text-gray-900 dark:text-white">
                        NIK
                    </label>
                    <input type="text" name="nik" id="nik" placeholder="1234567890123456" required
                        class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 border border-gray-200 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div class="w-full">
                    <label for="nuptk" class="block mb-2 text-sm/6 font-medium text-gray-900 dark:text-white">
                        NUPTK
                    </label>
                    <input type="text" name="nuptk" id="nuptk" placeholder="2368934567893653"
                        class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 border border-gray-200 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div class="w-full">
                    <label for="position" class="block mb-2 text-sm/6 font-medium text-gray-900 dark:text-white">
                        Position
                    </label>
                    <input type="text" name="position" id="position" placeholder="Teacher"
                        value="{{ old('position') }}"
                        class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 border border-gray-200 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div class="w-full">
                    <label for="address" class="block mb-2 text-sm/6 font-medium text-gray-900 dark:text-white">
                        Current Address
                    </label>
                    <input type="text" name="address" id="address" placeholder="Jl. Abc 1/2, Jakarta, Indonesia"
                        class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 border border-gray-200 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div class="w-full">
                    <label for="phoneNumber" class="block mb-2 text-sm/6 font-medium text-gray-900 dark:text-white">
                        Phone Number
                    </label>
                    <input type="text" name="phoneNumber" id="phoneNumber" placeholder="081212345678"
                        class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 border border-gray-200 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <label for="role" class="block mb-2 text-sm/6 font-medium text-gray-900 dark:text-white">
                        Role
                    </label>
                    <select name="role" id="role"
                        class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 border border-gray-200 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option selected="">Select role</option>
                        <option value="TV">TV/Monitors</option>
                        <option value="PC">PC</option>
                        <option value="GA">Gaming/Console</option>
                        <option value="PH">Phones</option>
                    </select>
                </div>
            </div>

            <label class="mt-4 inline-flex items-center cursor-pointer">
                <input type="checkbox" value="" class="sr-only peer" checked>
                <div
                    class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600 dark:peer-checked:bg-blue-600">
                </div>
                <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Active</span>
            </label>

            <button type="submit"
                class="flex w-40 justify-center mt-6 rounded-md bg-indigo-600 p-2.5 font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                {{ $pageName }}
            </button>
        </form>
    </section>
</x-layout>
