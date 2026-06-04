@extends('layouts.app')

@section('content')
<div class="space-y-12 pb-24">
    <x-page-header title="Component Library" subtitle="Preview all reusable UI components">
        <x-slot:action>
            <x-button variant="primary" icon="plus">Primary Action</x-button>
        </x-slot:action>
    </x-page-header>

    <x-form-section title="1. Buttons" description="All button variants and sizes">
        <div class="flex flex-wrap gap-4 items-center">
            <x-button variant="primary">Primary</x-button>
            <x-button variant="secondary">Secondary</x-button>
            <x-button variant="danger">Danger</x-button>
            <x-button variant="success">Success</x-button>
            <x-button variant="warning">Warning</x-button>
        </div>
        <div class="flex flex-wrap gap-4 items-center mt-4">
            <x-button size="sm">Small</x-button>
            <x-button size="md">Medium</x-button>
            <x-button size="lg">Large</x-button>
            <x-button disabled>Disabled</x-button>
            <x-button icon="check">With Icon</x-button>
        </div>
    </x-form-section>

    <x-form-section title="2 & 3. Input & Textarea" description="Form controls">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-input label="Nama Barang" name="nama_barang" required />
            <x-input label="Harga" name="harga" type="number" prefix="Rp" />
            <x-input label="Berat" name="berat" type="number" suffix="kg" hint="Dalam satuan kilogram" />
            <x-input label="Disabled Input" name="disabled" disabled value="Cannot type here" />
        </div>
        <div class="mt-4">
            <x-textarea label="Deskripsi" name="deskripsi" placeholder="Tuliskan deskripsi..." />
        </div>
    </x-form-section>

    <x-form-section title="4, 5, 6. Select, Checkbox, Radio, Toggle">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-select label="Kategori" name="kategori" :options="['A' => 'Kategori A', 'B' => 'Kategori B']" required />
            
            <div class="space-y-4 pt-6">
                <x-checkbox label="Dapat Dijual?" name="dapat_dijual" checked />
                <x-radio label="Pembayaran Lunas" name="metode" value="lunas" checked />
                <x-toggle label="Status Aktif" name="status" checked />
            </div>
        </div>
    </x-form-section>

    <x-form-section title="7. Card" description="Basic card layout">
        <x-card title="Data Kandang" subtitle="Informasi kandang utama">
            <p class="text-gray-600">Konten card...</p>
            <x-slot:footer>
                <div class="text-right">
                    <x-button>Simpan</x-button>
                </div>
            </x-slot:footer>
        </x-card>
    </x-form-section>

    <x-form-section title="8. Stat Card">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <x-stat-card title="Total Populasi" value="12,500 ekor" icon="users" color="blue" />
            <x-stat-card title="Produksi Hari Ini" value="8,340 butir" icon="sparkles" color="green" trend="up" trendValue="+3.2%" />
            <x-stat-card title="Ayam Afkir" value="120 ekor" icon="exclamation-triangle" color="red" trend="down" trendValue="-1.2%" />
        </div>
    </x-form-section>

    <x-form-section title="9. Table & Pagination">
        <x-table :headers="['No', 'Nama', 'Kategori', 'Stok', 'Aksi']">
            <tr>
                <x-table-cell>1</x-table-cell>
                <x-table-cell>Pakan Ayam Layer</x-table-cell>
                <x-table-cell><x-badge variant="success">Pakan</x-badge></x-table-cell>
                <x-table-cell>500 kg</x-table-cell>
                <x-table-cell>
                    <x-button variant="secondary" size="sm" icon="pencil">Edit</x-button>
                </x-table-cell>
            </tr>
            <tr>
                <x-table-cell>2</x-table-cell>
                <x-table-cell>Vitamin C</x-table-cell>
                <x-table-cell><x-badge variant="warning">Obat</x-badge></x-table-cell>
                <x-table-cell>50 botol</x-table-cell>
                <x-table-cell>
                    <x-button variant="secondary" size="sm" icon="pencil">Edit</x-button>
                </x-table-cell>
            </tr>
        </x-table>
        <div class="mt-4 text-sm text-gray-500">Note: Pagination uses standard Laravel views styled with Tailwind.</div>
    </x-form-section>

    <x-form-section title="11. Badges">
        <div class="flex flex-wrap gap-4">
            <x-badge variant="success">Aktif</x-badge>
            <x-badge variant="danger">Kritis</x-badge>
            <x-badge variant="warning" dot>Tempo</x-badge>
            <x-badge variant="info">Info</x-badge>
            <x-badge variant="gray">Draft</x-badge>
        </div>
    </x-form-section>

    <x-form-section title="12. Alerts">
        <div class="space-y-4">
            <x-alert type="success" title="Berhasil!">Data telah disimpan.</x-alert>
            <x-alert type="error" title="Gagal">Terjadi kesalahan pada sistem.</x-alert>
            <x-alert type="warning">Peringatan: Stok hampir habis.</x-alert>
            <x-alert type="info">Info: Sistem akan maintenance jam 12 malam.</x-alert>
        </div>
    </x-form-section>

    <x-form-section title="13. Modal">
        <x-button @click="$dispatch('open-modal-demo')">Open Modal Demo</x-button>
        
        <x-modal id="demo" title="Contoh Modal">
            <p class="text-gray-600">Ini adalah isi dari modal dialog.</p>
            <x-slot:footer>
                <x-button variant="secondary" @click="$dispatch('close-modal-demo')">Batal</x-button>
                <x-button>Simpan</x-button>
            </x-slot:footer>
        </x-modal>
    </x-form-section>
    
    <x-form-section title="18. Empty State">
        <x-card>
            <x-empty-state message="Belum ada data kandang" actionLabel="Tambah Kandang" actionUrl="#" />
        </x-card>
    </x-form-section>
    
    <x-confirm-dialog title="Hapus Data" message="Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan." />
    
    <!-- Confirm Dialog Trigger Demo -->
    <x-form-section title="20. Confirm Dialog">
        <x-button variant="danger" @click="$dispatch('confirm-delete', {action: '/dev/components'})">Test Delete Modal</x-button>
    </x-form-section>

</div>
@endsection
