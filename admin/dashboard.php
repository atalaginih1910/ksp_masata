<?php
/**
 * Dashboard Admin
 * MASATA PINJAMIN
 */

require_once '../config/database.php';
require_once '../config/session.php';
require_once '../config/helper.php';

// Redirect jika belum login
require_login();

$page_title = 'Dashboard';

// Get statistik anggota
$q_anggota = "SELECT COUNT(*) as total FROM anggota";
$r_anggota = query($q_anggota);
$d_anggota = fetch_single($r_anggota);
$total_anggota = $d_anggota['total'];

// Get statistik simpanan
$q_simpanan = "SELECT SUM(besar_simpanan) as total FROM simpanan";
$r_simpanan = query($q_simpanan);
$d_simpanan = fetch_single($r_simpanan);
$total_simpanan = $d_simpanan['total'] ?? 0;

// Get statistik pinjaman
$q_pinjaman = "SELECT SUM(besar_pinjaman) as total FROM pinjaman";
$r_pinjaman = query($q_pinjaman);
$d_pinjaman = fetch_single($r_pinjaman);
$total_pinjaman = $d_pinjaman['total'] ?? 0;

// Get statistik angsuran
$q_angsuran = "SELECT SUM(besar_angsuran) as total FROM angsuran";
$r_angsuran = query($q_angsuran);
$d_angsuran = fetch_single($r_angsuran);
$total_angsuran = $d_angsuran['total'] ?? 0;

// Get data simpanan per bulan untuk chart
$q_chart_simpanan = "SELECT 
                    MONTH(tgl_simpanan) as bulan,
                    YEAR(tgl_simpanan) as tahun,
                    SUM(besar_simpanan) as total
                FROM simpanan
                GROUP BY YEAR(tgl_simpanan), MONTH(tgl_simpanan)
                ORDER BY tahun DESC, bulan DESC
                LIMIT 12";
$r_chart_simpanan = query($q_chart_simpanan);
$d_chart_simpanan = fetch_all($r_chart_simpanan);

// Get data pinjaman per bulan untuk chart
$q_chart_pinjaman = "SELECT 
                    MONTH(tgl_pinjaman) as bulan,
                    YEAR(tgl_pinjaman) as tahun,
                    SUM(besar_pinjaman) as total
                FROM pinjaman
                WHERE tgl_pinjaman IS NOT NULL
                GROUP BY YEAR(tgl_pinjaman), MONTH(tgl_pinjaman)
                ORDER BY tahun DESC, bulan DESC
                LIMIT 12";
$r_chart_pinjaman = query($q_chart_pinjaman);
$d_chart_pinjaman = fetch_all($r_chart_pinjaman);

// Get anggota terbaru
$q_anggota_terbaru = "SELECT * FROM anggota ORDER BY id_anggota DESC LIMIT 5";
$r_anggota_terbaru = query($q_anggota_terbaru);
$d_anggota_terbaru = fetch_all($r_anggota_terbaru);

// Get pinjaman pending
$q_pinjaman_pending = "SELECT p.*, a.nama FROM pinjaman p 
                      LEFT JOIN anggota a ON p.id_anggota = a.id_anggota
                      WHERE p.tgl_acc_peminjam IS NULL
                      ORDER BY p.tgl_pengajuan_pinjaman DESC
                      LIMIT 5";
$r_pinjaman_pending = query($q_pinjaman_pending);
$d_pinjaman_pending = fetch_all($r_pinjaman_pending);

// Prepare chart data
$months = [];
$simpanan_data = [];
$pinjaman_data = [];

// Create 12 months array
for ($i = 11; $i >= 0; $i--) {
    $timestamp = strtotime("-$i months");
    $bulan = date('n', $timestamp);
    $tahun = date('Y', $timestamp);
    $months[] = date('M Y', $timestamp);
}

// Fill chart data
foreach ($d_chart_simpanan as $item) {
    $key = array_search(date('M Y', mktime(0, 0, 0, $item['bulan'], 1, $item['tahun'])), $months);
    if ($key !== false) {
        $simpanan_data[$key] = intval($item['total']);
    }
}

