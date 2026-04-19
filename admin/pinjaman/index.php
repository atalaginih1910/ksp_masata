<?php
/**
 * Daftar Pinjaman
 * MASATA PINJAMIN
 */

require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../config/helper.php';

require_login();

$page_title = 'Pinjaman';

// Get filter
$filter_status = isset($_GET['filter_status']) ? $_GET['filter_status'] : '';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 15;
$offset = ($page - 1) * $limit;

// Build query
$where = "1=1";
if ($filter_status === 'pending') {
    $where .= " AND p.tgl_acc_peminjam IS NULL";
} elseif ($filter_status === 'disetujui') {
    $where .= " AND p.tgl_acc_peminjam IS NOT NULL AND p.tgl_pinjaman IS NULL";
} elseif ($filter_status === 'berjalan') {
    $where .= " AND p.tgl_pinjaman IS NOT NULL AND p.tgl_pelunasan IS NULL";
} elseif ($filter_status === 'lunas') {
    $where .= " AND p.tgl_pelunasan IS NOT NULL";
}

// Get pinjaman
$sql = "SELECT p.*, a.nama FROM pinjaman p 
        LEFT JOIN anggota a ON p.id_anggota = a.id_anggota 
        WHERE $where 
        ORDER BY p.tgl_pengajuan_pinjaman DESC 
        LIMIT $offset, $limit";
$result = query($sql);
$d_pinjaman = fetch_all($result);

// Get total
$q_total = "SELECT COUNT(*) as total FROM pinjaman p WHERE $where";
$r_total = query($q_total);
$d_total = fetch_single($r_total);
$total_data = $d_total['total'];
$total_pages = ceil($total_data / $limit);

// Get anggota untuk dropdown
$q_anggota = "SELECT id_anggota, nama FROM anggota ORDER BY nama ASC";
$r_anggota = query($q_anggota);
$d_anggota = fetch_all($r_anggota);

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
                <i class="fas fa-money-bill-wave" style="color: #2ecc71;"></i> Pinjaman
            </h3>
            <small style="color: #7f8c8d;">Kelola transaksi pinjaman anggota</small>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fas fa-plus"></i> Tambah Pinjaman
        </button>
    </div>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Filter Status</label>
                    <select name="filter_status" class="form-control">
                        <option value="">-- Semua Status --</option>
                        <option value="pending" <?php echo $filter_status === 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="disetujui" <?php echo $filter_status === 'disetujui' ? 'selected' : ''; ?>>Disetujui</option>
                        <option value="berjalan" <?php echo $filter_status === 'berjalan' ? 'selected' : ''; ?>>Berjalan</option>
                        <option value="lunas" <?php echo $filter_status === 'lunas' ? 'selected' : ''; ?>>Lunas</option>
                    </select>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Data Table -->
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background: #f8f9fa;">
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th>Anggota</th>
                        <th>Nama Pinjaman</th>
                        <th>Tgl. Pengajuan</th>
                        <th style="text-align: right;">Jumlah</th>
                        <th>Status</th>
                        <th style="width: 20%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($d_pinjaman) > 0): ?>
                        <?php $no = $offset + 1; foreach ($d_pinjaman as $pinjaman): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $pinjaman['nama']; ?></td>
                                <td><?php echo $pinjaman['nama_pinjaman']; ?></td>
                                <td><?php echo format_tanggal($pinjaman['tgl_pengajuan_pinjaman']); ?></td>
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
                                <td>
                                    <a href="detail.php?id=<?php echo $pinjaman['id_pinjaman']; ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                    <?php if (!$pinjaman['tgl_acc_peminjam']): ?>
                                        <a href="approve.php?id=<?php echo $pinjaman['id_pinjaman']; ?>" class="btn btn-sm btn-success" onclick="return confirm('Setujui pinjaman ini?')">
                                            <i class="fas fa-check"></i> Setujui
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-inbox" style="font-size: 30px; opacity: 0.3;"></i>
                                <p style="margin-top: 10px;">Tidak ada data pinjaman</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
        <nav aria-label="Page navigation" style="margin-top: 20px;">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $page - 1; ?>&filter_status=<?php echo $filter_status; ?>">
                        <i class="fas fa-chevron-left"></i> Sebelumnya
                    </a>
                </li>
                
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&filter_status=<?php echo $filter_status; ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>
                
                <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $page + 1; ?>&filter_status=<?php echo $filter_status; ?>">
                        Selanjutnya <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<!-- Modal Add Pinjaman -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Pinjaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="process.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="id_anggota" class="form-label">Anggota</label>
                        <select name="id_anggota" id="id_anggota" class="form-control" required>
                            <option value="">-- Pilih Anggota --</option>
                            <?php foreach ($d_anggota as $anggota): ?>
                                <option value="<?php echo $anggota['id_anggota']; ?>">
                                    <?php echo $anggota['nama']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="nama_pinjaman" class="form-label">Nama Pinjaman</label>
                        <input type="text" name="nama_pinjaman" id="nama_pinjaman" class="form-control" placeholder="Contoh: Pinjaman Usaha" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="besar_pinjaman" class="form-label">Jumlah Pinjaman</label>
                        <input type="number" name="besar_pinjaman" id="besar_pinjaman" class="form-control" min="1" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="tgl_pengajuan_pinjaman" class="form-label">Tanggal Pengajuan</label>
                        <input type="date" name="tgl_pengajuan_pinjaman" id="tgl_pengajuan_pinjaman" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="ket" class="form-label">Keterangan</label>
                        <textarea name="ket" id="ket" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
// Include footer
require_once '../../partials/footer.php';
?>
