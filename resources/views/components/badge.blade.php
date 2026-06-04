@props([
    'variant' => 'gray',
    'size' => 'sm',
    'dot' => false,
])

@php
    $variantClasses = match($variant) {
        'success' => 'bg-emerald-100 text-emerald-800',
        'warning' => 'bg-amber-100 text-amber-800',
        'danger' => 'bg-red-100 text-red-800',
        'info' => 'bg-blue-100 text-blue-800',
        'gray' => 'bg-gray-100 text-gray-800',
        default => 'bg-gray-100 text-gray-800',
    };

    $sizeClasses = match($size) {
        'sm' => 'px-2.5 py-0.5 text-xs',
        'md' => 'px-3 py-1 text-sm',
        default => 'px-2.5 py-0.5 text-xs',
    };
    
    $dotClasses = match($variant) {
        'success' => 'text-emerald-500',
        'warning' => 'text-amber-500',
        'danger' => 'text-red-500',
        'info' => 'text-blue-500',
        'gray' => 'text-gray-500',
        default => 'text-gray-500',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center font-medium rounded-full {$variantClasses} {$sizeClasses}"]) }}>
    @if($dot)
        <svg class="mr-1.5 h-2 w-2 {{ $dotClasses }}" fill="currentColor" viewBox="0 0 8 8">
            <circle cx="4" cy="4" r="3" />
        </svg>
    @endif
    {{ $slot }}
</span>
