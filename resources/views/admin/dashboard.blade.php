<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Dashboard - Vape Expo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
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
            <!-- Sidebar Menu -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-grid me-2"></i> Owner Menu
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <h1 class="h3 mb-4">Owner Dashboard</h1>
                
                <!-- Statistics Cards -->
                <div class="row">
                    <div class="col-md-3 mb-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h5 class="card-title">Branches</h5>
                                <h2>5</h2>
                                <a href="{{ route('admin.branches.index') }}" class="text-white">Manage →</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5 class="card-title">Total Staff</h5>
                                <h2>{{ \App\Models\User::whereIn('role', ['branch_admin', 'staff'])->count() }}</h2>
                                <span class="text-white">Employees</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h5 class="card-title">Products</h5>
                                <h2>{{ \App\Models\Product::count() }}</h2>
                                <span class="text-white">X Ultra, Slimbar, Relx</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <h5 class="card-title">Customers</h5>
                                <h2>{{ \App\Models\User::where('role', 'customer')->count() }}</h2>
                                <span class="text-white">Registered</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- System Overview -->
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
                                    <li><strong>Staff Management</strong> - 5 branch staff members</li>
                                    <li><strong>Product Catalog</strong> - X Ultra, Slimbar, Relx</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul>
                                    <li><strong>Inventory Tracking</strong> across all branches</li>
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