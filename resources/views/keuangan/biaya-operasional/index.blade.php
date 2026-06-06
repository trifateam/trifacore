@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Keuangan'],
        ['label' => 'Biaya Operasional'],
    ]" />

    <x-page-header title="Biaya Operasional" subtitle="Pencatatan pengeluaran harian dan bulanan di luar pembelian material dan pullet." />

    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
        
        {{-- KOLOM KIRI: FORM CATAT PENGELUARAN (1/3) --}}
        <div class="md:col-span-1">
            <x-card class="border border-gray-200 sticky top-6">
                <div class="px-5 py-4 border-b border-gray-200 bg-gray-50 flex items-center">
                    <svg class="w-5 h-5 text-red-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    <h3 class="text-lg font-bold text-gray-900">Catat Pengeluaran Baru</h3>
                </div>
                <div class="p-5">
                    @if($kategoriBiaya->isEmpty())
                        <div class="bg-amber-50 border-l-4 border-amber-500 p-4 mb-4 rounded-md shadow-sm">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-amber-700">
                                        Tambahkan kategori biaya di Master Data terlebih dahulu.
                                    </p>
                                    <p class="mt-2 text-sm">
                                        <a href="{{ route('master-data.kategori-biaya.index') }}" class="font-medium text-amber-700 hover:text-amber-600 underline">
                                            Pergi ke Master Data &rarr;
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($akunKas->isEmpty())
                        <div class="bg-amber-50 border-l-4 border-amber-500 p-4 mb-4 rounded-md shadow-sm">
                            <div class="flex">
                                <div class="ml-3">
                                    <p class="text-sm text-amber-700">
                                        Tambahkan Rekening/Kas yang aktif di Master Data terlebih dahulu.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('keuangan.biaya-operasional.store') }}" class="space-y-4">
                        @csrf
                        
                        <div>
                            <label for="tanggal_operasional" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pengeluaran <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_operasional" id="tanggal_operasional" value="{{ date('Y-m-d') }}" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="id_kategori_biaya" class="block text-sm font-medium text-gray-700 mb-1">Kategori Biaya <span class="text-red-500">*</span></label>
                            <select name="id_kategori_biaya" id="id_kategori_biaya" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategoriBiaya as $kb)
                                    <option value="{{ $kb->id_kategori_biaya }}">{{ $kb->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="nama_pengeluaran" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Pengeluaran <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_pengeluaran" id="nama_pengeluaran" required maxlength="100" placeholder="Cth: Bayar listrik Kandang A bulan Mei" class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="biaya_operasional" class="block text-sm font-medium text-gray-700 mb-1">Total Jumlah (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" name="biaya_operasional" id="biaya_operasional" required min="1" placeholder="Cth: 500000" class="w-full font-bold text-red-600 rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="id_akun" class="block text-sm font-medium text-gray-700 mb-1">Bayar dari Rekening/Kas <span class="text-red-500">*</span></label>
                            <select name="id_akun" id="id_akun" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                                <option value="">-- Pilih Rekening --</option>
                                @foreach($akunKas as $akun)
                                    <option value="{{ $akun->id_akun }}">{{ $akun->nama_akun }} (Saldo: Rp {{ number_format($akun->saldo, 0, ',', '.') }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="pt-4 border-t border-gray-100">
                            <button type="submit" @if($kategoriBiaya->isEmpty() || $akunKas->isEmpty()) disabled @endif class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-bold text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                Catat Pengeluaran
                            </button>
                        </div>
                    </form>
                </div>
            </x-card>
        </div>

        {{-- KOLOM KANAN: TABEL RIWAYAT BIAYA (2/3) --}}
        <div class="md:col-span-2 space-y-6">
            
            {{-- Summary Card --}}
            <x-card class="bg-red-50 border border-red-200">
                <div class="p-6 flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-600 mr-4">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-red-800 uppercase tracking-wide">Total Pengeluaran Bulan Ini</p>
                        <p class="text-3xl font-black text-red-600 mt-1">Rp {{ number_format($totalBulanIni, 0, ',', '.') }}</p>
                    </div>
                </div>
            </x-card>

            {{-- History Table --}}
            <x-card class="border border-gray-200">
                <div class="px-5 py-4 border-b border-gray-200 bg-white">
                    <h3 class="text-lg font-bold text-gray-900">Riwayat Pengeluaran Operasional</h3>
                </div>
                <div class="overflow-x-auto">
                    <x-table :headers="['Tanggal', 'Kategori', 'Deskripsi', 'Rekening', 'Total']">
                        @forelse($operasionals as $op)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($op->tanggal_operasional)->translatedFormat('d M Y') }}
                                    <div class="text-xs text-gray-400 mt-0.5">{{ $op->kode_operasional }}</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $op->kategoriBiaya->nama_kategori ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 max-w-xs truncate" title="{{ $op->nama_pengeluaran }}">
                                    {{ $op->nama_pengeluaran }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                    {{ $op->akunKas->nama_akun ?? '-' }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-bold text-red-600 text-right">
                                    Rp {{ number_format($op->biaya_operasional, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10">
                                    <x-empty-state 
                                        icon="document-text" 
                                        title="Belum Ada Pengeluaran" 
                                        description="Riwayat pengeluaran operasional akan muncul di sini." 
                                    />
                                </td>
                            </tr>
                        @endforelse
                    </x-table>
                </div>
                @if($operasionals->hasPages())
                    <div class="p-4 border-t border-gray-200">
                        {{ $operasionals->links() }}
                    </div>
                @endif
            </x-card>
            
        </div>
    </div>
@endsection
