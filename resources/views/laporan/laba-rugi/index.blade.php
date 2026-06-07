@extends('layouts.app')

@section('title', 'Laporan Laba Rugi')

@section('content')
    <x-page-header title="Laporan Laba Rugi (Profit & Loss)">
        <x-button variant="primary" onclick="window.print()">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            Cetak Laporan
        </x-button>
    </x-page-header>

    <!-- Filter Bar -->
    <x-card class="mb-6 print:hidden">
        <form id="filter-form" class="flex flex-wrap items-end gap-4" onsubmit="event.preventDefault(); generateReport();">
            <div class="w-full md:w-1/3">
                <x-select name="bulan" label="Bulan">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" @selected($m == date('n'))>{{ date('F', mktime(0, 0, 0, $m, 10)) }}</option>
                    @endforeach
                </x-select>
            </div>

            <div class="w-full md:w-1/3">
                <x-select name="tahun" label="Tahun">
                    @foreach($years as $y)
                        <option value="{{ $y }}" @selected($y == date('Y'))>{{ $y }}</option>
                    @endforeach
                </x-select>
            </div>

            <div class="w-full md:w-auto mb-4">
                <x-button type="submit" variant="primary">Generate Laporan</x-button>
            </div>
        </form>
    </x-card>

    <!-- Loading State -->
    <div id="loading" class="hidden text-center py-10 print:hidden">
        <svg class="animate-spin h-10 w-10 text-indigo-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="mt-4 text-gray-500">Menyusun laporan Laba Rugi...</p>
    </div>

    <!-- Report Content -->
    <div id="report-content" class="hidden">
        
        <x-card class="max-w-4xl mx-auto">
            <div class="text-center mb-8 border-b pb-4">
                <h2 class="text-2xl font-bold text-gray-800 uppercase">Laporan Laba Rugi</h2>
                <p class="text-gray-500 mt-1" id="periode-label">Periode: -</p>
            </div>

            <!-- SECTION A: ARUS KAS MASUK -->
            <div class="mb-8">
                <h3 class="text-lg font-bold text-gray-800 border-b border-gray-200 pb-2 mb-4 bg-gray-50 px-4 py-2 rounded-t-md">Pendapatan / Arus Kas Masuk</h3>
                
                <table class="w-full text-sm">
                    <tbody class="divide-y divide-gray-100">
                        <tr>
                            <td class="py-3 px-4 text-gray-600 pl-8">Penjualan Telur</td>
                            <td class="py-3 px-4 text-right font-medium text-gray-900" id="in_telur">Rp 0</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 text-gray-600 pl-8">Penjualan Ayam Afkir</td>
                            <td class="py-3 px-4 text-right font-medium text-gray-900" id="in_afkir">Rp 0</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 text-gray-600 pl-8">Penjualan Pupuk Kandang</td>
                            <td class="py-3 px-4 text-right font-medium text-gray-900" id="in_pupuk">Rp 0</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 text-gray-600 pl-8">Pelunasan Piutang Bulan Ini</td>
                            <td class="py-3 px-4 text-right font-medium text-gray-900" id="in_piutang">Rp 0</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="bg-green-50">
                            <td class="py-4 px-4 font-bold text-green-800 uppercase">Total Kas Masuk</td>
                            <td class="py-4 px-4 text-right font-bold text-green-800 text-lg" id="in_total">Rp 0</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- SECTION B: ARUS KAS KELUAR -->
            <div class="mb-8">
                <h3 class="text-lg font-bold text-gray-800 border-b border-gray-200 pb-2 mb-4 bg-gray-50 px-4 py-2 rounded-t-md">Pengeluaran / Arus Kas Keluar</h3>
                
                <table class="w-full text-sm">
                    <tbody class="divide-y divide-gray-100">
                        <tr>
                            <td class="py-3 px-4 text-gray-600 pl-8">Pembelian Pakan</td>
                            <td class="py-3 px-4 text-right font-medium text-gray-900" id="out_pakan">Rp 0</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 text-gray-600 pl-8">Pembelian Vitamin</td>
                            <td class="py-3 px-4 text-right font-medium text-gray-900" id="out_vitamin">Rp 0</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 text-gray-600 pl-8">Pembelian Pullet</td>
                            <td class="py-3 px-4 text-right font-medium text-gray-900" id="out_pullet">Rp 0</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 text-gray-600 pl-8">Pelunasan Hutang Bulan Ini</td>
                            <td class="py-3 px-4 text-right font-medium text-gray-900" id="out_hutang">Rp 0</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700 bg-gray-50" colspan="2">Biaya Operasional:</td>
                        </tr>
                    </tbody>
                    <tbody id="ops_breakdown" class="divide-y divide-gray-100">
                        <!-- Breakdown injected here -->
                    </tbody>
                    <tfoot>
                        <tr class="bg-red-50">
                            <td class="py-4 px-4 font-bold text-red-800 uppercase border-t border-red-200">Total Kas Keluar</td>
                            <td class="py-4 px-4 text-right font-bold text-red-800 text-lg border-t border-red-200" id="out_total">Rp 0</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- SECTION C: BOTTOM LINE -->
            <div class="border-t-2 border-gray-800 pt-6 mt-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold uppercase tracking-wider text-gray-900">Net Profit / Loss</h3>
                    <div id="net_profit" class="text-2xl font-black px-4 py-2 rounded-lg">Rp 0</div>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 font-medium">Profit Margin</span>
                    <span id="profit_margin" class="text-lg font-bold text-gray-900 bg-gray-100 px-3 py-1 rounded">0%</span>
                </div>
            </div>

        </x-card>
    </div>
