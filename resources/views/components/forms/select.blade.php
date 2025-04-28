<div>
    <label for="{{ $name }}" class="block mb-2 text-sm/6 font-medium text-gray-900 dark:text-white">
        {{ $label }}
    </label>
    <select name="{{ $name }}" id="{{ $id }}" value="{{ old($name) }}"
        {{ $isRequired ? 'required' : '' }} {{ $isDisabled ? 'disabled' : '' }}
        class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 border border-gray-200 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        <option value="" {{ old($name, $selected) ? '' : 'selected' }}>Select {{ $name }}</option>
        @foreach ($options as $option)
            <option value="{{ isset($option['ids']) ? implode(',', $option['ids']) : $option['id'] ?? $option->id }}"
                {{ (old($name, $selected) == (isset($option['ids']) ? implode(',', $option['ids']) : $option['id'] ?? $option->id)) ? 'selected' : '' }}>
                {{ $option['display'] ?? $option->$display }}
            </option>
        @endforeach
    </select>
    @error($name)
        <p class="mt-2 text-sm text-red-600 dark:text-red-500"> {{ $message }}</p>
    @enderror
</div>
