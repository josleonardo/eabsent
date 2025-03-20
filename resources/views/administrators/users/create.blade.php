<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <section class="bg-gray-50 border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <form action="#" class="p-3 max-w-5xl sm:p-4">
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <div class="w-full">
                    <label for="email" class="block mb-2 text-sm/6 font-medium text-gray-900 dark:text-white">
                        Email address
                    </label>
                    <input type="email" name="email" id="email" placeholder="user@example.com" required
                        value="{{ old('email') }}"
                        class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div class="w-full">
                    <label for="fullname" class="block mb-2 text-sm/6 font-medium text-gray-900 dark:text-white">
                        Full Name
                    </label>
                    <input type="text" name="fullname" id="fullname" placeholder="John Doe" required
                        value="{{ old('fullname') }}"
                        class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div class="w-full">
                    <label for="username" class="block mb-2 text-sm/6 font-medium text-gray-900 dark:text-white">
                        Username
                    </label>
                    <input type="text" name="username" id="username" placeholder="JohnDoe123"
                        value="{{ old('username') }}"
                        class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div class="w-full">
                    <label for="password" class="block mb-2 text-sm/6 font-medium text-gray-900 dark:text-white">
                        Password
                    </label>
                    <input type="password" name="password" id="password"
                        class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div class="w-full">
                    <label for="nik" class="block mb-2 text-sm/6 font-medium text-gray-900 dark:text-white">
                        NIK
                    </label>
                    <input type="text" name="nik" id="nik" placeholder="1234567890123456" required
                        class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div class="w-full">
                    <label for="nuptk" class="block mb-2 text-sm/6 font-medium text-gray-900 dark:text-white">
                        NUPTK
                    </label>
                    <input type="text" name="nuptk" id="nuptk"
                        class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div class="w-full">
                    <label for="position" class="block mb-2 text-sm/6 font-medium text-gray-900 dark:text-white">
                        Position
                    </label>
                    <input type="text" name="position" id="position" placeholder="Teacher"
                        value="{{ old('position') }}"
                        class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div class="w-full">
                    <label for="address" class="block mb-2 text-sm/6 font-medium text-gray-900 dark:text-white">
                        Current Address
                    </label>
                    <input type="text" name="address" id="address" placeholder="Jl. Abc 1/2, Jakarta, Indonesia"
                        class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div class="w-full">
                    <label for="phoneNumber" class="block mb-2 text-sm/6 font-medium text-gray-900 dark:text-white">
                        Phone Number
                    </label>
                    <input type="text" name="phoneNumber" id="phoneNumber" placeholder="081212345678"
                        class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <label for="role" class="block mb-2 text-sm/6 font-medium text-gray-900 dark:text-white">
                        Role
                    </label>
                    <select name="role" id="role"
                        class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
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
                class="flex w-40 justify-center mt-6 rounded-md bg-indigo-600 px-3 py-2 font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Add user
            </button>
        </form>
    </section>
</x-layout>
