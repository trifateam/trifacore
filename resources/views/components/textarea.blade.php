@props([
    'label' => null,
    'name',
    'value' => null,
    'rows' => 3,
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'hint' => null,
])

@php
    $hasError = $errors->has($name);
    $inputClass = 'w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-[#ff9900] focus:ring-[#ff9900] text-sm';
    
    if ($hasError) {
        $inputClass = 'w-full rounded-lg border-red-500 dark:border-red-400 bg-white dark:bg-gray-700 focus:border-red-500 focus:ring-red-500 text-sm text-red-900 dark:text-red-300 placeholder-red-300 dark:placeholder-red-500';
    }

    if ($disabled) {
        $inputClass .= ' bg-gray-100 dark:bg-gray-600 cursor-not-allowed text-gray-500 dark:text-gray-400';
    }
@endphp

<div class="mb-4">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            {{ $label }} @if($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif

    <div class="relative rounded-md shadow-sm">
        <textarea 
            name="{{ $name }}"
            id="{{ $name }}"
            rows="{{ $rows }}"
            placeholder="{{ $placeholder }}"
            @disabled($disabled)
            @required($required)
            {{ $attributes->merge(['class' => $inputClass]) }}
        >{{ old($name, $value) }}</textarea>
    </div>

    @error($name)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror

    @if($hint && !$hasError)
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $hint }}</p>
    @endif
</div>
