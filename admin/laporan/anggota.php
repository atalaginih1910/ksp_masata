<?php
/**
 * Laporan Anggota
 * MASATA PINJAMIN
 */

require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../config/helper.php';

require_login();

$page_title = 'Laporan Anggota';

// Get filter
$filter_status = isset($_GET['filter_status']) ? $_GET['filter_status'] : '';
$tgl_dari = isset($_GET['tgl_dari']) ? $_GET['tgl_dari'] : date('Y-m-d', strtotime('-1 month'));
$tgl_sampai = isset($_GET['tgl_sampai']) ? $_GET['tgl_sampai'] : date('Y-m-d');

// Build query
$where = "1=1";
if ($filter_status) {
    $where .= " AND status = '$filter_status'";
}

// Get anggota
$sql = "SELECT * FROM anggota WHERE $where ORDER BY id_anggota DESC";
$result = query($sql);
$d_anggota = fetch_all($result);

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
                <i class="fas fa-file-pdf" style="color: #e74c3c;"></i> Laporan Anggota
            </h3>
            <small style="color: #7f8c8d;">Data lengkap anggota koperasi</small>
        </div>
        <button onclick="window.print()" class="btn btn-danger">
            <i class="fas fa-print"></i> Print
        </button>
    </div>
    
    <!-- Filter -->
    <div class="card mb-4 no-print">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Filter Status</label>
                    <select name="filter_status" class="form-control">
                        <option value="">-- Semua Status --</option>
                        <option value="Aktif" <?php echo $filter_status === 'Aktif' ? 'selected' : ''; ?>>Aktif</option>
                        <option value="Non Aktif" <?php echo $filter_status === 'Non Aktif' ? 'selected' : ''; ?>>Non Aktif</option>
                    </select>
                </div>
                
                <div class="col-md-8">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="anggota.php" class="btn btn-secondary">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Report -->
    <div class="card">
        <div class="card-body">
            <h5 style="text-align: center; margin-bottom: 30px; font-weight: bold;">
                LAPORAN DATA ANGGOTA<br>
                <small>Koperasi Simpan Pinjam MASATA</small>
            </h5>
            
            <div class="table-responsive">
                <table class="table table-bordered table-striped" style="font-size: 12px;">
                    <thead style="background: #f8f9fa;">
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th>Nama</th>
                            <th>Tempat Lahir</th>
                            <th>Alamat</th>
                            <th>No. Telp</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($d_anggota) > 0): ?>
                            <?php $no = 1; foreach ($d_anggota as $anggota): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $anggota['nama']; ?></td>
                                    <td><?php echo $anggota['tmp_lhr']; ?></td>
                                    <td><?php echo substr($anggota['alamat'], 0, 40); ?></td>
                                    <td><?php echo $anggota['no_tlp']; ?></td>
                                    <td><?php echo $anggota['status']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Tidak ada data</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <hr style="margin-top: 30px;">
            
            <div class="row" style="margin-top: 30px;">
                <div class="col-md-3">&nbsp;</div>
                <div class="col-md-3" style="text-align: center;">
                    <p>Disetujui,</p>
                    <p style="margin-top: 40px; border-top: 1px solid #000;">
                        <small>Kepala Koperasi</small>
                    </p>
                </div>
                <div class="col-md-3" style="text-align: center;">
                    <p>Dibuat,</p>
                    <p style="margin-top: 40px; border-top: 1px solid #000;">
                        <small><?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Admin'; ?></small>
                    </p>
                </div>
                <div class="col-md-3">&nbsp;</div>
            </div>
        </div>
    </div>
</div>

<style media="print">
    .card {
        border: none;
        box-shadow: none;
    }

    .no-print {
        display: none !important;
    }
</style>

<?php
// Include footer
require_once '../../partials/footer.php';
?>
