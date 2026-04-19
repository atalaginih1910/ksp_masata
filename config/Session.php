<?php
/**
 * Session Management
 * MASATA PINJAMIN - Koperasi Simpan Pinjam
 */

session_start();

// Helper function untuk check login
function is_logged_in() {
    return isset($_SESSION['user_id']) && isset($_SESSION['user_name']);
}

// Helper function untuk check role
function check_role($role) {
    if (!is_logged_in()) {
        return false;
    }
    return $_SESSION['user_role'] === $role;
}

// Helper function untuk redirect jika belum login
function require_login() {
    if (!is_logged_in()) {
        $_SESSION['error'] = "Anda harus login terlebih dahulu!";
        header("Location: " . BASE_URL . "/login.php");
        exit;
    }
}

// Konstanta BASE URL
define('BASE_URL', 'http://localhost/Ahmad/ksp_masata');
?>
