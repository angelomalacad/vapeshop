<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Dashboard - Vape Expo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background: #f5f7fb;
        }
        
        /* Sidebar */
        .sidebar-card {
            border: none;
            border-radius: 24px;
            overflow: hidden;
            background: white;
            box-shadow: 0 8px 32px rgba(0,0,0,0.04);
        }
        
        .sidebar-card .card-header {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            border-bottom: none;
            padding: 1.25rem 1rem;
            color: white;
            font-weight: 600;
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
            color: #6c757d;
        }
        
        .sidebar-card .list-group-item.active i,
        .sidebar-card .list-group-item:hover i {
            color: #0d6efd;
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
            padding: 0.25rem 1.25rem;
            margin-top: 0.25rem;
        }
        
        .sidebar-card .ps-4 {
            padding-left: 2.5rem !important;
            border-left: 2px dashed #dee2e6;
            margin-left: 1rem;
        }
        
        /* Modern Stat Cards */
        .stat-card {
            border: none;
            border-radius: 20px;
            transition: all 0.3s ease;
            background: white;
            overflow: hidden;
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.08);
        }
        
        .stat-card .card-body {
            padding: 1.25rem;
        }
        
        .stat-card h5 {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
            color: #94a3b8;
            margin-bottom: 0.5rem;
        }
        
        .stat-card h2 {
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 0.25rem;
            color: #1a1a2e;
        }
        
        .stat-icon {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }
        
        .icon-bg-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .icon-bg-success { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; }
        .icon-bg-info { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; }
        .icon-bg-warning { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; }
        .icon-bg-danger { background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%); color: white; }
        .icon-bg-secondary { background: linear-gradient(135deg, #757f9a 0%, #d7dde8 100%); color: white; }
        .icon-bg-purple { background: linear-gradient(135deg, #8e2de2 0%, #4a00e0 100%); color: white; }
        
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
            background: radial-gradient(circle, rgba(231, 76, 60, 0.15) 0%, transparent 70%);
            border-radius: 50%;
        }
        
        /* Modern Buttons */
        .btn-modern {
            border-radius: 10px;
            padding: 0.5rem 0.75rem;
            font-size: 0.8rem;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .btn-modern:hover {
            transform: translateY(-2px);
        }
        
        /* Activity Items */
        .activity-item {
            padding: 0.75rem;
            border-radius: 12px;
            transition: all 0.2s;
        }
        
        .activity-item:hover {
            background: #f8f9fa;
            transform: translateX(5px);
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
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            border-radius: 16px;
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid #f0f0f0;
            font-weight: 600;
            padding: 1rem 1.25rem;
        }
        
        /* Quick Action Grid */
        .quick-action-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
            gap: 0.75rem;
        }
        
        @media (min-width: 768px) {
            .quick-action-grid {
                grid-template-columns: repeat(6, 1fr);
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
        <div class="container">
            <a class="navbar-brand text-white fw-bold" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="30" class="d-inline-block align-text-top me-2">
                Vape Expo - Owner Panel
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text text-white-50 me-3">
                    <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }} (Owner)
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm rounded-pill">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>
    
    <div class="container-fluid px-4 py-4">
        <!-- Welcome Banner -->
        <div class="welcome-banner">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="text-white mb-2 fw-bold">
                        <i class="bi bi-stars me-2 text-warning"></i>Welcome back, Carlo!
                    </h4>
                    <p class="text-white-50 mb-0">Here's what's happening with your business today.</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="d-inline-block bg-white bg-opacity-10 rounded-3 px-4 py-2">
                        <i class="bi bi-calendar3 text-white me-2"></i>
                        <span class="text-white">{{ now()->format('l, F j, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
 <!-- Sidebar Menu -->
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
            
            <!-- Branch Admin Management link -->
            @if(Route::has('admin.branch-admin.index'))
                <a href="{{ route('admin.branch-admin.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.branch-admin.*') ? 'active' : '' }}">
                    <i class="bi bi-people me-2"></i> Branch Personnel
                    @php $branchAdminCount = \App\Models\User::whereIn('role', ['branch_admin', 'staff'])->count(); @endphp
                    <span class="badge bg-info float-end">{{ $branchAdminCount }}</span>
                </a>
            @endif
            
            <!-- ===== CUSTOMER MANAGEMENT LINK ===== -->
            @if(Route::has('admin.customers.index'))
                <a href="{{ route('admin.customers.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill me-2"></i> Customers
                    @php $customerCount = \App\Models\User::where('role', 'customer')->count(); @endphp
                    <span class="badge bg-success float-end">{{ $customerCount }}</span>
                </a>
            @endif
            
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
            
            <!-- ===== WAREHOUSE SECTION ===== -->
            <div class="text-muted">WAREHOUSE</div>

            <a href="{{ route('admin.warehouse.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.warehouse.index') ? 'active' : '' }}">
                <i class="bi bi-building me-2"></i> Warehouse Stock
            </a>

           
            <!-- ===== END OF WAREHOUSE SECTION ===== -->
            
            <!-- ===== DELIVERIES SECTION ===== -->
            <div class="text-muted">DELIVERIES</div>

            <a href="{{ route('admin.deliveries.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.deliveries.index') ? 'active' : '' }}">
                <i class="bi bi-truck me-2 text-primary"></i> All Deliveries
                @php
                    $pendingDeliveries = \App\Models\Delivery::whereIn('status', ['pending', 'assigned', 'picked_up', 'in_transit'])->count();
                @endphp
                @if($pendingDeliveries > 0)
                    <span class="badge bg-warning rounded-pill float-end">{{ $pendingDeliveries }}</span>
                @endif
            </a>

            <!-- ===== ONLINE ORDERS SECTION (OWNER VIEW) ===== -->
            @if(Route::has('admin.online-orders.index'))
            <a href="{{ route('admin.online-orders.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.online-orders.*') ? 'active' : '' }}">
                <i class="bi bi-cart me-2 text-primary"></i> Online Orders
                @php
                    $pendingOnlineOrders = \App\Models\Order::where('order_number', 'NOT LIKE', 'POS-%')
                        ->whereIn('order_status', ['pending', 'confirmed', 'processing'])
                        ->count();
                @endphp
                @if($pendingOnlineOrders > 0)
                    <span class="badge bg-warning rounded-pill float-end">{{ $pendingOnlineOrders }}</span>
                @endif
            </a>
            @endif

            <a href="{{ route('admin.driver-shifts.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.driver-shifts.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check me-2 text-primary"></i> Driver Shifts
                @php
                    $todayDriver = \App\Models\DriverShift::where('shift_date', today())->where('status', 'active')->first();
                @endphp
                @if($todayDriver)
                    <span class="badge bg-success float-end">{{ $todayDriver->driver->name }}</span>
                @endif
            </a>
            <!-- ===== END OF DELIVERIES SECTION ===== -->
            
            <div class="text-muted">TRANSACTIONS</div>
            
            @if(Route::has('admin.pos.history'))
                <a href="{{ route('admin.pos.history') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.pos.history') ? 'active' : '' }}">
                    <i class="bi bi-clock-history me-2 text-secondary"></i> Sales History
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
                <h1 class="h3 mb-4 fw-semibold">Owner Dashboard</h1>
                
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
                    
                    $todaySales = \App\Models\Order::where('delivery_type', 'pickup')
                        ->whereDate('created_at', \Carbon\Carbon::today())
                        ->sum('total_amount');
                    $todayOrders = \App\Models\Order::where('delivery_type', 'pickup')
                        ->whereDate('created_at', \Carbon\Carbon::today())
                        ->count();
                @endphp
                
                <!-- Stats Cards Row 1 -->
                <div class="row g-4 mb-4">
                    <!-- Branch Personnel card -->
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5>BRANCH PERSONNEL</h5>
                                        <h2>{{ $totalStaff }}</h2>
                                        <a href="{{ route('admin.branch-admin.index') }}" class="small text-decoration-none mt-2 d-inline-block text-primary">Manage →</a>
                                    </div>
                                    <div class="stat-icon" style="background: rgba(13, 110, 253, 0.1); color: #0d6efd;">
                                        <i class="bi bi-people fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5>PRODUCTS</h5>
                                        <h2>{{ $totalProducts }}</h2>
                                        <a href="{{ route('admin.products.index') }}" class="small text-decoration-none text-info">Manage →</a>
                                    </div>
                                    <div class="stat-icon" style="background: rgba(13, 110, 253, 0.1); color: #0d6efd;">
                                        <i class="bi bi-box-seam fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5>CUSTOMERS</h5>
                                        <h2>{{ $totalCustomers }}</h2>
                                        <a href="{{ route('admin.customers.index') }}" class="small text-decoration-none mt-2 d-inline-block text-primary">Manage →</a>
                                    </div>
                                    <div class="stat-icon" style="background: rgba(25, 135, 84, 0.1); color: #198754;">
                                        <i class="bi bi-person fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5>TOTAL ITEMS</h5>
                                        <h2>{{ $totalInventoryItems }}</h2>
                                        <span class="small text-muted">Across branches</span>
                                    </div>
                                    <div class="stat-icon" style="background: rgba(13, 110, 253, 0.1); color: #0d6efd;">
                                        <i class="bi bi-database fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards Row 2 -->
                <div class="row g-4 mb-4">
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5>LOW STOCK</h5>
                                        <h2>{{ $lowStockCount }}</h2>
                                        @if($lowStockCount > 0)
                                            <a href="{{ route('admin.inventory.low-stock') }}" class="small text-danger low-stock-badge text-decoration-none">View Alerts →</a>
                                        @else
                                            <span class="small text-muted">All good</span>
                                        @endif
                                    </div>
                                    <div class="stat-icon" style="background: rgba(253, 126, 20, 0.1); color: #fd7e14;">
                                        <i class="bi bi-exclamation-triangle fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5>OUT OF STOCK</h5>
                                        <h2>{{ $outOfStockCount }}</h2>
                                        <span class="small text-muted">Need restocking</span>
                                    </div>
                                    <div class="stat-icon" style="background: rgba(220, 53, 69, 0.1); color: #dc3545;">
                                        <i class="bi bi-x-circle fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5>STOCK VALUE</h5>
                                        <h2>₱{{ number_format($totalStockValue, 0) }}</h2>
                                        <span class="small text-muted">Total inventory</span>
                                    </div>
                                    <div class="stat-icon" style="background: rgba(25, 135, 84, 0.1); color: #198754;">
                                        <i class="fs-4">₱</i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5>PENDING TRANSFERS</h5>
                                        <h2>{{ $pendingTransfers }}</h2>
                                        @if($pendingTransfers > 0)
                                            <a href="{{ route('admin.inventory.transfers') }}" class="small text-warning text-decoration-none">View →</a>
                                        @else
                                            <span class="small text-muted">No pending</span>
                                        @endif
                                    </div>
                                    <div class="stat-icon" style="background: rgba(255, 193, 7, 0.1); color: #ffc107;">
                                        <i class="bi bi-arrow-left-right fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <i class="bi bi-lightning-charge me-2 text-warning"></i> Quick Actions
                    </div>
                    <div class="card-body">
                        <div class="quick-action-grid">
                            <a href="{{ route('admin.products.create') }}" class="btn btn-outline-primary btn-modern">
                                <i class="bi bi-plus-circle me-1"></i> New Product
                            </a>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-info btn-modern">
                                <i class="bi bi-box me-1"></i> Products
                            </a>
                            <a href="{{ route('admin.inventory.index') }}" class="btn btn-outline-primary btn-modern">
                                <i class="bi bi-box-seam me-1"></i> Inventory
                            </a>
                            <a href="{{ route('admin.inventory.low-stock') }}" class="btn btn-outline-warning btn-modern">
                                <i class="bi bi-exclamation-triangle me-1"></i> Low Stock
                                @if($lowStockCount > 0)
                                    <span class="badge bg-danger ms-1">{{ $lowStockCount }}</span>
                                @endif
                            </a>
                            <a href="{{ route('admin.inventory.transfers') }}" class="btn btn-outline-info btn-modern">
                                <i class="bi bi-arrow-left-right me-1"></i> Transfers
                                @if($pendingTransfers > 0)
                                    <span class="badge bg-warning ms-1">{{ $pendingTransfers }}</span>
                                @endif
                            </a>
                            <a href="{{ route('admin.branch-admin.index') }}" class="btn btn-outline-success btn-modern">
                                <i class="bi bi-people me-1"></i> Branch Personnel
                            </a>
                            <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-primary btn-modern">
                                <i class="bi bi-people-fill me-1"></i> Customers
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Business Overview -->
                <div class="card">
                    <div class="card-header bg-white">
                        <i class="bi bi-info-circle me-2 text-primary"></i> Business Overview
                    </div>
                    <div class="card-body">
                        <p>Welcome to the Vape Expo Owner Panel, <strong>Carlo Caranto</strong>. From here you can manage:</p>
                        <div class="row">
                            <div class="col-md-6">
                                <ul>
                                    <li><strong>5 Branches</strong> across Calamba</li>
                                    <li><strong>Branch Personnel</strong> - {{ $totalStaff }} staff members across all branches</li>
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