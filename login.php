<?php
/**
 * Login Admin / Petugas
 * MASATA PINJAMIN
 */

require_once 'config/database.php';
require_once 'config/session.php';
require_once 'config/helper.php';

if (is_logged_in()) {
    header('Location: ' . BASE_URL . '/admin/dashboard.php');
    exit;
}

if (is_anggota_logged_in()) {
    header('Location: ' . BASE_URL . '/anggota/dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim(escape($_POST['username'] ?? ''));
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error = 'Username dan Password harus diisi!';
    } else {
        $sql = "SELECT * FROM petugas_koperasi WHERE nama = '$username' LIMIT 1";
        $result = query($sql);
        $user = fetch_single($result);

        if ($user) {
            $expectedPassword = stripos((string) $user['ket'], 'admin') !== false ? 'admin123' : 'petugas123';

            if ($password === $expectedPassword) {
                $_SESSION['user_id'] = $user['id_petugas'];
                $_SESSION['user_name'] = $user['nama'];
                $_SESSION['user_role'] = stripos((string) $user['ket'], 'admin') !== false ? 'admin' : 'petugas';
                $_SESSION['user_phone'] = $user['no_tlp'];
                $_SESSION['success'] = 'Selamat datang, ' . $user['nama'] . '!';

                header('Location: ' . BASE_URL . '/admin/dashboard.php');
                exit;
            }
        }

        $error = 'Username atau Password salah!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Petugas - MASATA PINJAMIN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { color-scheme: light; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            font-family: Inter, "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background:
                radial-gradient(circle at 20% 20%, rgba(45, 212, 191, 0.20), transparent 26%),
                radial-gradient(circle at 80% 20%, rgba(56, 189, 248, 0.18), transparent 24%),
                linear-gradient(135deg, #07111f 0%, #0d1b2a 50%, #10243b 100%);
        }
        body::before,
        body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            filter: blur(18px);
            opacity: 0.55;
        }
        body::before {
            width: 320px;
            height: 320px;
            left: -120px;
            bottom: -90px;
            background: rgba(45, 212, 191, 0.18);
        }
        body::after {
            width: 220px;
            height: 220px;
            right: -70px;
            top: 12%;
            background: rgba(56, 189, 248, 0.16);
        }
        .login-container {
            position: relative;
            width: min(1040px, calc(100vw - 32px));
            display: grid;
            grid-template-columns: 1.08fr 0.92fr;
            overflow: hidden;
            border-radius: 28px;
            border: 1px solid rgba(255, 255, 255, 0.14);
            background: rgba(255, 255, 255, 0.06);
            box-shadow: 0 32px 80px rgba(0, 0, 0, 0.34);
            backdrop-filter: blur(18px);
        }
        .login-header {
            position: relative;
            padding: 48px 42px;
            text-align: left;
            color: #f8fafc;
            background:
                linear-gradient(145deg, rgba(8, 47, 73, 0.92), rgba(15, 118, 110, 0.9)),
                radial-gradient(circle at top left, rgba(45, 212, 191, 0.28), transparent 34%),
                radial-gradient(circle at 70% 25%, rgba(56, 189, 248, 0.22), transparent 30%);
        }
        .login-header i {
            display: inline-flex;
            width: 68px;
            height: 68px;
            align-items: center;
            justify-content: center;
            margin-bottom: 22px;
            border-radius: 22px;
            font-size: 30px;
            background: linear-gradient(135deg, rgba(255,255,255,0.16), rgba(255,255,255,0.05));
        }
        .login-header h1 {
            font-size: clamp(2rem, 4vw, 3.2rem);
            font-weight: 900;
            letter-spacing: -0.03em;
            margin-bottom: 12px;
        }
        .login-header p {
            font-size: 1rem;
            line-height: 1.7;
            color: rgba(226, 232, 240, 0.84);
            max-width: 42ch;
        }
        .login-body { padding: 42px 38px; background: rgba(255,255,255,0.93); }
        .form-group { margin-bottom: 18px; }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #334155;
            font-weight: 700;
            font-size: 14px;
        }
        .form-group input {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid rgba(15, 23, 42, 0.12);
            border-radius: 14px;
            font-size: 14px;
            transition: all 0.25s ease;
            background: #fff;
        }
        .form-group input:focus {
            outline: none;
            border-color: rgba(45, 212, 191, 0.85);
            box-shadow: 0 0 0 0.22rem rgba(45, 212, 191, 0.16);
        }
        .btn-login {
            width: 100%;
            padding: 14px 16px;
            background: linear-gradient(135deg, #10b981 0%, #2dd4bf 42%, #38bdf8 100%);
            color: white;
            border: none;
            border-radius: 14px;
            font-weight: 800;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 14px 30px rgba(45, 212, 191, 0.22);
            margin-top: 10px;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 18px 36px rgba(56, 189, 248, 0.26);
        }
        .alert {
            margin-bottom: 18px;
            border-radius: 14px;
            border: none;
            padding: 12px 15px;
        }
        .login-note {
            margin-top: 18px;
            padding: 14px 16px;
            border-radius: 16px;
            background: #effbf6;
            border: 1px solid #d8f5e6;
            color: #14532d;
            font-size: 13px;
        }
        .login-note strong { display: inline-block; min-width: 92px; }
        .login-footer {
            text-align: center;
            padding: 14px 0 0;
            color: #64748b;
            font-size: 12px;
        }
        .login-links {
            margin-top: 14px;
            display: grid;
            gap: 10px;
        }
        .login-links a {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            border-radius: 11px;
            padding: 10px 12px;
        }
        .link-anggota {
            border: 1px solid rgba(14,165,233,0.35);
            color: #0369a1;
            background: rgba(14,165,233,0.08);
        }
        @media (max-width: 900px) {
            body { overflow-y: auto; padding: 18px 0; }
            .login-container { grid-template-columns: 1fr; }
            .login-header,
            .login-body { padding: 30px 22px; }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <i class="fas fa-user-shield"></i>
            <h1>MASATA PINJAMIN</h1>
            <p>
                Panel khusus Admin dan Petugas koperasi untuk mengelola data anggota,
                simpanan, pinjaman, angsuran, dan laporan.
            </p>
        </div>

        <div class="login-body">
            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <h2 class="mb-2" style="font-weight: 800; color: #0f172a;">Login Admin / Petugas</h2>
            <p class="mb-4" style="color: #64748b;">Gunakan akun petugas koperasi untuk melanjutkan.</p>

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

            <div class="login-note">
                <div><i class="fas fa-circle-info"></i> Login demo:</div>
                <div><strong>Admin:</strong> Admin / admin123</div>
                <div><strong>Petugas:</strong> Ata / petugas123</div>
            </div>

            <div class="login-links">
                <a href="<?php echo BASE_URL; ?>/anggota/login.php" class="link-anggota">
                    <i class="fas fa-user"></i> Login Anggota
                </a>
            </div>

            <div class="login-footer">
                &copy; 2026 MASATA PINJAMIN - Koperasi Simpan Pinjam.
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
