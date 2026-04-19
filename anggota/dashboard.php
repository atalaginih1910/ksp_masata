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
$success_message = $_SESSION['success'] ?? '';
$error_message = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);

$sql_anggota = "SELECT * FROM anggota WHERE id_anggota = $id_anggota LIMIT 1";
$result_anggota = query($sql_anggota);
$anggota = fetch_single($result_anggota);

$sql_total_simpanan = "SELECT COALESCE(SUM(besar_simpanan), 0) AS total FROM simpanan WHERE id_anggota = $id_anggota";
$total_simpanan = fetch_single(query($sql_total_simpanan));

$sql_total_pinjaman = "SELECT COALESCE(SUM(besar_pinjaman), 0) AS total FROM pinjaman WHERE id_anggota = $id_anggota";
$total_pinjaman = fetch_single(query($sql_total_pinjaman));

$sql_total_angsuran = "SELECT COALESCE(SUM(besar_angsuran), 0) AS total FROM angsuran WHERE id_anggota = $id_anggota AND tgl_pembayaran IS NOT NULL";
$total_angsuran = fetch_single(query($sql_total_angsuran));

$sql_total_pinjaman_lunas = "SELECT COUNT(*) AS total FROM pinjaman WHERE id_anggota = $id_anggota AND tgl_pelunasan IS NOT NULL";
$total_pinjaman_lunas = fetch_single(query($sql_total_pinjaman_lunas));

$sql_total_pinjaman_pending = "SELECT COUNT(*) AS total FROM pinjaman WHERE id_anggota = $id_anggota AND tgl_acc_peminjam IS NULL";
$total_pinjaman_pending = fetch_single(query($sql_total_pinjaman_pending));

$sql_total_pinjaman_disetujui = "SELECT COUNT(*) AS total FROM pinjaman WHERE id_anggota = $id_anggota AND tgl_acc_peminjam IS NOT NULL AND tgl_pelunasan IS NULL";
$total_pinjaman_disetujui = fetch_single(query($sql_total_pinjaman_disetujui));

$sql_kategori = "SELECT id_kategori_pinjaman, nama_pinjaman FROM kategori_pinjaman ORDER BY nama_pinjaman ASC";
$kategori_pinjaman = fetch_all(query($sql_kategori));

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

$sql_chart = "SELECT
                DATE_FORMAT(tgl_simpanan, '%Y-%m') AS periode,
                SUM(besar_simpanan) AS total_simpanan
              FROM simpanan
              WHERE id_anggota = $id_anggota
              GROUP BY DATE_FORMAT(tgl_simpanan, '%Y-%m')
              ORDER BY periode DESC
              LIMIT 6";
$chart_rows = fetch_all(query($sql_chart));

$months = [];
$chart_simpanan = [];
$chart_pinjaman = [];

for ($i = 5; $i >= 0; $i--) {
    $ts = strtotime("-$i months");
    $key = date('Y-m', $ts);
    $months[$key] = date('M Y', $ts);
    $chart_simpanan[$key] = 0;
    $chart_pinjaman[$key] = 0;
}

foreach ($chart_rows as $row) {
    if (isset($chart_simpanan[$row['periode']])) {
        $chart_simpanan[$row['periode']] = (float) $row['total_simpanan'];
    }
}

$sql_chart_pinjaman = "SELECT
                        DATE_FORMAT(tgl_pengajuan_pinjaman, '%Y-%m') AS periode,
                        SUM(besar_pinjaman) AS total_pinjaman
                      FROM pinjaman
                      WHERE id_anggota = $id_anggota
                      GROUP BY DATE_FORMAT(tgl_pengajuan_pinjaman, '%Y-%m')
                      ORDER BY periode DESC
                      LIMIT 6";
$chart_rows_pinjaman = fetch_all(query($sql_chart_pinjaman));

foreach ($chart_rows_pinjaman as $row) {
    if (isset($chart_pinjaman[$row['periode']])) {
        $chart_pinjaman[$row['periode']] = (float) $row['total_pinjaman'];
    }
}

