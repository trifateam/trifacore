<x-layouts.app title="Riwayat Pembelian">
    <div class="page-header">
        <h1>📜 Riwayat Pembelian</h1>
        <p>Daftar riwayat transaksi pembelian</p>
    </div>

    <x-data-table title="Riwayat Pembelian" :headers="['No', 'Tanggal', 'Supplier', 'Barang', 'Jumlah', 'Total']">
        <tr><td>1</td><td>10 Mei 2026</td><td>PT Pakan Jaya</td><td>Pakan Ayam</td><td>100 kg</td><td>Rp 1.200.000</td></tr>
        <tr><td>2</td><td>08 Mei 2026</td><td>UD Obat Ternak</td><td>Vitamin</td><td>5 liter</td><td>Rp 350.000</td></tr>
        <tr><td>3</td><td>05 Mei 2026</td><td>PT Pakan Jaya</td><td>Pakan Ayam</td><td>150 kg</td><td>Rp 1.800.000</td></tr>
    </x-data-table>
</x-layouts.app>
