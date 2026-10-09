<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Branch Staff Dashboard - Vape Expo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap"
        rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        html,
        body {
            max-width: 100%;
            overflow-x: hidden;
        }

        body {
            background: #f5f7fb;
            -webkit-tap-highlight-color: transparent;
            -webkit-font-smoothing: antialiased;
        }

        img {
            max-width: 100%;
        }

        /* ========== LOADING SCREEN ========== */
        .loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.6s ease, visibility 0.6s ease;
        }

        .loading-logo {
            background: linear-gradient(135deg, #e8f0fe 0%, #d4e4fc 100%);
            border-radius: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem auto;
            animation: pulseGlow 1.5s ease-in-out infinite;
            box-shadow: 0 0 30px rgba(13, 110, 253, 0.15);
            padding: 20px;
        }

        .loading-title {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin-bottom: 0.5rem;
            text-align: center;
        }

        .loading-spinner {
            width: 40px;
            height: 40px;
            margin: 1.5rem auto 0;
            border: 3px solid rgba(13, 110, 253, 0.15);
            border-top-color: #0d6efd;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes pulseGlow {

            0%,
            100% {
                box-shadow: 0 0 20px rgba(13, 110, 253, 0.15);
                transform: scale(1);
            }

            50% {
                box-shadow: 0 0 40px rgba(13, 110, 253, 0.3);
                transform: scale(1.03);
            }
        }

        /* Navbar */
        .navbar-glass {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            backdrop-filter: blur(10px);
        }

        .navbar-divider {
            height: 2px;
            background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.1), transparent);
        }

        /* ========================================== */
        /* LAYOUT: wrapper + main-content             */
        /* ========================================== */
        .dash-wrapper {
            display: flex;
            align-items: flex-start;
            width: 100%;
            padding: 1.5rem;
            gap: 1rem;
            box-sizing: border-box;
        }

        .dash-sidebar {
            width: 240px;
            flex-shrink: 0;
        }

        .dash-main {
            flex: 1;
            min-width: 0;
        }

        /* Sidebar card */
        .sidebar-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            background: white;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.04);
            padding: 0.4rem;
        }

        .sidebar-card .card-header {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            border-bottom: none;
            padding: 0.9rem 1rem;
            color: white;
            font-weight: 600;
            border-radius: 16px 16px 0 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar-card .card-header .close-btn {
            background: rgba(255, 255, 255, 0.12);
            border: none;
            color: white;
            width: 28px;
            height: 28px;
            border-radius: 8px;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
        }

        .sidebar-card .card-header .close-btn:hover {
            background: rgba(255, 255, 255, 0.22);
        }

        .sidebar-card .list-group-item {
            background: white;
            color: #4a5568;
            border: none;
            padding: 0.7rem 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin: 3px 6px;
            border-radius: 12px;
            position: relative;
            font-size: 0.9rem;
        }

        .sidebar-card .list-group-item:hover {
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.05) 0%, rgba(13, 110, 253, 0.08) 100%);
            color: #0d6efd;
            transform: translateX(6px);
        }

        .sidebar-card .list-group-item.active {
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.1) 0%, rgba(13, 110, 253, 0.15) 100%);
            color: #0d6efd;
            font-weight: 600;
            border-left: 3px solid #0d6efd;
        }

        .sidebar-card .list-group-item i {
            width: 22px;
            transition: transform 0.3s ease;
        }

        .sidebar-card .list-group-item:hover i {
            transform: scale(1.1);
        }

        /* Mobile-only logout item — HIDDEN on desktop */
        .mobile-only-logout {
            display: none;
        }

        /* Sidebar overlay */
        .dash-sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            z-index: 1040;
        }

                /* Floating toggle button — RIGHT side */
        .dash-toggle-btn {
            display: none;
            position: fixed;
            bottom: calc(18px + env(safe-area-inset-bottom));
            right: calc(18px + env(safe-area-inset-right));
            left: auto;
            z-index: 1060;
            background: #0d6efd;
            color: white;
            border: none;
            border-radius: 50%;
            width: 48px;
            height: 48px;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 14px rgba(13, 110, 253, 0.45);
            cursor: pointer;
            transition: transform 0.2s ease, background 0.2s ease;
            font-size: 1.1rem;
        }

        .dash-toggle-btn:hover {
            background: #0b5ed7;
            transform: scale(1.05);
        }

        .dash-toggle-btn i {
            transition: transform 0.3s ease;
        }

        /* When drawer is open, button stays visible but changes color (close state) */
        body.sidebar-open .dash-toggle-btn {
            background: #dc3545;
            box-shadow: 0 4px 14px rgba(220, 53, 69, 0.45);
        }

        body.sidebar-open .dash-toggle-btn:hover {
            background: #b02a37;
        }


        /* Badge Counts */
        .badge-count,
        .badge-count-cyan,
        .badge-count-green,
        .badge-count-gray,
        .badge-count-red {
            color: white;
            border-radius: 20px;
            padding: 0.2rem 0.55rem;
            font-size: 0.68rem;
            margin-left: auto;
            font-weight: 600;
            display: inline-block;
            min-width: 22px;
            text-align: center;
        }

        .badge-count {
            background: #0d6efd;
        }

        .badge-count-cyan {
            background: #0dcaf0;
        }

        .badge-count-green {
            background: #198754;
        }

        .badge-count-gray {
            background: #6c757d;
        }

        .badge-count-red {
            background: #dc3545;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.6;
            }
        }

        /* Modern Cards */
        .modern-card {
            border: none;
            border-radius: 20px;
            transition: all 0.3s ease;
            background: white;
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .modern-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
        }

        /* Stat Icons */
        .stat-icon {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            flex-shrink: 0;
        }

        /* Welcome Banner */
        .welcome-banner {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .welcome-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(13, 110, 253, 0.15) 0%, transparent 70%);
            border-radius: 50%;
        }

        .welcome-banner::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(25, 135, 84, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        /* Activity Items */
        .activity-item {
            padding: 0.75rem;
            border-radius: 12px;
            transition: all 0.2s;
            cursor: pointer;
        }

        .activity-item:hover {
            background: #f8f9fa;
            transform: translateX(5px);
        }

        /* Branch Info */
        .branch-info-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
        }

        /* Owner Card */
        .owner-card {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            border: none;
            border-radius: 20px;
            margin-top: 0.75rem;
        }

        /* Tabs */
        .custom-tabs .nav-link {
            color: #64748b;
            font-weight: 500;
            border: none;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
        }

        .custom-tabs .nav-link:hover {
            color: #0d6efd;
            background: rgba(13, 110, 253, 0.05);
        }

        .custom-tabs .nav-link.active {
            color: #0d6efd;
            background: rgba(13, 110, 253, 0.1);
            border-radius: 12px 12px 0 0;
            border-bottom: 3px solid #0d6efd;
        }

        /* Branch Info Text */
        .branch-info-text {
            font-size: 1.1rem;
            margin-bottom: 0.75rem;
            word-break: break-word;
        }

        .branch-info-label {
            font-weight: 600;
            font-size: 0.95rem;
            color: #1e293b;
        }

                /* Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f0f2f5;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        * {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f0f2f5;
        }

        /* ========================================== */
        /* DESKTOP large */
        /* ========================================== */
        @media (min-width: 1400px) {
            .dash-wrapper {
                padding: 1.75rem 2rem;
            }

            .dash-sidebar {
                width: 260px;
            }
        }

        @media (min-width: 1600px) {
            .dash-wrapper {
                padding: 2rem 3rem;
            }
        }

        /* ========================================== */
        /* TABLET */
        /* ========================================== */
        @media (min-width: 769px) and (max-width: 1024px) {
            .dash-wrapper {
                padding: 1.25rem;
            }

            .dash-sidebar {
                width: 220px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* ========================================== */
        /* MOBILE — sidebar becomes slide-in drawer  */
        /* ========================================== */
        @media (max-width: 768px) {

            body.sidebar-open {
                overflow: hidden;
            }

            body.sidebar-open .dash-sidebar-overlay {
                display: block;
            }

            .dash-wrapper {
                padding: 12px;
                gap: 0;
                display: block;
            }

            .dash-sidebar {
                position: fixed;
                top: 0;
                left: 0;
                margin: 0 !important;
                width: min(82vw, 300px);
                height: 100vh;
                height: 100dvh;
                z-index: 1050;
                padding-top: calc(env(safe-area-inset-top) + 12px);
                padding-bottom: calc(env(safe-area-inset-bottom) + 12px);
                padding-left: 10px;
                padding-right: 10px;
                overflow-y: auto;
                background: #f5f7fb;
                transform: translateX(-105%);
                transition: transform 0.3s ease;
                box-shadow: 4px 0 24px rgba(0, 0, 0, 0.15);
            }

            body.sidebar-open .dash-sidebar {
                transform: translateX(0);
            }

            .sidebar-card .card-header .close-btn {
                display: flex;
            }

            /* Show floating toggle on mobile */
            .dash-toggle-btn {
                display: flex;
            }

            /* Show mobile-only logout item */
            .mobile-only-logout {
                display: block;
            }

            .dash-main {
                width: 100%;
            }

            /* Navbar */
            .navbar-glass {
                padding-top: env(safe-area-inset-top);
            }

            .navbar-glass .container {
                padding-left: 12px;
                padding-right: 12px;
            }

            .navbar-glass .navbar-brand {
                font-size: 1rem !important;
            }

            .navbar-glass .navbar-brand img {
                height: 26px;
            }

            .navbar-glass .navbar-brand small {
                display: block;
                font-size: 0.7rem !important;
                margin-left: 0 !important;
                margin-top: 2px;
            }

            .navbar-toggler {
                border-color: rgba(255, 255, 255, 0.3);
                padding: 0.3rem 0.5rem;
            }

            .navbar-toggler-icon {
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.85%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
                width: 1.2em;
                height: 1.2em;
            }

            .navbar-collapse {
                background: rgba(26, 26, 46, 0.98);
                border-radius: 12px;
                margin-top: 0.6rem;
                padding: 0.75rem;
            }

            .navbar-nav .nav-item {
                margin-bottom: 0.4rem;
            }

            .navbar-nav .nav-link {
                padding: 0.5rem 0.75rem !important;
                font-size: 0.9rem;
            }

            .navbar-nav form button {
                width: 100%;
                font-size: 0.85rem;
            }

            /* Welcome banner */
            .welcome-banner {
                padding: 1.1rem;
                border-radius: 16px;
                margin-bottom: 1rem;
            }

            .welcome-banner h4 {
                font-size: 1.05rem;
            }

            .welcome-banner p {
                font-size: 0.85rem;
            }

            .welcome-banner .col-md-4 {
                margin-top: 0.75rem;
                text-align: left !important;
            }

            .welcome-banner .d-inline-block {
                font-size: 0.8rem;
                padding: 0.5rem 0.9rem !important;
            }

            /* Cards */
            .modern-card,
            .branch-info-card {
                border-radius: 16px;
            }

            .modern-card .card-body,
            .branch-info-card .card-body {
                padding: 1rem;
            }

            .modern-card .card-header {
                padding: 0.85rem 1rem 0.5rem;
                font-size: 0.95rem;
            }

            .branch-info-text {
                font-size: 0.95rem;
            }

            .branch-info-label {
                font-size: 0.85rem;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.75rem;
            }

            .stat-icon {
                width: 42px;
                height: 42px;
                border-radius: 12px;
            }

            .stat-icon i {
                font-size: 1.05rem !important;
            }

            .stats-grid h4 {
                font-size: 1.15rem;
            }

            .stats-grid h6 {
                font-size: 0.7rem;
            }

            .row.g-4>.col-md-4 {
                flex: 0 0 100% !important;
                max-width: 100% !important;
            }

            .custom-tabs {
                flex-wrap: nowrap;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
                padding-bottom: 4px;
            }

            .custom-tabs::-webkit-scrollbar {
                display: none;
            }

            .custom-tabs .nav-item {
                flex-shrink: 0;
            }

            .custom-tabs .nav-link {
                padding: 0.6rem 0.9rem !important;
                font-size: 0.82rem;
                white-space: nowrap;
            }

            .activity-item {
                padding: 0.6rem;
            }

            .activity-item .d-flex {
                flex-wrap: wrap;
                gap: 0.4rem;
            }

            .activity-item strong {
                font-size: 0.9rem;
            }

            .activity-item small {
                font-size: 0.72rem;
            }

            .border-top .col-md-6 {
                text-align: center !important;
                margin-bottom: 0.4rem;
            }

            input:not([type="checkbox"]):not([type="radio"]):not([type="range"]):not([type="file"]),
            select,
            textarea {
                font-size: 16px !important;
            }

            .modal-dialog {
                margin: 0.6rem;
            }

            .modal-content {
                border-radius: 16px;
            }

            /* Leave bottom padding so floating button doesn't overlap content */
            .dash-main {
                padding-bottom: 80px;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .welcome-banner h4 {
                font-size: 1rem;
            }

            .navbar-brand small {
                display: none !important;
            }

            .dash-wrapper {
                padding: 10px;
            }

            .modern-card .card-body {
                padding: 0.85rem;
            }
        }

        @media (max-width: 380px) {
            .dash-wrapper {
                padding: 8px;
            }

            .welcome-banner {
                padding: 0.9rem;
            }

            .welcome-banner h4 {
                font-size: 0.92rem;
            }

            .stat-icon {
                width: 38px;
                height: 38px;
            }

            .stats-grid h4 {
                font-size: 1rem;
            }
        }

        @media print {

            .navbar,
            .dash-sidebar,
            .dash-toggle-btn,
            .owner-card,
            .loading-screen,
            .btn {
                display: none !important;
            }
        }
    </style>
</head>

<body>
    <!-- Loading Screen -->
    <div class="loading-screen" id="loadingScreen">
        <div class="loading-content">
            <div class="loading-logo">
                <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" style="width: 70px; height: auto;">
            </div>
            <div class="loading-title">VAPE EXPO</div>
            <div class="loading-spinner"></div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-glass">
        <div class="container">
            <a class="navbar-brand text-white fw-bold fs-4" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="35"
                    class="d-inline-block align-text-top me-2">
                <span
                    style="background: linear-gradient(135deg, #fff 0%, #a0aec0 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Vape
                    Expo</span>
                <small class="text-white-50 fs-6 ms-2">{{ Auth::user()->branch->name ?? 'Branch' }}</small>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item me-3">
                        <span class="nav-link text-white p-0">
                            <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }} <span
                                class="badge bg-light text-dark ms-1 rounded-pill">Staff</span>
                        </span>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-sm rounded-pill my-1 px-3"
                                style="border-color: rgba(255,255,255,0.3);">
                                <i class="bi bi-box-arrow-right me-1"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="navbar-divider"></div>

    <!-- ============================================ -->
    <!-- WRAPPER: SIDEBAR + MAIN                      -->
    <!-- ============================================ -->
    <div class="dash-wrapper">

        <!-- Sidebar (drawer on mobile) -->
        <aside class="dash-sidebar" id="dashSidebar">
            <div class="sidebar-card">
                <div class="card-header">
                    <span><i class="bi bi-grid me-2"></i> Staff Menu</span>
                    <button type="button" class="close-btn" id="dashSidebarClose" aria-label="Close menu">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('branch-admin.dashboard') }}"
                        class="list-group-item list-group-item-action active">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>

                    <a href="{{ route('branch-admin.inventory.index') }}"
                        class="list-group-item list-group-item-action">
                        <i class="bi bi-box-seam me-2"></i> Inventory
                        @php
                            $inventoryCount = \App\Models\BranchInventory::where(
                                'branch_id',
                                Auth::user()->branch_id,
                            )->count();
                        @endphp
                        @if ($inventoryCount > 0)
                            <span class="badge-count-cyan float-end">{{ $inventoryCount }}</span>
                        @endif
                    </a>

                    <a href="{{ route('branch-admin.inventory.stock-history') }}"
                        class="list-group-item list-group-item-action">
                        <i class="bi bi-clock-history me-2"></i> Stock History
                    </a>

                    <a href="{{ route('branch-admin.inventory.transfer.form') }}"
                        class="list-group-item list-group-item-action">
                        <i class="bi bi-send me-2"></i> Request Transfer
                    </a>

                    <a href="{{ route('branch-admin.inventory.transfers') }}"
                        class="list-group-item list-group-item-action">
                        <i class="bi bi-arrow-left-right me-2"></i> All Transfers
                        @php
                            $pendingTransfersNav = \App\Models\StockTransfer::where(function ($q) {
                                $q->where('from_branch_id', Auth::user()->branch_id)->orWhere(
                                    'to_branch_id',
                                    Auth::user()->branch_id,
                                );
                            })
                                ->where('status', 'pending')
                                ->count();
                        @endphp
                        @if ($pendingTransfersNav > 0)
                            <span class="badge-count-red float-end">{{ $pendingTransfersNav }}</span>
                        @endif
                    </a>

                    <a href="{{ route('branch-admin.products.index') }}"
                        class="list-group-item list-group-item-action">
                        <i class="bi bi-tags me-2"></i> Catalog
                        @php
                            $catalogCount = \App\Models\Product::count();
                        @endphp
                        @if ($catalogCount > 0)
                            <span class="badge-count-green float-end">{{ $catalogCount }}</span>
                        @endif
                    </a>

                    <a href="{{ route('branch-admin.products.create') }}"
                        class="list-group-item list-group-item-action">
                        <i class="bi bi-plus-lg me-2"></i> New Product
                    </a>

                    <a href="{{ route('branch-admin.warehouse.index') }}"
                        class="list-group-item list-group-item-action">
                        <i class="bi bi-house-door me-2"></i> Warehouse Stock
                    </a>

                    <a href="{{ route('branch-admin.online-orders.index') }}"
                        class="list-group-item list-group-item-action">
                        <i class="bi bi-cart me-2"></i> Online Orders
                        @php
                            $onlineOrdersCount = \App\Models\Order::where('branch_id', Auth::user()->branch_id)
                                ->where('order_number', 'NOT LIKE', 'POS-%')
                                ->whereNotIn('order_status', ['delivered', 'delivery_failed', 'cancelled'])
                                ->count();
                        @endphp
                        @if ($onlineOrdersCount > 0)
                            <span class="badge-count-green float-end">{{ $onlineOrdersCount }}</span>
                        @endif
                    </a>

                    <a href="{{ route('branch-admin.pos.index') }}"
                        class="list-group-item list-group-item-action">
                        <i class="bi bi-cash-coin me-2"></i> Point of Sale
                    </a>

                    <a href="{{ route('branch-admin.pos.history') }}"
                        class="list-group-item list-group-item-action">
                        <i class="bi bi-clock-history me-2"></i> Sales History
                    </a>

                    <a href="{{ route('home') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-house me-2"></i> Home
                    </a>

                    {{-- Logout item — visible only on mobile (hidden on desktop via .mobile-only-logout) --}}
                    <form method="POST" action="{{ route('logout') }}" class="mobile-only-logout">
                        @csrf
                        <button type="submit"
                            class="list-group-item list-group-item-action w-100 text-start border-0 bg-transparent">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </button>
                    </form>
                </div>
            </div>

            <!-- Branch Hours Card -->
            <div class="modern-card mt-3">
                <div class="card-header bg-white border-0 pt-3 pb-0">
                    <i class="bi bi-clock me-2 text-primary"></i> <strong>Branch Hours</strong>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Daily:</strong> 9:00 AM – 10:00 PM</p>
                    <p class="mb-0 text-muted"><small>All branches follow same hours</small></p>
                </div>
            </div>

            <!-- Owner Contact Card -->
            <div class="owner-card">
                <div class="card-header bg-transparent border-0 pt-3 pb-0">
                    <i class="bi bi-person-circle me-2 text-white"></i> <strong class="text-white">Shop
                        Owner</strong>
                </div>
                <div class="card-body">
                    <p class="mb-1 text-white"><strong>Carlo Caranto</strong></p>
                    <p class="mb-0 text-white-50"><i class="bi bi-telephone me-2"></i> 0960 328 0432</p>
                </div>
            </div>
        </aside>

        <!-- Overlay for mobile drawer -->
        <div class="dash-sidebar-overlay" id="dashSidebarOverlay"></div>

        <!-- Main Content -->
        <main class="dash-main">

            <!-- Welcome Banner (top of page) -->
            <div class="welcome-banner">
                <div class="row align-items-center position-relative">
                    <div class="col-md-8">
                        <h4 class="text-white mb-2 fw-bold">
                            <i class="bi bi-stars me-2 text-warning"></i>Welcome back, {{ Auth::user()->name }}!
                        </h4>
                        <p class="text-white-50 mb-0">Here's what's happening at
                            {{ Auth::user()->branch->name ?? 'your branch' }} today.</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="d-inline-block bg-white bg-opacity-10 rounded-3 px-4 py-2">
                            <i class="bi bi-calendar3 text-white me-2"></i>
                            <span class="text-white">{{ now()->format('l, F j, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @php
                $branch = Auth::user()->branch;
            @endphp

            @if ($branch)

                <!-- Branch Information Card -->
                <div class="branch-info-card modern-card mb-4">
                    <div class="card-header bg-white border-0 pt-3 pb-0">
                        <i class="bi bi-shop me-2 text-primary"></i> <strong>{{ $branch->name }}</strong>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="branch-info-text"><i
                                        class="bi bi-geo-alt-fill text-danger me-2 fs-5"></i> <span
                                        class="branch-info-label">Address:</span><br>{{ $branch->address }}</p>
                                @php
                                    $landmarks = [
                                        'Majada Out Branch' => 'Near 7-Eleven Majada Out and Gran Avila',
                                        'Asia 1 Branch' => 'Near Hernandez Grocery and Grimaldo',
                                        'MCDC Branch' => 'Near Geosnack and Mango Royale MCDC',
                                        'Paciano Branch' => 'In front of Paciano Barangay Hall and 7‑Eleven Paciano',
                                        'Paciano V2 Branch' => 'Near the area',
                                    ];
                                    $landmark = $landmarks[$branch->name] ?? '';
                                @endphp
                                @if ($landmark)
                                    <p class="branch-info-text"><i
                                            class="bi bi-pin-map-fill text-warning me-2 fs-5"></i> <span
                                            class="branch-info-label">Landmark:</span><br>{{ $landmark }}</p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <p class="branch-info-text"><i
                                        class="bi bi-telephone-fill text-primary me-2 fs-5"></i> <span
                                        class="branch-info-label">Contact:</span><br>{{ $branch->phone ?? '0960 328 0432' }}
                                </p>
                                <p class="branch-info-text"><i
                                        class="bi bi-person-badge-fill text-success me-2 fs-5"></i> <span
                                        class="branch-info-label">Staff:</span><br>{{ Auth::user()->name }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="modern-card">
                    <div class="card-header bg-white border-0 pt-3 pb-0">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-bar-chart me-2 text-primary"></i> Branch Quick
                            Stats</h5>
                    </div>
                    <div class="card-body">
                        <div class="stats-grid">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon" style="background: rgba(13, 110, 253, 0.1); color: #0d6efd;">
                                    <i class="bi bi-box fs-4"></i>
                                </div>
                                <div class="ms-2 ms-md-3">
                                    <h6 class="mb-0 text-muted small">Total Products</h6>
                                    <h4 class="mb-0 fw-bold">{{ $totalProducts ?? 0 }}</h4>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="stat-icon" style="background: rgba(253, 126, 20, 0.1); color: #fd7e14;">
                                    <i class="bi bi-exclamation-triangle fs-4"></i>
                                </div>
                                <div class="ms-2 ms-md-3">
                                    <h6 class="mb-0 text-muted small">Low Stock</h6>
                                    <h4 class="mb-0 fw-bold">{{ $lowStockCount ?? 0 }}</h4>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="stat-icon" style="background: rgba(25, 135, 84, 0.1); color: #198754;">
                                    <i class="bi bi-cart-check fs-4"></i>
                                </div>
                                <div class="ms-2 ms-md-3">
                                    <h6 class="mb-0 text-muted small">Today's Orders</h6>
                                    <h4 class="mb-0 fw-bold">{{ $todayOrders ?? 0 }}</h4>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="stat-icon" style="background: rgba(25, 135, 84, 0.1); color: #198754;">
                                    <i class="bi bi-cash-stack fs-4"></i>
                                </div>
                                <div class="ms-2 ms-md-3">
                                    <h6 class="mb-0 text-muted small">Today's Revenue</h6>
                                    <h4 class="mb-0 fw-bold">₱{{ number_format($todaySales ?? 0, 2) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Stats Row -->
                <div class="row g-3 my-1">
                    <div class="col-md-4">
                        <div class="modern-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1 small">Out of Stock</h6>
                                        <h3 class="mb-0 fw-bold text-danger">{{ $outOfStockCount ?? 0 }}</h3>
                                    </div>
                                    <div class="stat-icon" style="background: rgba(220, 53, 69, 0.1); color: #dc3545;">
                                        <i class="bi bi-x-circle fs-4"></i>
                                    </div>
                                </div>
                                <small class="text-muted">Items needing restock</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="modern-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1 small">Low Stock</h6>
                                        <h3 class="mb-0 fw-bold text-warning">{{ $lowStockCount ?? 0 }}</h3>
                                    </div>
                                    <div class="stat-icon" style="background: rgba(255, 193, 7, 0.1); color: #ffc107;">
                                        <i class="bi bi-exclamation-triangle fs-4"></i>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <small class="text-muted">Items below threshold</small>
                                    @if (($lowStockCount ?? 0) > 0)
                                        <a href="{{ route('branch-admin.inventory.low-stock') }}"
                                            class="btn btn-sm btn-outline-warning rounded-pill px-3">
                                            View <i class="bi bi-arrow-right ms-1"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="modern-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1 small">Pending Transfers</h6>
                                        <h3 class="mb-0 fw-bold text-warning">{{ $pendingTransfersTotal ?? 0 }}</h3>
                                        <div class="mt-1">
                                            <small class="text-success">
                                                <i class="bi bi-download me-1"></i> Incoming:
                                                {{ $pendingTransfersIncoming ?? 0 }}
                                            </small>
                                            <br>
                                            <small class="text-info">
                                                <i class="bi bi-upload me-1"></i> Outgoing:
                                                {{ $pendingTransfersOutgoing ?? 0 }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="stat-icon" style="background: rgba(13, 110, 253, 0.1); color: #0d6efd;">
                                        <i class="bi bi-arrow-left-right fs-4"></i>
                                    </div>
                                </div>
                                <small class="text-muted">Stock transfer requests</small>
                                @if (($pendingTransfersTotal ?? 0) > 0)
                                    <a href="{{ route('branch-admin.inventory.transfers') }}"
                                        class="btn btn-sm btn-outline-primary mt-2 w-100">
                                        View All Transfers <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabs -->
                <div class="modern-card mb-3">
                    <div class="card-body">
                        <ul class="nav nav-tabs custom-tabs mb-3" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="activity-tab" data-bs-toggle="tab"
                                    data-bs-target="#activity" type="button" role="tab"
                                    aria-controls="activity" aria-selected="true">
                                    <i class="bi bi-clock-history me-2"></i> Recent Activity
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="products-tab" data-bs-toggle="tab"
                                    data-bs-target="#products" type="button" role="tab"
                                    aria-controls="products" aria-selected="false">
                                    <i class="bi bi-box-seam me-2"></i> Recently Added Products
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">

                            <div class="tab-pane fade show active" id="activity" role="tabpanel"
                                aria-labelledby="activity-tab">
                                @php
                                    $recentActivities = \App\Models\StockMovement::where('branch_id', $branch->id)
                                        ->with(['product', 'creator'])
                                        ->orderBy('created_at', 'desc')
                                        ->limit(5)
                                        ->get();
                                @endphp

                                @if ($recentActivities->count() > 0)
                                    <div class="list-group list-group-flush">
                                        @foreach ($recentActivities as $activity)
                                            <div class="activity-item">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <i
                                                            class="bi
                                                        @if ($activity->quantity_change > 0) bi-arrow-up-circle-fill text-success
                                                        @else bi-arrow-down-circle-fill text-danger @endif me-2 fs-5">
                                                        </i>
                                                        <strong>{{ $activity->product->name ?? 'Unknown Product' }}</strong>
                                                        <span class="text-muted small">
                                                            @if ($activity->quantity_change > 0)
                                                                +{{ $activity->quantity_change }} units added
                                                            @else
                                                                {{ $activity->quantity_change }} units removed
                                                            @endif
                                                        </span>
                                                        <br>
                                                        <small class="text-muted">
                                                            <i class="bi bi-person"></i>
                                                            {{ $activity->creator->name ?? 'System' }} |
                                                            <i class="bi bi-tag"></i>
                                                            {{ ucfirst(str_replace('_', ' ', $activity->movement_type)) }}
                                                        </small>
                                                    </div>
                                                    <small
                                                        class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="text-center mt-3">
                                        <a href="{{ route('branch-admin.inventory.stock-history') }}"
                                            class="btn btn-outline-primary rounded-pill px-4">
                                            <i class="bi bi-clock-history me-2"></i>View All Activity
                                        </a>
                                    </div>
                                @else
                                    <div class="text-center text-muted py-4">
                                        <i class="bi bi-inbox fs-1 d-block mb-3 text-secondary"></i>
                                        <p>No recent activity to display.</p>
                                        <a href="{{ route('branch-admin.pos.index') }}"
                                            class="btn btn-primary rounded-pill px-4">
                                            <i class="bi bi-cash-coin me-2"></i>Make your first sale
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <div class="tab-pane fade" id="products" role="tabpanel"
                                aria-labelledby="products-tab">
                                @php
                                    $recentProducts = isset($recentProducts) ? $recentProducts->take(5) : collect();
                                @endphp
                                @if ($recentProducts->count() > 0)
                                    <div class="list-group list-group-flush">
                                        @foreach ($recentProducts as $item)
                                            <div class="activity-item">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <i class="bi bi-box-seam me-2 text-primary fs-5"></i>
                                                        <strong>{{ $item->product->name }}</strong>
                                                        <br>
                                                        <small class="text-muted">
                                                            Stock: {{ $item->quantity }} units |
                                                            Price: ₱{{ number_format($item->product->price, 2) }}
                                                        </small>
                                                    </div>
                                                    <small
                                                        class="text-muted">{{ $item->updated_at->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center text-muted py-4">
                                        <i class="bi bi-inbox fs-1 d-block mb-3 text-secondary"></i>
                                        <p>No products added recently.</p>
                                        <a href="{{ route('branch-admin.products.create') }}"
                                            class="btn btn-primary rounded-pill px-4">
                                            <i class="bi bi-plus-circle me-2"></i>Add Your First Product
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Footer -->
            <div class="mt-4 pt-3 text-muted border-top">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-0 small"><i class="bi bi-telephone me-2"></i> For concerns, contact owner:
                            <strong>Carlo Caranto - 0960 328 0432</strong>
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p class="mb-0 small"><i class="bi bi-shield-check me-2"></i> Vape Expo - Authorized
                            Branch Staff</p>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Floating toggle button (mobile only) — right side -->
    <button type="button" class="dash-toggle-btn" id="dashToggleBtn" aria-label="Open menu">
        <i class="bi bi-list"></i>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Loading Screen -->
    <script>
        window.addEventListener('load', function() {
            const loadingScreen = document.getElementById('loadingScreen');
            if (loadingScreen) {
                setTimeout(function() {
                    loadingScreen.style.opacity = '0';
                    setTimeout(function() {
                        loadingScreen.style.display = 'none';
                    }, 600);
                }, 800);
            }
        });
    </script>

    <!-- Sidebar Drawer Behavior (owner-style) -->
    <script>
        (function() {
            const sidebar = document.getElementById('dashSidebar');
            const overlay = document.getElementById('dashSidebarOverlay');
            const openBtn = document.getElementById('dashToggleBtn');
            const closeBtn = document.getElementById('dashSidebarClose');
            if (!sidebar || !overlay || !openBtn) return;

            const isMobile = () => window.innerWidth <= 768;
            const icon = openBtn.querySelector('i');

            function openDrawer() {
                if (!isMobile()) return;
                document.body.classList.add('sidebar-open');
                if (icon) icon.className = 'bi bi-x-lg';
            }

            function closeDrawer() {
                document.body.classList.remove('sidebar-open');
                if (icon) icon.className = 'bi bi-list';
            }

            openBtn.addEventListener('click', function() {
                if (document.body.classList.contains('sidebar-open')) {
                    closeDrawer();
                } else {
                    openDrawer();
                }
            });

            if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
            overlay.addEventListener('click', closeDrawer);

            // Close drawer when tapping any nav link inside the sidebar
            sidebar.querySelectorAll('a, button[type="submit"]').forEach(function(el) {
                el.addEventListener('click', function() {
                    if (isMobile()) closeDrawer();
                });
            });

            // Close drawer if screen resizes to desktop
            window.addEventListener('resize', function() {
                if (!isMobile()) closeDrawer();
            });

            // Escape key closes the drawer
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeDrawer();
            });
        })();
    </script>
</body>

</html>