foreach ($d_chart_pinjaman as $item) {
    $key = array_search(date('M Y', mktime(0, 0, 0, $item['bulan'], 1, $item['tahun'])), $months);
    if ($key !== false) {
        $pinjaman_data[$key] = intval($item['total']);
    }
}

// Fill missing data with 0
for ($i = 0; $i < 12; $i++) {
    if (!isset($simpanan_data[$i])) {
        $simpanan_data[$i] = 0;
    }
    if (!isset($pinjaman_data[$i])) {
        $pinjaman_data[$i] = 0;
    }
}

ksort($simpanan_data);
ksort($pinjaman_data);

// Include header
require_once '../partials/header.php';
require_once '../partials/sidebar.php';
?>

<!-- Main Content -->
<div class="main-content">
    <!-- Page Title -->
    <div style="margin-bottom: 30px;">
        <h3 style="color: #2c3e50; margin: 0;">
            <i class="fas fa-tachometer-alt" style="color: #2ecc71;"></i> Dashboard
        </h3>
        <small style="color: #7f8c8d;">Selamat datang di MASATA PINJAMIN</small>
    </div>

    <div class="card mb-4" style="background: linear-gradient(135deg, rgba(14,165,233,0.92) 0%, rgba(16,185,129,0.92) 100%); color: white; border: none; overflow: hidden;">
        <div class="card-body p-4 p-lg-5" style="position: relative;">
            <div style="position: absolute; inset: auto -60px -70px auto; width: 220px; height: 220px; border-radius: 50%; background: rgba(255,255,255,0.1);"></div>
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <span class="badge" style="background: rgba(255,255,255,0.18); color: white; margin-bottom: 14px;">Dashboard Ringkas</span>
                    <h2 style="font-weight: 900; letter-spacing: -0.03em; margin-bottom: 10px;">Halo, <?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Admin'; ?></h2>
                    <p style="max-width: 56ch; margin-bottom: 18px; color: rgba(255,255,255,0.88);">
                        Pantau aktivitas koperasi dari satu layar. Angka penting, grafik, dan status transaksi diperbarui untuk membantu pengambilan keputusan lebih cepat.
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="<?php echo BASE_URL; ?>/admin/anggota/index.php" class="btn btn-light text-dark">
                            <i class="fas fa-users"></i> Anggota
                        </a>
                        <a href="<?php echo BASE_URL; ?>/admin/simpanan/index.php" class="btn btn-light text-dark">
                            <i class="fas fa-coins"></i> Simpanan
                        </a>
                        <a href="<?php echo BASE_URL; ?>/admin/pinjaman/index.php" class="btn btn-light text-dark">
                            <i class="fas fa-money-bill-wave"></i> Pinjaman
                        </a>
                        <a href="<?php echo BASE_URL; ?>/admin/laporan/pinjaman.php" class="btn btn-outline-light">
                            <i class="fas fa-print"></i> Laporan
                        </a>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="card h-100" style="background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.16); color: white;">
                                <div class="card-body">
                                    <small style="opacity: 0.85;">Hari ini</small>
                                    <div style="font-size: 1.3rem; font-weight: 800; margin-top: 8px;"><?php echo date('d M Y'); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card h-100" style="background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.16); color: white;">
                                <div class="card-body">
                                    <small style="opacity: 0.85;">Role</small>
                                    <div style="font-size: 1.3rem; font-weight: 800; margin-top: 8px; text-transform: capitalize;"><?php echo isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'Admin'; ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <!-- Statistik Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card card-animate">
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <h3><i class="fas fa-users"></i> Total Anggota</h3>
                        <div class="stat-value"><?php echo $total_anggota; ?></div>
                        <small>Anggota terdaftar</small>
                    </div>
                    <i class="fas fa-users" style="font-size: 50px; opacity: 0.2;"></i>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card stat-card-blue card-animate">
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <h3><i class="fas fa-coins"></i> Total Simpanan</h3>
                        <div class="stat-value"><?php echo format_rupiah($total_simpanan); ?></div>
                        <small>Dari semua anggota</small>
                    </div>
                    <i class="fas fa-coins" style="font-size: 50px; opacity: 0.2;"></i>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card stat-card-orange card-animate">
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <h3><i class="fas fa-money-bill-wave"></i> Total Pinjaman</h3>
                        <div class="stat-value"><?php echo format_rupiah($total_pinjaman); ?></div>
                        <small>Pinjaman aktif & lunas</small>
                    </div>
                    <i class="fas fa-money-bill-wave" style="font-size: 50px; opacity: 0.2;"></i>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card stat-card-red card-animate">
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <h3><i class="fas fa-credit-card"></i> Total Angsuran</h3>
                        <div class="stat-value"><?php echo format_rupiah($total_angsuran); ?></div>
                        <small>Pembayaran angsuran</small>
                    </div>
                    <i class="fas fa-credit-card" style="font-size: 50px; opacity: 0.2;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title" style="color: #2c3e50;">
                        <i class="fas fa-chart-line" style="color: #2ecc71;"></i> Grafik Simpanan 12 Bulan Terakhir
                    </h5>
                    <div class="chart-container">
                        <canvas id="chartSimpanan"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title" style="color: #2c3e50;">
                        <i class="fas fa-chart-bar" style="color: #3498db;"></i> Grafik Pinjaman 12 Bulan Terakhir
                    </h5>
                    <div class="chart-container">
                        <canvas id="chartPinjaman"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tables -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header" style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
                    <h5 class="mb-0" style="color: #2c3e50;">
                        <i class="fas fa-user-check" style="color: #2ecc71;"></i> Anggota Terbaru
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr style="background: #f8f9fa;">
                                    <th>Nama</th>
                                    <th>No. Telp</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($d_anggota_terbaru as $anggota): ?>
                                    <tr>
                                        <td><?php echo $anggota['nama']; ?></td>
                                        <td><?php echo $anggota['no_tlp']; ?></td>
                                        <td>
                                            <a href="<?php echo BASE_URL; ?>/admin/anggota/detail.php?id=<?php echo $anggota['id_anggota']; ?>" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header" style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
                    <h5 class="mb-0" style="color: #2c3e50;">
                        <i class="fas fa-hourglass-half" style="color: #f39c12;"></i> Pinjaman Menunggu Persetujuan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr style="background: #f8f9fa;">
                                    <th>Anggota</th>
                                    <th>Jumlah</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($d_pinjaman_pending as $pinjaman): ?>
                                    <tr>
                                        <td><?php echo $pinjaman['nama']; ?></td>
                                        <td><?php echo format_rupiah($pinjaman['besar_pinjaman']); ?></td>
                                        <td>
                                            <a href="<?php echo BASE_URL; ?>/admin/pinjaman/detail.php?id=<?php echo $pinjaman['id_pinjaman']; ?>" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart Scripts -->
