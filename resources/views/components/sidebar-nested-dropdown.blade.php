{{-- 
    Sidebar Nested Dropdown Component (Dropdown-in-Dropdown)
    
    Usage:
    <x-sidebar-nested-dropdown label="Sub Group" :active="$isActive">
        <x-sidebar-nav-item href="/path" :active="request()->is('path')">Label</x-sidebar-nav-item>
    </x-sidebar-nested-dropdown>
    
    Props:
    - label: Sub-group heading text
    - active: Whether any child is active (auto-opens sub-dropdown)
    - defaultOpen: Whether sub-dropdown starts open (default: true)
--}}

@props([
    'label',
    'active' => false,
    'defaultOpen' => true,
])

<div class="sidebar-nested-dropdown" x-data="{ subOpen: {{ ($active || $defaultOpen) ? 'true' : 'false' }} }">
    {{-- Nested Toggle Button --}}
    <button 
        @click="subOpen = !subOpen" 
        class="sidebar-nested-toggle group"
        :class="{ 'sidebar-nested-active': {{ $active ? 'true' : 'false' }} }"
    >
        <div class="flex items-center gap-2 min-w-0">
            <svg 
                class="sidebar-nested-chevron" 
                :class="{ 'rotate-90': subOpen }"
                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"
            >
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
            <span class="sidebar-nested-label">{{ $label }}</span>
        </div>
    </button>

    {{-- Nested Content --}}
    <div x-show="subOpen" x-collapse {{ ($active || $defaultOpen) ? '' : 'x-cloak' }}>
        <div class="sidebar-nested-content">
            {{ $slot }}
        </div>
    </div>
</div>
