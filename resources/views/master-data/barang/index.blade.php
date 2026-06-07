@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Master Data'],
        ['label' => 'Data Barang/Item'],
    ]" />

    <x-page-header title="Data Barang/Item" subtitle="Kelola data barang dan material peternakan">
        <x-slot:action>
            <x-button variant="primary" @click="$dispatch('open-modal-tambah-barang'); resetForm()">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Data
            </x-button>
        </x-slot:action>
    </x-page-header>

    <x-filter-bar :action="route('master-data.barang.index')">
        <x-search-field name="search" placeholder="Cari nama barang..." :value="$search" />
        <x-select
            name="kategori"
            label="Kategori"
            :options="array_map(fn($k) => ['value' => $k, 'label' => $k], $kategoriList)"
            :selected="$kategori"
            placeholder="Semua Kategori"
        />
    </x-filter-bar>

    @if($barangs->count() > 0)
        <x-table :headers="['No', 'Nama Barang', 'Kategori', 'SKU', 'Stok', 'Satuan', 'Harga', 'Jual/Beli', 'Aksi']">
            @foreach($barangs as $index => $barang)
                <tr>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $barangs->firstItem() + $index }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $barang->nama_barang }}</td>
                    <td class="px-4 py-3 text-sm">
                        @php
                            $badgeVariant = match($barang->kategori_barang) {
                                'Telur' => 'warning',
                                'Pakan' => 'success',
                                'Vitamin' => 'info',
                                'Pupuk' => 'gray',
                                'Obat' => 'danger',
                                default => 'gray',
                            };
                        @endphp
                        <x-badge :variant="$badgeVariant">{{ $barang->kategori_barang }}</x-badge>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $barang->sku ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">
                        <span class="{{ $barang->stok_barang <= $barang->stok_minimum && $barang->stok_minimum > 0 ? 'text-red-600 font-semibold' : '' }}">
                            {{ number_format($barang->stok_barang, 0, ',', '.') }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $barang->satuan }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">@rupiah($barang->harga)</td>
                    <td class="px-4 py-3 text-sm">
                        <div class="flex items-center space-x-3">
                            <span title="Dapat Dijual">
                                @if($barang->dapat_dijual)
                                    <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                @endif
                            </span>
                            <span title="Dapat Dibeli">
                                @if($barang->dapat_dibeli)
                                    <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                @endif
                            </span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <div class="flex items-center space-x-2">
                            <x-button variant="secondary" size="sm"
                                @click="editBarang({{ json_encode([
                                    'id' => $barang->id_barang,
                                    'nama_barang' => $barang->nama_barang,
                                    'kategori_barang' => $barang->kategori_barang,
                                    'sku' => $barang->sku,
                                    'satuan' => $barang->satuan,
                                    'stok_barang' => $barang->stok_barang,
                                    'stok_minimum' => $barang->stok_minimum,
                                    'harga' => $barang->harga,
                                    'dapat_dijual' => $barang->dapat_dijual ? '1' : '0',
                                    'dapat_dibeli' => $barang->dapat_dibeli ? '1' : '0',
                                ]) }})">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                Edit
                            </x-button>
                            <x-button variant="danger" size="sm"
                                @click="$dispatch('confirm-delete', { action: '{{ route('master-data.barang.destroy', $barang->id_barang) }}' })">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                Hapus
                            </x-button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-table>
        <div class="mt-6">{{ $barangs->links() }}</div>
    @else
        <x-empty-state message="Belum ada data barang" icon="inbox" />
    @endif

    {{-- Modal Tambah --}}
    <x-modal id="tambah-barang" title="Tambah Barang Baru" size="xl">
        <form id="formTambahBarang" method="POST" action="{{ route('master-data.barang.store') }}">
            @csrf
            <x-form-section title="Informasi Barang" description="Lengkapi data barang baru">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                    <x-input name="nama_barang" label="Nama Barang" placeholder="Contoh: Telur Ayam" :required="true" hint="Maksimal 100 karakter, harus unik" />
                    <x-select name="kategori_barang" label="Kategori" :required="true"
                        :options="[
                            ['value' => 'Telur', 'label' => 'Telur'],
                            ['value' => 'Pakan', 'label' => 'Pakan'],
                            ['value' => 'Vitamin', 'label' => 'Vitamin'],
                            ['value' => 'Pupuk', 'label' => 'Pupuk'],
                            ['value' => 'Obat', 'label' => 'Obat'],
                            ['value' => 'Lainnya', 'label' => 'Lainnya'],
                        ]" />
                    <x-input name="sku" label="SKU / Kode" placeholder="Contoh: TLR-001" hint="Opsional, harus unik jika diisi" />
                    <x-select name="satuan" label="Satuan" :required="true"
                        :options="[
                            ['value' => 'butir', 'label' => 'Butir'],
                            ['value' => 'kg', 'label' => 'Kilogram (kg)'],
                            ['value' => 'karung', 'label' => 'Karung'],
                            ['value' => 'liter', 'label' => 'Liter'],
                            ['value' => 'box', 'label' => 'Box'],
                            ['value' => 'botol', 'label' => 'Botol'],
                            ['value' => 'ekor', 'label' => 'Ekor'],
                        ]" />
                    <x-input name="stok_barang" label="Stok Awal" type="number" placeholder="0" value="0" :required="true" hint="Stok awal saat pertama ditambahkan" />
                    <x-input name="stok_minimum" label="Stok Minimum" type="number" placeholder="0" value="0" :required="true" hint="Batas alert stok kritis" />
                    <x-input name="harga" label="Harga Default" type="number" placeholder="0" value="0" :required="true" prefix="Rp" hint="Harga satuan default" />
                </div>
                <div class="flex items-center space-x-8 mt-2">
                    <x-toggle name="dapat_dijual" label="Dapat Dijual" :checked="false" />
                    <x-toggle name="dapat_dibeli" label="Dapat Dibeli" :checked="false" />
                </div>
            </x-form-section>
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                <x-button variant="secondary" type="button" @click="$dispatch('close-modal-tambah-barang')">Batal</x-button>
                <x-button variant="primary" type="submit">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    Simpan Barang
                </x-button>
            </div>
        </form>
    </x-modal>

    {{-- Modal Edit --}}
    <x-modal id="edit-barang" title="Edit Data Barang" size="xl">
        <form id="formEditBarang" method="POST" action="">
            @csrf
            @method('PUT')
            <x-form-section title="Informasi Barang" description="Perbarui data barang">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                    <div class="mb-4">
                        <label for="edit_nama_barang" class="block text-sm font-medium text-gray-700 mb-1">Nama Barang <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_barang" id="edit_nama_barang" required maxlength="100" placeholder="Contoh: Telur Ayam" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <p class="mt-1 text-sm text-gray-500">Maksimal 100 karakter, harus unik</p>
                    </div>
                    <div class="mb-4">
                        <label for="edit_kategori_barang" class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                        <select name="kategori_barang" id="edit_kategori_barang" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="Telur">Telur</option>
                            <option value="Pakan">Pakan</option>
                            <option value="Vitamin">Vitamin</option>
                            <option value="Pupuk">Pupuk</option>
                            <option value="Obat">Obat</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="edit_sku" class="block text-sm font-medium text-gray-700 mb-1">SKU / Kode</label>
                        <input type="text" name="sku" id="edit_sku" maxlength="50" placeholder="Contoh: TLR-001" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <p class="mt-1 text-sm text-gray-500">Opsional, harus unik jika diisi</p>
                    </div>
                    <div class="mb-4">
                        <label for="edit_satuan" class="block text-sm font-medium text-gray-700 mb-1">Satuan <span class="text-red-500">*</span></label>
                        <select name="satuan" id="edit_satuan" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="butir">Butir</option>
                            <option value="kg">Kilogram (kg)</option>
                            <option value="karung">Karung</option>
                            <option value="liter">Liter</option>
                            <option value="box">Box</option>
                            <option value="botol">Botol</option>
                            <option value="ekor">Ekor</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stok Saat Ini</label>
                        <input type="text" id="edit_stok_display" disabled class="w-full rounded-lg border-gray-300 shadow-sm text-sm bg-gray-100 cursor-not-allowed text-gray-500">
                        <p class="mt-1 text-sm text-gray-500">Stok hanya berubah via transaksi/opname</p>
                    </div>
                    <div class="mb-4">
                        <label for="edit_stok_minimum" class="block text-sm font-medium text-gray-700 mb-1">Stok Minimum <span class="text-red-500">*</span></label>
                        <input type="number" name="stok_minimum" id="edit_stok_minimum" required min="0" placeholder="0" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <p class="mt-1 text-sm text-gray-500">Batas alert stok kritis</p>
                    </div>
                    <div class="mb-4">
                        <label for="edit_harga" class="block text-sm font-medium text-gray-700 mb-1">Harga Default <span class="text-red-500">*</span></label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3"><span class="text-gray-500 sm:text-sm">Rp</span></div>
                            <input type="number" name="harga" id="edit_harga" required min="0" placeholder="0" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm pl-12">
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Harga satuan default</p>
                    </div>
                </div>
                <div class="flex items-center space-x-8 mt-2" x-data="{ jual: false, beli: false }" x-ref="editToggles">
                    <div class="flex items-center">
                        <input type="hidden" name="dapat_dijual" :value="jual ? '1' : '0'">
                        <button type="button" role="switch" :aria-checked="jual.toString()" @click="jual = !jual"
                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2"
                            :class="jual ? 'bg-indigo-600' : 'bg-gray-200'">
                            <span aria-hidden="true" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out" :class="jual ? 'translate-x-5' : 'translate-x-0'"></span>
                        </button>
                        <label class="ml-3 block text-sm font-medium text-gray-700 cursor-pointer" @click="jual = !jual">Dapat Dijual</label>
                    </div>
                    <div class="flex items-center">
                        <input type="hidden" name="dapat_dibeli" :value="beli ? '1' : '0'">
                        <button type="button" role="switch" :aria-checked="beli.toString()" @click="beli = !beli"
                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2"
                            :class="beli ? 'bg-indigo-600' : 'bg-gray-200'">
                            <span aria-hidden="true" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out" :class="beli ? 'translate-x-5' : 'translate-x-0'"></span>
                        </button>
                        <label class="ml-3 block text-sm font-medium text-gray-700 cursor-pointer" @click="beli = !beli">Dapat Dibeli</label>
                    </div>
                </div>
            </x-form-section>
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                <x-button variant="secondary" type="button" @click="$dispatch('close-modal-edit-barang')">Batal</x-button>
                <x-button variant="primary" type="submit">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    Simpan Perubahan
                </x-button>
            </div>
        </form>
    </x-modal>

    <x-confirm-dialog
        title="Hapus Data Barang"
        message="Apakah Anda yakin ingin menghapus data barang ini? Barang yang sudah digunakan dalam transaksi tidak dapat dihapus."
        confirmLabel="Ya, Hapus"
        confirmVariant="danger"
    />
