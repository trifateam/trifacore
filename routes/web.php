<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterData\BarangController;
use App\Http\Controllers\MasterData\KandangController;
use App\Http\Controllers\MasterData\KategoriBiayaController;
use App\Http\Controllers\MasterData\PegawaiController;
use App\Http\Controllers\MasterData\PelangganController;
use App\Http\Controllers\MasterData\RekeningController;
use App\Http\Controllers\MasterData\SupplierController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// Dashboard (placeholder, dilindungi auth) — semua role
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    if (config('app.debug')) {
        Route::get('/dev/components', function () {
            return view('dev.components');
        })->name('dev.components');
    }

    // ── Pencatatan Harian: Admin, Pegawai Kandang ──
    Route::middleware('role:Admin,Pegawai Kandang')->prefix('pencatatan')->name('pencatatan.')->group(function () {
        Route::get('/produksi-telur', fn () => view('pencatatan.produksi-telur'))->name('produksi-telur');
        Route::get('/konsumsi-pakan', fn () => view('pencatatan.konsumsi-pakan'))->name('konsumsi-pakan');
        Route::get('/konsumsi-vitamin', fn () => view('pencatatan.konsumsi-vitamin'))->name('konsumsi-vitamin');
        Route::get('/deplesi', fn () => view('pencatatan.deplesi'))->name('deplesi');
        Route::get('/suhu', fn () => view('pencatatan.suhu'))->name('suhu');
        Route::get('/pupuk', fn () => view('pencatatan.pupuk'))->name('pupuk');
        Route::get('/riwayat', fn () => view('pencatatan.riwayat'))->name('riwayat');
    });

    // ── Manajemen Transaksi: Admin, Owner, Sales ──
    Route::middleware('role:Admin,Owner,Sales')->prefix('transaksi')->name('transaksi.')->group(function () {
        Route::get('/penjualan', fn () => view('transaksi.penjualan'))->name('penjualan');
        Route::get('/pembelian', fn () => view('transaksi.pembelian'))->name('pembelian');
        Route::get('/riwayat-penjualan', fn () => view('transaksi.riwayat-penjualan'))->name('riwayat-penjualan');
        Route::get('/riwayat-pembelian', fn () => view('transaksi.riwayat-pembelian'))->name('riwayat-pembelian');
    });

    // ── Kandang Operasional: Admin, Owner ──
    Route::middleware('role:Admin,Owner')->prefix('kandang-operasional')->name('kandang-operasional.')->group(function () {
        Route::get('/', fn () => view('kandang-operasional.index'))->name('index');
    });

    // ── Gudang: Admin, Owner, Pegawai Gudang ──
    Route::middleware('role:Admin,Owner,Pegawai Gudang')->prefix('gudang')->name('gudang.')->group(function () {
        Route::get('/', fn () => view('gudang.index'))->name('index');
    });

    // ── Admin & Owner Only: Master Data, Keuangan, Laporan, Pengaturan ──
    Route::middleware('role:Admin,Owner')->group(function () {

        // Master Data
        Route::prefix('master-data')->name('master-data.')->group(function () {
            Route::resource('kandang', KandangController::class)->except(['create', 'show', 'edit']);
            Route::resource('barang', BarangController::class)->except(['create', 'show', 'edit']);
            Route::resource('supplier', SupplierController::class)->except(['create', 'show', 'edit']);
            Route::resource('pegawai', PegawaiController::class)->except(['create', 'show', 'edit']);
            Route::resource('pelanggan', PelangganController::class)->except(['create', 'show', 'edit']);
            Route::resource('rekening', RekeningController::class)->except(['create', 'show', 'edit']);
            Route::resource('kategori-biaya', KategoriBiayaController::class)->except(['create', 'show', 'edit']);
        });

        // Management Keuangan
        Route::prefix('keuangan')->name('keuangan.')->group(function () {
            Route::get('/biaya-operasional', fn () => view('keuangan.biaya-operasional'))->name('biaya-operasional');
            Route::get('/buku-kas', fn () => view('keuangan.buku-kas'))->name('buku-kas');
            Route::get('/buku-utang', fn () => view('keuangan.buku-utang'))->name('buku-utang');
            Route::get('/buku-piutang', fn () => view('keuangan.buku-piutang'))->name('buku-piutang');
        });

        // Laporan
        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/produksi-performa', fn () => view('laporan.produksi-performa'))->name('produksi-performa');
            Route::get('/laba-rugi', fn () => view('laporan.laba-rugi'))->name('laba-rugi');
            Route::get('/cetak/produksi-telur', fn () => view('laporan.cetak.produksi-telur'))->name('cetak.produksi-telur');
            Route::get('/cetak/penjualan-telur', fn () => view('laporan.cetak.penjualan-telur'))->name('cetak.penjualan-telur');
            Route::get('/cetak/pembelian-pakan', fn () => view('laporan.cetak.pembelian-pakan'))->name('cetak.pembelian-pakan');
        });

        // Pengaturan
        Route::prefix('pengaturan')->name('pengaturan.')->group(function () {
            Route::get('/profil-sistem', fn () => view('pengaturan.profil-sistem'))->name('profil-sistem');
        });
    });
});
