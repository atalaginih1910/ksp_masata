<?php
/**
 * Daftar Simpanan
 * MASATA PINJAMIN
 */

require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../config/helper.php';

require_login();

$page_title = 'Simpanan';

// Get filter
$filter_anggota = isset($_GET['filter_anggota']) ? intval($_GET['filter_anggota']) : 0;
$filter_bulan = isset($_GET['filter_bulan']) ? $_GET['filter_bulan'] : date('Y-m');
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 15;
$offset = ($page - 1) * $limit;

// Build query
$where = "1=1";
if ($filter_anggota > 0) {
    $where .= " AND s.id_anggota = $filter_anggota";
}
if ($filter_bulan) {
    $where .= " AND DATE_FORMAT(s.tgl_simpanan, '%Y-%m') = '$filter_bulan'";
}

// Get simpanan
$sql = "SELECT s.*, a.nama FROM simpanan s 
        LEFT JOIN anggota a ON s.id_anggota = a.id_anggota 
        WHERE $where 
        ORDER BY s.tgl_simpanan DESC 
        LIMIT $offset, $limit";
$result = query($sql);
$d_simpanan = fetch_all($result);

// Get total
$q_total = "SELECT COUNT(*) as total FROM simpanan s WHERE $where";
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
                <i class="fas fa-coins" style="color: #2ecc71;"></i> Simpanan
            </h3>
            <small style="color: #7f8c8d;">Kelola transaksi simpanan anggota</small>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fas fa-plus"></i> Tambah Simpanan
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
                <div class="col-md-4">
                    <label class="form-label">Filter Anggota</label>
                    <select name="filter_anggota" class="form-control member-select">
                        <option value="">-- Semua Anggota --</option>
                        <?php foreach ($d_anggota as $anggota): ?>
                            <option value="<?php echo $anggota['id_anggota']; ?>" <?php echo $filter_anggota === $anggota['id_anggota'] ? 'selected' : ''; ?>>
                                <?php echo $anggota['nama']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-4">
                    <label class="form-label">Filter Bulan</label>
                    <input type="month" name="filter_bulan" class="form-control" value="<?php echo $filter_bulan; ?>">
                </div>
                
                <div class="col-md-4">
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
                        <th>Nama Simpanan</th>
                        <th>Tanggal</th>
                        <th style="text-align: right;">Jumlah</th>
                        <th style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($d_simpanan) > 0): ?>
                        <?php $no = $offset + 1; foreach ($d_simpanan as $simpanan): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $simpanan['nama']; ?></td>
                                <td><?php echo $simpanan['nm_simpanan']; ?></td>
                                <td><?php echo format_tanggal($simpanan['tgl_simpanan']); ?></td>
                                <td style="text-align: right; font-weight: bold;"><?php echo format_rupiah($simpanan['besar_simpanan']); ?></td>
                                <td>
                                    <a href="delete.php?id=<?php echo $simpanan['id_simpanan']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin hapus?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-inbox" style="font-size: 30px; opacity: 0.3;"></i>
                                <p style="margin-top: 10px;">Tidak ada data simpanan</p>
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
                    <a class="page-link" href="?page=<?php echo $page - 1; ?>&filter_anggota=<?php echo $filter_anggota; ?>&filter_bulan=<?php echo $filter_bulan; ?>">
                        <i class="fas fa-chevron-left"></i> Sebelumnya
                    </a>
                </li>
                
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&filter_anggota=<?php echo $filter_anggota; ?>&filter_bulan=<?php echo $filter_bulan; ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>
                
                <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $page + 1; ?>&filter_anggota=<?php echo $filter_anggota; ?>&filter_bulan=<?php echo $filter_bulan; ?>">
                        Selanjutnya <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<!-- Modal Add Simpanan -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Simpanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="process.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="id_anggota" class="form-label">Anggota</label>
                        <select name="id_anggota" id="id_anggota" class="form-control member-select" required>
                            <option value="">-- Pilih Anggota --</option>
                            <?php foreach ($d_anggota as $anggota): ?>
                                <option value="<?php echo $anggota['id_anggota']; ?>">
                                    <?php echo $anggota['nama']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="nm_simpanan" class="form-label">Nama Simpanan</label>
                        <input type="text" name="nm_simpanan" id="nm_simpanan" class="form-control" placeholder="Contoh: Simpanan Pokok" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="tgl_simpanan" class="form-label">Tanggal Simpanan</label>
                        <input type="date" name="tgl_simpanan" id="tgl_simpanan" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="besar_simpanan" class="form-label">Jumlah Simpanan</label>
                        <input type="number" name="besar_simpanan" id="besar_simpanan" class="form-control" min="1" required>
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
