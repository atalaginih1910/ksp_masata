<?php
/**
 * Dashboard Anggota
 * MASATA PINJAMIN
 */

require_once '../config/database.php';
require_once '../config/session.php';
require_once '../config/helper.php';

require_anggota_login();

$id_anggota = intval($_SESSION['anggota_id']);

$sql_anggota = "SELECT * FROM anggota WHERE id_anggota = $id_anggota LIMIT 1";
$result_anggota = query($sql_anggota);
$anggota = fetch_single($result_anggota);

$sql_total_simpanan = "SELECT COALESCE(SUM(besar_simpanan), 0) AS total FROM simpanan WHERE id_anggota = $id_anggota";
$total_simpanan = fetch_single(query($sql_total_simpanan));

$sql_total_pinjaman = "SELECT COALESCE(SUM(besar_pinjaman), 0) AS total FROM pinjaman WHERE id_anggota = $id_anggota";
$total_pinjaman = fetch_single(query($sql_total_pinjaman));

$sql_total_angsuran = "SELECT COALESCE(SUM(besar_angsuran), 0) AS total FROM angsuran WHERE id_anggota = $id_anggota AND tgl_pembayaran IS NOT NULL";
$total_angsuran = fetch_single(query($sql_total_angsuran));

$sql_simpanan = "SELECT * FROM simpanan WHERE id_anggota = $id_anggota ORDER BY tgl_simpanan DESC LIMIT 10";
$riwayat_simpanan = fetch_all(query($sql_simpanan));

$sql_pinjaman = "SELECT * FROM pinjaman WHERE id_anggota = $id_anggota ORDER BY tgl_pengajuan_pinjaman DESC LIMIT 10";
$riwayat_pinjaman = fetch_all(query($sql_pinjaman));

$sql_angsuran = "SELECT ang.*, kp.nama_pinjaman
                FROM angsuran ang
                LEFT JOIN kategori_pinjaman kp ON ang.id_kategori = kp.id_kategori_pinjaman
                WHERE ang.id_anggota = $id_anggota
                ORDER BY ang.id_angsuran DESC
                LIMIT 10";
$riwayat_angsuran = fetch_all(query($sql_angsuran));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Anggota - MASATA PINJAMIN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f3f7fb; font-family: Inter, "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; }
        .topbar {
            background: linear-gradient(135deg, #0ea5e9 0%, #10b981 100%);
            color: #fff;
            padding: 16px 0;
            box-shadow: 0 10px 24px rgba(2, 132, 199, 0.2);
        }
        .card { border: none; border-radius: 16px; box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06); }
        .stat-card { color: #fff; border-radius: 16px; padding: 18px; }
        .s1 { background: linear-gradient(135deg, #10b981, #2dd4bf); }
        .s2 { background: linear-gradient(135deg, #0ea5e9, #2563eb); }
        .s3 { background: linear-gradient(135deg, #f59e0b, #ef4444); }
    </style>
</head>
<body>
    <div class="topbar">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0"><i class="fas fa-user-circle"></i> Dashboard Anggota</h4>
                <small>Selamat datang, <?php echo htmlspecialchars($_SESSION['anggota_nama']); ?></small>
            </div>
            <a href="<?php echo BASE_URL; ?>/logout.php" class="btn btn-light btn-sm" onclick="return confirm('Yakin ingin logout?');">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>

    <div class="container py-4">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="mb-3">Profil Anggota</h5>
                <div class="row">
                    <div class="col-md-4"><strong>No Anggota:</strong> <?php echo $anggota['id_anggota']; ?></div>
                    <div class="col-md-4"><strong>Nama:</strong> <?php echo htmlspecialchars($anggota['nama']); ?></div>
                    <div class="col-md-4"><strong>No Telepon:</strong> <?php echo htmlspecialchars($anggota['no_tlp']); ?></div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4"><div class="stat-card s1"><small>Total Simpanan</small><h4 class="mb-0"><?php echo format_rupiah($total_simpanan['total']); ?></h4></div></div>
            <div class="col-md-4"><div class="stat-card s2"><small>Total Pinjaman</small><h4 class="mb-0"><?php echo format_rupiah($total_pinjaman['total']); ?></h4></div></div>
            <div class="col-md-4"><div class="stat-card s3"><small>Total Angsuran Terbayar</small><h4 class="mb-0"><?php echo format_rupiah($total_angsuran['total']); ?></h4></div></div>
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="mb-3">Riwayat Simpanan</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead><tr><th>Tanggal</th><th>Jenis</th><th class="text-end">Jumlah</th></tr></thead>
                                <tbody>
                                    <?php if (count($riwayat_simpanan) > 0): foreach ($riwayat_simpanan as $item): ?>
                                        <tr>
                                            <td><?php echo format_tanggal($item['tgl_simpanan']); ?></td>
                                            <td><?php echo htmlspecialchars($item['nm_simpanan']); ?></td>
                                            <td class="text-end"><?php echo format_rupiah($item['besar_simpanan']); ?></td>
                                        </tr>
                                    <?php endforeach; else: ?>
                                        <tr><td colspan="3" class="text-center text-muted">Belum ada data</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="mb-3">Riwayat Pinjaman</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead><tr><th>Pengajuan</th><th>Pinjaman</th><th class="text-end">Jumlah</th></tr></thead>
                                <tbody>
                                    <?php if (count($riwayat_pinjaman) > 0): foreach ($riwayat_pinjaman as $item): ?>
                                        <tr>
                                            <td><?php echo format_tanggal($item['tgl_pengajuan_pinjaman']); ?></td>
                                            <td><?php echo htmlspecialchars($item['nama_pinjaman']); ?></td>
                                            <td class="text-end"><?php echo format_rupiah($item['besar_pinjaman']); ?></td>
                                        </tr>
                                    <?php endforeach; else: ?>
                                        <tr><td colspan="3" class="text-center text-muted">Belum ada data</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-3">Riwayat Angsuran</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead><tr><th>Kategori</th><th>Angsuran Ke</th><th>Tanggal Bayar</th><th class="text-end">Jumlah</th><th>Status</th></tr></thead>
                                <tbody>
                                    <?php if (count($riwayat_angsuran) > 0): foreach ($riwayat_angsuran as $item): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($item['nama_pinjaman'] ?: '-'); ?></td>
                                            <td><?php echo intval($item['angsuran_ke']); ?></td>
                                            <td><?php echo $item['tgl_pembayaran'] ? format_tanggal($item['tgl_pembayaran']) : '-'; ?></td>
                                            <td class="text-end"><?php echo format_rupiah($item['besar_angsuran']); ?></td>
                                            <td><?php echo $item['tgl_pembayaran'] ? '<span class="badge bg-success">Lunas</span>' : '<span class="badge bg-warning text-dark">Belum Bayar</span>'; ?></td>
                                        </tr>
                                    <?php endforeach; else: ?>
                                        <tr><td colspan="5" class="text-center text-muted">Belum ada data</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
