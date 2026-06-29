@extends('layouts.app')

@section('content')
    {{-- Breadcrumb --}}
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Master Data'],
        ['label' => 'Data Kandang'],
    ]" />

    {{-- Page Header --}}
    <x-page-header title="Data Kandang" subtitle="Kelola data kandang peternakan">
        <x-slot:action>
            <x-button variant="primary" @click="$dispatch('open-modal-tambah-kandang'); resetForm()">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Data
            </x-button>
        </x-slot:action>
    </x-page-header>

    {{-- Search & Filter --}}
    <x-filter-bar :action="route('master-data.kandang.index')">
        <x-search-field
            name="search"
            placeholder="Cari nama kandang..."
            :value="$search"
        />
    </x-filter-bar>

    {{-- Data Table --}}
    @if($kandangs->count() > 0)
        <x-table :headers="['No', 'Nama Kandang', 'Kapasitas', 'Populasi Saat Ini', 'Tahun Masuk', 'Status', 'Aksi']">
            @foreach($kandangs as $index => $kandang)
                <tr>
                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                        {{ $kandangs->firstItem() + $index }}
                    </td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">
                        {{ $kandang->nama_kandang }}
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                        {{ number_format($kandang->kapasitas_kandang, 0, ',', '.') }} ekor
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                        {{ number_format($kandang->populasi_saat_ini, 0, ',', '.') }} ekor
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                        {{ $kandang->tahun_masuk }}
                    </td>
                    <td class="px-4 py-3 text-sm">
                        @if(!$kandang->trashed())
                            <x-badge variant="success" dot>Aktif</x-badge>
                        @else
                            <x-badge variant="gray" dot>Non-Aktif</x-badge>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <div class="flex items-center space-x-2">
                            <x-button
                                variant="secondary"
                                size="sm"
                                @click="editKandang({{ json_encode([
                                    'id' => $kandang->id_kandang,
                                    'nama_kandang' => $kandang->nama_kandang,
                                    'kapasitas_kandang' => $kandang->kapasitas_kandang,
                                    'tahun_masuk' => $kandang->tahun_masuk,
                                    'status' => $kandang->trashed() ? 'non-aktif' : 'aktif',
                                ]) }})"
                            >
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                                Edit
                            </x-button>
                            <x-button
                                variant="danger"
                                size="sm"
                                @click="$dispatch('confirm-delete', { action: '{{ route('master-data.kandang.destroy', $kandang->id_kandang) }}' })"
                            >
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                                Hapus
                            </x-button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-table>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $kandangs->links() }}
        </div>
    @else
        <x-empty-state
            message="Belum ada data kandang"
            icon="inbox"
        />
    @endif

    {{-- Modal Tambah Kandang --}}
    <x-modal id="tambah-kandang" title="Tambah Kandang Baru" size="lg">
        <form id="formTambahKandang" method="POST" action="{{ route('master-data.kandang.store') }}">
            @csrf
            <x-form-section title="Informasi Kandang" description="Lengkapi data kandang baru">
                <x-input
                    name="nama_kandang"
                    label="Nama Kandang"
                    placeholder="Contoh: Kandang A1"
                    :required="true"
                    hint="Maksimal 50 karakter, harus unik"
                />

                <x-input
                    name="kapasitas_kandang"
                    label="Kapasitas Maksimal"
                    type="number"
                    placeholder="Contoh: 5000"
                    :required="true"
                    suffix="ekor"
                    hint="Jumlah ayam maksimal yang dapat ditampung"
                />

                <x-input
                    name="tahun_masuk"
                    label="Tahun Masuk"
                    type="number"
                    placeholder="Contoh: {{ date('Y') }}"
                    :required="true"
                    hint="Tahun mulai operasi (2000 - {{ date('Y') }})"
                />


            </x-form-section>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <x-button variant="secondary" type="button" @click="$dispatch('close-modal-tambah-kandang')">
                    Batal
                </x-button>
                <x-button variant="primary" type="submit">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Simpan Kandang
                </x-button>
            </div>
        </form>
    </x-modal>

    {{-- Modal Edit Kandang --}}
    <x-modal id="edit-kandang" title="Edit Data Kandang" size="lg">
        <form id="formEditKandang" method="POST" action="">
            @csrf
            @method('PUT')
            <x-form-section title="Informasi Kandang" description="Perbarui data kandang">
                <div class="mb-4">
                    <label for="edit_nama_kandang" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Nama Kandang <span class="text-red-500">*</span>
                    </label>
                    <div class="relative rounded-md shadow-sm">
                        <input
                            type="text"
                            name="nama_kandang"
                            id="edit_nama_kandang"
                            placeholder="Contoh: Kandang A1"
                            required
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm sm:text-sm"
                        >
                    </div>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Maksimal 50 karakter, harus unik</p>
                    @error('nama_kandang')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="edit_kapasitas_kandang" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Kapasitas Maksimal <span class="text-red-500">*</span>
                    </label>
                    <div class="relative rounded-md shadow-sm">
                        <input
                            type="number"
                            name="kapasitas_kandang"
                            id="edit_kapasitas_kandang"
                            placeholder="Contoh: 5000"
                            required
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm sm:text-sm pr-12"
                        >
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                            <span class="text-gray-500 dark:text-gray-400 sm:text-sm">ekor</span>
                        </div>
                    </div>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Jumlah ayam maksimal yang dapat ditampung</p>
                    @error('kapasitas_kandang')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="edit_tahun_masuk" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Tahun Masuk <span class="text-red-500">*</span>
                    </label>
                    <div class="relative rounded-md shadow-sm">
                        <input
                            type="number"
                            name="tahun_masuk"
                            id="edit_tahun_masuk"
                            placeholder="Contoh: {{ date('Y') }}"
                            required
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm sm:text-sm"
                        >
                    </div>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Tahun mulai operasi (2000 - {{ date('Y') }})</p>
                    @error('tahun_masuk')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="edit_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select
                        name="status"
                        id="edit_status"
                        required
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm sm:text-sm"
                    >
                        <option value="aktif">Aktif</option>
                        <option value="non-aktif">Non-Aktif</option>
                    </select>
                </div>

            </x-form-section>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <x-button variant="secondary" type="button" @click="$dispatch('close-modal-edit-kandang')">
                    Batal
                </x-button>
                <x-button variant="primary" type="submit">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Simpan Perubahan
                </x-button>
            </div>
        </form>
    </x-modal>

    {{-- Confirm Delete Dialog --}}
    <x-confirm-dialog
        title="Hapus Data Kandang"
        message="Apakah Anda yakin ingin menghapus data kandang ini? Kandang yang memiliki batch aktif tidak dapat dihapus."
        confirmLabel="Ya, Hapus"
        confirmVariant="danger"
    />
@endsection

@section('scripts')
<script>
    function resetForm() {
        const form = document.getElementById('formTambahKandang');
        if (form) form.reset();
    }

    function editKandang(data) {
        // Set form action
        const form = document.getElementById('formEditKandang');
        form.action = '/master-data/kandang/' + data.id;

        // Set field values using unique edit_ prefixed IDs
        document.getElementById('edit_nama_kandang').value = data.nama_kandang;
        document.getElementById('edit_kapasitas_kandang').value = data.kapasitas_kandang;
        document.getElementById('edit_tahun_masuk').value = data.tahun_masuk;
        document.getElementById('edit_status').value = data.status;

        // Open modal
        window.dispatchEvent(new CustomEvent('open-modal-edit-kandang'));
    }
</script>
@endsection

