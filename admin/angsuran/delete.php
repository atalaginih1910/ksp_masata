<?php
/**
 * Delete Angsuran
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

// Delete angsuran
$sql = "DELETE FROM angsuran WHERE id_angsuran = $id";

if (query($sql)) {
    $_SESSION['success'] = "Angsuran berhasil dihapus!";
} else {
    $_SESSION['error'] = "Gagal menghapus angsuran!";
}

header("Location: index.php");
exit;
?>
