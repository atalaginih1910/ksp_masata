<?php
/**
 * Detail Anggota
 * MASATA PINJAMIN
 */

require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../config/helper.php';

require_login();

$page_title = 'Detail Anggota';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);

// Get anggota detail
$sql = "SELECT * FROM anggota WHERE id_anggota = $id";
$result = query($sql);
$anggota = fetch_single($result);

if (!$anggota) {
    $_SESSION['error'] = "Data anggota tidak ditemukan!";
    header("Location: index.php");
    exit;
}

// Get simpanan
$sql_simpanan = "SELECT * FROM simpanan WHERE id_anggota = $id ORDER BY tgl_simpanan DESC";
$r_simpanan = query($sql_simpanan);
$d_simpanan = fetch_all($r_simpanan);
$total_simpanan = 0;
foreach ($d_simpanan as $s) {
    $total_simpanan += $s['besar_simpanan'];
}

// Get pinjaman
$sql_pinjaman = "SELECT * FROM pinjaman WHERE id_anggota = $id ORDER BY tgl_pinjaman DESC";
$r_pinjaman = query($sql_pinjaman);
$d_pinjaman = fetch_all($r_pinjaman);

// Include header
require_once '../../partials/header.php';
require_once '../../partials/sidebar.php';
?>

<!-- Main Content -->
<div class="main-content">
    <!-- Page Title -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 style="color: #2c3e50; margin: 0;">
                <i class="fas fa-user" style="color: #2ecc71;"></i> Detail Anggota
            </h3>
            <small style="color: #7f8c8d;">Informasi lengkap anggota</small>
        </div>
        <div>
            <a href="edit.php?id=<?php echo $anggota['id_anggota']; ?>" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="index.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    
    <!-- Detail Anggota -->
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body text-center">
                    <div style="width: 80px; height: 80px; margin: 0 auto 20px; background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 40px; color: white;">
                        <i class="fas fa-user"></i>
                    </div>
                    <h4><?php echo $anggota['nama']; ?></h4>
                    <p style="color: #7f8c8d; margin: 0;"><?php echo $anggota['status']; ?></p>
                    
                    <hr>
                    
                    <div style="text-align: left;">
                        <div style="margin-bottom: 15px;">
                            <small style="color: #7f8c8d;">ID Anggota</small>
                            <p style="font-weight: bold; margin: 0;"><?php echo $anggota['id_anggota']; ?></p>
                        </div>
                        
                        <div style="margin-bottom: 15px;">
                            <small style="color: #7f8c8d;">Jenis Kelamin</small>
                            <p style="font-weight: bold; margin: 0;"><?php echo get_jenis_kelamin($anggota['j_kel']); ?></p>
                        </div>
                        
                        <div style="margin-bottom: 15px;">
                            <small style="color: #7f8c8d;">Tempat, Tanggal Lahir</small>
                            <p style="font-weight: bold; margin: 0;"><?php echo $anggota['tmp_lhr']; ?>, <?php echo format_tanggal($anggota['tgl_lhr']); ?></p>
                        </div>
                        
                        <div style="margin-bottom: 15px;">
                            <small style="color: #7f8c8d;">No. Telepon</small>
                            <p style="font-weight: bold; margin: 0;"><?php echo $anggota['no_tlp']; ?></p>
                        </div>
                        
                        <div>
                            <small style="color: #7f8c8d;">Alamat</small>
                            <p style="font-weight: bold; margin: 0; font-size: 13px;"><?php echo $anggota['alamat']; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <!-- Statistik -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="stat-card">
                        <h3><i class="fas fa-coins"></i> Total Simpanan</h3>
                        <div class="stat-value"><?php echo format_rupiah($total_simpanan); ?></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="stat-card stat-card-orange">
                        <h3><i class="fas fa-money-bill-wave"></i> Total Pinjaman</h3>
                        <div class="stat-value"><?php echo count($d_pinjaman); ?></div>
                    </div>
                </div>
            </div>
            
            <!-- Riwayat Simpanan -->
            <div class="card mb-4">
                <div class="card-header" style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
                    <h5 class="mb-0" style="color: #2c3e50;">
                        <i class="fas fa-history" style="color: #2ecc71;"></i> Riwayat Simpanan
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead style="background: #f8f9fa;">
                            <tr>
                                <th>Tanggal</th>
                                <th>Nama Simpanan</th>
                                <th style="text-align: right;">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($d_simpanan) > 0): ?>
                                <?php foreach ($d_simpanan as $simpanan): ?>
                                    <tr>
                                        <td><?php echo format_tanggal($simpanan['tgl_simpanan']); ?></td>
                                        <td><?php echo $simpanan['nm_simpanan']; ?></td>
                                        <td style="text-align: right; font-weight: bold;"><?php echo format_rupiah($simpanan['besar_simpanan']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">Tidak ada riwayat simpanan</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Riwayat Pinjaman -->
            <div class="card">
                <div class="card-header" style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
                    <h5 class="mb-0" style="color: #2c3e50;">
                        <i class="fas fa-history" style="color: #f39c12;"></i> Riwayat Pinjaman
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead style="background: #f8f9fa;">
                            <tr>
                                <th>Tanggal Pengajuan</th>
                                <th>Nama Pinjaman</th>
                                <th style="text-align: right;">Jumlah</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($d_pinjaman) > 0): ?>
                                <?php foreach ($d_pinjaman as $pinjaman): ?>
                                    <tr>
                                        <td><?php echo format_tanggal($pinjaman['tgl_pengajuan_pinjaman']); ?></td>
                                        <td><?php echo $pinjaman['nama_pinjaman']; ?></td>
                                        <td style="text-align: right; font-weight: bold;"><?php echo format_rupiah($pinjaman['besar_pinjaman']); ?></td>
                                        <td>
                                            <?php 
                                            if ($pinjaman['tgl_pelunasan']) {
                                                echo '<span class="badge bg-success">Lunas</span>';
                                            } elseif ($pinjaman['tgl_pinjaman']) {
                                                echo '<span class="badge bg-info">Berjalan</span>';
                                            } elseif ($pinjaman['tgl_acc_peminjam']) {
                                                echo '<span class="badge bg-primary">Disetujui</span>';
                                            } else {
                                                echo '<span class="badge bg-warning">Pending</span>';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Tidak ada riwayat pinjaman</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
require_once '../../partials/footer.php';
?>
