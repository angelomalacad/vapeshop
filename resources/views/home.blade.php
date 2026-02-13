<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VapeShop - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
            margin-bottom: 50px;
        }
        .feature-icon {
            width: 4rem;
            height: 4rem;
            border-radius: .75rem;
        }
        .icon-link {
            display: inline-flex;
            align-items: center;
        }
        .icon-link > .bi {
            margin-top: .125rem;
            margin-left: .125rem;
            transition: transform .25s ease-in-out;
            fill: currentColor;
        }
        .icon-link:hover > .bi {
            transform: translate(.25rem);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="/">
                <i class="bi bi-cloud-fog"></i> Vape Expo
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/contact">Contact</a>
                    </li>
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                @if(Auth::user()->role == 'super_admin')
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Admin Dashboard</a></li>
                                @elseif(Auth::user()->role == 'branch_admin')
                                    <li><a class="dropdown-item" href="{{ route('branch-admin.dashboard') }}">Branch Dashboard</a></li>
                                @elseif(Auth::user()->role == 'customer')
                                    <li><a class="dropdown-item" href="{{ route('customer.dashboard') }}">My Dashboard</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">VapeShop Management System</h1>
                    <p class="lead mb-4">Complete inventory, ordering, and management system for multi-branch vape shops.</p>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                        @auth
                            @if(Auth::user()->role == 'super_admin')
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-light btn-lg px-4 me-md-2">
                                    Go to Admin Dashboard
                                </a>
                            @elseif(Auth::user()->role == 'branch_admin')
                                <a href="{{ route('branch-admin.dashboard') }}" class="btn btn-light btn-lg px-4 me-md-2">
                                    Go to Branch Dashboard
                                </a>
                            @elseif(Auth::user()->role == 'customer')
                                <a href="{{ route('customer.dashboard') }}" class="btn btn-light btn-lg px-4 me-md-2">
                                    Go to My Dashboard
                                </a>
                            @endif
                        @else
                            <a href="{{ route('register') }}" class="btn btn-light btn-lg px-4 me-md-2">
                                Get Started
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4">
                                Login
                            </a>
                        @endauth
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="bg-white rounded-3 p-4 shadow-lg">
                        <h4 class="text-dark mb-3">Multi-Branch Features</h4>
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="p-3 bg-light rounded">
                                    <i class="bi bi-shop fs-1 text-primary"></i>
                                    <h6 class="mt-2 mb-0">5 Branches</h6>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-light rounded">
                                    <i class="bi bi-box-seam fs-1 text-success"></i>
                                    <h6 class="mt-2 mb-0">Inventory</h6>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-light rounded">
                                    <i class="bi bi-cart-check fs-1 text-warning"></i>
                                    <h6 class="mt-2 mb-0">Online Orders</h6>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-light rounded">
                                    <i class="bi bi-bell fs-1 text-danger"></i>
                                    <h6 class="mt-2 mb-0">Stock Alerts</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="container py-5">
        <h2 class="text-center mb-5">Complete Vape Shop Management</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-primary bg-gradient text-white rounded-3 mb-3 p-3 mx-auto">
                            <i class="bi bi-clipboard-data fs-3"></i>
                        </div>
                        <h4 class="card-title">Inventory Management</h4>
                        <p class="card-text">Track stock levels across all branches in real-time with automated low-stock alerts.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-success bg-gradient text-white rounded-3 mb-3 p-3 mx-auto">
                            <i class="bi bi-cart fs-3"></i>
                        </div>
                        <h4 class="card-title">Online Ordering</h4>
                        <p class="card-text">Customers can order online for delivery or pickup from any branch.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-info bg-gradient text-white rounded-3 mb-3 p-3 mx-auto">
                            <i class="bi bi-graph-up fs-3"></i>
                        </div>
                        <h4 class="card-title">Analytics & Reports</h4>
                        <p class="card-text">Comprehensive sales reports, inventory analytics, and branch performance tracking.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>VapeShop</h5>
                    <p>Complete management system for multi-branch vape shops.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="/" class="text-white-50 text-decoration-none">Home</a></li>
                        <li><a href="/about" class="text-white-50 text-decoration-none">About</a></li>
                        <li><a href="/contact" class="text-white-50 text-decoration-none">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact</h5>
                    <p><i class="bi bi-telephone"></i> +63 912 345 6789</p>
                    <p><i class="bi bi-envelope"></i> info@vapeshop.com</p>
                </div>
            </div>
            <hr class="bg-white">
            <div class="text-center">
                <small>&copy; {{ date('Y') }} VapeShop System. All rights reserved.</small>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</body>
</html>