# Feature Documentation

Berikut adalah dokumentasi detail fitur-fitur yang terdapat di dalam sistem manajemen peternakan **Trifacore**, disusun per fitur dengan status implementasinya saat ini.

> **Keterangan Status:**
> - ✅ : **Sudah Ada** (Fitur telah diimplementasikan di sistem)
> - ⏳ : **On Progress** (Fitur sedang dalam tahap pengembangan atau direncanakan)

---

### 1. Landing Page (Profil Publik)
| Komponen | Deskripsi |
| :--- | :--- |
| **Status** | ⏳ On Progress |
| **Tujuan Fitur** | Tampilan representasi digital dan *branding* profil Trifa Farm bagi pengunjung web |
| **Aktor** | Pengunjung Publik |
| **Alur Fitur** | Akses URL utama &rarr; lihat info profil peternakan |
| **Route / Controller** | *(Belum diimplementasikan)* |
| **Screenshot Fitur** | *[Tambahkan Screenshot Disini]* |

---

### 2. Dashboard Monitoring
| Komponen | Deskripsi |
| :--- | :--- |
| **Status** | ✅ Sudah Ada |
| **Tujuan Fitur** | Menampilkan ringkasan data, grafik performa, dan informasi utama sistem |
| **Aktor** | Semua Role (Admin, Owner, dll) |
| **Alur Fitur** | User login &rarr; diarahkan otomatis ke dashboard &rarr; melihat statistik |
| **Route / Controller** | `GET /dashboard`<br>`DashboardController` |
| **Screenshot Fitur** | *[Tambahkan Screenshot Disini]* |

---

### 3. AI Production Forecasting
| Komponen | Deskripsi |
| :--- | :--- |
| **Status** | ⏳ On Progress |
| **Tujuan Fitur** | Visualisasi prediksi tren produksi telur harian menggunakan model Linear Regression |
| **Aktor** | Owner, Admin |
| **Alur Fitur** | Buka dashboard &rarr; sistem memuat hasil model AI &rarr; menampilkan grafik prediksi |
| **Route / Controller** | *(Integrasi API Python)* |
| **Screenshot Fitur** | *[Tambahkan Screenshot Disini]* |

---

### 4. Notifikasi & Alert Produksi
| Komponen | Deskripsi |
| :--- | :--- |
| **Status** | ⏳ On Progress |
| **Tujuan Fitur** | Sistem notifikasi otomatis jika terdeteksi anomali penurunan produksi |
| **Aktor** | Owner |
| **Alur Fitur** | Penurunan terdeteksi oleh AI &rarr; sistem trigger alert di dashboard / email |
| **Route / Controller** | *(Belum diimplementasikan)* |
| **Screenshot Fitur** | *[Tambahkan Screenshot Disini]* |

---

### 5. Autentikasi (Login/Register)
| Komponen | Deskripsi |
| :--- | :--- |
| **Status** | ✅ Sudah Ada |
| **Tujuan Fitur** | Mengautentikasi pengguna untuk masuk atau mendaftar ke sistem |
| **Aktor** | Pengguna (Guest) |
| **Alur Fitur** | User input email & password &rarr; validasi &rarr; masuk dashboard |
| **Route / Controller** | `GET\|POST /login`<br>`GET\|POST /register`<br>`LoginController`, `RegisterController` |
| **Screenshot Fitur** | *[Tambahkan Screenshot Disini]* |

---

### 6. Pengaturan & Profil Sistem
| Komponen | Deskripsi |
| :--- | :--- |
| **Status** | ✅ Sudah Ada |
| **Tujuan Fitur** | Mengatur konfigurasi dasar informasi perusahaan / sistem aplikasi |
| **Aktor** | Admin, Owner |
| **Alur Fitur** | Buka pengaturan profil &rarr; ubah informasi nama/logo &rarr; simpan |
| **Route / Controller** | `GET\|POST /pengaturan/profil-sistem`<br>`ProfilSistemController` |
| **Screenshot Fitur** | *[Tambahkan Screenshot Disini]* |