@endsection

@push('scripts')
<script>
    function generateReport() {
        const form = document.getElementById('filter-form');
        const formData = new FormData(form);
        const searchParams = new URLSearchParams(formData);

        const bulanSelect = document.querySelector('select[name="bulan"]');
        const tahunSelect = document.querySelector('select[name="tahun"]');
        const bulanText = bulanSelect.options[bulanSelect.selectedIndex].text;
        const tahunText = tahunSelect.options[tahunSelect.selectedIndex].text;

        document.getElementById('loading').classList.remove('hidden');
        document.getElementById('report-content').classList.add('hidden');

        fetch(`{{ route('laporan.laba-rugi.generate') }}?${searchParams.toString()}`)
            .then(response => response.json())
            .then(data => {
                
                document.getElementById('periode-label').innerText = `Periode: ${bulanText} ${tahunText}`;

                // Section A
                document.getElementById('in_telur').innerText = data.kas_masuk.penjualan_telur;
                document.getElementById('in_afkir').innerText = data.kas_masuk.penjualan_afkir;
                document.getElementById('in_pupuk').innerText = data.kas_masuk.penjualan_pupuk;
                document.getElementById('in_piutang').innerText = data.kas_masuk.pelunasan_piutang;
                document.getElementById('in_total').innerText = data.kas_masuk.total;

                // Section B
                document.getElementById('out_pakan').innerText = data.kas_keluar.pembelian_pakan;
                document.getElementById('out_vitamin').innerText = data.kas_keluar.pembelian_vitamin;
                document.getElementById('out_pullet').innerText = data.kas_keluar.pembelian_pullet;
                document.getElementById('out_hutang').innerText = data.kas_keluar.pelunasan_hutang;
                document.getElementById('out_total').innerText = data.kas_keluar.total;

                const opsTbody = document.getElementById('ops_breakdown');
                opsTbody.innerHTML = '';
                if(data.kas_keluar.operasional_breakdown.length > 0) {
                    data.kas_keluar.operasional_breakdown.forEach(item => {
                        opsTbody.innerHTML += `
                            <tr>
                                <td class="py-2 px-4 text-gray-500 pl-12 text-sm">→ ${item.kategori}</td>
                                <td class="py-2 px-4 text-right font-medium text-gray-700 text-sm">${item.total}</td>
                            </tr>
                        `;
                    });
                } else {
                    opsTbody.innerHTML = `
                        <tr>
                            <td class="py-2 px-4 text-gray-400 pl-12 text-sm italic" colspan="2">Tidak ada biaya operasional bulan ini</td>
                        </tr>
                    `;
                }

                // Section C
                const netEl = document.getElementById('net_profit');
                netEl.innerText = data.bottom_line.net;
                
                if(data.bottom_line.net_raw > 0) {
                    netEl.className = 'text-2xl font-black px-4 py-2 rounded-lg bg-green-100 text-green-700';
                } else if(data.bottom_line.net_raw < 0) {
                    netEl.className = 'text-2xl font-black px-4 py-2 rounded-lg bg-red-100 text-red-700';
                } else {
                    netEl.className = 'text-2xl font-black px-4 py-2 rounded-lg bg-gray-100 text-gray-700';
                }

                document.getElementById('profit_margin').innerText = data.bottom_line.margin;

                document.getElementById('loading').classList.add('hidden');
                document.getElementById('report-content').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error fetching report:', error);
                document.getElementById('loading').classList.add('hidden');
                alert('Terjadi kesalahan saat menyusun laporan Laba Rugi.');
            });
    }

    // Load initial data
    document.addEventListener('DOMContentLoaded', () => {
        generateReport();
    });
</script>
@endpush
