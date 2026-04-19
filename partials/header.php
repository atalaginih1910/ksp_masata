<?php
/**
 * Header Partial
 * MASATA PINJAMIN
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - MASATA PINJAMIN' : 'MASATA PINJAMIN'; ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    
    <style>
        :root {
            --primary-color: #2dd4bf;
            --secondary-color: #38bdf8;
            --danger-color: #f87171;
            --warning-color: #fbbf24;
            --success-color: #16a34a;
            --info-color: #0ea5e9;
            --dark-color: #0f172a;
            --light-color: #f8fafc;
            --surface: rgba(255, 255, 255, 0.78);
            --surface-strong: rgba(255, 255, 255, 0.92);
            --line: rgba(15, 23, 42, 0.08);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Inter, "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background:
                radial-gradient(circle at 15% 20%, rgba(45, 212, 191, 0.16), transparent 26%),
                radial-gradient(circle at 85% 8%, rgba(56, 189, 248, 0.14), transparent 22%),
                linear-gradient(180deg, #edf7fb 0%, #f6fbff 100%);
            color: #0f172a;
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(15, 23, 42, 0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(15, 23, 42, 0.02) 1px, transparent 1px);
            background-size: 38px 38px;
            mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.7), transparent 88%);
            pointer-events: none;
        }

        .sidebar {
            background: linear-gradient(180deg, rgba(8, 47, 73, 0.96) 0%, rgba(15, 118, 110, 0.95) 100%);
            min-height: 100vh;
            padding: 18px 0;
            position: fixed;
            left: 0;
            top: 0;
            width: 260px;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 0 18px 45px rgba(2, 8, 23, 0.24);
            border-right: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
        }

        .sidebar-brand {
            padding: 18px 20px 22px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
            margin-bottom: 14px;
        }

        .sidebar-brand h4 {
            color: #7ff0c3;
            font-weight: 800;
            margin-bottom: 5px;
            font-size: 18px;
            letter-spacing: 0.06em;
        }

        .sidebar-brand p {
            color: rgba(226, 232, 240, 0.78);
            font-size: 12px;
            margin: 0;
        }

        .sidebar-menu {
            padding: 0;
            list-style: none;
        }

        .sidebar-menu li {
            margin: 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 13px 22px;
            color: rgba(226, 232, 240, 0.76);
            text-decoration: none;
            transition: transform 0.2s ease, background 0.2s ease, color 0.2s ease;
            border-left: 4px solid transparent;
            margin: 3px 10px;
            border-radius: 14px;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: linear-gradient(135deg, rgba(45, 212, 191, 0.18), rgba(56, 189, 248, 0.12));
            color: #ffffff;
            border-left-color: #7ff0c3;
            transform: translateX(3px);
        }

        .sidebar-menu i {
            width: 22px;
            text-align: center;
            font-size: 14px;
        }

        .sidebar-menu .badge {
            margin-left: auto;
        }

        .topnav {
            background: rgba(255, 255, 255, 0.72);
            border-bottom: 1px solid rgba(15, 23, 42, 0.06);
            color: #0f172a;
            padding: 14px 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: 260px;
            right: 0;
            z-index: 999;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
            backdrop-filter: blur(18px);
        }

        .topnav h2 {
            margin: 0;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: 0.02em;
        }

        .topnav-right {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .topnav-user {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .topnav-user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            background: linear-gradient(135deg, #0ea5e9 0%, #2dd4bf 100%);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            box-shadow: 0 10px 24px rgba(14, 165, 233, 0.22);
        }

        .main-content {
            margin-left: 260px;
            margin-top: 74px;
            padding: 28px;
            min-height: calc(100vh - 74px);
        }

        .card {
            border: 1px solid rgba(15, 23, 42, 0.06);
            border-radius: 20px;
            background: var(--surface-strong);
            box-shadow: 0 16px 35px rgba(15, 23, 42, 0.06);
            transition: transform 0.22s ease, box-shadow 0.22s ease;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.09);
        }

        .card-header {
            background: rgba(248, 250, 252, 0.9);
        }

        .stat-card {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #10b981 0%, #2dd4bf 48%, #38bdf8 100%);
            color: white;
            padding: 24px;
            border-radius: 22px;
            margin-bottom: 20px;
            box-shadow: 0 18px 35px rgba(45, 212, 191, 0.25);
        }

        .stat-card::after {
            content: "";
            position: absolute;
            inset: auto -40px -40px auto;
            width: 160px;
            height: 160px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.12);
        }

        .stat-card-blue {
            background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%);
            box-shadow: 0 18px 35px rgba(37, 99, 235, 0.22);
        }

        .stat-card-orange {
            background: linear-gradient(135deg, #f59e0b 0%, #fb7185 100%);
            box-shadow: 0 18px 35px rgba(245, 158, 11, 0.22);
        }

        .stat-card-red {
            background: linear-gradient(135deg, #ef4444 0%, #fb7185 100%);
            box-shadow: 0 18px 35px rgba(239, 68, 68, 0.22);
        }

        .stat-card h3 {
            margin: 0;
            font-size: 14px;
            opacity: 0.95;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .stat-card .stat-value {
            font-size: 30px;
            font-weight: 800;
            margin: 12px 0 0 0;
            letter-spacing: -0.02em;
        }

        .table {
            background: transparent;
            margin-bottom: 0;
        }

        .table thead {
            background: rgba(248, 250, 252, 0.88);
            border-bottom: 1px solid rgba(15, 23, 42, 0.08);
        }

        .table th {
            padding: 14px 16px;
            color: #334155;
            font-weight: 700;
            border: none;
        }

        .table td {
            padding: 14px 16px;
            border-color: rgba(15, 23, 42, 0.06);
            vertical-align: middle;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(45, 212, 191, 0.06);
        }

        .btn {
            border-radius: 12px;
            padding: 9px 16px;
            font-size: 13px;
            font-weight: 700;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-primary,
        .btn-success,
        .btn-info,
        .btn-warning,
        .btn-danger,
        .btn-secondary {
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #10b981 0%, #2dd4bf 100%);
            color: white;
        }

        .btn-primary:hover {
            box-shadow: 0 10px 22px rgba(45, 212, 191, 0.28);
            color: white;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #64748b 0%, #334155 100%);
            color: white;
        }

        .btn-info {
            background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%);
            color: white;
        }

        .btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #fb7185 100%);
            color: white;
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #f97316 100%);
            color: white;
        }

        .badge {
            padding: 7px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.01em;
        }

        .alert {
            border-radius: 16px;
            border: 1px solid rgba(15, 23, 42, 0.06);
            padding: 14px 18px;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.08);
            color: #166534;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.08);
            color: #991b1b;
        }

        .alert-warning {
            background: rgba(245, 158, 11, 0.08);
            color: #92400e;
        }

        .alert-info {
            background: rgba(14, 165, 233, 0.08);
            color: #075985;
        }

        .modal-content {
            border: 1px solid rgba(15, 23, 42, 0.06);
            border-radius: 22px;
            overflow: hidden;
        }

        .modal-header {
            background: linear-gradient(135deg, #0ea5e9 0%, #10b981 100%);
            color: white;
            border: none;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        .form-label {
            color: #334155;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            border-radius: 14px;
            border: 1px solid rgba(15, 23, 42, 0.12);
            padding: 11px 14px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: rgba(45, 212, 191, 0.85);
            box-shadow: 0 0 0 0.22rem rgba(45, 212, 191, 0.14);
        }

        .chart-container {
            position: relative;
            height: 320px;
        }

        .pagination .page-link {
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 12px;
            margin: 0 4px;
            color: #0f172a;
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #0ea5e9 0%, #10b981 100%);
            border-color: transparent;
        }

        .pagination .page-link:hover {
            background: rgba(45, 212, 191, 0.08);
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                padding: 0;
                overflow: hidden;
            }

            .topnav {
                left: 0;
                padding: 12px 16px;
            }

            .main-content {
                margin-left: 0;
                padding: 20px 16px;
            }

            .topnav h2 {
                font-size: 16px;
            }

            .chart-container {
                height: 260px;
            }
        }

        @media print {
            .sidebar,
            .topnav,
            .btn,
            .modal,
            .no-print {
                display: none !important;
            }

            .main-content {
                margin-left: 0;
                margin-top: 0;
                padding: 0;
            }

            body {
                background: white;
            }

            .card {
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }
    </style>
</head>
<body>
