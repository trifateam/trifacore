<?php

use App\Http\Controllers\PegawaiController;
use Illuminate\Support\Facades\Route;

// Auth (tampilan saja)
Route::get('/login', fn() => view('auth.login'))->name('login');

// Dashboard
Route::get('/', fn() => view('dashboard'))->name('dashboard');

// Pencatatan Harian
Route::get('/pencatatan/produksi-telur', fn() => view('pencatatan.produksi-telur'))->name('pencatatan.produksi-telur');

// Manajemen Transaksi
Route::get('/transaksi/penjualan', fn() => view('transaksi.penjualan'))->name('transaksi.penjualan');
Route::get('/transaksi/pembelian', fn() => view('transaksi.pembelian'))->name('transaksi.pembelian');
Route::get('/transaksi/riwayat-penjualan', fn() => view('transaksi.riwayat-penjualan'))->name('transaksi.riwayat-penjualan');
Route::get('/transaksi/riwayat-pembelian', fn() => view('transaksi.riwayat-pembelian'))->name('transaksi.riwayat-pembelian');

// Kandang
Route::get('/kandang/populasi', fn() => view('kandang.populasi'))->name('kandang.populasi');

// Gudang
Route::get('/gudang', fn() => view('gudang.index'))->name('gudang.index');

// Master Data — CRUD Pegawai (full)
Route::resource('pegawai', PegawaiController::class)->except(['show']);

// Master Data — Placeholder
Route::get('/master/kandang', fn() => view('master.kandang.index'))->name('master.kandang.index');
Route::get('/master/barang', fn() => view('master.barang.index'))->name('master.barang.index');
Route::get('/master/supplier', fn() => view('master.supplier.index'))->name('master.supplier.index');
Route::get('/master/pelanggan', fn() => view('master.pelanggan.index'))->name('master.pelanggan.index');
