@props([
    'label',
    'name',
    'value' => '1',
    'checked' => false,
    'disabled' => false,
])

<div class="flex items-center">
    <input 
        type="checkbox"
        name="{{ $name }}"
        id="{{ $name }}_{{ $value }}"
        value="{{ $value }}"
        @checked(old($name, $checked))
        @disabled($disabled)
        {{ $attributes->merge(['class' => 'h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 ' . ($disabled ? 'bg-gray-100 cursor-not-allowed' : '')]) }}
    >
    <label for="{{ $name }}_{{ $value }}" class="ml-2 block text-sm text-gray-900 {{ $disabled ? 'text-gray-500 cursor-not-allowed' : '' }}">
        {{ $label }}
    </label>
</div>
