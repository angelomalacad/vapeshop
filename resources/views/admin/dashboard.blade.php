<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Dashboard - Vape Expo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: #f5f7fb; }
        
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
        .sidebar-card .list-group-item i { width: 24px; color: #6c757d; }
        .sidebar-card .list-group-item.active i,
        .sidebar-card .list-group-item:hover i { color: #0d6efd; }
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
        
        /* Stat Cards */
        .stat-card {
            border: none;
            border-radius: 20px;
            transition: all 0.3s ease;
            background: white;
            overflow: hidden;
        }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 12px 24px rgba(0,0,0,0.08); }
        .stat-card .card-body { padding: 1.25rem; }
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
        .low-stock-badge { animation: pulse 2s infinite; }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.6; }
            100% { opacity: 1; }
        }
        
        /* Cards */
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
        
        /* Tabs */
        .nav-tabs .nav-link {
            font-weight: 600;
            border: none;
            color: #6c757d;
            padding: 0.75rem 1.5rem;
        }
        .nav-tabs .nav-link.active {
            color: #0d6efd;
            border-bottom: 3px solid #0d6efd;
            background: transparent;
        }
        .tab-content { padding-top: 1.5rem; }
        
        /* Chart containers - FIXED SIZES */
        .chart-container-small {
            max-width: 100%;
            height: 200px;
            margin: 0 auto;
        }
        .chart-container-medium {
            max-width: 100%;
            height: 250px;
            margin: 0 auto;
        }
        .chart-wrapper {
            position: relative;
            width: 100%;
            min-height: 200px;
        }
        canvas {
            max-height: 100%;
            width: 100% !important;
        }
        
        /* Fallback */
        .chart-fallback {
            min-height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            border-radius: 12px;
        }
        
        /* Analytics cards */
        .analytics-card {
            height: 100%;
            transition: all 0.2s;
        }
        .analytics-card .card-body {
            padding: 1rem;
        }
        
        /* Table fixes */
        .table-responsive {
            border-radius: 12px;
        }
        .table th, .table td {
            padding: 0.75rem;
            vertical-align: middle;
        }
        
        /* Progress bar */
        .progress {
            border-radius: 10px;
            background-color: #e9ecef;
        }
        
        /* Status badges */
        .status-badge {
            padding: 0.25rem 0.5rem;
            font-size: 0.7rem;
            border-radius: 20px;
        }
    </style>
