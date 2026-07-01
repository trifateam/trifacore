# Feature Documentation

Berikut adalah dokumentasi detail fitur-fitur yang terdapat di dalam sistem manajemen peternakan **Trifacore**, disusun per fitur dengan status implementasinya saat ini.

> **Keterangan Status:**
> - ✅ : **Sudah Ada** (Fitur telah diimplementasikan di sistem)
> - ⏳ : **On Progress** (Fitur sedang dalam tahap pengembangan atau direncanakan)

---

### 1. Landing Page (Profil Publik)
| Komponen | Deskripsi |
| :--- | :--- |
| **Status** | ✅ Sudah Ada |
| **Tujuan Fitur** | Tampilan representasi digital dan *branding* profil Trifa Farm bagi pengunjung web |
| **Aktor** | Pengunjung Publik |
| **Alur Fitur** | Akses URL utama &rarr; lihat info profil peternakan |
| **Route / Controller** | `GET /`<br>`welcome.blade.php` |
| **Screenshot Fitur** | ![Landing Page](screenshot/Landing%20Page.png) |

---

### 2. Dashboard Monitoring
| Komponen | Deskripsi |
| :--- | :--- |
| **Status** | ✅ Sudah Ada |
| **Tujuan Fitur** | Menampilkan ringkasan data, grafik performa, dan informasi utama sistem |
| **Aktor** | Semua Role (Admin, Owner, dll) |
| **Alur Fitur** | User login &rarr; diarahkan otomatis ke dashboard &rarr; melihat statistik |
| **Route / Controller** | `GET /dashboard`<br>`DashboardController` |
| **Screenshot Fitur** | ![Dashboard Monitoring](screenshot/Dashboard%20Monitoring.png) |

---


### 3. Autentikasi (Login/Register)
| Komponen | Deskripsi |
| :--- | :--- |
| **Status** | ✅ Sudah Ada |
| **Tujuan Fitur** | Mengautentikasi pengguna untuk masuk atau mendaftar ke sistem |
| **Aktor** | Pengguna (Guest) |
| **Alur Fitur** | User input email & password &rarr; validasi &rarr; masuk dashboard |
| **Route / Controller** | `GET\|POST /login`<br>`GET\|POST /register`<br>`LoginController`, `RegisterController` |
| **Screenshot Fitur** | ![Autentikasi Login](screenshot/Autentikasi%20Login.png) |

---

### 4. Pengaturan & Profil Sistem
| Komponen | Deskripsi |
| :--- | :--- |
| **Status** | ✅ Sudah Ada |
| **Tujuan Fitur** | Mengatur konfigurasi dasar informasi perusahaan / sistem aplikasi |
| **Aktor** | Admin, Owner |
| **Alur Fitur** | Buka pengaturan profil &rarr; ubah informasi nama/logo &rarr; simpan |
| **Route / Controller** | `GET\|POST /pengaturan/profil-sistem`<br>`ProfilSistemController` |
| **Screenshot Fitur** | ![Pengaturan & Profil Sistem](screenshot/Pengaturan%20%26%20Profil%20Sistem.png) |

---

### 5. Riwayat Aktivitas (Audit Trail)
| Komponen | Deskripsi |
| :--- | :--- |
| **Status** | ✅ Sudah Ada |
| **Tujuan Fitur** | Mencatat dan menampilkan seluruh log aktivitas dari pengguna dalam sistem |
| **Aktor** | Admin, Owner |
| **Alur Fitur** | Buka menu riwayat aktivitas &rarr; sistem menampilkan tabel log kejadian |
| **Route / Controller** | `GET /riwayat-aktivitas`<br>`RiwayatAktivitasController` |
| **Screenshot Fitur** | ![Riwayat Aktivitas (Audit Trail)](screenshot/Riwayat%20Aktivitas%20%28Audit%20Trail%29.png) |

---

### 6. Pencatatan Produksi (Telur & Pupuk)
| Komponen | Deskripsi |
| :--- | :--- |
| **Status** | ✅ Sudah Ada |
| **Tujuan Fitur** | Mencatat hasil produksi harian dari kandang untuk diakumulasi |
| **Aktor** | Admin, Pegawai Kandang |
| **Alur Fitur** | Pilih menu pencatatan &rarr; pilih batch/kandang &rarr; input produksi &rarr; simpan |
| **Route / Controller** | `GET\|POST /pencatatan/produksi-telur`<br>`GET\|POST /pencatatan/pupuk`<br>`ProduksiTelurController`, `ProduksiPupukController` |
| **Screenshot Fitur** | ![Pencatatan Produksi Telur](screenshot/Pencatatan%20Produksi%20Telur.png)<br>![Pencatatan Produksi Pupuk](screenshot/Pencatatan%20Produksi%20Pupuk.png) |

