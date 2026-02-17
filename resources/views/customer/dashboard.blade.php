<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard - Vape Expo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="30" class="d-inline-block align-text-top me-2">
                Vape Expo
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text text-white me-3">
                    Welcome, {{ Auth::user()->name }}!
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                </form>
            </div>
        </div>
    </nav>
    
    <div class="container mt-4">
        <!-- Shop Information Banner -->
        <div class="alert alert-info mb-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="mb-2"><i class="bi bi-shop me-2"></i>Vape Expo - Your Trusted Vape Shop</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><i class="bi bi-person-circle me-2"></i> Owner: <strong>Carlo Caranto</strong></p>
                            <p class="mb-1"><i class="bi bi-telephone me-2"></i> Contact: <strong>0960 328 0432</strong></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><i class="bi bi-clock me-2"></i> Hours: <strong>9:00 AM – 10:00 PM</strong></p>
                            <p class="mb-1"><i class="bi bi-calendar me-2"></i> Since: <strong>May 20, 2024</strong></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <span class="badge bg-warning text-dark p-2">
                        <i class="bi bi-star-fill me-1"></i> 5 Branches in Calamba
                    </span>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Sidebar Menu -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">Customer Menu</div>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('customer.dashboard') }}" class="list-group-item list-group-item-action active">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                        <a href="{{ route('customer.products.index') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-shop me-2"></i> Browse Products
                        </a>
                        <a href="{{ route('customer.cart.index') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-cart me-2"></i> My Cart
                        </a>
                        <a href="{{ route('branches.index') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-geo-alt me-2"></i> Branch Locations
                        </a>
                        <a href="{{ route('home') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-house me-2"></i> Back to Home
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <h1 class="h3 mb-4">Customer Dashboard</h1>
                
                <!-- Quick Action Cards -->
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h5 class="card-title"><i class="bi bi-shop"></i> Browse Products</h5>
                                <p>View all available vape products</p>
                                <a href="{{ route('customer.products.index') }}" class="btn btn-light">Shop Now</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5 class="card-title"><i class="bi bi-cart"></i> My Cart</h5>
                                <p>View your shopping cart</p>
                                <a href="{{ route('customer.cart.index') }}" class="btn btn-light">View Cart</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h5 class="card-title"><i class="bi bi-geo-alt"></i> Branches</h5>
                                <p>Find our branches</p>
                                <a href="{{ route('branches.index') }}" class="btn btn-light">View Branches</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Two Column Layout for Recent Orders and Branch Info -->
                <div class="row">
                    <!-- Recent Orders -->
                    <div class="col-md-7">
                        <div class="card">
                            <div class="card-header">Recent Orders</div>
                            <div class="card-body">
                                <div class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                    <p>No orders yet. Start shopping now!</p>
                                    <a href="{{ route('customer.products.index') }}" class="btn btn-primary btn-sm">
                                        Browse Products
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Branch Information -->
                    <div class="col-md-5">
                        <div class="card">
                            <div class="card-header">Branch Locations</div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li class="mb-3">
                                        <strong><i class="bi bi-geo-alt-fill text-danger me-1"></i> Majada Out:</strong>
                                        <p class="mb-0 text-muted">EFG Building, Majada Out Road</p>
                                        <small class="text-muted">Near 7-Eleven and Gran Avila</small>
                                    </li>
                                    <li class="mb-3">
                                        <strong><i class="bi bi-geo-alt-fill text-danger me-1"></i> Asia 1:</strong>
                                        <p class="mb-0 text-muted">Blk 67 Lot 1 Asia 1 Rd., Canlubang</p>
                                        <small class="text-muted">Near Hernandez Grocery and Grimaldo</small>
                                    </li>
                                    <li class="mb-3">
                                        <strong><i class="bi bi-geo-alt-fill text-danger me-1"></i> MCDC:</strong>
                                        <p class="mb-0 text-muted">Blk 1 Lot 10 Kapayapaan, Canlubang</p>
                                        <small class="text-muted">Near Geosnack and Mango Royale</small>
                                    </li>
                                    <li class="mb-3">
                                        <strong><i class="bi bi-geo-alt-fill text-danger me-1"></i> Paciano:</strong>
                                        <p class="mb-0 text-muted">215 National Road, Brgy. Paciano Rizal</p>
                                        <small class="text-muted">In front of Barangay Hall and 7-Eleven</small>
                                    </li>
                                    <li class="mb-3">
                                        <strong><i class="bi bi-geo-alt-fill text-danger me-1"></i> Paciano V2:</strong>
                                        <p class="mb-0 text-muted">39 Mayapa-Canlubang Cadre Road</p>
                                        <small class="text-muted">Near the area</small>
                                    </li>
                                </ul>
                                <hr>
                                <p class="mb-0 text-center">
                                    <i class="bi bi-clock"></i> All branches open daily: <strong>9:00 AM – 10:00 PM</strong>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

        
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>