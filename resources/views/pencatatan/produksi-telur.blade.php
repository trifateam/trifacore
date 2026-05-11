<x-layouts.app title="Produksi Telur">
    <div class="page-header">
        <h1>📊 Pencatatan Produksi Telur</h1>
        <p>Form pencatatan produksi telur harian per kandang</p>
    </div>

    <div class="card form-card">
        <div class="card-header">
            <h6 class="mb-0 fw-bold">Form Produksi Telur</h6>
        </div>
        <div class="card-body">
            <form>
                <div class="row">
                    <div class="col-md-6">
                        <x-form-input name="tanggal" label="Tanggal" type="date" :value="date('Y-m-d')" required />
                    </div>
                    <div class="col-md-6">
                        <x-form-select name="kandang_id" label="Kandang" :options="['1' => 'Kandang A', '2' => 'Kandang B', '3' => 'Kandang C']" required />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <x-form-input name="jumlah_telur" label="Jumlah Telur (butir)" type="number" required placeholder="0" />
                    </div>
                    <div class="col-md-4">
                        <x-form-input name="telur_rusak" label="Telur Rusak (butir)" type="number" placeholder="0" />
                    </div>
                    <div class="col-md-4">
                        <x-form-input name="berat_total" label="Berat Total (kg)" type="number" placeholder="0.0" />
                    </div>
                </div>
                <x-form-input name="keterangan" label="Keterangan" type="textarea" placeholder="Catatan tambahan..." />

                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary" onclick="alert('Fitur simpan belum tersedia.')">
                        💾 Simpan
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
