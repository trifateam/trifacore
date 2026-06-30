@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        {{-- Page Header --}}
        <x-page-header title="Dashboard Sales"
            subtitle="Ringkasan aktivitas penjualan — {{ now()->translatedFormat('l, d F Y') }}">
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
        1. MAIN CONTENT
        ═══════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 gap-5">
            <x-card title="Selamat Datang, {{ $user->nama ?? 'Sales' }}" subtitle="Anda sedang berada di dashboard Sales">
                <x-empty-state 
                    icon="briefcase" 
                    title="Siap Berjualan Hari Ini?" 
                    description="Pilih menu Transaksi Penjualan untuk memulai pencatatan pesanan." 
                />
            </x-card>
        </div>

    </div>
@endsection
