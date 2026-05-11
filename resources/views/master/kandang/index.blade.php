<x-layouts.app title="Data Kandang">
    <div class="page-header">
        <h1>🏗️ Data Kandang</h1>
        <p>Manajemen data kandang</p>
    </div>

    <x-data-table title="Data Kandang" :headers="['No', 'Kode', 'Nama Kandang', 'Kapasitas', 'Status']">
        <tr>
            <td>1</td><td>KDG-001</td><td>Kandang A</td><td>500 ekor</td>
            <td><span class="badge badge-aktif">Aktif</span></td>
        </tr>
        <tr>
            <td>2</td><td>KDG-002</td><td>Kandang B</td><td>400 ekor</td>
            <td><span class="badge badge-aktif">Aktif</span></td>
        </tr>
        <tr>
            <td>3</td><td>KDG-003</td><td>Kandang C</td><td>300 ekor</td>
            <td><span class="badge badge-nonaktif">Nonaktif</span></td>
        </tr>
    </x-data-table>
</x-layouts.app>
