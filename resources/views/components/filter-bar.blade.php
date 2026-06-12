@props([
    'action' => '',
    'method' => 'GET',
])

<form action="{{ $action }}" method="{{ $method }}" {{ $attributes->merge(['class' => 'bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 mb-6 flex flex-wrap gap-4 items-end']) }}>
    {{ $slot }}
    
    <div class="mb-4 flex space-x-2">
        <x-button type="submit" variant="primary" icon="funnel">Filter</x-button>
        @if(request()->except(['page']))
            <x-button type="button" variant="secondary" href="{{ url()->current() }}">Reset</x-button>
        @endif
    </div>
</form>
