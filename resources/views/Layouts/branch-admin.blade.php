<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Branch Staff - Vape Expo')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <style>
        /* Base Styles */
        :root {
            --sidebar-width: 260px;
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
            transition: margin-left 0.3s ease;
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
            transition: all 0.3s;
        }

        .toggle-btn:hover {
            background: #0b5ed7;
            transform: scale(1.05);
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

        /* Branch Info Card */
        .branch-info-card {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            margin: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
            transition: all 0.3s ease;
            word-break: break-word;
        }

        .branch-info-card i {
            color: var(--primary-color);
            margin-right: 0.5rem;
            width: 20px;
            flex-shrink: 0;
        }

        .branch-info-card div {
            color: var(--text-dark);
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
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

        /* Badges */
        .badge-count {
            background: var(--primary-color);
            color: white;
            border-radius: 20px;
            padding: 0.2rem 0.6rem;
            font-size: 0.7rem;
            margin-left: 0.5rem;
            font-weight: 500;
            display: inline-block;
        }

        .pending-badge {
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

        /* Dropdown */
        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border-radius: 12px;
            max-height: 400px;
            overflow-y: auto;
        }

        /* Alerts */
        .alert {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
            padding: 1rem;
            margin-bottom: 1rem;
            word-break: break-word;
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
                z-index: 1000;
                height: 100vh;
                margin-left: calc(var(--sidebar-width) * -1);
            }

            .sidebar.active {
                margin-left: 0;
            }

            .toggle-btn {
                bottom: 20px;
                left: 20px;
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
            .branch-info-card,
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
            <button type="button" id="sidebarCollapse" class="btn btn-light me-3">
                <i class="bi bi-list"></i> Menu
            </button>
            <a class="navbar-brand text-white fw-bold fs-4" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="30"
                    class="d-inline-block align-text-top me-2">
                <span
                    style="background: linear-gradient(135deg, #fff 0%, #a0aec0 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Vape
                    Expo</span>
                <small class="text-white-50 fs-6 ms-2">{{ Auth::user()->branch->name ?? 'Branch' }}</small>
            </a>

            <div class="navbar-nav ms-auto">
                <span class="navbar-text text-white me-3">
                    <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }} (Staff)
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm rounded-pill"
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
                <!-- Branch Staff Info -->
                <div class="branch-info-card">
                    <div><i class="bi bi-person-circle"></i> {{ Auth::user()->name }}</div>
                    <div class="small mt-1"><i class="bi bi-envelope"></i> {{ Auth::user()->email }}</div>
                    <div class="small mt-1"><i class="bi bi-shield-check"></i> Branch Staff</div>
                </div>

                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('branch-admin.dashboard') ? 'active' : '' }}"
                            href="{{ route('branch-admin.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>

                    <li class="sidebar-heading">INVENTORY</li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('branch-admin.inventory.index') ? 'active' : '' }}"
                            href="{{ route('branch-admin.inventory.index') }}">
                            <i class="bi bi-box-seam"></i> Inventory
                        </a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('branch-admin.inventory.add-product') ? 'active' : '' }}"
                           href="{{ route('branch-admin.inventory.add-product') }}">
                            <i class="bi bi-plus-circle"></i> Add Stock
                        </a>
                    </li> --}}

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('branch-admin.inventory.stock-history') ? 'active' : '' }}"
                            href="{{ route('branch-admin.inventory.stock-history') }}">
                            <i class="bi bi-clock-history"></i> Stock History
                        </a>
                    </li>

                    <li class="sidebar-heading">TRANSFERS</li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('branch-admin.inventory.transfer.form') ? 'active' : '' }}"
                            href="{{ route('branch-admin.inventory.transfer.form') }}">
                            <i class="bi bi-send"></i> Request Transfer
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('branch-admin.inventory.transfers') ? 'active' : '' }}"
                            href="{{ route('branch-admin.inventory.transfers') }}">
                            <i class="bi bi-arrow-left-right"></i> All Transfers
                        </a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('branch-admin.inventory.transfers', ['filter' => 'incoming']) ? 'active' : '' }}"
                            href="{{ route('branch-admin.inventory.transfers', ['filter' => 'incoming']) }}">
                            <i class="bi bi-download"></i> Incoming
                            @php
                                $pendingIncoming = \App\Models\StockTransfer::where(
                                    'to_branch_id',
                                    Auth::user()->branch_id,
                                )
                                    ->where('status', 'pending')
                                    ->count();
                            @endphp
                            @if ($pendingIncoming > 0)
                                <span class="badge bg-warning pending-badge ms-2">{{ $pendingIncoming }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('branch-admin.inventory.transfers', ['filter' => 'outgoing']) ? 'active' : '' }}"
                            href="{{ route('branch-admin.inventory.transfers', ['filter' => 'outgoing']) }}">
                            <i class="bi bi-upload"></i> Outgoing
                            @php
                                $pendingOutgoing = \App\Models\StockTransfer::where(
                                    'from_branch_id',
                                    Auth::user()->branch_id,
                                )
                                    ->where('status', 'pending')
                                    ->count();
                            @endphp
                            @if ($pendingOutgoing > 0)
                                <span class="badge bg-warning pending-badge ms-2">{{ $pendingOutgoing }}</span>
                            @endif
                        </a>
                    </li> --}}

                    <li class="sidebar-heading">PRODUCTS</li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('branch-admin.products.index') ? 'active' : '' }}"
                            href="{{ route('branch-admin.products.index') }}">
                            <i class="bi bi-tags"></i> Catalog
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('branch-admin.products.create') ? 'active' : '' }}"
                            href="{{ route('branch-admin.products.create') }}">
                            <i class="bi bi-plus-lg"></i> New Product
                        </a>
                    </li>

                    <!-- ===== WAREHOUSE SECTION - ADDED HERE ===== -->
