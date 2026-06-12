@props([
    'name' => 'search',
    'placeholder' => 'Cari...',
    'value' => null,
])

<div class="mb-4 relative">
    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
        <x-dynamic-component component="heroicon-o-magnifying-glass" class="h-5 w-5 text-gray-400 dark:text-gray-500 dark:text-gray-400" />
    </div>
    <input 
        type="text" 
        name="{{ $name }}" 
        id="{{ $name }}" 
        value="{{ old($name, request($name, $value)) }}"
        class="block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 pl-10 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm shadow-sm" 
        placeholder="{{ $placeholder }}"
        {{ $attributes }}
    >
</div>
