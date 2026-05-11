<x-layouts.app title="Data Supplier">
    <div class="page-header">
        <h1>🤝 Data Supplier</h1>
        <p>Manajemen data supplier</p>
    </div>

    <x-data-table title="Data Supplier" :headers="['No', 'Nama', 'No HP', 'Alamat']">
        <tr><td>1</td><td>PT Pakan Jaya</td><td>081234567890</td><td>Jl. Industri No. 12, Surabaya</td></tr>
        <tr><td>2</td><td>UD Obat Ternak</td><td>089876543210</td><td>Jl. Pasar Baru No. 5, Malang</td></tr>
    </x-data-table>
</x-layouts.app>
