<?php
/**
 * Konfigurasi Database
 * MASATA PINJAMIN - Koperasi Simpan Pinjam
 */

// Konfigurasi Database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'koperasi_simpan_pinjam');

// Membuat koneksi menggunakan MySQLi
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi Database Gagal: " . $conn->connect_error);
}

// Set charset ke UTF-8
$conn->set_charset("utf8");

// Function untuk escape string (mencegah SQL Injection)
function escape($str) {
    global $conn;
    return $conn->real_escape_string($str);
}

// Function untuk execute query
function query($sql) {
    global $conn;
    $result = $conn->query($sql);
    
    if (!$result) {
        die("Query Error: " . $conn->error);
    }
    
    return $result;
}

// Function untuk fetch data
function fetch_all($result) {
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

// Function untuk fetch single data
function fetch_single($result) {
    return $result->fetch_assoc();
}

// Function untuk get last insert id
function last_insert_id() {
    global $conn;
    return $conn->insert_id;
}

// Function untuk count affected rows
function affected_rows() {
    global $conn;
    return $conn->affected_rows;
}
?>
