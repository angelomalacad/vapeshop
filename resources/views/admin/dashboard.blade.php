<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Dashboard - Vape Expo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        /* White Theme Sidebar */
        .sidebar-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            background: white;
        }
        
        .sidebar-card .card-header {
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            padding: 1.25rem 1rem;
            color: #2c3e50;
            font-weight: 600;
        }
        
        .sidebar-card .card-header i {
            color: #0d6efd;
        }
        
        .sidebar-card .list-group-item {
            background: white;
            color: #4a5568;
            border: none;
            padding: 0.75rem 1.25rem;
            transition: all 0.2s;
            margin: 2px 8px;
            border-radius: 8px;
        }
        
        .sidebar-card .list-group-item:hover {
            background: #f1f8ff;
            color: #0d6efd;
            transform: translateX(5px);
        }
        
        .sidebar-card .list-group-item.active {
            background: #e7f1ff;
            color: #0d6efd;
            font-weight: 600;
            border-left: 3px solid #0d6efd;
        }
        
        .sidebar-card .list-group-item i {
            width: 24px;
            color: #0d6efd;
        }
        
        .sidebar-card .list-group-item.active i {
            color: #0d6efd;
        }
        
        .sidebar-card .list-group-item.disabled {
            opacity: 0.5;
            background: #f8f9fa;
            color: #6c757d;
        }
        
        .sidebar-card .badge {
            border-radius: 20px;
            padding: 0.25rem 0.6rem;
            font-size: 0.7rem;
        }
        
        .sidebar-card .text-muted {
            color: #6c757d !important;
            font-size: 0.75rem;
            padding: 0.75rem 1.25rem 0.25rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }
        
        .sidebar-card .bg-light {
            background: #f8f9fa !important;
            color: #6c757d;
            font-size: 0.75rem;
            padding: 0.25rem 1.25rem;
            margin-top: 0.25rem;
        }
        
        .sidebar-card .ps-4 {
            padding-left: 2.5rem !important;
            border-left: 2px dashed #dee2e6;
            margin-left: 1rem;
        }
        
        .stat-card {
            transition: transform 0.2s;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .low-stock-badge {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.6; }
            100% { opacity: 1; }
        }
        
        .card {
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            border-radius: 12px;
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid #f0f0f0;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .alert-info {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: none;
            color: #2c3e50;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="30" class="d-inline-block align-text-top me-2">
                Vape Expo - Owner Panel
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text text-white me-3">
                    <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }} (Owner)
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>
    
    <div class="container-fluid mt-4">
        <!-- Owner Information Banner -->
        <div class="alert alert-info mb-4 mx-3">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="mb-2"><i class="bi bi-shop me-2"></i>Vape Expo - Owner Dashboard</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><i class="bi bi-person-circle me-2"></i> Owner: <strong>Carlo Caranto</strong></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><i class="bi bi-clock me-2"></i> Business Hours: <strong>9:00 AM – 10:00 PM</strong></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <span class="badge bg-warning text-dark p-2">
                        <i class="bi bi-star-fill me-1"></i> 5 Branches
                    </span>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Sidebar Menu - White Theme -->
            <div class="col-md-3">
                <div class="card sidebar-card">
                    <div class="card-header">
                        <i class="bi bi-grid me-2"></i> Owner Menu
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                        
                        <div class="text-muted">MANAGEMENT</div>
                        
                        <a href="{{ route('admin.branches.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.branches.*') ? 'active' : '' }}">
                            <i class="bi bi-shop me-2"></i> Branches
                        </a>
                        
                        @if(Route::has('admin.staff.index'))
                            <a href="{{ route('admin.staff.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}">
                                <i class="bi bi-people me-2"></i> Staff Management
                            </a>
                        @else
                            <a href="#" class="list-group-item list-group-item-action disabled" tabindex="-1" aria-disabled="true">
                                <i class="bi bi-people me-2"></i> Staff Management (Coming Soon)
                            </a>
                        @endif
                        
                        <!-- Products Link - Now Active -->
                        @if(Route::has('admin.products.index'))
                            <a href="{{ route('admin.products.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                                <i class="bi bi-box me-2"></i> Products
                                @php $productCount = \App\Models\Product::count(); @endphp
                                <span class="badge bg-info float-end">{{ $productCount }}</span>
                            </a>
                        @else
                            <a href="#" class="list-group-item list-group-item-action disabled" tabindex="-1" aria-disabled="true">
                                <i class="bi bi-box me-2"></i> Products (Coming Soon)
                            </a>
                        @endif
                        
                        <div class="text-muted">INVENTORY</div>
                        
                        @if(Route::has('admin.inventory.index'))
                            <a href="{{ route('admin.inventory.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.inventory.index') ? 'active' : '' }}">
                                <i class="bi bi-clipboard-data me-2"></i> Inventory Overview
                                @php $totalItems = \App\Models\BranchInventory::count(); @endphp
                                <span class="badge bg-secondary float-end">{{ $totalItems }}</span>
                            </a>
                        @endif
                        
                        @if(Route::has('admin.inventory.low-stock') || Route::has('admin.inventory.transfers') || Route::has('admin.inventory.stock-history'))
                            <div class="bg-light">
                                <small class="text-muted"><i class="bi bi-arrow-right-short me-1"></i> QUICK LINKS</small>
                            </div>
                            
                            @if(Route::has('admin.inventory.low-stock'))
                            <a href="{{ route('admin.inventory.low-stock') }}" class="list-group-item list-group-item-action ps-4 {{ request()->routeIs('admin.inventory.low-stock') ? 'active' : '' }}">
                                <i class="bi bi-exclamation-triangle me-2 text-warning"></i> Low Stock Alert
                                @php
                                    $lowStockCount = \App\Models\BranchInventory::whereColumn('quantity', '<=', 'low_stock_threshold')->count();
                                @endphp
                                @if($lowStockCount > 0)
                                    <span class="badge bg-danger rounded-pill float-end">{{ $lowStockCount }}</span>
                                @endif
                            </a>
                            @endif
                            
                            @if(Route::has('admin.inventory.transfers'))
                            <a href="{{ route('admin.inventory.transfers') }}" class="list-group-item list-group-item-action ps-4 {{ request()->routeIs('admin.inventory.transfers') ? 'active' : '' }}">
                                <i class="bi bi-arrow-left-right me-2 text-info"></i> Stock Transfers
                                @php
                                    $pendingTransfers = \App\Models\StockTransfer::where('status', 'pending')->count();
                                @endphp
                                @if($pendingTransfers > 0)
                                    <span class="badge bg-warning rounded-pill float-end">{{ $pendingTransfers }}</span>
                                @endif
                            </a>
                            @endif
                            
                            @if(Route::has('admin.inventory.stock-history'))
                            <a href="{{ route('admin.inventory.stock-history') }}" class="list-group-item list-group-item-action ps-4 {{ request()->routeIs('admin.inventory.stock-history') ? 'active' : '' }}">
                                <i class="bi bi-clock-history me-2 text-secondary"></i> Stock History
                            </a>
                            @endif
                        @endif
                        
                        <div class="text-muted">TRANSACTIONS</div>
                        
                        @if(Route::has('admin.orders.index'))
                            <a href="{{ route('admin.orders.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                                <i class="bi bi-cart me-2"></i> Orders
                            </a>
                        @else
                            <a href="#" class="list-group-item list-group-item-action disabled" tabindex="-1" aria-disabled="true">
                                <i class="bi bi-cart me-2"></i> Orders (Coming Soon)
                            </a>
                        @endif
                        
                        <div class="text-muted">REPORTS</div>
                        
                        @if(Route::has('admin.reports.index'))
                            <a href="{{ route('admin.reports.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                                <i class="bi bi-graph-up me-2"></i> Reports
                            </a>
                        @else
                            <a href="#" class="list-group-item list-group-item-action disabled" tabindex="-1" aria-disabled="true">
                                <i class="bi bi-graph-up me-2"></i> Reports (Coming Soon)
                            </a>
                        @endif
                        
                        <div class="text-muted">SYSTEM</div>
                        
                        @if(Route::has('admin.users.index'))
                            <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                <i class="bi bi-people me-2"></i> Users
                            </a>
                        @else
                            <a href="#" class="list-group-item list-group-item-action disabled" tabindex="-1" aria-disabled="true">
                                <i class="bi bi-people me-2"></i> Users (Coming Soon)
                            </a>
                        @endif
                        
                        @if(Route::has('admin.settings.index'))
                            <a href="{{ route('admin.settings.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                                <i class="bi bi-gear me-2"></i> Settings
                            </a>
                        @else
                            <a href="#" class="list-group-item list-group-item-action disabled" tabindex="-1" aria-disabled="true">
                                <i class="bi bi-gear me-2"></i> Settings (Coming Soon)
                            </a>
                        @endif
                        
                        <div class="dropdown-divider"></div>
                        
                        <a href="{{ route('home') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-house me-2"></i> Back to Home
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <h1 class="h3 mb-4">Owner Dashboard</h1>
                
                @php
                    $totalInventoryItems = \App\Models\BranchInventory::count();
                    $lowStockCount = \App\Models\BranchInventory::whereColumn('quantity', '<=', 'low_stock_threshold')->count();
                    $outOfStockCount = \App\Models\BranchInventory::where('quantity', '<=', 0)->count();
                    $pendingTransfers = \App\Models\StockTransfer::where('status', 'pending')->count();
                    $totalProducts = \App\Models\Product::count();
                    $totalStaff = \App\Models\User::whereIn('role', ['branch_admin', 'staff'])->count();
                    $totalCustomers = \App\Models\User::where('role', 'customer')->count();
                    
                    $totalStockValue = \App\Models\BranchInventory::with('product')
                        ->get()
                        ->sum(function($item) {
                            return $item->quantity * ($item->product->price ?? 0);
                        });
                @endphp
                
                <div class="row">
                    <div class="col-md-3 mb-4">
                        <div class="card bg-primary text-white stat-card">
                            <div class="card-body">
                                <h5 class="card-title">Branches</h5>
                                <h2>5</h2>
                                <a href="{{ route('admin.branches.index') }}" class="text-white">Manage →</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card bg-success text-white stat-card">
                            <div class="card-body">
                                <h5 class="card-title">Total Staff</h5>
                                <h2>{{ $totalStaff }}</h2>
                                <span class="text-white">Employees</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card bg-info text-white stat-card">
                            <div class="card-body">
                                <h5 class="card-title">Products</h5>
                                <h2>{{ $totalProducts }}</h2>
                                <a href="{{ route('admin.products.index') }}" class="text-white">Manage Catalog →</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card bg-warning text-white stat-card">
                            <div class="card-body">
                                <h5 class="card-title">Customers</h5>
                                <h2>{{ $totalCustomers }}</h2>
                                <span class="text-white">Registered</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-3 mb-4">
                        <div class="card bg-secondary text-white stat-card">
                            <div class="card-body">
                                <h5 class="card-title">Total Items</h5>
                                <h2>{{ $totalInventoryItems }}</h2>
                                <span class="text-white">Across all branches</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card bg-danger text-white stat-card">
                            <div class="card-body">
                                <h5 class="card-title">Low Stock</h5>
                                <h2>{{ $lowStockCount }}</h2>
                                @if($lowStockCount > 0)
                                    <a href="{{ route('admin.inventory.low-stock') }}" class="text-white low-stock-badge">View Alerts →</a>
                                @else
                                    <span class="text-white">All good</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card bg-dark text-white stat-card">
                            <div class="card-body">
                                <h5 class="card-title">Out of Stock</h5>
                                <h2>{{ $outOfStockCount }}</h2>
                                <span class="text-white">Need restocking</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card bg-purple" style="background-color: #6f42c1; color: white;">
                            <div class="card-body">
                                <h5 class="card-title">Stock Value</h5>
                                <h2>₱{{ number_format($totalStockValue, 0) }}</h2>
                                <span class="text-white">Total inventory</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header bg-white">
                                <i class="bi bi-lightning-charge me-2"></i> Quick Actions
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2 mb-2">
                                        <a href="{{ route('admin.products.create') }}" class="btn btn-outline-primary w-100">
                                            <i class="bi bi-plus-circle"></i> New Product
                                        </a>
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-info w-100">
                                            <i class="bi bi-box"></i> Manage Products
                                        </a>
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        <a href="{{ route('admin.inventory.index') }}" class="btn btn-outline-primary w-100">
                                            <i class="bi bi-box-seam"></i> View All Inventory
                                        </a>
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        <a href="{{ route('admin.inventory.low-stock') }}" class="btn btn-outline-warning w-100">
                                            <i class="bi bi-exclamation-triangle"></i> Check Low Stock
                                            @if($lowStockCount > 0)
                                                <span class="badge bg-danger ms-1">{{ $lowStockCount }}</span>
                                            @endif
                                        </a>
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        <a href="{{ route('admin.inventory.transfers') }}" class="btn btn-outline-info w-100">
                                            <i class="bi bi-arrow-left-right"></i> View Transfers
                                            @if($pendingTransfers > 0)
                                                <span class="badge bg-warning ms-1">{{ $pendingTransfers }}</span>
                                            @endif
                                        </a>
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        <a href="{{ route('admin.inventory.stock-history') }}" class="btn btn-outline-secondary w-100">
                                            <i class="bi bi-clock-history"></i> Stock History
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-info-circle me-2"></i> Business Overview
                    </div>
                    <div class="card-body">
                        <p>Welcome to the Vape Expo Owner Panel, <strong>Carlo Caranto</strong>. From here you can manage:</p>
                        <div class="row">
                            <div class="col-md-6">
                                <ul>
                                    <li><strong>5 Branches</strong> across Calamba</li>
                                    <li><strong>Staff Management</strong> - {{ $totalStaff }} branch staff members</li>
                                    <li><strong>Product Catalog</strong> - {{ $totalProducts }} products (X Ultra, Slimbar, Relx)</li>
                                    <li><strong>Inventory Tracking</strong> - {{ $totalInventoryItems }} items across branches</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul>
                                    <li><strong>Stock Alerts</strong> - {{ $lowStockCount }} items need attention</li>
                                    <li><strong>Pending Transfers</strong> - {{ $pendingTransfers }} requests</li>
                                    <li><strong>Order Management</strong> and fulfillment</li>
                                    <li><strong>Sales Reports</strong> and analytics</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>