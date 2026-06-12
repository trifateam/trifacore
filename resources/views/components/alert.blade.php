@props([
    'type' => 'info',
    'dismissible' => true,
    'title' => null,
])

@php
    $typeClasses = match($type) {
        'success' => 'bg-emerald-50 dark:bg-emerald-900/30 border-emerald-500 text-emerald-800 dark:text-emerald-300',
        'error' => 'bg-red-50 dark:bg-red-900/30 border-red-500 text-red-800 dark:text-red-300',
        'warning' => 'bg-amber-50 dark:bg-amber-900/30 border-amber-500 text-amber-800 dark:text-amber-300',
        'info' => 'bg-blue-50 dark:bg-blue-900/30 border-blue-500 text-blue-800 dark:text-blue-300',
        default => 'bg-blue-50 dark:bg-blue-900/30 border-blue-500 text-blue-800 dark:text-blue-300',
    };
    
    $icon = match($type) {
        'success' => 'check-circle',
        'error' => 'x-circle',
        'warning' => 'exclamation-triangle',
        'info' => 'information-circle',
        default => 'information-circle',
    };
@endphp

<div x-data="{ show: true }" x-show="show" x-transition.opacity
    {{ $attributes->merge(['class' => "rounded-lg p-4 border-l-4 {$typeClasses}"]) }}
    role="alert">
    <div class="flex">
        <div class="flex-shrink-0">
            <x-dynamic-component :component="'heroicon-s-'.$icon" class="h-5 w-5" />
        </div>
        <div class="ml-3 w-full">
            @if($title)
                <h3 class="text-sm font-medium">{{ $title }}</h3>
            @endif
            <div class="text-sm {{ $title ? 'mt-2' : '' }}">
                {{ $slot }}
            </div>
        </div>
        @if($dismissible)
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button type="button" @click="show = false" class="inline-flex rounded-md p-1.5 focus:outline-none focus:ring-2 focus:ring-offset-2 {{ $typeClasses }}">
                        <span class="sr-only">Dismiss</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>
