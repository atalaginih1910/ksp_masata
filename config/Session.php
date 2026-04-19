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

// Helper function untuk check login anggota
function is_anggota_logged_in() {
    return isset($_SESSION['anggota_id']) && isset($_SESSION['anggota_nama']);
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

// Helper function untuk redirect jika anggota belum login
function require_anggota_login() {
    if (!is_anggota_logged_in()) {
        $_SESSION['error'] = "Anda harus login sebagai anggota terlebih dahulu!";
        header("Location: " . BASE_URL . "/anggota/login.php");
        exit;
    }
}

// Konstanta BASE URL
define('BASE_URL', 'http://localhost/Ahmad/ksp_masata');
?>
