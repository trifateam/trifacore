<x-layouts.app title="Transaksi Penjualan">
    <div class="page-header">
        <h1>💰 Transaksi Penjualan</h1>
        <p>Form pencatatan transaksi penjualan</p>
    </div>

    <div class="card form-card">
        <div class="card-header">
            <h6 class="mb-0 fw-bold">Form Penjualan</h6>
        </div>
        <div class="card-body">
            <form>
                <div class="row">
                    <div class="col-md-6">
                        <x-form-input name="tanggal" label="Tanggal" type="date" :value="date('Y-m-d')" required />
                    </div>
                    <div class="col-md-6">
                        <x-form-select name="pelanggan_id" label="Pelanggan" :options="['1' => 'Toko Pak Budi', '2' => 'Warung Bu Sari']" required />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <x-form-select name="barang_id" label="Barang" :options="['1' => 'Telur Ayam (kg)', '2' => 'Ayam Potong (ekor)']" required />
                    </div>
                    <div class="col-md-4">
                        <x-form-input name="jumlah" label="Jumlah" type="number" required placeholder="0" />
                    </div>
                    <div class="col-md-4">
                        <x-form-input name="harga_satuan" label="Harga Satuan (Rp)" type="number" required placeholder="0" />
                    </div>
                </div>
                <x-form-input name="keterangan" label="Keterangan" type="textarea" placeholder="Catatan..." />

                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary" onclick="alert('Fitur simpan belum tersedia.')">
                        💾 Simpan
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
