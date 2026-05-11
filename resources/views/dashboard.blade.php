<x-layouts.app title="Dashboard">
    <div class="page-header">
        <h1>🏠 Dashboard</h1>
        <p>Selamat datang di TriFaCore — Sistem Manajemen Peternakan Ayam</p>
    </div>

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">👤</div>
                    <div>
                        <div class="stat-value">12</div>
                        <div class="stat-label">Total Pegawai</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-success bg-opacity-10 text-success">🏗️</div>
                    <div>
                        <div class="stat-value">5</div>
                        <div class="stat-label">Total Kandang</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning">📦</div>
                    <div>
                        <div class="stat-value">24</div>
                        <div class="stat-label">Item Gudang</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-info bg-opacity-10 text-info">🥚</div>
                    <div>
                        <div class="stat-value">1,250</div>
                        <div class="stat-label">Produksi Hari Ini</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Info --}}
    <div class="row g-3">
        <div class="col-md-6">
            <div class="card table-card">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold">📊 Produksi Terakhir</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Kandang</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>11 Mei 2026</td><td>Kandang A</td><td>350 butir</td></tr>
                            <tr><td>11 Mei 2026</td><td>Kandang B</td><td>280 butir</td></tr>
                            <tr><td>10 Mei 2026</td><td>Kandang A</td><td>340 butir</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card table-card">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold">💰 Transaksi Terakhir</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jenis</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>11 Mei 2026</td><td><span class="badge bg-success">Penjualan</span></td><td>Rp 2.500.000</td></tr>
                            <tr><td>10 Mei 2026</td><td><span class="badge bg-danger">Pembelian</span></td><td>Rp 1.200.000</td></tr>
                            <tr><td>09 Mei 2026</td><td><span class="badge bg-success">Penjualan</span></td><td>Rp 3.100.000</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
