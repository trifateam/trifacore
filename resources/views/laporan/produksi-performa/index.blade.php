@extends('layouts.app')

@section('title', 'Laporan Produksi & Performa')

@section('content')
    <x-page-header title="Laporan Produksi & Performa">
        <x-button variant="primary" onclick="window.print()">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            Cetak Laporan
        </x-button>
    </x-page-header>

    <!-- Filter Bar -->
    <x-card class="mb-6">
        <form id="filter-form" class="flex flex-wrap items-end gap-4" onsubmit="event.preventDefault(); generateReport();">
            <div class="w-full md:w-1/4">
                <x-select name="kandang_id" label="Kandang">
                    <option value="all">Semua Kandang</option>
                    @foreach($kandangs as $kandang)
                        <option value="{{ $kandang->id_kandang }}">{{ $kandang->nama_kandang }}</option>
                    @endforeach
                </x-select>
            </div>
            
            <div class="w-full md:w-1/4">
                <x-select name="bulan" label="Bulan">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" @selected($m == date('n'))>{{ date('F', mktime(0, 0, 0, $m, 10)) }}</option>
                    @endforeach
                </x-select>
            </div>

            <div class="w-full md:w-1/4">
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
    <div id="loading" class="hidden text-center py-10">
        <svg class="animate-spin h-10 w-10 text-indigo-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="mt-4 text-gray-500">Memuat data laporan...</p>
    </div>

    <!-- Report Content -->
    <div id="report-content" class="hidden">
        
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">
            <x-stat-card 
                title="Total Produksi Telur" 
                value="0" 
                icon="chart-bar" 
                id="sum_total_produksi_card" />
                
            <x-stat-card 
                title="Rata-rata HDP" 
                value="0%" 
                icon="chart-pie" 
                id="sum_rata_hdp_card" />
                
            <x-stat-card 
                title="Total Mortalitas & Afkir" 
                value="0" 
                icon="exclamation-circle" 
                id="sum_total_mortalitas_card" />
                
            <x-stat-card 
                title="Estimasi Revenue" 
                value="Rp 0" 
                icon="currency-dollar" 
                id="sum_estimasi_revenue_card" />
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Line Chart -->
            <div class="lg:col-span-2">
                <x-chart-wrapper title="Tren Produksi Harian" id="lineChartContainer">
                    <canvas id="lineChart" height="250"></canvas>
                </x-chart-wrapper>
            </div>
            
            <!-- Pie Chart -->
            <div class="lg:col-span-1">
                <x-chart-wrapper title="Distribusi per Kandang" id="pieChartContainer">
                    <canvas id="pieChart" height="250"></canvas>
                </x-chart-wrapper>
            </div>
        </div>

        <!-- Detail Table -->
        <x-card title="Detail Produksi Harian">
            <div class="overflow-x-auto">
                <x-table>
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kandang</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Batch</th>
                            <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">RB</th>
                            <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">MB</th>
                            <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">MK</th>
                            <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Pecah</th>
                            <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider text-indigo-600 font-bold">Total</th>
                            <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Populasi</th>
                            <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">HDP %</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="bg-white divide-y divide-gray-200">
                        <!-- Data rows injected here -->
                    </tbody>
                </x-table>
            </div>
        </x-card>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let lineChartInstance = null;
    let pieChartInstance = null;

    function generateReport() {
        const form = document.getElementById('filter-form');
        const formData = new FormData(form);
        const searchParams = new URLSearchParams(formData);

        document.getElementById('loading').classList.remove('hidden');
        document.getElementById('report-content').classList.add('hidden');

        fetch(`{{ route('laporan.produksi-performa.generate') }}?${searchParams.toString()}`)
            .then(response => response.json())
            .then(data => {
                // Update Summary Cards
                const cardMap = {
                    'sum_total_produksi_card': data.summary.total_produksi,
                    'sum_rata_hdp_card': data.summary.rata_hdp,
                    'sum_total_mortalitas_card': data.summary.total_mortalitas,
                    'sum_estimasi_revenue_card': data.summary.estimasi_revenue
                };

                for (const [id, value] of Object.entries(cardMap)) {
                    const card = document.getElementById(id);
                    if(card) {
                        const valElem = card.querySelector('.text-2xl');
                        if(valElem) {
                            valElem.innerText = value;
                        }
                    }
                }

                // Update Line Chart
                updateLineChart(data.chart_line);
                
                // Update Pie Chart
                updatePieChart(data.chart_pie);

                // Update Table
                updateTable(data.table_data);

                document.getElementById('loading').classList.add('hidden');
                document.getElementById('report-content').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error fetching report:', error);
                document.getElementById('loading').classList.add('hidden');
                alert('Terjadi kesalahan saat memuat laporan.');
            });
    }

    function updateLineChart(chartData) {
        const ctx = document.getElementById('lineChart').getContext('2d');
        
        if (lineChartInstance) {
            lineChartInstance.destroy();
        }
        
        lineChartInstance = new Chart(ctx, {
            type: 'line',
            data: chartData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    function updatePieChart(chartData) {
        const ctx = document.getElementById('pieChart').getContext('2d');
        
        if (pieChartInstance) {
            pieChartInstance.destroy();
        }
        
        pieChartInstance = new Chart(ctx, {
            type: 'pie',
            data: chartData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }

    function updateTable(tableData) {
        const tbody = document.getElementById('tableBody');
        tbody.innerHTML = '';
        
        if (tableData.length === 0) {
            tbody.innerHTML = `<tr><td colspan="10" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data untuk periode ini</td></tr>`;
            return;
        }

        tableData.forEach(row => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${row.tanggal}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${row.kandang}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${row.batch}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">${row.rb}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">${row.mb}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">${row.mk}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">${row.pecah}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-indigo-600 text-right">${row.total}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">${row.populasi}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${row.hdp >= 80 ? 'bg-green-100 text-green-800' : (row.hdp >= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')}">
                        ${row.hdp}%
                    </span>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    // Load initial data
    document.addEventListener('DOMContentLoaded', () => {
        generateReport();
    });
</script>
@endpush
