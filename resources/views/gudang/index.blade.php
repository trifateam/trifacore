<x-layouts.app title="Gudang">
    <div class="page-header">
        <h1>📦 Stok Gudang</h1>
        <p>Data stok barang di gudang</p>
    </div>

    <x-data-table title="Stok Gudang" :headers="['No', 'Kode', 'Nama Barang', 'Kategori', 'Stok', 'Satuan']">
        <tr><td>1</td><td>BRG001</td><td>Pakan Ayam Layer</td><td>Pakan</td><td>500</td><td>kg</td></tr>
        <tr><td>2</td><td>BRG002</td><td>Vitamin B Complex</td><td>Obat</td><td>12</td><td>liter</td></tr>
        <tr><td>3</td><td>BRG003</td><td>Egg Tray</td><td>Perlengkapan</td><td>200</td><td>pcs</td></tr>
        <tr><td>4</td><td>BRG004</td><td>Pakan Ayam Starter</td><td>Pakan</td><td>150</td><td>kg</td></tr>
    </x-data-table>
</x-layouts.app>