</head>
<body>
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
            <!-- Sidebar -->
            <div class="col-md-3">
                <div class="card sidebar-card">
                    <div class="card-header"><i class="bi bi-grid me-2"></i> Owner Menu</div>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action active">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                        <div class="text-muted">MANAGEMENT</div>
                        @if(Route::has('admin.branch-admin.index'))
                        <a href="{{ route('admin.branch-admin.index') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-people me-2"></i> Branch Personnel
                            @php $branchAdminCount = \App\Models\User::whereIn('role', ['branch_admin', 'staff'])->count(); @endphp
                            <span class="badge bg-info float-end">{{ $branchAdminCount }}</span>
                        </a>
                        @endif
                        @if(Route::has('admin.customers.index'))
                        <a href="{{ route('admin.customers.index') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-people-fill me-2"></i> Customers
                            @php $customerCount = \App\Models\User::where('role', 'customer')->count(); @endphp
                            <span class="badge bg-success float-end">{{ $customerCount }}</span>
                        </a>
                        @endif
                        @if(Route::has('admin.products.index'))
                        <a href="{{ route('admin.products.index') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-box me-2"></i> Products
                            @php $productCount = \App\Models\Product::count(); @endphp
                            <span class="badge bg-info float-end">{{ $productCount }}</span>
                        </a>
                        @endif
                        <div class="text-muted">INVENTORY</div>
                        @if(Route::has('admin.inventory.index'))
                        <a href="{{ route('admin.inventory.index') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-clipboard-data me-2"></i> Inventory Overview
                            @php $totalItems = \App\Models\BranchInventory::count(); @endphp
                            <span class="badge bg-secondary float-end">{{ $totalItems }}</span>
                        </a>
                        @endif
                        <div class="bg-light"><small class="text-muted"><i class="bi bi-arrow-right-short me-1"></i> QUICK LINKS</small></div>
                        @if(Route::has('admin.inventory.low-stock'))
                        <a href="{{ route('admin.inventory.low-stock') }}" class="list-group-item list-group-item-action ps-4">
                            <i class="bi bi-exclamation-triangle me-2 text-warning"></i> Low Stock Alert
                            @php $lowStockCountNav = \App\Models\BranchInventory::whereColumn('quantity', '<=', 'low_stock_threshold')->count(); @endphp
                            @if($lowStockCountNav > 0) <span class="badge bg-danger rounded-pill float-end">{{ $lowStockCountNav }}</span> @endif
                        </a>
                        @endif
                        @if(Route::has('admin.inventory.transfers'))
                        <a href="{{ route('admin.inventory.transfers') }}" class="list-group-item list-group-item-action ps-4">
                            <i class="bi bi-arrow-left-right me-2 text-info"></i> Stock Transfers
                            @php $pendingTransfersNav = \App\Models\StockTransfer::where('status', 'pending')->count(); @endphp
                            @if($pendingTransfersNav > 0) <span class="badge bg-warning rounded-pill float-end">{{ $pendingTransfersNav }}</span> @endif
                        </a>
                        @endif
                        @if(Route::has('admin.inventory.stock-history'))
                        <a href="{{ route('admin.inventory.stock-history') }}" class="list-group-item list-group-item-action ps-4">
                            <i class="bi bi-clock-history me-2 text-secondary"></i> Stock History
                        </a>
                        @endif
                        <div class="text-muted">WAREHOUSE</div>
                        @if(Route::has('admin.warehouse.index'))
                        <a href="{{ route('admin.warehouse.index') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-building me-2"></i> Warehouse Stock
                        </a>
                        @endif
                        <div class="text-muted">DELIVERIES</div>
                        @if(Route::has('admin.deliveries.index'))
                        <a href="{{ route('admin.deliveries.index') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-truck me-2 text-primary"></i> All Deliveries
                        </a>
                        @endif
                        
                        @if(Route::has('admin.driver-shifts.index'))
                        <a href="{{ route('admin.driver-shifts.index') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-calendar-check me-2 text-primary"></i> Driver Shifts
                        </a>
                        @endif
                        <div class="text-muted">TRANSACTIONS</div>
                        @if(Route::has('admin.pos.history'))
                        <a href="{{ route('admin.pos.history') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-clock-history me-2 text-secondary"></i> Sales History
                        </a>
                        @endif
                        @if(Route::has('admin.online-orders.index'))
                        <a href="{{ route('admin.online-orders.index') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-cart me-2 text-primary"></i> Online Orders History
                        </a>
                        @endif
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('home') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-house me-2"></i> Back to Home
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Content with Tabs -->
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
                    $totalStockValue = \App\Models\BranchInventory::with('product')->get()->sum(fn($item) => $item->quantity * ($item->product->price ?? 0));
                    
                    // Analytics data (with fallbacks)
                    $expiringSoon = $expiringSoon ?? collect();
                    $onlineOrderStatus = $onlineOrderStatus ?? [];
                    $repeatCustomerRate = $repeatCustomerRate ?? 0;
                    $deliveryVsPickup = $deliveryVsPickup ?? ['delivery_sales' => 0, 'pickup_sales' => 0];
                    $fastMovingProducts = $fastMovingProducts ?? collect();
                @endphp

                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs" id="dashboardTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard Overview
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="analytics-tab" data-bs-toggle="tab" data-bs-target="#analytics" type="button" role="tab">
                            <i class="bi bi-graph-up me-2"></i> Analytics
                        </button>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- ===== OVERVIEW TAB ===== -->
                    <div class="tab-pane fade show active" id="overview" role="tabpanel">
                        <div class="row g-4 mb-4">
                            <div class="col-md-3"><div class="card stat-card"><div class="card-body"><div class="d-flex justify-content-between align-items-start"><div><h5>BRANCH PERSONNEL</h5><h2>{{ $totalStaff }}</h2><a href="{{ route('admin.branch-admin.index') }}" class="small text-decoration-none mt-2 d-inline-block text-primary">Manage →</a></div><div class="stat-icon" style="background: rgba(13,110,253,0.1); color:#0d6efd;"><i class="bi bi-people fs-4"></i></div></div></div></div></div>
                            <div class="col-md-3"><div class="card stat-card"><div class="card-body"><div class="d-flex justify-content-between align-items-start"><div><h5>PRODUCTS</h5><h2>{{ $totalProducts }}</h2><a href="{{ route('admin.products.index') }}" class="small text-decoration-none text-info">Manage →</a></div><div class="stat-icon" style="background: rgba(13,110,253,0.1); color:#0d6efd;"><i class="bi bi-box-seam fs-4"></i></div></div></div></div></div>
                            <div class="col-md-3"><div class="card stat-card"><div class="card-body"><div class="d-flex justify-content-between align-items-start"><div><h5>CUSTOMERS</h5><h2>{{ $totalCustomers }}</h2><a href="{{ route('admin.customers.index') }}" class="small text-decoration-none mt-2 d-inline-block text-primary">Manage →</a></div><div class="stat-icon" style="background: rgba(25,135,84,0.1); color:#198754;"><i class="bi bi-person fs-4"></i></div></div></div></div></div>
                            <div class="col-md-3"><div class="card stat-card"><div class="card-body"><div class="d-flex justify-content-between align-items-start"><div><h5>TOTAL ITEMS</h5><h2>{{ $totalInventoryItems }}</h2><span class="small text-muted">Across branches</span></div><div class="stat-icon" style="background: rgba(13,110,253,0.1); color:#0d6efd;"><i class="bi bi-database fs-4"></i></div></div></div></div></div>
                        </div>
                        <div class="row g-4 mb-4">
                            <div class="col-md-3"><div class="card stat-card"><div class="card-body"><div class="d-flex justify-content-between align-items-start"><div><h5>LOW STOCK</h5><h2>{{ $lowStockCount }}</h2>@if($lowStockCount > 0)<a href="{{ route('admin.inventory.low-stock') }}" class="small text-danger low-stock-badge text-decoration-none">View Alerts →</a>@else<span class="small text-muted">All good</span>@endif</div><div class="stat-icon" style="background: rgba(253,126,20,0.1); color:#fd7e14;"><i class="bi bi-exclamation-triangle fs-4"></i></div></div></div></div></div>
                            <div class="col-md-3"><div class="card stat-card"><div class="card-body"><div class="d-flex justify-content-between align-items-start"><div><h5>OUT OF STOCK</h5><h2>{{ $outOfStockCount }}</h2><span class="small text-muted">Need restocking</span></div><div class="stat-icon" style="background: rgba(220,53,69,0.1); color:#dc3545;"><i class="bi bi-x-circle fs-4"></i></div></div></div></div></div>
                            <div class="col-md-3"><div class="card stat-card"><div class="card-body"><div class="d-flex justify-content-between align-items-start"><div><h5>STOCK VALUE</h5><h2>₱{{ number_format($totalStockValue, 0) }}</h2><span class="small text-muted">Total inventory</span></div><div class="stat-icon" style="background: rgba(25,135,84,0.1); color:#198754;"><i class="fs-4">₱</i></div></div></div></div></div>
                            <div class="col-md-3"><div class="card stat-card"><div class="card-body"><div class="d-flex justify-content-between align-items-start"><div><h5>PENDING TRANSFERS</h5><h2>{{ $pendingTransfers }}</h2>@if($pendingTransfers > 0)<a href="{{ route('admin.inventory.transfers') }}" class="small text-warning text-decoration-none">View →</a>@else<span class="small text-muted">No pending</span>@endif</div><div class="stat-icon" style="background: rgba(255,193,7,0.1); color:#ffc107;"><i class="bi bi-arrow-left-right fs-4"></i></div></div></div></div></div>
                        </div>
                        <div class="card">
                            <div class="card-header bg-white"><i class="bi bi-info-circle me-2 text-primary"></i> Business Overview</div>
                            <div class="card-body">
                                <p>Welcome to the Vape Expo Owner Panel, <strong>Carlo Caranto</strong>. From here you can manage:</p>
                                <div class="row">
                                    <div class="col-md-6"><ul><li><strong>5 Branches</strong> across Calamba</li><li><strong>Branch Personnel</strong> - {{ $totalStaff }} staff members across all branches</li><li><strong>Product Catalog</strong> - {{ $totalProducts }} products (X Ultra, Slimbar, Relx)</li><li><strong>Inventory Tracking</strong> - {{ $totalInventoryItems }} items across branches</li></ul></div>
                                    <div class="col-md-6"><ul><li><strong>Stock Alerts</strong> - {{ $lowStockCount }} items need attention</li><li><strong>Pending Transfers</strong> - {{ $pendingTransfers }} requests</li><li><strong>Order Management</strong> and fulfillment</li><li><strong>Sales Reports</strong> and analytics</li></ul></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ===== ANALYTICS TAB - FIXED UI ===== -->
                    <div class="tab-pane fade" id="analytics" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="mb-0"><i class="bi bi-graph-up me-2 text-primary"></i> Business Analytics</h3>
                            <small class="text-muted">Real‑time data from your operations</small>
                        </div>

                        <!-- Row 1: Expiring Soon + Online Order Status -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-12">
                                <div class="card analytics-card">
                                    <div class="card-header bg-white">
                                        <i class="bi bi-calendar-exclamation me-2 text-danger"></i> Expiring Soon (next 30 days)
                                        @if($expiringSoon->count() > 0)<span class="badge bg-danger float-end">{{ $expiringSoon->count() }} items</span>@endif
                                    </div>
                                    <div class="card-body p-0">
                                        @if($expiringSoon->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <thead class="bg-light">
                                                    <tr><th>Branch</th><th>Product</th><th>Expiration Date</th><th>Days Left</th><th>Qty</th></tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($expiringSoon as $item)
                                                    <tr>
                                                        <td>{{ $item->branch->name ?? 'N/A' }}</td>
                                                        <td>{{ $item->product->name ?? 'N/A' }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($item->expiration_date)->format('M d, Y') }}</td>
                                                        <td>@php $daysLeft = \Carbon\Carbon::now()->diffInDays($item->expiration_date, false); @endphp
                                                            <span class="badge {{ $daysLeft <= 7 ? 'bg-danger' : ($daysLeft <= 14 ? 'bg-warning' : 'bg-secondary') }}">{{ max(0, $daysLeft) }} days</span>
                                                        </td>
                                                        <td>{{ number_format($item->quantity) }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        @else
                                        <div class="text-center py-4 text-success"><i class="bi bi-check-circle fs-2"></i><p class="mb-0">No products expiring soon</p></div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Row 2: Charts Row -->
                        <div class="row g-4 mb-4">
                            <!-- Online Order Status - Pie Chart (smaller) -->
                            <div class="col-md-5">
                                <div class="card analytics-card h-100">
                                    <div class="card-header bg-white">
                                        <i class="bi bi-cart-check me-2 text-primary"></i> Online Order Status
                                        <span class="badge bg-secondary float-end">{{ array_sum($onlineOrderStatus) }} total</span>
                                    </div>
                                    <div class="card-body text-center">
                                        @if(count($onlineOrderStatus) > 0)
                                        <div class="chart-container-medium">
                                            <canvas id="orderStatusChart"></canvas>
                                        </div>
                                        <div class="row mt-3 text-center small">
                                            @foreach($onlineOrderStatus as $status => $count)
                                            <div class="col-4 col-md-3 mb-2">
                                                <span class="badge bg-{{ $status == 'pending' ? 'warning' : ($status == 'confirmed' ? 'info' : ($status == 'processing' ? 'primary' : ($status == 'ready' ? 'success' : ($status == 'out_for_delivery' ? 'dark' : ($status == 'delivered' ? 'secondary' : 'danger'))))) }} status-badge">{{ ucwords(str_replace('_', ' ', $status)) }}</span>
                                                <div class="fw-bold mt-1">{{ $count }}</div>
                                            </div>
                                            @endforeach
                                        </div>
                                        @else
                                        <div class="chart-fallback"><p class="text-muted">No online orders data yet</p></div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Delivery vs POS - Donut Chart (smaller) -->
                            <div class="col-md-3">
                                <div class="card analytics-card h-100">
                                    <div class="card-header bg-white">
                                        <i class="bi bi-truck me-2 text-primary"></i> Delivery vs POS
                                    </div>
                                    <div class="card-body text-center">
                                        <div class="chart-container-small">
                                            <canvas id="deliveryVsPickupChart"></canvas>
                                        </div>
                                        <div class="row text-center mt-3">
                                            <div class="col-6">
                                                <span class="badge bg-primary status-badge">Delivery</span>
                                                <h5 class="mb-0 mt-1">₱{{ number_format($deliveryVsPickup['delivery_sales'], 2) }}</h5>
                                            </div>
                                            <div class="col-6">
                                                <span class="badge bg-success status-badge">POS</span>
                                                <h5 class="mb-0 mt-1">₱{{ number_format($deliveryVsPickup['pickup_sales'], 2) }}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Repeat Customer Rate - Card -->
                            <div class="col-md-4">
                                <div class="card analytics-card h-100 text-center">
                                    <div class="card-body d-flex flex-column justify-content-center">
                                        <i class="bi bi-people-fill fs-1 text-primary"></i>
                                        <h5 class="mt-2">Repeat Customer Rate</h5>
                                        <h2 class="mb-0">{{ $repeatCustomerRate }}%</h2>
                                        <small class="text-muted">of customers ordered more than once</small>
                                        <div class="progress mt-3" style="height: 8px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $repeatCustomerRate }}%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Row 3: Fastest Moving Products (Full Width) -->
                        <div class="row g-4">
                            <div class="col-md-12">
                                <div class="card analytics-card">
                                    <div class="card-header bg-white">
                                        <i class="bi bi-lightning-charge me-2 text-warning"></i> Fastest Moving Products
                                    </div>
                                    <div class="card-body p-0">
                                        @if($fastMovingProducts->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <thead class="bg-light">
                                                    <tr><th>Product</th><th>Units Sold</th><th>Revenue</th><th class="text-end">Rank</th></tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($fastMovingProducts as $index => $product)
                                                    <tr>
                                                        <td><strong>{{ $product->name }}</strong></td>
                                                        <td><span class="badge bg-primary">{{ number_format($product->total_sold) }}</span></td>
                                                        <td>₱{{ number_format($product->total_sold * ($product->price ?? 350), 2) }}</td>
                                                        <td class="text-end"><span class="badge bg-secondary rounded-pill">#{{ $index+1 }}</span></td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        @else
                                        <div class="text-center py-4 text-muted"><i class="bi bi-box-seam fs-2"></i><p>No product sales data yet</p></div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Online Order Status Pie Chart (Fixed size)
            const statusCanvas = document.getElementById('orderStatusChart');
            if (statusCanvas) {
                try {
                    const statusData = @json(array_values($onlineOrderStatus));
                    const statusLabels = @json(array_keys($onlineOrderStatus));
                    if (statusData.length > 0) {
                        new Chart(statusCanvas, {
                            type: 'pie',
                            data: {
                                labels: statusLabels.map(l => l.replace(/_/g, ' ').toUpperCase()),
                                datasets: [{
                                    data: statusData,
                                    backgroundColor: ['#ffc107', '#0dcaf0', '#0d6efd', '#198754', '#6c757d', '#dc3545', '#20c997']
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: true,
                                plugins: {
                                    legend: { position: 'right', labels: { boxWidth: 12, font: { size: 11 } } },
                                    tooltip: { callbacks: { label: (ctx) => `${ctx.label}: ${ctx.raw} orders` } }
                                }
                            }
                        });
                    }
                } catch(e) { console.warn('Chart error:', e); }
            }

            // Delivery vs POS Donut Chart (Fixed size)
            const dpCanvas = document.getElementById('deliveryVsPickupChart');
            if (dpCanvas) {
                try {
                    const deliverySales = {{ $deliveryVsPickup['delivery_sales'] ?? 0 }};
                    const pickupSales = {{ $deliveryVsPickup['pickup_sales'] ?? 0 }};
                    if (deliverySales > 0 || pickupSales > 0) {
                        new Chart(dpCanvas, {
                            type: 'doughnut',
                            data: {
                                labels: ['Delivery', 'POS (Walk-in)'],
                                datasets: [{
                                    data: [deliverySales, pickupSales],
                                    backgroundColor: ['#0d6efd', '#198754']
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: true,
                                plugins: {
                                    legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } },
                                    tooltip: { callbacks: { label: (ctx) => `₱${ctx.raw.toFixed(2)}` } }
                                }
                            }
                        });
                    }
                } catch(e) { console.warn('Chart error:', e); }
            }
        });
    </script>
</body>
</html>