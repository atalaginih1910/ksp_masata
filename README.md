<<<<<<< HEAD
# 🏪 MASATA PINJAMIN - Aplikasi Koperasi Simpan Pinjam

Aplikasi web manajemen **Koperasi Simpan Pinjam** yang dibangun dengan **PHP Native** dan **Bootstrap 5**.

## 📋 Fitur Utama

✅ **Sistem Login** - Autentikasi pengguna dengan session management  
✅ **Dashboard** - Statistik dan grafik transaksi real-time  
✅ **Manajemen Anggota** - CRUD lengkap data anggota koperasi  
✅ **Transaksi Simpanan** - Pencatatan dan tracking simpanan anggota  
✅ **Transaksi Pinjaman** - Pengajuan, persetujuan, dan tracking status pinjaman  
✅ **Manajemen Angsuran** - Pembayaran dan monitoring angsuran pinjaman  
✅ **Kategori Pinjaman** - Pengaturan jenis-jenis pinjaman  
✅ **Laporan Lengkap** - Laporan anggota, simpanan, pinjaman, dan angsuran dengan fitur print  
✅ **Responsive Design** - Tampilan mobile-friendly dengan Bootstrap 5  
✅ **Grafik Interaktif** - Chart.js untuk visualisasi data transaksi  

## 🎨 Desain & UI

- **Tema Warna**: Hijau & Biru (gradient elegan)
- **Layout**: Sidebar navigation + Top navigation bar
- **Komponen**: Card layout, modal forms, responsive tables
- **Icon**: Font Awesome 6.4.0
- **Framework CSS**: Bootstrap 5.3.0

## 🛠️ Teknologi

```
- PHP 8.0+
- MySQL/MariaDB
- Bootstrap 5.3.0
- Chart.js 3.9.1
- Font Awesome 6.4.0
- MySQLi (Native PHP Database)
```

## 📂 Struktur Folder

```
ksp_masata/
├── admin/
│   ├── dashboard.php
│   ├── anggota/
│   ├── simpanan/
│   ├── pinjaman/
│   ├── angsuran/
│   ├── kategori/
│   └── laporan/
├── config/
│   ├── database.php
│   ├── session.php
│   └── helper.php
├── partials/
│   ├── header.php
│   ├── sidebar.php
│   └── footer.php
├── assets/
│   ├── css/
│   ├── js/
│   └── img/
├── index.php
├── login.php
├── logout.php
└── README.md
```

## 🚀 Instalasi & Setup

### 1. Persiapan Database

```sql
CREATE DATABASE `koperasi_simpan_pinjam`;
USE `koperasi_simpan_pinjam`;
```

Import file SQL yang disediakan melalui phpMyAdmin atau command line.

### 2. Konfigurasi Database

Edit file `config/database.php`:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'koperasi_simpan_pinjam');
```

### 3. Akses Aplikasi

```
http://localhost/Ahmad/ksp_masata/login.php
```

### 4. Login

**Demo Credentials:**
- Username: `Ahmad`
- Password: `admin123`

## 📚 Panduan Penggunaan

### Dashboard
- Statistik anggota, simpanan, pinjaman, angsuran
- Grafik simpanan dan pinjaman
- List anggota terbaru dan pinjaman pending

### Manajemen Anggota
- Tambah, edit, hapus anggota
- Lihat detail riwayat simpanan dan pinjaman

### Simpanan
- Catat transaksi simpanan per anggota
- Filter berdasarkan anggota dan bulan

### Pinjaman
- Ajukan pinjaman baru
- Approve pinjaman (Pending → Disetujui)
- Lihat detail dan riwayat angsuran

### Angsuran
- Catat pembayaran angsuran
- Track angsuran ke-n per pinjaman

### Laporan
- Laporan Anggota
- Laporan Simpanan (dengan total)
- Laporan Pinjaman (dengan total)
- Laporan Angsuran (dengan total)
- Fitur print untuk semua laporan

## 🔐 Keamanan

✓ Session Management  
✓ Input Validation  
✓ Safe Database Queries  
✓ CORS-Ready  

## 📞 Support

Untuk bantuan, hubungi administrator atau cek browser console untuk error details.

---

**Version**: 1.0.0  
**Last Updated**: April 2026  
**Developer**: Ahmad  

**Selamat menggunakan MASATA PINJAMIN!** 🎉
=======
# ksp_masata
koperasi simpan pinjam projet UK
>>>>>>> a7828f1d045c32e56059b9c4acc53e17cc15e453
