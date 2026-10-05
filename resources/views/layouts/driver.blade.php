<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Driver Dashboard - Vape Expo</title>
    <meta name="theme-color" content="#1a1a2e">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        * {
            -webkit-tap-highlight-color: transparent;
        }

        html {
            -webkit-overflow-scrolling: touch;
        }

        body {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            overscroll-behavior-y: none;
        }

        .navbar-driver {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 60px;
            z-index: 1050;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .btn {
            border-radius: 30px;
        }

        .badge {
            border-radius: 30px;
            padding: 0.35rem 0.75rem;
        }

        /* Minimal Home button matching the dark theme */
        .btn-home-minimal {
            color: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 0.25rem 0.8rem;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }

        .btn-home-minimal:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            border-color: rgba(255, 255, 255, 0.4);
        }

        /* ================================================================ */
        /* ✅ SIDEBAR STYLES (Desktop only — hidden on mobile) */
        /* ================================================================ */
        .app-sidebar {
            position: fixed !important;
            top: 80px !important;
            left: 0 !important;
            width: 260px !important;
            height: auto !important;
            max-height: calc(100vh - 100px) !important;
            background: #ffffff;
            border-radius: 16px !important;
            box-shadow: 2px 0 20px rgba(0, 0, 0, 0.05);
            z-index: 1040 !important;
            overflow-y: auto !important;
            padding-bottom: 10px !important;
            margin-top: 0 !important;
            transform: none !important;
            transition: none !important;
        }

        .sidebar-header {
            background: #1e293b;
            padding: 18px 20px;
            text-align: center;
            color: #fff;
            border-radius: 16px 16px 0 0;
        }

        .sidebar-header h6 {
            font-weight: 600;
            margin: 0;
            letter-spacing: 0.3px;
        }

        .sidebar-menu {
            padding: 12px;
        }

        .sidebar-menu .menu-item {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            border-radius: 12px;
            color: #64748b;
            text-decoration: none;
            transition: all 0.2s ease;
            margin-bottom: 4px;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .sidebar-menu .menu-item i {
            font-size: 1.1rem;
            width: 24px;
            text-align: center;
            margin-right: 14px;
        }

        .sidebar-menu .menu-item:hover {
            background: #f1f5f9;
            color: #1e293b;
        }

        .sidebar-menu .menu-item.active {
            background: #eff6ff;
            color: #2563eb;
            border-left: 3px solid #2563eb;
        }

        /* ================================================================ */
        /* ✅ CONTENT MARGINS - Desktop with sidebar */
        /* ================================================================ */
        body.has-sidebar .container.mt-4 {
            margin-left: 280px !important;
            max-width: calc(100% - 280px) !important;
            padding-right: 20px !important;
            padding-top: 80px !important;
        }

        /* ✅ Normal pages (No sidebar) - Centered */
        body:not(.has-sidebar) .container.mt-4 {
            padding-top: 80px !important;
            max-width: 1140px !important;
        }

        /* ================================================================ */
        /* ✅ MOBILE BOTTOM NAVIGATION (App-like) */
        /* ================================================================ */
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
            background: none;
            border: none;
        }

        .mobile-bottom-nav .nav-item-link i {
            font-size: 1.25rem;
            transition: all 0.2s ease;
        }

        .mobile-bottom-nav .nav-item-link:active {
            transform: scale(0.94);
        }

        .mobile-bottom-nav .nav-item-link.active {
            color: #2563eb;
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
            background: #2563eb;
            border-radius: 0 0 3px 3px;
        }

        /* Account dropdown for mobile */
        .driver-account-dropdown {
            position: fixed;
            bottom: calc(72px + env(safe-area-inset-bottom));
            right: 12px;
            min-width: 240px;
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #eef2f6;
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
            padding: 0.5rem;
            z-index: 1040;
        }

        .driver-account-dropdown .dropdown-header {
            padding: 0.65rem 0.85rem;
            font-weight: 600;
            color: #1a1a2e;
            font-size: 0.88rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            border-bottom: 1px solid #f1f5f9;
            margin-bottom: 0.35rem;
        }

        .driver-account-dropdown .dropdown-header i {
            color: #2563eb;
            font-size: 1.1rem;
        }

        .driver-account-dropdown .dropdown-header .account-email {
            display: block;
            font-size: 0.72rem;
            color: #64748b;
            font-weight: 400;
            margin-top: 0.1rem;
        }

        .driver-account-dropdown .dropdown-item {
            padding: 0.65rem 0.85rem;
            border-radius: 10px;
            font-size: 0.85rem;
            color: #374151;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            transition: all 0.15s ease;
        }

        .driver-account-dropdown .dropdown-item:active {
            background: #f1f5f9;
        }

        .driver-account-dropdown .dropdown-item.text-danger {
            color: #dc2626 !important;
        }

        /* ================================================================ */
        /* RESPONSIVE — Mobile behavior */
        /* ================================================================ */
        @media (max-width: 991px) {
            /* HIDE the sidebar completely on mobile — bottom nav takes over */
            .app-sidebar {
                display: none !important;
            }

            /* Show bottom navigation on mobile */
            .mobile-bottom-nav {
                display: block;
            }

            /* Add bottom padding to body so content isn't hidden behind bottom nav */
            body {
                padding-bottom: calc(72px + env(safe-area-inset-bottom));
            }

            /* No sidebar margins on mobile */
            body.has-sidebar .container.mt-4,
            body:not(.has-sidebar) .container.mt-4 {
                margin-left: 0 !important;
                max-width: 100% !important;
                padding-right: 15px !important;
                padding-left: 15px !important;
            }
        }

        /* ================================================================ */
        /* ✅ MOBILE-APP NAVBAR (Android + iPhone)                          */
        /* ================================================================ */
        @media (max-width: 767.98px) {

            /* ---------- Navbar ---------- */
            .navbar-driver {
                height: 56px;
                padding-top: env(safe-area-inset-top);
                height: calc(56px + env(safe-area-inset-top));
            }

            .navbar-driver .container-fluid {
                padding-left: 10px !important;
                padding-right: 10px !important;
                height: 100%;
                align-items: center;
            }

            /* ✅ HIDE the Home button on mobile */
            .btn-home-minimal {
                display: none !important;
            }

            /* Brand text is visible on mobile, just smaller */
            .navbar-brand {
                font-size: 0.82rem;
                padding: 0;
                margin: 0;
                font-weight: 600;
                white-space: nowrap;
            }

            .navbar-brand img {
                height: 22px !important;
                margin-right: 6px !important;
            }

            .navbar-brand .brand-text {
                display: inline;
                color: #fff;
                font-size: 0.82rem;
                letter-spacing: -0.2px;
            }

            /* HIDE the entire user info block (profile icon + name) on mobile */
            .navbar-driver .navbar-user-info {
                display: none !important;
            }

            /* Also hide the navbar logout button (bottom nav has it) */
            .navbar-driver .btn-outline-light {
                display: none;
            }

            /* ---------- Main content padding ---------- */
            body:not(.has-sidebar) .container.mt-4,
            body.has-sidebar .container.mt-4 {
                padding-top: calc(72px + env(safe-area-inset-top)) !important;
                padding-bottom: 2rem !important;
                padding-left: 14px !important;
                padding-right: 14px !important;
            }

            .container-fluid {
                padding-left: 14px;
                padding-right: 14px;
            }

            /* ---------- Cards ---------- */
            .card {
                border-radius: 14px;
            }

            /* ---------- Alerts (compact, rounded) ---------- */
            .alert {
                font-size: 0.82rem;
                padding: 0.7rem 0.9rem;
                border-radius: 12px;
                line-height: 1.4;
            }

            /* ---------- Buttons (bigger touch target) ---------- */
            .btn {
                padding: 0.5rem 1rem;
                font-size: 0.85rem;
                font-weight: 500;
            }

            .btn-sm {
                padding: 0.4rem 0.85rem;
                font-size: 0.78rem;
            }

            /* ---------- Badges ---------- */
            .badge {
                font-size: 0.7rem;
                padding: 0.3rem 0.65rem;
            }

            /* ---------- Modal (bottom sheet) ---------- */
            #customModal {
                align-items: flex-end !important;
                background: rgba(0, 0, 0, 0.55) !important;
            }

            #customModal > div {
                width: 100% !important;
                max-width: 100% !important;
                max-height: 92vh !important;
                border-radius: 24px 24px 0 0 !important;
                animation: driverSheetUp 0.3s ease-out;
                padding-bottom: env(safe-area-inset-bottom);
            }

            @keyframes driverSheetUp {
                from { transform: translateY(100%); }
                to { transform: translateY(0); }
            }

            /* ---------- Table responsiveness (safety) ---------- */
            .table-responsive {
                -webkit-overflow-scrolling: touch;
            }

            /* ---------- Inputs / selects (16px prevents iOS zoom) ---------- */
            input.form-control,
            select.form-select,
            textarea.form-control {
                font-size: 16px;
            }

            /* ---------- Dropdowns ---------- */
            .dropdown-menu {
                border-radius: 12px;
                border: 1px solid #eef2f6;
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
                font-size: 0.85rem;
                padding: 0.35rem;
            }

            .dropdown-item {
                padding: 0.55rem 0.75rem;
                border-radius: 8px;
                font-size: 0.85rem;
            }

            .dropdown-item:active {
                background: #f1f5f9;
            }

            /* ---------- Bottom nav compact ---------- */
            .mobile-bottom-nav .nav-item-link {
                font-size: 0.62rem;
                padding: 0.35rem 0.4rem;
            }

            .mobile-bottom-nav .nav-item-link i {
                font-size: 1.2rem;
            }
        }

        /* Extra small devices */
        @media (max-width: 380px) {
            .navbar-driver {
                height: 52px;
                height: calc(52px + env(safe-area-inset-top));
            }

            .navbar-brand {
                font-size: 0.75rem;
            }

            .navbar-brand img {
                height: 20px !important;
                margin-right: 5px !important;
            }

            .navbar-brand .brand-text {
                font-size: 0.75rem;
            }

            body:not(.has-sidebar) .container.mt-4,
            body.has-sidebar .container.mt-4 {
                padding-top: calc(66px + env(safe-area-inset-top)) !important;
                padding-left: 12px !important;
                padding-right: 12px !important;
            }

            .mobile-bottom-nav .nav-item-link {
                font-size: 0.58rem;
                padding: 0.3rem 0.3rem;
            }

            .mobile-bottom-nav .nav-item-link i {
                font-size: 1.1rem;
            }

            .driver-account-dropdown {
                min-width: 220px;
                right: 8px;
            }
        }

        /* Touch device — remove hover */
        @media (hover: none) {
            .btn-home-minimal:hover {
                background: transparent;
                border-color: rgba(255, 255, 255, 0.2);
            }

            .sidebar-menu .menu-item:hover {
                background: transparent;
                color: #64748b;
            }
        }

        /* iPhone safe area for navbar */
        @supports (padding: env(safe-area-inset-top)) {
            @media (max-width: 767.98px) {
                .navbar-driver {
                    padding-top: env(safe-area-inset-top);
                }
            }
        }
    </style>
