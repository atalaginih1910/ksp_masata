<?php
/**
 * Sidebar Partial
 * MASATA PINJAMIN
 */

// Determine current page
$current_page = basename($_SERVER['PHP_SELF'], '.php');
$current_module = isset($_GET['module']) ? $_GET['module'] : '';
?>

<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-brand">
        <h4><i class="fas fa-piggy-bank"></i> MASATA</h4>
        <p>Koperasi Simpan Pinjam</p>
    </div>
    
    <ul class="sidebar-menu">
        <li>
            <a href="<?php echo BASE_URL; ?>/admin/dashboard.php" class="<?php echo $current_page === 'dashboard' ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>
        
        <li style="padding: 15px 25px; color: #7f8c8d; font-size: 11px; text-transform: uppercase; font-weight: bold; margin-top: 10px;">
            <i class="fas fa-database"></i> Data Master
        </li>
        
        <li>
            <a href="<?php echo BASE_URL; ?>/admin/anggota/index.php" class="<?php echo $current_page === 'index' && strpos($_SERVER['REQUEST_URI'], 'anggota') ? 'active' : ''; ?>">
                <i class="fas fa-users"></i>
                <span>Anggota</span>
            </a>
        </li>
        
        <li>
            <a href="<?php echo BASE_URL; ?>/admin/kategori/index.php" class="<?php echo $current_page === 'index' && strpos($_SERVER['REQUEST_URI'], 'kategori') ? 'active' : ''; ?>">
                <i class="fas fa-list"></i>
                <span>Kategori Pinjaman</span>
            </a>
        </li>
        
        <li style="padding: 15px 25px; color: #7f8c8d; font-size: 11px; text-transform: uppercase; font-weight: bold; margin-top: 10px;">
            <i class="fas fa-exchange-alt"></i> Transaksi
        </li>
        
        <li>
            <a href="<?php echo BASE_URL; ?>/admin/simpanan/index.php" class="<?php echo $current_page === 'index' && strpos($_SERVER['REQUEST_URI'], 'simpanan') ? 'active' : ''; ?>">
                <i class="fas fa-coins"></i>
                <span>Simpanan</span>
            </a>
        </li>
        
        <li>
            <a href="<?php echo BASE_URL; ?>/admin/pinjaman/index.php" class="<?php echo $current_page === 'index' && strpos($_SERVER['REQUEST_URI'], 'pinjaman') ? 'active' : ''; ?>">
                <i class="fas fa-money-bill-wave"></i>
                <span>Pinjaman</span>
            </a>
        </li>
        
        <li>
            <a href="<?php echo BASE_URL; ?>/admin/angsuran/index.php" class="<?php echo $current_page === 'index' && strpos($_SERVER['REQUEST_URI'], 'angsuran') ? 'active' : ''; ?>">
                <i class="fas fa-credit-card"></i>
                <span>Angsuran</span>
            </a>
        </li>
        
        <li style="padding: 15px 25px; color: #7f8c8d; font-size: 11px; text-transform: uppercase; font-weight: bold; margin-top: 10px;">
            <i class="fas fa-file-alt"></i> Laporan
        </li>
        
        <li>
            <a href="<?php echo BASE_URL; ?>/admin/laporan/anggota.php" class="<?php echo $current_page === 'anggota' && strpos($_SERVER['REQUEST_URI'], 'laporan') ? 'active' : ''; ?>">
                <i class="fas fa-file-pdf"></i>
                <span>Laporan Anggota</span>
            </a>
        </li>
        
        <li>
            <a href="<?php echo BASE_URL; ?>/admin/laporan/simpanan.php" class="<?php echo $current_page === 'simpanan' && strpos($_SERVER['REQUEST_URI'], 'laporan') ? 'active' : ''; ?>">
                <i class="fas fa-file-pdf"></i>
                <span>Laporan Simpanan</span>
            </a>
        </li>
        
        <li>
            <a href="<?php echo BASE_URL; ?>/admin/laporan/pinjaman.php" class="<?php echo $current_page === 'pinjaman' && strpos($_SERVER['REQUEST_URI'], 'laporan') ? 'active' : ''; ?>">
                <i class="fas fa-file-pdf"></i>
                <span>Laporan Pinjaman</span>
            </a>
        </li>
        
        <li>
            <a href="<?php echo BASE_URL; ?>/admin/laporan/angsuran.php" class="<?php echo $current_page === 'angsuran' && strpos($_SERVER['REQUEST_URI'], 'laporan') ? 'active' : ''; ?>">
                <i class="fas fa-file-pdf"></i>
                <span>Laporan Angsuran</span>
            </a>
        </li>
        
        <li style="padding: 15px 25px; color: #7f8c8d; font-size: 11px; text-transform: uppercase; font-weight: bold; margin-top: 10px;">
            <i class="fas fa-cog"></i> Sistem
        </li>
        
        <li>
            <a href="<?php echo BASE_URL; ?>/logout.php" onclick="return confirm('Yakin ingin logout?');">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>
</div>

<!-- Top Navigation -->
<div class="topnav">
    <div>
        <h2><i class="fas fa-bars"></i> MASATA PINJAMIN</h2>
    </div>
    <div class="topnav-right">
        <div class="topnav-user">
            <div class="topnav-user-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div>
                <small>Welcome</small>
                <div style="font-weight: bold;"><?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User'; ?></div>
            </div>
        </div>
    </div>
</div>
