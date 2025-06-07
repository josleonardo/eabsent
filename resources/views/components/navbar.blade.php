<nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">

            <div class="flex items-center justify-start gap-3">
                <button @click="sidebarOpen = !sidebarOpen" aria-controls="dashboard-sidebar" type="button"
                    class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd" fill-rule="evenodd"
                            d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                        </path>
                    </svg>
                </button>

                <!-- Company logo and name -->
                <a href="{{ route('home.index') }}"
                    class="flex gap-3 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    <x-icon-icons class="size-8 text-blue-500" />
                    <span
                        class="self-center text-blue-500 text-xl font-semibold sm:text-2xl whitespace-nowrap">EAbsent</span>
                </a>
            </div>

            <div class="md:block">
                <div class="ml-4 flex items-center md:ml-6">
                    <!-- Notification bell -->
                    <button type="button"
                        class="relative rounded-full p-1 text-gray-500 transition hover:bg-gray-100 hover:text-gray-900 focus:text-gray-900 focus:bg-gray-100 focus:shadow-sm focus:outline-0 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:text-white dark:focus:bg-gray-700">
                        <span class="absolute -inset-1.5"></span>
                        <span class="sr-only">View notifications</span>
                        <x-icon-bell class="size-6" />
                    </button>

                    <!-- Profile dropdown -->
                    @auth
                        <div x-data="{ profileDrop: false }" class="relative ml-3">
                            <div>
                                <button type="button" @click="profileDrop = !profileDrop"
                                    class="relative rounded-full p-1 text-gray-500 transition hover:bg-gray-100 hover:text-gray-900 focus:text-gray-900 focus:bg-gray-100 focus:shadow-sm focus:outline-0 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:text-white dark:focus:bg-gray-700"
                                    id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                    <span class="absolute -inset-1.5"></span>
                                    <span class="sr-only">Open user menu</span>
                                    @php
                                        $avatar = Auth::user()->profile->avatar;
                                    @endphp
                                    @if ($avatar && Storage::disk('public')->exists($avatar))
                                        <img src="{{ asset('storage/' . $avatar) }}" alt="User Avatar" class="size-6 rounded-full object-cover border-2 border-gray-500 dark:border-gray-400" />
                                    @else
                                        <x-icon-user-circle class="size-6" />
                                    @endif
                                </button>
                            </div>

                            <div x-show="profileDrop"
                                @click.away="profileDrop = false"x-transition:enter="transition ease-out duration-100 transform"
                                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75 transform"
                                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                                class="absolute right-0 z-10 mt-2 w-48 origin-top-right list-none rounded-sm bg-white ring-1 divide-y divide-gray-100 shadow-lg ring-black/5 focus:outline-hidden dark:bg-gray-700 dark:divide-gray-600"
                                role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button"
                                tabindex="-1">
                                <div class="px-4 py-3" role="none">
                                    <p class="text-sm text-gray-900 dark:text-white" role="none">
                                        {{ Auth::user()->profile->first_name }} {{ Auth::user()->profile->last_name }}
                                    </p>
                                    <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300" role="none">
                                        {{ Auth::user()->email }}
                                    </p>
                                </div>
                                <ul class="py-1" role="none">
                                    <li>
                                        <a href="{{ route('settings.profile') }}"
                                            class="flex items-center justify-start px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                            role="menuitem" tabindex="-1" id="settings-btn">
                                            <x-icon-settings-2 class="inline-flex mr-2 size-4" />
                                            Settings
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('signout') }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="flex items-center justify-start w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                                role="menuitem" tabindex="-1" id="signout-btn">
                                                <x-icon-logout class="inline-flex mr-2 size-4" />
                                                Sign out
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>

        </div>
    </div>
</nav>
