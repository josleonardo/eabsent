<x-layout>
    <x-slot:pageName>{{ $pageName }}</x-slot>

    <section class="p-3 space-y-6 border border-gray-200 sm:p-4 dark:bg-gray-800 dark:border-gray-700">
        <x-forms.button as="link" href="{{ route('menu.index') }}" icon="icon-chevron-left">
            Back
        </x-forms.button>

        <form action="{{ route('menu.store') }}" method="POST" class="space-y-6 max-w-7xl">
            @csrf

            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <x-forms.input-field label="Menu Group" name="menu_group" id="menu_group" type="number" placeholder="1"
                    :isRequired="true" />

                <x-forms.input-field label="Menu Name" name="menu_name" id="menu_name" placeholder="Menu Name"
                    :isRequired="true" />

                <x-forms.input-field label="URL" name="url" id="url" placeholder="menu-url"
                    :isRequired="true" />

                <div>
                    <label for="platform" class="block mb-2 text-sm/6 font-medium text-gray-900 dark:text-white">
                        Platform
                    </label>
                    <select name="platform" id="platform" required
                        class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 border border-gray-200 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @foreach ($platforms as $key => $label)
                            <option value="{{ $key }}" {{ old('platform') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('platform')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500"> {{ $message }}</p>
                    @enderror
                </div>

                <x-forms.input-field label="Order" name="order" id="order" type="number" placeholder="1" />

                <x-forms.input-field label="Icon" name="icon" id="icon" placeholder="home-rounded" />
            </div>

            <x-forms.toggle name="active" :checked="true" :trueLabel="__($activeKey[1]['active'])" :falseLabel="__($activeKey[0]['active'])" />

            <x-forms.button type="submit" btnBg="bg-green-400 dark:bg-green-600" btnHover="hover:bg-green-500"
                icon="icon-square-plus" btnSize="w-full sm:w-40">
                Create
            </x-forms.button>
        </form>
    </section>
</x-layout>
