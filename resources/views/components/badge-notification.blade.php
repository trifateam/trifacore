@props(['show' => false, 'type' => 'dot'])

@if($show)
    @if($type === 'dot')
        <span {{ $attributes->merge(['class' => 'absolute flex h-2.5 w-2.5']) }}>
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
        </span>
    @else
        <span {{ $attributes->merge(['class' => 'absolute inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full']) }}>
            {{ $slot }}
        </span>
    @endif
@endif
