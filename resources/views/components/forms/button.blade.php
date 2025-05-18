@props([
    'type' => 'button',
    'as' => 'button',
    'href' => '#',
    'btnSize' => 'w-fit',
    'btnBg' => 'bg-indigo-400 dark:bg-indigo-600',
    'btnHover' => 'hover:bg-indigo-500',
    'icon' => null,
    'iconPosition' => 'left',
    'textColor' => 'text-gray-50 dark:text-white',
    'textSize' => 'text-base',
    'fontWeight' => 'font-semibold',
    'disabled' => false,
])

@php
    $baseClass = "$btnSize $btnBg $textColor $textSize $fontWeight p-2 flex items-center justify-center rounded-md shadow-sm transition focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600";
    $disabledClass = $disabled ? 'opacity-50 cursor-not-allowed' : $btnHover;
    $hasSlot = trim($slot);
    $iconLeftClass = $hasSlot ? 'size-5 me-1' : 'size-5 m-0.5';
    $iconRightClass = $hasSlot ? 'size-5 ms-1' : 'size-5 m-0.5';
@endphp

@if ($as === 'link')
    <a href="{{ $disabled ? '#' : $href }}" {{ $attributes->merge(['class' => "$baseClass $disabledClass"]) }}
        @if ($disabled) aria-disabled="true" tabindex="-1" @endif>
        @if ($icon && $iconPosition === 'left')
            <x-dynamic-component :component="$icon" class="{{ $iconLeftClass }}" />
        @endif

        @if ($hasSlot)
            <span>{{ $slot }}</span>
        @endif

        @if ($icon && $iconPosition === 'right')
            <x-dynamic-component :component="$icon" class="{{ $iconRightClass }}" />
        @endif
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => "$baseClass $disabledClass"]) }}
        {{ $disabled ? 'disabled' : '' }}>
        @if ($icon && $iconPosition === 'left')
            <x-dynamic-component :component="$icon" class="{{ $iconLeftClass }}" />
        @endif

        @if ($hasSlot)
            <span>{{ $slot }}</span>
        @endif

        @if ($icon && $iconPosition === 'right')
            <x-dynamic-component :component="$icon" class="{{ $iconRightClass }}" />
        @endif
    </button>
@endif
