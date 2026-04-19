<?php
/**
 * Delete Simpanan
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

// Delete simpanan
$sql = "DELETE FROM simpanan WHERE id_simpanan = $id";

if (query($sql)) {
    $_SESSION['success'] = "Simpanan berhasil dihapus!";
} else {
    $_SESSION['error'] = "Gagal menghapus simpanan!";
}

header("Location: index.php");
exit;
?>
