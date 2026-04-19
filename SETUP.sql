-- ============================================
-- MASATA PINJAMIN - SQL Setup Script
-- Koperasi Simpan Pinjam Database
-- ============================================

-- Buat Database
CREATE DATABASE IF NOT EXISTS `koperasi_simpan_pinjam`;
USE `koperasi_simpan_pinjam`;

-- ============================================
-- Table Structure
-- ============================================

-- TABLE: ANGGOTA
DROP TABLE IF EXISTS `anggota`;
CREATE TABLE `anggota` (
  `id_anggota` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) DEFAULT NULL,
  `alamat` text,
  `tgl_lhr` date DEFAULT NULL,
  `tmp_lhr` varchar(50) DEFAULT NULL,
  `j_kel` char(1) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `no_tlp` varchar(15) DEFAULT NULL,
  `ket` text,
  PRIMARY KEY (`id_anggota`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- TABLE: PETUGAS KOPERASI
DROP TABLE IF EXISTS `petugas_koperasi`;
CREATE TABLE `petugas_koperasi` (
  `id_petugas` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) DEFAULT NULL,
  `alamat` text,
  `no_tlp` varchar(15) DEFAULT NULL,
  `tmp_lhr` varchar(50) DEFAULT NULL,
  `tgl_lhr` date DEFAULT NULL,
  `ket` text,
  PRIMARY KEY (`id_petugas`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- TABLE: KATEGORI PINJAMAN
DROP TABLE IF EXISTS `kategori_pinjaman`;
CREATE TABLE `kategori_pinjaman` (
  `id_kategori_pinjaman` int NOT NULL AUTO_INCREMENT,
  `nama_pinjaman` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_kategori_pinjaman`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- TABLE: SIMPANAN
DROP TABLE IF EXISTS `simpanan`;
CREATE TABLE `simpanan` (
  `id_simpanan` int NOT NULL AUTO_INCREMENT,
  `nm_simpanan` varchar(100) DEFAULT NULL,
  `id_anggota` int DEFAULT NULL,
  `tgl_simpanan` date DEFAULT NULL,
  `besar_simpanan` decimal(12,2) DEFAULT NULL,
  `ket` text,
  PRIMARY KEY (`id_simpanan`),
  KEY `id_anggota` (`id_anggota`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- TABLE: PINJAMAN
DROP TABLE IF EXISTS `pinjaman`;
CREATE TABLE `pinjaman` (
  `id_pinjaman` int NOT NULL AUTO_INCREMENT,
  `nama_pinjaman` varchar(100) DEFAULT NULL,
  `id_anggota` int DEFAULT NULL,
  `besar_pinjaman` decimal(12,2) DEFAULT NULL,
  `tgl_pengajuan_pinjaman` date DEFAULT NULL,
  `tgl_acc_peminjam` date DEFAULT NULL,
  `tgl_pinjaman` date DEFAULT NULL,
  `tgl_pelunasan` date DEFAULT NULL,
  `id_angsuran` int DEFAULT NULL,
  `ket` text,
  PRIMARY KEY (`id_pinjaman`),
  KEY `id_anggota` (`id_anggota`),
  KEY `id_angsuran` (`id_angsuran`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- TABLE: ANGSURAN
DROP TABLE IF EXISTS `angsuran`;
CREATE TABLE `angsuran` (
  `id_angsuran` int NOT NULL AUTO_INCREMENT,
  `id_kategori` int DEFAULT NULL,
  `id_anggota` int DEFAULT NULL,
  `tgl_pembayaran` date DEFAULT NULL,
  `angsuran_ke` int DEFAULT NULL,
  `besar_angsuran` decimal(12,2) DEFAULT NULL,
  `ket` text,
  PRIMARY KEY (`id_angsuran`),
  KEY `id_kategori` (`id_kategori`),
  KEY `id_anggota` (`id_anggota`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- TABLE: DETAIL ANGSURAN
DROP TABLE IF EXISTS `detail_angsuran`;
CREATE TABLE `detail_angsuran` (
  `id_detail` int NOT NULL AUTO_INCREMENT,
  `id_angsuran` int DEFAULT NULL,
  `tgl_jatuh_tempo` date DEFAULT NULL,
  `besar_angsuran` decimal(12,2) DEFAULT NULL,
  `ket` text,
  PRIMARY KEY (`id_detail`),
  KEY `id_angsuran` (`id_angsuran`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Insert Data Default
-- ============================================

-- Insert Petugas Default
INSERT INTO `petugas_koperasi` (`nama`, `alamat`, `no_tlp`, `tmp_lhr`, `tgl_lhr`, `ket`) 
VALUES 
('Ahmad', 'Jl. Merdeka No. 1', '08123456789', 'Cirebon', '1990-05-15', 'Admin'),
('Rina', 'Jl. Sudirman No. 5', '08987654321', 'Cirebon', '1992-08-20', 'Petugas Koperasi');

-- Insert Kategori Pinjaman Default
INSERT INTO `kategori_pinjaman` (`nama_pinjaman`) 
VALUES 
('Pinjaman Usaha'),
('Pinjaman Pendidikan'),
('Pinjaman Kesehatan'),
('Pinjaman Perumahan');

-- Insert Anggota Sample (Optional)
INSERT INTO `anggota` (`nama`, `alamat`, `tgl_lhr`, `tmp_lhr`, `j_kel`, `status`, `no_tlp`, `ket`) 
VALUES 
('Budi Santoso', 'Jl. Ahmad Yani No. 10', '1985-03-15', 'Bandung', 'L', 'Aktif', '08111111111', 'Anggota tetap'),
('Siti Nurhaliza', 'Jl. Gatot Subroto No. 25', '1990-07-20', 'Jakarta', 'P', 'Aktif', '08222222222', 'Anggota tetap'),
('Ahmad Dahlan', 'Jl. Diponegoro No. 8', '1988-11-05', 'Yogyakarta', 'L', 'Aktif', '08333333333', 'Anggota tetap');

-- ============================================
-- Kesimpulan Setup
-- ============================================
-- Database berhasil dibuat dengan tabel-tabel berikut:
-- 1. anggota - Menyimpan data anggota koperasi
-- 2. petugas_koperasi - Menyimpan data petugas/admin
-- 3. kategori_pinjaman - Menyimpan jenis-jenis pinjaman
-- 4. simpanan - Menyimpan transaksi simpanan
-- 5. pinjaman - Menyimpan data pinjaman
-- 6. angsuran - Menyimpan data pembayaran angsuran
-- 7. detail_angsuran - Menyimpan detail per cicilan
--
-- Login default:
-- Username: Ahmad
-- Password: admin123
-- ============================================
