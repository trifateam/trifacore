<x-layouts.app title="Populasi Kandang">
    <div class="page-header">
        <h1>🐔 Populasi Kandang</h1>
        <p>Data populasi ayam per kandang</p>
    </div>

    <x-data-table title="Populasi Kandang" :headers="['No', 'Kandang', 'Kapasitas', 'Populasi', 'Mortalitas', 'Status']">
        <tr>
            <td>1</td><td>Kandang A</td><td>500 ekor</td><td>480 ekor</td><td>3 ekor</td>
            <td><span class="badge badge-aktif">Aktif</span></td>
        </tr>
        <tr>
            <td>2</td><td>Kandang B</td><td>400 ekor</td><td>385 ekor</td><td>5 ekor</td>
            <td><span class="badge badge-aktif">Aktif</span></td>
        </tr>
        <tr>
            <td>3</td><td>Kandang C</td><td>300 ekor</td><td>0 ekor</td><td>0 ekor</td>
            <td><span class="badge badge-nonaktif">Nonaktif</span></td>
        </tr>
    </x-data-table>
</x-layouts.app>
