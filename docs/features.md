# Dokumentasi Fitur (Feature Documentation)

## Autentikasi Login
**Tujuan fitur**
Mengautentikasi pengguna dengan aman untuk membatasi akses sistem berdasarkan hak akses/role (*Role-Based Access Control*).

**Aktor**
Pengguna, Karyawan, Owner, Admin.

**Alur fitur**
User memasukkan email & password di halaman login &rarr; Sistem memvalidasi input dan memeriksa hak akses (role) &rarr; Sistem mengarahkan (redirect) user ke dashboard sesuai role masing-masing (Pegawai Gudang, Pegawai Kandang, Owner, atau Admin).

**Route / Controller terkait**
`POST /login`
`App\Http\Controllers\Auth\LoginController`

**Screenshot fitur**
![Autentikasi Login](screenshot/Autentikasi%20Login.png)

---

## Dashboard Monitoring
**Tujuan fitur**
Menyajikan ringkasan dan analisis performa peternakan secara *real-time*, seperti HDP (Hen Day Production), peringatan sisa stok pakan/vitamin, saldo keuangan, dan grafik tren hasil panen.

**Aktor**
Owner, Admin.

**Alur fitur**
User login &rarr; dialihkan ke rute `/dashboard` &rarr; Controller mengambil data aggregasi dari berbagai tabel (populasi, stok barang, transaksi) &rarr; Data direpresentasikan dalam bentuk komponen metrik (card) dan divisualisasikan dengan Chart.js.

**Route / Controller terkait**
`GET /dashboard`
`App\Http\Controllers\DashboardController`

**Screenshot fitur**
![Dashboard](screenshot/Dashboard%20Monitoring.png)

---

## Pencatatan Produksi Telur
**Tujuan fitur**
Mencatat hasil panen harian dari masing-masing kandang operasional beserta spesifikasi/kategorinya (RB, MB, MK, Pecah).

**Aktor**
Karyawan (Pegawai Kandang), Admin.

**Alur fitur**
User masuk ke halaman "Pencatatan Produksi" &rarr; Memilih ID Kandang yang aktif &rarr; Mengisi kuantitas telur yang dihasilkan per grade (RB/MB/MK/Pecah) &rarr; Menyimpan data &rarr; Sistem secara otomatis memperbarui *log* riwayat harian dan memperbarui rasio HDP.

**Route / Controller terkait**
`POST /recording/produksi-telur`
`App\Http\Controllers\Recording\ProduksiTelurController`

**Screenshot fitur**
![Produksi Telur](screenshot/Pencatatan%20Produksi%20Telur.png)

---

## Manajemen Transaksi (Penjualan)
**Tujuan fitur**
Mencatat proses perpindahan komoditas (Telur, Ayam Afkir, Pupuk) ke pelanggan/pengepul, dan mengelola arus kas masuk (pembayaran lunas atau piutang/utang pelanggan).

**Aktor**
Karyawan (Pegawai), Owner, Admin.

**Alur fitur**
User membuka halaman "Transaksi Penjualan" &rarr; Menentukan tipe komoditas, jumlah, metode pembayaran (LUNAS atau PIUTANG), dan data Pelanggan &rarr; Konfirmasi penjualan &rarr; Sistem memotong stok terkait (stok telur di inventory) &rarr; Uang ditambahkan ke Buku Kas/Piutang.

**Route / Controller terkait**
`POST /transaksi/penjualan`
`App\Http\Controllers\Transaksi\PenjualanController`

**Screenshot fitur**
![Transaksi Penjualan](screenshot/Transaksi%20Penjualan%20Telur.png)

---

## Laporan Keuangan (Laba Rugi)
**Tujuan fitur**
Merekapitulasi seluruh arus kas operasional, penjualan bersih, pembelian (pakan/vitamin), serta menghitung Net Profit & Profit Margin secara otomatis. Mendukung cetak *server-side* PDF secara langsung.

**Aktor**
Owner, Admin.

**Alur fitur**
User mengakses halaman "Laporan Laba Rugi" &rarr; Memilih filter periode (Bulan & Tahun) &rarr; Menekan "Tampilkan" &rarr; Sistem melakukan rekapitulasi agregasi keuangan &rarr; Untuk *export*, user menekan "Download PDF" &rarr; Sistem menggunakan `laravel-dompdf` untuk mencetak file PDF di *backend* dan langsung mengunduhnya ke perangkat user.

**Route / Controller terkait**
`GET /laporan/laba-rugi/preview` & `GET /laporan/laba-rugi/pdf`
`App\Http\Controllers\Laporan\LabaRugiController`

**Screenshot fitur**
![Laporan Laba Rugi](screenshot/Manajemen%20Keuangan(Buku%20Kas).png)
*(Catatan: Screenshot menyusul setelah export PDF final dirender secara visual)*