<?php
/**
 * Index/Redirect ke Admin Dashboard
 * MASATA PINJAMIN - Koperasi Simpan Pinjam
 */

// Jika sudah login, redirect ke admin dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: admin/dashboard.php");
    exit;
} else {
    // Jika belum login, redirect ke login page
    header("Location: login.php");
    exit;
}
?>
