<?php
/**
 * Index/Redirect ke Admin Dashboard
 * MASATA PINJAMIN - Koperasi Simpan Pinjam
 */

require_once 'config/session.php';

// Jika admin/petugas sudah login, redirect ke admin dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: admin/dashboard.php");
    exit;
}

// Jika anggota sudah login, redirect ke dashboard anggota
if (isset($_SESSION['anggota_id'])) {
    header("Location: anggota/dashboard.php");
    exit;
} else {
    // Jika belum login, redirect ke login page
    header("Location: login.php");
    exit;
}
?>
