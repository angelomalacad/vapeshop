<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard - Vape Expo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background: #f5f7fb;
            min-height: 100vh;
        }
        
        /* ===== ANIMATIONS ===== */
        
        /* Slide Down Animation for Navbar */
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
        
        /* Fade In Up Animation */
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
        
        /* Fade In Left Animation */
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
        
        /* Fade In Right Animation */
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
        
        /* Floating Animation for Icons */
        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-8px);
            }
        }
        
        /* Pulse Animation */
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }
        
        /* Shine Effect */
        @keyframes shine {
            0% {
                left: -100%;
            }
            100% {
                left: 100%;
            }
        }
        
        /* Rotate Animation for Banner */
        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }
        
        /* Apply Animations */
        .navbar-custom {
            background: #1a1a2e;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            animation: slideDown 0.5s ease;
        }
        
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
            background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
            border-radius: 50%;
            animation: rotate 20s linear infinite;
        }
        
        .sidebar-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            background: white;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
            animation: fadeInLeft 0.5s ease;
        }
        
        /* Action Cards with Staggered Animation */
        .action-card {
            border: none;
            border-radius: 20px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: white;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
            opacity: 0;
            animation: fadeInUp 0.5s ease forwards;
            position: relative;
            overflow: hidden;
        }
        
        /* Shine effect on hover */
        .action-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s ease;
        }
        
        .action-card:hover::before {
            left: 100%;
        }
        
        .action-card:nth-child(1) { animation-delay: 0.1s; }
        .action-card:nth-child(2) { animation-delay: 0.2s; }
        .action-card:nth-child(3) { animation-delay: 0.3s; }
        
        .action-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 30px rgba(0,0,0,0.12);
        }
        
        .action-card .card-icon {
            width: 55px;
            height: 55px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }
        
        .action-card:hover .card-icon {
            transform: scale(1.05);
        }
        
        .card-icon.primary { background: rgba(231, 76, 60, 0.1); color: #e74c3c; }
        .card-icon.success { background: rgba(46, 204, 113, 0.1); color: #27ae60; }
        .card-icon.info { background: rgba(52, 152, 219, 0.1); color: #3498db; }
        
        .action-card .btn {
            border-radius: 12px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .action-card .btn:hover {
            transform: translateY(-2px);
        }
        
        /* Modern Cards */
        .modern-card {
            border: none;
            border-radius: 20px;
            background: white;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
            animation: fadeInUp 0.5s ease;
            transition: all 0.3s ease;
        }
        
        .modern-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }
        
        .card-header-modern {
            background: white;
            border-bottom: 1px solid #eef2f6;
            font-weight: 600;
            padding: 1rem 1.25rem;
            border-radius: 20px 20px 0 0 !important;
            color: #1a1a2e;
        }
        
        .card-header-modern i {
            color: #e74c3c;
        }
        
        /* Branch Cards with Hover Animation */
        .branch-card {
            border: 1px solid #eef2f6;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: white;
            margin-bottom: 0.75rem;
            animation: fadeInRight 0.5s ease;
        }
        
        .branch-card:hover {
            border-color: #e74c3c;
            transform: translateX(8px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        
        /* Sidebar Items Animation */
        .sidebar-card .list-group-item {
            background: white;
            color: #4a5568;
            border: none;
            padding: 0.7rem 1.25rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin: 2px 8px;
            border-radius: 10px;
        }
        
        .sidebar-card .list-group-item:hover {
            background: #f8f9fa;
            color: #e74c3c;
            transform: translateX(8px);
        }
        
        .sidebar-card .list-group-item.active {
            background: #fff5f5;
            color: #e74c3c;
            font-weight: 500;
            border-left: 3px solid #e74c3c;
        }
        
        .sidebar-card .list-group-item i {
            width: 24px;
            color: #a0aec0;
            transition: transform 0.3s ease;
        }
        
        .sidebar-card .list-group-item:hover i {
            transform: scale(1.1);
            color: #e74c3c;
        }
        
        /* Floating Icon Animation */
        .float-icon {
            animation: float 3s ease-in-out infinite;
        }
        
        /* Pulse Animation for attention */
        .pulse-icon {
            animation: pulse 2s ease-in-out infinite;
        }
        
        /* Custom Scrollbar */
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
        
        /* Responsive */
        @media (max-width: 768px) {
            .action-card {
                margin-bottom: 1rem;
            }
            
            .welcome-banner {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation with Slide Down Animation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand text-white" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="32" class="d-inline-block align-text-top me-2">
                Vape Expo
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item me-3">
                        <span class="text-white-50 small">
                            <i class="bi bi-person-circle me-1 float-icon"></i> Welcome, <strong class="text-white">{{ Auth::user()->name }}</strong>
                        </span>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-sm rounded-pill px-3">
                                <i class="bi bi-box-arrow-right me-1"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="container mt-4">
        <!-- Welcome Banner with Rotating Background Animation -->
        <div class="welcome-banner">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="text-white mb-2 fw-bold">
                        <i class="bi bi-stars me-2 text-warning float-icon"></i>Welcome back, {{ Auth::user()->name }}!
                    </h4>
                    <p class="text-white-50 mb-0">Discover the best vaping experience at Vape Expo. Quality products, great prices, and friendly service.</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="d-inline-block bg-white bg-opacity-10 rounded-3 px-4 py-2">
                        <i class="bi bi-calendar3 text-white me-2"></i>
                        <span class="text-white">{{ now()->format('l, F j, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Sidebar Menu with Fade In Left Animation -->
            <div class="col-md-3">
                <div class="card sidebar-card">
                    <div class="card-header">
                        <i class="bi bi-grid me-2"></i> Customer Menu
                    </div>
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

                <!-- Shop Info Card with Animation -->
                <div class="card modern-card mt-3">
                    <div class="card-header-modern">
                        <i class="bi bi-info-circle me-2"></i> Shop Information
                    </div>
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-person-circle fs-5 me-3 float-icon" style="color: #e74c3c;"></i>
                            <div>
                                <small class="text-muted d-block">Owner</small>
                                <strong class="small">Carlo Caranto</strong>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-telephone fs-5 me-3 float-icon" style="color: #27ae60;"></i>
                            <div>
                                <small class="text-muted d-block">Contact</small>
                                <strong class="small">0960 328 0432</strong>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-clock fs-5 me-3 float-icon" style="color: #f39c12;"></i>
                            <div>
                                <small class="text-muted d-block">Business Hours</small>
                                <strong class="small">9:00 AM – 10:00 PM</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <!-- Quick Action Cards with Staggered Fade In Up Animation -->
                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="card action-card">
                            <div class="card-body">
                                <div class="card-icon primary">
                                    <i class="bi bi-shop fs-3"></i>
                                </div>
                                <h5 class="card-title">Browse Products</h5>
                                <p class="card-text">View all available vape products from our collection.</p>
                                <a href="{{ route('customer.products.index') }}" class="btn btn-primary w-100 rounded-pill">
                                    Shop Now <i class="bi bi-arrow-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card action-card">
                            <div class="card-body">
                                <div class="card-icon success">
                                    <i class="bi bi-cart fs-3"></i>
                                </div>
                                <h5 class="card-title">My Cart</h5>
                                <p class="card-text">View and manage your shopping cart items.</p>
                                <a href="{{ route('customer.cart.index') }}" class="btn btn-success w-100 rounded-pill">
                                    View Cart <i class="bi bi-arrow-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card action-card">
                            <div class="card-body">
                                <div class="card-icon info">
                                    <i class="bi bi-geo-alt fs-3"></i>
                                </div>
                                <h5 class="card-title">Branches</h5>
                                <p class="card-text">Find our 5 branches near you in Calamba.</p>
                                <a href="{{ route('branches.index') }}" class="btn btn-info w-100 rounded-pill">
                                    View Branches <i class="bi bi-arrow-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Two Column Layout -->
                <div class="row g-4">
                    <!-- Recent Orders -->
                    <div class="col-md-7">
                        <div class="card modern-card">
                            <div class="card-header-modern">
                                <i class="bi bi-clock-history me-2"></i> Recent Orders
                            </div>
                            <div class="card-body">
                                <div class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-1 d-block mb-3 float-icon" style="color: #cbd5e0;"></i>
                                    <p class="mb-3">No orders yet. Start shopping now!</p>
                                    <a href="{{ route('customer.products.index') }}" class="btn btn-primary rounded-pill px-4">
                                        <i class="bi bi-shop me-2"></i>Start Shopping
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Branch Information with Fade In Right Animation -->
                    <div class="col-md-5">
                        <div class="card modern-card">
                            <div class="card-header-modern">
                                <i class="bi bi-geo-alt me-2"></i> Our Branches
                            </div>
                            <div class="card-body p-3" style="max-height: 380px; overflow-y: auto;">
                                <div class="branch-card p-2">
                                    <div class="d-flex align-items-start">
                                        <i class="bi bi-building fs-5 me-2 text-primary float-icon"></i>
                                        <div class="flex-grow-1">
                                            <strong class="d-block">Majada Out Branch</strong>
                                            <small class="text-muted d-block">EFG Building, Majada Out Road</small>
                                            <span class="badge mt-1">Near 7-Eleven and Gran Avila</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="branch-card p-2">
                                    <div class="d-flex align-items-start">
                                        <i class="bi bi-building fs-5 me-2 text-primary float-icon"></i>
                                        <div class="flex-grow-1">
                                            <strong class="d-block">Asia 1 Branch</strong>
                                            <small class="text-muted d-block">Blk 67 Lot 1 Asia 1 Rd., Canlubang</small>
                                            <span class="badge mt-1">Near Hernandez Grocery and Grimaldo</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="branch-card p-2">
                                    <div class="d-flex align-items-start">
                                        <i class="bi bi-building fs-5 me-2 text-primary float-icon"></i>
                                        <div class="flex-grow-1">
                                            <strong class="d-block">MCDC Branch</strong>
                                            <small class="text-muted d-block">Blk 1 Lot 10 Kapayapaan, Canlubang</small>
                                            <span class="badge mt-1">Near Geosnack and Mango Royale</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="branch-card p-2">
                                    <div class="d-flex align-items-start">
                                        <i class="bi bi-building fs-5 me-2 text-primary float-icon"></i>
                                        <div class="flex-grow-1">
                                            <strong class="d-block">Paciano Branch</strong>
                                            <small class="text-muted d-block">215 National Road, Brgy. Paciano Rizal</small>
                                            <span class="badge mt-1">In front of Barangay Hall and 7-Eleven</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="branch-card p-2">
                                    <div class="d-flex align-items-start">
                                        <i class="bi bi-building fs-5 me-2 text-primary float-icon"></i>
                                        <div class="flex-grow-1">
                                            <strong class="d-block">Paciano V2 Branch</strong>
                                            <small class="text-muted d-block">39 Mayapa-Canlubang Cadre Road</small>
                                            <span class="badge mt-1">Near the area</span>
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-2">
                                <div class="text-center mt-2">
                                    <i class="bi bi-clock me-1 text-warning small float-icon"></i>
                                    <small class="text-muted">All branches open daily: <strong>9:00 AM – 10:00 PM</strong></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer-custom mt-4 pt-3">
            <div class="row">
                <div class="col-md-6 text-md-start">
                    <small><i class="bi bi-shield-check me-1"></i> Vape Expo - Quality Vape Products Since 2024</small>
                </div>
                <div class="col-md-6 text-md-end">
                    <small><i class="bi bi-telephone me-1"></i> Need help? Contact owner: <strong>Carlo Caranto - 0960 328 0432</strong></small>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>