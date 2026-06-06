<aside class="fixed left-0 top-0 h-screen w-64 bg-gray-900 text-gray-300 overflow-y-auto z-30 transition-transform duration-300 flex flex-col"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">
    
    <!-- Logo -->
    <div class="text-xl font-bold text-white py-5 px-6 flex items-center shrink-0">
        TriFaCore
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-2 py-4 space-y-1 pb-20">
        
        <!-- Dashboard (Semua Role) -->
        <a href="/dashboard" class="flex items-center px-4 py-2.5 text-sm rounded-lg mx-2 transition-colors {{ request()->is('dashboard') ? 'bg-indigo-600/20 text-indigo-400 border-r-2 border-indigo-500' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            DASHBOARD
        </a>

        @role('Admin', 'Pegawai Kandang')
        <div class="border-t border-gray-700 my-2 mx-2"></div>
        <!-- Pencatatan Harian (Admin, Pegawai Kandang) -->
        <div x-data="{ open: {{ request()->is('pencatatan*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 text-sm text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg mx-2 transition-colors">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    PENCATATAN HARIAN
                </div>
                <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="open" x-collapse style="display: none;">
                <div class="py-1 space-y-1">
                    <a href="/pencatatan/produksi-telur" class="block pl-12 pr-4 py-2 text-sm {{ request()->is('pencatatan/produksi-telur') ? 'text-indigo-400' : 'text-gray-400 hover:text-white' }}">Produksi Telur</a>
                    <a href="/pencatatan/konsumsi-pakan" class="block pl-12 pr-4 py-2 text-sm {{ request()->is('pencatatan/konsumsi-pakan') ? 'text-indigo-400' : 'text-gray-400 hover:text-white' }}">Konsumsi Pakan</a>
                    <a href="/pencatatan/konsumsi-vitamin" class="block pl-12 pr-4 py-2 text-sm {{ request()->is('pencatatan/konsumsi-vitamin*') ? 'text-indigo-400' : 'text-gray-400 hover:text-white' }}">Konsumsi Vitamin</a>
                    <a href="/pencatatan/deplesi" class="block pl-12 pr-4 py-2 text-sm {{ request()->is('pencatatan/deplesi') ? 'text-indigo-400' : 'text-gray-400 hover:text-white' }}">Kematian/Afkir (Deplesi)</a>
                    <a href="/pencatatan/suhu" class="block pl-12 pr-4 py-2 text-sm {{ request()->is('pencatatan/suhu') ? 'text-indigo-400' : 'text-gray-400 hover:text-white' }}">Suhu Lingkungan</a>
                    <a href="/pencatatan/pupuk" class="block pl-12 pr-4 py-2 text-sm {{ request()->is('pencatatan/pupuk') ? 'text-indigo-400' : 'text-gray-400 hover:text-white' }}">Produksi Pupuk</a>
                    <a href="/pencatatan/riwayat" class="block pl-12 pr-4 py-2 text-sm {{ request()->is('pencatatan/riwayat') ? 'text-indigo-400' : 'text-gray-400 hover:text-white' }}">Riwayat Recording</a>
                </div>
            </div>
        </div>
        @endrole

        @role('Admin', 'Owner', 'Sales')
        <div class="border-t border-gray-700 my-2 mx-2"></div>
        <!-- Manajemen Transaksi (Admin, Owner, Sales) -->
        <div x-data="{ open: {{ request()->is('transaksi*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 text-sm text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg mx-2 transition-colors">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    MANAJEMEN TRANSAKSI
                </div>
                <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="open" x-collapse style="display: none;">
                <div class="py-1 space-y-1">
                    <a href="/transaksi/penjualan" class="block pl-12 pr-4 py-2 text-sm {{ request()->is('transaksi/penjualan') ? 'text-indigo-400' : 'text-gray-400 hover:text-white' }}">Transaksi Penjualan</a>
                    <a href="/transaksi/pembelian" class="block pl-12 pr-4 py-2 text-sm {{ request()->is('transaksi/pembelian') ? 'text-indigo-400' : 'text-gray-400 hover:text-white' }}">Transaksi Pembelian</a>
                    <a href="/transaksi/riwayat-penjualan" class="block pl-12 pr-4 py-2 text-sm {{ request()->is('transaksi/riwayat-penjualan') ? 'text-indigo-400' : 'text-gray-400 hover:text-white' }}">Riwayat Penjualan</a>
                    <a href="/transaksi/riwayat-pembelian" class="block pl-12 pr-4 py-2 text-sm {{ request()->is('transaksi/riwayat-pembelian') ? 'text-indigo-400' : 'text-gray-400 hover:text-white' }}">Riwayat Pembelian</a>
                </div>
            </div>
        </div>
        @endrole

        @role('Admin', 'Owner')
        <div class="border-t border-gray-700 my-2 mx-2"></div>
        <!-- Kandang Operasional (Admin, Owner) -->
        <a href="/kandang-operasional" class="flex items-center px-4 py-2.5 text-sm rounded-lg mx-2 transition-colors {{ request()->is('kandang-operasional*') ? 'bg-indigo-600/20 text-indigo-400 border-r-2 border-indigo-500' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            KANDANG
        </a>
        @endrole

        @role('Admin', 'Owner', 'Pegawai Gudang')
        <div class="border-t border-gray-700 my-2 mx-2"></div>
        <!-- Gudang (Admin, Owner, Pegawai Gudang) -->
        <a href="/gudang" class="flex items-center px-4 py-2.5 text-sm rounded-lg mx-2 transition-colors {{ request()->is('gudang*') ? 'bg-indigo-600/20 text-indigo-400 border-r-2 border-indigo-500' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
            GUDANG
        </a>
        @endrole

        @role('Admin', 'Owner')
        <div class="border-t border-gray-700 my-2 mx-2"></div>
        <!-- Master Data (Admin, Owner) -->
        <div x-data="{ open: {{ request()->is('master-data*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 text-sm text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg mx-2 transition-colors">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                    MASTER DATA
                </div>
                <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="open" x-collapse style="display: none;">
                <div class="py-1 space-y-1">
                    <a href="/master-data/kandang" class="block pl-12 pr-4 py-2 text-sm {{ request()->is('master-data/kandang') ? 'text-indigo-400' : 'text-gray-400 hover:text-white' }}">Data Kandang</a>
                    <a href="/master-data/barang" class="block pl-12 pr-4 py-2 text-sm {{ request()->is('master-data/barang') ? 'text-indigo-400' : 'text-gray-400 hover:text-white' }}">Data Barang/Item</a>
                    <a href="/master-data/supplier" class="block pl-12 pr-4 py-2 text-sm {{ request()->is('master-data/supplier') ? 'text-indigo-400' : 'text-gray-400 hover:text-white' }}">Data Supplier</a>
                    <a href="/master-data/pegawai" class="block pl-12 pr-4 py-2 text-sm {{ request()->is('master-data/pegawai') ? 'text-indigo-400' : 'text-gray-400 hover:text-white' }}">Data Pegawai</a>
                    <a href="/master-data/pelanggan" class="block pl-12 pr-4 py-2 text-sm {{ request()->is('master-data/pelanggan') ? 'text-indigo-400' : 'text-gray-400 hover:text-white' }}">Data Pelanggan</a>
                    <a href="/master-data/rekening" class="block pl-12 pr-4 py-2 text-sm {{ request()->is('master-data/rekening') ? 'text-indigo-400' : 'text-gray-400 hover:text-white' }}">Data Rekening Kas/Bank</a>
                    <a href="/master-data/kategori-biaya" class="block pl-12 pr-4 py-2 text-sm {{ request()->is('master-data/kategori-biaya') ? 'text-indigo-400' : 'text-gray-400 hover:text-white' }}">Data Kategori Biaya</a>
                </div>
            </div>
        </div>

        <!-- Management Keuangan (Admin, Owner) -->
        <div class="border-t border-gray-700 my-2 mx-2"></div>
        <div x-data="{ open: {{ request()->is('keuangan*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 text-sm text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg mx-2 transition-colors">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    MANAGEMENT KEUANGAN
                </div>
                <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="open" x-collapse style="display: none;">
                <div class="py-1 space-y-1">
                    <a href="/keuangan/biaya-operasional" class="block pl-12 pr-4 py-2 text-sm {{ request()->is('keuangan/biaya-operasional') ? 'text-indigo-400' : 'text-gray-400 hover:text-white' }}">Biaya Operasional</a>
                    <a href="/keuangan/buku-kas" class="block pl-12 pr-4 py-2 text-sm {{ request()->is('keuangan/buku-kas') ? 'text-indigo-400' : 'text-gray-400 hover:text-white' }}">Buku Kas</a>
                    <a href="/keuangan/buku-utang" class="block pl-12 pr-4 py-2 text-sm {{ request()->is('keuangan/buku-utang') ? 'text-indigo-400' : 'text-gray-400 hover:text-white' }}">Buku Utang</a>
                    <a href="/keuangan/buku-piutang" class="block pl-12 pr-4 py-2 text-sm {{ request()->is('keuangan/buku-piutang') ? 'text-indigo-400' : 'text-gray-400 hover:text-white' }}">Buku Piutang</a>
                </div>
            </div>
        </div>

        <!-- Laporan (Admin, Owner) -->
        <div class="border-t border-gray-700 my-2 mx-2"></div>
        <div x-data="{ open: {{ request()->is('laporan*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 text-sm text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg mx-2 transition-colors">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    LAPORAN
                </div>
                <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="open" x-collapse style="display: none;">
                <div class="py-1 space-y-1">
                    <a href="/laporan/produksi-performa" class="block pl-12 pr-4 py-2 text-sm {{ request()->is('laporan/produksi-performa') ? 'text-indigo-400' : 'text-gray-400 hover:text-white' }}">Produksi & Performa</a>
                    <a href="/laporan/laba-rugi" class="block pl-12 pr-4 py-2 text-sm {{ request()->is('laporan/laba-rugi') ? 'text-indigo-400' : 'text-gray-400 hover:text-white' }}">Laporan Laba Rugi</a>
                    <a href="/laporan/cetak/produksi-telur" class="block pl-12 pr-4 py-2 text-sm {{ request()->is('laporan/cetak/produksi-telur') ? 'text-indigo-400' : 'text-gray-400 hover:text-white' }}">Cetak Produksi Telur</a>
                    <a href="/laporan/cetak/penjualan-telur" class="block pl-12 pr-4 py-2 text-sm {{ request()->is('laporan/cetak/penjualan-telur') ? 'text-indigo-400' : 'text-gray-400 hover:text-white' }}">Cetak Penjualan Telur</a>
                    <a href="/laporan/cetak/pembelian-pakan" class="block pl-12 pr-4 py-2 text-sm {{ request()->is('laporan/cetak/pembelian-pakan') ? 'text-indigo-400' : 'text-gray-400 hover:text-white' }}">Cetak Pembelian Pakan</a>
                </div>
            </div>
        </div>

        <!-- Pengaturan (Admin, Owner) -->
        <div class="border-t border-gray-700 my-2 mx-2"></div>
        <div x-data="{ open: {{ request()->is('pengaturan*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 text-sm text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg mx-2 transition-colors">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    PENGATURAN
                </div>
                <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="open" x-collapse style="display: none;">
                <div class="py-1 space-y-1">
                    <a href="/pengaturan/profil-sistem" class="block pl-12 pr-4 py-2 text-sm {{ request()->is('pengaturan/profil-sistem') ? 'text-indigo-400' : 'text-gray-400 hover:text-white' }}">Profil & Sistem</a>
                </div>
            </div>
        </div>
        @endrole

    </nav>
</aside>
