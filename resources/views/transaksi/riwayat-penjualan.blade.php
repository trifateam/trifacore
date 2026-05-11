<x-layouts.app title="Riwayat Penjualan">
    <div class="page-header">
        <h1>📜 Riwayat Penjualan</h1>
        <p>Daftar riwayat transaksi penjualan</p>
    </div>

    <x-data-table title="Riwayat Penjualan" :headers="['No', 'Tanggal', 'Pelanggan', 'Barang', 'Jumlah', 'Total']">
        <tr><td>1</td><td>11 Mei 2026</td><td>Toko Pak Budi</td><td>Telur Ayam</td><td>50 kg</td><td>Rp 2.500.000</td></tr>
        <tr><td>2</td><td>10 Mei 2026</td><td>Warung Bu Sari</td><td>Telur Ayam</td><td>30 kg</td><td>Rp 1.500.000</td></tr>
        <tr><td>3</td><td>09 Mei 2026</td><td>Toko Pak Budi</td><td>Ayam Potong</td><td>20 ekor</td><td>Rp 3.100.000</td></tr>
    </x-data-table>
</x-layouts.app>
