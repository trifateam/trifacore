<x-layouts.app title="Data Barang">
    <div class="page-header">
        <h1>📦 Data Barang/Item</h1>
        <p>Manajemen data barang dan item</p>
    </div>

    <x-data-table title="Data Barang" :headers="['No', 'Kode', 'Nama Barang', 'Kategori', 'Satuan', 'Stok']">
        <tr><td>1</td><td>BRG001</td><td>Pakan Ayam Layer</td><td>Pakan</td><td>kg</td><td>500</td></tr>
        <tr><td>2</td><td>BRG002</td><td>Vitamin B Complex</td><td>Obat</td><td>liter</td><td>12</td></tr>
        <tr><td>3</td><td>BRG003</td><td>Egg Tray</td><td>Perlengkapan</td><td>pcs</td><td>200</td></tr>
    </x-data-table>
</x-layouts.app>
