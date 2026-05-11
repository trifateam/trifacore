<x-layouts.app title="{{ isset($pegawai) ? 'Edit Pegawai' : 'Tambah Pegawai' }}">
    <div class="page-header">
        <h1>👤 {{ isset($pegawai) ? 'Edit Pegawai' : 'Tambah Pegawai' }}</h1>
        <p>{{ isset($pegawai) ? 'Ubah data pegawai' : 'Tambahkan data pegawai baru' }}</p>
    </div>

    <div class="card form-card">
        <div class="card-header">
            <h6 class="mb-0 fw-bold">Form Pegawai</h6>
        </div>
        <div class="card-body">
            <form action="{{ isset($pegawai) ? route('pegawai.update', $pegawai) : route('pegawai.store') }}" method="POST">
                @csrf
                @if(isset($pegawai))
                    @method('PUT')
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <x-form-input name="nama" label="Nama Lengkap" :value="$pegawai->nama ?? ''" required placeholder="Masukkan nama lengkap" />
                    </div>
                    <div class="col-md-6">
                        <x-form-input name="jabatan" label="Jabatan" :value="$pegawai->jabatan ?? ''" required placeholder="Contoh: Operator, Supervisor" />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <x-form-input name="no_hp" label="No HP" :value="$pegawai->no_hp ?? ''" placeholder="08xxxxxxxxxx" />
                    </div>
                    <div class="col-md-6">
                        <x-form-input name="tanggal_masuk" label="Tanggal Masuk" type="date" :value="isset($pegawai) ? $pegawai->tanggal_masuk->format('Y-m-d') : date('Y-m-d')" required />
                    </div>
                </div>

                <x-form-input name="alamat" label="Alamat" type="textarea" :value="$pegawai->alamat ?? ''" placeholder="Masukkan alamat lengkap" />

                <x-form-select name="status" label="Status" :options="['aktif' => 'Aktif', 'nonaktif' => 'Nonaktif']" :selected="$pegawai->status ?? 'aktif'" required />

                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">
                        💾 {{ isset($pegawai) ? 'Update' : 'Simpan' }}
                    </button>
                    <a href="{{ route('pegawai.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
