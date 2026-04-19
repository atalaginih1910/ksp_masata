<?php
/**
 * Bayar Angsuran
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

// Update pembayaran angsuran
$tgl_pembayaran = date('Y-m-d');
$sql = "UPDATE angsuran SET tgl_pembayaran = '$tgl_pembayaran' WHERE id_angsuran = $id";

if (query($sql)) {
    $_SESSION['success'] = "Pembayaran angsuran berhasil dicatat!";
} else {
    $_SESSION['error'] = "Gagal mencatat pembayaran angsuran!";
}

header("Location: index.php");
exit;
?>
