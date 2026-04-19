<?php
/**
 * Daftar Kategori Pinjaman
 * MASATA PINJAMIN
 */

require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../config/helper.php';

require_login();

$page_title = 'Kategori Pinjaman';

// Get kategori pinjaman
$sql = "SELECT * FROM kategori_pinjaman ORDER BY id_kategori_pinjaman DESC";
$result = query($sql);
$d_kategori = fetch_all($result);

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
                <i class="fas fa-list" style="color: #2ecc71;"></i> Kategori Pinjaman
            </h3>
            <small style="color: #7f8c8d;">Kelola kategori pinjaman</small>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fas fa-plus"></i> Tambah Kategori
        </button>
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
    
    <!-- Data Table -->
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background: #f8f9fa;">
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th>Nama Kategori</th>
                        <th style="width: 20%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($d_kategori) > 0): ?>
                        <?php $no = 1; foreach ($d_kategori as $kategori): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $kategori['nama_pinjaman']; ?></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal" 
                                        onclick="setEditForm(<?php echo $kategori['id_kategori_pinjaman']; ?>, '<?php echo addslashes($kategori['nama_pinjaman']); ?>')">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <a href="delete.php?id=<?php echo $kategori['id_kategori_pinjaman']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin hapus?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">
                                <i class="fas fa-inbox" style="font-size: 30px; opacity: 0.3;"></i>
                                <p style="margin-top: 10px;">Tidak ada data kategori pinjaman</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Add -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Kategori Pinjaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="process.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Kategori</label>
                        <input type="text" name="nama_pinjaman" id="nama" class="form-control" placeholder="Contoh: Pinjaman Usaha" required>
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

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Kategori Pinjaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="process.php" method="POST">
                <input type="hidden" name="id_kategori_pinjaman" id="editId" value="">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editNama" class="form-label">Nama Kategori</label>
                        <input type="text" name="nama_pinjaman" id="editNama" class="form-control" required>
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
function setEditForm(id, nama) {
    document.getElementById('editId').value = id;
    document.getElementById('editNama').value = nama;
}
</script>

<?php
// Include footer
require_once '../../partials/footer.php';
?>
