@props([
    'message' => 'Belum ada data',
    'icon' => 'inbox',
    'actionLabel' => null,
    'actionUrl' => null,
])

<div {{ $attributes->merge(['class' => 'text-center py-12 px-4']) }}>
    <x-dynamic-component :component="'heroicon-o-'.$icon" class="mx-auto h-12 w-12 text-gray-400" />
    <h3 class="mt-2 text-sm font-semibold text-gray-900">{{ $message }}</h3>
    @if($actionLabel && $actionUrl)
        <div class="mt-6">
            <x-button variant="primary" icon="plus" href="{{ $actionUrl }}">
                {{ $actionLabel }}
            </x-button>
        </div>
    @endif
</div>
