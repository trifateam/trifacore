@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Performa Batch Aktif'],
    ]" />

    <x-page-header title="Performa Batch Aktif" subtitle="Pantau performa produksi telur harian (HDP) untuk setiap batch yang sedang aktif." />

    <div class="mt-6 space-y-6">
        @if(empty($batchChartData))
            <x-card class="text-center py-12">
                <x-empty-state 
                    icon="chart-bar" 
                    title="Belum Ada Batch Aktif" 
                    description="Performa batch akan muncul di sini setelah ada batch yang di-assign ke kandang dan aktif berproduksi." 
                />
            </x-card>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($batchChartData as $index => $data)
                    <x-card class="overflow-hidden border border-gray-200 dark:border-gray-700 flex flex-col">
                        <!-- Header -->
                        <div class="p-5 border-b border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $data['batch']->nama_batch ?? $data['batch']->kode_batch }}</h3>
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">Total Deplesi (Mati + Cacat)</span>
                                        <span class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ number_format($data['batch']->deplesi->sum(fn($d) => $d->jml_mati + $d->jml_cacat), 0, ',', '.') }} ekor</span>
                                    </div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                        Kandang: <span class="font-medium text-gray-700 dark:text-gray-300">{{ $data['batch']->kandang->nama_kandang ?? '-' }}</span>
                                    </p>
                                </div>
                                <x-badge variant="success" class="text-xs">Aktif</x-badge>
                            </div>
                        </div>

                        <!-- Chart Area -->
                        <div class="p-5 flex-grow bg-gray-50 dark:bg-gray-800/50">
                            <div class="relative w-full h-48 md:h-64">
                                <canvas id="chart-{{ $index }}"></canvas>
                            </div>
                        </div>

                        <!-- Footer / Afkir Countdown -->
                        <div class="p-4 border-t border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800">
                            @php
                                $sisaHari = $data['sisaHariAfkir'];
                                $afkirColor = 'text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/30'; // Default > 90 days
                                
                                if ($sisaHari !== null) {
                                    if ($sisaHari <= 30) {
                                        $afkirColor = 'text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/30';
                                    } elseif ($sisaHari <= 90) {
                                        $afkirColor = 'text-orange-600 dark:text-orange-400 bg-orange-50 dark:bg-orange-900/30';
                                    }
                                }
                            @endphp

                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    Umur saat ini: <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $data['batch']->umur_saat_ini_minggu }} Minggu</span>
                                </div>
                                @if($sisaHari !== null)
                                    <div class="px-3 py-1 rounded-full text-xs font-bold flex items-center {{ $afkirColor }}">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Afkir {{ $sisaHari }} hari lagi
                                    </div>
                                @else
                                    <div class="text-xs text-gray-400 italic">Tanggal afkir tidak tersedia</div>
                                @endif
                            </div>
                        </div>
                    </x-card>
                @endforeach
            </div>
        @endif
    </div>
@endsection

@section('scripts')
@if(!empty($batchChartData))
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const batchData = @json($batchChartData);

        // Chart.js default defaults for dark mode adaptability
        const isDarkMode = document.documentElement.classList.contains('dark');
        const textColor = isDarkMode ? '#9CA3AF' : '#6B7280';
        const gridColor = isDarkMode ? 'rgba(75, 85, 99, 0.2)' : 'rgba(229, 231, 235, 0.5)';

        batchData.forEach((data, index) => {
            const ctx = document.getElementById('chart-' + index).getContext('2d');
            
            const hdpData = data.hdpData;
            const labels = Object.keys(hdpData).map(w => 'Mgg ' + w);
            const values = Object.values(hdpData);

            // Create gradient color array based on value (Green > 80, Yellow 70-80, Red < 70)
            const backgroundColors = values.map(val => {
                if (val === null) return 'rgba(156, 163, 175, 0.2)'; // No data (gray)
                if (val >= 80) return 'rgba(34, 197, 94, 0.8)'; // Green
                if (val >= 70) return 'rgba(234, 179, 8, 0.8)'; // Yellow
                return 'rgba(239, 68, 68, 0.8)'; // Red
            });

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'HDP %',
                        data: values,
                        backgroundColor: backgroundColors,
                        borderRadius: 4,
                        borderSkipped: false,
                        barPercentage: 0.7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    if (context.parsed.y === null) return 'Tidak ada data';
                                    return 'HDP: ' + context.parsed.y + '%';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                color: textColor,
                                callback: function(value) {
                                    return value + '%';
                                },
                                stepSize: 20
                            },
                            grid: {
                                color: gridColor,
                                drawBorder: false
                            }
                        },
                        x: {
                            ticks: {
                                color: textColor,
                                maxRotation: 0,
                                minRotation: 0
                            },
                            grid: {
                                display: false,
                                drawBorder: false
                            }
                        }
                    }
                }
            });
        });
    });
</script>
@endif
@endsection
