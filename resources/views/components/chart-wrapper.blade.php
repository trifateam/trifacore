@props([
    'title' => null,
    'height' => 'h-80',
    'id',
])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden flex flex-col']) }}>
    @if($title)
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $title }}</h3>
        </div>
    @endif
    
    <div class="p-4 flex-1">
        <div class="relative {{ $height }} w-full">
            <canvas id="{{ $id }}"></canvas>
        </div>
    </div>
</div>
