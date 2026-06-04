@props([
    'label' => null,
    'name',
    'type' => 'text',
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'hint' => null,
    'prefix' => null,
    'suffix' => null,
])

@php
    $hasError = $errors->has($name);
    $inputClass = 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm sm:text-sm';
    
    if ($hasError) {
        $inputClass = 'w-full rounded-lg border-red-500 focus:border-red-500 focus:ring-red-500 text-sm sm:text-sm text-red-900 placeholder-red-300';
    }

    if ($disabled || $readonly) {
        $inputClass .= ' bg-gray-100 cursor-not-allowed text-gray-500';
    }

    if ($prefix) $inputClass .= ' pl-12';
    if ($suffix) $inputClass .= ' pr-12';
@endphp

<div class="mb-4">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }} @if($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif

    <div class="relative rounded-md shadow-sm">
        @if($prefix)
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <span class="text-gray-500 sm:text-sm">{{ $prefix }}</span>
            </div>
        @endif

        <input 
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $name }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            @disabled($disabled)
            @readonly($readonly)
            @required($required)
            {{ $attributes->merge(['class' => $inputClass]) }}
        >

        @if($suffix)
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                <span class="text-gray-500 sm:text-sm">{{ $suffix }}</span>
            </div>
        @endif
    </div>

    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror

    @if($hint && !$hasError)
        <p class="mt-1 text-sm text-gray-500">{{ $hint }}</p>
    @endif
</div>
