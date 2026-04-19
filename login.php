<?php
/**
 * Login Process
 * MASATA PINJAMIN - Koperasi Simpan Pinjam
 */

require_once 'config/database.php';
require_once 'config/session.php';
require_once 'config/helper.php';

// Jika sudah login, redirect ke dashboard
if (is_logged_in()) {
    header("Location: " . BASE_URL . "/admin/dashboard.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = escape($_POST['username']);
    $password = escape($_POST['password']);
    
    // Validasi input
    if (empty($username) || empty($password)) {
        $error = "Username dan Password harus diisi!";
    } else {
        // Query ke database untuk cek login
        // Note: Untuk demo, kita gunakan petugas_koperasi sebagai user
        $sql = "SELECT * FROM petugas_koperasi WHERE nama = '$username' LIMIT 1";
        $result = query($sql);
        $user = fetch_single($result);
        
        if ($user) {
            // Untuk versi sederhana, password adalah hardcoded
            // Pada production, gunakan password_hash dan password_verify
            if ($password === 'admin123') {
                $_SESSION['user_id'] = $user['id_petugas'];
                $_SESSION['user_name'] = $user['nama'];
                $_SESSION['user_role'] = 'admin'; // Default role admin
                $_SESSION['user_phone'] = $user['no_tlp'];
                
                $_SESSION['success'] = "Selamat datang, " . $user['nama'] . "!";
                header("Location: " . BASE_URL . "/admin/dashboard.php");
                exit;
            } else {
                $error = "Username atau Password salah!";
            }
        } else {
            $error = "Username atau Password salah!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MASATA PINJAMIN</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .login-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
        }
        
        .login-header {
            background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }
        
        .login-header h1 {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .login-header p {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .login-header i {
            font-size: 40px;
            margin-bottom: 10px;
            display: block;
        }
        
        .login-body {
            padding: 40px 30px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #2ecc71;
            box-shadow: 0 0 0 3px rgba(46, 204, 113, 0.1);
        }
        
        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(46, 204, 113, 0.2);
        }
        
        .alert {
            margin-bottom: 20px;
            border-radius: 8px;
            border: none;
            padding: 12px 15px;
        }
        
        .login-footer {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-top: 1px solid #e0e0e0;
            font-size: 12px;
            color: #666;
        }
        
        .input-group-text {
            background: transparent;
            border: 2px solid #e0e0e0;
            color: #666;
        }
        
        .input-group input:focus + .input-group-text {
            border-color: #2ecc71;
            color: #2ecc71;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Header -->
        <div class="login-header">
            <i class="fas fa-piggy-bank"></i>
            <h1>MASATA PINJAMIN</h1>
            <p>Koperasi Simpan Pinjam</p>
        </div>
        
        <!-- Body -->
        <div class="login-body">
            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username"><i class="fas fa-user"></i> Username</label>
                    <input type="text" id="username" name="username" placeholder="Masukkan username" required>
                </div>
                
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Password</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                </div>
                
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> MASUK
                </button>
            </form>
            
            <div style="text-align: center; margin-top: 20px; color: #999; font-size: 13px;">
                <p><i class="fas fa-info-circle"></i></p>
                <p>Demo Login:</p>
                <p><strong>Username:</strong> Ahmad</p>
                <p><strong>Password:</strong> admin123</p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="login-footer">
            <p>&copy; 2026 MASATA PINJAMIN - Koperasi Simpan Pinjam. All rights reserved.</p>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
