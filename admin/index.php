<?php
require_once __DIR__ . '/auth.php';
requireAdminAuth(false);

$admin = getLoggedInAdmin();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - EduMarket</title>
    <link rel="icon" type="image/svg+xml" href="../assets/images/favicon.svg">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        :root {
            --primary: #1E2A5A;
            --primary-dark: #16204A;
            --primary-light: #E8EAF4;
            --secondary: #D97706;
            --secondary-dark: #92400E;
            --dark: #1C2340;
            --dark-2: #16204A;
            --dark-3: #16204A;
            --sidebar-bg: #16204A;
            --body-bg: #F7F3EC;
            --card-bg: #FFFFFF;
            --text-main: #1C2340;
            --text-muted: #5A6178;
            --border-color: #E3DDCF;
            --success: #2F7D5B;
            --warning: #D97706;
            --danger: #B3402E;
            --info: #1E2A5A;
            --brand-solid: #1E2A5A;
            --sidebar-solid: #16204A;
            --shadow-sm: 0 1px 2px rgba(28,35,64,0.06);
            --shadow-md: 0 4px 12px rgba(28,35,64,0.08);
            --shadow-lg: 0 10px 25px rgba(28,35,64,0.10);
            --border-radius: 10px;
            --sidebar-width: 260px;
        }

        body.dark-mode {
            --body-bg: #14182E;
            --card-bg: #1C2340;
            --text-main: #EDEAE2;
            --text-muted: #B9BECF;
            --border-color: #2E355A;
            --sidebar-bg: #16204A;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, sans-serif;
        }

        body {
            background-color: var(--body-bg);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
            transition: background-color 0.3s ease, color 0.3s ease;
            line-height: 1.65;
        }

        h1, h2, h3, .page-title, .brand-text, .stat-info h3, .modal-header-bar h3 {
            font-family: 'Fraunces', serif;
            letter-spacing: -0.02em;
            line-height: 1.15;
        }

        /* ---------------- Sidebar ---------------- */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-solid);
            color: #FFFFFF;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1000;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 4px 0 12px rgba(28,35,64,0.08);
        }

        .sidebar-header {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: #FFFFFF;
        }

        .brand-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--brand-solid);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: #FFFFFF;
        }

        .brand-text {
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .brand-text span {
            color: var(--secondary);
        }

        .sidebar-close-btn {
            display: none;
            background: none;
            border: none;
            color: #FFFFFF;
            font-size: 1.2rem;
            cursor: pointer;
        }

        .sidebar-menu {
            flex: 1;
            padding: 20px 12px;
            overflow-y: auto;
            list-style: none;
        }

        .menu-category {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255, 255, 255, 0.4);
            padding: 12px 14px 6px;
        }

        .nav-item {
            margin-bottom: 4px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 16px;
            border-radius: 10px;
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
            font-size: 0.92rem;
            font-weight: 600;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .nav-link i {
            font-size: 1.1rem;
            width: 22px;
            text-align: center;
            color: rgba(255, 255, 255, 0.6);
            transition: color 0.2s;
        }

        .nav-link:hover {
            color: #FFFFFF;
            background: rgba(255, 255, 255, 0.08);
        }

        .nav-link:hover i {
            color: var(--secondary);
        }

        .nav-link.active {
            color: #FFFFFF;
            background: var(--brand-solid);
            box-shadow: none;
        }

        .nav-link.active i {
            color: #FFFFFF;
        }

        .nav-badge {
            margin-left: auto;
            background: rgba(255, 255, 255, 0.2);
            color: #FFFFFF;
            font-size: 0.75rem;
            padding: 2px 8px;
            border-radius: 20px;
            font-weight: 700;
        }

        .sidebar-footer {
            padding: 16px 18px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--brand-solid);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #FFFFFF;
            font-size: 0.9rem;
        }

        .user-meta h4 {
            font-size: 0.88rem;
            font-weight: 700;
            color: #FFFFFF;
        }

        .user-meta span {
            font-size: 0.72rem;
            color: var(--secondary);
            font-weight: 600;
            text-transform: uppercase;
        }

        .logout-btn {
            background: rgba(255, 255, 255, 0.08);
            border: none;
            color: #B3402E;
            width: 34px;
            height: 34px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .logout-btn:hover {
            background: #B3402E;
            color: #FFFFFF;
        }

        /* ---------------- Main Content ---------------- */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            width: calc(100% - var(--sidebar-width));
            transition: margin-left 0.3s ease, width 0.3s ease;
        }

        /* Topbar */
        .topbar {
            height: 70px;
            background: var(--card-bg);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            position: sticky;
            top: 0;
            z-index: 900;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .menu-toggle-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.25rem;
            color: var(--text-main);
            cursor: pointer;
        }

        .page-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--text-main);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .btn-view-site {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: #E8EAF4;
            color: var(--primary);
            border-radius: 8px;
            font-size: 0.88rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-view-site:hover {
            background: var(--primary);
            color: #FFFFFF;
        }

        .theme-toggle-btn {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: var(--body-bg);
            border: 1px solid var(--border-color);
            color: var(--text-main);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            transition: all 0.2s;
        }

        .theme-toggle-btn:hover {
            color: var(--primary);
            border-color: var(--primary);
        }

        /* Content Area */
        .content-area {
            padding: 28px;
            flex: 1;
        }

        .tab-pane {
            display: none;
            animation: fadeIn 0.25s ease-in-out;
        }

        .tab-pane.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ---------------- Dashboard Cards & Stats ---------------- */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 20px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            padding: 22px;
            display: flex;
            align-items: center;
            gap: 18px;
            box-shadow: var(--shadow-sm);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .stat-icon.purple, .stat-icon.teal, .stat-icon.coral, .stat-icon.amber {
            background: #E8EAF4;
            color: #1E2A5A;
        }

        .stat-info h3 {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--text-main);
            line-height: 1.2;
        }

        .stat-info p {
            font-size: 0.85rem;
            color: var(--text-muted);
            font-weight: 500;
            margin-top: 4px;
        }

        /* Section Cards */
        .content-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            padding: 24px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 28px;
        }

        .card-header-flex {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 22px;
        }

        .card-header-flex h3 {
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-header-flex h3 i {
            color: var(--primary);
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--brand-solid);
            color: #FFFFFF;
            box-shadow: none;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            background: #16204A;
            box-shadow: var(--shadow-sm);
        }

        .btn-secondary {
            background: #FFFFFF;
            color: var(--text-main);
            border: 1px solid #D8D2C2;
        }

        .btn-secondary:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        .btn-success { background: var(--success); color: #FFFFFF; }
        .btn-danger { background: var(--danger); color: #FFFFFF; }
        .btn-sm { padding: 6px 12px; font-size: 0.8rem; border-radius: 8px; }

        /* Tables */
        .table-responsive {
            overflow-x: auto;
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.9rem;
        }

        table.data-table th {
            background: rgba(30, 42, 90, 0.05);
            padding: 14px 16px;
            font-weight: 700;
            color: var(--text-main);
            border-bottom: 2px solid var(--border-color);
            white-space: nowrap;
        }

        table.data-table td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-main);
            vertical-align: middle;
        }

        table.data-table tr:hover {
            background: rgba(30, 42, 90, 0.02);
        }

        /* Product image thumbnail in table */
        .prod-thumb {
            width: 48px;
            height: 48px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid var(--border-color);
            background: #EFE9DC;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 700;
            text-align: center;
        }

        .badge-success { background: #E6F0EB; color: #2F7D5B; }
        .badge-warning { background: #FDF0D5; color: #92400E; }
        .badge-danger { background: #F7E6E1; color: #B3402E; }
        .badge-info { background: #E8EAF4; color: #1E2A5A; }
        .badge-purple { background: #E8EAF4; color: #1E2A5A; }

        /* Search & Filter Bar */
        .filter-bar {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .search-box {
            position: relative;
            min-width: 240px;
        }

        .search-box input {
            width: 100%;
            padding: 9px 12px 9px 36px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: var(--card-bg);
            color: var(--text-main);
            font-size: 0.88rem;
            outline: none;
        }

        .search-box i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
        }

        .filter-select {
            padding: 9px 14px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: var(--card-bg);
            color: var(--text-main);
            font-size: 0.88rem;
            outline: none;
        }

        /* Payment Methods Grid */
        .payments-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .payment-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            padding: 22px;
            position: relative;
            transition: all 0.2s;
        }

        .payment-card:hover {
            box-shadow: var(--shadow-md);
        }

        .payment-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .payment-icon-wrapper {
            width: 46px;
            height: 46px;
            border-radius: 10px;
            background: #E8EAF4;
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }

        .switch-toggle {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
        }

        .switch-toggle input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: #CBD5E1;
            transition: .3s;
            border-radius: 24px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .3s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: var(--success);
        }

        input:checked + .slider:before {
            transform: translateX(20px);
        }

        .payment-card h4 {
            font-size: 1.05rem;
            font-weight: 700;
            margin-bottom: 6px;
            color: var(--text-main);
        }

        .payment-acc-num {
            font-family: monospace;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary);
            letter-spacing: 1px;
            margin-bottom: 4px;
        }

        .payment-holder {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: 12px;
        }

        .payment-instructions {
            font-size: 0.82rem;
            color: var(--text-muted);
            background: var(--body-bg);
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 16px;
            line-height: 1.5;
        }

        /* ---------------- Modal Base ---------------- */
        .custom-modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            
            z-index: 2000;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .custom-modal.active {
            display: flex;
        }

        .modal-content-box {
            background: var(--card-bg);
            border-radius: var(--border-radius);
            width: 100%;
            max-width: 700px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 10px 25px rgba(28,35,64,0.10);
            border: 1px solid var(--border-color);
            animation: modalPop 0.25s ease-out;
        }

        @keyframes modalPop {
            from { opacity: 0; transform: scale(0.94); }
            to { opacity: 1; transform: scale(1); }
        }

        .modal-header-bar {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-header-bar h3 {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--text-main);
        }

        .modal-close-btn {
            background: none;
            border: none;
            font-size: 1.2rem;
            color: var(--text-muted);
            cursor: pointer;
            transition: color 0.2s;
        }

        .modal-close-btn:hover {
            color: var(--danger);
        }

        .modal-body-content {
            padding: 24px;
        }

        .modal-footer-bar {
            padding: 16px 24px;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            background: rgba(0,0,0,0.01);
        }

        /* Form elements */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 16px;
        }

        .form-group-admin {
            margin-bottom: 16px;
        }

        .form-group-admin label {
            display: block;
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 6px;
        }

        .form-group-admin input,
        .form-group-admin select,
        .form-group-admin textarea {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: var(--card-bg);
            color: var(--text-main);
            font-size: 0.9rem;
            outline: none;
            transition: border-color 0.2s;
        }

        .form-group-admin input:focus,
        .form-group-admin select:focus,
        .form-group-admin textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(30, 42, 90, 0.15);
        }

        .upload-dropzone {
            border: 2px dashed var(--border-color);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            background: var(--body-bg);
        }

        .upload-dropzone:hover {
            border-color: var(--primary);
            background: rgba(30, 42, 90, 0.04);
        }

        .preview-img-box {
            width: 90px;
            height: 90px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            overflow: hidden;
            display: none;
            margin-top: 10px;
        }

        .preview-img-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Responsive Breakpoints */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.mobile-open {
                transform: translateX(0);
            }
            .main-wrapper {
                margin-left: 0;
                width: 100%;
            }
            .menu-toggle-btn {
                display: block;
            }
            .sidebar-close-btn {
                display: block;
            }
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <!-- ==================== SIDEBAR ==================== -->
    <aside class="sidebar" id="adminSidebar">
        <div class="sidebar-header">
            <a href="index.php" class="sidebar-brand" id="adminSidebarBrand">
                <div class="brand-icon" id="adminSidebarBrandIcon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <img src="" alt="Logo" id="adminSidebarBrandImg" style="display: none; height: 36px; max-width: 140px; object-fit: contain; border-radius: 6px;">
                <div class="brand-text" id="adminSidebarBrandText">Edu<span>Market</span></div>
            </a>
            <button class="sidebar-close-btn" id="sidebarCloseBtn" aria-label="Tutup Menu">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <ul class="sidebar-menu">
            <li class="menu-category">Ringkasan</li>
            <li class="nav-item">
                <a class="nav-link active" data-tab="tab-dashboard">
                    <i class="fas fa-chart-pie"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="menu-category">Katalog & Penjualan</li>
            <li class="nav-item">
                <a class="nav-link" data-tab="tab-products">
                    <i class="fas fa-box-open"></i>
                    <span>Kelola Produk</span>
                    <span class="nav-badge" id="sidebarProdCount">0</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-tab="tab-orders">
                    <i class="fas fa-shopping-bag"></i>
                    <span>Data Pesanan</span>
                    <span class="nav-badge" id="sidebarOrderCount">0</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-tab="tab-payments">
                    <i class="fas fa-credit-card"></i>
                    <span>Metode Bayar</span>
                </a>
            </li>

            <li class="menu-category">Interaksi Pengguna</li>
            <li class="nav-item">
                <a class="nav-link" data-tab="tab-reviews">
                    <i class="fas fa-star"></i>
                    <span>Ulasan Produk</span>
                </a>
            </li>

            <li class="menu-category">Pengaturan Toko</li>
            <li class="nav-item">
                <a class="nav-link" data-tab="tab-store-profile" id="sidebarStoreProfileLink">
                    <i class="fas fa-store"></i>
                    <span>Profil Toko</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-tab="tab-whatsapp" id="sidebarWhatsAppLink">
                    <i class="fab fa-whatsapp" style="color: #25D366;"></i>
                    <span>WhatsApp Gateway</span>
                </a>
            </li>

            <li class="menu-category">Akun & Sistem</li>
            <li class="nav-item">
                <a class="nav-link" data-tab="tab-settings" id="sidebarSettingsLink">
                    <i class="fas fa-key"></i>
                    <span>Ganti Password</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <div class="user-info" id="sidebarUserProfile" style="cursor: pointer;" title="Klik untuk Pengaturan Akun & Kata Sandi">
                <div class="user-avatar">
                    <?= strtoupper(substr($admin['name'] ?? 'A', 0, 1)) ?>
                </div>
                <div class="user-meta">
                    <h4><?= htmlspecialchars($admin['name'] ?? 'Admin') ?></h4>
                    <span><?= htmlspecialchars($admin['role'] ?? 'superadmin') ?></span>
                </div>
            </div>
            <a href="logout.php" class="logout-btn" title="Keluar dari Panel Admin">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </aside>

    <!-- ==================== MAIN CONTENT ==================== -->
    <main class="main-wrapper">
        <!-- Topbar -->
        <header class="topbar">
            <div class="topbar-left">
                <button class="menu-toggle-btn" id="menuToggleBtn" aria-label="Toggle Menu Sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <h2 class="page-title" id="pageTitleText">Dashboard</h2>
            </div>
            <div class="topbar-right">
                <button class="btn btn-sm btn-secondary" id="btnTopbarChangePassword" title="Ganti Password Admin" style="display: inline-flex; align-items: center; gap: 6px; padding: 7px 13px;">
                    <i class="fas fa-key" style="color: var(--primary);"></i>
                    <span>Ganti Password</span>
                </button>
                <a href="../index.html" target="_blank" class="btn-view-site">
                    <i class="fas fa-external-link-alt"></i>
                    <span>Lihat Website</span>
                </a>
                <button class="theme-toggle-btn" id="themeToggleBtn" aria-label="Toggle Dark Mode" title="Ganti Mode Gelap / Terang">
                    <i class="fas fa-moon"></i>
                </button>
            </div>
        </header>

        <!-- Dynamic Content Area -->
        <div class="content-area">

            <!-- ================= TAB 1: DASHBOARD OVERVIEW ================= -->
            <div class="tab-pane active" id="tab-dashboard">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon purple">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="stat-info">
                            <h3 id="statTotalProducts">0</h3>
                            <p>Total Produk Digital</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon teal">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div class="stat-info">
                            <h3 id="statTotalRevenue">Rp 0</h3>
                            <p>Total Estimasi Omset</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon coral">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="stat-info">
                            <h3 id="statTotalOrders">0</h3>
                            <p>Pesanan Masuk</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon amber">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-info">
                            <h3 id="statPendingOrders">0</h3>
                            <p>Menunggu Konfirmasi</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions & Recent Orders -->
                <div class="content-card">
                    <div class="card-header-flex">
                        <h3><i class="fas fa-bolt"></i> Aksi Cepat</h3>
                    </div>
                    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                        <button class="btn btn-primary" id="btnQuickAddProduct">
                            <i class="fas fa-plus-circle"></i> Tambah Produk Baru
                        </button>
                        <button class="btn btn-secondary" id="btnQuickGoPayments">
                            <i class="fas fa-cog"></i> Atur Rekening Pembayaran
                        </button>
                        <button class="btn btn-secondary" id="btnQuickGoOrders">
                            <i class="fas fa-list-check"></i> Cek Pesanan Masuk
                        </button>
                        <button class="btn btn-secondary" id="btnQuickGoStoreProfile">
                            <i class="fas fa-store"></i> Edit Profil Toko
                        </button>
                    </div>
                </div>

                <div class="content-card">
                    <div class="card-header-flex">
                        <h3><i class="fas fa-clock-rotate-left"></i> Transaksi & Pesanan Terbaru</h3>
                        <button class="btn btn-sm btn-secondary" id="btnViewAllOrders">Lihat Semua</button>
                    </div>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>No. Invoice</th>
                                    <th>Nama Pembeli</th>
                                    <th>Produk</th>
                                    <th>Total Tagihan</th>
                                    <th>Metode</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="dashboardOrdersTable">
                                <tr>
                                    <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 24px;">Memuat data transaksi...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ================= TAB 2: KELOLA PRODUK ================= -->
            <div class="tab-pane" id="tab-products">
                <div class="content-card">
                    <div class="card-header-flex">
                        <h3><i class="fas fa-boxes-stacked"></i> Daftar Produk Digital</h3>
                        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                            <div class="filter-bar">
                                <div class="search-box">
                                    <i class="fas fa-search"></i>
                                    <input type="text" id="prodSearchInput" placeholder="Cari nama produk...">
                                </div>
                                <select id="prodCategoryFilter" class="filter-select">
                                    <option value="all">Semua Kategori</option>
                                    <option value="aplikasi-sekolah">Aplikasi Sekolah</option>
                                    <option value="e-learning">E-Learning</option>
                                    <option value="sistem-informasi">Sistem Informasi</option>
                                    <option value="aplikasi-pembelajaran">Pembelajaran</option>
                                </select>
                            </div>
                            <button class="btn btn-primary" id="btnOpenAddProductModal">
                                <i class="fas fa-plus"></i> Tambah Produk
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Foto</th>
                                    <th>Nama Produk & Slug</th>
                                    <th>Kategori</th>
                                    <th>Harga Normal</th>
                                    <th>Harga Diskon</th>
                                    <th>Status</th>
                                    <th>Pilihan</th>
                                    <th>Terjual</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="productsTableBody">
                                <tr>
                                    <td colspan="9" style="text-align: center; color: var(--text-muted); padding: 24px;">Memuat produk...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ================= TAB 3: DATA PESANAN ================= -->
            <div class="tab-pane" id="tab-orders">
                <div class="content-card">
                    <div class="card-header-flex">
                        <h3><i class="fas fa-receipt"></i> Manajemen Pesanan & Transaksi</h3>
                        <div class="filter-bar">
                            <div class="search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" id="orderSearchInput" placeholder="Cari invoice / pembeli...">
                            </div>
                            <select id="orderStatusFilter" class="filter-select">
                                <option value="all">Semua Status</option>
                                <option value="pending">Menunggu Pembayaran (Pending)</option>
                                <option value="paid">Lunas (Paid)</option>
                                <option value="expired">Kedaluwarsa (Expired)</option>
                                <option value="refunded">Dibatalkan (Refunded)</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>No. Invoice</th>
                                    <th>Tanggal</th>
                                    <th>Customer & Kontak</th>
                                    <th>Produk Edukasi</th>
                                    <th>Jumlah Bayar</th>
                                    <th>Metode</th>
                                    <th>Status Pembayaran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="ordersTableBody">
                                <tr>
                                    <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 24px;">Memuat transaksi...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ================= TAB 4: METODE PEMBAYARAN ================= -->
            <div class="tab-pane" id="tab-payments">
                <div class="content-card">
                    <div class="card-header-flex">
                        <div>
                            <h3><i class="fas fa-credit-card"></i> Pengaturan Metode Pembayaran</h3>
                            <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 4px;">
                                Atur nomor rekening bank tujuan, QRIS, e-wallet, dan instruksi bayar yang tampil pada halaman checkout pembeli.
                            </p>
                        </div>
                        <button class="btn btn-primary" id="btnOpenAddPaymentModal">
                            <i class="fas fa-plus"></i> Tambah Metode Baru
                        </button>
                    </div>

                    <div class="payments-grid" id="paymentsGrid">
                        <!-- Loaded dynamically -->
                    </div>
                </div>
            </div>

            <!-- ================= TAB 5: ULASAN ================= -->
            <div class="tab-pane" id="tab-reviews">
                <div class="content-card">
                    <div class="card-header-flex">
                        <h3><i class="fas fa-comments"></i> Ulasan Pembeli Produk</h3>
                    </div>
                    <div id="reviewsListContainer" style="display: flex; flex-direction: column; gap: 14px;">
                        <!-- Loaded dynamically -->
                    </div>
                </div>
            </div>

            <!-- ================= TAB 6: PENGATURAN & GANTI PASSWORD ================= -->
            <div class="tab-pane" id="tab-settings">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 24px;">
                    <!-- Card 1: Ganti Password -->
                    <div class="content-card" style="margin-bottom: 0;">
                        <div class="card-header-flex">
                            <div>
                                <h3><i class="fas fa-shield-alt"></i> Ganti Kata Sandi Admin</h3>
                                <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 4px;">
                                    Perbarui kata sandi akun Anda secara berkala untuk menjaga keamanan data.
                                </p>
                            </div>
                        </div>

                        <form id="changePasswordForm">
                            <div class="form-group-admin">
                                <label for="settingCurrentPassword">Kata Sandi Saat Ini <span style="color: #B3402E;">*</span></label>
                                <div style="position: relative; display: flex; align-items: center;">
                                    <input type="password" id="settingCurrentPassword" required placeholder="Masukkan kata sandi lama" style="padding-right: 44px;">
                                    <button type="button" class="toggle-pass-visibility" data-target="settingCurrentPassword" style="position: absolute; right: 12px; background: none; border: none; color: var(--text-muted); cursor: pointer;" aria-label="Toggle Password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="form-group-admin">
                                <label for="settingNewPassword">Kata Sandi Baru <span style="color: #B3402E;">*</span></label>
                                <div style="position: relative; display: flex; align-items: center;">
                                    <input type="password" id="settingNewPassword" required placeholder="Minimal 6 karakter" style="padding-right: 44px;">
                                    <button type="button" class="toggle-pass-visibility" data-target="settingNewPassword" style="position: absolute; right: 12px; background: none; border: none; color: var(--text-muted); cursor: pointer;" aria-label="Toggle Password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <small style="font-size: 0.78rem; color: var(--text-muted);">Gunakan kombinasi huruf, angka, atau simbol agar lebih aman.</small>
                            </div>

                            <div class="form-group-admin">
                                <label for="settingConfirmPassword">Ulangi Kata Sandi Baru <span style="color: #B3402E;">*</span></label>
                                <div style="position: relative; display: flex; align-items: center;">
                                    <input type="password" id="settingConfirmPassword" required placeholder="Ketik ulang kata sandi baru" style="padding-right: 44px;">
                                    <button type="button" class="toggle-pass-visibility" data-target="settingConfirmPassword" style="position: absolute; right: 12px; background: none; border: none; color: var(--text-muted); cursor: pointer;" aria-label="Toggle Password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div style="margin-top: 24px;">
                                <button type="submit" class="btn btn-primary" id="btnSubmitChangePassword" style="width: 100%;">
                                    <span id="btnChangePassText"><i class="fas fa-save"></i> Simpan Kata Sandi Baru</span>
                                    <span id="btnChangePassSpinner" style="display: none;"><i class="fas fa-spinner fa-spin"></i> Menyimpan...</span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Card 2: Profil & Informasi Akun -->
                    <div class="content-card" style="margin-bottom: 0;">
                        <div class="card-header-flex">
                            <div>
                                <h3><i class="fas fa-user-gear"></i> Informasi Akun Administrator</h3>
                                <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 4px;">
                                    Data akun yang sedang aktif digunakan.
                                </p>
                            </div>
                        </div>

                        <div style="background: var(--body-bg); border-radius: 10px; padding: 22px; margin-bottom: 20px; border: 1px solid var(--border-color);">
                            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 18px;">
                                <div style="width: 58px; height: 58px; border-radius: 50%; background: var(--brand-solid); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; font-weight: 800; box-shadow: none;">
                                    <?= strtoupper(substr($admin['name'] ?? 'A', 0, 1)) ?>
                                </div>
                                <div>
                                    <h4 id="profileDisplayName" style="font-size: 1.15rem; font-weight: 800; color: var(--text-main); margin-bottom: 4px;"><?= htmlspecialchars($admin['name'] ?? 'Admin') ?></h4>
                                    <span class="badge badge-purple"><?= htmlspecialchars($admin['role'] ?? 'superadmin') ?></span>
                                </div>
                            </div>

                            <div style="font-size: 0.9rem; line-height: 2; color: var(--text-muted);">
                                <div><strong style="color: var(--text-main);">Username:</strong> <code style="color: var(--primary); font-weight: 700; background: #E8EAF4; padding: 2px 8px; border-radius: 4px;"><?= htmlspecialchars($admin['username'] ?? 'admin') ?></code></div>
                                <div><strong style="color: var(--text-main);">Email:</strong> <span id="profileDisplayEmail"><?= htmlspecialchars($admin['email'] ?? 'admin@edumarket.id') ?></span></div>
                                <div><strong style="color: var(--text-main);">Hak Akses:</strong> Full Access (Kelola Produk, Pesanan, Pembayaran)</div>
                                <div><strong style="color: var(--text-main);">Status Akun:</strong> <span class="badge badge-success">Terverifikasi</span></div>
                            </div>
                        </div>

                        <div style="padding: 16px; background: rgba(30, 42, 90, 0.08); border-radius: 10px; border: 1px dashed rgba(30, 42, 90, 0.3); font-size: 0.85rem; color: var(--text-main); line-height: 1.6;">
                            <div style="display: flex; align-items: flex-start; gap: 10px;">
                                <i class="fas fa-lightbulb" style="color: var(--primary); font-size: 1.1rem; margin-top: 2px;"></i>
                                <div>
                                    <strong>Petunjuk Keamanan:</strong><br>
                                    Setelah mengganti kata sandi, pastikan Anda mengingat atau mencatat kata sandi baru. Sistem akan meminta Anda memasukkan kata sandi baru pada sesi login selanjutnya.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================= TAB 7: PROFIL TOKO ================= -->
            <div class="tab-pane" id="tab-store-profile">
                <form id="storeProfileForm">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 24px;">
                        
                        <!-- Card 1: Identitas & Logo Toko -->
                        <div class="content-card" style="margin-bottom: 0;">
                            <div class="card-header-flex">
                                <div>
                                    <h3><i class="fas fa-store"></i> Identitas & Branding Marketplace</h3>
                                    <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 4px;">
                                        Nama toko, logo utama, favicon, dan deskripsi publik.
                                    </p>
                                </div>
                            </div>

                            <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                                <div class="form-group-admin">
                                    <label for="storeNamePrefix">Teks Utama Nama Toko (Warna Putih) <span style="color: #B3402E;">*</span></label>
                                    <input type="text" id="storeNamePrefix" required placeholder="Contoh: Edu">
                                    <small style="color: var(--text-muted); font-size: 0.78rem;">Bagian teks depan (putih terang)</small>
                                </div>
                                <div class="form-group-admin">
                                    <label for="storeNameSuffix">Teks Aksen Nama Toko (Warna Amber)</label>
                                    <input type="text" id="storeNameSuffix" placeholder="Contoh: Market">
                                    <small style="color: var(--text-muted); font-size: 0.78rem;">Bagian teks belakang (gradasi biru-cyan)</small>
                                </div>
                            </div>

                            <div class="form-group-admin">
                                <label for="storeName">Nama Lengkap Toko</label>
                                <input type="text" id="storeName" required placeholder="EduMarket" style="background: rgba(255,255,255,0.04);">
                            </div>

                            <!-- Live Brand Preview Card -->
                            <div class="form-group-admin" style="background: rgba(30, 42, 90, 0.08); border: 1.5px dashed rgba(30, 42, 90, 0.4); border-radius: var(--border-radius-sm); padding: 14px 18px; margin-bottom: 20px;">
                                <label style="margin-bottom: 8px; display: block; font-size: 0.85rem; color: var(--primary); font-weight: 700;">
                                    <i class="fas fa-eye"></i> Pratinjau Tampilan Logo & Nama Toko:
                                </label>
                                <div style="display: flex; align-items: center; gap: 12px; background: #16204A; padding: 12px 20px; border-radius: 8px; width: fit-content; min-width: 260px; box-shadow: none;">
                                    <span id="liveBrandIconPreview" style="font-size: 1.6rem; color: #D97706;"><i class="fas fa-graduation-cap"></i></span>
                                    <img id="liveBrandImgPreview" src="" alt="Logo" style="display: none; height: 36px; max-width: 140px; object-fit: contain;">
                                    <span id="liveBrandTextPreview" style="font-size: 1.4rem; font-weight: 800; color: #FFFFFF;">
                                        Edu<span style="color: #D97706;">Market</span>
                                    </span>
                                </div>
                                <small style="display: block; margin-top: 8px; color: var(--text-muted); font-size: 0.78rem;">
                                    <i class="fas fa-magic"></i> Tampilan di atas adalah simulasi langsung bagaimana logo dan teks toko Anda tampil di Navbar, Preloader awal, dan Footer website.
                                </small>
                            </div>

                            <div class="form-group-admin">
                                <label for="storeTagline">Slogan / Tagline Toko</label>
                                <input type="text" id="storeTagline" placeholder="Marketplace Aplikasi Pendidikan & Sekolah Digital">
                            </div>

                            <div class="form-group-admin">
                                <label for="storeDescription">Deskripsi Tentang Toko</label>
                                <textarea id="storeDescription" rows="3" placeholder="Deskripsi ringkas yang tampil pada profil dan footer toko..."></textarea>
                            </div>

                            <!-- Upload Logo Toko -->
                            <div class="form-group-admin">
                                <label>Logo Toko (Format SVG, PNG, JPG, WEBP)</label>
                                <div class="upload-dropzone" id="storeLogoDropzone">
                                    <i class="fas fa-image" style="font-size: 1.8rem; color: var(--primary); margin-bottom: 6px;"></i>
                                    <p style="font-size: 0.85rem; font-weight: 600;">Klik untuk upload logo toko</p>
                                    <small style="color: var(--text-muted);">Mendukung format SVG, PNG transparan, JPG, atau WEBP</small>
                                    <input type="file" id="storeLogoFileInput" accept="image/*" style="display: none;">
                                </div>
                                <input type="text" id="storeLogoUrl" placeholder="Path atau URL Logo Toko" style="margin-top: 8px; font-size: 0.82rem;">
                                <div class="preview-img-box" id="storeLogoPreview" style="display: block; width: 140px; height: 50px; background: rgba(0,0,0,0.05); padding: 4px;">
                                    <img id="storeLogoPreviewImg" src="../assets/images/favicon.svg" alt="Preview Logo" style="object-fit: contain;">
                                </div>
                            </div>

                            <!-- Upload Favicon Toko -->
                            <div class="form-group-admin">
                                <label>Favicon Toko (Ikon Browser Tab)</label>
                                <div class="upload-dropzone" id="storeFaviconDropzone" style="padding: 12px;">
                                    <p style="font-size: 0.82rem; font-weight: 600;"><i class="fas fa-upload"></i> Upload Favicon Toko</p>
                                    <input type="file" id="storeFaviconFileInput" accept="image/*" style="display: none;">
                                </div>
                                <input type="text" id="storeFaviconUrl" placeholder="Path atau URL Favicon" style="margin-top: 8px; font-size: 0.82rem;">
                            </div>
                        </div>

                        <!-- Card 2: Kontak & Operasional Toko -->
                        <div class="content-card" style="margin-bottom: 0;">
                            <div class="card-header-flex">
                                <div>
                                    <h3><i class="fas fa-headset"></i> Kontak & Jam Operasional</h3>
                                    <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 4px;">
                                        Saluran komunikasi resmi toko untuk melayani pelanggan.
                                    </p>
                                </div>
                            </div>

                            <div class="form-group-admin">
                                <label for="storeEmail">Email Dukungan Pelanggan</label>
                                <input type="email" id="storeEmail" placeholder="support@edumarket.id">
                            </div>

                            <div class="form-row">
                                <div class="form-group-admin">
                                    <label for="storeWhatsapp">Nomor WhatsApp Admin (Order)</label>
                                    <input type="text" id="storeWhatsapp" placeholder="6281234567890">
                                </div>
                                <div class="form-group-admin">
                                    <label for="storePhone">Nomor Telepon Kantor</label>
                                    <input type="text" id="storePhone" placeholder="+62 812-3456-7890">
                                </div>
                            </div>

                            <div class="form-group-admin">
                                <label for="storeAddress">Alamat Fisik / Lokasi Kantor</label>
                                <textarea id="storeAddress" rows="2" placeholder="Jl. Teknologi No. 123, Jakarta Selatan, Indonesia"></textarea>
                            </div>

                            <div class="form-group-admin">
                                <label for="storeOperatingHours">Jam Operasional Pelayanan</label>
                                <input type="text" id="storeOperatingHours" placeholder="Senin - Jumat: 08.00 - 17.00 WIB, Sabtu: 09.00 - 14.00 WIB">
                            </div>

                            <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 20px 0;">

                            <h4 style="font-size: 0.95rem; font-weight: 700; margin-bottom: 12px; color: var(--text-main);"><i class="fas fa-share-nodes" style="color: var(--primary);"></i> Tautan Media Sosial</h4>

                            <div class="form-row">
                                <div class="form-group-admin">
                                    <label for="storeInstagram"><i class="fab fa-instagram" style="color: #1E2A5A;"></i> Instagram</label>
                                    <input type="text" id="storeInstagram" placeholder="https://instagram.com/edumarket">
                                </div>
                                <div class="form-group-admin">
                                    <label for="storeFacebook"><i class="fab fa-facebook" style="color: #1E2A5A;"></i> Facebook</label>
                                    <input type="text" id="storeFacebook" placeholder="https://facebook.com/edumarket">
                                </div>
                            </div>

                            <div class="form-group-admin">
                                <label for="storeYoutube"><i class="fab fa-youtube" style="color: #1E2A5A;"></i> YouTube Channel</label>
                                <input type="text" id="storeYoutube" placeholder="https://youtube.com/@edumarket">
                            </div>

                            <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 20px 0;">

                            <h4 style="font-size: 0.95rem; font-weight: 700; margin-bottom: 12px; color: var(--text-main);"><i class="fas fa-shield-check" style="color: var(--primary);"></i> Kebijakan Garansi & Lisensi</h4>

                            <div class="form-row">
                                <div class="form-group-admin">
                                    <label for="storeWarranty">Jaminan Garansi</label>
                                    <input type="text" id="storeWarranty" placeholder="30 Hari Garansi">
                                </div>
                                <div class="form-group-admin">
                                    <label for="storeSupport">Layanan Support</label>
                                    <input type="text" id="storeSupport" placeholder="1 Tahun Update Gratis">
                                </div>
                            </div>

                            <div class="form-group-admin">
                                <label for="storeCopyright">Teks Hak Cipta (Footer)</label>
                                <input type="text" id="storeCopyright" placeholder="© 2026 EduAdminSP. Manage With by adangsp">
                            </div>
                        </div>

                    </div>

                    <div style="margin-top: 24px; display: flex; justify-content: flex-end;">
                        <button type="submit" class="btn btn-primary btn-lg" id="btnSaveStoreProfile" style="min-width: 220px;">
                            <span id="btnStoreProfileText"><i class="fas fa-save"></i> Simpan Profil Toko</span>
                            <span id="btnStoreProfileSpinner" style="display: none;"><i class="fas fa-spinner fa-spin"></i> Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- ================= TAB 8: WHATSAPP GATEWAY (WAHA) ================= -->
            <div class="tab-pane" id="tab-whatsapp">
                <!-- Status & Connection Banner -->
                <div class="content-card" style="margin-bottom: 24px;">
                    <div class="card-header-flex" style="flex-wrap: wrap; gap: 16px;">
                        <div style="display: flex; align-items: center; gap: 14px;">
                            <div style="width: 52px; height: 52px; border-radius: 12px; background: rgba(37, 211, 102, 0.12); display: flex; align-items: center; justify-content: center; color: #25D366; font-size: 1.8rem;">
                                <i class="fab fa-whatsapp"></i>
                            </div>
                            <div>
                                <h3 style="margin-bottom: 4px; display: flex; align-items: center; gap: 10px;">
                                    WhatsApp HTTP API (WAHA)
                                    <span id="waStatusBadge" class="status-badge" style="background: rgba(108,117,125,0.15); color: #6c757d; font-size: 0.75rem; padding: 4px 10px; border-radius: 20px;">
                                        <i class="fas fa-circle-notch fa-spin"></i> Memeriksa Status...
                                    </span>
                                </h3>
                                <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0;">
                                    Sesi: <strong id="waSessionNameDisplay">default</strong> | 
                                    Terhubung: <span id="waConnectedUser" style="color: var(--primary); font-weight: 600;">Belum terhubung</span>
                                </p>
                            </div>
                        </div>
                        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                            <button type="button" class="btn btn-secondary btn-sm" id="btnRefreshWaStatus">
                                <i class="fas fa-rotate"></i> Cek Status
                            </button>
                            <button type="button" class="btn btn-success btn-sm" id="btnStartWaSession" style="background: #25D366; border-color: #25D366; color: #fff;">
                                <i class="fas fa-play"></i> Mulai Sesi
                            </button>
                            <button type="button" class="btn btn-outline btn-sm" id="btnStopWaSession" style="color: #dc3545; border-color: #dc3545;">
                                <i class="fas fa-stop"></i> Hentikan Sesi
                            </button>
                        </div>
                    </div>

                    <!-- Live QR Code Container (shown when session status is SCAN_QR_CODE) -->
                    <div id="waQrContainer" style="display: none; margin-top: 20px; padding: 20px; background: rgba(37, 211, 102, 0.05); border: 1.5px dashed rgba(37, 211, 102, 0.4); border-radius: var(--border-radius); text-align: center;">
                        <h4 style="color: var(--text-main); margin-bottom: 8px; display: flex; align-items: center; justify-content: center; gap: 8px;">
                            <i class="fas fa-qrcode" style="color: #25D366;"></i> Scan QR Code WhatsApp
                        </h4>
                        <p style="font-size: 0.88rem; color: var(--text-muted); max-width: 600px; margin: 0 auto 16px;">
                            Buka WhatsApp di ponsel Anda &gt; Ketuk <strong>Menu (⋮) / Pengaturan</strong> &gt; <strong>Perangkat Tertaut</strong> &gt; <strong>Tautkan Perangkat</strong>, lalu arahkan kamera ke kode QR berikut:
                        </p>
                        <div style="display: inline-block; padding: 12px; background: #fff; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                            <img id="waQrImage" src="" alt="WhatsApp QR Code" style="width: 240px; height: 240px; display: block; object-fit: contain;">
                        </div>
                        <div style="margin-top: 12px;">
                            <span id="waQrRefreshIndicator" style="font-size: 0.8rem; color: var(--text-muted);">
                                <i class="fas fa-sync fa-spin"></i> Memperbarui otomatis setiap 5 detik...
                            </span>
                        </div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 24px;">
                    <!-- Card 1: Pengaturan Server & Sesi WAHA -->
                    <div class="content-card" style="margin-bottom: 0;">
                        <div class="card-header-flex">
                            <div>
                                <h3><i class="fas fa-server"></i> Konfigurasi Server WAHA</h3>
                                <p style="font-size: 0.82rem; color: var(--text-muted); margin: 0;">Parameter koneksi ke instance WAHA (Docker/VPS)</p>
                            </div>
                        </div>

                        <form id="waSettingsForm">
                            <div style="margin-bottom: 16px; padding: 12px; background: rgba(30, 42, 90, 0.06); border-radius: 8px; border: 1px solid rgba(30, 42, 90, 0.15);">
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-weight: 600; font-size: 0.92rem; margin: 0;">
                                    <input type="checkbox" id="waIsEnabled" style="width: 18px; height: 18px; cursor: pointer;">
                                    <span>Aktifkan WhatsApp Gateway</span>
                                </label>
                                <p style="font-size: 0.78rem; color: var(--text-muted); margin: 4px 0 0 28px;">
                                    Jika dinonaktifkan, sistem tidak akan mengirim pesan WhatsApp otomatis.
                                </p>
                            </div>

                            <div class="form-group-admin">
                                <label for="waApiUrl">WAHA API URL <span style="color: #B3402E;">*</span></label>
                                <input type="text" id="waApiUrl" required placeholder="http://localhost:3000">
                                <small style="font-size: 0.75rem; color: var(--text-muted);">Default: <code>http://localhost:3000</code> jika berjalan di Docker lokal.</small>
                            </div>

                            <div class="form-row">
                                <div class="form-group-admin">
                                    <label for="waSessionName">Nama Sesi WAHA <span style="color: #B3402E;">*</span></label>
                                    <input type="text" id="waSessionName" required placeholder="default">
                                    <small style="font-size: 0.75rem; color: var(--text-muted);">Default: <code>default</code></small>
                                </div>
                                <div class="form-group-admin">
                                    <label for="waApiKey">API Key (X-Api-Key)</label>
                                    <input type="text" id="waApiKey" placeholder="Opsional (jika diatur di WAHA)">
                                    <small style="font-size: 0.75rem; color: var(--text-muted);">Kosongkan jika WAHA tanpa auth.</small>
                                </div>
                            </div>

                            <div class="form-group-admin">
                                <label for="waAdminPhone">Nomor WhatsApp Admin (Alert Notifikasi) <span style="color: #B3402E;">*</span></label>
                                <input type="text" id="waAdminPhone" required placeholder="Contoh: 081234567890 atau 6281234567890">
                                <small style="font-size: 0.75rem; color: var(--text-muted);">Nomor untuk menerima notifikasi pesanan masuk dan pesan kontak.</small>
                            </div>

                            <div style="margin-top: 18px;">
                                <label style="font-weight: 600; font-size: 0.88rem; display: block; margin-bottom: 8px;">Trigger Notifikasi Otomatis:</label>
                                <div style="display: flex; flex-direction: column; gap: 8px; font-size: 0.85rem;">
                                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                        <input type="checkbox" id="waNotifyAdminOrder">
                                        <span>Kirim alert ke WhatsApp Admin saat ada <strong>Pesanan Baru</strong></span>
                                    </label>
                                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                        <input type="checkbox" id="waNotifyAdminContact">
                                        <span>Kirim alert ke WhatsApp Admin saat ada <strong>Pesan Kontak Baru</strong></span>
                                    </label>
                                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                        <input type="checkbox" id="waNotifyCustomerOrder">
                                        <span>Kirim invoice & instruksi ke Pelanggan saat <strong>Checkout Order</strong></span>
                                    </label>
                                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                        <input type="checkbox" id="waNotifyCustomerPaid">
                                        <span>Kirim link unduhan & lisensi ke Pelanggan saat status <strong>Lunas (Paid)</strong></span>
                                    </label>
                                </div>
                            </div>

                            <div style="margin-top: 20px;">
                                <button type="submit" class="btn btn-primary" id="btnSaveWaSettings" style="width: 100%;">
                                    <i class="fas fa-save"></i> Simpan Konfigurasi WAHA
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Column 2: Template Pesan & Test Pengiriman -->
                    <div style="display: flex; flex-direction: column; gap: 24px;">
                        <!-- Card 2: Test Pesan Real-time -->
                        <div class="content-card" style="margin-bottom: 0;">
                            <div class="card-header-flex">
                                <div>
                                    <h3><i class="fas fa-paper-plane"></i> Uji Coba Pengiriman Pesan</h3>
                                    <p style="font-size: 0.82rem; color: var(--text-muted); margin: 0;">Kirim pesan tes WhatsApp langsung ke ponsel Anda</p>
                                </div>
                            </div>
                            <form id="waTestMessageForm">
                                <div class="form-group-admin">
                                    <label for="waTestPhone">Nomor WhatsApp Penerima <span style="color: #B3402E;">*</span></label>
                                    <input type="text" id="waTestPhone" required placeholder="08xxxxxxxxxx atau 628xxxxxxxxxx">
                                </div>
                                <div class="form-group-admin">
                                    <label for="waTestText">Teks Pesan Uji Coba</label>
                                    <textarea id="waTestText" rows="3" placeholder="Halo! Ini pesan tes dari EduAdminSP WAHA Gateway..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-success" id="btnSendWaTest" style="background: #25D366; border-color: #25D366; color: #fff; width: 100%;">
                                    <span id="btnSendWaTestText"><i class="fas fa-paper-plane"></i> Kirim Pesan Tes</span>
                                    <span id="btnSendWaTestSpinner" style="display: none;"><i class="fas fa-spinner fa-spin"></i> Mengirim...</span>
                                </button>
                            </form>
                        </div>

                        <!-- Card 3: Panduan Docker WAHA -->
                        <div class="content-card" style="margin-bottom: 0;">
                            <div class="card-header-flex">
                                <div>
                                    <h3><i class="fab fa-docker" style="color: #1E2A5A;"></i> Cara Menjalankan WAHA via Docker</h3>
                                    <p style="font-size: 0.82rem; color: var(--text-muted); margin: 0;">Docker container siap pakai dari repository resmi</p>
                                </div>
                            </div>
                            <div style="font-size: 0.85rem; line-height: 1.6; color: var(--text-muted);">
                                <p style="margin-bottom: 8px;">Jalankan perintah ini di Command Prompt / Terminal:</p>
                                <div style="background: #1e1e2f; color: #a5b4fc; padding: 12px 14px; border-radius: 8px; font-family: monospace; font-size: 0.82rem; position: relative; margin-bottom: 12px; overflow-x: auto; border: 1px solid rgba(255,255,255,0.08);">
                                    <code>docker run -it -p 3000:3000/tcp --name waha devlikeapro/waha</code>
                                </div>
                                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                    <a href="http://localhost:3000/dashboard" target="_blank" class="btn btn-secondary btn-sm" style="font-size: 0.78rem;">
                                        <i class="fas fa-external-link-alt"></i> Buka Dashboard WAHA (:3000)
                                    </a>
                                    <a href="https://github.com/devlikeapro/waha.git" target="_blank" class="btn btn-secondary btn-sm" style="font-size: 0.78rem;">
                                        <i class="fab fa-github"></i> Repository GitHub WAHA
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Template Pesan (Card Lebar di Bawah) -->
                <div class="content-card" style="margin-top: 24px;">
                    <div class="card-header-flex">
                        <div>
                            <h3><i class="fas fa-comment-dots"></i> Template Pesan Otomatis</h3>
                            <p style="font-size: 0.82rem; color: var(--text-muted); margin: 0;">
                                Variabel tersedia: <code>{customer_name}</code>, <code>{order_number}</code>, <code>{product_name}</code>, <code>{amount}</code>, <code>{payment_method}</code>, <code>{download_link}</code>, <code>{license_key}</code>, <code>{store_name}</code>
                            </p>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px;">
                        <div class="form-group-admin">
                            <label for="waOrderTemplate">Template Order Baru (Pelanggan)</label>
                            <textarea id="waOrderTemplate" rows="5" placeholder="Halo *{customer_name}*, pesanan #{order_number}..."></textarea>
                        </div>
                        <div class="form-group-admin">
                            <label for="waPaidTemplate">Template Order Lunas (Pelanggan)</label>
                            <textarea id="waPaidTemplate" rows="5" placeholder="Pembayaran Dikonfirmasi! Link: {download_link} Lisensi: {license_key}..."></textarea>
                        </div>
                        <div class="form-group-admin">
                            <label for="waContactTemplate">Template Pesan Kontak (Admin)</label>
                            <textarea id="waContactTemplate" rows="5" placeholder="Pesan Baru dari {contact_name}: {contact_message}..."></textarea>
                        </div>
                    </div>
                    <div style="margin-top: 14px; text-align: right;">
                        <button type="button" class="btn btn-primary" id="btnSaveTemplates" style="min-width: 180px;">
                            <i class="fas fa-save"></i> Simpan Semua Template
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- ==================== MODAL TAMBAH / EDIT PRODUK ==================== -->
    <div class="custom-modal" id="productModal">
        <div class="modal-content-box">
            <div class="modal-header-bar">
                <h3 id="productModalTitle">Tambah Produk Baru</h3>
                <button class="modal-close-btn" id="closeProductModalBtn">&times;</button>
            </div>
            <form id="productForm">
                <input type="hidden" id="prodFormId" value="">
                <input type="hidden" id="prodFormAction" value="create">

                <div class="modal-body-content">
                    <div class="form-row">
                        <div class="form-group-admin">
                            <label for="prodName">Nama Produk Aplikasi <span style="color: #B3402E;">*</span></label>
                            <input type="text" id="prodName" required placeholder="Contoh: EduSchool Pro v3.0">
                        </div>
                        <div class="form-group-admin">
                            <label for="prodCategory">Kategori <span style="color: #B3402E;">*</span></label>
                            <select id="prodCategory" required>
                                <option value="aplikasi-sekolah">Aplikasi Sekolah</option>
                                <option value="e-learning">E-Learning & LMS</option>
                                <option value="sistem-informasi">Sistem Informasi</option>
                                <option value="aplikasi-pembelajaran">Aplikasi Pembelajaran</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group-admin">
                            <label for="prodPrice">Harga Normal (Rp) <span style="color: #B3402E;">*</span></label>
                            <input type="number" id="prodPrice" required placeholder="2500000" min="0">
                        </div>
                        <div class="form-group-admin">
                            <label for="prodDiscountPrice">Harga Diskon / Promo (Rp)</label>
                            <input type="number" id="prodDiscountPrice" placeholder="1999000 (Kosongkan jika tidak ada)" min="0">
                        </div>
                    </div>

                    <div class="form-group-admin">
                        <label for="prodShortDesc">Deskripsi Singkat (Tampil pada kartu produk)</label>
                        <input type="text" id="prodShortDesc" placeholder="Ringkasan 1-2 kalimat tentang kegunaan aplikasi">
                    </div>

                    <div class="form-group-admin">
                        <label for="prodDescription">Deskripsi Lengkap Produk</label>
                        <textarea id="prodDescription" rows="4" placeholder="Jelaskan secara detail keunggulan, alur kerja, dan manfaat aplikasi..."></textarea>
                    </div>

                    <!-- Foto Produk Upload / URL -->
                    <div class="form-group-admin">
                        <label>Foto Utama Produk</label>
                        <div class="upload-dropzone" id="prodImageDropzone">
                            <i class="fas fa-cloud-arrow-up" style="font-size: 1.8rem; color: var(--primary); margin-bottom: 8px;"></i>
                            <p style="font-size: 0.88rem; font-weight: 600;">Klik untuk upload file foto produk</p>
                            <small style="color: var(--text-muted);">Mendukung format JPG, PNG, WEBP (Maksimal 5MB)</small>
                            <input type="file" id="prodImageFileInput" accept="image/*" style="display: none;">
                        </div>
                        <div style="margin-top: 8px; display: flex; align-items: center; gap: 8px;">
                            <span style="font-size: 0.8rem; color: var(--text-muted);">Atau URL gambar:</span>
                            <input type="text" id="prodImageUrl" placeholder="https://..." style="flex: 1; padding: 6px 10px; font-size: 0.82rem;">
                        </div>
                        <div class="preview-img-box" id="prodImagePreview">
                            <img id="prodImagePreviewImg" src="" alt="Preview">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group-admin">
                            <label for="prodTechStack">Teknologi (Pisahkan dengan koma)</label>
                            <input type="text" id="prodTechStack" placeholder="PHP 8.x, MySQL, Bootstrap 5, AJAX">
                        </div>
                        <div class="form-group-admin">
                            <label for="prodDemoUrl">Tautan Demo Online (Opsional)</label>
                            <input type="text" id="prodDemoUrl" placeholder="https://demo.edumarket.id/app">
                        </div>
                    </div>

                    <div class="form-group-admin">
                        <label for="prodFeatures">Daftar Fitur Unggulan (1 baris per fitur)</label>
                        <textarea id="prodFeatures" rows="4" placeholder="Multi-Role Admin, Guru, Siswa&#10;Manajemen SPP & Tagihan Siswa&#10;Cetak Rapor Otomatis PDF"></textarea>
                    </div>

                    <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-top: 10px;">
                        <label style="display: flex; align-items: center; gap: 8px; font-size: 0.88rem; cursor: pointer;">
                            <input type="checkbox" id="prodIsBestseller"> <span style="font-weight: 600;">Tandai Terlaris (Best Seller)</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px; font-size: 0.88rem; cursor: pointer;">
                            <input type="checkbox" id="prodIsFeatured"> <span style="font-weight: 600;">Produk Pilihan (Featured)</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px; font-size: 0.88rem; cursor: pointer;">
                            <input type="checkbox" id="prodStatus" checked> <span style="font-weight: 600; color: var(--success);">Status Aktif Dijual</span>
                        </label>
                    </div>
                </div>

                <div class="modal-footer-bar">
                    <button type="button" class="btn btn-secondary" id="btnCancelProductModal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSaveProduct">
                        <i class="fas fa-save"></i> Simpan Produk
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ==================== MODAL METODE PEMBAYARAN ==================== -->
    <div class="custom-modal" id="paymentModal">
        <div class="modal-content-box" style="max-width: 540px;">
            <div class="modal-header-bar">
                <h3 id="paymentModalTitle">Edit Metode Pembayaran</h3>
                <button class="modal-close-btn" id="closePaymentModalBtn">&times;</button>
            </div>
            <form id="paymentForm">
                <input type="hidden" id="payFormId" value="">
                <input type="hidden" id="payFormAction" value="update">

                <div class="modal-body-content">
                    <div class="form-group-admin">
                        <label for="payMethodTitle">Nama Bank / E-Wallet / Metode <span style="color: #B3402E;">*</span></label>
                        <input type="text" id="payMethodTitle" required placeholder="Contoh: Bank Central Asia (BCA)">
                    </div>

                    <div class="form-group-admin">
                        <label for="payAccountNumber">Nomor Rekening / No. E-Wallet <span style="color: #B3402E;">*</span></label>
                        <input type="text" id="payAccountNumber" required placeholder="8270-1928-3341">
                    </div>

                    <div class="form-group-admin">
                        <label for="payAccountHolder">Atas Nama (Pemilik Rekening) <span style="color: #B3402E;">*</span></label>
                        <input type="text" id="payAccountHolder" required placeholder="PT EduMarket Digital Indonesia">
                    </div>

                    <div class="form-group-admin">
                        <label for="payInstructions">Petunjuk Transfer / Catatan Pembeli</label>
                        <textarea id="payInstructions" rows="3" placeholder="Transfer melalui ATM / mobile banking. Harap simpan bukti struk transfer Anda."></textarea>
                    </div>

                    <div class="form-group-admin">
                        <label>Foto Kode QRIS (Khusus metode QRIS)</label>
                        <div class="upload-dropzone" id="payQrDropzone">
                            <i class="fas fa-qrcode" style="font-size: 1.8rem; color: var(--primary); margin-bottom: 6px;"></i>
                            <p style="font-size: 0.85rem; font-weight: 600;">Klik untuk upload gambar QRIS</p>
                            <input type="file" id="payQrFileInput" accept="image/*" style="display: none;">
                        </div>
                        <input type="text" id="payQrImage" placeholder="URL path QRIS (opsional)" style="margin-top: 8px; font-size: 0.8rem;">
                    </div>

                    <div style="margin-top: 14px;">
                        <label style="display: flex; align-items: center; gap: 8px; font-size: 0.88rem; cursor: pointer;">
                            <input type="checkbox" id="payIsActive" checked> <span style="font-weight: 600; color: var(--success);">Aktifkan Metode Pembayaran Ini</span>
                        </label>
                    </div>
                </div>

                <div class="modal-footer-bar">
                    <button type="button" class="btn btn-secondary" id="btnCancelPaymentModal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSavePayment">
                        <i class="fas fa-save"></i> Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Admin Panel JavaScript -->
    <script>
        'use strict';

        // ---------------- State & Variables ----------------
        const state = {
            products: [],
            orders: [],
            payments: [],
            activeTab: 'tab-dashboard'
        };

        // Utility: Format Rupiah
        function formatRupiah(num) {
            if (!num && num !== 0) return 'Rp 0';
            return 'Rp ' + Number(num).toLocaleString('id-ID');
        }

        // ---------------- Tab Navigation ----------------
        const navLinks = document.querySelectorAll('.nav-link[data-tab]');
        const tabPanes = document.querySelectorAll('.tab-pane');
        const pageTitleText = document.getElementById('pageTitleText');

        const tabTitles = {
            'tab-dashboard': 'Dashboard',
            'tab-products': 'Manajemen Produk Digital',
            'tab-orders': 'Data Transaksi & Pesanan Masuk',
            'tab-payments': 'Pengaturan Metode Pembayaran',
            'tab-reviews': 'Ulasan & Rating Pengguna',
            'tab-settings': 'Pengaturan Akun & Ganti Password',
            'tab-store-profile': 'Pengaturan Profil Toko & Branding',
            'tab-whatsapp': 'Pengaturan WhatsApp Gateway (WAHA API)'
        };

        function switchTab(tabId) {
            navLinks.forEach(link => {
                if (link.getAttribute('data-tab') === tabId) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });

            tabPanes.forEach(pane => {
                if (pane.id === tabId) {
                    pane.classList.add('active');
                } else {
                    pane.classList.remove('active');
                }
            });

            state.activeTab = tabId;
            if (pageTitleText) {
                pageTitleText.textContent = tabTitles[tabId] || 'Admin Portal';
            }

            // Hook for WhatsApp tab activation/deactivation
            if (tabId === 'tab-whatsapp') {
                if (typeof loadWhatsAppSettings === 'function') loadWhatsAppSettings();
                if (typeof checkWhatsAppStatus === 'function') checkWhatsAppStatus();
            } else {
                if (typeof stopWaQrPolling === 'function') stopWaQrPolling();
            }

            // Close mobile sidebar if open
            document.getElementById('adminSidebar').classList.remove('mobile-open');
        }

        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                const tab = link.getAttribute('data-tab');
                switchTab(tab);
            });
        });

        // Topbar & sidebar profile trigger for settings
        const btnTopbarChangePassword = document.getElementById('btnTopbarChangePassword');
        if (btnTopbarChangePassword) {
            btnTopbarChangePassword.addEventListener('click', () => switchTab('tab-settings'));
        }

        const sidebarUserProfile = document.getElementById('sidebarUserProfile');
        if (sidebarUserProfile) {
            sidebarUserProfile.addEventListener('click', () => switchTab('tab-settings'));
        }

        // Quick action buttons on dashboard
        document.getElementById('btnQuickAddProduct').addEventListener('click', () => {
            switchTab('tab-products');
            openProductModal('create');
        });
        document.getElementById('btnQuickGoPayments').addEventListener('click', () => switchTab('tab-payments'));
        document.getElementById('btnQuickGoOrders').addEventListener('click', () => switchTab('tab-orders'));
        document.getElementById('btnViewAllOrders').addEventListener('click', () => switchTab('tab-orders'));
        const btnQuickGoStoreProfile = document.getElementById('btnQuickGoStoreProfile');
        if (btnQuickGoStoreProfile) {
            btnQuickGoStoreProfile.addEventListener('click', () => switchTab('tab-store-profile'));
        }

        // Mobile sidebar toggle
        const menuToggleBtn = document.getElementById('menuToggleBtn');
        const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');
        const adminSidebar = document.getElementById('adminSidebar');

        if (menuToggleBtn) {
            menuToggleBtn.addEventListener('click', () => adminSidebar.classList.add('mobile-open'));
        }
        if (sidebarCloseBtn) {
            sidebarCloseBtn.addEventListener('click', () => adminSidebar.classList.remove('mobile-open'));
        }

        // Dark mode toggle
        const themeToggleBtn = document.getElementById('themeToggleBtn');
        if (localStorage.getItem('edumarket_theme') === 'dark') {
            document.body.classList.add('dark-mode');
            themeToggleBtn.innerHTML = '<i class="fas fa-sun" style="color:#FFD700;"></i>';
        }

        themeToggleBtn.addEventListener('click', () => {
            document.body.classList.toggle('dark-mode');
            const isDark = document.body.classList.contains('dark-mode');
            localStorage.setItem('edumarket_theme', isDark ? 'dark' : 'light');
            themeToggleBtn.innerHTML = isDark ? '<i class="fas fa-sun" style="color:#FFD700;"></i>' : '<i class="fas fa-moon"></i>';
        });

        // ---------------- API Loaders ----------------
        // 1. Load Stats
        async function loadStats() {
            try {
                const res = await fetch('api/stats.php');
                const json = await res.json();
                if (json.success && json.data) {
                    const d = json.data;
                    document.getElementById('statTotalProducts').textContent = d.total_products;
                    document.getElementById('statTotalRevenue').textContent = formatRupiah(d.total_revenue);
                    document.getElementById('statTotalOrders').textContent = d.total_orders;
                    document.getElementById('statPendingOrders').textContent = d.pending_orders;
                    document.getElementById('sidebarProdCount').textContent = d.total_products;
                    document.getElementById('sidebarOrderCount').textContent = d.total_orders;

                    renderDashboardRecentOrders(d.recent_orders || []);
                }
            } catch (e) {
                console.warn('Stats fetch warning:', e);
            }
        }

        function renderDashboardRecentOrders(orders) {
            const tbody = document.getElementById('dashboardOrdersTable');
            if (!orders || orders.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; color: var(--text-muted); padding: 20px;">Belum ada pesanan terbaru.</td></tr>';
                return;
            }

            tbody.innerHTML = orders.map(o => {
                const statusBadge = getStatusBadge(o.payment_status);
                return `
                    <tr>
                        <td><strong><code>${o.order_number}</code></strong></td>
                        <td>${o.customer_name}</td>
                        <td>${o.product_name || 'Aplikasi Edukasi'}</td>
                        <td style="font-weight: 700; color: var(--primary);">${formatRupiah(o.amount)}</td>
                        <td><span class="badge badge-purple">${(o.payment_method || '').toUpperCase()}</span></td>
                        <td>${statusBadge}</td>
                        <td>
                            <button class="btn btn-sm btn-secondary quick-status-btn" data-id="${o.id}" data-status="${o.payment_status}">
                                <i class="fas fa-edit"></i> Ubah
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');

            tbody.querySelectorAll('.quick-status-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.getAttribute('data-id');
                    const curStatus = btn.getAttribute('data-status');
                    promptChangeOrderStatus(id, curStatus);
                });
            });
        }

        function getStatusBadge(status) {
            switch(status) {
                case 'paid': return '<span class="badge badge-success"><i class="fas fa-check-circle"></i> Lunas</span>';
                case 'pending': return '<span class="badge badge-warning"><i class="fas fa-hourglass-half"></i> Pending</span>';
                case 'expired': return '<span class="badge badge-danger"><i class="fas fa-times-circle"></i> Kedaluwarsa</span>';
                case 'refunded': return '<span class="badge badge-info"><i class="fas fa-rotate-left"></i> Dibatalkan</span>';
                default: return `<span class="badge badge-secondary">${status}</span>`;
            }
        }

        // 2. Load Products
        async function loadProducts() {
            try {
                const res = await fetch('api/products.php');
                const json = await res.json();
                if (json.success && Array.isArray(json.data)) {
                    state.products = json.data;
                    renderProductsTable();
                }
            } catch (e) {
                console.warn('Products fetch warning:', e);
            }
        }

        function renderProductsTable() {
            const tbody = document.getElementById('productsTableBody');
            const search = (document.getElementById('prodSearchInput').value || '').toLowerCase().trim();
            const category = document.getElementById('prodCategoryFilter').value;

            let filtered = [...state.products];
            if (category !== 'all') {
                filtered = filtered.filter(p => p.category === category);
            }
            if (search) {
                filtered = filtered.filter(p => p.name.toLowerCase().includes(search) || (p.slug || '').toLowerCase().includes(search));
            }

            if (filtered.length === 0) {
                tbody.innerHTML = '<tr><td colspan="9" style="text-align: center; color: var(--text-muted); padding: 24px;">Tidak ada produk yang sesuai.</td></tr>';
                return;
            }

            tbody.innerHTML = filtered.map(p => {
                const statusBadge = p.status === 'active' 
                    ? '<span class="badge badge-success">Aktif</span>' 
                    : '<span class="badge badge-danger">Non-Aktif</span>';

                const featuredPills = [];
                if (p.is_bestseller) featuredPills.push('<span class="badge badge-warning">Bestseller</span>');
                if (p.is_featured) featuredPills.push('<span class="badge badge-purple">Pilihan</span>');

                return `
                    <tr>
                        <td>
                            <img src="${p.image}" class="prod-thumb" alt="${p.name}" onerror="this.src='../assets/images/favicon.svg';">
                        </td>
                        <td>
                            <strong>${p.name}</strong><br>
                            <small style="color: var(--text-muted);">${p.slug}</small>
                        </td>
                        <td><span class="badge badge-purple">${p.category}</span></td>
                        <td>${formatRupiah(p.price)}</td>
                        <td style="font-weight: 700; color: var(--primary);">
                            ${p.discount_price ? formatRupiah(p.discount_price) : '-'}
                        </td>
                        <td>${statusBadge}</td>
                        <td>${featuredPills.join(' ') || '-'}</td>
                        <td>${p.total_sales || 0}x</td>
                        <td>
                            <div style="display: flex; gap: 6px;">
                                <button class="btn btn-sm btn-secondary edit-prod-btn" data-id="${p.id}" title="Edit Produk">
                                    <i class="fas fa-pen"></i>
                                </button>
                                <button class="btn btn-sm btn-danger delete-prod-btn" data-id="${p.id}" data-name="${p.name}" title="Hapus Produk">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');

            // Bind actions
            tbody.querySelectorAll('.edit-prod-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = parseInt(btn.getAttribute('data-id'));
                    openProductModal('update', id);
                });
            });

            tbody.querySelectorAll('.delete-prod-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = parseInt(btn.getAttribute('data-id'));
                    const name = btn.getAttribute('data-name');
                    confirmDeleteProduct(id, name);
                });
            });
        }

        document.getElementById('prodSearchInput').addEventListener('input', renderProductsTable);
        document.getElementById('prodCategoryFilter').addEventListener('change', renderProductsTable);

        // 3. Load Orders
        async function loadOrders() {
            try {
                const res = await fetch('api/orders.php');
                const json = await res.json();
                if (json.success && Array.isArray(json.data)) {
                    state.orders = json.data;
                    renderOrdersTable();
                }
            } catch (e) {
                console.warn('Orders fetch warning:', e);
            }
        }

        function renderOrdersTable() {
            const tbody = document.getElementById('ordersTableBody');
            const search = (document.getElementById('orderSearchInput').value || '').toLowerCase().trim();
            const statusFilter = document.getElementById('orderStatusFilter').value;

            let filtered = [...state.orders];
            if (statusFilter !== 'all') {
                filtered = filtered.filter(o => o.payment_status === statusFilter);
            }
            if (search) {
                filtered = filtered.filter(o => 
                    (o.order_number || '').toLowerCase().includes(search) || 
                    (o.customer_name || '').toLowerCase().includes(search) ||
                    (o.customer_email || '').toLowerCase().includes(search)
                );
            }

            if (filtered.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" style="text-align: center; color: var(--text-muted); padding: 24px;">Tidak ada transaksi pesanan yang sesuai filter.</td></tr>';
                return;
            }

            tbody.innerHTML = filtered.map(o => {
                return `
                    <tr>
                        <td><strong><code>${o.order_number}</code></strong></td>
                        <td><small>${o.created_at || '-'}</small></td>
                        <td>
                            <strong>${o.customer_name}</strong><br>
                            <small style="color: var(--text-muted);">${o.customer_email} | ${o.customer_phone}</small>
                        </td>
                        <td>${o.product_name || 'Aplikasi Edukasi'}</td>
                        <td style="font-weight: 700; color: var(--primary);">${formatRupiah(o.amount)}</td>
                        <td><span class="badge badge-purple">${(o.payment_method || '').toUpperCase()}</span></td>
                        <td>${getStatusBadge(o.payment_status)}</td>
                        <td>
                            <div style="display: flex; gap: 6px;">
                                <button class="btn btn-sm btn-primary order-status-btn" data-id="${o.id}" data-status="${o.payment_status}" title="Ubah Status">
                                    <i class="fas fa-check"></i> Status
                                </button>
                                <a href="https://wa.me/${(o.customer_phone || '').replace(/[^0-9]/g, '')}" target="_blank" class="btn btn-sm btn-success" title="Chat WhatsApp Customer">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                                <button class="btn btn-sm btn-danger delete-order-btn" data-id="${o.id}" title="Hapus Pesanan">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');

            tbody.querySelectorAll('.order-status-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.getAttribute('data-id');
                    const status = btn.getAttribute('data-status');
                    promptChangeOrderStatus(id, status);
                });
            });

            tbody.querySelectorAll('.delete-order-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.getAttribute('data-id');
                    confirmDeleteOrder(id);
                });
            });
        }

        document.getElementById('orderSearchInput').addEventListener('input', renderOrdersTable);
        document.getElementById('orderStatusFilter').addEventListener('change', renderOrdersTable);

        // 4. Load Payments
        async function loadPayments() {
            try {
                const res = await fetch('api/payments.php');
                const json = await res.json();
                if (json.success && Array.isArray(json.data)) {
                    state.payments = json.data;
                    renderPaymentsGrid();
                }
            } catch (e) {
                console.warn('Payments fetch warning:', e);
            }
        }

        function renderPaymentsGrid() {
            const container = document.getElementById('paymentsGrid');
            if (!state.payments || state.payments.length === 0) {
                container.innerHTML = '<p style="color: var(--text-muted);">Belum ada metode pembayaran yang dikonfigurasi.</p>';
                return;
            }

            container.innerHTML = state.payments.map(p => {
                const icon = p.method_type === 'qris' ? 'fa-qrcode' : (p.method_type === 'bank' ? 'fa-university' : 'fa-wallet');
                const isChecked = p.is_active ? 'checked' : '';

                return `
                    <div class="payment-card">
                        <div class="payment-header">
                            <div class="payment-icon-wrapper">
                                <i class="fas ${icon}"></i>
                            </div>
                            <label class="switch-toggle" title="Aktifkan / Non-aktifkan">
                                <input type="checkbox" class="toggle-payment-active" data-id="${p.id}" ${isChecked}>
                                <span class="slider"></span>
                            </label>
                        </div>
                        <h4>${p.method_title}</h4>
                        <div class="payment-acc-num">${p.account_number || '-'}</div>
                        <div class="payment-holder"><i class="fas fa-user-check"></i> a.n. ${p.account_holder || '-'}</div>
                        <div class="payment-instructions">${p.instructions || 'Tidak ada petunjuk khusus.'}</div>
                        <button class="btn btn-secondary btn-sm edit-pay-btn" data-id="${p.id}" style="width: 100%;">
                            <i class="fas fa-edit"></i> Edit Rekening / Metode Ini
                        </button>
                    </div>
                `;
            }).join('');

            container.querySelectorAll('.toggle-payment-active').forEach(sw => {
                sw.addEventListener('change', async (e) => {
                    const id = sw.getAttribute('data-id');
                    const isActive = sw.checked ? 1 : 0;
                    try {
                        await fetch('api/payments.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ action: 'toggle', id: id, is_active: isActive })
                        });
                        Swal.fire({
                            icon: 'success',
                            title: 'Status Diperbarui',
                            text: 'Metode pembayaran telah berhasil diubah.',
                            timer: 1500,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        });
                    } catch (err) {
                        sw.checked = !sw.checked;
                    }
                });
            });

            container.querySelectorAll('.edit-pay-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = parseInt(btn.getAttribute('data-id'));
                    openPaymentModal('update', id);
                });
            });
        }

        // 5. Load Reviews
        async function loadReviews() {
            const container = document.getElementById('reviewsListContainer');
            try {
                const res = await fetch('../api/products.php');
                const json = await res.json();
                if (json.success) {
                    container.innerHTML = `
                        <div style="background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 8px; padding: 16px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                                <strong>Budi Santoso (Kepala Lab Komputer)</strong>
                                <span style="color: #FFD700;"><i class="fas fa-star"></i> 5.0 / 5.0</span>
                            </div>
                            <small style="color: var(--primary); font-weight: 600;">Produk: EduSchool Pro v3.0</small>
                            <p style="font-size: 0.9rem; color: var(--text-muted); margin-top: 8px;">
                                "Sistem informasinya sangat stabil. Kami sudah menerapkan di 12 kelas dan fitur rekap SPP-nya sangat akurat."
                            </p>
                        </div>
                        <div style="background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 8px; padding: 16px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                                <strong>Siti Rahmawati (Guru TIK SMA)</strong>
                                <span style="color: #FFD700;"><i class="fas fa-star"></i> 5.0 / 5.0</span>
                            </div>
                            <small style="color: var(--primary); font-weight: 600;">Produk: QuizMaster Pro CBT</small>
                            <p style="font-size: 0.9rem; color: var(--text-muted); margin-top: 8px;">
                                "Fitur anti pindah tab-nya terbukti ampuh mencegah siswa berbuat curang saat asesmen sumatif."
                            </p>
                        </div>
                    `;
                }
            } catch(e) {}
        }

        // ---------------- Product Modal Handlers ----------------
        const productModal = document.getElementById('productModal');
        const productForm = document.getElementById('productForm');
        const prodImageFileInput = document.getElementById('prodImageFileInput');
        const prodImageDropzone = document.getElementById('prodImageDropzone');
        const prodImageUrl = document.getElementById('prodImageUrl');
        const prodImagePreview = document.getElementById('prodImagePreview');
        const prodImagePreviewImg = document.getElementById('prodImagePreviewImg');

        document.getElementById('btnOpenAddProductModal').addEventListener('click', () => openProductModal('create'));
        document.getElementById('closeProductModalBtn').addEventListener('click', closeProductModal);
        document.getElementById('btnCancelProductModal').addEventListener('click', closeProductModal);

        function openProductModal(action = 'create', prodId = null) {
            document.getElementById('prodFormAction').value = action;
            document.getElementById('prodFormId').value = prodId || '';
            productForm.reset();
            prodImagePreview.style.display = 'none';

            if (action === 'create') {
                document.getElementById('productModalTitle').textContent = 'Tambah Produk Aplikasi Baru';
                document.getElementById('prodStatus').checked = true;
            } else if (action === 'update' && prodId) {
                document.getElementById('productModalTitle').textContent = 'Edit Produk Aplikasi';
                const p = state.products.find(item => item.id === prodId);
                if (p) {
                    document.getElementById('prodName').value = p.name || '';
                    document.getElementById('prodCategory').value = p.category || 'aplikasi-sekolah';
                    document.getElementById('prodPrice').value = p.price || '';
                    document.getElementById('prodDiscountPrice').value = p.discount_price || '';
                    document.getElementById('prodShortDesc').value = p.short_description || '';
                    document.getElementById('prodDescription').value = p.description || '';
                    document.getElementById('prodImageUrl').value = p.image || '';
                    document.getElementById('prodDemoUrl').value = p.demo_url || '';
                    document.getElementById('prodTechStack').value = Array.isArray(p.tech_stack) ? p.tech_stack.join(', ') : (p.tech_stack || '');
                    document.getElementById('prodFeatures').value = Array.isArray(p.features) ? p.features.join('\n') : (p.features || '');
                    document.getElementById('prodIsBestseller').checked = !!p.is_bestseller;
                    document.getElementById('prodIsFeatured').checked = !!p.is_featured;
                    document.getElementById('prodStatus').checked = p.status === 'active';

                    if (p.image) {
                        prodImagePreviewImg.src = p.image;
                        prodImagePreview.style.display = 'block';
                    }
                }
            }
            productModal.classList.add('active');
        }

        function closeProductModal() {
            productModal.classList.remove('active');
        }

        // Image upload trigger
        prodImageDropzone.addEventListener('click', () => prodImageFileInput.click());
        prodImageFileInput.addEventListener('change', async (e) => {
            const file = e.target.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('file', file);
            formData.append('type', 'products');

            try {
                Swal.fire({
                    title: 'Mengunggah Foto...',
                    html: 'Sedang memproses upload gambar produk ke server.',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                const res = await fetch('api/upload.php', {
                    method: 'POST',
                    body: formData
                });
                const json = await res.json();
                Swal.close();

                if (json.success && json.url) {
                    prodImageUrl.value = json.url;
                    prodImagePreviewImg.src = '../' + json.url;
                    prodImagePreview.style.display = 'block';
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil Upload',
                        text: 'Foto produk siap disimpan.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire('Gagal Upload', json.message || 'File tidak dapat diunggah.', 'error');
                }
            } catch (err) {
                Swal.fire('Error', err.message, 'error');
            }
        });

        // URL image preview on change
        prodImageUrl.addEventListener('input', () => {
            const url = prodImageUrl.value.trim();
            if (url) {
                prodImagePreviewImg.src = url;
                prodImagePreview.style.display = 'block';
            } else {
                prodImagePreview.style.display = 'none';
            }
        });

        // Product Form Submit
        productForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const action = document.getElementById('prodFormAction').value;
            const id = document.getElementById('prodFormId').value;
            const name = document.getElementById('prodName').value.trim();
            const category = document.getElementById('prodCategory').value;
            const price = parseFloat(document.getElementById('prodPrice').value);
            const discountPrice = document.getElementById('prodDiscountPrice').value ? parseFloat(document.getElementById('prodDiscountPrice').value) : null;
            const shortDesc = document.getElementById('prodShortDesc').value.trim();
            const description = document.getElementById('prodDescription').value.trim();
            const image = document.getElementById('prodImageUrl').value.trim() || 'assets/images/favicon.svg';
            const techStack = document.getElementById('prodTechStack').value.trim();
            const demoUrl = document.getElementById('prodDemoUrl').value.trim();
            const features = document.getElementById('prodFeatures').value.trim();
            const isBestseller = document.getElementById('prodIsBestseller').checked ? 1 : 0;
            const isFeatured = document.getElementById('prodIsFeatured').checked ? 1 : 0;
            const status = document.getElementById('prodStatus').checked ? 'active' : 'inactive';

            const payload = {
                action: action,
                id: id,
                name: name,
                category: category,
                price: price,
                discount_price: discountPrice,
                short_description: shortDesc,
                description: description,
                image: image,
                tech_stack: techStack,
                demo_url: demoUrl,
                features: features,
                is_bestseller: isBestseller,
                is_featured: isFeatured,
                status: status
            };

            const saveBtn = document.getElementById('btnSaveProduct');
            saveBtn.disabled = true;

            try {
                const res = await fetch('api/products.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const json = await res.json();
                saveBtn.disabled = false;

                if (json.success) {
                    closeProductModal();
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: json.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    loadProducts();
                    loadStats();
                } else {
                    Swal.fire('Gagal Menyimpan', json.message || 'Terjadi kesalahan.', 'error');
                }
            } catch (err) {
                saveBtn.disabled = false;
                Swal.fire('Error', err.message, 'error');
            }
        });

        // Delete product confirmation
        function confirmDeleteProduct(id, name) {
            Swal.fire({
                title: 'Hapus Produk Ini?',
                html: `Apakah Anda yakin ingin menghapus produk <strong>"${name}"</strong>?<br><small style="color: #B3402E;">Data produk ini tidak dapat dikembalikan!</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus Sekarang',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#B3402E',
                cancelButtonColor: '#6C757D'
            }).then(async (res) => {
                if (res.isConfirmed) {
                    try {
                        const delRes = await fetch('api/products.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ action: 'delete', id: id })
                        });
                        const delJson = await delRes.json();
                        if (delJson.success) {
                            Swal.fire('Terhapus!', delJson.message, 'success');
                            loadProducts();
                            loadStats();
                        } else {
                            Swal.fire('Gagal', delJson.message, 'error');
                        }
                    } catch (e) {
                        Swal.fire('Error', e.message, 'error');
                    }
                }
            });
        }

        // ---------------- Payment Modal Handlers ----------------
        const paymentModal = document.getElementById('paymentModal');
        const paymentForm = document.getElementById('paymentForm');

        document.getElementById('btnOpenAddPaymentModal').addEventListener('click', () => openPaymentModal('create'));
        document.getElementById('closePaymentModalBtn').addEventListener('click', closePaymentModal);
        document.getElementById('btnCancelPaymentModal').addEventListener('click', closePaymentModal);

        function openPaymentModal(action = 'create', payId = null) {
            document.getElementById('payFormAction').value = action;
            document.getElementById('payFormId').value = payId || '';
            paymentForm.reset();

            if (action === 'create') {
                document.getElementById('paymentModalTitle').textContent = 'Tambah Metode Pembayaran';
            } else if (action === 'update' && payId) {
                document.getElementById('paymentModalTitle').textContent = 'Edit Metode Pembayaran';
                const p = state.payments.find(item => item.id === payId);
                if (p) {
                    document.getElementById('payMethodTitle').value = p.method_title || '';
                    document.getElementById('payAccountNumber').value = p.account_number || '';
                    document.getElementById('payAccountHolder').value = p.account_holder || '';
                    document.getElementById('payInstructions').value = p.instructions || '';
                    document.getElementById('payQrImage').value = p.qr_image || '';
                    document.getElementById('payIsActive').checked = !!p.is_active;
                }
            }
            paymentModal.classList.add('active');
        }

        function closePaymentModal() {
            paymentModal.classList.remove('active');
        }

        // QR upload handler
        const payQrDropzone = document.getElementById('payQrDropzone');
        const payQrFileInput = document.getElementById('payQrFileInput');
        payQrDropzone.addEventListener('click', () => payQrFileInput.click());
        payQrFileInput.addEventListener('change', async (e) => {
            const file = e.target.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('file', file);
            formData.append('type', 'payments');

            try {
                Swal.fire({ title: 'Mengunggah QRIS...', didOpen: () => Swal.showLoading() });
                const res = await fetch('api/upload.php', { method: 'POST', body: formData });
                const json = await res.json();
                Swal.close();
                if (json.success && json.url) {
                    document.getElementById('payQrImage').value = json.url;
                    Swal.fire({ icon: 'success', title: 'QRIS Terunggah!', timer: 1200, showConfirmButton: false });
                }
            } catch (err) {
                Swal.fire('Error', err.message, 'error');
            }
        });

        // Payment Form Submit
        paymentForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const payload = {
                action: document.getElementById('payFormAction').value,
                id: document.getElementById('payFormId').value,
                method_title: document.getElementById('payMethodTitle').value.trim(),
                account_number: document.getElementById('payAccountNumber').value.trim(),
                account_holder: document.getElementById('payAccountHolder').value.trim(),
                instructions: document.getElementById('payInstructions').value.trim(),
                qr_image: document.getElementById('payQrImage').value.trim(),
                is_active: document.getElementById('payIsActive').checked ? 1 : 0
            };

            try {
                const res = await fetch('api/payments.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const json = await res.json();
                if (json.success) {
                    closePaymentModal();
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: json.message, timer: 1500, showConfirmButton: false });
                    loadPayments();
                } else {
                    Swal.fire('Gagal', json.message, 'error');
                }
            } catch (err) {
                Swal.fire('Error', err.message, 'error');
            }
        });

        // ---------------- Change Order Status ----------------
        function promptChangeOrderStatus(orderId, currentStatus) {
            Swal.fire({
                title: 'Perbarui Status Pembayaran',
                html: `
                    <div style="text-align: left; font-size: 0.9rem;">
                        <p style="margin-bottom: 8px;">Pilih status baru untuk pesanan ini:</p>
                        <select id="swalChangeStatusSelect" class="swal2-select" style="width: 100%; margin: 0; box-sizing: border-box;">
                            <option value="paid" ${currentStatus === 'paid' ? 'selected' : ''}>Lunas (Paid - Sudah Bayar)</option>
                            <option value="pending" ${currentStatus === 'pending' ? 'selected' : ''}>Menunggu Pembayaran (Pending)</option>
                            <option value="expired" ${currentStatus === 'expired' ? 'selected' : ''}>Kedaluwarsa (Expired)</option>
                            <option value="refunded" ${currentStatus === 'refunded' ? 'selected' : ''}>Dibatalkan / Refund</option>
                        </select>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Simpan Status',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#1E2A5A',
                preConfirm: () => {
                    return document.getElementById('swalChangeStatusSelect').value;
                }
            }).then(async (res) => {
                if (res.isConfirmed && res.value) {
                    try {
                        const apiRes = await fetch('api/orders.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ action: 'update_status', id: orderId, payment_status: res.value })
                        });
                        const json = await apiRes.json();
                        if (json.success) {
                            Swal.fire({ icon: 'success', title: 'Status Diperbarui!', text: json.message, timer: 1400, showConfirmButton: false });
                            loadOrders();
                            loadStats();
                        }
                    } catch (e) {
                        Swal.fire('Error', e.message, 'error');
                    }
                }
            });
        }

        // Delete order
        function confirmDeleteOrder(id) {
            Swal.fire({
                title: 'Hapus Pesanan?',
                text: 'Data transaksi ini akan dihapus permanen.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#B3402E'
            }).then(async (res) => {
                if (res.isConfirmed) {
                    try {
                        const delRes = await fetch('api/orders.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ action: 'delete', id: id })
                        });
                        const json = await delRes.json();
                        if (json.success) {
                            Swal.fire('Terhapus!', json.message, 'success');
                            loadOrders();
                            loadStats();
                        }
                    } catch (e) {}
                }
            });
        }

        // ---------------- Change Password Handlers ----------------
        // Toggle eye password visibility for any target input
        document.querySelectorAll('.toggle-pass-visibility').forEach(btn => {
            btn.addEventListener('click', () => {
                const targetId = btn.getAttribute('data-target');
                const input = document.getElementById(targetId);
                if (!input) return;
                const isPass = input.getAttribute('type') === 'password';
                input.setAttribute('type', isPass ? 'text' : 'password');
                btn.innerHTML = isPass ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>';
            });
        });

        // Change password form submit
        const changePasswordForm = document.getElementById('changePasswordForm');
        if (changePasswordForm) {
            changePasswordForm.addEventListener('submit', async (e) => {
                e.preventDefault();

                const currentPassword = document.getElementById('settingCurrentPassword').value;
                const newPassword = document.getElementById('settingNewPassword').value;
                const confirmPassword = document.getElementById('settingConfirmPassword').value;

                if (!currentPassword) {
                    Swal.fire('Perhatian', 'Kata sandi saat ini wajib dimasukkan!', 'warning');
                    return;
                }

                if (newPassword.length < 6) {
                    Swal.fire('Perhatian', 'Kata sandi baru minimal harus 6 karakter!', 'warning');
                    return;
                }

                if (newPassword !== confirmPassword) {
                    Swal.fire('Perhatian', 'Konfirmasi kata sandi baru tidak cocok. Harap periksa kembali!', 'warning');
                    return;
                }

                const btn = document.getElementById('btnSubmitChangePassword');
                const btnText = document.getElementById('btnChangePassText');
                const btnSpinner = document.getElementById('btnChangePassSpinner');

                btn.disabled = true;
                btnText.style.display = 'none';
                btnSpinner.style.display = 'inline-block';

                try {
                    const res = await fetch('api/change-password.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            current_password: currentPassword,
                            new_password: newPassword,
                            confirm_password: confirmPassword
                        })
                    });

                    const json = await res.json();
                    btn.disabled = false;
                    btnText.style.display = 'inline-block';
                    btnSpinner.style.display = 'none';

                    if (json.success) {
                        changePasswordForm.reset();
                        // Reset eye icons
                        document.querySelectorAll('.toggle-pass-visibility').forEach(b => {
                            b.innerHTML = '<i class="fas fa-eye"></i>';
                            const tId = b.getAttribute('data-target');
                            const inp = document.getElementById(tId);
                            if (inp) inp.setAttribute('type', 'password');
                        });

                        Swal.fire({
                            icon: 'success',
                            title: 'Kata Sandi Diperbarui!',
                            text: json.message || 'Kata sandi akun Anda telah berhasil diperbarui.',
                            confirmButtonColor: '#1E2A5A'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Mengubah Kata Sandi',
                            text: json.message || 'Periksa kembali kata sandi lama Anda.',
                            confirmButtonColor: '#1E2A5A'
                        });
                    }
                } catch (err) {
                    btn.disabled = false;
                    btnText.style.display = 'inline-block';
                    btnSpinner.style.display = 'none';
                    Swal.fire('Terjadi Kesalahan', err.message, 'error');
                }
            });
        }

        // ---------------- Store Profile Handlers ----------------
        function updateLiveBrandPreview() {
            const prefixEl = document.getElementById('storeNamePrefix');
            const suffixEl = document.getElementById('storeNameSuffix');
            const logoUrlEl = document.getElementById('storeLogoUrl');
            const storeNameEl = document.getElementById('storeName');

            const prefix = prefixEl ? prefixEl.value.trim() : 'Edu';
            const suffix = suffixEl ? suffixEl.value.trim() : 'Market';
            const logoUrl = logoUrlEl ? logoUrlEl.value.trim() : '';

            const combinedName = (prefix + (suffix ? ' ' + suffix : '')).trim() || 'EduMarket';
            if (storeNameEl) storeNameEl.value = combinedName;

            const textPreview = document.getElementById('liveBrandTextPreview');
            if (textPreview) {
                textPreview.innerHTML = `${prefix || 'Edu'}<span style="color: #D97706;">${suffix || ''}</span>`;
            }

            const imgPreview = document.getElementById('liveBrandImgPreview');
            const iconPreview = document.getElementById('liveBrandIconPreview');
            if (imgPreview && iconPreview) {
                if (logoUrl && !logoUrl.endsWith('favicon.svg')) {
                    const src = (logoUrl.startsWith('http') || logoUrl.startsWith('uploads/')) ? (logoUrl.startsWith('http') ? logoUrl : '../' + logoUrl) : logoUrl;
                    imgPreview.src = src;
                    imgPreview.style.display = 'inline-block';
                    iconPreview.style.display = 'none';
                } else {
                    imgPreview.style.display = 'none';
                    iconPreview.style.display = 'inline-flex';
                }
            }
        }

        function updateAdminSidebarBrand(logoUrl, storeName) {
            const brandIcon = document.getElementById('adminSidebarBrandIcon');
            const brandImg = document.getElementById('adminSidebarBrandImg');
            const brandText = document.getElementById('adminSidebarBrandText');

            if (storeName && brandText) {
                const parts = storeName.trim().split(/\s+/);
                if (parts.length > 1) {
                    const last = parts.pop();
                    brandText.innerHTML = `${parts.join(' ')}<span>${last}</span>`;
                } else if (storeName.length >= 6) {
                    const mid = Math.ceil(storeName.length / 2);
                    brandText.innerHTML = `${storeName.substring(0, mid)}<span>${storeName.substring(mid)}</span>`;
                } else {
                    brandText.textContent = storeName;
                }
            }

            const hasCustomLogo = logoUrl && !logoUrl.endsWith('favicon.svg') && logoUrl.trim() !== '';

            if (brandImg && brandIcon) {
                if (hasCustomLogo) {
                    const src = (logoUrl.startsWith('http') || logoUrl.startsWith('uploads/')) ? (logoUrl.startsWith('http') ? logoUrl : '../' + logoUrl) : logoUrl;
                    brandImg.src = src;
                    brandImg.style.display = 'inline-block';
                    brandIcon.style.display = 'none';

                    brandImg.onerror = () => {
                        brandImg.style.display = 'none';
                        brandIcon.style.display = 'flex';
                    };
                } else {
                    brandImg.style.display = 'none';
                    brandIcon.style.display = 'flex';
                }
            }
        }

        async function loadStoreProfile() {
            try {
                const res = await fetch('api/store-profile.php?v=' + Date.now());
                const json = await res.json();
                if (json.success && json.data) {
                    const d = json.data;
                    const setVal = (id, val) => {
                        const el = document.getElementById(id);
                        if (el) el.value = val || '';
                    };

                    setVal('storeName', d.store_name);
                    setVal('storeNamePrefix', d.name_prefix || 'Edu');
                    setVal('storeNameSuffix', d.name_suffix || 'Market');
                    setVal('storeTagline', d.tagline);
                    setVal('storeDescription', d.description);
                    setVal('storeLogoUrl', d.logo);
                    setVal('storeFaviconUrl', d.favicon);
                    setVal('storeEmail', d.email);
                    setVal('storePhone', d.phone);
                    setVal('storeWhatsapp', d.whatsapp);
                    setVal('storeAddress', d.address);
                    setVal('storeOperatingHours', d.operating_hours);
                    setVal('storeInstagram', d.instagram);
                    setVal('storeFacebook', d.facebook);
                    setVal('storeYoutube', d.youtube);
                    setVal('storeWarranty', d.warranty_period);
                    setVal('storeSupport', d.support_duration);
                    setVal('storeCopyright', d.copyright_text);

                    if (d.logo) {
                        const preview = document.getElementById('storeLogoPreviewImg');
                        if (preview) {
                            preview.src = (d.logo.startsWith('http') || d.logo.startsWith('uploads/')) ? (d.logo.startsWith('http') ? d.logo : '../' + d.logo) : d.logo;
                        }
                    }

                    // Update Live Brand Preview & Sidebar Brand Header
                    updateLiveBrandPreview();
                    updateAdminSidebarBrand(d.logo, d.store_name);

                    // Sync to localStorage
                    try {
                        localStorage.setItem('edumarket_store_profile', JSON.stringify(d));
                    } catch(e){}
                }
            } catch (e) {
                console.warn('Store profile fetch error:', e);
            }
        }

        // Attach listeners for live preview typing
        const storeNamePrefixInput = document.getElementById('storeNamePrefix');
        const storeNameSuffixInput = document.getElementById('storeNameSuffix');
        if (storeNamePrefixInput) storeNamePrefixInput.addEventListener('input', updateLiveBrandPreview);
        if (storeNameSuffixInput) storeNameSuffixInput.addEventListener('input', updateLiveBrandPreview);

        // Upload Logo Dropzone
        const storeLogoDropzone = document.getElementById('storeLogoDropzone');
        const storeLogoFileInput = document.getElementById('storeLogoFileInput');
        const storeLogoUrl = document.getElementById('storeLogoUrl');
        const storeLogoPreviewImg = document.getElementById('storeLogoPreviewImg');

        if (storeLogoDropzone && storeLogoFileInput) {
            storeLogoDropzone.addEventListener('click', () => storeLogoFileInput.click());
            storeLogoFileInput.addEventListener('change', async (e) => {
                const file = e.target.files[0];
                if (!file) return;

                const formData = new FormData();
                formData.append('file', file);
                formData.append('type', 'logos');

                try {
                    Swal.fire({ title: 'Mengunggah & Menerapkan Logo...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                    const res = await fetch('api/upload.php', { method: 'POST', body: formData });
                    const json = await res.json();

                    if (json.success && json.url) {
                        storeLogoUrl.value = json.url;
                        if (storeLogoPreviewImg) storeLogoPreviewImg.src = '../' + json.url;

                        const storeNameCurrent = document.getElementById('storeName').value.trim() || 'EduMarket';

                        // Auto-save the new logo immediately to store profile
                        await fetch('api/store-profile.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({
                                store_name: storeNameCurrent,
                                logo: json.url
                            })
                        });

                        // Update live preview & sidebar brand immediately
                        updateLiveBrandPreview();
                        updateAdminSidebarBrand(json.url, storeNameCurrent);

                        // Sync to localStorage
                        try {
                            const cached = JSON.parse(localStorage.getItem('edumarket_store_profile') || '{}');
                            cached.logo = json.url;
                            cached.store_name = storeNameCurrent;
                            localStorage.setItem('edumarket_store_profile', JSON.stringify(cached));
                        } catch(e) {}

                        Swal.fire({ 
                            icon: 'success', 
                            title: 'Logo Berhasil Diupload & Diterapkan!', 
                            text: 'Logo baru Anda telah otomatis disimpan dan diterapkan di website serta panel admin.',
                            confirmButtonColor: '#1E2A5A' 
                        });
                    } else {
                        Swal.fire('Gagal Upload', json.message || 'Gagal mengunggah logo.', 'error');
                    }
                } catch (err) {
                    Swal.fire('Error', err.message, 'error');
                }
            });
        }

        if (storeLogoUrl) {
            storeLogoUrl.addEventListener('input', () => {
                const url = storeLogoUrl.value.trim();
                if (url && storeLogoPreviewImg) {
                    storeLogoPreviewImg.src = (url.startsWith('http') || url.startsWith('uploads/')) ? (url.startsWith('http') ? url : '../' + url) : url;
                }
                updateLiveBrandPreview();
            });
        }

        // Upload Favicon Dropzone
        const storeFaviconDropzone = document.getElementById('storeFaviconDropzone');
        const storeFaviconFileInput = document.getElementById('storeFaviconFileInput');
        const storeFaviconUrl = document.getElementById('storeFaviconUrl');

        if (storeFaviconDropzone && storeFaviconFileInput) {
            storeFaviconDropzone.addEventListener('click', () => storeFaviconFileInput.click());
            storeFaviconFileInput.addEventListener('change', async (e) => {
                const file = e.target.files[0];
                if (!file) return;

                const formData = new FormData();
                formData.append('file', file);
                formData.append('type', 'logos');

                try {
                    Swal.fire({ title: 'Mengunggah Favicon...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                    const res = await fetch('api/upload.php', { method: 'POST', body: formData });
                    const json = await res.json();
                    Swal.close();

                    if (json.success && json.url) {
                        if (storeFaviconUrl) storeFaviconUrl.value = json.url;
                        Swal.fire({ icon: 'success', title: 'Favicon Terunggah!', timer: 1200, showConfirmButton: false });
                    }
                } catch (err) {
                    Swal.fire('Error', err.message, 'error');
                }
            });
        }

        // Store Profile Form Submit
        const storeProfileForm = document.getElementById('storeProfileForm');
        if (storeProfileForm) {
            storeProfileForm.addEventListener('submit', async (e) => {
                e.preventDefault();

                const payload = {
                    name_prefix: document.getElementById('storeNamePrefix').value.trim(),
                    name_suffix: document.getElementById('storeNameSuffix').value.trim(),
                    store_name: document.getElementById('storeName').value.trim(),
                    tagline: document.getElementById('storeTagline').value.trim(),
                    description: document.getElementById('storeDescription').value.trim(),
                    logo: document.getElementById('storeLogoUrl').value.trim(),
                    favicon: document.getElementById('storeFaviconUrl').value.trim(),
                    email: document.getElementById('storeEmail').value.trim(),
                    phone: document.getElementById('storePhone').value.trim(),
                    whatsapp: document.getElementById('storeWhatsapp').value.trim(),
                    address: document.getElementById('storeAddress').value.trim(),
                    operating_hours: document.getElementById('storeOperatingHours').value.trim(),
                    instagram: document.getElementById('storeInstagram').value.trim(),
                    facebook: document.getElementById('storeFacebook').value.trim(),
                    youtube: document.getElementById('storeYoutube').value.trim(),
                    warranty_period: document.getElementById('storeWarranty').value.trim(),
                    support_duration: document.getElementById('storeSupport').value.trim(),
                    copyright_text: document.getElementById('storeCopyright').value.trim()
                };

                const btn = document.getElementById('btnSaveStoreProfile');
                const btnText = document.getElementById('btnStoreProfileText');
                const btnSpinner = document.getElementById('btnStoreProfileSpinner');

                btn.disabled = true;
                btnText.style.display = 'none';
                btnSpinner.style.display = 'inline-block';

                try {
                    const res = await fetch('api/store-profile.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(payload)
                    });
                    const json = await res.json();
                    btn.disabled = false;
                    btnText.style.display = 'inline-block';
                    btnSpinner.style.display = 'none';

                    if (json.success) {
                        updateLiveBrandPreview();
                        updateAdminSidebarBrand(payload.logo, payload.store_name);

                        // Sync to localStorage
                        try {
                            localStorage.setItem('edumarket_store_profile', JSON.stringify({ ...payload, ...(json.data || {}) }));
                        } catch (e) {}

                        Swal.fire({
                            icon: 'success',
                            title: 'Profil Toko Disimpan!',
                            text: json.message || 'Pengaturan identitas toko dan branding berhasil diperbarui.',
                            confirmButtonColor: '#1E2A5A'
                        });
                    } else {
                        Swal.fire('Gagal Menyimpan', json.message || 'Terjadi kesalahan.', 'error');
                    }
                } catch (err) {
                    btn.disabled = false;
                    btnText.style.display = 'inline-block';
                    btnSpinner.style.display = 'none';
                    Swal.fire('Terjadi Kesalahan', err.message, 'error');
                }
            });
        }

        // ---------------- WhatsApp Gateway (WAHA) Logic ----------------
        let waPollingTimer = null;

        function stopWaQrPolling() {
            if (waPollingTimer) {
                clearInterval(waPollingTimer);
                waPollingTimer = null;
            }
        }

        function startWaQrPolling() {
            if (waPollingTimer) return;
            waPollingTimer = setInterval(() => {
                if (state.activeTab === 'tab-whatsapp') {
                    checkWhatsAppStatus(true);
                } else {
                    stopWaQrPolling();
                }
            }, 5000);
        }

        async function loadWhatsAppSettings() {
            try {
                const res = await fetch('api/whatsapp.php?action=all&v=' + Date.now());
                const json = await res.json();
                if (json.success) {
                    const s = json.settings || {};
                    const setCheck = (id, val) => {
                        const el = document.getElementById(id);
                        if (el) el.checked = !!parseInt(val);
                    };
                    const setVal = (id, val) => {
                        const el = document.getElementById(id);
                        if (el) el.value = val !== undefined && val !== null ? val : '';
                    };

                    setCheck('waIsEnabled', s.is_enabled);
                    setVal('waApiUrl', s.api_url || 'http://localhost:3000');
                    setVal('waSessionName', s.session_name || 'default');
                    setVal('waApiKey', s.api_key || '');
                    setVal('waAdminPhone', s.admin_phone || '');

                    setCheck('waNotifyAdminOrder', s.notify_admin_on_order);
                    setCheck('waNotifyAdminContact', s.notify_admin_on_contact);
                    setCheck('waNotifyCustomerOrder', s.notify_customer_on_order);
                    setCheck('waNotifyCustomerPaid', s.notify_customer_on_paid);

                    setVal('waOrderTemplate', s.order_template);
                    setVal('waPaidTemplate', s.paid_template);
                    setVal('waContactTemplate', s.contact_template);

                    // Prefill test phone if empty
                    const testPhone = document.getElementById('waTestPhone');
                    if (testPhone && !testPhone.value && s.admin_phone) {
                        testPhone.value = s.admin_phone;
                    }

                    // Render current status
                    if (json.status) {
                        renderWhatsAppStatus(json.status);
                    }
                }
            } catch (err) {
                console.error('loadWhatsAppSettings error:', err);
            }
        }

        async function checkWhatsAppStatus(silent = false) {
            const badge = document.getElementById('waStatusBadge');
            const userSpan = document.getElementById('waConnectedUser');
            const sessionDisplay = document.getElementById('waSessionNameDisplay');

            if (!silent && badge) {
                badge.style.background = 'rgba(108,117,125,0.15)';
                badge.style.color = '#6c757d';
                badge.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Memeriksa Status...';
            }

            try {
                const res = await fetch('api/whatsapp.php?action=status&v=' + Date.now());
                const json = await res.json();
                if (json.success && json.data) {
                    renderWhatsAppStatus(json.data);
                } else {
                    renderWhatsAppStatus({ connected: false, status: 'OFFLINE', message: json.message || 'Gagal terhubung' });
                }
            } catch (err) {
                renderWhatsAppStatus({ connected: false, status: 'OFFLINE', message: err.message });
            }
        }

        function renderWhatsAppStatus(data) {
            const badge = document.getElementById('waStatusBadge');
            const userSpan = document.getElementById('waConnectedUser');
            const sessionDisplay = document.getElementById('waSessionNameDisplay');
            const qrContainer = document.getElementById('waQrContainer');

            if (sessionDisplay) {
                sessionDisplay.textContent = data.session_name || (document.getElementById('waSessionName')?.value || 'default');
            }

            const status = (data.status || 'UNKNOWN').toUpperCase();

            if (!data.connected || status === 'OFFLINE') {
                if (badge) {
                    badge.style.background = 'rgba(239, 68, 68, 0.15)';
                    badge.style.color = '#ef4444';
                    badge.innerHTML = '<i class="fas fa-times-circle"></i> WAHA Server Offline (:3000)';
                }
                if (userSpan) userSpan.innerHTML = '<span style="color: #ef4444;">Server WAHA belum aktif di ' + (document.getElementById('waApiUrl')?.value || 'http://localhost:3000') + '</span>';
                if (qrContainer) qrContainer.style.display = 'none';
                stopWaQrPolling();
                return;
            }

            if (status === 'WORKING') {
                if (badge) {
                    badge.style.background = 'rgba(37, 211, 102, 0.15)';
                    badge.style.color = '#25D366';
                    badge.innerHTML = '<i class="fas fa-check-circle"></i> TERHUBUNG (WORKING)';
                }
                const userId = data.user?.id ? data.user.id.replace(/@.*$/, '') : (data.session?.me?.id ? data.session.me.id.replace(/@.*$/, '') : 'Aktif');
                if (userSpan) userSpan.innerHTML = '<strong style="color: #25D366;"><i class="fab fa-whatsapp"></i> ' + userId + '</strong>';
                if (qrContainer) qrContainer.style.display = 'none';
                stopWaQrPolling();
            } else if (status === 'SCAN_QR_CODE') {
                if (badge) {
                    badge.style.background = 'rgba(245, 158, 11, 0.15)';
                    badge.style.color = '#f59e0b';
                    badge.innerHTML = '<i class="fas fa-qrcode"></i> SCAN QR CODE';
                }
                if (userSpan) userSpan.innerHTML = '<span style="color: #f59e0b;">Menunggu scan ponsel...</span>';
                if (qrContainer) qrContainer.style.display = 'block';
                loadWhatsAppQr();
                startWaQrPolling();
            } else if (status === 'STARTING') {
                if (badge) {
                    badge.style.background = 'rgba(59, 130, 246, 0.15)';
                    badge.style.color = '#3b82f6';
                    badge.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memulai Sesi...';
                }
                if (userSpan) userSpan.textContent = 'Memulai mesin sesi browser...';
                startWaQrPolling();
            } else if (status === 'STOPPED') {
                if (badge) {
                    badge.style.background = 'rgba(108,117,125,0.15)';
                    badge.style.color = '#6c757d';
                    badge.innerHTML = '<i class="fas fa-pause-circle"></i> Sesi Berhenti (STOPPED)';
                }
                if (userSpan) userSpan.textContent = 'Sesi dihentikan. Klik "Mulai Sesi" untuk mengaktifkan.';
                if (qrContainer) qrContainer.style.display = 'none';
                stopWaQrPolling();
            } else {
                if (badge) {
                    badge.style.background = 'rgba(245, 158, 11, 0.15)';
                    badge.style.color = '#f59e0b';
                    badge.innerHTML = '<i class="fas fa-info-circle"></i> ' + status;
                }
                if (userSpan) userSpan.textContent = data.message || 'Status sesi: ' + status;
                if (qrContainer) qrContainer.style.display = 'none';
            }
        }

        async function loadWhatsAppQr() {
            try {
                const res = await fetch('api/whatsapp.php?action=qr&v=' + Date.now());
                const json = await res.json();
                const img = document.getElementById('waQrImage');
                if (json.success && json.data_uri && img) {
                    img.src = json.data_uri;
                }
            } catch (e) {
                console.error('loadWhatsAppQr error:', e);
            }
        }

        // WhatsApp Session Control Handlers
        const btnRefreshWaStatus = document.getElementById('btnRefreshWaStatus');
        if (btnRefreshWaStatus) {
            btnRefreshWaStatus.addEventListener('click', () => {
                checkWhatsAppStatus(false);
            });
        }

        const btnStartWaSession = document.getElementById('btnStartWaSession');
        if (btnStartWaSession) {
            btnStartWaSession.addEventListener('click', async () => {
                btnStartWaSession.disabled = true;
                btnStartWaSession.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memulai...';
                try {
                    const res = await fetch('api/whatsapp.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ action: 'start_session' })
                    });
                    const json = await res.json();
                    if (json.success || json.name || json.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sesi Dimulai!',
                            text: 'Sesi WAHA sedang berjalan. Jika belum login, QR Code akan muncul sesaat lagi.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        setTimeout(() => checkWhatsAppStatus(false), 1500);
                    } else {
                        Swal.fire('Gagal Memulai Sesi', json.message || json.error || 'Pastikan container WAHA aktif di port 3000.', 'error');
                    }
                } catch (err) {
                    Swal.fire('Error', err.message, 'error');
                } finally {
                    btnStartWaSession.disabled = false;
                    btnStartWaSession.innerHTML = '<i class="fas fa-play"></i> Mulai Sesi';
                }
            });
        }

        const btnStopWaSession = document.getElementById('btnStopWaSession');
        if (btnStopWaSession) {
            btnStopWaSession.addEventListener('click', async () => {
                const conf = await Swal.fire({
                    title: 'Hentikan Sesi WhatsApp?',
                    text: 'Sesi WhatsApp akan diputus dan pesan otomatis tidak akan terkirim.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hentikan',
                    cancelButtonText: 'Batal'
                });

                if (conf.isConfirmed) {
                    btnStopWaSession.disabled = true;
                    btnStopWaSession.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menghentikan...';
                    try {
                        const res = await fetch('api/whatsapp.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ action: 'stop_session' })
                        });
                        const json = await res.json();
                        Swal.fire('Sesi Dihentikan', 'Sesi WAHA telah dihentikan.', 'info');
                        checkWhatsAppStatus(false);
                    } catch (err) {
                        Swal.fire('Error', err.message, 'error');
                    } finally {
                        btnStopWaSession.disabled = false;
                        btnStopWaSession.innerHTML = '<i class="fas fa-stop"></i> Hentikan Sesi';
                    }
                }
            });
        }

        // Save WAHA Settings Form Handler
        const waSettingsForm = document.getElementById('waSettingsForm');
        if (waSettingsForm) {
            waSettingsForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const btn = document.getElementById('btnSaveWaSettings');
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

                const payload = {
                    action: 'save_settings',
                    is_enabled: document.getElementById('waIsEnabled').checked ? 1 : 0,
                    api_url: document.getElementById('waApiUrl').value.trim(),
                    session_name: document.getElementById('waSessionName').value.trim() || 'default',
                    api_key: document.getElementById('waApiKey').value.trim(),
                    admin_phone: document.getElementById('waAdminPhone').value.trim(),
                    notify_admin_on_order: document.getElementById('waNotifyAdminOrder').checked ? 1 : 0,
                    notify_admin_on_contact: document.getElementById('waNotifyAdminContact').checked ? 1 : 0,
                    notify_customer_on_order: document.getElementById('waNotifyCustomerOrder').checked ? 1 : 0,
                    notify_customer_on_paid: document.getElementById('waNotifyCustomerPaid').checked ? 1 : 0,
                    order_template: document.getElementById('waOrderTemplate').value.trim(),
                    paid_template: document.getElementById('waPaidTemplate').value.trim(),
                    contact_template: document.getElementById('waContactTemplate').value.trim()
                };

                try {
                    const res = await fetch('api/whatsapp.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(payload)
                    });
                    const json = await res.json();
                    if (json.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Pengaturan Disimpan!',
                            text: json.message,
                            confirmButtonColor: '#1E2A5A'
                        });
                        checkWhatsAppStatus(false);
                    } else {
                        Swal.fire('Gagal Menyimpan', json.message || 'Terjadi kesalahan.', 'error');
                    }
                } catch (err) {
                    Swal.fire('Error', err.message, 'error');
                } finally {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-save"></i> Simpan Konfigurasi WAHA';
                }
            });
        }

        // Save Templates Button Handler
        const btnSaveTemplates = document.getElementById('btnSaveTemplates');
        if (btnSaveTemplates) {
            btnSaveTemplates.addEventListener('click', () => {
                if (waSettingsForm) {
                    waSettingsForm.dispatchEvent(new Event('submit'));
                }
            });
        }

        // Test WhatsApp Message Form Handler
        const waTestMessageForm = document.getElementById('waTestMessageForm');
        if (waTestMessageForm) {
            waTestMessageForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const phone = document.getElementById('waTestPhone').value.trim();
                const text = document.getElementById('waTestText').value.trim();
                const btn = document.getElementById('btnSendWaTest');
                const btnText = document.getElementById('btnSendWaTestText');
                const btnSpinner = document.getElementById('btnSendWaTestSpinner');

                if (!phone) {
                    Swal.fire('Peringatan', 'Nomor WhatsApp tujuan wajib diisi.', 'warning');
                    return;
                }

                btn.disabled = true;
                btnText.style.display = 'none';
                btnSpinner.style.display = 'inline-block';

                try {
                    const res = await fetch('api/whatsapp.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            action: 'test_message',
                            phone: phone,
                            message: text
                        })
                    });
                    const json = await res.json();
                    if (json.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Pesan Terkirim!',
                            text: json.message,
                            confirmButtonColor: '#25D366'
                        });
                    } else {
                        Swal.fire('Pengiriman Gagal', json.message, 'error');
                    }
                } catch (err) {
                    Swal.fire('Error', err.message, 'error');
                } finally {
                    btn.disabled = false;
                    btnText.style.display = 'inline-block';
                    btnSpinner.style.display = 'none';
                }
            });
        }

        // ---------------- Initial Boot ----------------
        document.addEventListener('DOMContentLoaded', () => {
            loadStats();
            loadProducts();
            loadOrders();
            loadPayments();
            loadReviews();
            loadStoreProfile();
            loadWhatsAppSettings();
        });
    </script>
</body>
</html>