$total_pinjaman_value = (float) ($total_pinjaman['total'] ?? 0);
$total_angsuran_value = (float) ($total_angsuran['total'] ?? 0);
$progress_pelunasan = 0;
if ($total_pinjaman_value > 0) {
    $progress_pelunasan = min(100, round(($total_angsuran_value / $total_pinjaman_value) * 100));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Anggota - MASATA PINJAMIN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <style>
        :root {
            --primary-color: #2dd4bf;
            --secondary-color: #38bdf8;
            --dark-color: #0f172a;
            --light-color: #f8fafc;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: Inter, "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background:
                radial-gradient(circle at 15% 20%, rgba(45, 212, 191, 0.16), transparent 26%),
                radial-gradient(circle at 85% 8%, rgba(56, 189, 248, 0.14), transparent 22%),
                linear-gradient(180deg, #edf7fb 0%, #f6fbff 100%);
            color: #0f172a;
        }
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(15, 23, 42, 0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(15, 23, 42, 0.02) 1px, transparent 1px);
            background-size: 38px 38px;
            mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.7), transparent 88%);
            pointer-events: none;
            z-index: -1;
        }
        .sidebar {
            background: linear-gradient(180deg, rgba(8, 47, 73, 0.96) 0%, rgba(15, 118, 110, 0.95) 100%);
            min-height: 100vh;
            padding: 18px 0;
            position: fixed;
            left: 0;
            top: 0;
            width: 260px;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 0 18px 45px rgba(2, 8, 23, 0.24);
            border-right: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
        }
        .sidebar-brand {
            padding: 18px 20px 22px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
            margin-bottom: 14px;
        }
        .sidebar-brand h4 {
            color: #7ff0c3;
            font-weight: 800;
            margin-bottom: 5px;
            font-size: 18px;
            letter-spacing: 0.06em;
        }
        .sidebar-brand p {
            color: rgba(226, 232, 240, 0.78);
            font-size: 12px;
            margin: 0;
        }
        .sidebar-menu { padding: 0; list-style: none; }
        .sidebar-menu li { margin: 0; }
        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 13px 22px;
            color: rgba(226, 232, 240, 0.8);
            text-decoration: none;
            transition: transform 0.2s ease, background 0.2s ease, color 0.2s ease;
            border-left: 4px solid transparent;
            margin: 3px 10px;
            border-radius: 14px;
        }
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: linear-gradient(135deg, rgba(45, 212, 191, 0.18), rgba(56, 189, 248, 0.12));
            color: #ffffff;
            border-left-color: #7ff0c3;
            transform: translateX(3px);
        }
        .topnav {
            background: rgba(255, 255, 255, 0.72);
            border-bottom: 1px solid rgba(15, 23, 42, 0.06);
            color: #0f172a;
            padding: 14px 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: 260px;
            right: 0;
            z-index: 999;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
            backdrop-filter: blur(18px);
        }
        .topnav h2 {
            margin: 0;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: 0.02em;
        }
        .topnav-user {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .topnav-user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            background: linear-gradient(135deg, #0ea5e9 0%, #2dd4bf 100%);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            box-shadow: 0 10px 24px rgba(14, 165, 233, 0.22);
        }
        .main-content {
            margin-left: 260px;
            margin-top: 74px;
            padding: 28px;
            min-height: calc(100vh - 74px);
        }
        .card {
            border: 1px solid rgba(15, 23, 42, 0.06);
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.92);
            box-shadow: 0 16px 35px rgba(15, 23, 42, 0.06);
            transition: transform 0.22s ease, box-shadow 0.22s ease;
            overflow: hidden;
        }
        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.09);
        }
        .hero-banner {
            border: none;
            color: #fff;
            background: linear-gradient(135deg, rgba(14,165,233,0.92) 0%, rgba(16,185,129,0.92) 100%);
            overflow: hidden;
        }
        .hero-banner .badge {
            background: rgba(255,255,255,0.18);
            color: #fff;
        }
        .stat-card {
            color: #fff;
            border-radius: 16px;
            padding: 18px;
            position: relative;
            overflow: hidden;
        }
        .stat-card::after {
            content: "";
            position: absolute;
            right: -22px;
            bottom: -22px;
            width: 88px;
            height: 88px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.12);
        }
        .s1 { background: linear-gradient(135deg, #10b981, #2dd4bf); }
        .s2 { background: linear-gradient(135deg, #0ea5e9, #2563eb); }
        .s3 { background: linear-gradient(135deg, #f59e0b, #ef4444); }
        .s4 { background: linear-gradient(135deg, #06b6d4, #0ea5e9); }
        .progress-modern {
            height: 14px;
            border-radius: 30px;
            background: #e2e8f0;
            overflow: hidden;
        }
        .progress-modern .progress-bar {
            border-radius: 30px;
            background: linear-gradient(135deg, #2dd4bf 0%, #0ea5e9 100%);
            font-size: 11px;
            font-weight: 700;
        }
        .filter-group .btn {
            border-radius: 999px;
            font-weight: 600;
            padding: 8px 14px;
        }
        .table > :not(caption) > * > * {
            padding: 0.65rem 0.7rem;
            vertical-align: middle;
        }
        @media (max-width: 991px) {
            .sidebar { display: none; }
            .topnav { left: 0; padding: 12px 16px; }
            .main-content { margin-left: 0; margin-top: 68px; padding: 16px; }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-brand">
            <h4><i class="fas fa-piggy-bank"></i> MASATA</h4>
            <p>Portal Anggota</p>
        </div>
        <ul class="sidebar-menu">
            <li><a href="#overview" class="active"><i class="fas fa-house"></i><span>Ringkasan</span></a></li>
            <li><a href="#ajukan"><i class="fas fa-paper-plane"></i><span>Ajukan Pinjaman</span></a></li>
            <li><a href="#riwayat"><i class="fas fa-clock-rotate-left"></i><span>Riwayat Transaksi</span></a></li>
            <li><a href="<?php echo BASE_URL; ?>/logout.php" onclick="return confirm('Yakin ingin logout?');"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
        </ul>
    </div>

    <div class="topnav">
        <h2><i class="fas fa-user-circle"></i> Dashboard Anggota</h2>
        <div class="d-flex align-items-center gap-3">
            <a href="<?php echo BASE_URL; ?>/logout.php" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin logout?');">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
            <div class="topnav-user">
                <div class="topnav-user-avatar"><i class="fas fa-user"></i></div>
                <div>
                    <small>Welcome</small>
                    <div style="font-weight: bold;"><?php echo htmlspecialchars($_SESSION['anggota_nama']); ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-content" id="overview">
        <?php if ($success_message): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success_message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error_message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card hero-banner mb-4">
            <div class="card-body p-4 p-lg-5" style="position: relative;">
                <div style="position: absolute; inset: auto -60px -70px auto; width: 220px; height: 220px; border-radius: 50%; background: rgba(255,255,255,0.1);"></div>
                <div class="row align-items-center g-4">
                    <div class="col-lg-8">
                        <span class="badge mb-2">Portal Anggota</span>
                        <h3 style="font-weight: 900; letter-spacing: -0.02em;">Selamat datang, <?php echo htmlspecialchars($anggota['nama']); ?></h3>
                        <p style="max-width: 60ch; margin-bottom: 0; color: rgba(255,255,255,0.88);">
                            Kelola simpanan, pantau pinjaman, dan ajukan kebutuhan dana langsung dari dashboard anggota dengan tampilan yang seragam dengan sistem petugas.
                        </p>
                    </div>
                    <div class="col-lg-4">
                        <div class="card" style="background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.16); color: white;">
                            <div class="card-body">
                                <small style="opacity: 0.85;">Tanggal Hari Ini</small>
                                <div style="font-size: 1.2rem; font-weight: 800; margin-top: 7px;"><?php echo date('d M Y'); ?></div>
                                <small style="opacity: 0.85;">No Anggota: <?php echo intval($anggota['id_anggota']); ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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

        <div class="row g-3 mb-4">
            <div class="col-md-4"><div class="stat-card s4"><small>Pinjaman Pending</small><h4 class="mb-0"><?php echo intval($total_pinjaman_pending['total']); ?></h4></div></div>
            <div class="col-md-4"><div class="stat-card s2"><small>Pinjaman Disetujui</small><h4 class="mb-0"><?php echo intval($total_pinjaman_disetujui['total']); ?></h4></div></div>
            <div class="col-md-4"><div class="stat-card s1"><small>Pinjaman Lunas</small><h4 class="mb-0"><?php echo intval($total_pinjaman_lunas['total']); ?></h4></div></div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">Progress Pelunasan Keseluruhan</h6>
                    <strong><?php echo intval($progress_pelunasan); ?>%</strong>
                </div>
                <div class="progress-modern">
                    <div class="progress-bar" role="progressbar" style="width: <?php echo intval($progress_pelunasan); ?>%" aria-valuenow="<?php echo intval($progress_pelunasan); ?>" aria-valuemin="0" aria-valuemax="100">
                        <?php echo intval($progress_pelunasan); ?>%
                    </div>
                </div>
                <small class="text-muted">Perbandingan total angsuran terbayar terhadap total pinjaman yang pernah diajukan.</small>
            </div>
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
                                <thead><tr><th>Pengajuan</th><th>Pinjaman</th><th class="text-end">Jumlah</th><th>Status</th></tr></thead>
                                <tbody>
                                    <?php if (count($riwayat_pinjaman) > 0): foreach ($riwayat_pinjaman as $item): ?>
                                        <tr>
                                            <td><?php echo format_tanggal($item['tgl_pengajuan_pinjaman']); ?></td>
                                            <td><?php echo htmlspecialchars($item['nama_pinjaman']); ?></td>
                                            <td class="text-end"><?php echo format_rupiah($item['besar_pinjaman']); ?></td>
                                            <td>
                                                <?php
                                                if (!empty($item['tgl_pelunasan'])) {
                                                    echo '<span class="badge bg-success">Lunas</span>';
                                                } elseif (!empty($item['tgl_acc_peminjam'])) {
                                                    echo '<span class="badge bg-primary">Disetujui</span>';
                                                } else {
                                                    echo '<span class="badge bg-warning text-dark">Pending</span>';
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; else: ?>
                                        <tr><td colspan="4" class="text-center text-muted">Belum ada data</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12" id="ajukan">
                <div class="card mb-4">
                    <div class="card-body">
                        <h6 class="mb-3">Ajukan Pinjaman Baru</h6>
                        <p class="text-muted mb-3" style="font-size: 14px;">Ajukan pinjaman langsung dari portal anggota. Pengajuan akan diverifikasi petugas koperasi.</p>

                        <form method="POST" action="<?php echo BASE_URL; ?>/anggota/pinjaman_ajukan.php" class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="id_kategori_pinjaman">Kategori Pinjaman</label>
                                <select name="id_kategori_pinjaman" id="id_kategori_pinjaman" class="form-select" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php foreach ($kategori_pinjaman as $kategori): ?>
                                        <option value="<?php echo intval($kategori['id_kategori_pinjaman']); ?>"><?php echo htmlspecialchars($kategori['nama_pinjaman']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="besar_pinjaman">Jumlah Pinjaman</label>
                                <input type="number" name="besar_pinjaman" id="besar_pinjaman" class="form-control" min="50000" step="1000" placeholder="Contoh: 2000000" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="ket">Catatan</label>
                                <input type="text" name="ket" id="ket" class="form-control" maxlength="255" placeholder="Contoh: Pinjaman biaya pendidikan">
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i> Kirim Pengajuan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <h6 class="mb-3">Grafik Personal 6 Bulan</h6>
                        <canvas id="chartAnggota" height="120"></canvas>
                    </div>
                </div>

                <div class="card" id="riwayat">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <h6 class="mb-0">Riwayat Angsuran</h6>
                            <div class="filter-group d-flex gap-2">
                                <button type="button" class="btn btn-outline-primary btn-sm btn-filter" data-filter="all">Semua</button>
                                <button type="button" class="btn btn-outline-success btn-sm btn-filter" data-filter="paid">Lunas</button>
                                <button type="button" class="btn btn-outline-warning btn-sm btn-filter" data-filter="unpaid">Belum Bayar</button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead><tr><th>Kategori</th><th>Angsuran Ke</th><th>Tanggal Bayar</th><th class="text-end">Jumlah</th><th>Status</th></tr></thead>
                                <tbody id="angsuranTableBody">
                                    <?php if (count($riwayat_angsuran) > 0): foreach ($riwayat_angsuran as $item): ?>
                                        <tr data-status="<?php echo $item['tgl_pembayaran'] ? 'paid' : 'unpaid'; ?>">
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
    <script>
        document.querySelectorAll('.sidebar-menu a[href^="#"]').forEach(function(el) {
            el.addEventListener('click', function(e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    document.querySelectorAll('.sidebar-menu a').forEach(function(a) { a.classList.remove('active'); });
                    this.classList.add('active');
                }
            });
        });

        const labels = <?php echo json_encode(array_values($months)); ?>;
        const dataSimpanan = <?php echo json_encode(array_values($chart_simpanan)); ?>;
        const dataPinjaman = <?php echo json_encode(array_values($chart_pinjaman)); ?>;

        const chartEl = document.getElementById('chartAnggota');
        if (chartEl) {
            new Chart(chartEl, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Simpanan',
                            data: dataSimpanan,
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16,185,129,0.16)',
                            fill: true,
                            tension: 0.35,
                            borderWidth: 2
                        },
                        {
                            label: 'Pinjaman',
                            data: dataPinjaman,
                            borderColor: '#0ea5e9',
                            backgroundColor: 'rgba(14,165,233,0.1)',
                            fill: true,
                            tension: 0.35,
                            borderWidth: 2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'top' }
                    },
                    scales: {
                        y: {
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        }

        const filterButtons = document.querySelectorAll('.btn-filter');
        const rows = document.querySelectorAll('#angsuranTableBody tr[data-status]');
        filterButtons.forEach(function(btn) {
            btn.addEventListener('click', function() {
                const filter = this.dataset.filter;
                filterButtons.forEach(function(b) {
                    b.classList.remove('active');
                });
                this.classList.add('active');

                rows.forEach(function(row) {
                    if (filter === 'all' || row.dataset.status === filter) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>
</html>
