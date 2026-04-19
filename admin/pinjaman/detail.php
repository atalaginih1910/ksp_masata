<?php
/**
 * Detail Pinjaman
 * MASATA PINJAMIN
 */

require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../config/helper.php';

require_login();

$page_title = 'Detail Pinjaman';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);

// Get pinjaman detail
$sql = "SELECT p.*, a.nama, a.alamat, a.no_tlp FROM pinjaman p 
        LEFT JOIN anggota a ON p.id_anggota = a.id_anggota 
        WHERE p.id_pinjaman = $id";
$result = query($sql);
$pinjaman = fetch_single($result);

if (!$pinjaman) {
    $_SESSION['error'] = "Data pinjaman tidak ditemukan!";
    header("Location: index.php");
    exit;
}

// Get angsuran untuk pinjaman ini
$sql_angsuran = "SELECT * FROM angsuran WHERE id_angsuran = " . intval($pinjaman['id_angsuran']) . " ORDER BY angsuran_ke ASC";
$r_angsuran = query($sql_angsuran);
$d_angsuran = fetch_all($r_angsuran);

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
                <i class="fas fa-money-bill-wave" style="color: #2ecc71;"></i> Detail Pinjaman
            </h3>
            <small style="color: #7f8c8d;">Informasi lengkap pinjaman</small>
        </div>
        <a href="index.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header" style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
                    <h5 class="mb-0" style="color: #2c3e50;">Info Peminjam</h5>
                </div>
                <div class="card-body">
                    <div style="margin-bottom: 15px;">
                        <small style="color: #7f8c8d;">Nama</small>
                        <p style="font-weight: bold; margin: 0;"><?php echo $pinjaman['nama']; ?></p>
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <small style="color: #7f8c8d;">No. Telepon</small>
                        <p style="font-weight: bold; margin: 0;"><?php echo $pinjaman['no_tlp']; ?></p>
                    </div>
                    
                    <div>
                        <small style="color: #7f8c8d;">Alamat</small>
                        <p style="font-weight: bold; margin: 0; font-size: 13px;"><?php echo $pinjaman['alamat']; ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <!-- Detail Pinjaman -->
            <div class="card mb-4">
                <div class="card-header" style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
                    <h5 class="mb-0" style="color: #2c3e50;">Detail Pinjaman</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div style="margin-bottom: 15px;">
                                <small style="color: #7f8c8d;">Nama Pinjaman</small>
                                <p style="font-weight: bold; margin: 0;"><?php echo $pinjaman['nama_pinjaman']; ?></p>
                            </div>
                            
                            <div style="margin-bottom: 15px;">
                                <small style="color: #7f8c8d;">Jumlah Pinjaman</small>
                                <p style="font-weight: bold; margin: 0; font-size: 18px; color: #2ecc71;"><?php echo format_rupiah($pinjaman['besar_pinjaman']); ?></p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div style="margin-bottom: 15px;">
                                <small style="color: #7f8c8d;">Tgl. Pengajuan</small>
                                <p style="font-weight: bold; margin: 0;"><?php echo format_tanggal($pinjaman['tgl_pengajuan_pinjaman']); ?></p>
                            </div>
                            
                            <div style="margin-bottom: 15px;">
                                <small style="color: #7f8c8d;">Status</small>
                                <p style="margin: 0;">
                                    <?php 
                                    if ($pinjaman['tgl_pelunasan']) {
                                        echo '<span class="badge bg-success" style="font-size: 12px;">Lunas</span>';
                                    } elseif ($pinjaman['tgl_pinjaman']) {
                                        echo '<span class="badge bg-info" style="font-size: 12px;">Berjalan</span>';
                                    } elseif ($pinjaman['tgl_acc_peminjam']) {
                                        echo '<span class="badge bg-primary" style="font-size: 12px;">Disetujui</span>';
                                    } else {
                                        echo '<span class="badge bg-warning" style="font-size: 12px;">Pending</span>';
                                    }
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div style="margin-bottom: 10px;">
                                <small style="color: #7f8c8d;">Tgl. Persetujuan</small>
                                <p style="font-weight: bold; margin: 0;"><?php echo $pinjaman['tgl_acc_peminjam'] ? format_tanggal($pinjaman['tgl_acc_peminjam']) : '-'; ?></p>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div style="margin-bottom: 10px;">
                                <small style="color: #7f8c8d;">Tgl. Pencairan</small>
                                <p style="font-weight: bold; margin: 0;"><?php echo $pinjaman['tgl_pinjaman'] ? format_tanggal($pinjaman['tgl_pinjaman']) : '-'; ?></p>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div style="margin-bottom: 10px;">
                                <small style="color: #7f8c8d;">Tgl. Pelunasan</small>
                                <p style="font-weight: bold; margin: 0;"><?php echo $pinjaman['tgl_pelunasan'] ? format_tanggal($pinjaman['tgl_pelunasan']) : '-'; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Riwayat Angsuran -->
            <?php if (count($d_angsuran) > 0): ?>
                <div class="card">
                    <div class="card-header" style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
                        <h5 class="mb-0" style="color: #2c3e50;">Riwayat Angsuran</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead style="background: #f8f9fa;">
                                <tr>
                                    <th>Angsuran Ke</th>
                                    <th>Tgl. Pembayaran</th>
                                    <th style="text-align: right;">Jumlah</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($d_angsuran as $angsuran): ?>
                                    <tr>
                                        <td><?php echo $angsuran['angsuran_ke']; ?></td>
                                        <td><?php echo $angsuran['tgl_pembayaran'] ? format_tanggal($angsuran['tgl_pembayaran']) : '-'; ?></td>
                                        <td style="text-align: right; font-weight: bold;"><?php echo format_rupiah($angsuran['besar_angsuran']); ?></td>
                                        <td><?php echo $angsuran['ket']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
// Include footer
require_once '../../partials/footer.php';
?>
