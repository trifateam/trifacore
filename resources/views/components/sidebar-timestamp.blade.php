{{-- 
    Sidebar Timestamp Component
    Displays live clock with Indonesian locale formatting.
    Uses Alpine.js for real-time updates.
--}}

<div class="relative mt-auto mb-6" x-data="sidebarClock()" x-init="init()" @mouseenter="show = true" @mouseleave="show = false" @click="show = !show">
    <div class="w-12 h-12 flex items-center justify-center rounded-xl text-gray-400 hover:text-gray-200 hover:bg-gray-800 transition-all duration-200 cursor-pointer">
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    </div>
    
    {{-- Tooltip/Popover --}}
    <div x-show="show" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-1"
         class="absolute z-50 pointer-events-none bg-gray-800 border border-gray-700 text-white rounded-lg shadow-xl px-4 py-3"
         style="left: 4rem; bottom: 0; width: max-content; display: none;">
        <div class="font-medium text-gray-400 uppercase tracking-wider mb-1" style="font-size: 10px;" x-text="currentDate"></div>
        <div class="font-mono font-bold text-sm text-gray-100 tabular-nums" x-text="currentTime"></div>
    </div>
</div>

<script>
function sidebarClock() {
    return {
        show: false,
        currentDate: '',
        currentTime: '',
        init() {
            this.updateClock();
            setInterval(() => this.updateClock(), 1000);
        },
        updateClock() {
            const now = new Date();
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            
            this.currentDate = `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;
            this.currentTime = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false });
        }
    };
}
</script>
