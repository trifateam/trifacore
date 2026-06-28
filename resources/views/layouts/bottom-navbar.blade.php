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
        
        <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100 dark:border-gray-700">
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
                    <a href="/pencatatan/produksi-telur" class="mobile-submenu-item {{ request()->is('pencatatan/produksi-telur*') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-indigo-400 shrink-0"></span>Produksi Telur
                    </a>
                    <a href="/pencatatan/konsumsi-pakan" class="mobile-submenu-item {{ request()->is('pencatatan/konsumsi-pakan*') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-indigo-400 shrink-0"></span>Konsumsi Pakan
                    </a>
                    <a href="/pencatatan/konsumsi-vitamin" class="mobile-submenu-item {{ request()->is('pencatatan/konsumsi-vitamin*') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-indigo-400 shrink-0"></span>Konsumsi Vitamin
                    </a>
                    <a href="/pencatatan/deplesi" class="mobile-submenu-item {{ request()->is('pencatatan/deplesi*') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-indigo-400 shrink-0"></span>Kematian/Afkir (Deplesi)
                    </a>
                    <a href="/pencatatan/suhu" class="mobile-submenu-item {{ request()->is('pencatatan/suhu*') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-indigo-400 shrink-0"></span>Suhu Lingkungan
                    </a>
                    <a href="/pencatatan/pupuk" class="mobile-submenu-item {{ request()->is('pencatatan/pupuk*') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-indigo-400 shrink-0"></span>Produksi Pupuk
                    </a>
                </div>
            </template>
            @endrole

            {{-- Transaksi Submenus --}}
            @role('Sales', 'Pegawai Gudang')
            <template x-if="activePanel === 'transaksi'">
                <div class="space-y-1">
                    @role('Sales')
                    <a href="/transaksi/penjualan" class="mobile-submenu-item {{ request()->is('transaksi/penjualan*') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-blue-400 shrink-0"></span>Transaksi Penjualan
                    </a>
                    @endrole
                    @role('Pegawai Gudang')
                    <a href="/transaksi/pembelian" class="mobile-submenu-item {{ request()->is('transaksi/pembelian*') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-blue-400 shrink-0"></span>Transaksi Pembelian
                    </a>
                    @endrole
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
                    @role('Admin', 'Owner', 'Sales', 'Pegawai Gudang')
                    <div class="px-3 pt-2 pb-1 text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Transaksi</div>
                    @role('Admin', 'Owner', 'Sales')
                    <a href="/transaksi/riwayat-penjualan" class="mobile-submenu-item {{ request()->is('transaksi/riwayat-penjualan') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-violet-400 shrink-0"></span>Penjualan
                    </a>
                    @endrole
                    @role('Pegawai Gudang', 'Admin', 'Owner')
                    <a href="/transaksi/riwayat-pembelian" class="mobile-submenu-item {{ request()->is('transaksi/riwayat-pembelian') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-violet-400 shrink-0"></span>Pembelian
                    </a>
                    @endrole
                    @endrole
                </div>
            </template>

            {{-- More (Lainnya) Submenus - Admin/Owner only --}}
            @role('Admin', 'Owner')
            <template x-if="activePanel === 'more'">
                <div class="space-y-1">
                    <div class="px-3 pt-1 pb-1 text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Kandang</div>
                    <a href="/kandang" class="mobile-submenu-item {{ request()->is('kandang') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-purple-400 shrink-0"></span>Kandang
                    </a>
                    <a href="/batch" class="mobile-submenu-item {{ request()->is('batch*') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-purple-400 shrink-0"></span>Batch
                    </a>

                    <div class="px-3 pt-2 pb-1 text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Master Data</div>
                    <a href="/master-data/kandang" class="mobile-submenu-item {{ request()->is('master-data/kandang') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-amber-400 shrink-0"></span>Data Kandang
                    </a>
                    <a href="/master-data/barang" class="mobile-submenu-item {{ request()->is('master-data/barang') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-amber-400 shrink-0"></span>Data Barang/Item
                    </a>
                    <a href="/master-data/supplier" class="mobile-submenu-item {{ request()->is('master-data/supplier') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-amber-400 shrink-0"></span>Data Supplier
                    </a>
                    <a href="/master-data/pegawai" class="mobile-submenu-item {{ request()->is('master-data/pegawai') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-amber-400 shrink-0"></span>Data Pegawai
                    </a>
                    <a href="/master-data/pelanggan" class="mobile-submenu-item {{ request()->is('master-data/pelanggan') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-amber-400 shrink-0"></span>Data Pelanggan
                    </a>
                    <a href="/master-data/rekening" class="mobile-submenu-item {{ request()->is('master-data/rekening') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-amber-400 shrink-0"></span>Data Rekening Kas/Bank
                    </a>
                    <a href="/master-data/kategori-biaya" class="mobile-submenu-item {{ request()->is('master-data/kategori-biaya') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-amber-400 shrink-0"></span>Data Kategori Biaya
                    </a>

                    <div class="px-3 pt-2 pb-1 text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Keuangan</div>
                    <a href="/keuangan/biaya-operasional" class="mobile-submenu-item {{ request()->is('keuangan/biaya-operasional') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-cyan-400 shrink-0"></span>Biaya Operasional
                    </a>
                    <a href="/keuangan/buku-kas" class="mobile-submenu-item {{ request()->is('keuangan/buku-kas') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-cyan-400 shrink-0"></span>Buku Kas
                    </a>
                    <a href="/keuangan/buku-utang" class="mobile-submenu-item {{ request()->is('keuangan/buku-utang') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-cyan-400 shrink-0"></span>Buku Utang
                    </a>
                    <a href="/keuangan/buku-piutang" class="mobile-submenu-item {{ request()->is('keuangan/buku-piutang') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-cyan-400 shrink-0"></span>Buku Piutang
                    </a>

                    <div class="px-3 pt-2 pb-1 text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Laporan</div>
                    <a href="/laporan/produksi-performa" class="mobile-submenu-item {{ request()->is('laporan/produksi-performa') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 shrink-0"></span>Produksi & Performa
                    </a>
                    <a href="/laporan/laba-rugi" class="mobile-submenu-item {{ request()->is('laporan/laba-rugi') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 shrink-0"></span>Laporan Laba Rugi
                    </a>

                    <div class="px-3 pt-2 pb-1 text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Pengaturan</div>
                    <a href="/pengaturan/profil-sistem" class="mobile-submenu-item {{ request()->is('pengaturan/profil-sistem') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-red-400 shrink-0"></span>Profil & Sistem
                    </a>
                    <a href="/riwayat-aktivitas" class="mobile-submenu-item {{ request()->is('riwayat-aktivitas') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-red-400 shrink-0"></span>Riwayat Aktivitas Sistem
                    </a>
                </div>
            </template>
            @endrole



            {{-- More for Pegawai Kandang --}}
            @role('Pegawai Kandang')
            <template x-if="activePanel === 'more'">
                <div class="space-y-1">
                    <div class="px-3 pt-1 pb-1 text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Kandang</div>
                    <a href="/kandang" class="mobile-submenu-item {{ request()->is('kandang') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-purple-400 shrink-0"></span>Kandang
                    </a>
                    <a href="/batch" class="mobile-submenu-item {{ request()->is('batch*') ? 'mobile-submenu-active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-purple-400 shrink-0"></span>Batch
                    </a>
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

            {{-- Dashboard (All roles) --}}
            <a href="/dashboard" class="mobile-nav-btn group" id="mobile-nav-dashboard">
                <div class="mobile-nav-icon {{ request()->is('dashboard') ? 'mobile-nav-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                </div>
                <span class="mobile-nav-label">Home</span>
            </a>

            @role('Pegawai Kandang')
            {{-- Pencatatan (Pegawai Kandang) --}}
            <button @click="togglePanel('pencatatan', 'Pencatatan Harian')" class="mobile-nav-btn group" id="mobile-nav-pencatatan">
                <div class="mobile-nav-icon {{ request()->is('pencatatan/*') && !request()->is('pencatatan/riwayat/*') ? 'mobile-nav-active' : '' }}"
                     :class="activePanel === 'pencatatan' && panelOpen ? 'mobile-nav-panel-open' : ''">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15a2.25 2.25 0 012.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" /></svg>
                </div>
                <span class="mobile-nav-label">Catat</span>
            </button>
            @endrole

            @role('Sales', 'Pegawai Gudang')
            {{-- Transaksi (Sales) --}}
            <button @click="togglePanel('transaksi', 'Manajemen Transaksi')" class="mobile-nav-btn group" id="mobile-nav-transaksi">
                <div class="mobile-nav-icon {{ request()->is('transaksi/*') && !request()->is('transaksi/riwayat*') ? 'mobile-nav-active' : '' }}"
                     :class="activePanel === 'transaksi' && panelOpen ? 'mobile-nav-panel-open' : ''">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" /></svg>
                </div>
                <span class="mobile-nav-label">Transaksi</span>
            </button>
            @endrole

            @role('Admin', 'Owner', 'Pegawai Kandang', 'Sales', 'Pegawai Gudang')
            {{-- Riwayat --}}
            <button @click="togglePanel('riwayat', 'Riwayat')" class="mobile-nav-btn group" id="mobile-nav-riwayat">
                <div class="mobile-nav-icon {{ request()->is('pencatatan/riwayat/*') || request()->is('transaksi/riwayat*') ? 'mobile-nav-active' : '' }}"
                     :class="activePanel === 'riwayat' && panelOpen ? 'mobile-nav-panel-open' : ''">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <span class="mobile-nav-label">Riwayat</span>
            </button>
            @endrole

            @role('Pegawai Gudang')
            {{-- Gudang (direct link for Pegawai Gudang) --}}
            <a href="/gudang" class="mobile-nav-btn group" id="mobile-nav-gudang">
                <div class="mobile-nav-icon {{ request()->is('gudang*') ? 'mobile-nav-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>
                </div>
                <span class="mobile-nav-label">Gudang</span>
            </a>
            @endrole

            @role('Admin', 'Owner', 'Pegawai Gudang', 'Pegawai Kandang')
            {{-- More / Lainnya --}}
            <button @click="togglePanel('more', 'Lainnya')" class="mobile-nav-btn group" id="mobile-nav-more">
                <div class="mobile-nav-icon {{ request()->is('master-data/*') || request()->is('keuangan/*') || request()->is('laporan/*') || request()->is('pengaturan/*') || request()->is('riwayat-aktivitas') || request()->is('kandang*') || request()->is('batch*') || request()->is('gudang*') ? 'mobile-nav-active' : '' }}"
                     :class="activePanel === 'more' && panelOpen ? 'mobile-nav-panel-open' : ''">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>
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
