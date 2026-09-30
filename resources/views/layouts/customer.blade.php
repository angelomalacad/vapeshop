<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#1a1a2e">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>Vape Expo - Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap"
        rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        html {
            -webkit-overflow-scrolling: touch;
        }

        body {
            background: #f5f7fb;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            overscroll-behavior-y: none;
        }

        /* Navbar Animation */
        .navbar-custom {
            background: #1a1a2e;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            animation: slideDown 0.5s ease;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Welcome Banner */
        .welcome-banner {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.6s ease;
        }

        .welcome-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 70%);
            border-radius: 50%;
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* Sidebar & Cards */
        .sidebar-card {
            border: none;
            border-radius: 20px;
            background: white;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        }

        .sidebar-card .list-group-item {
            border: none;
            padding: 0.7rem 1.25rem;
            border-radius: 10px;
            margin: 2px 8px;
            transition: all 0.3s;
        }

        .sidebar-card .list-group-item:hover {
            background: #f8f9fa;
            color: #e74c3c;
            transform: translateX(8px);
        }

        .sidebar-card .list-group-item.active {
            background: #fff5f5;
            color: #e74c3c;
            border-left: 3px solid #e74c3c;
        }

        .modern-card {
            border: none;
            border-radius: 20px;
            background: white;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
            transition: transform 0.2s;
        }

        .modern-card:hover {
            transform: translateY(-3px);
        }

        .card-header-modern {
            background: white;
            border-bottom: 1px solid #eef2f6;
            font-weight: 600;
            padding: 1rem 1.25rem;
            border-radius: 20px 20px 0 0;
        }

        /* Branch Card */
        .branch-card {
            transition: all 0.3s;
        }

        .branch-card:hover {
            background: #f8f9fa;
            transform: translateX(5px);
        }

        /* Product Grid */
        .product-card {
            border: none;
            border-radius: 16px;
            transition: all 0.3s;
            overflow: hidden;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        }

        .product-img {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }

        .product-price {
            font-weight: 700;
            color: #e74c3c;
            font-size: 1.25rem;
        }

        .btn-add-cart {
            background: #e74c3c;
            border: none;
            border-radius: 30px;
            padding: 8px 16px;
            transition: all 0.3s;
        }

        .btn-add-cart:hover {
            background: #c0392b;
            transform: scale(1.02);
        }

        /* Cart Table */
        .cart-table th,
        .cart-table td {
            vertical-align: middle;
        }

        .quantity-input {
            width: 70px;
            text-align: center;
            border-radius: 30px;
        }

        /* Cart Badge */
        .cart-icon-wrapper {
            position: relative;
            display: inline-block;
        }

        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: #dc3545;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 10px;
            font-weight: bold;
            min-width: 18px;
            text-align: center;
        }

        /* Footer */
        .footer-custom {
            font-size: 0.85rem;
            color: #6c757d;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .float-icon {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #e74c3c;
            border-radius: 10px;
        }

        /* ===== MOBILE BOTTOM NAVIGATION ===== */
        .mobile-bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #ffffff;
            border-top: 1px solid #eef2f6;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.06);
            z-index: 1030;
            padding: 0.35rem 0;
            padding-bottom: calc(0.35rem + env(safe-area-inset-bottom));
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .mobile-bottom-nav .nav-items {
            display: flex;
            justify-content: space-around;
            align-items: center;
            max-width: 600px;
            margin: 0 auto;
        }

        .mobile-bottom-nav .nav-item-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.15rem;
            padding: 0.4rem 0.6rem;
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.65rem;
            font-weight: 500;
            border-radius: 12px;
            transition: all 0.2s ease;
            position: relative;
            flex: 1;
            min-width: 0;
        }

        .mobile-bottom-nav .nav-item-link i {
            font-size: 1.25rem;
            transition: all 0.2s ease;
        }

        .mobile-bottom-nav .nav-item-link:active {
            transform: scale(0.94);
        }

        .mobile-bottom-nav .nav-item-link.active {
            color: #e74c3c;
        }

        .mobile-bottom-nav .nav-item-link.active i {
            transform: translateY(-2px);
        }

        .mobile-bottom-nav .nav-item-link.active::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 24px;
            height: 3px;
            background: #e74c3c;
            border-radius: 0 0 3px 3px;
        }

        .mobile-bottom-nav .cart-nav-wrapper {
            position: relative;
        }

        .mobile-bottom-nav .cart-nav-badge {
            position: absolute;
            top: -4px;
            right: -8px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            padding: 1px 5px;
            font-size: 9px;
            font-weight: 700;
            min-width: 16px;
            text-align: center;
            line-height: 1.4;
            border: 2px solid #fff;
        }

        /* ===== MOBILE RESPONSIVE ===== */
        @media (max-width: 991.98px) {

            /* Show bottom nav, hide desktop nav links */
            .mobile-bottom-nav {
                display: block;
            }

            /* Add bottom padding to body so content isn't hidden behind bottom nav */
            body {
                padding-bottom: calc(72px + env(safe-area-inset-bottom));
            }

            /* Hide the desktop navbar-collapse entirely on mobile since bottom nav handles everything */
            #navbarCustomer {
                display: none !important;
            }

            /* Also hide the hamburger toggler since we don't need it anymore */
            .navbar-toggler {
                display: none !important;
            }

            /* Container padding */
            .container {
                padding-left: 14px;
                padding-right: 14px;
            }

            /* Footer adjustments */
            .footer-custom {
                padding: 1rem 0 !important;
                margin-top: 1rem !important;
                font-size: 0.75rem;
            }

            /* Main padding */
            main.py-4 {
                padding-top: 0.75rem !important;
                padding-bottom: 0.75rem !important;
            }

            /* Product image mobile */
            .product-img {
                height: 150px;
            }

            /* Alerts mobile */
            .alert {
                font-size: 0.82rem;
                padding: 0.65rem 0.9rem;
                border-radius: 12px;
            }

            /* Modern cards mobile */
            .modern-card {
                border-radius: 16px;
            }

            .card-header-modern {
                padding: 0.85rem 1rem;
                font-size: 0.92rem;
                border-radius: 16px 16px 0 0;
            }
        }

        /* Extra small devices */
        @media (max-width: 380px) {
            .mobile-bottom-nav .nav-item-link {
                font-size: 0.6rem;
                padding: 0.35rem 0.4rem;
            }

            .mobile-bottom-nav .nav-item-link i {
                font-size: 1.15rem;
            }
        }

        /* Tablet */
        @media (min-width: 768px) and (max-width: 991.98px) {
            body {
                padding-bottom: calc(76px + env(safe-area-inset-bottom));
            }

            .mobile-bottom-nav .nav-item-link {
                font-size: 0.7rem;
                padding: 0.45rem 0.8rem;
            }

            .mobile-bottom-nav .nav-item-link i {
                font-size: 1.35rem;
            }
        }

        /* Touch device: remove hover transforms */
        @media (hover: none) {
            .modern-card:hover {
                transform: none;
            }

            .product-card:hover {
                transform: none;
            }

            .branch-card:hover {
                transform: none;
            }

            .sidebar-card .list-group-item:hover {
                transform: none;
            }

            .modern-card:active {
                transform: scale(0.99);
            }

            .product-card:active {
                transform: scale(0.98);
            }
        }

        /* iPhone notch safe area */
        @supports (padding: env(safe-area-inset-top)) {
            .navbar-custom {
                padding-top: env(safe-area-inset-top);
            }
        }

        /* Better tap target */
        .navbar-toggler {
            border: none;
            padding: 0.4rem 0.6rem;
        }

        .navbar-toggler:focus {
            box-shadow: none;
            outline: none;
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.9%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
    </style>
    @stack('styles')
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand text-white fw-bold" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Vape Expo" height="32"
                    class="d-inline-block align-text-top me-2">
                Vape Expo
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCustomer"
                aria-controls="navbarCustomer" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCustomer">
                <ul class="navbar-nav ms-auto align-items-center gap-2">
                    <li class="nav-item">
                        <a href="{{ route('customer.products.index') }}"
                            class="btn btn-outline-light btn-sm rounded-pill">
                            <i class="bi bi-shop"></i> Shop
                        </a>
                    </li>
                    <li class="nav-item">
                        <div class="cart-icon-wrapper">
                            <a href="{{ route('customer.cart.index') }}"
                                class="btn btn-outline-light btn-sm rounded-pill">
                                <i class="bi bi-cart"></i> Cart
                            </a>
                            @php
                                $cartCount = \App\Helpers\CartHelper::getItemCount();
                            @endphp
                            @if ($cartCount > 0)
                                <span class="cart-badge">{{ $cartCount }}</span>
                            @endif
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('customer.orders.index') }}"
                            class="btn btn-outline-light btn-sm rounded-pill">
                            <i class="bi bi-receipt"></i> Orders
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" role="button"
                            data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('customer.dashboard') }}">Dashboard</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
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
        @if (session('success'))
            <div class="container">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        {{-- ============================================================ --}}
        {{-- DISABLED THE ERROR ALERT TO PREVENT "Undefined variable" UI CRASH --}}
        {{-- ============================================================ --}}
        {{-- 
        @if (session('error'))
            <div class="container">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif
        --}}
        {{-- ============================================================ --}}

        @if (session('info'))
            <div class="container">
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="bi bi-info-circle-fill me-2"></i> {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="footer-custom text-center py-4 mt-4">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} Vape Expo. All rights reserved.</p>
        </div>
    </footer>

    <!-- ===== MOBILE BOTTOM NAVIGATION (App-like) ===== -->
    <nav class="mobile-bottom-nav">
        <div class="nav-items">
            <a href="{{ route('customer.dashboard') }}"
                class="nav-item-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                <i class="bi bi-house-door{{ request()->routeIs('customer.dashboard') ? '-fill' : '' }}"></i>
                <span>Home</span>
            </a>
            <a href="{{ route('customer.products.index') }}"
                class="nav-item-link {{ request()->routeIs('customer.products.*') ? 'active' : '' }}">
                <i class="bi bi-shop{{ request()->routeIs('customer.products.*') ? '-window' : '' }}"></i>
                <span>Shop</span>
            </a>
            <a href="{{ route('customer.cart.index') }}"
                class="nav-item-link cart-nav-wrapper {{ request()->routeIs('customer.cart.*') ? 'active' : '' }}">
                <i class="bi bi-cart{{ request()->routeIs('customer.cart.*') ? '-fill' : '' }}"></i>
                @if ($cartCount > 0)
                    <span class="cart-nav-badge">{{ $cartCount }}</span>
                @endif
                <span>Cart</span>
            </a>
            <a href="{{ route('customer.orders.index') }}"
                class="nav-item-link {{ request()->routeIs('customer.orders.*') ? 'active' : '' }}">
                <i class="bi bi-receipt{{ request()->routeIs('customer.orders.*') ? '-cutoff' : '' }}"></i>
                <span>Orders</span>
            </a>
            <a href="#" class="nav-item-link" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle"></i>
                <span>Account</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" style="position: fixed; bottom: 80px; right: 12px;">
                <li>
                    <h6 class="dropdown-header">{{ Auth::user()->name }}</h6>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="{{ route('customer.dashboard') }}"><i
                            class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="dropdown-item text-danger" type="submit"><i
                                class="bi bi-box-arrow-right me-2"></i>Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>