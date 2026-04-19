<?php
/**
 * Delete Kategori Pinjaman
 * MASATA PINJAMIN
 */

require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../config/helper.php';

require_login();

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);

// Delete kategori pinjaman
$sql = "DELETE FROM kategori_pinjaman WHERE id_kategori_pinjaman = $id";

if (query($sql)) {
    $_SESSION['success'] = "Kategori pinjaman berhasil dihapus!";
} else {
    $_SESSION['error'] = "Gagal menghapus kategori pinjaman!";
}

header("Location: index.php");
exit;
?>
