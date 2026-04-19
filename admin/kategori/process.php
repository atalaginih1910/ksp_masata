<?php
/**
 * Process Kategori Pinjaman
 * MASATA PINJAMIN
 */

require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../config/helper.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_kategori_pinjaman = isset($_POST['id_kategori_pinjaman']) ? intval($_POST['id_kategori_pinjaman']) : 0;
    $nama_pinjaman = escape($_POST['nama_pinjaman']);
    
    // Validasi
    if (empty($nama_pinjaman)) {
        $_SESSION['error'] = "Nama kategori harus diisi!";
        header("Location: index.php");
        exit;
    }
    
    if ($id_kategori_pinjaman > 0) {
        // Update
        $sql = "UPDATE kategori_pinjaman SET nama_pinjaman = '$nama_pinjaman' WHERE id_kategori_pinjaman = $id_kategori_pinjaman";
        
        if (query($sql)) {
            $_SESSION['success'] = "Kategori pinjaman berhasil diperbarui!";
        } else {
            $_SESSION['error'] = "Gagal memperbarui kategori pinjaman!";
        }
    } else {
        // Insert
        $sql = "INSERT INTO kategori_pinjaman (nama_pinjaman) VALUES ('$nama_pinjaman')";
        
        if (query($sql)) {
            $_SESSION['success'] = "Kategori pinjaman berhasil ditambahkan!";
        } else {
            $_SESSION['error'] = "Gagal menambahkan kategori pinjaman!";
        }
    }
}

header("Location: index.php");
exit;
?>
