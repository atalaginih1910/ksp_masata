<?php
/**
 * Laporan Pinjaman
 * MASATA PINJAMIN
 */

require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../config/helper.php';

require_login();

$page_title = 'Laporan Pinjaman';

// Get filter
$filter_status = isset($_GET['filter_status']) ? $_GET['filter_status'] : '';
$tgl_dari = isset($_GET['tgl_dari']) ? $_GET['tgl_dari'] : date('Y-m-d', strtotime('-3 month'));
$tgl_sampai = isset($_GET['tgl_sampai']) ? $_GET['tgl_sampai'] : date('Y-m-d');

// Build query
$where = "tgl_pengajuan_pinjaman BETWEEN '$tgl_dari' AND '$tgl_sampai'";
if ($filter_status === 'pending') {
    $where .= " AND tgl_acc_peminjam IS NULL";
} elseif ($filter_status === 'disetujui') {
    $where .= " AND tgl_acc_peminjam IS NOT NULL AND tgl_pinjaman IS NULL";
} elseif ($filter_status === 'lunas') {
    $where .= " AND tgl_pelunasan IS NOT NULL";
}

// Get pinjaman
$sql = "SELECT p.*, a.nama FROM pinjaman p 
        LEFT JOIN anggota a ON p.id_anggota = a.id_anggota 
        WHERE $where 
        ORDER BY p.tgl_pengajuan_pinjaman ASC";
$result = query($sql);
$d_pinjaman = fetch_all($result);

// Get total pinjaman
$q_total = "SELECT SUM(besar_pinjaman) as total FROM pinjaman WHERE $where";
$r_total = query($q_total);
$d_total = fetch_single($r_total);
$total_pinjaman = $d_total['total'] ?? 0;

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
                <i class="fas fa-file-pdf" style="color: #e74c3c;"></i> Laporan Pinjaman
            </h3>
            <small style="color: #7f8c8d;">Data pinjaman anggota periode tertentu</small>
        </div>
        <button onclick="window.print()" class="btn btn-danger">
            <i class="fas fa-print"></i> Print
        </button>
    </div>
    
    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="tgl_dari" class="form-control" value="<?php echo $tgl_dari; ?>">
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="tgl_sampai" class="form-control" value="<?php echo $tgl_sampai; ?>">
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Filter Status</label>
                    <select name="filter_status" class="form-control">
                        <option value="">-- Semua --</option>
                        <option value="pending" <?php echo $filter_status === 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="disetujui" <?php echo $filter_status === 'disetujui' ? 'selected' : ''; ?>>Disetujui</option>
                        <option value="lunas" <?php echo $filter_status === 'lunas' ? 'selected' : ''; ?>>Lunas</option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Report -->
    <div class="card">
        <div class="card-body">
            <h5 style="text-align: center; margin-bottom: 30px; font-weight: bold;">
                LAPORAN PINJAMAN<br>
                <small>Koperasi Simpan Pinjam MASATA</small><br>
                <small style="font-size: 11px;">Periode <?php echo format_tanggal($tgl_dari); ?> s/d <?php echo format_tanggal($tgl_sampai); ?></small>
            </h5>
            
            <div class="table-responsive">
                <table class="table table-bordered table-striped" style="font-size: 12px;">
                    <thead style="background: #f8f9fa;">
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th>Anggota</th>
                            <th>Nama Pinjaman</th>
                            <th style="text-align: right;">Jumlah</th>
                            <th>Tgl. Pengajuan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($d_pinjaman) > 0): ?>
                            <?php $no = 1; foreach ($d_pinjaman as $pinjaman): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $pinjaman['nama']; ?></td>
                                    <td><?php echo $pinjaman['nama_pinjaman']; ?></td>
                                    <td style="text-align: right;"><?php echo format_rupiah($pinjaman['besar_pinjaman']); ?></td>
                                    <td><?php echo format_tanggal($pinjaman['tgl_pengajuan_pinjaman']); ?></td>
                                    <td>
                                        <?php 
                                        if ($pinjaman['tgl_pelunasan']) {
                                            echo 'Lunas';
                                        } elseif ($pinjaman['tgl_pinjaman']) {
                                            echo 'Berjalan';
                                        } elseif ($pinjaman['tgl_acc_peminjam']) {
                                            echo 'Disetujui';
                                        } else {
                                            echo 'Pending';
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr style="background: #f8f9fa; font-weight: bold;">
                                <td colspan="3" style="text-align: right;">TOTAL</td>
                                <td style="text-align: right;"><?php echo format_rupiah($total_pinjaman); ?></td>
                                <td colspan="2"></td>
                            </tr>
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
</style>

<?php
// Include footer
require_once '../../partials/footer.php';
?>
