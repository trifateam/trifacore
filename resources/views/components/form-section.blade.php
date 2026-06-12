@props([
    'title',
    'description' => null,
])

<div {{ $attributes->merge(['class' => 'mb-8']) }}>
    <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">{{ $title }}</h3>
        @if($description)
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $description }}</p>
        @endif
    </div>
    <div class="space-y-6">
        {{ $slot }}
    </div>
</div>
