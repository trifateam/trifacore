@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Master Data'],
        ['label' => 'Data Supplier'],
    ]" />

    <x-page-header title="Data Supplier" subtitle="Kelola data supplier dan mitra pemasok">
        <x-slot:action>
            <x-button variant="primary" @click="$dispatch('open-modal-tambah-supplier'); resetForm()">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Data
            </x-button>
        </x-slot:action>
    </x-page-header>

    <x-filter-bar :action="route('master-data.supplier.index')">
        <x-search-field name="search" placeholder="Cari nama supplier..." :value="$search" />
    </x-filter-bar>

    @if($suppliers->count() > 0)
        <x-table :headers="['No', 'Nama Supplier', 'Kontak', 'Alamat', 'PIC', 'Email', 'Aksi']">
            @foreach($suppliers as $index => $supplier)
                <tr>
                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $suppliers->firstItem() + $index }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $supplier->nama_supplier }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $supplier->kontak_supplier }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300 max-w-xs truncate" title="{{ $supplier->alamat_supplier }}">{{ Str::limit($supplier->alamat_supplier, 40) }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $supplier->nama_pic ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $supplier->email ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm">
                        <div class="flex items-center space-x-2">
                            <x-button variant="secondary" size="sm"
                                @click="editSupplier({{ json_encode([
                                    'id' => $supplier->id_supplier,
                                    'nama_supplier' => $supplier->nama_supplier,
                                    'alamat_supplier' => $supplier->alamat_supplier,
                                    'kontak_supplier' => $supplier->kontak_supplier,
                                    'email' => $supplier->email,
                                    'nama_pic' => $supplier->nama_pic,
                                ]) }})">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                Edit
                            </x-button>
                            <x-button variant="danger" size="sm"
                                @click="$dispatch('confirm-delete', { action: '{{ route('master-data.supplier.destroy', $supplier->id_supplier) }}' })">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                Hapus
                            </x-button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-table>
        <div class="mt-6">{{ $suppliers->links() }}</div>
    @else
        <x-empty-state message="Belum ada data supplier" icon="inbox" />
    @endif

    {{-- Modal Tambah --}}
    <x-modal id="tambah-supplier" title="Tambah Supplier Baru" size="xl">
        <form id="formTambahSupplier" method="POST" action="{{ route('master-data.supplier.store') }}">
            @csrf
            <x-form-section title="Informasi Supplier" description="Lengkapi data supplier baru">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                    <x-input name="nama_supplier" label="Nama Supplier" placeholder="Contoh: PT Pakan Sejahtera" :required="true" hint="Maksimal 100 karakter, harus unik" />
                    <x-input name="kontak_supplier" label="No. Telp / Kontak" placeholder="Contoh: 08123456789" :required="true" hint="Maksimal 20 karakter" />
                    <x-input name="email" label="Email" type="email" placeholder="Contoh: supplier@email.com" hint="Opsional, format email valid" />
                    <x-input name="nama_pic" label="Nama PIC (Penanggung Jawab)" placeholder="Contoh: Budi Santoso" hint="Opsional, maksimal 100 karakter" />
                </div>
                <x-textarea name="alamat_supplier" label="Alamat" placeholder="Masukkan alamat lengkap supplier..." :required="true" hint="Alamat lengkap supplier" />
            </x-form-section>
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <x-button variant="secondary" type="button" @click="$dispatch('close-modal-tambah-supplier')">Batal</x-button>
                <x-button variant="primary" type="submit">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    Simpan Supplier
                </x-button>
            </div>
        </form>
    </x-modal>

    {{-- Modal Edit --}}
    <x-modal id="edit-supplier" title="Edit Data Supplier" size="xl">
        <form id="formEditSupplier" method="POST" action="">
            @csrf
            @method('PUT')
            <x-form-section title="Informasi Supplier" description="Perbarui data supplier">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                    <div class="mb-4">
                        <label for="edit_nama_supplier" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Supplier <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_supplier" id="edit_nama_supplier" required maxlength="100" placeholder="Contoh: PT Pakan Sejahtera" class="w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Maksimal 100 karakter, harus unik</p>
                    </div>
                    <div class="mb-4">
                        <label for="edit_kontak_supplier" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">No. Telp / Kontak <span class="text-red-500">*</span></label>
                        <input type="text" name="kontak_supplier" id="edit_kontak_supplier" required maxlength="20" placeholder="Contoh: 08123456789" class="w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Maksimal 20 karakter</p>
                    </div>
                    <div class="mb-4">
                        <label for="edit_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                        <input type="email" name="email" id="edit_email" maxlength="100" placeholder="Contoh: supplier@email.com" class="w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Opsional, format email valid</p>
                    </div>
                    <div class="mb-4">
                        <label for="edit_nama_pic" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama PIC (Penanggung Jawab)</label>
                        <input type="text" name="nama_pic" id="edit_nama_pic" maxlength="100" placeholder="Contoh: Budi Santoso" class="w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Opsional, maksimal 100 karakter</p>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="edit_alamat_supplier" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat <span class="text-red-500">*</span></label>
                    <textarea name="alamat_supplier" id="edit_alamat_supplier" required rows="3" placeholder="Masukkan alamat lengkap supplier..." class="w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"></textarea>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Alamat lengkap supplier</p>
                </div>
            </x-form-section>
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <x-button variant="secondary" type="button" @click="$dispatch('close-modal-edit-supplier')">Batal</x-button>
                <x-button variant="primary" type="submit">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    Simpan Perubahan
                </x-button>
            </div>
        </form>
    </x-modal>

    <x-confirm-dialog
        title="Hapus Data Supplier"
        message="Apakah Anda yakin ingin menghapus data supplier ini? Supplier yang masih memiliki transaksi pembelian tidak dapat dihapus."
        confirmLabel="Ya, Hapus"
        confirmVariant="danger"
    />
@endsection

@section('scripts')
<script>
    function resetForm() {
        const form = document.getElementById('formTambahSupplier');
        if (form) form.reset();
    }

    function editSupplier(data) {
        const form = document.getElementById('formEditSupplier');
        form.action = '/master-data/supplier/' + data.id;

        document.getElementById('edit_nama_supplier').value = data.nama_supplier;
        document.getElementById('edit_kontak_supplier').value = data.kontak_supplier;
        document.getElementById('edit_email').value = data.email || '';
        document.getElementById('edit_nama_pic').value = data.nama_pic || '';
        document.getElementById('edit_alamat_supplier').value = data.alamat_supplier;

        window.dispatchEvent(new CustomEvent('open-modal-edit-supplier'));
    }
</script>
@endsection