---

### 7. Pencatatan Konsumsi & Suhu
| Komponen | Deskripsi |
| :--- | :--- |
| **Status** | ✅ Sudah Ada |
| **Tujuan Fitur** | Memantau dan mencatat konsumsi pakan/vitamin serta suhu lingkungan kandang |
| **Aktor** | Admin, Pegawai Kandang |
| **Alur Fitur** | Pilih menu pencatatan &rarr; pilih batch/kandang &rarr; input nilai &rarr; simpan |
| **Route / Controller** | `GET\|POST /pencatatan/konsumsi-pakan`<br>`GET\|POST /pencatatan/suhu`<br>`KonsumsiPakanController`, `SuhuKandangController` |
| **Screenshot Fitur** | ![Pencatatan Konsumsi Pakan](screenshot/Pencatatan%20Konsumsi%20Pakan.png)<br>![Pencatatan Suhu Kandang](screenshot/Pencatatan%20Suhu%20Kandang.png) |

---

### 8. Pencatatan Kematian (Deplesi)
| Komponen | Deskripsi |
| :--- | :--- |
| **Status** | ✅ Sudah Ada |
| **Tujuan Fitur** | Mencatat jumlah ayam yang mati atau diafkir pada batch kandang tertentu |
| **Aktor** | Admin, Pegawai Kandang |
| **Alur Fitur** | Buka menu deplesi &rarr; pilih batch &rarr; input jumlah kematian &rarr; simpan |
| **Route / Controller** | `GET\|POST /pencatatan/deplesi`<br>`DeplesiController` |
| **Screenshot Fitur** | ![Pencatatan Deplesi Ayam](screenshot/Pencatatan%20Deplesi%20Ayam.png) |

---

### 9. Kandang Operasional
| Komponen | Deskripsi |
| :--- | :--- |
| **Status** | ✅ Sudah Ada |
| **Tujuan Fitur** | Mengatur penempatan (assignment) ayam ke dalam kandang berdasarkan batch |
| **Aktor** | Admin, Owner |
| **Alur Fitur** | Buka menu kandang operasional &rarr; pilih batch &rarr; assign kandang &rarr; simpan |
| **Route / Controller** | `GET /kandang`<br>`POST /batch/assign`<br>`KandangOperasionalController` |
| **Screenshot Fitur** | ![Kandang Operasional](screenshot/Kandang%20Operasional.png) |

---

### 10. Manajemen Master Data
| Komponen | Deskripsi |
| :--- | :--- |
| **Status** | ✅ Sudah Ada |
| **Tujuan Fitur** | Mengelola data inti seperti Kandang, Barang, Supplier, Pegawai, Pelanggan, dsb (CRUD) |
| **Aktor** | Admin, Owner |
| **Alur Fitur** | Pilih sub-menu master data &rarr; tambah/edit data form &rarr; simpan |
| **Route / Controller** | `Resource /master-data/*`<br>`KandangController`, `BarangController`, dll |
| **Screenshot Fitur** | ![Master Data Barang](screenshot/Master%20Data%20Barang.png)<br>![Master Data Kandang](screenshot/Master%20Data%20Kandang.png)<br>![Master Data Kategori Biaya](screenshot/Master%20Data%20Kategori%20Biaya.png)<br>![Master Data Pegawai](screenshot/Master%20Data%20Pegawai.png)<br>![Master Data Pelanggan](screenshot/Master%20Data%20Pelanggan.png)<br>![Master Data Rekening Kas](screenshot/Master%20Data%20Rekening%20Kas.png)<br>![Master Data Supplier](screenshot/Master%20Data%20Supplier.png) |

---

