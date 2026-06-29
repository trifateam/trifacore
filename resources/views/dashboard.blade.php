@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        @if($pakanKritis->isNotEmpty())
            <x-alert type="error" title="Peringatan Stok Pakan Minimum" :dismissible="true">
                <ul class="list-disc pl-5 mt-1">
                    @foreach($pakanKritis as $pakan)
                        <li>Stok <strong>{{ $pakan->nama_barang }}</strong> tersisa {{ number_format($pakan->stok_barang, 0) }}
                            {{ $pakan->satuan }} (Minimum: {{ number_format($pakan->stok_minimum, 0) }} {{ $pakan->satuan }}).
                            Segera lakukan pembelian!</li>
                    @endforeach
                </ul>
            </x-alert>
        @endif

        {{-- Page Header --}}
        <x-page-header title="Dashboard"
            subtitle="Ringkasan eksekutif peternakan — {{ now()->translatedFormat('l, d F Y') }}">
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
        1. SUMMARY CARDS — 4 kolom (atau 3 jika saldo hidden)
        ═══════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 {{ $showSaldoKas ? 'lg:grid-cols-4' : 'lg:grid-cols-3' }} gap-5">

            {{-- Total Populasi Ayam (Biru) --}}
            <x-stat-card title="Total Populasi Ayam" :value="number_format($totalPopulasi)" icon="home-modern"
                color="blue" />

            {{-- Produksi Telur Hari Ini (Hijau) --}}
            <x-stat-card title="Produksi Telur Hari Ini" :value="number_format($produksiHariIni) . ' butir'"
                icon="circle-stack" color="green" />

            {{-- Stok Kritis (Merah) --}}
            <x-stat-card title="Stok Kritis" :value="number_format($stokKritis) . ' barang'" icon="exclamation-triangle"
                color="red" />

            {{-- Saldo Kas Total (Kuning) — HIDDEN untuk Pegawai Kandang, Sales, Pegawai Gudang --}}
            @if($showSaldoKas)
                <x-stat-card title="Saldo Kas Total" :value="\App\Helpers\RupiahFormatter::format($saldoKas)" icon="banknotes"
                    color="yellow" />
            @endif

        </div>

        {{-- ═══════════════════════════════════════════════════════════
        2. GRAFIK TREN PRODUKSI 7 HARI TERAKHIR
        ═══════════════════════════════════════════════════════════ --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 overflow-hidden relative">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
                <h3 class="text-base font-bold uppercase tracking-wider text-gray-900 dark:text-gray-100" id="chart-title">
                    Tren Produksi Telur — 7 Hari Terakhir
                </h3>
                <div class="flex items-center p-1 bg-gray-100 dark:bg-gray-700 rounded-lg">
                    <button type="button" id="btn-terakhir" onclick="switchChart('terakhir')" class="px-4 py-1.5 text-sm font-semibold rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm transition-all">
                        Terakhir
                    </button>
                    <button type="button" id="btn-prediksi" onclick="switchChart('prediksi')" class="px-4 py-1.5 text-sm font-semibold rounded-md text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-all">
                        Prediksi
                    </button>
                </div>
            </div>
            <div class="relative w-full h-80">
                <canvas id="chartProduksi"></canvas>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════
        3 & 4. ARUS KAS (bawah kiri) + ALERT STOK KRITIS (bawah kanan)
        ═══════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 {{ $showArusKas ? 'lg:grid-cols-2' : '' }} gap-5">

            {{-- 3. Ringkasan Arus Kas Bulan Ini — HIDDEN untuk selain Admin dan Owner --}}
            @if($showArusKas)
                <x-card title="Ringkasan Arus Kas Bulan Ini" subtitle="{{ now()->translatedFormat('F Y') }}">
                    @if($kasMasuk == 0 && $kasKeluar == 0)
                        <x-empty-state message="Belum ada transaksi kas bulan ini" icon="banknotes" />
                    @else
                        <div class="space-y-4">
                            {{-- Kas Masuk --}}
                            <div
                                class="flex items-center justify-between p-4 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800">
                                <div class="flex items-center space-x-3">
                                    <div class="p-2 rounded-lg bg-emerald-100 dark:bg-emerald-900/50">
                                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-500" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m0-16l-4 4m4-4l4 4" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-emerald-800 dark:text-emerald-300">Total Kas Masuk</span>
                                </div>
                                <span class="text-lg font-bold text-emerald-700 dark:text-emerald-400">@rupiah($kasMasuk)</span>
                            </div>

                            {{-- Kas Keluar --}}
                            <div
                                class="flex items-center justify-between p-4 rounded-lg bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800">
                                <div class="flex items-center space-x-3">
                                    <div class="p-2 rounded-lg bg-red-100 dark:bg-red-900/50">
                                        <svg class="w-5 h-5 text-red-600 dark:text-red-500" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 20V4m0 16l4-4m-4 4l-4-4" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-red-800 dark:text-red-300">Total Kas Keluar</span>
                                </div>
                                <span class="text-lg font-bold text-red-700 dark:text-red-400">@rupiah($kasKeluar)</span>
                            </div>

                            {{-- Net --}}
                            <div
                                class="flex items-center justify-between p-4 rounded-lg {{ $kasNet >= 0 ? 'bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800' : 'bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800' }}">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="p-2 rounded-lg {{ $kasNet >= 0 ? 'bg-blue-100 dark:bg-blue-900/50' : 'bg-amber-100 dark:bg-amber-900/50' }}">
                                        <svg class="w-5 h-5 {{ $kasNet >= 0 ? 'text-blue-600 dark:text-blue-500' : 'text-amber-600 dark:text-amber-500' }}"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                                        </svg>
                                    </div>
                                    <span
                                        class="text-sm font-medium {{ $kasNet >= 0 ? 'text-blue-800 dark:text-blue-300' : 'text-amber-800 dark:text-amber-300' }}">Net
                                        Arus Kas</span>
                                </div>
                                <span
                                    class="text-lg font-bold {{ $kasNet >= 0 ? 'text-blue-700 dark:text-blue-400' : 'text-amber-700 dark:text-amber-400' }}">
                                    {{ $kasNet >= 0 ? '+' : '-' }} @rupiah(abs($kasNet))
                                </span>
                            </div>
                        </div>
                    @endif
                </x-card>
            @endif

            {{-- 4. Alert Stok Kritis --}}
            <x-card title="Alert Stok Kritis" subtitle="Barang dengan stok di bawah minimum">
                @if($barangKritis->isEmpty())
                    <x-empty-state message="Semua stok dalam kondisi aman" icon="check-circle" />
                @else
                    <div class="overflow-x-auto">
                        <x-table :headers="['Nama Barang', 'Stok Saat Ini', 'Stok Minimum', 'Status']">
                            @foreach($barangKritis as $barang)
                                <tr>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $barang->nama_barang }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                        {{ number_format($barang->stok_barang, 0) }} {{ $barang->satuan }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                        {{ number_format($barang->stok_minimum, 0) }} {{ $barang->satuan }}</td>
                                    <td class="px-4 py-3">
                                        <x-badge variant="danger" dot>Kritis</x-badge>
                                    </td>
                                </tr>
                            @endforeach
                        </x-table>
                    </div>
                @endif
            </x-card>

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
        
        const labelsPrediksi = @json($chartLabelsPrediksi);
        const dataPrediksi = @json($chartDataPrediksi);

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

        function switchChart(type) {
            const btnTerakhir = document.getElementById('btn-terakhir');
            const btnPrediksi = document.getElementById('btn-prediksi');
            const title = document.getElementById('chart-title');

            if (type === 'terakhir') {
                btnTerakhir.className = "px-4 py-1.5 text-sm font-semibold rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm transition-all";
                btnPrediksi.className = "px-4 py-1.5 text-sm font-semibold rounded-md text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-all";
                title.innerText = "Tren Produksi Telur — 7 Hari Terakhir";
                initChart(labelsTerakhir, dataTerakhir, '#10b981', 'rgba(16, 185, 129, 0.1)');
            } else {
                btnPrediksi.className = "px-4 py-1.5 text-sm font-semibold rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm transition-all";
                btnTerakhir.className = "px-4 py-1.5 text-sm font-semibold rounded-md text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-all";
                title.innerText = "Prediksi Produksi Telur — 7 Hari Kedepan";
                initChart(labelsPrediksi, dataPrediksi, '#3b82f6', 'rgba(59, 130, 246, 0.1)');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Load chartJS properly if not ready
            const renderChart = () => {
                if (typeof window.Chart === 'undefined') {
                    setTimeout(renderChart, 100);
                    return;
                }
                switchChart('terakhir');
            };
            renderChart();
        });
    </script>
@endsection