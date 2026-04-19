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
            --primary-color: #2ecc71;
            --secondary-color: #3498db;
            --danger-color: #e74c3c;
            --warning-color: #f39c12;
            --success-color: #27ae60;
            --info-color: #1abc9c;
            --dark-color: #2c3e50;
            --light-color: #ecf0f1;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .sidebar {
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
            min-height: 100vh;
            padding: 20px 0;
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar-brand {
            padding: 20px;
            text-align: center;
            border-bottom: 2px solid rgba(46, 204, 113, 0.2);
            margin-bottom: 20px;
        }
        
        .sidebar-brand h4 {
            color: #2ecc71;
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 18px;
        }
        
        .sidebar-brand p {
            color: #ecf0f1;
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
            padding: 15px 25px;
            color: #bdc3c7;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }
        
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background-color: rgba(46, 204, 113, 0.1);
            color: #2ecc71;
            border-left-color: #2ecc71;
        }
        
        .sidebar-menu i {
            width: 25px;
            text-align: center;
            margin-right: 15px;
        }
        
        .sidebar-menu .badge {
            margin-left: auto;
        }
        
        .topnav {
            background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: 250px;
            right: 0;
            z-index: 999;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .topnav h2 {
            margin: 0;
            font-size: 22px;
            font-weight: bold;
        }
        
        .topnav-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .topnav-user {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }
        
        .topnav-user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        
        .main-content {
            margin-left: 250px;
            margin-top: 70px;
            padding: 30px;
            min-height: calc(100vh - 70px);
        }
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }
        
        .card:hover {
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.12);
        }
        
        .stat-card {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--success-color) 100%);
            color: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(46, 204, 113, 0.2);
        }
        
        .stat-card-blue {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #2980b9 100%);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.2);
        }
        
        .stat-card-orange {
            background: linear-gradient(135deg, var(--warning-color) 0%, #e67e22 100%);
            box-shadow: 0 5px 15px rgba(243, 156, 18, 0.2);
        }
        
        .stat-card-red {
            background: linear-gradient(135deg, var(--danger-color) 0%, #c0392b 100%);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.2);
        }
        
        .stat-card h3 {
            margin: 0;
            font-size: 14px;
            opacity: 0.9;
        }
        
        .stat-card .stat-value {
            font-size: 32px;
            font-weight: bold;
            margin: 10px 0 0 0;
        }
        
        .stat-card i {
            font-size: 40px;
            opacity: 0.3;
            float: right;
        }
        
        .table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .table thead {
            background: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
        }
        
        .table th {
            padding: 15px;
            color: #495057;
            font-weight: 600;
            border: none;
        }
        
        .table td {
            padding: 15px;
            border-color: #e9ecef;
            vertical-align: middle;
        }
        
        .btn {
            border-radius: 6px;
            padding: 8px 15px;
            font-size: 13px;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: var(--primary-color);
            border: none;
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--success-color);
            color: white;
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: var(--secondary-color);
            border: none;
            color: white;
        }
        
        .btn-info {
            background: var(--info-color);
            border: none;
            color: white;
        }
        
        .btn-danger {
            background: var(--danger-color);
            border: none;
            color: white;
        }
        
        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .alert {
            border-radius: 8px;
            border: none;
            padding: 15px 20px;
        }
        
        .alert-success {
            background: rgba(46, 204, 113, 0.1);
            color: #27ae60;
        }
        
        .alert-danger {
            background: rgba(231, 76, 60, 0.1);
            color: #c0392b;
        }
        
        .alert-warning {
            background: rgba(243, 156, 18, 0.1);
            color: #d68910;
        }
        
        .alert-info {
            background: rgba(52, 152, 219, 0.1);
            color: #2980b9;
        }
        
        .modal-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--success-color) 100%);
            color: white;
            border: none;
        }
        
        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }
        
        .form-label {
            color: #495057;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .form-control {
            border-radius: 6px;
            border: 1px solid #dee2e6;
            padding: 10px 15px;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(46, 204, 113, 0.25);
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                overflow: hidden;
            }
            
            .topnav {
                left: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .topnav h2 {
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
