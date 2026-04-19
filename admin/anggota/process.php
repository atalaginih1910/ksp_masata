<?php
/**
 * Process Anggota (Add/Edit)
 * MASATA PINJAMIN
 */

require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../config/helper.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_anggota = isset($_POST['id_anggota']) ? intval($_POST['id_anggota']) : 0;
    $nama = escape($_POST['nama']);
    $alamat = escape($_POST['alamat']);
    $tgl_lhr = escape($_POST['tgl_lhr']);
    $tmp_lhr = escape($_POST['tmp_lhr']);
    $j_kel = escape($_POST['j_kel']);
    $status = escape($_POST['status']);
    $no_tlp = escape($_POST['no_tlp']);
    $ket = escape($_POST['ket']);
    
    // Validasi
    if (empty($nama)) {
        $_SESSION['error'] = "Nama harus diisi!";
        header("Location: index.php");
        exit;
    }
    
    if ($id_anggota > 0) {
        // Update
        $sql = "UPDATE anggota SET 
                nama = '$nama',
                alamat = '$alamat',
                tgl_lhr = '$tgl_lhr',
                tmp_lhr = '$tmp_lhr',
                j_kel = '$j_kel',
                status = '$status',
                no_tlp = '$no_tlp',
                ket = '$ket'
                WHERE id_anggota = $id_anggota";
        
        if (query($sql)) {
            $_SESSION['success'] = "Data anggota berhasil diperbarui!";
        } else {
            $_SESSION['error'] = "Gagal memperbarui data anggota!";
        }
    } else {
        // Insert
        $sql = "INSERT INTO anggota (nama, alamat, tgl_lhr, tmp_lhr, j_kel, status, no_tlp, ket) 
                VALUES ('$nama', '$alamat', '$tgl_lhr', '$tmp_lhr', '$j_kel', '$status', '$no_tlp', '$ket')";
        
        if (query($sql)) {
            $_SESSION['success'] = "Data anggota berhasil ditambahkan!";
        } else {
            $_SESSION['error'] = "Gagal menambahkan data anggota!";
        }
    }
}

header("Location: index.php");
exit;
?>
