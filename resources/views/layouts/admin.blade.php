<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Owner Panel - Vape Expo')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <style>
        /* Base Styles */
        :root {
            --sidebar-width: 290px;
            --sidebar-collapsed-width: 70px;
            --primary-color: #0d6efd;
            --secondary-bg: #f8f9fa;
            --text-dark: #2c3e50;
            --text-muted: #6c757d;
            --border-light: rgba(0, 0, 0, 0.03);
        }

        /* Ensure full height for wrapper and body */
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        /* Wrapper for collapsible sidebar */
        .wrapper {
            display: flex;
            width: 100%;
            height: calc(100% - 56px);
            /* subtract navbar height */
            align-items: stretch;
        }

        /* Sidebar - background scrolls with content */
        .sidebar {
            width: var(--sidebar-width);
            flex-shrink: 0;
            background: linear-gradient(145deg, #f5f7fa 0%, #e9ecef 100%);
            box-shadow: 2px 0 15px rgba(0, 0, 0, 0.03);
            transition: margin-left 0.3s ease, width 0.3s ease;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar.active {
            margin-left: calc(var(--sidebar-width) * -1);
        }

        /* Sidebar content – normal block */
        .sidebar-sticky {
            flex: 1;
            padding: 0.5rem 0;
        }

        /* DeepSeek‑style scrollbar for the whole page and sidebar */
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
            transition: background 0.2s;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* For Firefox (modern) */
        * {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f0f2f5;
        }

        /* Navigation items – unchanged */
        .sidebar .nav-link {
            font-weight: 500;
            color: var(--text-dark);
            padding: 0.75rem 1.25rem;
            transition: all 0.2s ease;
            margin: 2px 12px;
            border-radius: 8px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar .nav-link:hover {
            color: var(--primary-color);
            background: rgba(13, 110, 253, 0.05);
            transform: translateX(5px);
        }

        .sidebar .nav-link.active {
            color: var(--primary-color);
            background: rgba(13, 110, 253, 0.1);
            font-weight: 600;
        }

        .sidebar .nav-link i {
            color: var(--primary-color);
            margin-right: 0.75rem;
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
            flex-shrink: 0;
        }

        .sidebar-heading {
            font-size: 0.7rem;
            text-transform: uppercase;
            color: var(--text-muted);
            padding: 0.75rem 1.25rem 0.25rem;
            margin-top: 0.5rem;
            letter-spacing: 0.5px;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Owner Info Card */
        .owner-info-card {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            margin: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
            transition: all 0.3s ease;
            word-break: break-word;
        }

        .owner-info-card i {
            color: var(--primary-color);
            margin-right: 0.5rem;
            width: 20px;
            flex-shrink: 0;
        }

        .owner-info-card div {
            color: var(--text-dark);
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        /* Badge Counts */
        .badge-count {
            color: white;
            border-radius: 20px;
            padding: 0.2rem 0.55rem;
            font-size: 0.7rem;
            margin-left: auto;
            font-weight: 600;
            display: inline-block;
            min-width: 24px;
            text-align: center;
        }

        .badge-count-cyan {
            background: #0dcaf0;
            color: white;
            border-radius: 20px;
            padding: 0.2rem 0.55rem;
            font-size: 0.7rem;
            margin-left: auto;
            font-weight: 600;
            display: inline-block;
            min-width: 24px;
            text-align: center;
        }

        .badge-count-green {
            background: #198754;
            color: white;
            border-radius: 20px;
            padding: 0.2rem 0.55rem;
            font-size: 0.7rem;
            margin-left: auto;
            font-weight: 600;
            display: inline-block;
            min-width: 24px;
            text-align: center;
        }

        .badge-count-gray {
            background: #6c757d;
            color: white;
            border-radius: 20px;
            padding: 0.2rem 0.55rem;
            font-size: 0.7rem;
            margin-left: auto;
            font-weight: 600;
            display: inline-block;
            min-width: 24px;
            text-align: center;
        }

        .badge-count-red {
            background: #dc3545;
            color: white;
            border-radius: 20px;
            padding: 0.2rem 0.55rem;
            font-size: 0.7rem;
            margin-left: auto;
            font-weight: 600;
            display: inline-block;
            min-width: 24px;
            text-align: center;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.6;
            }

            100% {
                opacity: 1;
            }
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 20px 25px;
            background: var(--secondary-bg);
            overflow-y: auto;
            width: 100%;
            box-sizing: border-box;
        }

        /* Toggle Button */
        .toggle-btn {
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 1000;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 50%;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .toggle-btn:hover {
            background: #0b5ed7;
            transform: scale(1.05);
        }

        .toggle-btn i {
            transition: transform 0.3s ease;
        }

        /* Navbar adjustments */
        .navbar-brand {
            font-size: 1.2rem;
            font-weight: 700;
        }

        /* Container inside main content */
        .main-content .container-fluid {
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
            box-sizing: border-box;
        }

        /* Card fixes */
        .main-content .card {
            width: 100%;
            margin-bottom: 1rem;
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
            border-radius: 12px;
            background: white;
            box-sizing: border-box;
        }

        .main-content .card-header {
            background: white;
            border-bottom: 1px solid var(--border-light);
            padding: 1rem 1.25rem;
        }

        .main-content .card-body {
            padding: 1.25rem;
            box-sizing: border-box;
        }

        /* Table fixes */
        .main-content .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: 8px;
            box-sizing: border-box;
        }

        .main-content .table {
            width: 100%;
            margin-bottom: 0;
            background-color: transparent;
            border-collapse: collapse;
            box-sizing: border-box;
        }

        .main-content .table th,
        .main-content .table td {
            padding: 0.75rem;
            vertical-align: middle;
            border-top: 1px solid #dee2e6;
            white-space: nowrap;
        }

        .main-content .table thead th {
            background-color: #f8f9fa;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
        }

        /* Stock info grid */
        .stock-info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
            border: 1px solid var(--border-light);
        }

        .stock-info-item {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .stock-info-label {
            color: var(--text-muted);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stock-info-value {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        /* Footer Info – normal block, no sticky */
        .footer-info {
            margin: 1rem;
            padding: 1rem;
            background: white;
            border-radius: 12px;
            color: var(--text-dark);
            font-size: 0.9rem;
            word-break: break-word;
        }

        .footer-info i {
            color: var(--primary-color);
            margin-right: 0.5rem;
            width: 20px;
            flex-shrink: 0;
        }

        .footer-info div {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        /* Top Navigation */
        .top-navbar {
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .top-navbar>div:first-child {
            flex: 1;
            min-width: 200px;
        }

        .top-navbar>div:last-child {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.5rem;
        }

        /* Buttons */
        .btn-outline-primary {
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
            border-radius: 20px;
            padding: 0.3rem 1rem;
            white-space: nowrap;
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            color: white;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                z-index: 999;
                height: 100vh;
                margin-left: calc(var(--sidebar-width) * -1);
                width: var(--sidebar-width);
                transition: margin-left 0.3s ease;
            }

            .sidebar.active {
                margin-left: 0;
            }

            .toggle-btn {
                bottom: 20px;
                left: 20px;
                z-index: 1001;
            }

            .main-content {
                padding: 15px;
            }

            .top-navbar {
                flex-direction: column;
            }

            .top-navbar>div:last-child {
                width: 100%;
                justify-content: flex-start;
            }

            .stock-info-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.75rem;
                padding: 1rem;
            }
        }

        @media (max-width: 480px) {
            .main-content {
                padding: 10px;
            }

            .stock-info-grid {
                grid-template-columns: 1fr;
            }

            .stock-info-item {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }
        }

        /* Print Styles */
        @media print {

            .sidebar,
            .top-navbar,
            .footer-info,
            .owner-info-card,
            .btn,
            .toggle-btn {
                display: none !important;
            }

            .main-content {
                margin: 0;
                padding: 0;
            }

            .card {
                break-inside: avoid;
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }

        /* Glassmorphism Navigation */
        .navbar-glass {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            backdrop-filter: blur(10px);
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-glass">
        <div class="container-fluid px-4">
            <!-- Left Side: Menu Button & Logo -->
            <div class="d-flex align-items-center">
                <button type="button" id="sidebarCollapse" class="btn btn-light me-3">
                    <i class="bi bi-list"></i> Menu
                </button>
                <a class="navbar-brand text-white fw-bold fs-4 d-flex align-items-center" href="{{ route('home') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="30"
                        class="d-inline-block me-2">
                    <span
                        style="background: linear-gradient(135deg, #fff 0%, #a0aec0 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Vape
                        Expo</span>
                    <small class="text-white-50 fs-6 ms-2 fw-normal">Owner Panel</small>
                </a>
            </div>

            <!-- Right Side: User Info & Logout (Properly Aligned) -->
            <div class="d-flex align-items-center ms-auto">
                <span class="navbar-text text-white me-3 d-flex align-items-center">
                    <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }} <span
                        class="badge bg-light text-dark ms-2 rounded-pill px-3 py-1 fw-normal"
                        style="background: rgba(255,255,255,0.15) !important; color: white !important;">Owner</span>
                </span>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm rounded-pill px-3"
                        style="border-color: rgba(255,255,255,0.3);">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="wrapper">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-sticky">
                <!-- Owner Info -->
                <div class="owner-info-card">
                    <div><i class="bi bi-person-circle"></i> {{ Auth::user()->name }}</div>
                    <div class="small mt-1"><i class="bi bi-envelope"></i> {{ Auth::user()->email }}</div>
                    <div class="small mt-1"><i class="bi bi-shield-check"></i> Owner</div>
                </div>

                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                            href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>

                    <li class="sidebar-heading">MANAGEMENT</li>
                    @if (Route::has('admin.branch-admin.index'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.branch-admin.*') ? 'active' : '' }}"
                                href="{{ route('admin.branch-admin.index') }}">
                                <i class="bi bi-people"></i> Branch Personnel
                                @php $branchAdminCount = \App\Models\User::whereIn('role', ['branch_admin', 'staff'])->count(); @endphp
                                @if ($branchAdminCount > 0)
                                    <span class="badge-count-cyan">{{ $branchAdminCount }}</span>
                                @endif
                            </a>
                        </li>
                    @endif

                    @if (Route::has('admin.customers.index'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}"
                                href="{{ route('admin.customers.index') }}">
                                <i class="bi bi-people-fill"></i> Customers
                                @php $customerCount = \App\Models\User::where('role', 'customer')->count(); @endphp
                                @if ($customerCount > 0)
                                    <span class="badge-count-green">{{ $customerCount }}</span>
                                @endif
                            </a>
                        </li>
                    @endif

                    @if (Route::has('admin.products.index'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}"
                                href="{{ route('admin.products.index') }}">
                                <i class="bi bi-box"></i> Products
                                @php $productCount = \App\Models\Product::count(); @endphp
                                @if ($productCount > 0)
                                    <span class="badge-count-cyan">{{ $productCount }}</span>
                                @endif
                            </a>
                        </li>
                    @endif

                    <li class="sidebar-heading">INVENTORY</li>
                    @if (Route::has('admin.inventory.index'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.inventory.index') ? 'active' : '' }}"
                                href="{{ route('admin.inventory.index') }}">
                                <i class="bi bi-clipboard-data"></i> Inventory Overview
                                @php $totalItems = \App\Models\BranchInventory::count(); @endphp
                                @if ($totalItems > 0)
                                    <span class="badge-count-gray">{{ $totalItems }}</span>
                                @endif
                            </a>
                        </li>
                    @endif

                    <li class="sidebar-heading">QUICK LINKS</li>
                    @if (Route::has('admin.inventory.low-stock'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.inventory.low-stock') ? 'active' : '' }}"
                                href="{{ route('admin.inventory.low-stock') }}">
                                <i class="bi bi-exclamation-triangle"></i> Low Stock Alert
                                @php $lowStockCountNav = \App\Models\BranchInventory::whereColumn('quantity', '<=', 'low_stock_threshold')->count(); @endphp
                                @if ($lowStockCountNav > 0)
                                    <span class="badge-count-red">{{ $lowStockCountNav }}</span>
                                @endif
                            </a>
                        </li>
                    @endif

                    @if (Route::has('admin.inventory.transfers'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.inventory.transfers') ? 'active' : '' }}"
                                href="{{ route('admin.inventory.transfers') }}">
                                <i class="bi bi-arrow-left-right"></i> Stock Transfers
                                @php $pendingTransfersNav = \App\Models\StockTransfer::where('status', 'pending')->count(); @endphp
                                @if ($pendingTransfersNav > 0)
                                    <span class="badge-count-red">{{ $pendingTransfersNav }}</span>
                                @endif
                            </a>
                        </li>
                    @endif

                    @if (Route::has('admin.inventory.stock-history'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.inventory.stock-history') ? 'active' : '' }}"
                                href="{{ route('admin.inventory.stock-history') }}">
                                <i class="bi bi-clock-history"></i> Stock History
                            </a>
                        </li>
                    @endif

                    <li class="sidebar-heading">WAREHOUSE</li>
                    @if (Route::has('admin.warehouse.index'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.warehouse.*') ? 'active' : '' }}"
                                href="{{ route('admin.warehouse.index') }}">
                                <i class="bi bi-house-door"></i> Warehouse Stock
                            </a>
                        </li>
                    @endif

                    <li class="sidebar-heading">DELIVERIES</li>
                    @if (Route::has('admin.driver-shifts.index'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.driver-shifts.*') ? 'active' : '' }}"
                                href="{{ route('admin.driver-shifts.index') }}">
                                <i class="bi bi-calendar-check"></i> Driver Shifts
                            </a>
                        </li>
                    @endif

                    <li class="sidebar-heading">TRANSACTIONS</li>
                    @if (Route::has('admin.pos.history'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.pos.history') ? 'active' : '' }}"
                                href="{{ route('admin.pos.history') }}">
                                <i class="bi bi-clock-history"></i> POS Sales History
                            </a>
                        </li>
                    @endif

                    @if (Route::has('admin.online-orders.index'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.online-orders.*') ? 'active' : '' }}"
                                href="{{ route('admin.online-orders.index') }}">
                                <i class="bi bi-cart"></i> Online Orders
                            </a>
                        </li>
                    @endif

                    @if (Route::has('admin.deliveries.index'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.deliveries.*') ? 'active' : '' }}"
                                href="{{ route('admin.deliveries.index') }}">
                                <i class="bi bi-truck"></i> Delivery History
                            </a>
                        </li>
                    @endif

                    <li class="sidebar-heading">ACCOUNT</li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="bi bi-house"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>

                {{-- <!-- Footer Info -->
                <div class="footer-info">
                    <div><i class="bi bi-clock"></i> 9AM - 10PM</div>
                    <div><i class="bi bi-telephone"></i> 0960 328 0432</div>
                    <div><i class="bi bi-person-circle"></i> Carlo Caranto</div>
                    <div class="mt-2"><i class="bi bi-shop"></i> 5 Branches</div>
                </div>
            </div> --}}
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Navigation Bar -->
            <div class="top-navbar d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">@yield('page-title', 'Owner Dashboard')</h5>
                    <small class="text-muted">Owner Panel</small>
                </div>
                <div class="d-flex align-items-center">
                    <span class="me-3 text-muted small">
                        <i class="bi bi-calendar3 me-1"></i> {{ now()->format('M d, Y') }}
                    </span>
                    <span class="me-3 text-muted small">
                        <i class="bi bi-clock me-1"></i> {{ now()->format('h:i A') }}
                    </span>

                    <!-- Low Stock Quick View -->
                    @php
                        $lowStockCount = \App\Models\BranchInventory::whereColumn(
                            'quantity',
                            '<=',
                            'low_stock_threshold',
                        )->count();
                    @endphp
                    <div class="dropdown">
                        <button class="btn btn-outline-primary btn-sm" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-exclamation-triangle"></i> Low Stock
                            <span class="{{ $lowStockCount > 0 ? 'badge-count-red' : 'badge-count-gray' }}"
                                id="lowStockBadge">{{ $lowStockCount }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('admin.inventory.low-stock') }}">View
                                    Low Stock</a></li>
                        </ul>
                    </div>

                    <!-- Pending Transfers Quick View -->
                    @php
                        $totalPending = \App\Models\StockTransfer::where('status', 'pending')->count();
                    @endphp
                    @if ($totalPending > 0)
                        <div class="ms-2">
                            <a href="{{ route('admin.inventory.transfers', ['filter' => 'all', 'status' => 'pending']) }}"
                                class="btn btn-warning btn-sm pending-badge">
                                <i class="bi bi-hourglass"></i> {{ $totalPending }} Pending
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Flash Messages -->
            @if (session('success'))
                <div class="alert alert-success">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close float-end" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close float-end" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Please check the form:</strong>
                    <button type="button" class="btn-close float-end" data-bs-dismiss="alert"></button>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Page Content -->
            @yield('content')
        </main>
    </div>

    <!-- Toggle Button for Mobile -->
    <div class="toggle-btn" id="toggleBtn">
        <i class="bi bi-chevron-left"></i>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- ============================================================ -->
    <!-- NOTIFICATION SYSTEM - EMBEDDED DIRECTLY (Fixes routing issue) -->
    <!-- ============================================================ -->
    <script>
        (function() {
            // Add CSS only once
            if (!document.querySelector('#admin-notification-styles')) {
                const style = document.createElement('style');
                style.id = 'admin-notification-styles';
                style.textContent = `
                .admin-notification-container {
                    position: fixed;
                    top: 24px;
                    right: 24px;
                    z-index: 9999;
                    display: flex;
                    flex-direction: column;
                    gap: 12px;
                    pointer-events: none;
                }
                .admin-notification {
                    pointer-events: auto;
                    position: relative;
                    width: 380px;
                    background: white;
                    border-radius: 16px;
                    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
                    overflow: hidden;
                    animation: notificationSlideIn 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
                }
                .admin-notification-hide {
                    animation: notificationSlideOut 0.3s ease forwards;
                }
                @keyframes notificationSlideIn {
                    0% { transform: translateX(100%) scale(0.8); opacity: 0; }
                    100% { transform: translateX(0) scale(1); opacity: 1; }
                }
                @keyframes notificationSlideOut {
                    0% { transform: translateX(0) scale(1); opacity: 1; }
                    100% { transform: translateX(100%) scale(0.8); opacity: 0; }
                }
                @keyframes progressShrink {
                    from { width: 100%; }
                    to { width: 0%; }
                }
                .admin-notification-inner {
                    display: flex;
                    align-items: center;
                    gap: 14px;
                    padding: 16px 18px;
                }
                .admin-notification-icon-wrapper {
                    width: 40px;
                    height: 40px;
                    border-radius: 12px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    flex-shrink: 0;
                }
                .admin-notification-icon-wrapper i { font-size: 1.4rem; }
                .admin-notification-icon-wrapper.success { background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); }
                .admin-notification-icon-wrapper.success i { color: #059669; }
                .admin-notification-icon-wrapper.error { background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); }
                .admin-notification-icon-wrapper.error i { color: #dc2626; }
                .admin-notification-icon-wrapper.warning { background: linear-gradient(135deg, #fed7aa 0%, #fdba74 100%); }
                .admin-notification-icon-wrapper.warning i { color: #ea580c; }
                .admin-notification-icon-wrapper.info { background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); }
                .admin-notification-icon-wrapper.info i { color: #2563eb; }
                .admin-notification-content { flex: 1; }
                .admin-notification-title { font-size: 0.875rem; font-weight: 700; margin-bottom: 4px; }
                .admin-notification.success .admin-notification-title { color: #059669; }
                .admin-notification.error .admin-notification-title { color: #dc2626; }
                .admin-notification.warning .admin-notification-title { color: #ea580c; }
                .admin-notification.info .admin-notification-title { color: #2563eb; }
                .admin-notification-message { font-size: 0.8rem; color: #475569; line-height: 1.4; }
                .admin-notification-close {
                    background: transparent;
                    border: none;
                    cursor: pointer;
                    padding: 4px;
                    border-radius: 8px;
                    color: #94a3b8;
                    flex-shrink: 0;
                }
                .admin-notification-close:hover { background: #f1f5f9; color: #475569; }
                .admin-notification-close i { font-size: 0.9rem; }
                .admin-notification-progress { height: 3px; width: 100%; animation: progressShrink 4s linear forwards; }
                .admin-notification-progress.success { background: linear-gradient(90deg, #10b981, #34d399); }
                .admin-notification-progress.error { background: linear-gradient(90deg, #ef4444, #f87171); }
                .admin-notification-progress.warning { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
                .admin-notification-progress.info { background: linear-gradient(90deg, #3b82f6, #60a5fa); }
                .is-invalid { border-color: #dc2626 !important; }
                .invalid-feedback { display: block; width: 100%; margin-top: 0.25rem; font-size: 0.75rem; color: #dc2626; }
                @media (max-width: 480px) {
                    .admin-notification-container { top: 16px; right: 16px; left: 16px; }
                    .admin-notification { width: auto; }
                    .admin-notification-inner { padding: 12px 14px; gap: 10px; }
                    .admin-notification-icon-wrapper { width: 34px; height: 34px; }
                    .admin-notification-icon-wrapper i { font-size: 1.1rem; }
                }
            `;
                document.head.appendChild(style);
            }

            // Global showNotification function
            window.showNotification = function(message, type = 'success') {
                let container = document.querySelector('.admin-notification-container');
                if (!container) {
                    container = document.createElement('div');
                    container.className = 'admin-notification-container';
                    document.body.appendChild(container);
                }

                const notification = document.createElement('div');
                notification.className = `admin-notification admin-notification-${type}`;

                let icon = '';
                let title = '';

                switch (type) {
                    case 'success':
                        icon = 'bi-check-circle-fill';
                        title = 'Success';
                        break;
                    case 'error':
                        icon = 'bi-x-circle-fill';
                        title = 'Error';
                        break;
                    case 'warning':
                        icon = 'bi-exclamation-triangle-fill';
                        title = 'Warning';
                        break;
                    case 'info':
                        icon = 'bi-info-circle-fill';
                        title = 'Info';
                        break;
                    default:
                        icon = 'bi-info-circle-fill';
                        title = 'Notice';
                        type = 'info';
                }

                notification.innerHTML = `
                <div class="admin-notification-inner">
                    <div class="admin-notification-icon-wrapper ${type}">
                        <i class="bi ${icon}"></i>
                    </div>
                    <div class="admin-notification-content">
                        <div class="admin-notification-title">${title}</div>
                        <div class="admin-notification-message">${message}</div>
                    </div>
                    <button class="admin-notification-close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="admin-notification-progress ${type}"></div>
            `;

                container.appendChild(notification);

                const progressBar = notification.querySelector('.admin-notification-progress');
                if (progressBar) {
                    progressBar.style.animation = 'progressShrink 4s linear forwards';
                }

                const dismissNotification = (notif) => {
                    notif.classList.add('admin-notification-hide');
                    setTimeout(() => {
                        if (notif && notif.parentElement) {
                            notif.remove();
                        }
                    }, 300);
                };

                const timeoutId = setTimeout(() => {
                    dismissNotification(notification);
                }, 4000);

                const closeBtn = notification.querySelector('.admin-notification-close');
                closeBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    clearTimeout(timeoutId);
                    dismissNotification(notification);
                });

                notification.addEventListener('click', (e) => {
                    if (e.target === notification || e.target.closest('.admin-notification-content')) {
                        clearTimeout(timeoutId);
                        dismissNotification(notification);
                    }
                });

                notification.addEventListener('mouseenter', () => {
                    if (progressBar) {
                        progressBar.style.animationPlayState = 'paused';
                    }
                    clearTimeout(timeoutId);
                });

                notification.addEventListener('mouseleave', () => {
                    if (progressBar) {
                        progressBar.style.animationPlayState = 'running';
                    }
                    const newTimeoutId = setTimeout(() => {
                        dismissNotification(notification);
                    }, 2000);
                    notification._timeoutId = newTimeoutId;
                });
            };

            console.log('✅ Admin Notification System embedded successfully!');
        })();
    </script>
    <!-- ============================================================ -->
    <!-- END OF NOTIFICATION SYSTEM EMBED                                 -->
    <!-- ============================================================ -->

    <!-- Global Functions for Modals -->
    <script>
        // Global function for edit modal submission
        window.submitEditForm = function(id) {
            console.log('submitEditForm called with id:', id);
            const form = document.getElementById('editForm' + id);
            if (!form) {
                console.error('Form not found with id: editForm' + id);
                if (typeof window.showNotification === 'function') {
                    window.showNotification('Form not found. Please refresh and try again.', 'error');
                } else {
                    alert('Form not found. Please refresh and try again.');
                }
                return;
            }

            // Disable button to prevent double submission
            const submitBtn = form.querySelector('.btn-update');
            if (!submitBtn) {
                console.error('Submit button not found in form');
                if (typeof window.showNotification === 'function') {
                    window.showNotification('Submit button not found.', 'error');
                } else {
                    alert('Submit button not found.');
                }
                return;
            }

            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Updating...';

            // Show processing notification
            if (typeof window.showNotification === 'function') {
                window.showNotification('Updating inventory settings...', 'info');
            }

            const formData = new FormData(form);
            formData.append('_method', 'PUT');

            fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(response => {
                    // Check if response is JSON
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error('Server returned non-JSON response. Please check your controller.');
                    }
                    return response.json();
                })
                .then(data => {
                    // Re-enable button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;

                    if (data.success) {
                        // Show success notification
                        if (typeof window.showNotification === 'function') {
                            window.showNotification(
                                data.message || 'Inventory updated successfully!',
                                'success'
                            );
                        } else {
                            alert('Success: ' + (data.message || 'Inventory updated successfully!'));
                        }

                        // Close modal
                        const modalElement = document.querySelector('.modal.show');
                        if (modalElement) {
                            const modal = bootstrap.Modal.getInstance(modalElement);
                            if (modal) modal.hide();
                        }
                        // Remove backdrop
                        const backdrop = document.querySelector('.modal-backdrop');
                        if (backdrop) backdrop.remove();
                        document.body.classList.remove('modal-open');

                        // RELOAD THE PAGE AFTER 1.5 SECONDS to show updated data
                        console.log('Reloading page in 1.5 seconds...');
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        // Handle validation errors
                        if (data.errors) {
                            // Clear previous errors
                            document.querySelectorAll('.is-invalid').forEach(el => {
                                el.classList.remove('is-invalid');
                            });
                            document.querySelectorAll('.invalid-feedback').forEach(el => {
                                el.remove();
                            });

                            // Show new errors
                            let errorMsg = '';
                            for (const [field, errors] of Object.entries(data.errors)) {
                                errorMsg += errors[0] + '\n';
                                const input = document.querySelector(`[name="${field}"]`);
                                if (input) {
                                    input.classList.add('is-invalid');
                                    const feedback = document.createElement('div');
                                    feedback.className = 'invalid-feedback';
                                    feedback.innerText = errors[0];
                                    input.parentNode.insertBefore(feedback, input.nextSibling);
                                }
                            }
                            if (typeof window.showNotification === 'function') {
                                window.showNotification(errorMsg || data.message || 'Validation failed', 'error');
                            } else {
                                alert('Validation Error: ' + errorMsg);
                            }
                        } else {
                            if (typeof window.showNotification === 'function') {
                                window.showNotification(data.message || 'Update failed', 'error');
                            } else {
                                alert('Error: ' + (data.message || 'Update failed'));
                            }
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                    if (typeof window.showNotification === 'function') {
                        // Show a user-friendly error message
                        let errorMessage = 'Network error. Please try again.';
                        if (error.message.includes('non-JSON')) {
                            errorMessage = 'Server error. Please check your controller.';
                        }
                        window.showNotification(errorMessage, 'error');
                    } else {
                        alert('Error: ' + error.message);
                    }
                });
        };

        // SIMPLE EVENT DELEGATION - Click handler at document level
        document.addEventListener('click', function(e) {
            // Check if the clicked element or its parent has the .btn-update class and data-inventory-id attribute
            const btn = e.target.closest('.btn-update[data-inventory-id]');
            if (btn) {
                e.preventDefault();
                const id = btn.getAttribute('data-inventory-id');
                console.log('Update button clicked for inventory ID:', id);
                if (typeof window.submitEditForm === 'function') {
                    window.submitEditForm(id);
                }
            }
        });

        // Log to confirm script loaded
        console.log('Modal edit script loaded successfully');
        console.log('submitEditForm available:', typeof window.submitEditForm === 'function');
    </script>

    <!-- ============================================================ -->
    <!-- SIDEBAR TOGGLE FUNCTIONALITY - FIXED WITH LEFT/RIGHT ICONS     -->
    <!-- ============================================================ -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get elements
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('toggleBtn');
            const sidebarCollapseBtn = document.getElementById('sidebarCollapse');

            // Check if elements exist
            if (!sidebar || !toggleBtn || !sidebarCollapseBtn) {
                console.error('Required elements not found!');
                return;
            }

            // Function to toggle sidebar
            function toggleSidebar() {
                sidebar.classList.toggle('active');

                // Update toggle button icon - LEFT/RIGHT arrows
                const icon = toggleBtn.querySelector('i');
                if (sidebar.classList.contains('active')) {
                    // Sidebar is hidden - show RIGHT arrow (chevron-right) to open
                    icon.className = 'bi bi-chevron-right';
                } else {
                    // Sidebar is visible - show LEFT arrow (chevron-left) to close
                    icon.className = 'bi bi-chevron-left';
                }

                // Save state to localStorage for persistence
                const isCollapsed = sidebar.classList.contains('active');
                localStorage.setItem('sidebarCollapsed', isCollapsed);

                console.log('Sidebar toggled. Active:', sidebar.classList.contains('active'));
            }

            // Event listeners for both toggle buttons
            toggleBtn.addEventListener('click', toggleSidebar);
            sidebarCollapseBtn.addEventListener('click', toggleSidebar);

            // Restore sidebar state from localStorage
            const savedState = localStorage.getItem('sidebarCollapsed');
            if (savedState === 'true') {
                sidebar.classList.add('active');
                const icon = toggleBtn.querySelector('i');
                icon.className = 'bi bi-chevron-right';
            }

            // Handle mobile responsive: auto-close sidebar on mobile when clicking main content
            const mainContent = document.querySelector('.main-content');
            if (mainContent) {
                mainContent.addEventListener('click', function(e) {
                    // Only on mobile devices
                    if (window.innerWidth <= 768) {
                        if (!sidebar.classList.contains('active')) {
                            // If sidebar is visible, close it
                            sidebar.classList.add('active');
                            const icon = toggleBtn.querySelector('i');
                            icon.className = 'bi bi-chevron-right';
                            localStorage.setItem('sidebarCollapsed', 'true');
                        }
                    }
                });
            }

            // Handle window resize - adjust behavior for mobile
            let resizeTimeout;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(function() {
                    if (window.innerWidth > 768) {
                        // On desktop, if sidebar is hidden, show it
                        if (sidebar.classList.contains('active')) {
                            sidebar.classList.remove('active');
                            const icon = toggleBtn.querySelector('i');
                            icon.className = 'bi bi-chevron-left';
                            localStorage.setItem('sidebarCollapsed', 'false');
                        }
                    } else {
                        // On mobile, if sidebar is visible, add active class to hide it
                        if (!sidebar.classList.contains('active')) {
                            sidebar.classList.add('active');
                            const icon = toggleBtn.querySelector('i');
                            icon.className = 'bi bi-chevron-right';
                            localStorage.setItem('sidebarCollapsed', 'true');
                        }
                    }
                }, 250);
            });

            // Initialize on mobile
            if (window.innerWidth <= 768 && !sidebar.classList.contains('active')) {
                sidebar.classList.add('active');
                const icon = toggleBtn.querySelector('i');
                icon.className = 'bi bi-chevron-right';
                localStorage.setItem('sidebarCollapsed', 'true');
            }

            // Log success
            console.log('✅ Sidebar toggle functionality initialized!');
        });
    </script>
    <!-- ============================================================ -->
    <!-- END OF SIDEBAR TOGGLE FUNCTIONALITY                              -->
    <!-- ============================================================ -->

    @stack('scripts')
</body>

</html>
