{{-- 
    Sidebar Timestamp Component
    Displays live clock with Indonesian locale formatting.
    Uses Alpine.js for real-time updates.
--}}

<div class="sidebar-timestamp" x-data="sidebarClock()" x-init="init()">
    <div class="sidebar-timestamp-inner">
        {{-- Clock Icon --}}
        <div class="sidebar-timestamp-icon">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        {{-- Date & Time --}}
        <div class="sidebar-timestamp-content">
            <span class="sidebar-timestamp-date" x-text="currentDate"></span>
            <span class="sidebar-timestamp-time" x-text="currentTime"></span>
        </div>
    </div>
</div>

<script>
function sidebarClock() {
    return {
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
