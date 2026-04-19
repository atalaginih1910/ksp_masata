<?php
/**
 * Approve Pinjaman
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

// Update status pinjaman
$tgl_acc = date('Y-m-d');
$sql = "UPDATE pinjaman SET tgl_acc_peminjam = '$tgl_acc' WHERE id_pinjaman = $id";

if (query($sql)) {
    $_SESSION['success'] = "Pinjaman berhasil disetujui!";
} else {
    $_SESSION['error'] = "Gagal menyetujui pinjaman!";
}

header("Location: index.php");
exit;
?>
