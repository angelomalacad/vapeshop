<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - VapeShop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-cloud-fog"></i> VapeShop Admin
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text text-white me-3">
                    Welcome, {{ Auth::user()->name }} (Super Admin)
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                </form>
            </div>
        </div>
    </nav>
    
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">Admin Menu</div>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action active">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                        <a href="{{ route('admin.branches.index') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-shop me-2"></i> Branches
                        </a>
                        <a href="{{ route('home') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-house me-2"></i> Back to Home
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <h1 class="h3 mb-4">Admin Dashboard</h1>
                
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
                                <h5 class="card-title">Users</h5>
                                <h2>{{ \App\Models\User::count() }}</h2>
                                <span class="text-white">Registered</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h5 class="card-title">Products</h5>
                                <h2>{{ \App\Models\Product::count() }}</h2>
                                <span class="text-white">Total</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <h5 class="card-title">Orders Today</h5>
                                <h2>0</h2>
                                <span class="text-white">New</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">System Overview</div>
                    <div class="card-body">
                        <p>Welcome to the VapeShop Administration Panel. As a Super Admin, you have full access to:</p>
                        <ul>
                            <li>Manage all branches</li>
                            <li>Manage products and inventory</li>
                            <li>View system reports</li>
                            <li>Manage user accounts</li>
                            <li>Configure system settings</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>