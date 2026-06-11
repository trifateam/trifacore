<aside class="fixed left-0 top-0 h-screen w-64 bg-gradient-to-b from-gray-900 to-gray-950 text-gray-300 overflow-y-auto z-30 transition-transform duration-300 flex flex-col"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">
    
    <!-- Logo -->
    <div class="py-5 px-6 flex items-center shrink-0">
        <span class="text-xl font-bold text-white">Tri<span style="color: #ff9900;">Fa</span>Core</span>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-2 py-4 space-y-1 pb-20">
        
        <!-- Dashboard (Semua Role) -->
        <a href="/dashboard" class="flex items-center px-4 py-2.5 text-sm rounded-lg mx-2 transition-colors {{ request()->is('dashboard') ? 'text-[#ff9900] border-l-2 border-[#ff9900]' : 'text-gray-300 hover:bg-gray-800 hover:text-[#ffc800]' }}" style="{{ request()->is('dashboard') ? 'background-color: rgba(255,153,0,0.15);' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            DASHBOARD
        </a>

        @role('Admin', 'Pegawai Kandang')
        <!-- Group: Pencatatan Harian -->
        <div class="border-t border-gray-700/50 my-3 mx-2"></div>
        <div class="px-4 mx-2 mb-1">
            <span class="text-[10px] font-bold uppercase tracking-widest" style="color: #72ce27;">Pencatatan Harian</span>
        </div>
        <a href="/pencatatan/produksi-telur" class="flex items-center px-4 py-2 text-sm rounded-lg mx-2 transition-colors {{ request()->is('pencatatan/produksi-telur') ? 'text-[#ff9900] border-l-2 border-[#ff9900]' : 'text-gray-400 hover:bg-gray-800 hover:text-[#ffc800]' }}" style="{{ request()->is('pencatatan/produksi-telur') ? 'background-color: rgba(255,153,0,0.15);' : '' }}">
            <span class="w-1.5 h-1.5 rounded-full mr-3 shrink-0" style="background-color: {{ request()->is('pencatatan/produksi-telur') ? '#ff9900' : '#6b7280' }};"></span>
            Produksi Telur
        </a>
        <a href="/pencatatan/konsumsi-pakan" class="flex items-center px-4 py-2 text-sm rounded-lg mx-2 transition-colors {{ request()->is('pencatatan/konsumsi-pakan') ? 'text-[#ff9900] border-l-2 border-[#ff9900]' : 'text-gray-400 hover:bg-gray-800 hover:text-[#ffc800]' }}" style="{{ request()->is('pencatatan/konsumsi-pakan') ? 'background-color: rgba(255,153,0,0.15);' : '' }}">
            <span class="w-1.5 h-1.5 rounded-full mr-3 shrink-0" style="background-color: {{ request()->is('pencatatan/konsumsi-pakan') ? '#ff9900' : '#6b7280' }};"></span>
            Konsumsi Pakan
        </a>
        <a href="/pencatatan/konsumsi-vitamin" class="flex items-center px-4 py-2 text-sm rounded-lg mx-2 transition-colors {{ request()->is('pencatatan/konsumsi-vitamin*') ? 'text-[#ff9900] border-l-2 border-[#ff9900]' : 'text-gray-400 hover:bg-gray-800 hover:text-[#ffc800]' }}" style="{{ request()->is('pencatatan/konsumsi-vitamin*') ? 'background-color: rgba(255,153,0,0.15);' : '' }}">
            <span class="w-1.5 h-1.5 rounded-full mr-3 shrink-0" style="background-color: {{ request()->is('pencatatan/konsumsi-vitamin*') ? '#ff9900' : '#6b7280' }};"></span>
            Konsumsi Vitamin
        </a>
        <a href="/pencatatan/deplesi" class="flex items-center px-4 py-2 text-sm rounded-lg mx-2 transition-colors {{ request()->is('pencatatan/deplesi*') ? 'text-[#ff9900] border-l-2 border-[#ff9900]' : 'text-gray-400 hover:bg-gray-800 hover:text-[#ffc800]' }}" style="{{ request()->is('pencatatan/deplesi*') ? 'background-color: rgba(255,153,0,0.15);' : '' }}">
            <span class="w-1.5 h-1.5 rounded-full mr-3 shrink-0" style="background-color: {{ request()->is('pencatatan/deplesi*') ? '#ff9900' : '#6b7280' }};"></span>
            Kematian/Afkir (Deplesi)
        </a>
        <a href="/pencatatan/suhu" class="flex items-center px-4 py-2 text-sm rounded-lg mx-2 transition-colors {{ request()->is('pencatatan/suhu*') ? 'text-[#ff9900] border-l-2 border-[#ff9900]' : 'text-gray-400 hover:bg-gray-800 hover:text-[#ffc800]' }}" style="{{ request()->is('pencatatan/suhu*') ? 'background-color: rgba(255,153,0,0.15);' : '' }}">
            <span class="w-1.5 h-1.5 rounded-full mr-3 shrink-0" style="background-color: {{ request()->is('pencatatan/suhu*') ? '#ff9900' : '#6b7280' }};"></span>
            Suhu Lingkungan
        </a>
        <a href="/pencatatan/pupuk" class="flex items-center px-4 py-2 text-sm rounded-lg mx-2 transition-colors {{ request()->is('pencatatan/pupuk*') ? 'text-[#ff9900] border-l-2 border-[#ff9900]' : 'text-gray-400 hover:bg-gray-800 hover:text-[#ffc800]' }}" style="{{ request()->is('pencatatan/pupuk*') ? 'background-color: rgba(255,153,0,0.15);' : '' }}">
            <span class="w-1.5 h-1.5 rounded-full mr-3 shrink-0" style="background-color: {{ request()->is('pencatatan/pupuk*') ? '#ff9900' : '#6b7280' }};"></span>
            Produksi Pupuk
        </a>
        <a href="/pencatatan/riwayat" class="flex items-center px-4 py-2 text-sm rounded-lg mx-2 transition-colors {{ request()->is('pencatatan/riwayat*') ? 'text-[#ff9900] border-l-2 border-[#ff9900]' : 'text-gray-400 hover:bg-gray-800 hover:text-[#ffc800]' }}" style="{{ request()->is('pencatatan/riwayat*') ? 'background-color: rgba(255,153,0,0.15);' : '' }}">
            <span class="w-1.5 h-1.5 rounded-full mr-3 shrink-0" style="background-color: {{ request()->is('pencatatan/riwayat*') ? '#ff9900' : '#6b7280' }};"></span>
            Riwayat Recording
        </a>
        @endrole

        @role('Admin', 'Owner', 'Sales')
        <!-- Group: Manajemen Transaksi -->
        <div class="border-t border-gray-700/50 my-3 mx-2"></div>
        <div class="px-4 mx-2 mb-1">
            <span class="text-[10px] font-bold uppercase tracking-widest" style="color: #95e214;">Manajemen Transaksi</span>
        </div>
        <a href="/transaksi/penjualan" class="flex items-center px-4 py-2 text-sm rounded-lg mx-2 transition-colors {{ request()->is('transaksi/penjualan*') ? 'text-[#ff9900] border-l-2 border-[#ff9900]' : 'text-gray-400 hover:bg-gray-800 hover:text-[#ffc800]' }}" style="{{ request()->is('transaksi/penjualan*') ? 'background-color: rgba(255,153,0,0.15);' : '' }}">
            <span class="w-1.5 h-1.5 rounded-full mr-3 shrink-0" style="background-color: {{ request()->is('transaksi/penjualan*') ? '#ff9900' : '#6b7280' }};"></span>
            Transaksi Penjualan
        </a>
        <a href="/transaksi/pembelian" class="flex items-center px-4 py-2 text-sm rounded-lg mx-2 transition-colors {{ request()->is('transaksi/pembelian*') ? 'text-[#ff9900] border-l-2 border-[#ff9900]' : 'text-gray-400 hover:bg-gray-800 hover:text-[#ffc800]' }}" style="{{ request()->is('transaksi/pembelian*') ? 'background-color: rgba(255,153,0,0.15);' : '' }}">
            <span class="w-1.5 h-1.5 rounded-full mr-3 shrink-0" style="background-color: {{ request()->is('transaksi/pembelian*') ? '#ff9900' : '#6b7280' }};"></span>
            Transaksi Pembelian
        </a>
        <a href="/transaksi/riwayat-penjualan" class="flex items-center px-4 py-2 text-sm rounded-lg mx-2 transition-colors {{ request()->is('transaksi/riwayat-penjualan') ? 'text-[#ff9900] border-l-2 border-[#ff9900]' : 'text-gray-400 hover:bg-gray-800 hover:text-[#ffc800]' }}" style="{{ request()->is('transaksi/riwayat-penjualan') ? 'background-color: rgba(255,153,0,0.15);' : '' }}">
            <span class="w-1.5 h-1.5 rounded-full mr-3 shrink-0" style="background-color: {{ request()->is('transaksi/riwayat-penjualan') ? '#ff9900' : '#6b7280' }};"></span>
            Riwayat Penjualan
        </a>
        <a href="/transaksi/riwayat-pembelian" class="flex items-center px-4 py-2 text-sm rounded-lg mx-2 transition-colors {{ request()->is('transaksi/riwayat-pembelian') ? 'text-[#ff9900] border-l-2 border-[#ff9900]' : 'text-gray-400 hover:bg-gray-800 hover:text-[#ffc800]' }}" style="{{ request()->is('transaksi/riwayat-pembelian') ? 'background-color: rgba(255,153,0,0.15);' : '' }}">
            <span class="w-1.5 h-1.5 rounded-full mr-3 shrink-0" style="background-color: {{ request()->is('transaksi/riwayat-pembelian') ? '#ff9900' : '#6b7280' }};"></span>
            Riwayat Pembelian
        </a>
        @endrole

        @role('Admin', 'Owner')
        <!-- Group: Operasional -->
        <div class="border-t border-gray-700/50 my-3 mx-2"></div>
        <div class="px-4 mx-2 mb-1">
            <span class="text-[10px] font-bold uppercase tracking-widest" style="color: #b8f500;">Operasional</span>
        </div>
        <a href="/kandang-operasional" class="flex items-center px-4 py-2 text-sm rounded-lg mx-2 transition-colors {{ request()->is('kandang-operasional*') ? 'text-[#ff9900] border-l-2 border-[#ff9900]' : 'text-gray-400 hover:bg-gray-800 hover:text-[#ffc800]' }}" style="{{ request()->is('kandang-operasional*') ? 'background-color: rgba(255,153,0,0.15);' : '' }}">
            <span class="w-1.5 h-1.5 rounded-full mr-3 shrink-0" style="background-color: {{ request()->is('kandang-operasional*') ? '#ff9900' : '#6b7280' }};"></span>
            Kandang
        </a>
        @endrole

        @role('Admin', 'Owner', 'Pegawai Gudang')
        <a href="/gudang" class="flex items-center px-4 py-2 text-sm rounded-lg mx-2 transition-colors {{ request()->is('gudang*') ? 'text-[#ff9900] border-l-2 border-[#ff9900]' : 'text-gray-400 hover:bg-gray-800 hover:text-[#ffc800]' }}" style="{{ request()->is('gudang*') ? 'background-color: rgba(255,153,0,0.15);' : '' }}">
            <span class="w-1.5 h-1.5 rounded-full mr-3 shrink-0" style="background-color: {{ request()->is('gudang*') ? '#ff9900' : '#6b7280' }};"></span>
            Gudang
        </a>
        @endrole

        @role('Admin', 'Owner')
        <!-- Group: Master Data -->
        <div class="border-t border-gray-700/50 my-3 mx-2"></div>
        <div class="px-4 mx-2 mb-1">
            <span class="text-[10px] font-bold uppercase tracking-widest" style="color: #ffc800;">Master Data</span>
        </div>
        <a href="/master-data/kandang" class="flex items-center px-4 py-2 text-sm rounded-lg mx-2 transition-colors {{ request()->is('master-data/kandang') ? 'text-[#ff9900] border-l-2 border-[#ff9900]' : 'text-gray-400 hover:bg-gray-800 hover:text-[#ffc800]' }}" style="{{ request()->is('master-data/kandang') ? 'background-color: rgba(255,153,0,0.15);' : '' }}">
            <span class="w-1.5 h-1.5 rounded-full mr-3 shrink-0" style="background-color: {{ request()->is('master-data/kandang') ? '#ff9900' : '#6b7280' }};"></span>
            Data Kandang
        </a>
        <a href="/master-data/barang" class="flex items-center px-4 py-2 text-sm rounded-lg mx-2 transition-colors {{ request()->is('master-data/barang') ? 'text-[#ff9900] border-l-2 border-[#ff9900]' : 'text-gray-400 hover:bg-gray-800 hover:text-[#ffc800]' }}" style="{{ request()->is('master-data/barang') ? 'background-color: rgba(255,153,0,0.15);' : '' }}">
            <span class="w-1.5 h-1.5 rounded-full mr-3 shrink-0" style="background-color: {{ request()->is('master-data/barang') ? '#ff9900' : '#6b7280' }};"></span>
            Data Barang/Item
        </a>
        <a href="/master-data/supplier" class="flex items-center px-4 py-2 text-sm rounded-lg mx-2 transition-colors {{ request()->is('master-data/supplier') ? 'text-[#ff9900] border-l-2 border-[#ff9900]' : 'text-gray-400 hover:bg-gray-800 hover:text-[#ffc800]' }}" style="{{ request()->is('master-data/supplier') ? 'background-color: rgba(255,153,0,0.15);' : '' }}">
            <span class="w-1.5 h-1.5 rounded-full mr-3 shrink-0" style="background-color: {{ request()->is('master-data/supplier') ? '#ff9900' : '#6b7280' }};"></span>
            Data Supplier
        </a>
        <a href="/master-data/pegawai" class="flex items-center px-4 py-2 text-sm rounded-lg mx-2 transition-colors {{ request()->is('master-data/pegawai') ? 'text-[#ff9900] border-l-2 border-[#ff9900]' : 'text-gray-400 hover:bg-gray-800 hover:text-[#ffc800]' }}" style="{{ request()->is('master-data/pegawai') ? 'background-color: rgba(255,153,0,0.15);' : '' }}">
            <span class="w-1.5 h-1.5 rounded-full mr-3 shrink-0" style="background-color: {{ request()->is('master-data/pegawai') ? '#ff9900' : '#6b7280' }};"></span>
            Data Pegawai
        </a>
        <a href="/master-data/pelanggan" class="flex items-center px-4 py-2 text-sm rounded-lg mx-2 transition-colors {{ request()->is('master-data/pelanggan') ? 'text-[#ff9900] border-l-2 border-[#ff9900]' : 'text-gray-400 hover:bg-gray-800 hover:text-[#ffc800]' }}" style="{{ request()->is('master-data/pelanggan') ? 'background-color: rgba(255,153,0,0.15);' : '' }}">
            <span class="w-1.5 h-1.5 rounded-full mr-3 shrink-0" style="background-color: {{ request()->is('master-data/pelanggan') ? '#ff9900' : '#6b7280' }};"></span>
            Data Pelanggan
        </a>
        <a href="/master-data/rekening" class="flex items-center px-4 py-2 text-sm rounded-lg mx-2 transition-colors {{ request()->is('master-data/rekening') ? 'text-[#ff9900] border-l-2 border-[#ff9900]' : 'text-gray-400 hover:bg-gray-800 hover:text-[#ffc800]' }}" style="{{ request()->is('master-data/rekening') ? 'background-color: rgba(255,153,0,0.15);' : '' }}">
            <span class="w-1.5 h-1.5 rounded-full mr-3 shrink-0" style="background-color: {{ request()->is('master-data/rekening') ? '#ff9900' : '#6b7280' }};"></span>
            Data Rekening Kas/Bank
        </a>
        <a href="/master-data/kategori-biaya" class="flex items-center px-4 py-2 text-sm rounded-lg mx-2 transition-colors {{ request()->is('master-data/kategori-biaya') ? 'text-[#ff9900] border-l-2 border-[#ff9900]' : 'text-gray-400 hover:bg-gray-800 hover:text-[#ffc800]' }}" style="{{ request()->is('master-data/kategori-biaya') ? 'background-color: rgba(255,153,0,0.15);' : '' }}">
            <span class="w-1.5 h-1.5 rounded-full mr-3 shrink-0" style="background-color: {{ request()->is('master-data/kategori-biaya') ? '#ff9900' : '#6b7280' }};"></span>
            Data Kategori Biaya
        </a>

        <!-- Group: Management Keuangan -->
        <div class="border-t border-gray-700/50 my-3 mx-2"></div>
        <div class="px-4 mx-2 mb-1">
            <span class="text-[10px] font-bold uppercase tracking-widest" style="color: #ffe000;">Management Keuangan</span>
        </div>
        <a href="/keuangan/biaya-operasional" class="flex items-center px-4 py-2 text-sm rounded-lg mx-2 transition-colors {{ request()->is('keuangan/biaya-operasional') ? 'text-[#ff9900] border-l-2 border-[#ff9900]' : 'text-gray-400 hover:bg-gray-800 hover:text-[#ffc800]' }}" style="{{ request()->is('keuangan/biaya-operasional') ? 'background-color: rgba(255,153,0,0.15);' : '' }}">
            <span class="w-1.5 h-1.5 rounded-full mr-3 shrink-0" style="background-color: {{ request()->is('keuangan/biaya-operasional') ? '#ff9900' : '#6b7280' }};"></span>
            Biaya Operasional
        </a>
        <a href="/keuangan/buku-kas" class="flex items-center px-4 py-2 text-sm rounded-lg mx-2 transition-colors {{ request()->is('keuangan/buku-kas') ? 'text-[#ff9900] border-l-2 border-[#ff9900]' : 'text-gray-400 hover:bg-gray-800 hover:text-[#ffc800]' }}" style="{{ request()->is('keuangan/buku-kas') ? 'background-color: rgba(255,153,0,0.15);' : '' }}">
            <span class="w-1.5 h-1.5 rounded-full mr-3 shrink-0" style="background-color: {{ request()->is('keuangan/buku-kas') ? '#ff9900' : '#6b7280' }};"></span>
            Buku Kas
        </a>
        <a href="/keuangan/buku-utang" class="flex items-center px-4 py-2 text-sm rounded-lg mx-2 transition-colors {{ request()->is('keuangan/buku-utang') ? 'text-[#ff9900] border-l-2 border-[#ff9900]' : 'text-gray-400 hover:bg-gray-800 hover:text-[#ffc800]' }}" style="{{ request()->is('keuangan/buku-utang') ? 'background-color: rgba(255,153,0,0.15);' : '' }}">
            <span class="w-1.5 h-1.5 rounded-full mr-3 shrink-0" style="background-color: {{ request()->is('keuangan/buku-utang') ? '#ff9900' : '#6b7280' }};"></span>
            Buku Utang
        </a>
        <a href="/keuangan/buku-piutang" class="flex items-center px-4 py-2 text-sm rounded-lg mx-2 transition-colors {{ request()->is('keuangan/buku-piutang') ? 'text-[#ff9900] border-l-2 border-[#ff9900]' : 'text-gray-400 hover:bg-gray-800 hover:text-[#ffc800]' }}" style="{{ request()->is('keuangan/buku-piutang') ? 'background-color: rgba(255,153,0,0.15);' : '' }}">
            <span class="w-1.5 h-1.5 rounded-full mr-3 shrink-0" style="background-color: {{ request()->is('keuangan/buku-piutang') ? '#ff9900' : '#6b7280' }};"></span>
            Buku Piutang
        </a>

        <!-- Group: Laporan -->
        <div class="border-t border-gray-700/50 my-3 mx-2"></div>
        <div class="px-4 mx-2 mb-1">
            <span class="text-[10px] font-bold uppercase tracking-widest" style="color: #fff700;">Laporan</span>
        </div>
        <a href="/laporan/produksi-performa" class="flex items-center px-4 py-2 text-sm rounded-lg mx-2 transition-colors {{ request()->is('laporan/produksi-performa') ? 'text-[#ff9900] border-l-2 border-[#ff9900]' : 'text-gray-400 hover:bg-gray-800 hover:text-[#ffc800]' }}" style="{{ request()->is('laporan/produksi-performa') ? 'background-color: rgba(255,153,0,0.15);' : '' }}">
            <span class="w-1.5 h-1.5 rounded-full mr-3 shrink-0" style="background-color: {{ request()->is('laporan/produksi-performa') ? '#ff9900' : '#6b7280' }};"></span>
            Produksi & Performa
        </a>
        <a href="/laporan/laba-rugi" class="flex items-center px-4 py-2 text-sm rounded-lg mx-2 transition-colors {{ request()->is('laporan/laba-rugi') ? 'text-[#ff9900] border-l-2 border-[#ff9900]' : 'text-gray-400 hover:bg-gray-800 hover:text-[#ffc800]' }}" style="{{ request()->is('laporan/laba-rugi') ? 'background-color: rgba(255,153,0,0.15);' : '' }}">
            <span class="w-1.5 h-1.5 rounded-full mr-3 shrink-0" style="background-color: {{ request()->is('laporan/laba-rugi') ? '#ff9900' : '#6b7280' }};"></span>
            Laporan Laba Rugi
        </a>
        <a href="/laporan/cetak/produksi-telur" class="flex items-center px-4 py-2 text-sm rounded-lg mx-2 transition-colors {{ request()->is('laporan/cetak/produksi-telur') ? 'text-[#ff9900] border-l-2 border-[#ff9900]' : 'text-gray-400 hover:bg-gray-800 hover:text-[#ffc800]' }}" style="{{ request()->is('laporan/cetak/produksi-telur') ? 'background-color: rgba(255,153,0,0.15);' : '' }}">
            <span class="w-1.5 h-1.5 rounded-full mr-3 shrink-0" style="background-color: {{ request()->is('laporan/cetak/produksi-telur') ? '#ff9900' : '#6b7280' }};"></span>
            Cetak Produksi Telur
        </a>
        <a href="/laporan/cetak/penjualan-telur" class="flex items-center px-4 py-2 text-sm rounded-lg mx-2 transition-colors {{ request()->is('laporan/cetak/penjualan-telur') ? 'text-[#ff9900] border-l-2 border-[#ff9900]' : 'text-gray-400 hover:bg-gray-800 hover:text-[#ffc800]' }}" style="{{ request()->is('laporan/cetak/penjualan-telur') ? 'background-color: rgba(255,153,0,0.15);' : '' }}">
            <span class="w-1.5 h-1.5 rounded-full mr-3 shrink-0" style="background-color: {{ request()->is('laporan/cetak/penjualan-telur') ? '#ff9900' : '#6b7280' }};"></span>
            Cetak Penjualan Telur
        </a>
        <a href="/laporan/cetak/pembelian-pakan" class="flex items-center px-4 py-2 text-sm rounded-lg mx-2 transition-colors {{ request()->is('laporan/cetak/pembelian-pakan') ? 'text-[#ff9900] border-l-2 border-[#ff9900]' : 'text-gray-400 hover:bg-gray-800 hover:text-[#ffc800]' }}" style="{{ request()->is('laporan/cetak/pembelian-pakan') ? 'background-color: rgba(255,153,0,0.15);' : '' }}">
            <span class="w-1.5 h-1.5 rounded-full mr-3 shrink-0" style="background-color: {{ request()->is('laporan/cetak/pembelian-pakan') ? '#ff9900' : '#6b7280' }};"></span>
            Cetak Pembelian Pakan
        </a>

        <!-- Group: Pengaturan -->
        <div class="border-t border-gray-700/50 my-3 mx-2"></div>
        <div class="px-4 mx-2 mb-1">
            <span class="text-[10px] font-bold uppercase tracking-widest" style="color: #ff9900;">Pengaturan</span>
        </div>
        <a href="/pengaturan/profil-sistem" class="flex items-center px-4 py-2 text-sm rounded-lg mx-2 transition-colors {{ request()->is('pengaturan/profil-sistem') ? 'text-[#ff9900] border-l-2 border-[#ff9900]' : 'text-gray-400 hover:bg-gray-800 hover:text-[#ffc800]' }}" style="{{ request()->is('pengaturan/profil-sistem') ? 'background-color: rgba(255,153,0,0.15);' : '' }}">
            <span class="w-1.5 h-1.5 rounded-full mr-3 shrink-0" style="background-color: {{ request()->is('pengaturan/profil-sistem') ? '#ff9900' : '#6b7280' }};"></span>
            Profil & Sistem
        </a>
        <a href="/riwayat-aktivitas" class="flex items-center px-4 py-2 text-sm rounded-lg mx-2 transition-colors {{ request()->is('riwayat-aktivitas') ? 'text-[#ff9900] border-l-2 border-[#ff9900]' : 'text-gray-400 hover:bg-gray-800 hover:text-[#ffc800]' }}" style="{{ request()->is('riwayat-aktivitas') ? 'background-color: rgba(255,153,0,0.15);' : '' }}">
            <span class="w-1.5 h-1.5 rounded-full mr-3 shrink-0" style="background-color: {{ request()->is('riwayat-aktivitas') ? '#ff9900' : '#6b7280' }};"></span>
            Riwayat Aktivitas Sistem
        </a>
        @endrole

    </nav>
</aside>
