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
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 48px 0 0;
            width: 260px;
            background: linear-gradient(145deg, #f5f7fa 0%, #e9ecef 100%);
            box-shadow: 2px 0 15px rgba(0,0,0,0.03);
        }
        
        .sidebar-sticky {
            position: relative;
            top: 0;
            height: calc(100vh - 48px);
            padding-top: .5rem;
            overflow-x: hidden;
            overflow-y: auto;
        }
        
        .sidebar .nav-link {
            font-weight: 500;
            color: #4a5568;
            padding: 0.85rem 1.25rem;
            transition: all 0.2s ease;
            margin: 2px 8px;
            border-radius: 8px;
        }
        
        .sidebar .nav-link:hover {
            color: #0d6efd;
            background: rgba(13, 110, 253, 0.05);
        }
        
        .sidebar .nav-link.active {
            color: #0d6efd;
            background: rgba(13, 110, 253, 0.1);
            font-weight: 600;
        }
        
        .sidebar .nav-link i {
            color: #0d6efd;
            margin-right: 0.75rem;
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
        }
        
        .sidebar-heading {
            font-size: .7rem;
            text-transform: uppercase;
            color: #6c757d;
            padding: 0.5rem 1.25rem;
            margin-top: 1rem;
            letter-spacing: 0.5px;
            font-weight: 600;
        }
        
        .main-content {
            margin-left: 260px;
            padding: 20px;
            background: #f8f9fa;
            min-height: 100vh;
        }
        
        .navbar-brand {
            padding: 1rem 1.25rem;
            font-size: 1.2rem;
            background: rgba(255,255,255,0.5);
            width: 260px;
            text-align: left;
            color: #2c3e50 !important;
            font-weight: 700;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        
        .navbar-brand small {
            font-size: 0.8rem;
            color: #6c757d;
            display: block;
            margin-top: 0.2rem;
            font-weight: normal;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
                height: auto;
            }
            .main-content {
                margin-left: 0;
            }
            .navbar-brand {
                width: 100%;
            }
        }
        
        .branch-info-card {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            margin: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        }
        
        .branch-info-card i {
            color: #0d6efd;
            margin-right: 0.5rem;
            width: 20px;
        }
        
        .branch-info-card div {
            color: #2c3e50;
            font-size: 0.9rem;
        }
        
        .branch-info-card .small {
            color: #6c757d;
        }
        
        .top-navbar {
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .badge-count {
            background: #0d6efd;
            color: white;
            border-radius: 20px;
            padding: 0.2rem 0.6rem;
            font-size: 0.7rem;
            margin-left: 0.5rem;
            font-weight: 500;
        }
        
        .footer-info {
            margin: 1rem;
            padding: 1rem;
            background: white;
            border-radius: 12px;
            color: #2c3e50;
            font-size: 0.9rem;
        }
        
        .footer-info i {
            color: #0d6efd;
            margin-right: 0.5rem;
            width: 20px;
        }
        
        .footer-info div {
            margin-bottom: 0.5rem;
        }
        
        .owner-name {
            color: #0d6efd;
            font-weight: 600;
        }
        
        .btn-outline-primary {
            border: 1px solid #0d6efd;
            color: #0d6efd;
            border-radius: 20px;
            padding: 0.3rem 1rem;
        }
        
        .btn-outline-primary:hover {
            background: #0d6efd;
            color: white;
        }
        
        .text-primary-custom {
            color: #0d6efd;
        }
        
        /* Soft shadows and rounded corners */
        .card {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
            border-radius: 12px;
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid rgba(0,0,0,0.03);
            padding: 1rem 1.25rem;
        }
        
        .list-group-item {
            border: none;
            margin-bottom: 2px;
            border-radius: 8px !important;
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border-radius: 12px;
        }
        
        .alert {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
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
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('branch-admin.inventory.transfer.form') ? 'active' : '' }}" 
                       href="{{ route('branch-admin.inventory.transfer.form') }}">
                        <i class="bi bi-arrow-left-right"></i> Transfers
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
            
            <!-- Branch Info -->
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
                        <li><a class="dropdown-item" href="{{ route('branch-admin.inventory.low-stock') }}">View Low Stock</a></li>
                    </ul>
                </div>
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