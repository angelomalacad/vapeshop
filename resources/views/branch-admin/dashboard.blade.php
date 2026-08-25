<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        body {
            background: #f5f7fb;
        }

        /* ========== LOADING SCREEN STYLES ========== */
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

        /* Glassmorphism Navigation */
        .navbar-glass {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            backdrop-filter: blur(10px);
        }

        /* Modern Sidebar */
        .sidebar-card {
            border: none;
            border-radius: 24px;
            overflow: hidden;
            background: white;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.04);
            padding: 0.5rem;
        }

        .sidebar-card .card-header {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            border-bottom: none;
            padding: 1.25rem 1rem;
            color: white;
            font-weight: 600;
            border-radius: 16px 16px 0 0;
        }

        .sidebar-card .list-group-item {
            background: white;
            color: #4a5568;
            border: none;
            padding: 0.75rem 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin: 4px 8px;
            border-radius: 12px;
        }

        .sidebar-card .list-group-item:hover {
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.05) 0%, rgba(13, 110, 253, 0.08) 100%);
            color: #0d6efd;
            transform: translateX(8px);
        }

        .sidebar-card .list-group-item.active {
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.1) 0%, rgba(13, 110, 253, 0.15) 100%);
            color: #0d6efd;
            font-weight: 600;
            border-left: 3px solid #0d6efd;
        }

        .sidebar-card .list-group-item i {
            width: 24px;
            transition: transform 0.3s ease;
        }

        .sidebar-card .list-group-item:hover i {
            transform: scale(1.1);
        }

        /* Modern Cards */
        .modern-card {
            border: none;
            border-radius: 20px;
            transition: all 0.3s ease;
            background: white;
            overflow: hidden;
        }

        .modern-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
        }

        /* Quick Action Cards - White version */
        .action-card {
            border: none;
            border-radius: 20px;
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .action-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(13, 110, 253, 0.05), transparent);
            transition: left 0.5s ease;
        }

        .action-card:hover::before {
            left: 100%;
        }

        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        }

        .action-card .card-title {
            color: #1a1a2e;
        }

        .action-card .badge {
            background: #f0f2f5 !important;
            color: #4a5568 !important;
            font-weight: 500;
        }

        .action-card .btn-light {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            color: #1a1a2e;
            transition: all 0.3s ease;
        }

        .action-card .btn-light:hover {
            background: #0d6efd;
            border-color: #0d6efd;
            color: white;
        }

        /* Gradient Icons for Stats */
        .stat-icon {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
        }

        .icon-bg-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .icon-bg-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
        }

        .icon-bg-info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }

        .icon-bg-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        .icon-bg-danger {
            background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
            color: white;
        }

        .icon-bg-secondary {
            background: linear-gradient(135deg, #757f9a 0%, #d7dde8 100%);
            color: white;
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

        /* Branch Info Card */
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

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* Pulse Animation */
        @keyframes pulse {
            0% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.8;
                transform: scale(1.05);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        .low-stock-pulse {
            animation: pulse 2s infinite;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #0d6efd, #0b5ed7);
            border-radius: 10px;
        }

        /* Owner Contact Card */
        .owner-card {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            border: none;
            border-radius: 20px;
        }

        /* Adds a sleek divider under the navbar like the image */
        .navbar-divider {
            height: 2px;
            background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.1), transparent);
        }

        /* Tab Styling to match the modern aesthetic */
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

        /* Making Branch Info Text Larger */
        .branch-info-text {
            font-size: 1.1rem;
            margin-bottom: 0.75rem;
        }

        .branch-info-label {
            font-weight: 600;
            font-size: 0.95rem;
            color: #1e293b;
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

    <div class="container-fluid px-4 mt-4">
        <div class="row">
            <!-- Sidebar Menu -->
            <div class="col-lg-2">
                <div class="card sidebar-card">
                    <div class="card-header text-center">
                        <i class="bi bi-grid me-2"></i> Owner Menu
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('branch-admin.dashboard') }}"
                            class="list-group-item list-group-item-action active">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                        <a href="{{ route('branch-admin.inventory.index') }}"
                            class="list-group-item list-group-item-action">
                            <i class="bi bi-box-seam me-2"></i> Inventory Management
                        </a>
                        <a href="{{ route('branch-admin.products.create') }}"
                            class="list-group-item list-group-item-action">
                            <i class="bi bi-plus-circle me-2"></i> Add New Product
                        </a>
                        <a href="{{ route('branch-admin.pos.index') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-cash-coin me-2"></i> Point of Sale
                        </a>
                        <a href="{{ route('branch-admin.pos.history') }}"
                            class="list-group-item list-group-item-action">
                            <i class="bi bi-clock-history me-2"></i> Sales History
                        </a>
                        <a href="{{ route('home') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-house me-2"></i> Back to Home
                        </a>
                    </div>
                </div>

                <!-- Branch Hours Card -->
                <div class="card modern-card mt-3">
                    <div class="card-header bg-white border-0 pt-3 pb-0">
                        <i class="bi bi-clock me-2 text-primary"></i> <strong>Branch Hours</strong>
                    </div>
                    <div class="card-body">
                        <p class="mb-2"><strong>Daily:</strong> 9:00 AM – 10:00 PM</p>
                        <p class="mb-0 text-muted"><small>All branches follow same hours</small></p>
                    </div>
                </div>

                <!-- Owner Contact Card -->
                <div class="card owner-card mt-3">
                    <div class="card-header bg-transparent border-0 pt-3 pb-0">
                        <i class="bi bi-person-circle me-2 text-white"></i> <strong class="text-white">Shop
                            Owner</strong>
                    </div>
                    <div class="card-body">
                        <p class="mb-1 text-white"><strong>Carlo Caranto</strong></p>
                        <p class="mb-0 text-white-50"><i class="bi bi-telephone me-2"></i> 0960 328 0432</p>
                    </div>
                </div>
            </div>

            <!-- Main Content: Widened -->
            <div class="col-lg-10">
                <!-- Welcome Banner -->
                <div class="welcome-banner">
                    <div class="row align-items-center">
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

                    <!-- Branch Information Card (Enlarged Text) -->
                    <div class="card branch-info-card modern-card mb-4">
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
                                            'Paciano Branch' =>
                                                'In front of Paciano Barangay Hall and 7‑Eleven Paciano',
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

                    <!-- Quick Stats Section -->
                    <div class="card modern-card mb-4">
                        <div class="card-header bg-white border-0 pt-3 pb-0">
                            <h5 class="mb-0 fw-bold"><i class="bi bi-bar-chart me-2 text-primary"></i> Branch Quick
                                Stats</h5>
                        </div>
                        <div class="card-body">
                            <div class="stats-grid">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon"
                                        style="background: rgba(13, 110, 253, 0.1); color: #0d6efd;">
                                        <i class="bi bi-box fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-muted small">Total Products</h6>
                                        <h4 class="mb-0 fw-bold">{{ $totalProducts ?? 0 }}</h4>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon"
                                        style="background: rgba(253, 126, 20, 0.1); color: #fd7e14;">
                                        <i class="bi bi-exclamation-triangle fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-muted small">Low Stock</h6>
                                        <h4 class="mb-0 fw-bold">{{ $lowStockCount ?? 0 }}</h4>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon"
                                        style="background: rgba(25, 135, 84, 0.1); color: #198754;">
                                        <i class="bi bi-cart-check fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-muted small">Today's Orders</h6>
                                        <h4 class="mb-0 fw-bold">{{ $todayOrders ?? 0 }}</h4>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon"
                                        style="background: rgba(25, 135, 84, 0.1); color: #198754;">
                                        <i class="bi bi-cash-stack fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-muted small">Today's Revenue</h6>
                                        <h4 class="mb-0 fw-bold">₱{{ number_format($todaySales ?? 0, 2) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Stats Row (Out of Stock, Low Stock, Pending Transfers) -->
                    <div class="row g-4 mb-4">
                        <!-- Out of Stock Card -->
                        <div class="col-md-4">
                            <div class="card modern-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-muted mb-1 small">Out of Stock</h6>
                                            <h3 class="mb-0 fw-bold text-danger">{{ $outOfStockCount ?? 0 }}</h3>
                                        </div>
                                        <div class="stat-icon"
                                            style="background: rgba(220, 53, 69, 0.1); color: #dc3545;">
                                            <i class="bi bi-x-circle fs-4"></i>
                                        </div>
                                    </div>
                                    <small class="text-muted">Items needing restock</small>
                                </div>
                            </div>
                        </div>

                        <!-- Low Stock Card -->
                        <div class="col-md-4">
                            <div class="card modern-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-muted mb-1 small">Low Stock</h6>
                                            <h3 class="mb-0 fw-bold text-warning">{{ $lowStockCount ?? 0 }}</h3>
                                        </div>
                                        <div class="stat-icon"
                                            style="background: rgba(255, 193, 7, 0.1); color: #ffc107;">
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

                        <!-- Pending Transfers Card -->
                        <div class="col-md-4">
                            <div class="card modern-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-muted mb-1 small">Pending Transfers</h6>
                                            <h3 class="mb-0 fw-bold text-warning">{{ $pendingTransfersTotal ?? 0 }}
                                            </h3>
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
                                        <div class="stat-icon"
                                            style="background: rgba(13, 110, 253, 0.1); color: #0d6efd;">
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

                    <!-- Tabs: Recent Activity & Recently Added Products -->
                    <div class="card modern-card mb-4">
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

                                <!-- Tab 1: Recent Activity -->
                                <div class="tab-pane fade show active" id="activity" role="tabpanel"
                                    aria-labelledby="activity-tab">
                                    @php
                                        $recentActivities = \App\Models\StockMovement::where('branch_id', $branch->id)
                                            ->with(['product', 'creator'])
                                            ->orderBy('created_at', 'desc')
                                            ->limit(5) /* CHANGED TO 5 */
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

                                <!-- Tab 2: Recently Added Products -->
                                <div class="tab-pane fade" id="products" role="tabpanel"
                                    aria-labelledby="products-tab">
                                    @php
                                        /* You will need to ensure $recentProducts in your controller is also set to 5,
 or you can limit it here if it's a collection */
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

                <!-- Footer Information -->
                <div class="mt-4 pt-3 text-muted border-top">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-0 small"><i class="bi bi-telephone me-2"></i> For concerns, contact owner:
                                <strong>Carlo Caranto - 0960 328 0432</strong></p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p class="mb-0 small"><i class="bi bi-shield-check me-2"></i> Vape Expo - Authorized
                                Branch Staff</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Loading Screen Script -->
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
</body>

</html>
