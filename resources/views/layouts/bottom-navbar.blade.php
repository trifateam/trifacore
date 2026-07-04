{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- MOBILE BOTTOM NAVBAR                                        --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<div x-data="mobileNav()" class="md:hidden">
    {{-- Slide-up submenu panel --}}
    <div x-show="panelOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-y-full"
         x-transition:enter-end="translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-y-0"
         x-transition:leave-end="translate-y-full"
         @click.away="panelOpen = false"
         class="fixed bottom-16 left-0 right-0 z-40 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 rounded-t-2xl shadow-2xl max-h-[60vh] overflow-y-auto"
         style="display: none;">
        
        <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100 dark:border-gray-700 sticky top-0 bg-white dark:bg-gray-800 z-10">
            <h3 class="text-sm font-bold text-gray-900 dark:text-white" x-text="panelTitle"></h3>
            <button @click="panelOpen = false" class="p-1 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <nav class="px-3 py-3 space-y-1">
            {{-- Pencatatan Submenus --}}
            @role('Pegawai Kandang')
            <template x-if="activePanel === 'pencatatan'">
                <div class="space-y-1">
                    <a href="/pencatatan/produksi-telur" class="mobile-submenu-item relative {{ request()->is('pencatatan/produksi-telur*') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-indigo-400 shrink-0"></span>Produksi Telur <x-badge-notification :show="isset($uncompletedTasks['telur']) && count($uncompletedTasks['telur']) > 0" class="top-1/2 -translate-y-1/2 right-4" />
                    </a>
                    <a href="/pencatatan/konsumsi-pakan" class="mobile-submenu-item relative {{ request()->is('pencatatan/konsumsi-pakan*') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-indigo-400 shrink-0"></span>Konsumsi Pakan <x-badge-notification :show="isset($uncompletedTasks['pakan']) && count($uncompletedTasks['pakan']) > 0" class="top-1/2 -translate-y-1/2 right-4" />
                    </a>
                    <a href="/pencatatan/konsumsi-vitamin" class="mobile-submenu-item relative {{ request()->is('pencatatan/konsumsi-vitamin*') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-indigo-400 shrink-0"></span>Konsumsi Vitamin
                    </a>
                    <a href="/pencatatan/deplesi" class="mobile-submenu-item relative {{ request()->is('pencatatan/deplesi*') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-indigo-400 shrink-0"></span>Kematian/Afkir (Deplesi)
                    </a>
                    <a href="/pencatatan/suhu" class="mobile-submenu-item relative {{ request()->is('pencatatan/suhu*') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-indigo-400 shrink-0"></span>Suhu Lingkungan <x-badge-notification :show="isset($uncompletedTasks['suhu']) && count($uncompletedTasks['suhu']) > 0" class="top-1/2 -translate-y-1/2 right-4" />
                    </a>
                    <a href="/pencatatan/pupuk" class="mobile-submenu-item relative {{ request()->is('pencatatan/pupuk*') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-indigo-400 shrink-0"></span>Produksi Pupuk
                    </a>
                </div>
            </template>
            @endrole

            {{-- Transaksi Submenus --}}
            @role('Sales')
            <template x-if="activePanel === 'transaksi'">
                <div class="space-y-1">
                    <a href="/transaksi/penjualan" class="mobile-submenu-item {{ request()->is('transaksi/penjualan*') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-blue-400 shrink-0"></span>Input Penjualan
                    </a>
                    <a href="/transaksi/order-aktif" class="mobile-submenu-item {{ request()->is('transaksi/order-aktif*') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-yellow-400 shrink-0"></span>Order Aktif
                    </a>
                    <a href="/transaksi/riwayat-penjualan" class="mobile-submenu-item {{ request()->is('transaksi/riwayat-penjualan*') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-blue-400 shrink-0"></span>Riwayat Penjualan
                    </a>
                </div>
            </template>
            @endrole

            {{-- Penerimaan Barang Submenus --}}
            @role('Pegawai Gudang')
            <template x-if="activePanel === 'penerimaan'">
                <div class="space-y-1">
                    <a href="/transaksi/pembelian" class="mobile-submenu-item {{ request()->is('transaksi/pembelian*') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-blue-400 shrink-0"></span>Input Penerimaan Barang
                    </a>
                    <a href="/transaksi/riwayat-pembelian" class="mobile-submenu-item {{ request()->is('transaksi/riwayat-pembelian*') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-blue-400 shrink-0"></span>Riwayat Penerimaan Barang
                    </a>
                </div>
            </template>
            @endrole

            {{-- Riwayat Submenus --}}
            <template x-if="activePanel === 'riwayat'">
                <div class="space-y-1">
                    @role('Admin', 'Owner', 'Pegawai Kandang')
                    <div class="px-3 pt-1 pb-1 text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Pencatatan</div>
                    <a href="/pencatatan/riwayat/produksi-telur" class="mobile-submenu-item {{ request()->is('pencatatan/riwayat/produksi-telur') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-violet-400 shrink-0"></span>Produksi Telur
                    </a>
                    <a href="/pencatatan/riwayat/konsumsi-pakan" class="mobile-submenu-item {{ request()->is('pencatatan/riwayat/konsumsi-pakan') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-violet-400 shrink-0"></span>Konsumsi Pakan
                    </a>
                    <a href="/pencatatan/riwayat/konsumsi-vitamin" class="mobile-submenu-item {{ request()->is('pencatatan/riwayat/konsumsi-vitamin') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-violet-400 shrink-0"></span>Konsumsi Vitamin
                    </a>
                    <a href="/pencatatan/riwayat/deplesi" class="mobile-submenu-item {{ request()->is('pencatatan/riwayat/deplesi') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-violet-400 shrink-0"></span>Deplesi
                    </a>
                    <a href="/pencatatan/riwayat/suhu" class="mobile-submenu-item {{ request()->is('pencatatan/riwayat/suhu') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-violet-400 shrink-0"></span>Suhu Kandang
                    </a>
                    <a href="/pencatatan/riwayat/pupuk" class="mobile-submenu-item {{ request()->is('pencatatan/riwayat/pupuk') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-violet-400 shrink-0"></span>Produksi Pupuk
                    </a>
                    @endrole

                </div>
            </template>

            @role('Admin', 'Owner', 'Pegawai Kandang')
            <template x-if="activePanel === 'kandang'">
                <div class="space-y-1">
                    <a href="/kandang" class="mobile-submenu-item {{ request()->is('kandang') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-purple-400 shrink-0"></span>Kandang
                    </a>
                </div>
            </template>
            @endrole

            {{-- Batch Submenus (For Admin/Owner) --}}
            @role('Admin', 'Owner')
            <template x-if="activePanel === 'batch'">
                <div class="space-y-1">
                    <a href="/batch/performa" class="mobile-submenu-item {{ request()->is('batch/performa') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-purple-400 shrink-0"></span>Performa Batch Aktif
                    </a>
                    <a href="/batch/masuk" class="mobile-submenu-item {{ request()->is('batch/masuk') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-purple-400 shrink-0"></span>Batch Masuk
                    </a>
                    <a href="/batch" class="mobile-submenu-item {{ request()->is('batch') && !request()->is('batch/*') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-purple-400 shrink-0"></span>Data Batch Aktif
                    </a>
                    <a href="/batch/riwayat" class="mobile-submenu-item {{ request()->is('batch/riwayat') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-purple-400 shrink-0"></span>Riwayat Batch
                    </a>
                </div>
            </template>
            @endrole

            @role('Admin', 'Owner', 'Pegawai Gudang')
            <template x-if="activePanel === 'gudang'">
                <div class="space-y-1">
                    <a href="/gudang/stok-konsumsi" class="mobile-submenu-item {{ request()->is('gudang/stok-konsumsi') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-indigo-400 shrink-0"></span>Stok Konsumsi
                    </a>
                    <a href="/gudang/stok-produksi" class="mobile-submenu-item {{ request()->is('gudang/stok-produksi') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-indigo-400 shrink-0"></span>Stok Hasil Produksi
                    </a>
                    <a href="/gudang/riwayat-penyesuaian" class="mobile-submenu-item {{ request()->is('gudang/riwayat-penyesuaian') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-indigo-400 shrink-0"></span>Riwayat Penyesuaian Stok
                    </a>
                </div>
            </template>
            @endrole

            {{-- ═══════════════════════════════════════════════════════════ --}}
            {{-- PANEL: LAINNYA (Menu Sisa Sesuai Role)                      --}}
            {{-- ═══════════════════════════════════════════════════════════ --}}
            @role('Admin', 'Owner', 'Pegawai Kandang', 'Pegawai Gudang')
            <template x-if="activePanel === 'lainnya'">
                <div class="space-y-4 pb-4">
                    
                    {{-- Pegawai Kandang: Batch --}}
                    @role('Pegawai Kandang')
                    <div class="space-y-1">
                        <div class="px-3 pt-2 pb-1 text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Batch</div>
                        <a href="/batch/performa" class="mobile-submenu-item {{ request()->is('batch/performa') ? 'mobile-submenu-active' : '' }}">
                            <span class="w-2 h-2 rounded-full bg-purple-400 shrink-0"></span>Performa Batch Aktif
                        </a>
                        <a href="/batch/masuk" class="mobile-submenu-item {{ request()->is('batch/masuk') ? 'mobile-submenu-active' : '' }}">
                            <span class="w-2 h-2 rounded-full bg-purple-400 shrink-0"></span>Batch Masuk
                        </a>
                        <a href="/batch" class="mobile-submenu-item {{ request()->is('batch') && !request()->is('batch/*') ? 'mobile-submenu-active' : '' }}">
                            <span class="w-2 h-2 rounded-full bg-purple-400 shrink-0"></span>Data Batch Aktif
                        </a>
                        <a href="/batch/riwayat" class="mobile-submenu-item {{ request()->is('batch/riwayat') ? 'mobile-submenu-active' : '' }}">
                            <span class="w-2 h-2 rounded-full bg-purple-400 shrink-0"></span>Riwayat Batch
                        </a>
                    </div>
                    @endrole

                    {{-- Admin / Owner: Gudang --}}
                    @role('Admin', 'Owner')
                    <div class="space-y-1">
                        <div class="px-3 pt-2 pb-1 text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Gudang</div>
                        <a href="/gudang/stok-konsumsi" class="mobile-submenu-item {{ request()->is('gudang/stok-konsumsi') ? 'mobile-submenu-active' : '' }}">
                            <span class="w-2 h-2 rounded-full bg-indigo-400 shrink-0"></span>Stok Konsumsi
                        </a>
                        <a href="/gudang/stok-produksi" class="mobile-submenu-item {{ request()->is('gudang/stok-produksi') ? 'mobile-submenu-active' : '' }}">
                            <span class="w-2 h-2 rounded-full bg-indigo-400 shrink-0"></span>Stok Hasil Produksi
                        </a>
                        <a href="/gudang/riwayat-penyesuaian" class="mobile-submenu-item {{ request()->is('gudang/riwayat-penyesuaian') ? 'mobile-submenu-active' : '' }}">
                            <span class="w-2 h-2 rounded-full bg-indigo-400 shrink-0"></span>Riwayat Penyesuaian Stok
                        </a>
                    </div>
                    @endrole

                    {{-- Admin / Owner / Pegawai Gudang / Sales: Master Data --}}
                    @role('Admin', 'Owner', 'Pegawai Gudang', 'Sales')
                    <div class="space-y-1">
                        <div class="px-3 pt-2 pb-1 text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Master Data</div>
                        @role('Admin', 'Owner')
                        <a href="/master-data/kandang" class="mobile-submenu-item {{ request()->is('master-data/kandang') ? 'mobile-submenu-active' : '' }}">
                            <span class="w-2 h-2 rounded-full bg-amber-400 shrink-0"></span>Data Kandang
                        </a>
                        @role('Admin', 'Owner', 'Sales')
                        <a href="/master-data/barang" class="mobile-submenu-item {{ request()->is('master-data/barang') ? 'mobile-submenu-active' : '' }}">
                            <span class="w-2 h-2 rounded-full bg-amber-400 shrink-0"></span>Data Barang/Item
                        </a>
                        @endrole
                        @role('Admin', 'Owner', 'Pegawai Gudang')
                        <a href="/master-data/supplier" class="mobile-submenu-item {{ request()->is('master-data/supplier') ? 'mobile-submenu-active' : '' }}">
                            <span class="w-2 h-2 rounded-full bg-amber-400 shrink-0"></span>Data Supplier
                        </a>
                        @endrole
                        @role('Admin', 'Owner')
                        <a href="/master-data/pegawai" class="mobile-submenu-item {{ request()->is('master-data/pegawai') ? 'mobile-submenu-active' : '' }}">
                            <span class="w-2 h-2 rounded-full bg-amber-400 shrink-0"></span>Data Pegawai
                        </a>
                        @endrole
                        @role('Admin', 'Owner', 'Sales')
                        <a href="/master-data/pelanggan" class="mobile-submenu-item {{ request()->is('master-data/pelanggan') ? 'mobile-submenu-active' : '' }}">
                            <span class="w-2 h-2 rounded-full bg-amber-400 shrink-0"></span>Data Pelanggan
                        </a>
                        @endrole
                        @role('Admin', 'Owner')
                        <a href="/master-data/rekening" class="mobile-submenu-item {{ request()->is('master-data/rekening') ? 'mobile-submenu-active' : '' }}">
                            <span class="w-2 h-2 rounded-full bg-amber-400 shrink-0"></span>Data Rekening Kas/Bank
                        </a>
                        <a href="/master-data/kategori-biaya" class="mobile-submenu-item {{ request()->is('master-data/kategori-biaya') ? 'mobile-submenu-active' : '' }}">
                            <span class="w-2 h-2 rounded-full bg-amber-400 shrink-0"></span>Data Kategori Biaya
                        </a>
                        @endrole
                    </div>
                    @endrole

                    {{-- Admin / Owner: Keuangan --}}
                    @role('Admin', 'Owner')
                    <div class="space-y-1">
                        <div class="px-3 pt-2 pb-1 text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Management Keuangan</div>
                        <a href="/keuangan/biaya-operasional" class="mobile-submenu-item {{ request()->is('keuangan/biaya-operasional') ? 'mobile-submenu-active' : '' }}">
                            <span class="w-2 h-2 rounded-full bg-cyan-400 shrink-0"></span>Biaya Operasional
                        </a>
                        <a href="/keuangan/buku-kas" class="mobile-submenu-item {{ request()->is('keuangan/buku-kas') ? 'mobile-submenu-active' : '' }}">
                            <span class="w-2 h-2 rounded-full bg-cyan-400 shrink-0"></span>Buku Kas
                        </a>
                    </div>
                    @endrole

                    {{-- Admin / Owner / Pegawai Gudang: Hutang --}}
                    @role('Admin', 'Owner', 'Pegawai Gudang')
                    <div class="space-y-1">
                        <div class="px-3 pt-2 pb-1 text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Hutang</div>
                        <a href="/keuangan/buku-utang?type=aktif" class="mobile-submenu-item {{ request()->is('keuangan/buku-utang') && (request('type') == 'aktif' || !request()->has('type')) ? 'mobile-submenu-active' : '' }}">
                            <span class="w-2 h-2 rounded-full bg-red-400 shrink-0"></span>Hutang Aktif
                        </a>
                        <a href="/keuangan/buku-utang?type=riwayat" class="mobile-submenu-item {{ request()->is('keuangan/buku-utang') && request('type') == 'riwayat' ? 'mobile-submenu-active' : '' }}">
                            <span class="w-2 h-2 rounded-full bg-red-400 shrink-0"></span>Riwayat Hutang
                        </a>
                    </div>
                    @endrole

                    {{-- Admin / Owner / Sales: Piutang --}}
                    @role('Admin', 'Owner', 'Sales')
                    <div class="space-y-1">
                        <div class="px-3 pt-2 pb-1 text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Piutang</div>
                        <a href="/keuangan/buku-piutang?type=aktif" class="mobile-submenu-item {{ request()->is('keuangan/buku-piutang') && (request('type') == 'aktif' || !request()->has('type')) ? 'mobile-submenu-active' : '' }}">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 shrink-0"></span>Piutang Aktif
                        </a>
                        <a href="/keuangan/buku-piutang?type=riwayat" class="mobile-submenu-item {{ request()->is('keuangan/buku-piutang') && request('type') == 'riwayat' ? 'mobile-submenu-active' : '' }}">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 shrink-0"></span>Riwayat Piutang
                        </a>
                    </div>
                    @endrole

                    {{-- Sales: Gudang --}}
                    @role('Sales')
                    <div class="space-y-1">
                        <div class="px-3 pt-2 pb-1 text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Gudang</div>
                        <a href="/gudang/stok-produksi" class="mobile-submenu-item {{ request()->is('gudang/stok-produksi') ? 'mobile-submenu-active' : '' }}">
                            <span class="w-2 h-2 rounded-full bg-blue-400 shrink-0"></span>Stok Hasil Produksi
                        </a>
                    </div>
                    @endrole


                    {{-- Admin / Owner: Laporan --}}
                    <div class="space-y-1">
                        <div class="px-3 pt-2 pb-1 text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Laporan</div>
                        <a href="/laporan/produksi-performa" class="mobile-submenu-item {{ request()->is('laporan/produksi-performa') ? 'mobile-submenu-active' : '' }}">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 shrink-0"></span>Produksi & Performa
                        </a>
                        <a href="/laporan/laba-rugi" class="mobile-submenu-item {{ request()->is('laporan/laba-rugi') ? 'mobile-submenu-active' : '' }}">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 shrink-0"></span>Laporan Laba Rugi
                        </a>
                        <div class="px-3 mb-1 mt-2 text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Cetak</div>
                        <a href="/laporan/cetak/produksi-telur" class="mobile-submenu-item {{ request()->is('laporan/cetak/produksi-telur') ? 'mobile-submenu-active' : '' }}">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 shrink-0"></span>Cetak Produksi Telur
                        </a>
                        <a href="/laporan/cetak/penjualan-telur" class="mobile-submenu-item {{ request()->is('laporan/cetak/penjualan-telur') ? 'mobile-submenu-active' : '' }}">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 shrink-0"></span>Cetak Penjualan Telur
                        </a>
                        <a href="/laporan/cetak/pembelian-pakan" class="mobile-submenu-item {{ request()->is('laporan/cetak/pembelian-pakan') ? 'mobile-submenu-active' : '' }}">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 shrink-0"></span>Cetak Pembelian Pakan
                        </a>
                    </div>

                    {{-- Admin / Owner: Pengaturan --}}
                    <div class="space-y-1">
                        <div class="px-3 pt-2 pb-1 text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Pengaturan</div>
                        <a href="/pengaturan/profil-sistem" class="mobile-submenu-item {{ request()->is('pengaturan/profil-sistem') ? 'mobile-submenu-active' : '' }}">
                            <span class="w-2 h-2 rounded-full bg-red-400 shrink-0"></span>Profil & Sistem
                        </a>
                        <a href="/riwayat-aktivitas" class="mobile-submenu-item {{ request()->is('riwayat-aktivitas') ? 'mobile-submenu-active' : '' }}">
                            <span class="w-2 h-2 rounded-full bg-red-400 shrink-0"></span>Riwayat Aktivitas Sistem
                        </a>
                    </div>
                    @endrole
                </div>
            </template>
            @endrole
        </nav>
    </div>

    {{-- Backdrop overlay --}}
    <div x-show="panelOpen"
         x-transition:enter="transition-opacity ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="panelOpen = false"
         class="fixed inset-0 z-30 bg-black/30"
         style="display: none;"></div>

    {{-- Bottom Navigation Bar --}}
    <nav class="fixed bottom-0 left-0 right-0 z-50 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 mobile-bottom-nav">
        <div class="flex items-center justify-around h-16 px-2">

            {{-- 1. Dashboard (All roles) --}}
            <a href="/dashboard" class="mobile-nav-btn group" id="mobile-nav-dashboard">
                <div class="mobile-nav-icon {{ request()->is('dashboard') ? 'mobile-nav-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                </div>
                <span class="mobile-nav-label">Home</span>
            </a>

            {{-- 2. Slot 2 --}}
            @role('Pegawai Kandang')
            <button @click="togglePanel('pencatatan', 'Pencatatan Harian')" class="mobile-nav-btn group relative">
                <div class="mobile-nav-icon {{ request()->is('pencatatan/*') && !request()->is('pencatatan/riwayat/*') ? 'mobile-nav-active' : '' }}" :class="activePanel === 'pencatatan' && panelOpen ? 'mobile-nav-panel-open' : ''">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15a2.25 2.25 0 012.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" /></svg>
                </div>
                <span class="mobile-nav-label">Catat</span>
                <x-badge-notification :show="isset($uncompletedTasks['has_any_task']) && $uncompletedTasks['has_any_task']" class="absolute top-1 right-2" />
            </button>
            @endrole

            @role('Sales')
            <button @click="togglePanel('transaksi', 'Penjualan')" class="mobile-nav-btn group">
                <div class="mobile-nav-icon {{ request()->is('transaksi/*') ? 'mobile-nav-active' : '' }}" :class="activePanel === 'transaksi' && panelOpen ? 'mobile-nav-panel-open' : ''">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" /></svg>
                </div>
                <span class="mobile-nav-label">Penjualan</span>
            </button>
            @endrole

            @role('Pegawai Gudang')
            <a href="/transaksi/order-masuk" class="mobile-nav-btn group relative">
                <div class="mobile-nav-icon {{ request()->is('transaksi/order-masuk*') ? 'mobile-nav-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.19-.504 1.125-1.125V14.25m-17.25 0h17.25m-17.25 0L5.625 4.5h12.75l2.625 9.75M12 4.5v15" /></svg>
                </div>
                <span class="mobile-nav-label">Order Masuk</span>
                <x-badge-notification :show="isset($pendingOrdersCount) && $pendingOrdersCount > 0" class="absolute top-1 right-2" />
            </a>

            <button @click="togglePanel('penerimaan', 'Penerimaan Barang')" class="mobile-nav-btn group">
                <div class="mobile-nav-icon {{ request()->is('transaksi/pembelian*') || request()->is('transaksi/riwayat-pembelian') ? 'mobile-nav-active' : '' }}" :class="activePanel === 'penerimaan' && panelOpen ? 'mobile-nav-panel-open' : ''">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                </div>
                <span class="mobile-nav-label">Penerimaan</span>
            </button>
            @endrole

            @role('Admin', 'Owner')
            <button @click="togglePanel('riwayat', 'Riwayat')" class="mobile-nav-btn group">
                <div class="mobile-nav-icon {{ request()->is('pencatatan/riwayat/*') ? 'mobile-nav-active' : '' }}" :class="activePanel === 'riwayat' && panelOpen ? 'mobile-nav-panel-open' : ''">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <span class="mobile-nav-label">Riwayat</span>
            </button>
            @endrole

            {{-- 3. Slot 3 --}}
            @role('Pegawai Kandang')
            <button @click="togglePanel('riwayat', 'Riwayat')" class="mobile-nav-btn group">
                <div class="mobile-nav-icon {{ request()->is('pencatatan/riwayat/*') ? 'mobile-nav-active' : '' }}" :class="activePanel === 'riwayat' && panelOpen ? 'mobile-nav-panel-open' : ''">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <span class="mobile-nav-label">Riwayat</span>
            </button>
            @endrole

            @role('Admin', 'Owner')
            <button @click="togglePanel('kandang', 'Kandang')" class="mobile-nav-btn group">
                <div class="mobile-nav-icon {{ request()->is('kandang*') ? 'mobile-nav-active' : '' }}" :class="activePanel === 'kandang' && panelOpen ? 'mobile-nav-panel-open' : ''">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m1.5.5l-1.5-.5M6.75 7.364V3h-3v18m3-13.636l10.5-3.819" /></svg>
                </div>
                <span class="mobile-nav-label">Kandang</span>
            </button>
            @endrole

            {{-- 4. Slot 4 --}}
            @role('Pegawai Kandang')
            <button @click="togglePanel('kandang', 'Kandang')" class="mobile-nav-btn group">
                <div class="mobile-nav-icon {{ request()->is('kandang*') ? 'mobile-nav-active' : '' }}" :class="activePanel === 'kandang' && panelOpen ? 'mobile-nav-panel-open' : ''">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m1.5.5l-1.5-.5M6.75 7.364V3h-3v18m3-13.636l10.5-3.819" /></svg>
                </div>
                <span class="mobile-nav-label">Kandang</span>
            </button>
            @endrole

            @role('Pegawai Gudang')
            <button @click="togglePanel('gudang', 'Gudang')" class="mobile-nav-btn group">
                <div class="mobile-nav-icon {{ request()->is('gudang*') ? 'mobile-nav-active' : '' }}" :class="activePanel === 'gudang' && panelOpen ? 'mobile-nav-panel-open' : ''">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>
                </div>
                <span class="mobile-nav-label">Gudang</span>
            </button>
            @endrole

            @role('Admin', 'Owner')
            <button @click="togglePanel('batch', 'Batch')" class="mobile-nav-btn group">
                <div class="mobile-nav-icon {{ request()->is('batch*') ? 'mobile-nav-active' : '' }}" :class="activePanel === 'batch' && panelOpen ? 'mobile-nav-panel-open' : ''">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 7.125C2.25 6.504 2.754 6 3.375 6h6c.621 0 1.125.504 1.125 1.125v3.75c0 .621-.504 1.125-1.125 1.125h-6a1.125 1.125 0 01-1.125-1.125v-3.75zM14.25 8.625c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v8.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 01-1.125-1.125v-8.25zM3.75 16.125c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 01-1.125-1.125v-2.25z" /></svg>
                </div>
                <span class="mobile-nav-label">Batch</span>
            </button>
            @endrole

            {{-- 5. Slot 5 (Lainnya) --}}
            @role('Admin', 'Owner', 'Pegawai Kandang', 'Pegawai Gudang', 'Sales')
            <button @click="togglePanel('lainnya', 'Menu Lainnya')" class="mobile-nav-btn group">
                <div class="mobile-nav-icon {{ request()->is('master-data/*') || request()->is('keuangan/*') || request()->is('laporan/*') || request()->is('pengaturan/*') || request()->is('riwayat-aktivitas') || (request()->is('batch*') && auth()->user() && !auth()->user()->hasRole('Admin', 'Owner')) || (request()->is('gudang*') && auth()->user() && !auth()->user()->hasRole('Pegawai Gudang')) ? 'mobile-nav-active' : '' }}"
                     :class="activePanel === 'lainnya' && panelOpen ? 'mobile-nav-panel-open' : ''">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M12 17.25h8.25" />
                    </svg>
                </div>
                <span class="mobile-nav-label">Lainnya</span>
            </button>
            @endrole

        </div>
    </nav>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('mobileNav', () => ({
        panelOpen: false,
        activePanel: '',
        panelTitle: '',

        togglePanel(panel, title) {
            if (this.activePanel === panel && this.panelOpen) {
                this.panelOpen = false;
            } else {
                this.activePanel = panel;
                this.panelTitle = title;
                this.panelOpen = true;
            }
        }
    }))
})
</script>
