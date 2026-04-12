<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <section class="space-y-6">
        {{-- Update email --}}
        <form action="{{ route('settings.account.email.update') }}" method="POST"
            class="p-3 max-w-7xl border border-gray-200 shadow-sm sm:p-4 dark:bg-gray-800 dark:border-gray-700">
            @csrf
            @method('PUT')

            <h2 class="pb-2 text-xl font-semibold text-gray-900 border-b-2 border-gray-200 dark:text-white">
                Change Email Address
            </h2>

            <div class="grid my-6 lg:grid-cols-2">
                <x-forms.input-field label="Email Address" name="email" id="email" type="email"
                    placeholder="user@example.com" :isRequired="true" :value="$user->email" />
            </div>

            <x-forms.button type="submit" icon="icon-edit" btnSize="w-full sm:w-52">
                Update Email
            </x-forms.button>
        </form>

        {{-- Update username --}}
        <form action="{{ route('settings.account.username.update') }}" method="POST"
            class="p-3 max-w-7xl border border-gray-200 shadow-sm sm:p-4 dark:bg-gray-800 dark:border-gray-700">
            @csrf
            @method('PUT')

            <h2 class="pb-2 text-xl font-semibold text-gray-900 border-b-2 border-gray-200 dark:text-white">
                Change Username
            </h2>

            <div class="grid my-6 lg:grid-cols-2">
                <x-forms.input-field label="Username" name="username" id="username" placeholder="JohnDoe123"
                    :value="$user->username" />
            </div>

            <x-forms.button type="submit" icon="icon-edit" btnSize="w-full sm:w-52">
                Update Username
            </x-forms.button>
        </form>

        {{-- Update password --}}
        @if ($hasChangePassword)
            <form action="{{ route('change-password.update') }}" method="POST"
                class="p-3 max-w-7xl border border-gray-200 shadow-sm sm:p-4 dark:bg-gray-800 dark:border-gray-700">
                @csrf
                @method('PUT')

                <h2 class="pb-2 text-xl font-semibold text-gray-900 border-b-2 border-gray-200 dark:text-white">
                    Change Password
                </h2>

                <div class="grid my-6 lg:grid-cols-2">
                    <div class="space-y-4 col-span-1">
                        <x-forms.input-field label="Current Password" name="current_password" id="current_password"
                            type="password" placeholder="Enter current password" :isRequired="true" />
                        <x-forms.input-field label="New Password" name="new_password" id="new_password" type="password"
                            placeholder="Enter new password" :isRequired="true" />
                        <x-forms.input-field label="Confirm New Password" name="new_password_confirmation"
                            id="new_password_confirmation" type="password" placeholder="Confirm new password"
                            :isRequired="true" />
                    </div>
                </div>

                <x-forms.button type="submit" icon="icon-edit" btnSize="w-full sm:w-52">
                    Update Password
                </x-forms.button>
            </form>
        @endif

        <form action="{{ route('settings.account.language.update') }}" method="POST"
            class="p-3 max-w-7xl border border-gray-200 shadow-sm sm:p-4 dark:bg-gray-800 dark:border-gray-700">
            @csrf
            @method('PUT')

            <h2 class="pb-2 text-xl font-semibold text-gray-900 border-b-2 border-gray-200 dark:text-white">
                Change Language
            </h2>

            <div class="grid my-6 lg:grid-cols-2">
                <select name="language" id="language" required
                    class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 border border-gray-200 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >
                    @foreach ($languages as $code => $language)
                        <option value="{{ $code }}" {{ $user->language === $code ? 'selected' : '' }}>
                            {{ $language }}
                        </option>
                    @endforeach
                </select>
            </div>

            <x-forms.button type="submit" icon="icon-edit" btnSize="w-full sm:w-52">
                Update Language
            </x-forms.button>
        </form>
    </section>
</x-layout>
