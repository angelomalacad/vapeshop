<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Vape Expo - Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: #f5f7fb; }
        /* Navbar Animation */
        .navbar-custom { background: #1a1a2e; box-shadow: 0 4px 20px rgba(0,0,0,0.08); animation: slideDown 0.5s ease; }
        @keyframes slideDown { from { transform: translateY(-100%); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        /* Welcome Banner */
        .welcome-banner { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); border-radius: 20px; padding: 1.5rem; margin-bottom: 1.5rem; position: relative; overflow: hidden; animation: fadeInUp 0.6s ease; }
        .welcome-banner::before { content: ''; position: absolute; top: -50%; right: -20%; width: 300px; height: 300px; background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%); border-radius: 50%; animation: rotate 20s linear infinite; }
        @keyframes rotate { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        /* Sidebar & Cards */
        .sidebar-card { border: none; border-radius: 20px; background: white; box-shadow: 0 2px 12px rgba(0,0,0,0.04); }
        .sidebar-card .list-group-item { border: none; padding: 0.7rem 1.25rem; border-radius: 10px; margin: 2px 8px; transition: all 0.3s; }
        .sidebar-card .list-group-item:hover { background: #f8f9fa; color: #e74c3c; transform: translateX(8px); }
        .sidebar-card .list-group-item.active { background: #fff5f5; color: #e74c3c; border-left: 3px solid #e74c3c; }
        .modern-card { border: none; border-radius: 20px; background: white; box-shadow: 0 2px 12px rgba(0,0,0,0.04); transition: transform 0.2s; }
        .modern-card:hover { transform: translateY(-3px); }
        .card-header-modern { background: white; border-bottom: 1px solid #eef2f6; font-weight: 600; padding: 1rem 1.25rem; border-radius: 20px 20px 0 0; }
        /* Branch Card */
        .branch-card { transition: all 0.3s; }
        .branch-card:hover { background: #f8f9fa; transform: translateX(5px); }
        /* Product Grid */
        .product-card { border: none; border-radius: 16px; transition: all 0.3s; overflow: hidden; background: white; box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 12px 24px rgba(0,0,0,0.1); }
        .product-img { height: 200px; object-fit: cover; width: 100%; }
        .product-price { font-weight: 700; color: #e74c3c; font-size: 1.25rem; }
        .btn-add-cart { background: #e74c3c; border: none; border-radius: 30px; padding: 8px 16px; transition: all 0.3s; }
        .btn-add-cart:hover { background: #c0392b; transform: scale(1.02); }
        /* Cart Table */
        .cart-table th, .cart-table td { vertical-align: middle; }
        .quantity-input { width: 70px; text-align: center; border-radius: 30px; }
        /* Footer */
        .footer-custom { font-size: 0.85rem; color: #6c757d; }
        /* Animations */
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeInLeft { from { opacity: 0; transform: translateX(-30px); } to { opacity: 1; transform: translateX(0); } }
        @keyframes fadeInRight { from { opacity: 0; transform: translateX(30px); } to { opacity: 1; transform: translateX(0); } }
        .float-icon { animation: float 3s ease-in-out infinite; }
        @keyframes float { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-8px); } }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: #e74c3c; border-radius: 10px; }
        /* Responsive */
        @media (max-width: 768px) { .product-img { height: 150px; } }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand text-white fw-bold" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Vape Expo" height="32" class="d-inline-block align-text-top me-2">
                Vape Expo
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCustomer">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCustomer">
                <ul class="navbar-nav ms-auto align-items-center gap-2">
                    <li class="nav-item">
                        <a href="{{ route('customer.products.index') }}" class="btn btn-outline-light btn-sm rounded-pill">
                            <i class="bi bi-shop"></i> Shop
                        </a>
                    </li>
                    <li class="nav-item position-relative">
                        <a href="{{ route('customer.cart.index') }}" class="btn btn-outline-light btn-sm rounded-pill">
                            <i class="bi bi-cart"></i> Cart
                            <span id="cartCountBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem; display: none;">0</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-light btn-sm rounded-pill">
                            <i class="bi bi-receipt"></i> Orders
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('customer.dashboard') }}">Dashboard</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item" type="submit">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @if(session('success'))
            <div class="container"><div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div></div>
        @endif
        @if(session('error'))
            <div class="container"><div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div></div>
        @endif
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Simple cart count from session (PHP sets it on page load)
    const cartCount = {{ \App\Helpers\CartHelper::getItemCount() }};
    const badge = document.getElementById('cartCountBadge');
    if (badge && cartCount > 0) {
        badge.textContent = cartCount;
        badge.style.display = 'inline-block';
    } else if (badge) {
        badge.style.display = 'none';
    }
</script>
    @stack('scripts')
</body>
</html>