<aside x-data="sidebarManager()"
       class="fixed left-0 top-0 h-screen z-30 transition-transform duration-300 flex"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- SECTION 1: Left Pane (Icon Bar)                             --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="w-20 bg-gray-900 border-r border-gray-800 flex flex-col items-center py-6 space-y-4 z-40 shrink-0">
        
        {{-- Dashboard --}}
        <a href="/dashboard" 
           @click="setMenu('dashboard', 'Dashboard')"
           class="w-12 h-12 flex items-center justify-center rounded-xl transition-all duration-200"
           :class="activeMenu === 'dashboard' ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/30' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800'">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
        </a>

        @role('Admin', 'Pegawai Kandang')
        {{-- Pencatatan Harian --}}
        <button @click="setMenu('pencatatan', 'Pencatatan Harian')"
           class="w-12 h-12 flex items-center justify-center rounded-xl transition-all duration-200 cursor-pointer"
           :class="activeMenu === 'pencatatan' ? 'bg-[#4f46e5] text-white shadow-lg shadow-[#4f46e5]/30' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800'">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15a2.25 2.25 0 012.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
            </svg>
        </button>
        @endrole

        @role('Admin', 'Owner', 'Sales')
        {{-- Manajemen Transaksi --}}
        <button @click="setMenu('transaksi', 'Manajemen Transaksi')"
           class="w-12 h-12 flex items-center justify-center rounded-xl transition-all duration-200 cursor-pointer"
           :class="activeMenu === 'transaksi' ? 'bg-[#2563eb] text-white shadow-lg shadow-[#2563eb]/30' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800'">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
            </svg>
        </button>
        @endrole

        @role('Admin', 'Owner', 'Pegawai Gudang')
        {{-- Operasional --}}
        <button @click="setMenu('operasional', 'Operasional')"
           class="w-12 h-12 flex items-center justify-center rounded-xl transition-all duration-200 cursor-pointer"
           :class="activeMenu === 'operasional' ? 'bg-[#7c3aed] text-white shadow-lg shadow-[#7c3aed]/30' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800'">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l-5.1-3.04A1.5 1.5 0 005 13.5v5.25a1.5 1.5 0 001.32 1.49l7.5.94a1.5 1.5 0 001.68-1.49V13.5a1.5 1.5 0 00-1.32-1.49l-2.76-.34zM17.5 7.64l-5.1-3.04A1.5 1.5 0 0011 5.87v5.25a1.5 1.5 0 001.32 1.49l7.5.94a1.5 1.5 0 001.68-1.49V5.87a1.5 1.5 0 00-1.32-1.49l-2.68-.34z" />
            </svg>
        </button>
        @endrole

        @role('Admin', 'Owner')
        {{-- Master Data --}}
        <button @click="setMenu('masterData', 'Master Data')"
           class="w-12 h-12 flex items-center justify-center rounded-xl transition-all duration-200 cursor-pointer"
           :class="activeMenu === 'masterData' ? 'bg-[#d97706] text-white shadow-lg shadow-[#d97706]/30' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800'">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
            </svg>
        </button>
        
        {{-- Management Keuangan --}}
        <button @click="setMenu('keuangan', 'Management Keuangan')"
           class="w-12 h-12 flex items-center justify-center rounded-xl transition-all duration-200 cursor-pointer"
           :class="activeMenu === 'keuangan' ? 'bg-[#0891b2] text-white shadow-lg shadow-[#0891b2]/30' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800'">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
            </svg>
        </button>

        {{-- Laporan --}}
        <button @click="setMenu('laporan', 'Laporan')"
           class="w-12 h-12 flex items-center justify-center rounded-xl transition-all duration-200 cursor-pointer"
           :class="activeMenu === 'laporan' ? 'bg-[#059669] text-white shadow-lg shadow-[#059669]/30' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800'">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
            </svg>
        </button>

        {{-- Pengaturan --}}
        <button @click="setMenu('pengaturan', 'Pengaturan')"
           class="w-12 h-12 flex items-center justify-center rounded-xl transition-all duration-200 cursor-pointer"
           :class="activeMenu === 'pengaturan' ? 'bg-[#dc2626] text-white shadow-lg shadow-[#dc2626]/30' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800'">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </button>
        @endrole
        
        <x-sidebar-timestamp />
    </div>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- SECTION 2: Right Pane (Submenu Bar)                         --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="w-60 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-300 flex flex-col border-r border-gray-200 dark:border-gray-700/60 shadow-xl z-30 sidebar-right-pane transition-colors duration-300">
        <div class="px-5 py-5 border-b border-gray-200 dark:border-gray-700/60">
            <h2 class="text-[15px] font-bold tracking-wide text-gray-900 dark:text-white" x-text="menuTitle"></h2>
        </div>
        
        <nav class="flex-1 overflow-y-auto px-2 py-4 space-y-1 sidebar-nav">
            
            {{-- Dashboard Submenus --}}
            <div x-show="activeMenu === 'dashboard'" x-cloak>
                <x-sidebar-nav-item href="/dashboard" :active="request()->is('dashboard')">Ringkasan Dashboard</x-sidebar-nav-item>
            </div>

            @role('Admin', 'Pegawai Kandang')
            <div x-show="activeMenu === 'pencatatan'" x-cloak class="space-y-4">
                <div>
                    <div class="px-4 mb-2 mt-2 text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pencatatan</div>
                    <x-sidebar-nav-item href="/pencatatan/produksi-telur" :active="request()->is('pencatatan/produksi-telur*')">Produksi Telur</x-sidebar-nav-item>
                    <x-sidebar-nav-item href="/pencatatan/konsumsi-pakan" :active="request()->is('pencatatan/konsumsi-pakan*')">Konsumsi Pakan</x-sidebar-nav-item>
                    <x-sidebar-nav-item href="/pencatatan/konsumsi-vitamin" :active="request()->is('pencatatan/konsumsi-vitamin*')">Konsumsi Vitamin</x-sidebar-nav-item>
                    <x-sidebar-nav-item href="/pencatatan/deplesi" :active="request()->is('pencatatan/deplesi*')">Kematian/Afkir (Deplesi)</x-sidebar-nav-item>
                    <x-sidebar-nav-item href="/pencatatan/suhu" :active="request()->is('pencatatan/suhu*')">Suhu Lingkungan</x-sidebar-nav-item>
                    <x-sidebar-nav-item href="/pencatatan/pupuk" :active="request()->is('pencatatan/pupuk*')">Produksi Pupuk</x-sidebar-nav-item>
                </div>
                <div>
                    <div class="px-4 mb-2 text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Riwayat Recording</div>
                    <x-sidebar-nav-item href="/pencatatan/riwayat/produksi-telur" :active="request()->is('pencatatan/riwayat/produksi-telur')">Produksi Telur</x-sidebar-nav-item>
                    <x-sidebar-nav-item href="/pencatatan/riwayat/konsumsi-pakan" :active="request()->is('pencatatan/riwayat/konsumsi-pakan')">Konsumsi Pakan</x-sidebar-nav-item>
                    <x-sidebar-nav-item href="/pencatatan/riwayat/konsumsi-vitamin" :active="request()->is('pencatatan/riwayat/konsumsi-vitamin')">Konsumsi Vitamin</x-sidebar-nav-item>
                    <x-sidebar-nav-item href="/pencatatan/riwayat/deplesi" :active="request()->is('pencatatan/riwayat/deplesi')">Deplesi</x-sidebar-nav-item>
                    <x-sidebar-nav-item href="/pencatatan/riwayat/suhu" :active="request()->is('pencatatan/riwayat/suhu')">Suhu Kandang</x-sidebar-nav-item>
                    <x-sidebar-nav-item href="/pencatatan/riwayat/pupuk" :active="request()->is('pencatatan/riwayat/pupuk')">Produksi Pupuk</x-sidebar-nav-item>
                </div>
            </div>
            @endrole

            @role('Admin', 'Owner', 'Sales')
            <div x-show="activeMenu === 'transaksi'" x-cloak>
                <div class="px-4 mb-2 mt-2 text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Transaksi</div>
                <x-sidebar-nav-item href="/transaksi/penjualan" :active="request()->is('transaksi/penjualan*')">Transaksi Penjualan</x-sidebar-nav-item>
                <x-sidebar-nav-item href="/transaksi/pembelian" :active="request()->is('transaksi/pembelian*')">Transaksi Pembelian</x-sidebar-nav-item>
                <div class="px-4 mb-2 mt-4 text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Riwayat</div>
                <x-sidebar-nav-item href="/transaksi/riwayat-penjualan" :active="request()->is('transaksi/riwayat-penjualan')">Riwayat Penjualan</x-sidebar-nav-item>
                <x-sidebar-nav-item href="/transaksi/riwayat-pembelian" :active="request()->is('transaksi/riwayat-pembelian')">Riwayat Pembelian</x-sidebar-nav-item>
            </div>
            @endrole

            @role('Admin', 'Owner', 'Pegawai Gudang')
            <div x-show="activeMenu === 'operasional'" x-cloak>
                <x-sidebar-nav-item href="/kandang-operasional" :active="request()->is('kandang-operasional*')">Kandang</x-sidebar-nav-item>
                <x-sidebar-nav-item href="/gudang" :active="request()->is('gudang*')">Gudang</x-sidebar-nav-item>
            </div>
            @endrole

            @role('Admin', 'Owner')
            <div x-show="activeMenu === 'masterData'" x-cloak>
                <x-sidebar-nav-item href="/master-data/kandang" :active="request()->is('master-data/kandang')">Data Kandang</x-sidebar-nav-item>
                <x-sidebar-nav-item href="/master-data/barang" :active="request()->is('master-data/barang')">Data Barang/Item</x-sidebar-nav-item>
                <x-sidebar-nav-item href="/master-data/supplier" :active="request()->is('master-data/supplier')">Data Supplier</x-sidebar-nav-item>
                <x-sidebar-nav-item href="/master-data/pegawai" :active="request()->is('master-data/pegawai')">Data Pegawai</x-sidebar-nav-item>
                <x-sidebar-nav-item href="/master-data/pelanggan" :active="request()->is('master-data/pelanggan')">Data Pelanggan</x-sidebar-nav-item>
                <x-sidebar-nav-item href="/master-data/rekening" :active="request()->is('master-data/rekening')">Data Rekening Kas/Bank</x-sidebar-nav-item>
                <x-sidebar-nav-item href="/master-data/kategori-biaya" :active="request()->is('master-data/kategori-biaya')">Data Kategori Biaya</x-sidebar-nav-item>
            </div>

            <div x-show="activeMenu === 'keuangan'" x-cloak>
                <x-sidebar-nav-item href="/keuangan/biaya-operasional" :active="request()->is('keuangan/biaya-operasional')">Biaya Operasional</x-sidebar-nav-item>
                <x-sidebar-nav-item href="/keuangan/buku-kas" :active="request()->is('keuangan/buku-kas')">Buku Kas</x-sidebar-nav-item>
                <x-sidebar-nav-item href="/keuangan/buku-utang" :active="request()->is('keuangan/buku-utang')">Buku Utang</x-sidebar-nav-item>
                <x-sidebar-nav-item href="/keuangan/buku-piutang" :active="request()->is('keuangan/buku-piutang')">Buku Piutang</x-sidebar-nav-item>
            </div>

            <div x-show="activeMenu === 'laporan'" x-cloak>
                <x-sidebar-nav-item href="/laporan/produksi-performa" :active="request()->is('laporan/produksi-performa')">Produksi & Performa</x-sidebar-nav-item>
                <x-sidebar-nav-item href="/laporan/laba-rugi" :active="request()->is('laporan/laba-rugi')">Laporan Laba Rugi</x-sidebar-nav-item>
                <div class="px-4 mb-2 mt-4 text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Cetak</div>
                <x-sidebar-nav-item href="/laporan/cetak/produksi-telur" :active="request()->is('laporan/cetak/produksi-telur')">Cetak Produksi Telur</x-sidebar-nav-item>
                <x-sidebar-nav-item href="/laporan/cetak/penjualan-telur" :active="request()->is('laporan/cetak/penjualan-telur')">Cetak Penjualan Telur</x-sidebar-nav-item>
                <x-sidebar-nav-item href="/laporan/cetak/pembelian-pakan" :active="request()->is('laporan/cetak/pembelian-pakan')">Cetak Pembelian Pakan</x-sidebar-nav-item>
            </div>

            <div x-show="activeMenu === 'pengaturan'" x-cloak>
                <x-sidebar-nav-item href="/pengaturan/profil-sistem" :active="request()->is('pengaturan/profil-sistem')">Profil & Sistem</x-sidebar-nav-item>
                <x-sidebar-nav-item href="/riwayat-aktivitas" :active="request()->is('riwayat-aktivitas')">Riwayat Aktivitas Sistem</x-sidebar-nav-item>
            </div>
            @endrole
        </nav>
    </div>
</aside>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('sidebarManager', () => ({
        activeMenu: 'dashboard',
        menuTitle: 'Dashboard',
        
        init() {
            const path = window.location.pathname;
            
            if (path.startsWith('/pencatatan')) {
                this.setMenu('pencatatan', 'Pencatatan Harian');
            } else if (path.startsWith('/transaksi')) {
                this.setMenu('transaksi', 'Manajemen Transaksi');
            } else if (path.startsWith('/kandang-operasional') || path.startsWith('/gudang')) {
                this.setMenu('operasional', 'Operasional');
            } else if (path.startsWith('/master-data')) {
                this.setMenu('masterData', 'Master Data');
            } else if (path.startsWith('/keuangan')) {
                this.setMenu('keuangan', 'Management Keuangan');
            } else if (path.startsWith('/laporan')) {
                this.setMenu('laporan', 'Laporan');
            } else if (path.startsWith('/pengaturan') || path.startsWith('/riwayat-aktivitas')) {
                this.setMenu('pengaturan', 'Pengaturan');
            } else {
                this.setMenu('dashboard', 'Dashboard');
            }
        },
        
        setMenu(id, title) {
            this.activeMenu = id;
            this.menuTitle = title;
        }
    }))
})
</script>
