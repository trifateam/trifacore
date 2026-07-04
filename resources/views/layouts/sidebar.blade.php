<aside x-data="sidebarManager()"
       @mouseenter="handleSidebarEnter"
       @mouseleave="handleSidebarLeave"
       class="fixed left-0 top-0 h-screen z-30 hidden md:flex">

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- SECTION 1: Left Pane (Icon Bar)                             --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="w-20 bg-gray-900 border-r border-gray-800 flex flex-col items-center py-6 space-y-4 z-40 shrink-0">
        
        {{-- Dashboard --}}
        <a href="/dashboard" 
           @mouseenter="handleIconHover('dashboard', 'Dashboard')"
           @click="handleIconClick('dashboard', 'Dashboard', $event)"
           class="w-12 h-12 flex items-center justify-center rounded-xl transition-all duration-200"
           :class="currentPage === 'dashboard' ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : (activeMenu === 'dashboard' && isExpanded ? 'text-amber-500 bg-amber-500/10' : 'text-gray-400 hover:text-amber-500 hover:bg-amber-500/10')">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
        </a>

        @role('Pegawai Kandang')
        {{-- Pencatatan Harian --}}
        <button @mouseenter="handleIconHover('pencatatan', 'Pencatatan Harian')"
           @click="handleIconClick('pencatatan', 'Pencatatan Harian', $event)"
           class="relative w-12 h-12 flex items-center justify-center rounded-xl transition-all duration-200 cursor-pointer"
           :class="currentPage === 'pencatatan' ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : (activeMenu === 'pencatatan' && isExpanded ? 'text-amber-500 bg-amber-500/10' : 'text-gray-400 hover:text-amber-500 hover:bg-amber-500/10')">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15a2.25 2.25 0 012.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
            </svg>
            <x-badge-notification :show="isset($uncompletedTasks['has_any_task']) && $uncompletedTasks['has_any_task']" class="absolute top-2 right-2" />
        </button>
        @endrole

        @role('Sales', 'Admin', 'Owner')
        {{-- Penjualan --}}
        <button @mouseenter="handleIconHover('transaksi', 'Penjualan')"
           @click="handleIconClick('transaksi', 'Penjualan', $event)"
           class="w-12 h-12 flex items-center justify-center rounded-xl transition-all duration-200 cursor-pointer"
           :class="currentPage === 'transaksi' ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : (activeMenu === 'transaksi' && isExpanded ? 'text-amber-500 bg-amber-500/10' : 'text-gray-400 hover:text-amber-500 hover:bg-amber-500/10')">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
            </svg>
        </button>
        @endrole

        @role('Pegawai Gudang', 'Admin', 'Owner')
        {{-- Order Masuk --}}
        <button @mouseenter="handleIconHover('order-masuk', 'Order Masuk')"
           @click="handleIconClick('order-masuk', 'Order Masuk', $event)"
           class="relative w-12 h-12 flex items-center justify-center rounded-xl transition-all duration-200 cursor-pointer"
           :class="currentPage === 'order-masuk' ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : (activeMenu === 'order-masuk' && isExpanded ? 'text-amber-500 bg-amber-500/10' : 'text-gray-400 hover:text-amber-500 hover:bg-amber-500/10')">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.19-.504 1.125-1.125V14.25m-17.25 0h17.25m-17.25 0L5.625 4.5h12.75l2.625 9.75M12 4.5v15" />
            </svg>
            <x-badge-notification :show="isset($pendingOrdersCount) && $pendingOrdersCount > 0" class="absolute top-2 right-2" />
        </button>

        {{-- Penerimaan Barang --}}
        <button @mouseenter="handleIconHover('penerimaan', 'Penerimaan Barang')"
           @click="handleIconClick('penerimaan', 'Penerimaan Barang', $event)"
           class="w-12 h-12 flex items-center justify-center rounded-xl transition-all duration-200 cursor-pointer"
           :class="currentPage === 'penerimaan' ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : (activeMenu === 'penerimaan' && isExpanded ? 'text-amber-500 bg-amber-500/10' : 'text-gray-400 hover:text-amber-500 hover:bg-amber-500/10')">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
        </button>
        @endrole

        @role('Admin', 'Owner', 'Pegawai Kandang')
        {{-- Riwayat --}}
        <button @mouseenter="handleIconHover('riwayat', 'Riwayat')"
           @click="handleIconClick('riwayat', 'Riwayat', $event)"
           class="w-12 h-12 flex items-center justify-center rounded-xl transition-all duration-200 cursor-pointer"
           :class="currentPage === 'riwayat' ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : (activeMenu === 'riwayat' && isExpanded ? 'text-amber-500 bg-amber-500/10' : 'text-gray-400 hover:text-amber-500 hover:bg-amber-500/10')">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </button>
        @endrole

        @role('Admin', 'Owner', 'Pegawai Kandang')
        {{-- Kandang --}}
        <a href="/kandang"
           @mouseenter="handleIconHover('kandang', 'Kandang')"
           @click="handleIconClick('kandang', 'Kandang', $event)"
           class="w-12 h-12 flex items-center justify-center rounded-xl transition-all duration-200"
           :class="currentPage === 'kandang' ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : (activeMenu === 'kandang' && isExpanded ? 'text-amber-500 bg-amber-500/10' : 'text-gray-400 hover:text-amber-500 hover:bg-amber-500/10')">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m1.5.5l-1.5-.5M6.75 7.364V3h-3v18m3-13.636l10.5-3.819" />
            </svg>
        </a>
        
        {{-- Batch --}}
        <button @mouseenter="handleIconHover('batch', 'Batch')"
           @click="handleIconClick('batch', 'Batch', $event)"
           class="w-12 h-12 flex items-center justify-center rounded-xl transition-all duration-200 cursor-pointer"
           :class="currentPage === 'batch' ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : (activeMenu === 'batch' && isExpanded ? 'text-amber-500 bg-amber-500/10' : 'text-gray-400 hover:text-amber-500 hover:bg-amber-500/10')">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 7.125C2.25 6.504 2.754 6 3.375 6h6c.621 0 1.125.504 1.125 1.125v3.75c0 .621-.504 1.125-1.125 1.125h-6a1.125 1.125 0 01-1.125-1.125v-3.75zM14.25 8.625c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v8.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 01-1.125-1.125v-8.25zM3.75 16.125c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 01-1.125-1.125v-2.25z" />
            </svg>
        </button>
        @endrole

        @role('Admin', 'Owner', 'Pegawai Gudang', 'Sales')
        {{-- Gudang --}}
        <button @mouseenter="handleIconHover('gudang', 'Gudang')"
           @click="handleIconClick('gudang', 'Gudang', $event)"
           class="w-12 h-12 flex items-center justify-center rounded-xl transition-all duration-200 cursor-pointer"
           :class="currentPage === 'gudang' ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : (activeMenu === 'gudang' && isExpanded ? 'text-amber-500 bg-amber-500/10' : 'text-gray-400 hover:text-amber-500 hover:bg-amber-500/10')">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
            </svg>
        </button>
        @endrole

        @role('Admin', 'Owner', 'Pegawai Gudang')
        {{-- Buku Hutang --}}
        <button @mouseenter="handleIconHover('buku-hutang', 'Buku Hutang')"
           @click="handleIconClick('buku-hutang', 'Buku Hutang', $event)"
           class="w-12 h-12 flex items-center justify-center rounded-xl transition-all duration-200 cursor-pointer"
           :class="currentPage === 'buku-hutang' ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : (activeMenu === 'buku-hutang' && isExpanded ? 'text-amber-500 bg-amber-500/10' : 'text-gray-400 hover:text-amber-500 hover:bg-amber-500/10')">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </button>
        @endrole

        @role('Admin', 'Owner', 'Sales')
        {{-- Buku Piutang --}}
        <button @mouseenter="handleIconHover('buku-piutang', 'Buku Piutang')"
           @click="handleIconClick('buku-piutang', 'Buku Piutang', $event)"
           class="w-12 h-12 flex items-center justify-center rounded-xl transition-all duration-200 cursor-pointer"
           :class="currentPage === 'buku-piutang' ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : (activeMenu === 'buku-piutang' && isExpanded ? 'text-amber-500 bg-amber-500/10' : 'text-gray-400 hover:text-amber-500 hover:bg-amber-500/10')">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
            </svg>
        </button>
        @endrole

        @role('Admin', 'Owner', 'Pegawai Gudang', 'Sales')
        {{-- Master Data --}}
        <button @mouseenter="handleIconHover('masterData', 'Master Data')"
           @click="handleIconClick('masterData', 'Master Data', $event)"
           class="w-12 h-12 flex items-center justify-center rounded-xl transition-all duration-200 cursor-pointer"
           :class="currentPage === 'masterData' ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : (activeMenu === 'masterData' && isExpanded ? 'text-amber-500 bg-amber-500/10' : 'text-gray-400 hover:text-amber-500 hover:bg-amber-500/10')">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
            </svg>
        </button>
        @endrole
        
        @role('Admin', 'Owner')
        {{-- Management Keuangan --}}
        <button @mouseenter="handleIconHover('keuangan', 'Management Keuangan')"
           @click="handleIconClick('keuangan', 'Management Keuangan', $event)"
           class="w-12 h-12 flex items-center justify-center rounded-xl transition-all duration-200 cursor-pointer"
           :class="currentPage === 'keuangan' ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : (activeMenu === 'keuangan' && isExpanded ? 'text-amber-500 bg-amber-500/10' : 'text-gray-400 hover:text-amber-500 hover:bg-amber-500/10')">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
            </svg>
        </button>

        {{-- Laporan --}}
        <button @mouseenter="handleIconHover('laporan', 'Laporan')"
           @click="handleIconClick('laporan', 'Laporan', $event)"
           class="w-12 h-12 flex items-center justify-center rounded-xl transition-all duration-200 cursor-pointer"
           :class="currentPage === 'laporan' ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : (activeMenu === 'laporan' && isExpanded ? 'text-amber-500 bg-amber-500/10' : 'text-gray-400 hover:text-amber-500 hover:bg-amber-500/10')">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
            </svg>
        </button>

        {{-- Pengaturan --}}
        <button @mouseenter="handleIconHover('pengaturan', 'Pengaturan')"
           @click="handleIconClick('pengaturan', 'Pengaturan', $event)"
           class="w-12 h-12 flex items-center justify-center rounded-xl transition-all duration-200 cursor-pointer"
           :class="currentPage === 'pengaturan' ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : (activeMenu === 'pengaturan' && isExpanded ? 'text-amber-500 bg-amber-500/10' : 'text-gray-400 hover:text-amber-500 hover:bg-amber-500/10')">
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
    <div class="absolute left-20 top-0 h-full w-60 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-300 flex flex-col border-r border-gray-200 dark:border-gray-700/60 shadow-xl z-20 sidebar-right-pane transform transition-transform duration-300 ease-in-out"
         :class="isExpanded ? 'translate-x-0' : '-translate-x-full'">
        <div class="px-5 py-5 border-b border-gray-200 dark:border-gray-700/60">
            <h2 class="text-[15px] font-bold tracking-wide text-gray-900 dark:text-white" x-text="menuTitle"></h2>
        </div>
        
        <nav class="flex-1 overflow-y-auto px-2 py-4 space-y-1 sidebar-nav">
            
            {{-- Dashboard Submenus --}}
            <div x-show="activeMenu === 'dashboard'" x-cloak>
                <x-sidebar-nav-item href="/dashboard" :active="request()->is('dashboard')">Ringkasan Dashboard</x-sidebar-nav-item>
            </div>

            @role('Pegawai Kandang')
            <div x-show="activeMenu === 'pencatatan'" x-cloak>
                <x-sidebar-nav-item href="/pencatatan/produksi-telur" :active="request()->is('pencatatan/produksi-telur*')">Produksi Telur <x-badge-notification :show="isset($uncompletedTasks['telur']) && count($uncompletedTasks['telur']) > 0" class="top-1/2 -translate-y-1/2 right-4" /></x-sidebar-nav-item>
                <x-sidebar-nav-item href="/pencatatan/konsumsi-pakan" :active="request()->is('pencatatan/konsumsi-pakan*')">Konsumsi Pakan <x-badge-notification :show="isset($uncompletedTasks['pakan']) && count($uncompletedTasks['pakan']) > 0" class="top-1/2 -translate-y-1/2 right-4" /></x-sidebar-nav-item>
                <x-sidebar-nav-item href="/pencatatan/konsumsi-vitamin" :active="request()->is('pencatatan/konsumsi-vitamin*')">Konsumsi Vitamin</x-sidebar-nav-item>
                <x-sidebar-nav-item href="/pencatatan/deplesi" :active="request()->is('pencatatan/deplesi*')">Kematian/Afkir (Deplesi)</x-sidebar-nav-item>
                <x-sidebar-nav-item href="/pencatatan/suhu" :active="request()->is('pencatatan/suhu*')">Suhu Lingkungan <x-badge-notification :show="isset($uncompletedTasks['suhu']) && count($uncompletedTasks['suhu']) > 0" class="top-1/2 -translate-y-1/2 right-4" /></x-sidebar-nav-item>
                <x-sidebar-nav-item href="/pencatatan/pupuk" :active="request()->is('pencatatan/pupuk*')">Produksi Pupuk</x-sidebar-nav-item>
            </div>
            @endrole

            @role('Sales', 'Admin', 'Owner')
            <div x-show="activeMenu === 'transaksi'" x-cloak>
                <x-sidebar-nav-item href="/transaksi/penjualan" :active="request()->is('transaksi/penjualan*')">Input Penjualan</x-sidebar-nav-item>
                <x-sidebar-nav-item href="/transaksi/order-aktif" :active="request()->is('transaksi/order-aktif*')">Order Aktif</x-sidebar-nav-item>
                <x-sidebar-nav-item href="/transaksi/riwayat-penjualan" :active="request()->is('transaksi/riwayat-penjualan')">Riwayat Penjualan</x-sidebar-nav-item>
            </div>
            @endrole

            @role('Pegawai Gudang', 'Admin', 'Owner')
            <div x-show="activeMenu === 'order-masuk'" x-cloak>
                <x-sidebar-nav-item href="/transaksi/order-masuk" :active="request()->is('transaksi/order-masuk*')">
                    Daftar Order Masuk
                    <x-badge-notification :show="isset($pendingOrdersCount) && $pendingOrdersCount > 0" class="top-1/2 -translate-y-1/2 right-4" />
                </x-sidebar-nav-item>
            </div>
            <div x-show="activeMenu === 'penerimaan'" x-cloak>
                <x-sidebar-nav-item href="/transaksi/pembelian" :active="request()->is('transaksi/pembelian*')">Input Penerimaan Barang</x-sidebar-nav-item>
                <x-sidebar-nav-item href="/transaksi/riwayat-pembelian" :active="request()->is('transaksi/riwayat-pembelian')">Riwayat Penerimaan Barang</x-sidebar-nav-item>
            </div>
            @endrole

            <div x-show="activeMenu === 'riwayat'" x-cloak class="space-y-4">
                @role('Admin', 'Owner', 'Pegawai Kandang')
                <div>
                    <div class="px-4 mb-2 mt-2 text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pencatatan</div>
                    <x-sidebar-nav-item href="/pencatatan/riwayat/produksi-telur" :active="request()->is('pencatatan/riwayat/produksi-telur')">Produksi Telur</x-sidebar-nav-item>
                    <x-sidebar-nav-item href="/pencatatan/riwayat/konsumsi-pakan" :active="request()->is('pencatatan/riwayat/konsumsi-pakan')">Konsumsi Pakan</x-sidebar-nav-item>
                    <x-sidebar-nav-item href="/pencatatan/riwayat/konsumsi-vitamin" :active="request()->is('pencatatan/riwayat/konsumsi-vitamin')">Konsumsi Vitamin</x-sidebar-nav-item>
                    <x-sidebar-nav-item href="/pencatatan/riwayat/deplesi" :active="request()->is('pencatatan/riwayat/deplesi')">Deplesi</x-sidebar-nav-item>
                    <x-sidebar-nav-item href="/pencatatan/riwayat/suhu" :active="request()->is('pencatatan/riwayat/suhu')">Suhu Kandang</x-sidebar-nav-item>
                    <x-sidebar-nav-item href="/pencatatan/riwayat/pupuk" :active="request()->is('pencatatan/riwayat/pupuk')">Produksi Pupuk</x-sidebar-nav-item>
                </div>
                @endrole

            </div>

            @role('Admin', 'Owner', 'Pegawai Kandang')
            <div x-show="activeMenu === 'kandang'" x-cloak>
                <x-sidebar-nav-item href="/kandang" :active="request()->is('kandang')">Kandang</x-sidebar-nav-item>
            </div>
            <div x-show="activeMenu === 'batch'" x-cloak>
                <x-sidebar-nav-item href="/batch/performa" :active="request()->is('batch/performa')">Performa Batch Aktif</x-sidebar-nav-item>
                <x-sidebar-nav-item href="/batch/masuk" :active="request()->is('batch/masuk')">Batch Masuk</x-sidebar-nav-item>
                <x-sidebar-nav-item href="/batch" :active="request()->is('batch') && !request()->is('batch/*')">Data Batch Aktif</x-sidebar-nav-item>
                <x-sidebar-nav-item href="/batch/riwayat" :active="request()->is('batch/riwayat')">Riwayat Batch</x-sidebar-nav-item>
            </div>
            @endrole

            @role('Admin', 'Owner', 'Pegawai Gudang')
            <div x-show="activeMenu === 'buku-hutang'" x-cloak>
                <x-sidebar-nav-item href="/keuangan/buku-utang?type=aktif" :active="request('type') == 'aktif' || !request()->has('type')">Hutang Aktif</x-sidebar-nav-item>
                <x-sidebar-nav-item href="/keuangan/buku-utang?type=riwayat" :active="request('type') == 'riwayat'">Riwayat Hutang</x-sidebar-nav-item>
            </div>
            @endrole

            @role('Admin', 'Owner', 'Sales')
            <div x-show="activeMenu === 'buku-piutang'" x-cloak>
                <x-sidebar-nav-item href="/keuangan/buku-piutang?type=aktif" :active="request('type') == 'aktif' || !request()->has('type')">Piutang Aktif</x-sidebar-nav-item>
                <x-sidebar-nav-item href="/keuangan/buku-piutang?type=riwayat" :active="request('type') == 'riwayat'">Riwayat Piutang</x-sidebar-nav-item>
            </div>
            @endrole

            @role('Admin', 'Owner', 'Pegawai Gudang', 'Sales')
            <div x-show="activeMenu === 'gudang'" x-cloak>
                @role('Admin', 'Owner', 'Pegawai Gudang')
                <x-sidebar-nav-item href="/gudang/stok-konsumsi" :active="request()->is('gudang/stok-konsumsi')">Stok Konsumsi</x-sidebar-nav-item>
                @endrole
                <x-sidebar-nav-item href="/gudang/stok-produksi" :active="request()->is('gudang/stok-produksi')">Stok Hasil Produksi</x-sidebar-nav-item>
                @role('Admin', 'Owner', 'Pegawai Gudang')
                <x-sidebar-nav-item href="/gudang/riwayat-penyesuaian" :active="request()->is('gudang/riwayat-penyesuaian')">Riwayat Penyesuaian Stok</x-sidebar-nav-item>
                @endrole
            </div>
            @endrole

            @role('Admin', 'Owner', 'Pegawai Gudang', 'Sales')
            <div x-show="activeMenu === 'masterData'" x-cloak>
                @role('Admin', 'Owner')
                <x-sidebar-nav-item href="/master-data/kandang" :active="request()->is('master-data/kandang')">Data Kandang</x-sidebar-nav-item>
                <x-sidebar-nav-item href="/master-data/barang" :active="request()->is('master-data/barang')">Data Barang/Item</x-sidebar-nav-item>
                @endrole
                @role('Admin', 'Owner', 'Pegawai Gudang')
                <x-sidebar-nav-item href="/master-data/supplier" :active="request()->is('master-data/supplier')">Data Supplier</x-sidebar-nav-item>
                @endrole
                @role('Admin', 'Owner')
                <x-sidebar-nav-item href="/master-data/pegawai" :active="request()->is('master-data/pegawai')">Data Pegawai</x-sidebar-nav-item>
                @endrole
                @role('Admin', 'Owner', 'Sales')
                <x-sidebar-nav-item href="/master-data/pelanggan" :active="request()->is('master-data/pelanggan')">Data Pelanggan</x-sidebar-nav-item>
                @endrole
                @role('Admin', 'Owner')
                <x-sidebar-nav-item href="/master-data/rekening" :active="request()->is('master-data/rekening')">Data Rekening Kas/Bank</x-sidebar-nav-item>
                <x-sidebar-nav-item href="/master-data/kategori-biaya" :active="request()->is('master-data/kategori-biaya')">Data Kategori Biaya</x-sidebar-nav-item>
                @endrole
            </div>
            @endrole

            @role('Admin', 'Owner')
            <div x-show="activeMenu === 'keuangan'" x-cloak>
                <x-sidebar-nav-item href="/keuangan/biaya-operasional" :active="request()->is('keuangan/biaya-operasional')">Biaya Operasional</x-sidebar-nav-item>
                <x-sidebar-nav-item href="/keuangan/buku-kas" :active="request()->is('keuangan/buku-kas')">Buku Kas</x-sidebar-nav-item>
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
        currentPage: 'dashboard',
        menuTitle: 'Dashboard',
        isExpanded: false,
        isMobile: window.innerWidth < 1024,
        closeTimeout: null,
        
        init() {
            const path = window.location.pathname;
            
            // Aturan pemetaan URL ke menu aktif (diurutkan dari yang paling spesifik ke umum)
            const menuMapping = [
                { path: '/pencatatan/riwayat', id: 'riwayat', title: 'Riwayat' },
                { path: '/transaksi/riwayat-pembelian', id: 'penerimaan', title: 'Penerimaan Barang' },
                { path: '/transaksi/order-aktif', id: 'transaksi', title: 'Penjualan' },
                { path: '/transaksi/order-masuk', id: 'order-masuk', title: 'Order Masuk' },
                { path: '/transaksi/riwayat-penjualan', id: 'transaksi', title: 'Penjualan' },
                { path: '/transaksi/pembelian', id: 'penerimaan', title: 'Penerimaan Barang' },
                { path: '/pencatatan', id: 'pencatatan', title: 'Pencatatan Harian' },
                { path: '/transaksi', id: 'transaksi', title: 'Penjualan' },
                { path: '/batch/masuk', id: 'batch', title: 'Batch' },
                { path: '/batch/performa', id: 'batch', title: 'Batch' },
                { path: '/batch/riwayat', id: 'batch', title: 'Batch' },
                { path: '/batch/assign', id: 'batch', title: 'Batch' },
                { path: '/batch', id: 'batch', title: 'Batch' },
                { path: '/kandang', id: 'kandang', title: 'Kandang' },
                { path: '/master-data', id: 'masterData', title: 'Master Data' },
                { path: '/keuangan/buku-utang', id: 'buku-hutang', title: 'Buku Hutang' },
                { path: '/keuangan/buku-piutang', id: 'buku-piutang', title: 'Buku Piutang' },
                { path: '/keuangan', id: 'keuangan', title: 'Management Keuangan' },
                { path: '/laporan', id: 'laporan', title: 'Laporan' },
                { path: '/pengaturan', id: 'pengaturan', title: 'Pengaturan' },
                { path: '/riwayat-aktivitas', id: 'pengaturan', title: 'Pengaturan' },
                { path: '/gudang', id: 'gudang', title: 'Gudang' }
            ];

            const matchedMenu = menuMapping.find(m => path.startsWith(m.path));
            
            if (matchedMenu) {
                this.setInitialMenu(matchedMenu.id, matchedMenu.title);
            } else {
                this.setInitialMenu('dashboard', 'Dashboard');
            }

            window.addEventListener('resize', () => {
                this.isMobile = window.innerWidth < 1024;
            });
        },
        
        setInitialMenu(id, title) {
            this.activeMenu = id;
            this.currentPage = id;
            this.menuTitle = title;
        },

        handleIconHover(id, title) {
            if (this.isMobile) return;
            clearTimeout(this.closeTimeout);
            this.activeMenu = id;
            this.menuTitle = title;
            this.isExpanded = true;
        },

        handleIconClick(id, title, e) {
            const directLinks = ['dashboard', 'kandang'];
            if (this.isMobile) {
                if (this.activeMenu === id && this.isExpanded) {
                    this.isExpanded = false;
                } else {
                    this.activeMenu = id;
                    this.menuTitle = title;
                    this.isExpanded = true;
                    if (!directLinks.includes(id)) {
                        e.preventDefault();
                    }
                }
            } else {
                if (!directLinks.includes(id)) {
                    e.preventDefault();
                }
            }
        },

        handleSidebarEnter() {
            if (this.isMobile) return;
            clearTimeout(this.closeTimeout);
            this.isExpanded = true;
        },

        handleSidebarLeave() {
            if (this.isMobile) return;
            this.closeTimeout = setTimeout(() => {
                this.isExpanded = false;
                this.activeMenu = this.currentPage;
            }, 300);
        }
    }))
})
</script>

