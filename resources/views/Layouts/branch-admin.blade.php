<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Branch Admin - VapeShop')</title>
    
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
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
            width: 250px;
            background: linear-gradient(180deg, #2c3e50 0%, #1a2530 100%);
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
            color: #ecf0f1;
            padding: 0.75rem 1rem;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
            padding-left: 1.5rem;
        }
        
        .sidebar .nav-link.active {
            color: #fff;
            background: rgba(52, 152, 219, 0.8);
        }
        
        .sidebar-heading {
            font-size: .75rem;
            text-transform: uppercase;
            color: #7b8a8b;
            padding: 0.5rem 1rem;
            margin-top: 1rem;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        
        .navbar-brand {
            padding: 0.75rem 1rem;
            font-size: 1.25rem;
            background: rgba(0, 0, 0, 0.2);
            width: 250px;
            text-align: center;
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
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar d-md-block">
        <div class="navbar-brand text-white">
            <i class="bi bi-shop"></i> VapeShop Branch
            <br>
            <small class="text-muted">{{ Auth::user()->branch->name ?? 'No Branch' }}</small>
        </div>
        
        <div class="sidebar-sticky">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('branch-admin.dashboard') ? 'active' : '' }}" 
                       href="{{ route('branch-admin.dashboard') }}">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>
                
                <li class="sidebar-heading">Inventory</li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('branch-admin.inventory.*') ? 'active' : '' }}" 
                       href="{{ route('branch-admin.inventory.index') }}">
                        <i class="bi bi-box-seam me-2"></i> Inventory Management
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('branch-admin.products.*') ? 'active' : '' }}" 
                       href="{{ route('branch-admin.products.index') }}">
                        <i class="bi bi-plus-circle me-2"></i> Add New Products
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('branch-admin.pos.*') ? 'active' : '' }}" 
                       href="{{ route('branch-admin.pos') }}">
                        <i class="bi bi-cash-coin me-2"></i> Point of Sale
                    </a>
                </li>
                
                <li class="sidebar-heading">Orders</li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('branch-admin.orders.index') }}">
                        <i class="bi bi-receipt me-2"></i> Order Management
                        <span class="badge bg-danger float-end">3</span>
                    </a>
                </li>
                
                <li class="sidebar-heading">Reports</li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('branch-admin.reports.sales') }}">
                        <i class="bi bi-graph-up me-2"></i> Sales Reports
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('branch-admin.reports.inventory') }}">
                        <i class="bi bi-clipboard-data me-2"></i> Inventory Reports
                    </a>
                </li>
                
                <li class="sidebar-heading">Account</li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">
                        <i class="bi bi-house me-2"></i> Back to Main Site
                    </a>
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link text-start w-100 border-0 bg-transparent">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
            
            <!-- Branch Info -->
            <div class="mt-auto p-3 text-white small">
                <div class="mb-2">
                    <i class="bi bi-person"></i> {{ Auth::user()->name }}
                </div>
                <div class="mb-2">
                    <i class="bi bi-envelope"></i> {{ Auth::user()->email }}
                </div>
                <div>
                    <i class="bi bi-shield-check"></i> Branch Administrator
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom mb-4">
            <div class="container-fluid">
                <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="collapse" 
                        data-bs-target="#topNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="topNavbar">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <span class="nav-link">
                                <i class="bi bi-calendar3"></i> {{ now()->format('F d, Y') }}
                            </span>
                        </li>
                        <li class="nav-item">
                            <span class="nav-link">
                                <i class="bi bi-clock"></i> {{ now()->format('h:i A') }}
                            </span>
                        </li>
                    </ul>
                    
                    <div class="d-flex align-items-center">
                        <!-- Low Stock Alert -->
                        <div class="dropdown me-3">
                            <button class="btn btn-warning btn-sm dropdown-toggle" type="button" 
                                    data-bs-toggle="dropdown">
                                <i class="bi bi-exclamation-triangle"></i> 
                                Low Stock Items
                                <span class="badge bg-dark">5</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><h6 class="dropdown-header">Low Stock Alert</h6></li>
                                <li><a class="dropdown-item" href="#">Product A - 2 left</a></li>
                                <li><a class="dropdown-item" href="#">Product B - 3 left</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('branch-admin.inventory.low-stock') }}">View All</a></li>
                            </ul>
                        </div>
                        
                        <!-- Quick Actions -->
                        <div class="btn-group">
                            <a href="{{ route('branch-admin.pos') }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-cash-coin"></i> Quick Sale
                            </a>
                            <a href="{{ route('branch-admin.products.create') }}" class="btn btn-success btn-sm">
                                <i class="bi bi-plus"></i> New Product
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Page Content -->
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i> 
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @yield('content')
        </div>
    </main>
    
    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>