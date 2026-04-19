<?php
/**
 * Edit Anggota
 * MASATA PINJAMIN
 */

require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../config/helper.php';

require_login();

$page_title = 'Edit Anggota';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM anggota WHERE id_anggota = $id";
$result = query($sql);
$anggota = fetch_single($result);

if (!$anggota) {
    $_SESSION['error'] = "Data anggota tidak ditemukan!";
    header("Location: index.php");
    exit;
}

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
                <i class="fas fa-edit" style="color: #2ecc71;"></i> Edit Anggota
            </h3>
            <small style="color: #7f8c8d;">Ubah data anggota</small>
        </div>
        <a href="index.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    
    <!-- Form -->
    <div class="card">
        <div class="card-body">
            <form action="process.php" method="POST">
                <input type="hidden" name="id_anggota" value="<?php echo $anggota['id_anggota']; ?>">
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" id="nama" class="form-control" value="<?php echo $anggota['nama']; ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="j_kel" class="form-label">Jenis Kelamin</label>
                            <select name="j_kel" id="j_kel" class="form-control" required>
                                <option value="">-- Pilih --</option>
                                <option value="L" <?php echo $anggota['j_kel'] === 'L' ? 'selected' : ''; ?>>Laki-laki</option>
                                <option value="P" <?php echo $anggota['j_kel'] === 'P' ? 'selected' : ''; ?>>Perempuan</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tmp_lhr" class="form-label">Tempat Lahir</label>
                            <input type="text" name="tmp_lhr" id="tmp_lhr" class="form-control" value="<?php echo $anggota['tmp_lhr']; ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tgl_lhr" class="form-label">Tanggal Lahir</label>
                            <input type="date" name="tgl_lhr" id="tgl_lhr" class="form-control" value="<?php echo $anggota['tgl_lhr']; ?>">
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <textarea name="alamat" id="alamat" class="form-control" rows="3"><?php echo $anggota['alamat']; ?></textarea>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="no_tlp" class="form-label">No. Telepon</label>
                            <input type="text" name="no_tlp" id="no_tlp" class="form-control" value="<?php echo $anggota['no_tlp']; ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <input type="text" name="status" id="status" class="form-control" value="<?php echo $anggota['status']; ?>">
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="ket" class="form-label">Keterangan</label>
                    <textarea name="ket" id="ket" class="form-control" rows="2"><?php echo $anggota['ket']; ?></textarea>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
// Include footer
require_once '../../partials/footer.php';
?>
