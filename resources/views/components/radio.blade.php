@props([
    'label',
    'name',
    'value',
    'checked' => false,
    'disabled' => false,
])

<div class="flex items-center">
    <input 
        type="radio"
        name="{{ $name }}"
        id="{{ $name }}_{{ $value }}"
        value="{{ $value }}"
        @checked(old($name) == $value || (!old($name) && $checked))
        @disabled($disabled)
        {{ $attributes->merge(['class' => 'h-4 w-4 border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-[#ff9900] focus:ring-[#ff9900] ' . ($disabled ? 'bg-gray-100 dark:bg-gray-600 cursor-not-allowed' : '')]) }}
    >
    <label for="{{ $name }}_{{ $value }}" class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300 {{ $disabled ? 'text-gray-500 dark:text-gray-500 dark:text-gray-400 cursor-not-allowed' : '' }}">
        {{ $label }}
    </label>
</div>
