@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Master Data'],
        ['label' => 'Kategori Biaya Operasional'],
    ]" />

    <x-page-header title="Kategori Biaya Operasional" subtitle="Kelola data kategori untuk pengeluaran biaya operasional">
        <x-slot:action>
            <x-button variant="primary" @click="$dispatch('open-modal-tambah-kategori'); resetForm()">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Kategori
            </x-button>
        </x-slot:action>
    </x-page-header>

    <x-filter-bar :action="route('master-data.kategori-biaya.index')">
        <x-search-field name="search" placeholder="Cari nama kategori..." :value="$search" />
    </x-filter-bar>

    @if($kategoris->count() > 0)
        <x-table :headers="['No', 'Nama Kategori', 'Keterangan', 'Aksi']">
            @foreach($kategoris as $index => $kategori)
                <tr>
                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $kategoris->firstItem() + $index }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $kategori->nama_kategori }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $kategori->keterangan ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm">
                        <div class="flex items-center space-x-2">
                            <x-button variant="secondary" size="sm"
                                @click="editKategori({{ json_encode([
                                    'id' => $kategori->id_kategori_biaya,
                                    'nama_kategori' => $kategori->nama_kategori,
                                    'keterangan' => $kategori->keterangan,
                                ]) }})">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                Edit
                            </x-button>
                            <x-button variant="danger" size="sm"
                                @click="$dispatch('confirm-delete', { action: '{{ route('master-data.kategori-biaya.destroy', $kategori->id_kategori_biaya) }}' })">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                Hapus
                            </x-button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-table>
        <div class="mt-6">{{ $kategoris->links() }}</div>
    @else
        <x-empty-state message="Belum ada data kategori biaya" icon="tag" />
    @endif

    {{-- Modal Tambah --}}
    <x-modal id="tambah-kategori" title="Tambah Kategori Baru" size="md">
        <form id="formTambahKategori" method="POST" action="{{ route('master-data.kategori-biaya.store') }}">
            @csrf
            <x-form-section title="Informasi Kategori" description="Lengkapi nama dan keterangan kategori">
                <div class="space-y-4">
                    <x-input name="nama_kategori" label="Nama Kategori" placeholder="Contoh: Gaji Tenaga Kerja, Listrik" :required="true" hint="Maksimal 100 karakter, harus unik" />
                    <x-textarea name="keterangan" label="Keterangan" placeholder="Penjelasan singkat mengenai kategori ini..." rows="3" />
                </div>
            </x-form-section>
            
            <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-end space-x-3 -mx-6 -mb-4 mt-6">
                <x-button variant="secondary" type="button" @click="$dispatch('close-modal-tambah-kategori')">Batal</x-button>
                <x-button variant="primary" type="submit">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    Simpan Kategori
                </x-button>
            </div>
        </form>
    </x-modal>

    {{-- Modal Edit --}}
    <x-modal id="edit-kategori" title="Edit Kategori Biaya" size="md">
        <form id="formEditKategori" method="POST" action="">
            @csrf
            @method('PUT')
            <x-form-section title="Informasi Kategori" description="Perbarui nama dan keterangan kategori">
                <div class="space-y-4">
                    <div class="mb-4">
                        <label for="edit_nama_kategori" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Kategori <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_kategori" id="edit_nama_kategori" required maxlength="100" placeholder="Contoh: Listrik" class="w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm sm:text-sm">
                    </div>
                    
                    <div class="mb-4">
                        <label for="edit_keterangan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Keterangan</label>
                        <textarea name="keterangan" id="edit_keterangan" rows="3" class="w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm sm:text-sm"></textarea>
                    </div>
                </div>
            </x-form-section>
            
            <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-end space-x-3 -mx-6 -mb-4 mt-6">
                <x-button variant="secondary" type="button" @click="$dispatch('close-modal-edit-kategori')">Batal</x-button>
                <x-button variant="primary" type="submit">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    Simpan Perubahan
                </x-button>
            </div>
        </form>
    </x-modal>

    <x-confirm-dialog
        title="Hapus Kategori Biaya"
        message="Apakah Anda yakin ingin menghapus kategori ini? Kategori yang sudah digunakan dalam transaksi pengeluaran operasional tidak dapat dihapus."
        confirmLabel="Ya, Hapus"
        confirmVariant="danger"
    />
@endsection

@section('scripts')
<script>
    function resetForm() {
        const form = document.getElementById('formTambahKategori');
        if (form) form.reset();
    }

    function editKategori(data) {
        const form = document.getElementById('formEditKategori');
        form.action = '/master-data/kategori-biaya/' + data.id;

        document.getElementById('edit_nama_kategori').value = data.nama_kategori;
        document.getElementById('edit_keterangan').value = data.keterangan || '';

        window.dispatchEvent(new CustomEvent('open-modal-edit-kategori'));
    }
</script>
@endsection
