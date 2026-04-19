<?php
/**
 * Process Simpanan
 * MASATA PINJAMIN
 */

require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../config/helper.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_anggota = intval($_POST['id_anggota']);
    $nm_simpanan = escape($_POST['nm_simpanan']);
    $tgl_simpanan = escape($_POST['tgl_simpanan']);
    $besar_simpanan = floatval($_POST['besar_simpanan']);
    $ket = escape($_POST['ket']);
    
    // Validasi
    if (empty($id_anggota) || empty($nm_simpanan) || empty($tgl_simpanan) || $besar_simpanan <= 0) {
        $_SESSION['error'] = "Data tidak lengkap!";
        header("Location: index.php");
        exit;
    }
    
    // Insert simpanan
    $sql = "INSERT INTO simpanan (id_anggota, nm_simpanan, tgl_simpanan, besar_simpanan, ket) 
            VALUES ($id_anggota, '$nm_simpanan', '$tgl_simpanan', $besar_simpanan, '$ket')";
    
    if (query($sql)) {
        $_SESSION['success'] = "Simpanan berhasil ditambahkan!";
    } else {
        $_SESSION['error'] = "Gagal menambahkan simpanan!";
    }
}

header("Location: index.php");
exit;
?>
