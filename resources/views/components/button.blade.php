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
        'primary' => 'text-white focus:ring-[#ffc800]',
        'secondary' => 'bg-gray-200 hover:bg-gray-300 text-gray-800 focus:ring-gray-400',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white focus:ring-red-500',
        'success' => 'text-white focus:ring-[#95e214]',
        'warning' => 'text-gray-900 focus:ring-[#fff700]',
        default => 'text-white focus:ring-[#ffc800]',
    };

    $variantStyles = match($variant) {
        'primary' => 'background-color: #ff9900;',
        'success' => 'background-color: #72ce27;',
        'warning' => 'background-color: #ffe000;',
        default => '',
    };

    $variantHoverStyles = match($variant) {
        'primary' => 'onmouseover="this.style.backgroundColor=\'#e68a00\'" onmouseout="this.style.backgroundColor=\'#ff9900\'"',
        'success' => 'onmouseover="this.style.backgroundColor=\'#5fb31e\'" onmouseout="this.style.backgroundColor=\'#72ce27\'"',
        'warning' => 'onmouseover="this.style.backgroundColor=\'#ffc800\'" onmouseout="this.style.backgroundColor=\'#ffe000\'"',
        default => '',
    };

    $widthClass = $fullWidth ? 'w-full' : '';
    
    $classes = "{$baseClasses} {$sizeClasses} {$variantClasses} {$widthClass}";
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }} style="{{ $variantStyles }}" {!! $variantHoverStyles !!}>
        @if($icon)
            <x-dynamic-component :component="'heroicon-o-'.$icon" class="w-5 h-5 mr-2 -ml-1" />
        @endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" @disabled($disabled) {{ $attributes->merge(['class' => $classes]) }} style="{{ $variantStyles }}" {!! $variantHoverStyles !!}>
        @if($icon)
            <x-dynamic-component :component="'heroicon-o-'.$icon" class="w-5 h-5 mr-2 -ml-1" />
        @endif
        {{ $slot }}
    </button>
@endif
