@props([
    'title',
    'subtitle' => null,
])

<div {{ $attributes->merge(['class' => 'flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6']) }}>
    <div class="mb-4 sm:mb-0">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $title }}</h1>
        @if($subtitle)
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $subtitle }}</p>
        @endif
    </div>
    
    @isset($action)
        <div class="flex items-center space-x-3">
            {{ $action }}
        </div>
    @endisset
</div>
