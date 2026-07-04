@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        @if($pakanKritis->isNotEmpty())
            <x-alert type="error" title="Peringatan Stok Pakan Minimum" :dismissible="true">
                <ul class="list-disc pl-5 mt-1">
                    @foreach($pakanKritis as $pakan)
                        <li>Stok <strong>{{ $pakan->nama_barang }}</strong> tersisa {{ number_format($pakan->stok_barang, 0) }}
                            {{ $pakan->satuan }} (Minimum: {{ number_format($pakan->stok_minimum, 0) }} {{ $pakan->satuan }}).
                            Beri tahu gudang atau admin!</li>
                    @endforeach
                </ul>
            </x-alert>
        @endif

        {{-- Page Header --}}
        <x-page-header title="Dashboard Pegawai Kandang"
            subtitle="Ringkasan operasional kandang — {{ now()->translatedFormat('l, d F Y') }}">
            <x-slot:action>
                <x-button variant="secondary" onclick="window.location.reload()">
                    <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Refresh
                </x-button>
            </x-slot:action>
        </x-page-header>

        {{-- ═══════════════════════════════════════════════════════════
        1. TUGAS HARIAN BELUM SELESAI
        ═══════════════════════════════════════════════════════════ --}}
        @if(isset($uncompletedTasks['has_any_task']) && $uncompletedTasks['has_any_task'])
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <h3 class="text-base font-bold uppercase tracking-wider text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                <svg class="w-5 h-5 text-red-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                Tugas Harian Belum Selesai
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @if(isset($uncompletedTasks['telur']) && count($uncompletedTasks['telur']) > 0)
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4 flex flex-col justify-between">
                    <div>
                        <h4 class="font-bold text-red-800 dark:text-red-400 mb-1 flex items-center">
                            <span class="w-2 h-2 rounded-full bg-red-500 mr-2"></span> Produksi Telur
                        </h4>
                        <p class="text-sm text-red-600 dark:text-red-300">Ada {{ count($uncompletedTasks['telur']) }} batch yang belum dicatat hari ini.</p>
                    </div>
                    <a href="/pencatatan/produksi-telur" class="mt-4 inline-flex items-center text-sm font-medium text-red-700 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                        Isi Sekarang <svg class="ml-1 w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </div>
                @endif
                
                @if(isset($uncompletedTasks['pakan']) && count($uncompletedTasks['pakan']) > 0)
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4 flex flex-col justify-between">
                    <div>
                        <h4 class="font-bold text-red-800 dark:text-red-400 mb-1 flex items-center">
                            <span class="w-2 h-2 rounded-full bg-red-500 mr-2"></span> Konsumsi Pakan
                        </h4>
                        <p class="text-sm text-red-600 dark:text-red-300">Ada {{ count($uncompletedTasks['pakan']) }} batch yang belum dicatat hari ini.</p>
                    </div>
                    <a href="/pencatatan/konsumsi-pakan" class="mt-4 inline-flex items-center text-sm font-medium text-red-700 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                        Isi Sekarang <svg class="ml-1 w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </div>
                @endif

                @if(isset($uncompletedTasks['suhu']) && count($uncompletedTasks['suhu']) > 0)
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4 flex flex-col justify-between">
                    <div>
                        <h4 class="font-bold text-red-800 dark:text-red-400 mb-1 flex items-center">
                            <span class="w-2 h-2 rounded-full bg-red-500 mr-2"></span> Suhu Lingkungan
                        </h4>
                        <p class="text-sm text-red-600 dark:text-red-300">Ada {{ count($uncompletedTasks['suhu']) }} kandang yang belum dicatat hari ini.</p>
                    </div>
                    <a href="/pencatatan/suhu" class="mt-4 inline-flex items-center text-sm font-medium text-red-700 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                        Isi Sekarang <svg class="ml-1 w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- ═══════════════════════════════════════════════════════════
        2. SUMMARY CARDS
        ═══════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            {{-- Total Populasi Ayam (Biru) --}}
            <x-stat-card title="Total Populasi Ayam" :value="number_format($totalPopulasi)" icon="home-modern"
                color="blue" />

            {{-- Produksi Telur Hari Ini (Hijau) --}}
            <x-stat-card title="Produksi Telur Hari Ini" :value="number_format($produksiHariIni) . ' butir'"
                icon="circle-stack" color="green" />
        </div>

        {{-- ═══════════════════════════════════════════════════════════
        2. GRAFIK TREN PRODUKSI 7 HARI TERAKHIR
        ═══════════════════════════════════════════════════════════ --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 overflow-hidden relative">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
                <h3 class="text-base font-bold uppercase tracking-wider text-gray-900 dark:text-gray-100" id="chart-title">
                    Tren Produksi Telur — 7 Hari Terakhir
                </h3>
            </div>
            <div class="relative w-full h-80">
                <canvas id="chartProduksi"></canvas>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let chartInstance = null;
        const labelsTerakhir = @json($chartLabels);
        const dataTerakhir = @json($chartData);

        function initChart(labels, data, color, bgColor) {
            const ctx = document.getElementById('chartProduksi');
            if (!ctx) return;

            if (chartInstance) {
                chartInstance.destroy();
            }

            chartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Produksi Telur',
                        data: data,
                        borderColor: color,
                        backgroundColor: bgColor,
                        borderWidth: 2.5,
                        pointBackgroundColor: color,
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        fill: true,
                        tension: 0.4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1f2937',
                            titleFont: { size: 13, weight: '600' },
                            bodyFont: { size: 12 },
                            padding: 12,
                            cornerRadius: 8,
                            displayColors: false,
                            callbacks: {
                                label: function (context) {
                                    return context.parsed.y.toLocaleString('id-ID') + ' butir';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 12 }, color: '#6b7280' }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f3f4f6' },
                            ticks: {
                                font: { size: 12 },
                                color: '#6b7280',
                                callback: function (value) { return value.toLocaleString('id-ID'); }
                            }
                        }
                    }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            const renderChart = () => {
                if (typeof window.Chart === 'undefined') {
                    setTimeout(renderChart, 100);
                    return;
                }
                initChart(labelsTerakhir, dataTerakhir, '#10b981', 'rgba(16, 185, 129, 0.1)');
            };
            renderChart();
        });
    </script>
@endsection