### 11. Transaksi Penjualan & Pembelian
| Komponen | Deskripsi |
| :--- | :--- |
| **Status** | ✅ Sudah Ada |
| **Tujuan Fitur** | Memproses transaksi penjualan produk (telur/ayam) dan pembelian kebutuhan |
| **Aktor** | Admin, Owner, Sales |
| **Alur Fitur** | Buka menu transaksi &rarr; buat baru &rarr; input data & pelanggan &rarr; simpan |
| **Route / Controller** | `GET\|POST /transaksi/penjualan`<br>`GET\|POST /transaksi/pembelian`<br>`PenjualanController`, `PembelianController` |
| **Screenshot Fitur** | ![Transaksi Pembelian Material Gudang (Vitamin, Pakan)](screenshot/Transaksi%20Pembelian%20Material%20Gudang%28Vitamin%2C%20Pakan%29.png)<br>![Transaksi Pembelian Pullet Ayam](screenshot/Transaksi%20Pembelian%20Pullet%20Ayam.png)<br>![Transaksi Penjualan Pupuk](screenshot/Transaksi%20Penjualan%20Pupuk.png)<br>![Transaksi Penjualan Telur](screenshot/Transaksi%20Penjualan%20Telur.png) |

---

### 12. Manajemen Gudang
| Komponen | Deskripsi |
| :--- | :--- |
| **Status** | ✅ Sudah Ada |
| **Tujuan Fitur** | Mengelola stok inventaris barang serta melakukan penyesuaian (adjustment) stok |
| **Aktor** | Admin, Owner, Pegawai Gudang |
| **Alur Fitur** | Buka menu gudang &rarr; lihat daftar stok barang &rarr; klik ubah stok &rarr; simpan |
| **Route / Controller** | `GET\|POST /gudang`<br>`GET\|POST /gudang/adjust/{barang}`<br>`GudangController` |
| **Screenshot Fitur** | ![Manajemen Gudang](screenshot/Manajemen%20Gudang.png) |

---

### 13. Manajemen Keuangan
| Komponen | Deskripsi |
| :--- | :--- |
| **Status** | ✅ Sudah Ada |
| **Tujuan Fitur** | Mencatat biaya operasional serta memonitor Buku Kas, Buku Utang, dan Piutang |
| **Aktor** | Admin, Owner |
| **Alur Fitur** | Buka menu keuangan &rarr; lihat saldo / input pengeluaran / lunasi &rarr; simpan |
| **Route / Controller** | `GET /keuangan/buku-kas`<br>`GET\|POST /keuangan/buku-utang`<br>`BiayaOperasionalController`, `BukuKasController` |
| **Screenshot Fitur** | ![Manajemen Keuangan (Biaya Operasional)](screenshot/Manajemen%20Keuangan%28Biaya%20Operasional%29.png)<br>![Manajemen Keuangan (Buku Kas)](screenshot/Manajemen%20Keuangan%28Buku%20Kas%29.png)<br>![Manajemen Keuangan (Buku Piutang)](screenshot/Manajemen%20Keuangan%28Buku%20Piutang%29.png)<br>![Manajemen Keuangan (Buku Utang)](screenshot/Manajemen%20Keuangan%28Buku%20Utang%29.png) |

---

### 14. Laporan & Cetak PDF
| Komponen | Deskripsi |
| :--- | :--- |
| **Status** | ✅ Sudah Ada |
| **Tujuan Fitur** | Menghasilkan laporan kinerja produksi dan keuangan (Laba/Rugi), beserta ekspor PDF |
| **Aktor** | Admin, Owner |
| **Alur Fitur** | Buka menu laporan &rarr; pilih periode tanggal &rarr; generate &rarr; preview &rarr; cetak PDF |
| **Route / Controller** | `GET /laporan/laba-rugi`<br>`GET /laporan/cetak/*`<br>`LabaRugiController`, `CetakProduksiController` |
| **Screenshot Fitur** | ![Laporan Produksi](screenshot/Laporan%20Produksi.png)<br>![Cetak Pembelian Barang](screenshot/Cetak%20Pembelian%20Barang.png)<br>![Cetak Penjualan Telur](screenshot/Cetak%20Penjualan%20Telur.png)<br>![Cetak Produksi Telur](screenshot/Cetak%20Produksi%20Telur.png) |

---

### 15. Export Laporan ke Excel
| Komponen | Deskripsi |
| :--- | :--- |
| **Status** | ⏳ On Progress |
| **Tujuan Fitur** | Kemampuan untuk mengunduh arsip laporan dalam format Spreadsheet (Excel) |
| **Aktor** | Admin, Owner |
| **Alur Fitur** | Buka laporan &rarr; klik tombol export Excel &rarr; file `.xlsx` diunduh |
| **Route / Controller** | *(Belum diimplementasikan)* |
| **Screenshot Fitur** | *[Belum diimplementasikan]* |
