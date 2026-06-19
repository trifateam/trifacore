@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Master Data'],
        ['label' => 'Data Pelanggan'],
    ]" />

    <x-page-header title="Data Pelanggan" subtitle="Kelola data pelanggan dan pembeli">
        <x-slot:action>
            <x-button variant="primary" @click="$dispatch('open-modal-tambah-pelanggan'); resetForm()">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Data
            </x-button>
        </x-slot:action>
    </x-page-header>

    <x-filter-bar :action="route('master-data.pelanggan.index')">
        <x-search-field name="search" placeholder="Cari nama pelanggan..." :value="$search" />
        <x-select
            name="kategori"
            label="Kategori"
            :options="array_map(fn($k) => ['value' => $k, 'label' => $k], $kategoriList)"
            :selected="$kategori"
            placeholder="Semua Kategori"
        />
    </x-filter-bar>

    @if($pelanggans->count() > 0)
        <x-table :headers="['No', 'Nama Pelanggan', 'Kategori', 'Kontak', 'Alamat', 'Status', 'Aksi']">
            @foreach($pelanggans as $index => $pelanggan)
                <tr>
                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $pelanggans->firstItem() + $index }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $pelanggan->nama_lengkap }}</td>
                    <td class="px-4 py-3 text-sm">
                        @php
                            $badgeVariant = match($pelanggan->kategori) {
                                'Distributor' => 'info',
                                'Retail' => 'success',
                                'Personal' => 'warning',
                                default => 'gray',
                            };
                        @endphp
                        <x-badge :variant="$badgeVariant">{{ $pelanggan->kategori }}</x-badge>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $pelanggan->kontak }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300 max-w-xs truncate" title="{{ $pelanggan->alamat }}">{{ Str::limit($pelanggan->alamat, 40) }}</td>
                    <td class="px-4 py-3 text-sm">
                        @if(!$pelanggan->trashed())
                            <x-badge variant="success">Aktif</x-badge>
                        @else
                            <x-badge variant="gray">Non-Aktif</x-badge>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <div class="flex items-center space-x-2">
                            <x-button variant="secondary" size="sm"
                                @click="editPelanggan({{ json_encode([
                                    'id' => $pelanggan->id_pelanggan,
                                    'nama_lengkap' => $pelanggan->nama_lengkap,
                                    'kategori' => $pelanggan->kategori,
                                    'kontak' => $pelanggan->kontak,
                                    'alamat' => $pelanggan->alamat,
                                ]) }})">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                Edit
                            </x-button>
                            <x-button variant="danger" size="sm"
                                @click="$dispatch('confirm-delete', { action: '{{ route('master-data.pelanggan.destroy', $pelanggan->id_pelanggan) }}' })">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                Hapus
                            </x-button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-table>
        <div class="mt-6">{{ $pelanggans->links() }}</div>
    @else
        <x-empty-state message="Belum ada data pelanggan" icon="inbox" />
    @endif

    {{-- Modal Tambah --}}
    <x-modal id="tambah-pelanggan" title="Tambah Pelanggan Baru" size="xl">
        <form id="formTambahPelanggan" method="POST" action="{{ route('master-data.pelanggan.store') }}">
            @csrf
            <x-form-section title="Informasi Pelanggan" description="Lengkapi data pelanggan baru">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                    <x-input name="nama_lengkap" label="Nama Pelanggan" placeholder="Contoh: CV Telur Makmur" :required="true" hint="Maksimal 100 karakter, harus unik" />
                    <x-input name="kontak" label="No. Telp / Kontak" placeholder="Contoh: 08123456789" :required="true" hint="Maksimal 20 karakter" />
                    <x-select name="kategori" label="Kategori" :required="true"
                        :options="[
                            ['value' => 'Distributor', 'label' => 'Distributor'],
                            ['value' => 'Retail', 'label' => 'Retail'],
                            ['value' => 'Personal', 'label' => 'Personal'],
                        ]" />
                </div>
                <x-textarea name="alamat" label="Alamat" placeholder="Masukkan alamat lengkap pelanggan..." :required="true" hint="Alamat lengkap pelanggan" />

            </x-form-section>
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <x-button variant="secondary" type="button" @click="$dispatch('close-modal-tambah-pelanggan')">Batal</x-button>
                <x-button variant="primary" type="submit">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    Simpan Pelanggan
                </x-button>
            </div>
        </form>
    </x-modal>

    {{-- Modal Edit --}}
    <x-modal id="edit-pelanggan" title="Edit Data Pelanggan" size="xl">
        <form id="formEditPelanggan" method="POST" action="">
            @csrf
            @method('PUT')
            <x-form-section title="Informasi Pelanggan" description="Perbarui data pelanggan">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                    <div class="mb-4">
                        <label for="edit_nama_lengkap" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Pelanggan <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_lengkap" id="edit_nama_lengkap" required maxlength="100" placeholder="Contoh: CV Telur Makmur" class="w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Maksimal 100 karakter, harus unik</p>
                    </div>
                    <div class="mb-4">
                        <label for="edit_kontak" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">No. Telp / Kontak <span class="text-red-500">*</span></label>
                        <input type="text" name="kontak" id="edit_kontak" required maxlength="20" placeholder="Contoh: 08123456789" class="w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Maksimal 20 karakter</p>
                    </div>
                    <div class="mb-4">
                        <label for="edit_kategori" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori <span class="text-red-500">*</span></label>
                        <select name="kategori" id="edit_kategori" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="Distributor">Distributor</option>
                            <option value="Retail">Retail</option>
                            <option value="Personal">Personal</option>
                        </select>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="edit_alamat" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat <span class="text-red-500">*</span></label>
                    <textarea name="alamat" id="edit_alamat" required rows="3" placeholder="Masukkan alamat lengkap pelanggan..." class="w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"></textarea>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Alamat lengkap pelanggan</p>
                </div>

            </x-form-section>
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <x-button variant="secondary" type="button" @click="$dispatch('close-modal-edit-pelanggan')">Batal</x-button>
                <x-button variant="primary" type="submit">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    Simpan Perubahan
                </x-button>
            </div>
        </form>
    </x-modal>

    <x-confirm-dialog
        title="Hapus Data Pelanggan"
        message="Apakah Anda yakin ingin menghapus data pelanggan ini? Pelanggan yang masih memiliki transaksi penjualan tidak dapat dihapus."
        confirmLabel="Ya, Hapus"
        confirmVariant="danger"
    />
@endsection

@section('scripts')
<script>
    function resetForm() {
        const form = document.getElementById('formTambahPelanggan');
        if (form) form.reset();
    }

    function editPelanggan(data) {
        const form = document.getElementById('formEditPelanggan');
        form.action = '/master-data/pelanggan/' + data.id;

        document.getElementById('edit_nama_lengkap').value = data.nama_lengkap;
        document.getElementById('edit_kontak').value = data.kontak;
        document.getElementById('edit_kategori').value = data.kategori;
        document.getElementById('edit_alamat').value = data.alamat;

        window.dispatchEvent(new CustomEvent('open-modal-edit-pelanggan'));
    }
</script>
@endsection
