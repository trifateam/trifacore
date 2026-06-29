{{-- 
    Sidebar Navigation Item Component
    
    Usage:
    <x-sidebar-nav-item href="/path" :active="request()->is('path')">
        Label Text
    </x-sidebar-nav-item>
    
    Props:
    - href: Link destination
    - active: Whether this item is currently active
--}}

@props([
    'href' => '#',
    'active' => false,
])

<a 
    href="{{ $href }}" 
    class="sidebar-nav-item {{ $active ? 'sidebar-nav-item-active' : 'sidebar-nav-item-default' }}"
>
    <span class="truncate">{{ $slot }}</span>
</a>
