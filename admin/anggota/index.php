<?php
/**
 * Daftar Anggota
 * MASATA PINJAMIN
 */

require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../config/helper.php';

require_login();

$page_title = 'Data Anggota';

// Get filter & search
$search = isset($_GET['search']) ? escape($_GET['search']) : '';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Query anggota
$q_anggota = "SELECT * FROM anggota WHERE nama LIKE '%$search%' ORDER BY id_anggota DESC LIMIT $offset, $limit";
$r_anggota = query($q_anggota);
$d_anggota = fetch_all($r_anggota);

// Query total data
$q_total = "SELECT COUNT(*) as total FROM anggota WHERE nama LIKE '%$search%'";
$r_total = query($q_total);
$d_total = fetch_single($r_total);
$total_data = $d_total['total'];
$total_pages = ceil($total_data / $limit);

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
                <i class="fas fa-users" style="color: #2ecc71;"></i> Data Anggota
            </h3>
            <small style="color: #7f8c8d;">Kelola data anggota koperasi</small>
        </div>
        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formModal" onclick="showAddForm()">
            <i class="fas fa-plus"></i> Tambah Anggota
        </a>
    </div>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <!-- Search & Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="form-control" placeholder="Cari nama anggota..." value="<?php echo $search; ?>">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Cari
                </button>
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-redo"></i> Reset
                </a>
            </form>
        </div>
    </div>
    
    <!-- Data Table -->
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="dataTable">
                <thead style="background: #f8f9fa;">
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>No. Telp</th>
                        <th style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($d_anggota) > 0): ?>
                        <?php $no = $offset + 1; foreach ($d_anggota as $anggota): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $anggota['nama']; ?></td>
                                <td><?php echo substr($anggota['alamat'], 0, 50); ?>...</td>
                                <td><?php echo $anggota['no_tlp']; ?></td>
                                <td>
                                    <a href="detail.php?id=<?php echo $anggota['id_anggota']; ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                    <a href="edit.php?id=<?php echo $anggota['id_anggota']; ?>" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="delete.php?id=<?php echo $anggota['id_anggota']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin hapus?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="fas fa-inbox" style="font-size: 30px; opacity: 0.3;"></i>
                                <p style="margin-top: 10px;">Tidak ada data anggota</p>
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
                    <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo $search; ?>">
                        <i class="fas fa-chevron-left"></i> Sebelumnya
                    </a>
                </li>
                
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo $search; ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>
                
                <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo $search; ?>">
                        Selanjutnya <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<!-- Modal Add Anggota -->
<div class="modal fade" id="formModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Tambah Anggota</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="process.php" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama" id="nama" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="j_kel" class="form-label">Jenis Kelamin</label>
                                <select name="j_kel" id="j_kel" class="form-control" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tmp_lhr" class="form-label">Tempat Lahir</label>
                                <input type="text" name="tmp_lhr" id="tmp_lhr" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tgl_lhr" class="form-label">Tanggal Lahir</label>
                                <input type="date" name="tgl_lhr" id="tgl_lhr" class="form-control">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control" rows="3"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="no_tlp" class="form-label">No. Telepon</label>
                                <input type="text" name="no_tlp" id="no_tlp" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <input type="text" name="status" id="status" class="form-control" placeholder="Contoh: Aktif, Non-aktif">
                            </div>
                        </div>
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

<script>
function showAddForm() {
    document.getElementById('modalTitle').textContent = 'Tambah Anggota';
}
</script>

<?php
// Include footer
require_once '../../partials/footer.php';
?>