</head>

<body class="@yield('page-class')">
    <nav class="navbar navbar-driver navbar-dark">
        <div class="container-fluid px-5">
            <div class="d-flex align-items-center">
                <!-- ✅ HOME BUTTON (visible on desktop, hidden on mobile) -->
                <a href="{{ route('driver.dashboard') }}" class="btn btn-home-minimal rounded-pill me-3">
                    <i class="bi bi-house-door me-1"></i> <span class="home-label">Home</span>
                </a>

                <!-- Logo & Brand Name -->
                <a class="navbar-brand" href="{{ route('driver.dashboard') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="Vape Expo" height="30"
                        class="d-inline-block align-text-top me-2">
                    <span class="brand-text">Vape Expo Driver</span>
                </a>
            </div>

            <div class="d-flex align-items-center">
                <span class="text-white me-3 navbar-user-info">
                    <i class="bi bi-person-circle"></i> <span class="user-name">{{ Auth::user()->name }}</span>
                </span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button class="btn btn-outline-light btn-sm rounded-pill">
                        <i class="bi bi-box-arrow-right"></i> <span class="logout-label">Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Sidebar (desktop only — hidden on mobile via CSS) -->
    <div class="app-sidebar">
        <div class="sidebar-header">
            <h6><i class="bi bi-grid-3x3-gap-fill"></i> Driver Menu</h6>
        </div>
        <div class="sidebar-menu">
            <a href="{{ route('driver.dashboard') }}"
                class="menu-item {{ request()->routeIs('driver.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="{{ route('driver.online-orders.index') }}"
                class="menu-item {{ request()->routeIs('driver.online-orders*') ? 'active' : '' }}">
                <i class="bi bi-cart"></i> Online Orders
            </a>
            <a href="{{ route('driver.delivery-history', ['sidebar' => 1]) }}"
                class="menu-item {{ request()->routeIs('driver.delivery-history') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i> Delivery History
            </a>
        </div>
    </div>

    <div class="container mt-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @yield('content')
    </div>

    <!-- Custom Modal Structure -->
    <div id="customModal"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
        <div
            style="background: white; border-radius: 12px; width: 90%; max-width: 900px; max-height: 90vh; overflow-y: auto; box-shadow: 0 5px 20px rgba(0,0,0,0.3);">
            <div id="customModalContent"></div>
        </div>
    </div>

    <!-- ===== MOBILE BOTTOM NAVIGATION (App-like) ===== -->
    <nav class="mobile-bottom-nav">
        <div class="nav-items">
            <a href="{{ route('driver.dashboard') }}"
                class="nav-item-link {{ request()->routeIs('driver.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('driver.online-orders.index') }}"
                class="nav-item-link {{ request()->routeIs('driver.online-orders*') ? 'active' : '' }}">
                <i class="bi bi-cart{{ request()->routeIs('driver.online-orders*') ? '-fill' : '' }}"></i>
                <span>Orders</span>
            </a>
            <a href="{{ route('driver.delivery-history', ['sidebar' => 1]) }}"
                class="nav-item-link {{ request()->routeIs('driver.delivery-history') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i>
                <span>History</span>
            </a>
            {{-- Account tab with dropdown containing name + logout --}}
            <a href="#" class="nav-item-link" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle"></i>
                <span>Account</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end driver-account-dropdown">
                <li>
                    <div class="dropdown-header">
                        <i class="bi bi-person-circle"></i>
                        <div>
                            {{ Auth::user()->name }}
                            <span class="account-email">{{ Auth::user()->email }}</span>
                        </div>
                    </div>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="dropdown-item text-danger" type="submit">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Global function for handling order status updates
        window.handleStatus = function(action, orderId) {
            console.log('handleStatus called with:', action, orderId);

            const resultDiv = document.getElementById('result');
            if (resultDiv) {
                resultDiv.innerHTML = '<div class="alert alert-info">Processing...</div>';
            }

            let url = '';
            if (action === 'confirm') url = '/driver/online-orders/' + orderId + '/confirm';
            else if (action === 'processing') url = '/driver/online-orders/' + orderId + '/processing';
            else if (action === 'ready') url = '/driver/online-orders/' + orderId + '/ready';

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (resultDiv) {
                            resultDiv.innerHTML = '<div class="alert alert-success">' + data.message + '</div>';
                        }
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        if (resultDiv) {
                            resultDiv.innerHTML = '<div class="alert alert-danger">' + (data.message ||
                                'Error occurred') + '</div>';
                        }
                    }
                })
                .catch(function(error) {
                    console.error('Error:', error);
                    if (resultDiv) {
                        resultDiv.innerHTML = '<div class="alert alert-danger">Error: ' + error.message + '</div>';
                    }
                });
        };

        function openDeliveryModal(deliveryId) {
            const modal = document.getElementById('customModal');
            const modalContent = document.getElementById('customModalContent');

            modalContent.innerHTML = `
                <div style="padding: 20px; text-align: center;">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2">Loading delivery details...</p>
                </div>
            `;
            modal.style.display = 'flex';

            fetch(`/driver/deliveries/${deliveryId}`)
                .then(response => response.text())
                .then(html => {
                    modalContent.innerHTML = html;
                })
                .catch(error => {
                    modalContent.innerHTML = `
                        <div style="padding: 20px;">
                            <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px;">
                                Failed to load delivery details. Please try again.
                            </div>
                            <div style="text-align: center; margin-top: 15px;">
                                <button onclick="closeModal()" class="btn btn-secondary">Close</button>
                            </div>
                        </div>
                    `;
                });
        }

        function closeModal() {
            document.getElementById('customModal').style.display = 'none';
            document.getElementById('customModalContent').innerHTML = '';
        }

        // Close modal when clicking outside
        document.getElementById('customModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>

</html>