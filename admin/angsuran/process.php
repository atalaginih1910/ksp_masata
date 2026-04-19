<?php
/**
 * Process Angsuran
 * MASATA PINJAMIN
 */

require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../config/helper.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_kategori = intval($_POST['id_kategori']);
    $id_anggota = intval($_POST['id_anggota']);
    $angsuran_ke = isset($_POST['angsuran_ke']) && $_POST['angsuran_ke'] !== '' ? intval($_POST['angsuran_ke']) : 0;
    $besar_angsuran = floatval($_POST['besar_angsuran']);
    $ket = escape($_POST['ket']);
    
    // Validasi
    if (empty($id_kategori) || empty($id_anggota) || $besar_angsuran <= 0) {
        $_SESSION['error'] = "Data tidak lengkap!";
        header("Location: index.php");
        exit;
    }

    if ($angsuran_ke <= 0) {
        $sql_next = "SELECT COALESCE(MAX(angsuran_ke), 0) + 1 AS next_no FROM angsuran WHERE id_kategori = $id_kategori AND id_anggota = $id_anggota";
        $result_next = query($sql_next);
        $next_row = fetch_single($result_next);
        $angsuran_ke = intval($next_row['next_no']);
    }
    
    // Insert angsuran
    $sql = "INSERT INTO angsuran (id_kategori, id_anggota, angsuran_ke, besar_angsuran, ket) 
            VALUES ($id_kategori, $id_anggota, $angsuran_ke, $besar_angsuran, '$ket')";
    
    if (query($sql)) {
        $_SESSION['success'] = "Angsuran berhasil ditambahkan!";
    } else {
        $_SESSION['error'] = "Gagal menambahkan angsuran!";
    }
}

header("Location: index.php");
exit;
?>
