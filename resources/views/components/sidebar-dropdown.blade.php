{{-- 
    Sidebar Dropdown Component
    
    Usage:
    <x-sidebar-dropdown label="Group Name" :active="$isActive" color="#hex">
        <x-slot:icon>
            <svg>...</svg>
        </x-slot:icon>
        <x-sidebar-nav-item href="/path" :active="request()->is('path')">Label</x-sidebar-nav-item>
    </x-sidebar-dropdown>
    
    Props:
    - label: Group heading text
    - color: Accent color for the group icon (hex)
    - active: Whether any child is active (auto-opens dropdown)
    - defaultOpen: Whether dropdown starts open (default: true)
    
    Slots:
    - icon: SVG heroicon for the dropdown header
    - default: Navigation items
--}}

@props([
    'label',
    'color' => '#64748b',
    'active' => false,
    'defaultOpen' => true,
])

<div class="sidebar-dropdown" x-data="{ open: {{ $defaultOpen ? 'true' : 'false' }} }">
    {{-- Dropdown Toggle Button --}}
    <button 
        @click="open = !open" 
        class="sidebar-dropdown-toggle group"
        :class="{ 'sidebar-dropdown-active': {{ $active ? 'true' : 'false' }} }"
    >
        <div class="flex items-center gap-2.5 min-w-0">
            @if(isset($icon))
                <span class="sidebar-dropdown-icon" style="color: {{ $color }};">
                    {{ $icon }}
                </span>
            @endif
            <span class="sidebar-dropdown-label">{{ $label }}</span>
        </div>
        <svg 
            class="sidebar-dropdown-chevron" 
            :class="{ 'rotate-90': open }"
            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"
        >
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
        </svg>
    </button>

    {{-- Dropdown Content --}}
    <div x-show="open" x-collapse x-cloak>
        <div class="sidebar-dropdown-content">
            {{ $slot }}
        </div>
    </div>
</div>