<li class="sidebar-heading">WAREHOUSE</li>
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('branch-admin.warehouse.index') ? 'active' : '' }}"
        href="{{ route('branch-admin.warehouse.index') }}">
        <i class="bi bi-house-door"></i> Warehouse Stock
    </a>
</li>
                    <!-- ===== END OF WAREHOUSE SECTION ===== -->

                    <li class="sidebar-heading">SALES</li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('branch-admin.pos.index') ? 'active' : '' }}"
                            href="{{ route('branch-admin.pos.index') }}">
                            <i class="bi bi-cash-coin"></i> Point of Sale
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('branch-admin.pos.history') ? 'active' : '' }}"
                            href="{{ route('branch-admin.pos.history') }}">
                            <i class="bi bi-clock-history"></i> Sales History
                        </a>
                    </li>

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

                <!-- Footer Info -->
                <div class="footer-info">
                    <div><i class="bi bi-clock"></i> 9AM - 10PM</div>
                    <div><i class="bi bi-telephone"></i> 0960 328 0432</div>
                    <div><i class="bi bi-person-circle"></i> Carlo Caranto</div>
                    <div class="mt-2"><i class="bi bi-shop"></i> 5 Branches</div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Navigation Bar -->
            <div class="top-navbar d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">@yield('page-title', 'Dashboard')</h5>
                    <small class="text-muted">{{ Auth::user()->branch->name }}</small>
                </div>
                <div class="d-flex align-items-center">
                    <span class="me-3 text-muted small">
                        <i class="bi bi-calendar3 me-1"></i> {{ now()->format('M d, Y') }}
                    </span>
                    <span class="me-3 text-muted small">
                        <i class="bi bi-clock me-1"></i> {{ now()->format('h:i A') }}
                    </span>

                    <!-- Low Stock Quick View -->
                    <div class="dropdown">
                        <button class="btn btn-outline-primary btn-sm" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-exclamation-triangle"></i> Stock
                            <span class="badge-count" id="lowStockBadge">0</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('branch-admin.inventory.low-stock') }}">View
                                    Low Stock</a></li>
                        </ul>
                    </div>

                    <!-- Pending Transfers Quick View -->
                    @php
                        $totalPending = \App\Models\StockTransfer::where(function ($q) {
                            $q->where('from_branch_id', Auth::user()->branch_id)->orWhere(
                                'to_branch_id',
                                Auth::user()->branch_id,
                            );
                        })
                            ->where('status', 'pending')
                            ->count();
                    @endphp
                    @if ($totalPending > 0)
                        <div class="ms-2">
                            <a href="{{ route('branch-admin.inventory.transfers', ['filter' => 'all', 'status' => 'pending']) }}"
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

    <script>
        // Sidebar toggle functionality
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleBtn');
        const sidebarCollapse = document.getElementById('sidebarCollapse');

        function toggleSidebar() {
            sidebar.classList.toggle('active');
            const icon = toggleBtn.querySelector('i');
            if (sidebar.classList.contains('active')) {
                icon.classList.remove('bi-chevron-left');
                icon.classList.add('bi-chevron-right');
            } else {
                icon.classList.remove('bi-chevron-right');
                icon.classList.add('bi-chevron-left');
            }
        }

        if (sidebarCollapse) {
            sidebarCollapse.addEventListener('click', toggleSidebar);
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', toggleSidebar);
        }

        // Store sidebar state in localStorage
        const sidebarState = localStorage.getItem('sidebarState');
        if (sidebarState === 'collapsed') {
            sidebar.classList.add('active');
            if (toggleBtn) {
                const icon = toggleBtn.querySelector('i');
                icon.classList.remove('bi-chevron-left');
                icon.classList.add('bi-chevron-right');
            }
        }

        // Save state when toggling
        sidebar.addEventListener('transitionend', function() {
            if (sidebar.classList.contains('active')) {
                localStorage.setItem('sidebarState', 'collapsed');
            } else {
                localStorage.setItem('sidebarState', 'expanded');
            }
        });

        // Auto-collapse on mobile
        if (window.innerWidth <= 768) {
            sidebar.classList.add('active');
            if (toggleBtn) {
                const icon = toggleBtn.querySelector('i');
                icon.classList.remove('bi-chevron-left');
                icon.classList.add('bi-chevron-right');
            }
        }

        // Update low stock badge
        document.addEventListener('DOMContentLoaded', function() {
            const lowStockCount = {{ $lowStockCount ?? 0 }};
            const badge = document.getElementById('lowStockBadge');
            if (badge) {
                badge.textContent = lowStockCount;
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
