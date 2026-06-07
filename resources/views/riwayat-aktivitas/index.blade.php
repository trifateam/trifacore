@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Riwayat Aktivitas Sistem'],
    ]" />

    <x-page-header title="Riwayat Aktivitas Sistem" subtitle="Pantau seluruh jejak audit dan log aktivitas pengguna pada platform." />

    <x-card class="mt-6 border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-200 bg-gray-50">
            <form method="GET" action="{{ route('riwayat-aktivitas.index') }}" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <label for="search" class="sr-only">Cari Aktivitas/Pengguna</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" id="search" value="{{ $search }}" placeholder="Cari aktivitas atau nama pengguna..." class="pl-10 w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                    </div>
                </div>
                <div class="w-full md:w-48">
                    <label for="tanggal_mulai" class="sr-only">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ $tanggal_mulai }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" title="Tanggal Mulai">
                </div>
                <div class="w-full md:w-48">
                    <label for="tanggal_selesai" class="sr-only">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ $tanggal_selesai }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" title="Tanggal Selesai">
                </div>
                <div>
                    <button type="submit" class="w-full md:w-auto flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Filter Log
                    </button>
                </div>
                @if($search || $tanggal_mulai || $tanggal_selesai)
                    <div>
                        <a href="{{ route('riwayat-aktivitas.index') }}" class="w-full md:w-auto flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Reset
                        </a>
                    </div>
                @endif
            </form>
        </div>
        
        <div class="overflow-x-auto">
            <x-table :headers="['Waktu Kejadian', 'Nama Pengguna', 'Aktivitas']">
                @forelse($riwayats as $riwayat)
                    <tr>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 font-medium">
                            {{ $riwayat->created_at->translatedFormat('d M Y, H:i:s') }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 font-medium">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 bg-gray-100 rounded-full flex items-center justify-center border border-gray-200">
                                    <span class="text-xs font-bold text-gray-600 uppercase">{{ substr($riwayat->pengguna->nama_lengkap ?? 'S', 0, 2) }}</span>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-bold text-gray-900">{{ $riwayat->pengguna->nama_lengkap ?? 'Sistem / Terhapus' }}</p>
                                    <p class="text-xs text-gray-500">{{ $riwayat->pengguna->role ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900 w-full">
                            {{ $riwayat->aktivitas }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-10">
                            <x-empty-state 
                                icon="document-text" 
                                title="Riwayat Tidak Ditemukan" 
                                description="Belum ada aktivitas yang terekam atau pencarian tidak sesuai." 
                            />
                        </td>
                    </tr>
                @endforelse
            </x-table>
        </div>
        
        @if($riwayats->hasPages())
            <div class="p-4 border-t border-gray-200 bg-gray-50">
                {{ $riwayats->links() }}
            </div>
        @endif
    </x-card>
@endsection
