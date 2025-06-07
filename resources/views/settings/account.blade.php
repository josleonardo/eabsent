<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    {{-- Toast notification --}}
    @if (session('success'))
        <x-toast type="success" :message="session('success')" />
    @endif
    @if (session('failed'))
        <x-toast type="failed" :message="session('failed')" />
    @endif

    <section class="space-y-6">
        {{-- Edit email form --}}
        <form action="{{ route('settings.account.update.email') }}" method="POST"
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

            {{-- Submit button --}}
            <x-forms.button type="submit" icon="icon-edit" btnSize="w-full sm:w-52">
                Update Email
            </x-forms.button>
        </form>

        {{-- Update username form --}}
        <form action="{{ route('settings.account.update.username') }}" method="POST"
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

            {{-- Submit button --}}
            <x-forms.button type="submit" icon="icon-edit" btnSize="w-full sm:w-52">
                Update Username
            </x-forms.button>
        </form>

        @if ($hasChangePassword)
            {{-- Change password form --}}
            <form action="{{ route('settings.account.update.password') }}" method="POST"
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

                {{-- Submit button --}}
                <x-forms.button type="submit" icon="icon-edit" btnSize="w-full sm:w-52">
                    Update Password
                </x-forms.button>
            </form>
        @endif
    </section>
</x-layout>
