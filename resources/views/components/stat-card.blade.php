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
        'blue' => 'bg-blue-100 text-blue-600',
        'green' => 'bg-emerald-100 text-emerald-600',
        'red' => 'bg-red-100 text-red-600',
        'yellow' => 'bg-amber-100 text-amber-600',
        'purple' => 'bg-purple-100 text-purple-600',
        default => 'bg-blue-100 text-blue-600',
    };

    $trendClasses = '';
    $trendIcon = '';
    
    if ($trend === 'up') {
        $trendClasses = 'text-emerald-600 bg-emerald-100';
        $trendIcon = '<svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" /></svg>';
    } elseif ($trend === 'down') {
        $trendClasses = 'text-red-600 bg-red-100';
        $trendIcon = '<svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" /></svg>';
    }
@endphp

<div {{ $attributes->merge(['class' => 'bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col']) }}>
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-medium text-gray-500">{{ $title }}</h3>
        @if($icon)
            <div class="p-2 rounded-lg {{ $colorClasses }}">
                <x-dynamic-component :component="'heroicon-o-'.$icon" class="w-5 h-5" />
            </div>
        @endif
    </div>
    
    <div class="flex items-baseline space-x-2">
        <div class="text-2xl font-bold text-gray-900">{{ $value }}</div>
        @if($trend && $trendValue)
            <span class="inline-flex items-baseline px-2 py-0.5 rounded-full text-xs font-medium md:mt-2 lg:mt-0 {{ $trendClasses }}">
                {!! $trendIcon !!}
                {{ $trendValue }}
            </span>
        @endif
    </div>
</div>
