<?php
/**
 * Laporan Angsuran
 * MASATA PINJAMIN
 */

require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../config/helper.php';

require_login();

$page_title = 'Laporan Angsuran';

// Get filter
$tgl_dari = isset($_GET['tgl_dari']) ? $_GET['tgl_dari'] : date('Y-m-d', strtotime('-3 month'));
$tgl_sampai = isset($_GET['tgl_sampai']) ? $_GET['tgl_sampai'] : date('Y-m-d');

// Build query
$where = "tgl_pembayaran IS NOT NULL AND tgl_pembayaran BETWEEN '$tgl_dari' AND '$tgl_sampai'";

// Get angsuran
$sql = "SELECT ang.*, a.nama FROM angsuran ang 
        LEFT JOIN anggota a ON ang.id_anggota = a.id_anggota 
        WHERE $where 
        ORDER BY ang.tgl_pembayaran ASC";
$result = query($sql);
$d_angsuran = fetch_all($result);

// Get total angsuran
$q_total = "SELECT SUM(besar_angsuran) as total FROM angsuran WHERE $where";
$r_total = query($q_total);
$d_total = fetch_single($r_total);
$total_angsuran = $d_total['total'] ?? 0;

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
                <i class="fas fa-file-pdf" style="color: #e74c3c;"></i> Laporan Angsuran
            </h3>
            <small style="color: #7f8c8d;">Data pembayaran angsuran pinjaman periode tertentu</small>
        </div>
        <button onclick="window.print()" class="btn btn-danger">
            <i class="fas fa-print"></i> Print
        </button>
    </div>
    
    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-5">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="tgl_dari" class="form-control" value="<?php echo $tgl_dari; ?>">
                </div>
                
                <div class="col-md-5">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="tgl_sampai" class="form-control" value="<?php echo $tgl_sampai; ?>">
                </div>
                
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Report -->
    <div class="card">
        <div class="card-body">
            <h5 style="text-align: center; margin-bottom: 30px; font-weight: bold;">
                LAPORAN ANGSURAN<br>
                <small>Koperasi Simpan Pinjam MASATA</small><br>
                <small style="font-size: 11px;">Periode <?php echo format_tanggal($tgl_dari); ?> s/d <?php echo format_tanggal($tgl_sampai); ?></small>
            </h5>
            
            <div class="table-responsive">
                <table class="table table-bordered table-striped" style="font-size: 12px;">
                    <thead style="background: #f8f9fa;">
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th>Anggota</th>
                            <th>Angsuran Ke</th>
                            <th>Tgl. Pembayaran</th>
                            <th style="text-align: right;">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($d_angsuran) > 0): ?>
                            <?php $no = 1; foreach ($d_angsuran as $angsuran): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $angsuran['nama']; ?></td>
                                    <td><?php echo $angsuran['angsuran_ke']; ?></td>
                                    <td><?php echo format_tanggal($angsuran['tgl_pembayaran']); ?></td>
                                    <td style="text-align: right;"><?php echo format_rupiah($angsuran['besar_angsuran']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr style="background: #f8f9fa; font-weight: bold;">
                                <td colspan="4" style="text-align: right;">TOTAL</td>
                                <td style="text-align: right;"><?php echo format_rupiah($total_angsuran); ?></td>
                            </tr>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Tidak ada data</td>
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
