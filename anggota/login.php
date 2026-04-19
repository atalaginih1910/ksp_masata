<?php
/**
 * Login Anggota
 * MASATA PINJAMIN
 */

require_once '../config/database.php';
require_once '../config/session.php';
require_once '../config/helper.php';

if (is_anggota_logged_in()) {
    header('Location: ' . BASE_URL . '/anggota/dashboard.php');
    exit;
}

if (is_logged_in()) {
    header('Location: ' . BASE_URL . '/admin/dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_anggota = intval($_POST['id_anggota'] ?? 0);
    $no_tlp = trim(escape($_POST['no_tlp'] ?? ''));

    if ($id_anggota <= 0 || $no_tlp === '') {
        $error = 'Nomor anggota dan nomor telepon wajib diisi.';
    } else {
        $sql = "SELECT * FROM anggota WHERE id_anggota = $id_anggota AND no_tlp = '$no_tlp' LIMIT 1";
        $result = query($sql);
        $anggota = fetch_single($result);

        if ($anggota) {
            $_SESSION['anggota_id'] = $anggota['id_anggota'];
            $_SESSION['anggota_nama'] = $anggota['nama'];
            $_SESSION['anggota_no_tlp'] = $anggota['no_tlp'];
            $_SESSION['success'] = 'Selamat datang, ' . $anggota['nama'] . '!';

            header('Location: ' . BASE_URL . '/anggota/dashboard.php');
            exit;
        }

        $error = 'Data login anggota tidak valid.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Anggota - MASATA PINJAMIN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            min-height: 100vh;
            display: grid;
            place-items: center;
            margin: 0;
            font-family: Inter, "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #082f49 0%, #0f766e 50%, #0ea5e9 100%);
        }

        .card-login {
            width: min(520px, calc(100vw - 24px));
            border: none;
            border-radius: 22px;
            box-shadow: 0 28px 60px rgba(0, 0, 0, 0.24);
            overflow: hidden;
        }

        .card-head {
            padding: 26px;
            color: #fff;
            background: linear-gradient(135deg, #10b981 0%, #2dd4bf 100%);
        }

        .card-body {
            padding: 28px;
        }

        .form-control {
            border-radius: 12px;
            padding: 11px 14px;
        }

        .btn-main {
            width: 100%;
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 700;
            color: #fff;
            background: linear-gradient(135deg, #0ea5e9 0%, #10b981 100%);
        }
    </style>
</head>
<body>
    <div class="card card-login">
        <div class="card-head">
            <h3 class="mb-1"><i class="fas fa-user"></i> Login Anggota</h3>
            <small>Akses riwayat simpanan, pinjaman, dan angsuran Anda.</small>
        </div>
        <div class="card-body">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Nomor Anggota</label>
                    <input type="number" name="id_anggota" class="form-control" placeholder="Contoh: 1" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nomor Telepon</label>
                    <input type="text" name="no_tlp" class="form-control" placeholder="Contoh: 08123456789" required>
                </div>
                <button type="submit" class="btn btn-main">
                    <i class="fas fa-sign-in-alt"></i> Masuk Sebagai Anggota
                </button>
            </form>

            <div class="mt-3 text-center">
                <a href="<?php echo BASE_URL; ?>/login.php" class="text-decoration-none">
                    <i class="fas fa-arrow-left"></i> Kembali ke Login Admin/Petugas
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
