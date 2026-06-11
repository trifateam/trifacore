@props([
    'label' => null,
    'name',
    'options' => [],
    'selected' => null,
    'placeholder' => '-- Pilih --',
    'required' => false,
    'disabled' => false,
])

@php
    $hasError = $errors->has($name);
    $inputClass = 'w-full rounded-lg border-gray-300 shadow-sm focus:border-[#ff9900] focus:ring-[#ff9900] text-sm';
    
    if ($hasError) {
        $inputClass = 'w-full rounded-lg border-red-500 focus:border-red-500 focus:ring-red-500 text-sm text-red-900';
    }

    if ($disabled) {
        $inputClass .= ' bg-gray-100 cursor-not-allowed text-gray-500';
    }
    
    $currentValue = old($name, $selected);
@endphp

<div class="mb-4">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }} @if($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif

    <select 
        name="{{ $name }}"
        id="{{ $name }}"
        @disabled($disabled)
        @required($required)
        {{ $attributes->merge(['class' => $inputClass]) }}
    >
        @if($placeholder)
            <option value="" disabled @selected(is_null($currentValue) || $currentValue === '')>{{ $placeholder }}</option>
        @endif
        
        {{ $slot }}

        @foreach($options as $option)
            <option value="{{ is_array($option) ? $option['value'] : $option }}" @selected($currentValue == (is_array($option) ? $option['value'] : $option))>
                {{ is_array($option) ? $option['label'] : $option }}
            </option>
        @endforeach
    </select>

    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
