-- MASATA PINJAMIN - DATA SEED ONLY
-- Jalankan file ini setelah struktur tabel dari SETUP.sql sudah diimport.
-- Data di bawah ini disiapkan agar relasi dasar dan dashboard langsung terisi.

START TRANSACTION;

INSERT INTO `petugas_koperasi` (`id_petugas`, `nama`, `alamat`, `no_tlp`, `tmp_lhr`, `tgl_lhr`, `ket`) VALUES
(1, 'Ahmad', 'Jl. Merdeka No. 1, Cirebon', '08123456789', 'Cirebon', '1990-05-15', 'Admin'),
(2, 'Rina', 'Jl. Sudirman No. 5, Cirebon', '08987654321', 'Cirebon', '1992-08-20', 'Petugas');

INSERT INTO `kategori_pinjaman` (`id_kategori_pinjaman`, `nama_pinjaman`) VALUES
(1, 'Pinjaman Usaha'),
(2, 'Pinjaman Pendidikan'),
(3, 'Pinjaman Kesehatan'),
(4, 'Pinjaman Renovasi Rumah');

INSERT INTO `anggota` (`id_anggota`, `nama`, `alamat`, `tgl_lhr`, `tmp_lhr`, `j_kel`, `status`, `no_tlp`, `ket`) VALUES
(1, 'Budi Santoso', 'Jl. Melati No. 10, Cirebon', '1985-03-15', 'Cirebon', 'L', 'Aktif', '08111111111', 'Anggota aktif sejak 2024'),
(2, 'Siti Aminah', 'Jl. Mawar No. 22, Cirebon', '1990-07-20', 'Cirebon', 'P', 'Aktif', '08222222222', 'Anggota simpan pinjam'),
(3, 'Andi Saputra', 'Jl. Kenanga No. 8, Cirebon', '1988-11-05', 'Bandung', 'L', 'Aktif', '08333333333', 'Anggota reguler'),
(4, 'Rina Lestari', 'Jl. Dahlia No. 14, Cirebon', '1993-02-10', 'Semarang', 'P', 'Aktif', '08444444444', 'Anggota baru'),
(5, 'Dedi Pratama', 'Jl. Anggrek No. 7, Cirebon', '1987-09-12', 'Jakarta', 'L', 'Non Aktif', '08555555555', 'Anggota lama');

INSERT INTO `simpanan` (`id_simpanan`, `nm_simpanan`, `id_anggota`, `tgl_simpanan`, `besar_simpanan`, `ket`) VALUES
(1, 'Simpanan Pokok', 1, '2026-01-05', 100000.00, 'Setoran awal'),
(2, 'Simpanan Wajib', 1, '2026-02-05', 50000.00, 'Bulanan'),
(3, 'Simpanan Pokok', 2, '2026-01-10', 100000.00, 'Setoran awal'),
(4, 'Simpanan Wajib', 2, '2026-02-10', 50000.00, 'Bulanan'),
(5, 'Simpanan Sukarela', 3, '2026-03-12', 250000.00, 'Tambahan tabungan'),
(6, 'Simpanan Wajib', 4, '2026-03-20', 50000.00, 'Bulanan');

INSERT INTO `angsuran` (`id_angsuran`, `id_kategori`, `id_anggota`, `tgl_pembayaran`, `angsuran_ke`, `besar_angsuran`, `ket`) VALUES
(1, 1, 1, '2026-02-15', 1, 250000.00, 'Angsuran pertama'),
(2, 2, 2, '2026-03-15', 1, 150000.00, 'Angsuran pendidikan'),
(3, 3, 3, NULL, 1, 200000.00, 'Belum dibayar'),
(4, 1, 4, '2026-04-01', 2, 300000.00, 'Angsuran ke-2'),
(5, 4, 1, NULL, 1, 175000.00, 'Menunggu pembayaran');

INSERT INTO `detail_angsuran` (`id_detail`, `id_angsuran`, `tgl_jatuh_tempo`, `besar_angsuran`, `ket`) VALUES
(1, 1, '2026-03-15', 250000.00, 'Jatuh tempo angsuran 1'),
(2, 2, '2026-04-15', 150000.00, 'Jatuh tempo angsuran 1'),
(3, 3, '2026-05-15', 200000.00, 'Jatuh tempo angsuran 1'),
(4, 4, '2026-05-01', 300000.00, 'Jatuh tempo angsuran 2'),
(5, 5, '2026-04-20', 175000.00, 'Jatuh tempo angsuran 1');

INSERT INTO `pinjaman` (`id_pinjaman`, `nama_pinjaman`, `id_anggota`, `besar_pinjaman`, `tgl_pengajuan_pinjaman`, `tgl_acc_peminjam`, `tgl_pinjaman`, `tgl_pelunasan`, `id_angsuran`, `ket`) VALUES
(1, 'Pinjaman Usaha Mikro', 1, 5000000.00, '2026-01-10', '2026-01-12', '2026-01-15', NULL, 1, 'Disetujui dan sedang berjalan'),
(2, 'Pinjaman Pendidikan', 2, 3000000.00, '2026-02-08', '2026-02-10', NULL, NULL, 2, 'Disetujui, menunggu pencairan'),
(3, 'Pinjaman Kesehatan', 3, 2500000.00, '2026-03-01', NULL, NULL, NULL, 3, 'Masih pending'),
(4, 'Pinjaman Renovasi Rumah', 4, 7000000.00, '2025-12-20', '2025-12-22', '2025-12-25', '2026-03-25', 4, 'Sudah lunas');

COMMIT;