<script>
    // Chart Simpanan
    const ctxSimpanan = document.getElementById('chartSimpanan').getContext('2d');
    new Chart(ctxSimpanan, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($months); ?>,
            datasets: [{
                label: 'Simpanan (Rp)',
                data: <?php echo json_encode(array_values($simpanan_data)); ?>,
                borderColor: '#2ecc71',
                backgroundColor: 'rgba(46, 204, 113, 0.1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointBackgroundColor: '#2ecc71',
                pointBorderColor: '#27ae60'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        usePointStyle: true,
                        padding: 15
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
    
    // Chart Pinjaman
    const ctxPinjaman = document.getElementById('chartPinjaman').getContext('2d');
    new Chart(ctxPinjaman, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($months); ?>,
            datasets: [{
                label: 'Pinjaman (Rp)',
                data: <?php echo json_encode(array_values($pinjaman_data)); ?>,
                backgroundColor: [
                    'rgba(52, 152, 219, 0.8)',
                    'rgba(41, 128, 185, 0.8)',
                    'rgba(52, 152, 219, 0.8)',
                    'rgba(41, 128, 185, 0.8)',
                    'rgba(52, 152, 219, 0.8)',
                    'rgba(41, 128, 185, 0.8)',
                    'rgba(52, 152, 219, 0.8)',
                    'rgba(41, 128, 185, 0.8)',
                    'rgba(52, 152, 219, 0.8)',
                    'rgba(41, 128, 185, 0.8)',
                    'rgba(52, 152, 219, 0.8)',
                    'rgba(41, 128, 185, 0.8)'
                ],
                borderColor: '#3498db',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        usePointStyle: true,
                        padding: 15
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>

<?php
// Include footer
require_once '../partials/footer.php';
?>