---

### 7. Riwayat Aktivitas (Audit Trail)
| Komponen | Deskripsi |
| :--- | :--- |
| **Status** | ✅ Sudah Ada |
| **Tujuan Fitur** | Mencatat dan menampilkan seluruh log aktivitas dari pengguna dalam sistem |
| **Aktor** | Admin, Owner |
| **Alur Fitur** | Buka menu riwayat aktivitas &rarr; sistem menampilkan tabel log kejadian |
| **Route / Controller** | `GET /riwayat-aktivitas`<br>`RiwayatAktivitasController` |
| **Screenshot Fitur** | *[Tambahkan Screenshot Disini]* |

---

### 8. Pencatatan Produksi (Telur & Pupuk)
| Komponen | Deskripsi |
| :--- | :--- |
| **Status** | ✅ Sudah Ada |
| **Tujuan Fitur** | Mencatat hasil produksi harian dari kandang untuk diakumulasi |
| **Aktor** | Admin, Pegawai Kandang |
| **Alur Fitur** | Pilih menu pencatatan &rarr; pilih batch/kandang &rarr; input produksi &rarr; simpan |
| **Route / Controller** | `GET\|POST /pencatatan/produksi-telur`<br>`GET\|POST /pencatatan/pupuk`<br>`ProduksiTelurController`, `ProduksiPupukController` |
| **Screenshot Fitur** | *[Tambahkan Screenshot Disini]* |

---

### 9. Pencatatan Konsumsi & Suhu
| Komponen | Deskripsi |
| :--- | :--- |
| **Status** | ✅ Sudah Ada |
| **Tujuan Fitur** | Memantau dan mencatat konsumsi pakan/vitamin serta suhu lingkungan kandang |
| **Aktor** | Admin, Pegawai Kandang |
| **Alur Fitur** | Pilih menu pencatatan &rarr; pilih batch/kandang &rarr; input nilai &rarr; simpan |
| **Route / Controller** | `GET\|POST /pencatatan/konsumsi-pakan`<br>`GET\|POST /pencatatan/suhu`<br>`KonsumsiPakanController`, `SuhuKandangController` |
| **Screenshot Fitur** | *[Tambahkan Screenshot Disini]* |

---

### 10. Pencatatan Kematian (Deplesi)
| Komponen | Deskripsi |
| :--- | :--- |
| **Status** | ✅ Sudah Ada |
| **Tujuan Fitur** | Mencatat jumlah ayam yang mati atau diafkir pada batch kandang tertentu |
| **Aktor** | Admin, Pegawai Kandang |
| **Alur Fitur** | Buka menu deplesi &rarr; pilih batch &rarr; input jumlah kematian &rarr; simpan |
| **Route / Controller** | `GET\|POST /pencatatan/deplesi`<br>`DeplesiController` |
| **Screenshot Fitur** | *[Tambahkan Screenshot Disini]* |

---

### 11. Kandang Operasional
| Komponen | Deskripsi |
| :--- | :--- |
| **Status** | ✅ Sudah Ada |
| **Tujuan Fitur** | Mengatur penempatan (assignment) ayam ke dalam kandang berdasarkan batch |
| **Aktor** | Admin, Owner |
| **Alur Fitur** | Buka menu kandang operasional &rarr; pilih batch &rarr; assign kandang &rarr; simpan |
| **Route / Controller** | `GET /kandang-operasional`<br>`POST /kandang-operasional/assign`<br>`KandangOperasionalController` |
| **Screenshot Fitur** | *[Tambahkan Screenshot Disini]* |

---

