<?php
/**
 * Proses Pengajuan Pinjaman Anggota
 * MASATA PINJAMIN
 */

require_once '../config/database.php';
require_once '../config/session.php';

require_anggota_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/anggota/dashboard.php');
    exit;
}

$id_anggota = intval($_SESSION['anggota_id']);
$id_kategori_pinjaman = intval($_POST['id_kategori_pinjaman'] ?? 0);
$besar_pinjaman = floatval($_POST['besar_pinjaman'] ?? 0);
$ket = trim(escape($_POST['ket'] ?? ''));

if ($id_kategori_pinjaman <= 0 || $besar_pinjaman <= 0) {
    $_SESSION['error'] = 'Kategori pinjaman dan jumlah pinjaman wajib diisi dengan benar.';
    header('Location: ' . BASE_URL . '/anggota/dashboard.php');
    exit;
}

$sql_kategori = "SELECT nama_pinjaman FROM kategori_pinjaman WHERE id_kategori_pinjaman = $id_kategori_pinjaman LIMIT 1";
$result_kategori = query($sql_kategori);
$kategori = fetch_single($result_kategori);

if (!$kategori) {
    $_SESSION['error'] = 'Kategori pinjaman tidak ditemukan.';
    header('Location: ' . BASE_URL . '/anggota/dashboard.php');
    exit;
}

$nama_pinjaman = escape($kategori['nama_pinjaman']);
$tgl_pengajuan = date('Y-m-d');

if ($ket === '') {
    $ket = 'Pengajuan anggota via portal';
}

$sql_insert = "INSERT INTO pinjaman (nama_pinjaman, id_anggota, besar_pinjaman, tgl_pengajuan_pinjaman, ket)
               VALUES ('$nama_pinjaman', $id_anggota, $besar_pinjaman, '$tgl_pengajuan', '$ket')";

query($sql_insert);

$_SESSION['success'] = 'Pengajuan pinjaman berhasil dikirim dan menunggu verifikasi petugas.';
header('Location: ' . BASE_URL . '/anggota/dashboard.php');
exit;
