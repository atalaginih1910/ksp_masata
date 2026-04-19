<?php
/**
 * Process Pinjaman
 * MASATA PINJAMIN
 */

require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../config/helper.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_anggota = intval($_POST['id_anggota']);
    $nama_pinjaman = escape($_POST['nama_pinjaman']);
    $besar_pinjaman = floatval($_POST['besar_pinjaman']);
    $tgl_pengajuan_pinjaman = escape($_POST['tgl_pengajuan_pinjaman']);
    $ket = escape($_POST['ket']);
    
    // Validasi
    if (empty($id_anggota) || empty($nama_pinjaman) || $besar_pinjaman <= 0) {
        $_SESSION['error'] = "Data tidak lengkap!";
        header("Location: index.php");
        exit;
    }
    
    // Insert pinjaman
    $sql = "INSERT INTO pinjaman (id_anggota, nama_pinjaman, besar_pinjaman, tgl_pengajuan_pinjaman, ket) 
            VALUES ($id_anggota, '$nama_pinjaman', $besar_pinjaman, '$tgl_pengajuan_pinjaman', '$ket')";
    
    if (query($sql)) {
        $_SESSION['success'] = "Pinjaman berhasil ditambahkan!";
    } else {
        $_SESSION['error'] = "Gagal menambahkan pinjaman!";
    }
}

header("Location: index.php");
exit;
?>