### 12. Manajemen Master Data
| Komponen | Deskripsi |
| :--- | :--- |
| **Status** | ✅ Sudah Ada |
| **Tujuan Fitur** | Mengelola data inti seperti Kandang, Barang, Supplier, Pegawai, Pelanggan, dsb (CRUD) |
| **Aktor** | Admin, Owner |
| **Alur Fitur** | Pilih sub-menu master data &rarr; tambah/edit data form &rarr; simpan |
| **Route / Controller** | `Resource /master-data/*`<br>`KandangController`, `BarangController`, dll |
| **Screenshot Fitur** | *[Tambahkan Screenshot Disini]* |

---

### 13. Transaksi Penjualan & Pembelian
| Komponen | Deskripsi |
| :--- | :--- |
| **Status** | ✅ Sudah Ada |
| **Tujuan Fitur** | Memproses transaksi penjualan produk (telur/ayam) dan pembelian kebutuhan |
| **Aktor** | Admin, Owner, Sales |
| **Alur Fitur** | Buka menu transaksi &rarr; buat baru &rarr; input data & pelanggan &rarr; simpan |
| **Route / Controller** | `GET\|POST /transaksi/penjualan`<br>`GET\|POST /transaksi/pembelian`<br>`PenjualanController`, `PembelianController` |
| **Screenshot Fitur** | *[Tambahkan Screenshot Disini]* |

---

### 14. Manajemen Gudang
| Komponen | Deskripsi |
| :--- | :--- |
| **Status** | ✅ Sudah Ada |
| **Tujuan Fitur** | Mengelola stok inventaris barang serta melakukan penyesuaian (adjustment) stok |
| **Aktor** | Admin, Owner, Pegawai Gudang |
| **Alur Fitur** | Buka menu gudang &rarr; lihat daftar stok barang &rarr; klik ubah stok &rarr; simpan |
| **Route / Controller** | `GET\|POST /gudang`<br>`GET\|POST /gudang/adjust/{barang}`<br>`GudangController` |
| **Screenshot Fitur** | *[Tambahkan Screenshot Disini]* |

---

### 15. Manajemen Keuangan
| Komponen | Deskripsi |
| :--- | :--- |
| **Status** | ✅ Sudah Ada |
| **Tujuan Fitur** | Mencatat biaya operasional serta memonitor Buku Kas, Buku Utang, dan Piutang |
| **Aktor** | Admin, Owner |
| **Alur Fitur** | Buka menu keuangan &rarr; lihat saldo / input pengeluaran / lunasi &rarr; simpan |
| **Route / Controller** | `GET /keuangan/buku-kas`<br>`GET\|POST /keuangan/buku-utang`<br>`BiayaOperasionalController`, `BukuKasController` |
| **Screenshot Fitur** | *[Tambahkan Screenshot Disini]* |

---

### 16. Laporan & Cetak PDF
| Komponen | Deskripsi |
| :--- | :--- |
| **Status** | ✅ Sudah Ada |
| **Tujuan Fitur** | Menghasilkan laporan kinerja produksi dan keuangan (Laba/Rugi), beserta ekspor PDF |
| **Aktor** | Admin, Owner |
| **Alur Fitur** | Buka menu laporan &rarr; pilih periode tanggal &rarr; generate &rarr; preview &rarr; cetak PDF |
| **Route / Controller** | `GET /laporan/laba-rugi`<br>`GET /laporan/cetak/*`<br>`LabaRugiController`, `CetakProduksiController` |
| **Screenshot Fitur** | *[Tambahkan Screenshot Disini]* |

---

### 17. Export Laporan ke Excel
| Komponen | Deskripsi |
| :--- | :--- |
| **Status** | ⏳ On Progress |
| **Tujuan Fitur** | Kemampuan untuk mengunduh arsip laporan dalam format Spreadsheet (Excel) |
| **Aktor** | Admin, Owner |
| **Alur Fitur** | Buka laporan &rarr; klik tombol export Excel &rarr; file `.xlsx` diunduh |
| **Route / Controller** | *(Belum diimplementasikan)* |
| **Screenshot Fitur** | *[Tambahkan Screenshot Disini]* |
