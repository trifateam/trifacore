@props([
    'title',
    'value',
    'icon' => null,
    'trend' => null,
    'trendValue' => null,
    'color' => 'blue',
])

@php
    $colorClasses = match($color) {
        'blue' => '',
        'green' => '',
        'red' => 'bg-red-100 dark:bg-red-900/50 text-red-600 dark:text-red-500',
        'yellow' => '',
        'purple' => '',
        default => '',
    };

    $colorStyles = match($color) {
        'blue' => 'background-color: rgba(255,200,0,0.2); color: #ff9900;',
        'green' => 'background-color: rgba(184,245,0,0.2); color: #72ce27;',
        'yellow' => 'background-color: rgba(255,224,0,0.2); color: #ffc800;',
        'purple' => 'background-color: rgba(149,226,20,0.2); color: #95e214;',
        default => 'background-color: rgba(255,200,0,0.2); color: #ff9900;',
    };

    $trendClasses = '';
    $trendStyle = '';
    $trendIcon = '';
    
    if ($trend === 'up') {
        $trendStyle = 'color: #72ce27; background-color: rgba(184,245,0,0.2);';
        $trendIcon = '<svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" /></svg>';
    } elseif ($trend === 'down') {
        $trendClasses = 'text-red-600 dark:text-red-500 bg-red-100 dark:bg-red-900/50';
        $trendIcon = '<svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" /></svg>';
    }
@endphp

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 flex flex-col']) }}>
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $title }}</h3>
        @if($icon)
            <div class="p-2 rounded-lg {{ $colorClasses }}" style="{{ $colorStyles }}">
                <x-dynamic-component :component="'heroicon-o-'.$icon" class="w-5 h-5" />
            </div>
        @endif
    </div>
    
    <div class="flex items-baseline space-x-2">
        <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $value }}</div>
        @if($trend && $trendValue)
            <span class="inline-flex items-baseline px-2 py-0.5 rounded-full text-xs font-medium md:mt-2 lg:mt-0 {{ $trendClasses }}" style="{{ $trendStyle }}">
                {!! $trendIcon !!}
                {{ $trendValue }}
            </span>
        @endif
    </div>
</div>
