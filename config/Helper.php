<?php
/**
 * Helper Functions
 * MASATA PINJAMIN - Koperasi Simpan Pinjam
 */

// Format mata uang Rupiah
function format_rupiah($value) {
    return "Rp " . number_format($value, 0, ',', '.');
}

// Format tanggal Indonesia
function format_tanggal($date) {
    $months = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
    
    $timestamp = strtotime($date);
    $day = date('d', $timestamp);
    $month = $months[date('n', $timestamp)];
    $year = date('Y', $timestamp);
    
    return $day . ' ' . $month . ' ' . $year;
}

// Get nama bulan
function get_bulan($bulan) {
    $months = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
    return $months[$bulan];
}

// Validasi email
function is_email_valid($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Generate random string
function generate_random() {
    return bin2hex(random_bytes(16));
}

// Get status label
function get_status_label($status) {
    $labels = [
        'pending' => '<span class="badge bg-warning">Pending</span>',
        'disetujui' => '<span class="badge bg-success">Disetujui</span>',
        'ditolak' => '<span class="badge bg-danger">Ditolak</span>',
        'lunas' => '<span class="badge bg-info">Lunas</span>'
    ];
    
    return isset($labels[$status]) ? $labels[$status] : $status;
}

// Get jenis kelamin label
function get_jenis_kelamin($jk) {
    return $jk === 'L' ? 'Laki-laki' : 'Perempuan';
}
?>
