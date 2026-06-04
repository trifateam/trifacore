@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'href' => null,
    'disabled' => false,
    'icon' => null,
    'fullWidth' => false,
])

@php
    $baseClasses = 'inline-flex items-center justify-center rounded-lg font-medium transition-colors focus:ring-2 focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed';
    
    $sizeClasses = match($size) {
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base',
        default => 'px-4 py-2 text-sm',
    };

    $variantClasses = match($variant) {
        'primary' => 'bg-indigo-600 hover:bg-indigo-700 text-white focus:ring-indigo-500',
        'secondary' => 'bg-gray-200 hover:bg-gray-300 text-gray-800 focus:ring-gray-400',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white focus:ring-red-500',
        'success' => 'bg-emerald-600 hover:bg-emerald-700 text-white focus:ring-emerald-500',
        'warning' => 'bg-amber-500 hover:bg-amber-600 text-white focus:ring-amber-500',
        default => 'bg-indigo-600 hover:bg-indigo-700 text-white focus:ring-indigo-500',
    };

    $widthClass = $fullWidth ? 'w-full' : '';
    
    $classes = "{$baseClasses} {$sizeClasses} {$variantClasses} {$widthClass}";
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)
            <x-dynamic-component :component="'heroicon-o-'.$icon" class="w-5 h-5 mr-2 -ml-1" />
        @endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" @disabled($disabled) {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)
            <x-dynamic-component :component="'heroicon-o-'.$icon" class="w-5 h-5 mr-2 -ml-1" />
        @endif
        {{ $slot }}
    </button>
@endif
