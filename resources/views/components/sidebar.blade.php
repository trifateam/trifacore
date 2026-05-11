<nav class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <h4>🐔 TriFaCore</h4>
        <small>Poultry Management System</small>
    </div>

    <div class="py-2">
        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="nav-icon">🏠</span> Dashboard
        </a>

        {{-- Pencatatan Harian --}}
        <div class="nav-section-title">Pencatatan Harian</div>
        <a href="{{ route('pencatatan.produksi-telur') }}" class="nav-link {{ request()->routeIs('pencatatan.*') ? 'active' : '' }}">
            <span class="nav-icon">📊</span> Produksi Telur
        </a>

        {{-- Manajemen Transaksi --}}
        <div class="nav-section-title">Manajemen Transaksi</div>
        <a href="{{ route('transaksi.penjualan') }}" class="nav-link {{ request()->routeIs('transaksi.penjualan') ? 'active' : '' }}">
            <span class="nav-icon">💰</span> Transaksi Penjualan
        </a>
        <a href="{{ route('transaksi.pembelian') }}" class="nav-link {{ request()->routeIs('transaksi.pembelian') ? 'active' : '' }}">
            <span class="nav-icon">📦</span> Transaksi Pembelian
        </a>
        <a href="{{ route('transaksi.riwayat-penjualan') }}" class="nav-link {{ request()->routeIs('transaksi.riwayat-penjualan') ? 'active' : '' }}">
            <span class="nav-icon">📜</span> Riwayat Penjualan
        </a>
        <a href="{{ route('transaksi.riwayat-pembelian') }}" class="nav-link {{ request()->routeIs('transaksi.riwayat-pembelian') ? 'active' : '' }}">
            <span class="nav-icon">📜</span> Riwayat Pembelian
        </a>

        {{-- Kandang --}}
        <div class="nav-section-title">Kandang</div>
        <a href="{{ route('kandang.populasi') }}" class="nav-link {{ request()->routeIs('kandang.*') ? 'active' : '' }}">
            <span class="nav-icon">🐔</span> Populasi Kandang
        </a>

        {{-- Gudang --}}
        <div class="nav-section-title">Gudang</div>
        <a href="{{ route('gudang.index') }}" class="nav-link {{ request()->routeIs('gudang.*') ? 'active' : '' }}">
            <span class="nav-icon">📦</span> Stok Gudang
        </a>

        {{-- Master Data --}}
        <div class="nav-section-title">Master Data</div>
        <a href="{{ route('master.kandang.index') }}" class="nav-link {{ request()->routeIs('master.kandang.*') ? 'active' : '' }}">
            <span class="nav-icon">🏗️</span> Data Kandang
        </a>
        <a href="{{ route('master.barang.index') }}" class="nav-link {{ request()->routeIs('master.barang.*') ? 'active' : '' }}">
            <span class="nav-icon">📦</span> Data Barang/Item
        </a>
        <a href="{{ route('master.supplier.index') }}" class="nav-link {{ request()->routeIs('master.supplier.*') ? 'active' : '' }}">
            <span class="nav-icon">🤝</span> Data Supplier
        </a>
        <a href="{{ route('pegawai.index') }}" class="nav-link {{ request()->routeIs('pegawai.*') ? 'active' : '' }}">
            <span class="nav-icon">👤</span> Data Pegawai
        </a>
        <a href="{{ route('master.pelanggan.index') }}" class="nav-link {{ request()->routeIs('master.pelanggan.*') ? 'active' : '' }}">
            <span class="nav-icon">🛒</span> Data Pelanggan
        </a>
    </div>
</nav>
