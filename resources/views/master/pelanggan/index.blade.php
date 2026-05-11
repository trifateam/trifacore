<x-layouts.app title="Data Pelanggan">
    <div class="page-header">
        <h1>🛒 Data Pelanggan</h1>
        <p>Manajemen data pelanggan</p>
    </div>

    <x-data-table title="Data Pelanggan" :headers="['No', 'Nama', 'No HP', 'Alamat']">
        <tr><td>1</td><td>Toko Pak Budi</td><td>081122334455</td><td>Jl. Merdeka No. 10, Surabaya</td></tr>
        <tr><td>2</td><td>Warung Bu Sari</td><td>089988776655</td><td>Jl. Ahmad Yani No. 22, Gresik</td></tr>
    </x-data-table>
</x-layouts.app>
