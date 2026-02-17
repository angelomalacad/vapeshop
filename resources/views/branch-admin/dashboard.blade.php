<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Branch Staff Dashboard - Vape Expo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-info">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="30" class="d-inline-block align-text-top me-2">
                Vape Expo - {{ Auth::user()->branch->name ?? 'Branch' }}
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text text-white me-3">
                    <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }} (Staff)
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
    
    <div class="container mt-4">
        

        <div class="row">
            <!-- Sidebar Menu -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-grid me-2"></i> Branch Menu
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('branch-admin.dashboard') }}" class="list-group-item list-group-item-action active">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                        <a href="{{ route('branch-admin.inventory.index') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-box-seam me-2"></i> Inventory Management
                        </a>
                        <a href="{{ route('branch-admin.products.create') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-plus-circle me-2"></i> Add New Product
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="bi bi-cart me-2"></i> Orders
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="bi bi-graph-up me-2"></i> Reports
                        </a>
                        <a href="{{ route('home') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-house me-2"></i> Back to Home
                        </a>
                    </div>
                </div>

                <!-- Branch Hours Card -->
                <div class="card mt-3">
                    <div class="card-header">
                        <i class="bi bi-clock me-2"></i> Branch Hours
                    </div>
                    <div class="card-body">
                        <p class="mb-2"><strong>Daily:</strong> 9:00 AM – 10:00 PM</p>
                        <p class="mb-0 text-muted"><small>All branches follow same hours</small></p>
                    </div>
                </div>

                <!-- Owner Contact Card -->
                <div class="card mt-3 border-primary">
                    <div class="card-header bg-primary text-white">
                        <i class="bi bi-person-circle me-2"></i> Shop Owner
                    </div>
                    <div class="card-body">
                        <p class="mb-1"><strong>Carlo Caranto</strong></p>
                        <p class="mb-0"><i class="bi bi-telephone me-2"></i> 0960 328 0432</p>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <h1 class="h3 mb-4">Branch Staff Dashboard</h1>
                
                @php
                    $branch = Auth::user()->branch;
                @endphp
                
                @if($branch)
                <!-- Branch Information Card -->
                <div class="card mb-4 border-info">
                    <div class="card-header bg-info text-white">
                        <i class="bi bi-shop me-2"></i> {{ $branch->name }}
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2"><i class="bi bi-geo-alt-fill text-danger me-2"></i> <strong>Address:</strong><br>{{ $branch->address }}</p>
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
                                @if($landmark)
                                <p class="mb-2"><i class="bi bi-pin-map-fill text-warning me-2"></i> <strong>Landmark:</strong><br>{{ $landmark }}</p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2"><i class="bi bi-telephone-fill text-primary me-2"></i> <strong>Contact:</strong><br>{{ $branch->phone ?? '0960 328 0432' }}</p>
                                <p class="mb-2"><i class="bi bi-person-badge-fill text-success me-2"></i> <strong>Staff:</strong><br>{{ Auth::user()->name }}</p>
                                <p class="mb-0"><i class="bi bi-calendar-event me-2"></i> <strong>Opened:</strong><br>{{ $branch->opening_date ?? '2024' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i> No branch assigned to your account. Please contact the owner Carlo Caranto.
                </div>
                @endif
                
                <!-- Quick Action Cards -->
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card bg-primary text-white h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title mb-0"><i class="bi bi-box-seam me-2"></i>Inventory</h5>
                                    <span class="badge bg-light text-dark">Manage Stock</span>
                                </div>
                                <p class="card-text">Manage branch inventory, update stock levels, and track products.</p>
                                <a href="{{ route('branch-admin.inventory.index') }}" class="btn btn-light w-100">
                                    <i class="bi bi-arrow-right me-2"></i>View Inventory
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card bg-success text-white h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title mb-0"><i class="bi bi-plus-circle me-2"></i>Add Product</h5>
                                    <span class="badge bg-light text-dark">X Ultra, Slimbar, Relx</span>
                                </div>
                                <p class="card-text">Add new products to your branch inventory with images and details.</p>
                                <a href="{{ route('branch-admin.products.create') }}" class="btn btn-light w-100">
                                    <i class="bi bi-arrow-right me-2"></i>Add New Product
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card bg-warning text-white h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title mb-0"><i class="bi bi-cart me-2"></i>Orders</h5>
                                    <span class="badge bg-light text-dark">Pending</span>
                                </div>
                                <p class="card-text">Process customer orders and manage order fulfillment.</p>
                                <a href="#" class="btn btn-light w-100">
                                    <i class="bi bi-arrow-right me-2"></i>View Orders
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Stats Section -->
                @if($branch)
                <div class="row mt-2">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="bi bi-bar-chart me-2"></i> Branch Quick Stats</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary rounded-circle p-3 me-3">
                                                <i class="bi bi-box text-white fs-4"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">Total Products</h6>
                                                <h4 class="mb-0">0</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-warning rounded-circle p-3 me-3">
                                                <i class="bi bi-exclamation-triangle text-white fs-4"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">Low Stock</h6>
                                                <h4 class="mb-0">0</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-success rounded-circle p-3 me-3">
                                                <i class="bi bi-cart-check text-white fs-4"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">Today's Orders</h6>
                                                <h4 class="mb-0">0</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-info rounded-circle p-3 me-3">
                                                <i class="bi bi-currency-dollar text-white fs-4"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">Today's Revenue</h6>
                                                <h4 class="mb-0">₱0.00</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity Section -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i> Recent Activity</h5>
                            </div>
                            <div class="card-body">
                                <div class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                    <p>No recent activity to display.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Footer Information -->
                <div class="mt-4 pt-3 text-muted border-top">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-0"><i class="bi bi-telephone me-2"></i> For concerns, contact owner: <strong>Carlo Caranto - 0960 328 0432</strong></p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p class="mb-0"><i class="bi bi-shield-check me-2"></i> Vape Expo - Authorized Branch Staff</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>