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
        --border-light: rgba(0,0,0,0.03);
    }

    /* Sidebar - Desktop */
    .sidebar {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        z-index: 1000;
        padding: 60px 0 0;
        width: var(--sidebar-width);
        background: linear-gradient(145deg, #f5f7fa 0%, #e9ecef 100%);
        box-shadow: 2px 0 15px rgba(0,0,0,0.03);
        transition: all 0.3s ease;
        overflow-y: auto;
    }

    .sidebar-sticky {
        position: relative;
        height: calc(100vh - 60px);
        padding: 0.5rem 0;
        overflow-x: hidden;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: var(--primary-color) #e9ecef;
    }

    /* Custom scrollbar for WebKit browsers */
    .sidebar-sticky::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar-sticky::-webkit-scrollbar-track {
        background: #e9ecef;
    }

    .sidebar-sticky::-webkit-scrollbar-thumb {
        background-color: var(--primary-color);
        border-radius: 20px;
    }

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

    .navbar-brand {
        position: fixed;
        top: 0;
        left: 0;
        padding: 0.75rem 1.25rem;
        font-size: 1.2rem;
        background: rgba(255,255,255,0.9);
        width: var(--sidebar-width);
        text-align: left;
        color: var(--text-dark) !important;
        font-weight: 700;
        border-bottom: 1px solid var(--border-light);
        z-index: 1001;
        backdrop-filter: blur(5px);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        transition: all 0.3s ease;
    }

    .navbar-brand small {
        font-size: 0.8rem;
        color: var(--text-muted);
        display: block;
        margin-top: 0.2rem;
        font-weight: normal;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Main Content - FIXED */
    .main-content {
        margin-left: var(--sidebar-width);
        padding: 20px 25px;
        background: var(--secondary-bg);
        min-height: 100vh;
        transition: all 0.3s ease;
        width: calc(100% - var(--sidebar-width));
        box-sizing: border-box;
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
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
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

    /* Table fixes - PREVENTS OVERLAPPING */
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

    /* Stock info grid for product details */
    .stock-info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
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

    .stock-info-value.small {
        font-size: 0.95rem;
    }

    /* Branch Info Card */
    .branch-info-card {
        background: white;
        border-radius: 12px;
        padding: 1rem;
        margin: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
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

    .branch-info-card div:last-child {
        margin-bottom: 0;
    }

    .branch-info-card .small {
        color: var(--text-muted);
    }

    /* Footer Info */
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

    .footer-info div:last-child {
        margin-bottom: 0;
    }

    /* Top Navigation */
    .top-navbar {
        background: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        border-radius: 12px;
        padding: 0.75rem 1.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .top-navbar > div:first-child {
        flex: 1;
        min-width: 200px;
    }

    .top-navbar > div:last-child {
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
        0% { opacity: 1; }
        50% { opacity: 0.6; }
        100% { opacity: 1; }
    }

    /* Dropdown */
    .dropdown-menu {
        border: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border-radius: 12px;
        max-height: 400px;
        overflow-y: auto;
    }

    /* Alerts */
    .alert {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
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

    /* Quick stats cards */
    .bg-primary, .bg-success, .bg-warning, .bg-danger {
        transition: transform 0.2s ease;
    }

    .bg-primary:hover, .bg-success:hover, .bg-warning:hover, .bg-danger:hover {
        transform: translateY(-2px);
    }

    /* Mobile Responsive Styles */
    @media (max-width: 768px) {
        /* Collapsible Sidebar */
        .sidebar {
            width: var(--sidebar-collapsed-width);
            overflow: visible;
        }
        
        .sidebar:hover {
            width: var(--sidebar-width);
            box-shadow: 2px 0 20px rgba(0,0,0,0.1);
        }
        
        .sidebar .nav-link span,
        .sidebar-heading,
        .branch-info-card div span,
        .footer-info div span,
        .navbar-brand small,
        .navbar-brand span:not(.d-none) {
            display: none;
        }
        
        .sidebar:hover .nav-link span,
        .sidebar:hover .sidebar-heading,
        .sidebar:hover .branch-info-card div span,
        .sidebar:hover .footer-info div span,
        .sidebar:hover .navbar-brand small,
        .sidebar:hover .navbar-brand span:not(.d-none) {
            display: inline;
        }
        
        .sidebar .nav-link {
            padding: 0.75rem;
            margin: 2px 4px;
            text-align: center;
        }
        
        .sidebar:hover .nav-link {
            padding: 0.75rem 1.25rem;
            text-align: left;
        }
        
        .sidebar .nav-link i {
            margin-right: 0;
            font-size: 1.4rem;
        }
        
        .sidebar:hover .nav-link i {
            margin-right: 0.75rem;
            font-size: 1.2rem;
        }
        
        .navbar-brand {
            width: var(--sidebar-collapsed-width);
            padding: 0.75rem;
            text-align: center;
        }
        
        .navbar-brand span {
            display: none;
        }
        
        .main-content {
            margin-left: var(--sidebar-collapsed-width);
            width: calc(100% - var(--sidebar-collapsed-width));
            padding: 15px;
        }
        
        /* Stock info grid for mobile */
        .stock-info-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
            padding: 1rem;
        }
        
        /* Adjust cards for mobile */
        .branch-info-card,
        .footer-info {
            padding: 0.75rem;
            margin: 0.75rem;
        }
        
        .branch-info-card div,
        .footer-info div {
            font-size: 0.85rem;
        }
        
        /* Top navbar mobile */
        .top-navbar {
            padding: 0.75rem;
            flex-direction: column;
        }
        
        .top-navbar > div:last-child {
            width: 100%;
            justify-content: flex-start;
        }
        
        .top-navbar .text-muted.small {
            font-size: 0.75rem;
        }
        
        /* Make buttons more touch-friendly */
        .btn-sm {
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
        }
        
        .badge-count {
            padding: 0.15rem 0.5rem;
            font-size: 0.65rem;
        }
    }

    /* Extra Small Devices */
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
        
        .stock-info-label {
            font-size: 0.8rem;
        }
        
        .stock-info-value {
            font-size: 1rem;
        }
        
        .top-navbar > div:last-child {
            flex-wrap: wrap;
        }
        
        .btn-outline-primary {
            padding: 0.25rem 0.75rem;
            font-size: 0.8rem;
        }
        
        .card-header {
            padding: 0.75rem 1rem;
        }
        
        .card-body {
            padding: 1rem;
        }
        
        .branch-info-card,
        .footer-info {
            margin: 0.5rem;
            padding: 0.5rem;
        }
    }

    /* Tablet Styles */
    @media (min-width: 769px) and (max-width: 1024px) {
        .main-content {
            padding: 20px;
        }
        
        .sidebar {
            width: 240px;
        }
        
        .navbar-brand {
            width: 240px;
        }
        
        .main-content {
            margin-left: 240px;
            width: calc(100% - 240px);
        }
        
        .stock-info-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    /* Large Screens */
    @media (min-width: 1400px) {
        .stock-info-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    /* Print Styles */
    @media print {
        .sidebar,
        .top-navbar,
        .footer-info,
        .branch-info-card,
        .btn {
            display: none !important;
        }
        
        .main-content {
            margin-left: 0;
            width: 100%;
            padding: 0;
        }
        
        .card {
            break-inside: avoid;
            box-shadow: none;
            border: 1px solid #ddd;
        }
    }
</style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar d-md-block">
        <div class="navbar-brand">
            <img src="{{ asset('images/logo.png') }}" alt="Vape Expo" height="30" class="me-2">
            VAPE EXPO
            <br>
            <small>{{ Auth::user()->branch->name ?? 'Branch' }}</small>
        </div>
        
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
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('branch-admin.inventory.add-product') ? 'active' : '' }}" 
                       href="{{ route('branch-admin.inventory.add-product') }}">
                        <i class="bi bi-plus-circle"></i> Add Stock
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('branch-admin.inventory.low-stock') ? 'active' : '' }}" 
                       href="{{ route('branch-admin.inventory.low-stock') }}">
                        <i class="bi bi-exclamation-triangle"></i> Low Stock
                    </a>
                </li>
                
                <!-- NEW: Stock Movement History Link -->
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
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('branch-admin.inventory.transfers', ['filter' => 'incoming']) ? 'active' : '' }}" 
                       href="{{ route('branch-admin.inventory.transfers', ['filter' => 'incoming']) }}">
                        <i class="bi bi-download"></i> Incoming
                        @php
                            $pendingIncoming = \App\Models\StockTransfer::where('to_branch_id', Auth::user()->branch_id)
                                ->where('status', 'pending')
                                ->count();
                        @endphp
                        @if($pendingIncoming > 0)
                            <span class="badge bg-warning pending-badge ms-2">{{ $pendingIncoming }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('branch-admin.inventory.transfers', ['filter' => 'outgoing']) ? 'active' : '' }}" 
                       href="{{ route('branch-admin.inventory.transfers', ['filter' => 'outgoing']) }}">
                        <i class="bi bi-upload"></i> Outgoing
                        @php
                            $pendingOutgoing = \App\Models\StockTransfer::where('from_branch_id', Auth::user()->branch_id)
                                ->where('status', 'pending')
                                ->count();
                        @endphp
                        @if($pendingOutgoing > 0)
                            <span class="badge bg-warning pending-badge ms-2">{{ $pendingOutgoing }}</span>
                        @endif
                    </a>
                </li>
                
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
                        <li><a class="dropdown-item" href="{{ route('branch-admin.inventory.low-stock') }}">View Low Stock</a></li>
                    </ul>
                </div>
                
                <!-- Pending Transfers Quick View -->
                @php
                    $totalPending = \App\Models\StockTransfer::where(function($q) {
                        $q->where('from_branch_id', Auth::user()->branch_id)
                          ->orWhere('to_branch_id', Auth::user()->branch_id);
                    })->where('status', 'pending')->count();
                @endphp
                @if($totalPending > 0)
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
        @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close float-end" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close float-end" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if($errors->any())
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> 
                <strong>Please check the form:</strong>
                <button type="button" class="btn-close float-end" data-bs-dismiss="alert"></button>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <!-- Page Content -->
        @yield('content')
    </main>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Update low stock badge
        document.addEventListener('DOMContentLoaded', function() {
            const lowStockCount = {{ $lowStockCount ?? 0 }};
            document.getElementById('lowStockBadge').textContent = lowStockCount;
        });
    </script>
    
    @stack('scripts')
</body>
</html>