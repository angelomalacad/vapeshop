<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Branch Admin Dashboard - VapeShop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-info">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-cloud-fog"></i> VapeShop Branch Admin
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text text-white me-3">
                    Welcome, {{ Auth::user()->name }} (Branch Admin)
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                </form>
            </div>
        </div>
    </nav>
    
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">Branch Menu</div>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('branch-admin.dashboard') }}" class="list-group-item list-group-item-action active">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                        <a href="{{ route('branch-admin.inventory.index') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-box-seam me-2"></i> Inventory
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
            </div>
            <div class="col-md-9">
                <h1 class="h3 mb-4">Branch Admin Dashboard</h1>
                
                @php
                    $branch = Auth::user()->branch;
                @endphp
                
                @if($branch)
                <div class="card mb-4">
                    <div class="card-header">My Branch</div>
                    <div class="card-body">
                        <h5>{{ $branch->name }}</h5>
                        <p class="mb-1"><i class="bi bi-geo-alt"></i> {{ $branch->address }}</p>
                        <p class="mb-1"><i class="bi bi-telephone"></i> {{ $branch->phone }}</p>
                        <p class="mb-0"><i class="bi bi-person"></i> Manager: {{ $branch->manager_name }}</p>
                    </div>
                </div>
                @else
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i> No branch assigned to your account.
                </div>
                @endif
                
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h5 class="card-title"><i class="bi bi-box-seam me-2"></i> Inventory</h5>
                                <p>Manage branch stock and products</p>
                                <a href="{{ route('branch-admin.inventory.index') }}" class="btn btn-light">View Inventory</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5 class="card-title"><i class="bi bi-cart me-2"></i> Orders</h5>
                                <p>Process customer orders</p>
                                <a href="#" class="btn btn-light">View Orders</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <h5 class="card-title"><i class="bi bi-graph-up me-2"></i> Reports</h5>
                                <p>Branch performance analytics</p>
                                <a href="#" class="btn btn-light">View Reports</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Stats Section -->
                @if($branch)
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="bi bi-bar-chart me-2"></i> Quick Stats</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
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
                                    <div class="col-md-3">
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
                                    <div class="col-md-3">
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
                                    <div class="col-md-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-info rounded-circle p-3 me-3">
                                                <i class="bi bi-currency-dollar text-white fs-4"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">Today's Revenue</h6>
                                                <h4 class="mb-0">Php0.00</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>