<?php
/**
 * Daftar Angsuran
 * MASATA PINJAMIN
 */

require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../config/helper.php';

require_login();

$page_title = 'Angsuran';

// Get filter
$filter_anggota = isset($_GET['filter_anggota']) ? intval($_GET['filter_anggota']) : 0;
$filter_status = isset($_GET['filter_status']) ? $_GET['filter_status'] : '';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 15;
$offset = ($page - 1) * $limit;

// Build query
$where = "1=1";
if ($filter_anggota > 0) {
    $where .= " AND a.id_anggota = $filter_anggota";
}
if ($filter_status === 'lunas') {
    $where .= " AND ang.tgl_pembayaran IS NOT NULL";
} elseif ($filter_status === 'belum') {
    $where .= " AND ang.tgl_pembayaran IS NULL";
}

// Get angsuran
$sql = "SELECT ang.*, a.nama, p.nama_pinjaman, p.besar_pinjaman FROM angsuran ang 
        LEFT JOIN anggota a ON ang.id_anggota = a.id_anggota 
        LEFT JOIN pinjaman p ON ang.id_kategori = p.id_pinjaman
        WHERE $where 
        ORDER BY ang.id_angsuran DESC 
        LIMIT $offset, $limit";
$result = query($sql);
$d_angsuran = fetch_all($result);

// Get total
$q_total = "SELECT COUNT(*) as total FROM angsuran ang LEFT JOIN anggota a ON ang.id_anggota = a.id_anggota WHERE $where";
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
                <i class="fas fa-credit-card" style="color: #2ecc71;"></i> Angsuran
            </h3>
            <small style="color: #7f8c8d;">Kelola pembayaran angsuran pinjaman</small>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fas fa-plus"></i> Tambah Angsuran
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
                    <select name="filter_anggota" class="form-control">
                        <option value="">-- Semua Anggota --</option>
                        <?php foreach ($d_anggota as $anggota): ?>
                            <option value="<?php echo $anggota['id_anggota']; ?>" <?php echo $filter_anggota === $anggota['id_anggota'] ? 'selected' : ''; ?>>
                                <?php echo $anggota['nama']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-4">
                    <label class="form-label">Filter Status</label>
                    <select name="filter_status" class="form-control">
                        <option value="">-- Semua Status --</option>
                        <option value="lunas" <?php echo $filter_status === 'lunas' ? 'selected' : ''; ?>>Lunas</option>
                        <option value="belum" <?php echo $filter_status === 'belum' ? 'selected' : ''; ?>>Belum Bayar</option>
                    </select>
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
                        <th>Angsuran Ke</th>
                        <th>Tgl. Pembayaran</th>
                        <th style="text-align: right;">Jumlah</th>
                        <th>Status</th>
                        <th style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($d_angsuran) > 0): ?>
                        <?php $no = $offset + 1; foreach ($d_angsuran as $angsuran): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $angsuran['nama']; ?></td>
                                <td><?php echo $angsuran['angsuran_ke']; ?></td>
                                <td><?php echo $angsuran['tgl_pembayaran'] ? format_tanggal($angsuran['tgl_pembayaran']) : '-'; ?></td>
                                <td style="text-align: right; font-weight: bold;"><?php echo format_rupiah($angsuran['besar_angsuran']); ?></td>
                                <td>
                                    <?php 
                                    echo $angsuran['tgl_pembayaran'] 
                                        ? '<span class="badge bg-success">Lunas</span>' 
                                        : '<span class="badge bg-warning">Belum Bayar</span>';
                                    ?>
                                </td>
                                <td>
                                    <?php if (!$angsuran['tgl_pembayaran']): ?>
                                        <a href="bayar.php?id=<?php echo $angsuran['id_angsuran']; ?>" class="btn btn-sm btn-success">
                                            <i class="fas fa-money-bill"></i> Bayar
                                        </a>
                                    <?php endif; ?>
                                    <a href="delete.php?id=<?php echo $angsuran['id_angsuran']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin hapus?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-inbox" style="font-size: 30px; opacity: 0.3;"></i>
                                <p style="margin-top: 10px;">Tidak ada data angsuran</p>
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
                    <a class="page-link" href="?page=<?php echo $page - 1; ?>&filter_anggota=<?php echo $filter_anggota; ?>&filter_status=<?php echo $filter_status; ?>">
                        <i class="fas fa-chevron-left"></i> Sebelumnya
                    </a>
                </li>
                
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&filter_anggota=<?php echo $filter_anggota; ?>&filter_status=<?php echo $filter_status; ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>
                
                <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $page + 1; ?>&filter_anggota=<?php echo $filter_anggota; ?>&filter_status=<?php echo $filter_status; ?>">
                        Selanjutnya <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<!-- Modal Add Angsuran -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Angsuran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="process.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="id_kategori" class="form-label">Pinjaman</label>
                        <select name="id_kategori" id="id_kategori" class="form-control" required>
                            <option value="">-- Pilih Pinjaman --</option>
                            <?php 
                            // Get daftar pinjaman yang aktif
                            $q_pin = "SELECT id_pinjaman, nama_pinjaman, a.nama FROM pinjaman p 
                                     LEFT JOIN anggota a ON p.id_anggota = a.id_anggota 
                                     WHERE p.tgl_acc_peminjam IS NOT NULL AND p.tgl_pelunasan IS NULL 
                                     ORDER BY a.nama ASC";
                            $r_pin = query($q_pin);
                            $d_pin = fetch_all($r_pin);
                            foreach ($d_pin as $pin): 
                            ?>
                                <option value="<?php echo $pin['id_pinjaman']; ?>">
                                    <?php echo $pin['nama'] . ' - ' . $pin['nama_pinjaman']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
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
                        <label for="angsuran_ke" class="form-label">Angsuran Ke</label>
                        <input type="number" name="angsuran_ke" id="angsuran_ke" class="form-control" min="1" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="besar_angsuran" class="form-label">Jumlah Angsuran</label>
                        <input type="number" name="besar_angsuran" id="besar_angsuran" class="form-control" min="1" required>
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
