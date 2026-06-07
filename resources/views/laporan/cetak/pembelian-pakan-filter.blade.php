@extends('layouts.app')

@section('content')
    <x-page-header title="Cetak Laporan Pembelian Pakan & Vitamin">
        <x-breadcrumb :items="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Cetak Laporan Pembelian']
        ]" />
    </x-page-header>

    <div class="max-w-3xl mx-auto">
        <x-card title="Filter Laporan Pembelian">
            <form id="print-form" action="{{ route('laporan.cetak.pembelian-pakan.preview') }}" method="GET" target="_blank">
                <x-form-section title="Pilih Parameter">
                    <div class="mb-4">
                        <x-select name="supplier_id" label="Supplier" required>
                            <option value="all">Semua Supplier</option>
                            @foreach($suppliers as $s)
                                <option value="{{ $s->id_supplier }}">{{ $s->nama_supplier }}</option>
                            @endforeach
                        </x-select>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-select name="bulan" label="Bulan" required>
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" @selected($m == date('n'))>{{ \Carbon\Carbon::createFromFormat('n', $m)->translatedFormat('F') }}</option>
                            @endforeach
                        </x-select>

                        <x-select name="tahun" label="Tahun" required>
                            @foreach($years as $y)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endforeach
                        </x-select>
                    </div>
                </x-form-section>

                <div class="mt-6 flex justify-end gap-3">
                    <x-button type="submit" variant="secondary" onclick="document.getElementById('print-form').action='{{ route('laporan.cetak.pembelian-pakan.preview') }}'">
                        Lihat Preview
                    </x-button>
                    <x-button type="submit" variant="primary" onclick="document.getElementById('print-form').action='{{ route('laporan.cetak.pembelian-pakan.pdf') }}'">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Cetak ke PDF
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>
@endsection
