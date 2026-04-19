<?php
/**
 * Logout Process
 * MASATA PINJAMIN - Koperasi Simpan Pinjam
 */

require_once 'config/session.php';

// Hapus session
session_destroy();

// Redirect ke login
header("Location: " . BASE_URL . "/login.php");
exit;
?>
