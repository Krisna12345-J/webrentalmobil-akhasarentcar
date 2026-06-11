# webrentalmobil-akhasarentcar
Sistem Informasi Manajemen Rental Mobil Berbasis Web
Akhasa Rent Car - Sistem Informasi Manajemen Rental Mobil

Aplikasi berbasis web (*Full-Stack*) untuk manajemen penyewaan mobil lepas kunci dan dengan *driver*. Sistem ini dirancang untuk mendigitalisasi proses bisnis operasional rental, mencegah *double-booking* secara *real-time*, dan menyediakan dasbor analitik (*Business Intelligence*) bagi pemilik usaha.

## Latar Belakang Proyek
Proyek ini dibangun untuk menyelesaikan 3 kendala fundamental pada mitra bisnis rental mobil konvensional:
1. **Risiko Kehilangan Data:** Beralih dari pencatatan kertas ke basis data (*database*) terpusat.
2. **Bentrok Jadwal (Double Booking):** Menerapkan sistem verifikasi status armada *real-time* berbasis AJAX.
3. **Pencarian Riwayat Transaksi:** Sentralisasi data transaksi pelanggan, persetujuan pembayaran, hingga ekspor laporan.

---

## Fitur Utama

### Sisi Pelanggan (Customer Area)
- **Katalog Real-Time:** Melihat daftar ketersediaan armada, harga, dan spesifikasi kendaraan secara langsung.
- **Reservasi Instan (Modal Pop-up):** Pemesanan mobil dengan kalkulasi harga otomatis berdasarkan durasi hari tanpa *reload* halaman.
- **Sistem Pembayaran Digital:** Integrasi informasi transfer Bank (BCA, Mandiri) dan E-Wallet (GoPay, DANA).
- **Upload Bukti Pembayaran:** Pengunggahan struk transaksi dengan validasi format dan ukuran file.
- **Cetak Invoice Digital:** Pelanggan dapat mengunduh bukti tanda terima (*Invoice*) berformat PDF setelah pembayaran disetujui.

### Sisi Administrator (Panel Admin)
- **Dashboard Business Intelligence (BI):** Visualisasi data tren pendapatan bulanan (Grafik Garis) dan Top 5 Armada Terlaris (Grafik Donat) menggunakan Chart.js.
- **Manajemen Transaksi:** Memantau antrean pembayaran dan memvalidasi (Setuju/Tolak) bukti transfer pelanggan.
- **Notifikasi WhatsApp Otomatis:** Begitu admin menyetujui pembayaran, sistem otomatis mengirimkan pesan konfirmasi ke WhatsApp pelanggan (terintegrasi dengan Fonnte API).
- **Anti Hit & Run:** Admin dapat membatalkan pesanan yang belum dibayar, sehingga status mobil otomatis kembali "Tersedia" di katalog depan.
- **Ekspor Laporan:** Mengunduh rekapitulasi data pendapatan Lunas ke dalam format Excel (.csv).

---

## Teknologi yang Digunakan

**Front-End:**
- HTML5, CSS3, JavaScript (ES6+)
- **Tailwind CSS** (Framework UI/UX)
- **SweetAlert2** (Pop-up & Flash Messages)
- **AOS** (Animate On Scroll Library)
- **Chart.js** (Visualisasi Data Analitik)

**Back-End & Database:**
- **PHP 8.x Native** (Procedural & OOP Logic)
- **MySQL** (Relational Database Management System)
- Fitur Keamanan: `password_hash()` Bcrypt, Prepared Statements & Session Handling.
- **API Integration:** cURL PHP untuk *WhatsApp Gateway*.

---

## Panduan Instalasi (Local Development)

Ikuti langkah-langkah berikut untuk menjalankan proyek ini di komputer lokal Anda:

### 1. Persiapan Server
- Pastikan Anda telah menginstal **XAMPP** (Apache & MySQL).
- *Clone* atau *Download* repository ini.
- Ekstrak folder proyek dan ubah namanya menjadi `akhasarentcar`.
- Pindahkan folder tersebut ke direktori `C:\xampp\htdocs\`.

### 2. Konfigurasi Database
- Buka XAMPP Control Panel dan jalankan **Apache** serta **MySQL**.
- Buka browser dan akses `http://localhost/phpmyadmin`.
- Buat database baru dengan nama persis: **`akhasarentcar_db`**.
- Pilih database tersebut, klik tab **Import**.
- Pilih file `akhasarentcar_db.sql` yang ada di dalam root folder proyek, lalu klik **Go**.

### 3. Konfigurasi API WhatsApp (Opsional untuk testing WA)
- Buka file `api_client/validasi_handler.php`.
- Buat akun di [Fonnte](https://fonnte.com/), hubungkan nomor WA, dan dapatkan **Token API**.
- Masukkan Token Anda pada variabel `$token = 'TOKEN_API_ANDA_DISINI';`.

### 4. Menjalankan Aplikasi
- Buka browser dan akses URL: `http://localhost/akhasarentcar`
- **Kredensial Default Administrator:**
  - **Email:** `admin@akhasarentcar.id` (Sesuaikan dengan data di tabel `users`)
  - **Password:** `password` atau kata sandi yang Anda buat sendiri.

---

## Struktur Direktori Utama
```text
akhasarentcar/
│
├── api_client/             # Logika Backend (PHP Handlers, API Call, Export)
├── assets/                 # Penyimpanan file statis (Gambar mobil, dll)
├── uploads/receipts/       # Tempat tersimpannya bukti transfer pelanggan
├── views/
│   ├── admin/              # Halaman Dashboard Administrator
│   ├── auth/               # Halaman Login & Registrasi
│   └── customer/           # Halaman Dashboard Pelanggan
├── index.php               # Halaman Utama (Landing Page & Katalog)
└── README.md               # Dokumentasi Proyek

---

Tim Pengembang
Dikembangkan untuk memenuhi Tugas Proyek Mata Kuliah Analisis & Perancangan Sistem Informasi - UPN "Veteran" Jakarta (Tahun 2026).
- Krisna Dwi Saputra (2410501078)
- Muhamad Fauzi Achsan (2410501091)
- Muhammad Nabil Irpi Syafei (2410501096)

Copyright © 2026 Akhasa Rent Car.