@endsection

@section('scripts')
<script>
    function resetForm() {
        const form = document.getElementById('formTambahBarang');
        if (form) form.reset();
    }

    function editBarang(data) {
        const form = document.getElementById('formEditBarang');
        form.action = '/master-data/barang/' + data.id;

        document.getElementById('edit_nama_barang').value = data.nama_barang;
        document.getElementById('edit_kategori_barang').value = data.kategori_barang;
        document.getElementById('edit_sku').value = data.sku || '';
        document.getElementById('edit_satuan').value = data.satuan;
        document.getElementById('edit_stok_display').value = parseFloat(data.stok_barang).toLocaleString('id-ID') + ' ' + data.satuan;
        document.getElementById('edit_stok_minimum').value = data.stok_minimum;
        document.getElementById('edit_harga').value = data.harga;

        // Set Alpine toggle states
        const togglesEl = document.querySelector('[x-ref="editToggles"]');
        if (togglesEl && togglesEl.__x) {
            togglesEl.__x.$data.jual = data.dapat_dijual === '1';
            togglesEl.__x.$data.beli = data.dapat_dibeli === '1';
        } else {
            // Fallback: dispatch after Alpine init
            setTimeout(() => {
                const el = document.querySelector('[x-ref="editToggles"]');
                if (el && el._x_dataStack) {
                    el._x_dataStack[0].jual = data.dapat_dijual === '1';
                    el._x_dataStack[0].beli = data.dapat_dibeli === '1';
                }
            }, 100);
        }

        window.dispatchEvent(new CustomEvent('open-modal-edit-barang'));
    }
</script>
@endsection
