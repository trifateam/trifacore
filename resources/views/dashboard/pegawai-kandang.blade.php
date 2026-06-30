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
        1. SUMMARY CARDS
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
