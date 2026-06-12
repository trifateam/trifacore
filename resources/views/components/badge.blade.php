@props([
    'variant' => 'gray',
    'size' => 'sm',
    'dot' => false,
])

@php
    $variantClasses = match($variant) {
        'success' => '',
        'warning' => '',
        'danger' => 'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300',
        'info' => '',
        'purple' => '',
        'gray' => 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200',
        default => 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200',
    };

    $variantStyles = match($variant) {
        'success' => 'background-color: rgba(184,245,0,0.2); color: #72ce27;',
        'warning' => 'background-color: rgba(255,224,0,0.2); color: #ff9900;',
        'info' => 'background-color: rgba(255,200,0,0.2); color: #ff9900;',
        'purple' => 'background-color: rgba(149,226,20,0.2); color: #72ce27;',
        default => '',
    };

    $sizeClasses = match($size) {
        'sm' => 'px-2.5 py-0.5 text-xs',
        'md' => 'px-3 py-1 text-sm',
        default => 'px-2.5 py-0.5 text-xs',
    };
    
    $dotStyles = match($variant) {
        'success' => 'color: #95e214;',
        'warning' => 'color: #ffc800;',
        'danger' => 'color: #ff0000;',
        'info' => 'color: #ff9900;',
        'purple' => 'color: #72ce27;',
        'gray' => 'color: #6b7280;',
        default => 'color: #6b7280;',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center font-medium rounded-full {$variantClasses} {$sizeClasses}"]) }} style="{{ $variantStyles }}">
    @if($dot)
        <svg class="mr-1.5 h-2 w-2" fill="currentColor" viewBox="0 0 8 8" style="{{ $dotStyles }}">
            <circle cx="4" cy="4" r="3" />
        </svg>
    @endif
    {{ $slot }}
</span>
