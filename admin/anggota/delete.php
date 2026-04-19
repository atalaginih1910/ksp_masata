<?php
/**
 * Delete Anggota
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

// Cek apakah anggota memiliki simpanan atau pinjaman
$sql_check = "SELECT COUNT(*) as total FROM simpanan WHERE id_anggota = $id";
$result_check = query($sql_check);
$data_check = fetch_single($result_check);

if ($data_check['total'] > 0) {
    $_SESSION['error'] = "Tidak bisa menghapus anggota yang masih memiliki simpanan!";
    header("Location: index.php");
    exit;
}

// Delete anggota
$sql = "DELETE FROM anggota WHERE id_anggota = $id";

if (query($sql)) {
    $_SESSION['success'] = "Data anggota berhasil dihapus!";
} else {
    $_SESSION['error'] = "Gagal menghapus data anggota!";
}

header("Location: index.php");
exit;
?>
