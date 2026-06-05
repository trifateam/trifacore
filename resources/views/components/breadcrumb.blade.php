@props([
    'items' => [],
])

<nav class="flex text-sm text-gray-500 mb-6" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        @foreach($items as $item)
            <li class="inline-flex items-center">
                @if(!$loop->first)
                    <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                @endif
                
                @if(isset($item['url']) && !$loop->last)
                    <a href="{{ $item['url'] }}" class="inline-flex items-center font-medium hover:text-indigo-600 transition-colors">
                        @if($loop->first)
                            <x-dynamic-component component="heroicon-s-home" class="w-4 h-4 mr-2.5" />
                        @endif
                        {{ $item['label'] }}
                    </a>
                @else
                    <span class="inline-flex items-center font-medium text-gray-900">
                        @if($loop->first)
                            <x-dynamic-component component="heroicon-s-home" class="w-4 h-4 mr-2.5" />
                        @endif
                        {{ $item['label'] }}
                    </span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
