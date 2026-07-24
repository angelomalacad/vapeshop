<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vape Expo - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #0d6efd;
            --dark-bg: #0f172a;
            --card-radius: 16px;
            --section-padding: 80px 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #ffffff;
            color: #1e293b;
        }

        /* --- Header & Nav --- */
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            border-bottom: 1px solid #f1f5f9;
            padding: 15px 0;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.4rem;
            color: var(--primary-color) !important;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Logo Image Placeholder */
        .navbar-brand img {
            height: 40px;
            width: 40px;
            object-fit: contain;
            border-radius: 8px;
            background: #f1f5f9;
        }

        .nav-link {
            font-weight: 500;
            color: #475569 !important;
            margin: 0 10px;
            transition: color 0.2s;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
        }

        /* --- Login Button --- */
        .btn-nav-login {
            background-color: #1e293b;
            color: #ffffff !important;
            padding: 8px 24px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .btn-nav-login:hover {
            background-color: var(--primary-color);
            color: #ffffff !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
            text-shadow: 0 0 4px rgba(255, 255, 255, 0.2);
        }

        /* --- ADDED: Register Button --- */
        .btn-nav-register {
            background-color: transparent;
            border: 1px solid var(--primary-color);
            color: var(--primary-color) !important;
            padding: 8px 24px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            margin-right: 10px;
        }

        .btn-nav-register:hover {
            background-color: var(--primary-color);
            color: #ffffff !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
        }

        /* --- Hero Section (Uses Asset Image) --- */
        #home {
            padding-top: 140px;
            padding-bottom: 100px;
            position: relative;
            min-height: 70vh; 
            background-color: #e2e8f0; 
            background-image: url('{{ asset('images/geekbar.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        #home::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(255, 255, 255, 0.6);
            z-index: 0;
        }

        .hero-container {
            position: relative;
            z-index: 1;
        }

        .hero-text {
            text-align: center;
        }

        .hero-badge {
            display: inline-block;
            background-color: #e0f2fe;
            color: #0369a1;
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 20px;
            letter-spacing: -1px;
        }

        .hero-title span {
            color: var(--primary-color);
        }

        .hero-subtitle {
            font-size: 1.15rem;
            color: #64748b;
            max-width: 600px;
            margin: 0 auto 30px auto;
            line-height: 1.6;
        }

        .hero-buttons .btn {
            padding: 12px 32px;
            border-radius: 50px;
            font-weight: 600;
            margin: 0 8px;
        }

        .btn-hero-primary {
            background-color: #1e293b;
            color: #fff;
            border: none;
        }

        .btn-hero-primary:hover {
            background-color: var(--primary-color);
            color: #fff;
        }

        .btn-hero-secondary {
            background-color: #f1f5f9;
            color: #1e293b;
        }

        .btn-hero-secondary:hover {
            background-color: #e2e8f0;
        }

        /* --- Story Section --- */
        #story {
            padding: var(--section-padding);
            background: #ffffff;
        }

        .story-text-group {
            margin-bottom: 40px;
        }

        .story-text-group .badge-label {
            background: #f1f5f9;
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #1e293b;
            display: inline-block;
            margin-bottom: 12px;
        }

        .story-text-group h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .story-text-group p {
            color: #64748b;
            font-size: 1.1rem;
            margin: 0;
        }

        .founder-profile-card {
            background-color: #f8fafc;
            border-radius: var(--card-radius);
            padding: 24px;
            margin-bottom: 30px;
        }

        .founder-title {
            font-weight: 700;
            font-size: 1rem;
            margin-bottom: 4px;
        }
        .founder-role {
            font-size: 0.9rem;
            color: #64748b;
        }
        .founder-desc {
            font-size: 0.95rem;
            color: #1e293b;
            margin: 12px 0 16px 0;
        }
        .founder-meta {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            color: #64748b;
            font-size: 0.85rem;
        }
        .founder-meta i {
            margin-right: 6px;
        }

        .timeline-group {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .timeline-row {
            display: flex;
            flex-direction: row;
            align-items: flex-start;
            gap: 20px;
        }

        .timeline-date-box {
            min-width: 80px;
            background-color: #f1f5f9;
            padding: 4px 12px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.75rem;
            color: #1e293b;
            text-align: center;
        }

        .timeline-content-box h6 {
            font-weight: 700;
            margin-bottom: 2px;
            font-size: 0.95rem;
        }

        .timeline-content-box p {
            color: #64748b;
            font-size: 0.9rem;
            margin: 0;
            line-height: 1.4;
        }

        /* --- Stats Section --- */
        #stats {
            padding: 40px 0;
            background-color: #f8fafc;
        }

        .stat-card {
            background: #fff;
            border-radius: var(--card-radius);
            padding: 30px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
            border: 1px solid #f1f5f9;
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-number {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #64748b;
            font-size: 0.95rem;
        }

        /* --- Products Section --- */
        #products {
            padding: var(--section-padding);
            background: #fff;
        }

        .product-card {
            background: #fff;
            border: 1px solid #f1f5f9;
            border-radius: var(--card-radius);
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
            height: 100%;
        }

        .product-card:hover {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transform: translateY(-5px);
        }

        .product-img-wrapper {
            height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            background-color: #f8fafc;
            border-radius: 8px;
            width: 100%;
        }

        .product-img-wrapper img {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
        }

        .product-card h5 {
            font-weight: 700;
            margin-bottom: 5px;
        }

        .product-card p {
            color: #64748b;
            font-size: 0.9rem;
        }

        .product-btn {
            border-radius: 50px;
            padding: 8px 20px;
            font-size: 0.85rem;
            background: #f1f5f9;
            color: #1e293b;
            border: none;
            text-decoration: none;
            display: inline-block;
        }

        .product-btn:hover {
            background: var(--primary-color);
            color: #fff;
        }

        /* --- Delivery Section --- */
        #delivery {
            padding: var(--section-padding);
            background-color: #f8fafc;
        }

        .delivery-card {
            background: #fff;
            border: 1px solid #f1f5f9;
            border-radius: var(--card-radius);
            padding: 30px;
            transition: all 0.3s ease;
            height: 100%;
        }

        .delivery-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .delivery-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 16px;
        }

        .delivery-icon-box {
            background: #f1f5f9;
            width: 54px;
            height: 54px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .delivery-icon-box i {
            font-size: 1.8rem;
            color: var(--primary-color);
        }

        .delivery-header h4 {
            font-weight: 700;
            margin-bottom: 2px;
        }

        .delivery-pill {
            display: inline-block;
            padding: 4px 14px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            background: #f1f5f9;
            color: #475569;
        }

        .delivery-content {
            color: #64748b;
            font-size: 0.95rem;
        }

        .delivery-highlight-box {
            background: #f8fafc;
            border-radius: 8px;
            padding: 16px;
            border-left: 3px solid var(--primary-color);
            margin: 16px 0;
        }
        
        .delivery-highlight-box strong {
            color: #1e293b;
        }

        .delivery-hours {
            margin-top: 8px;
        }
        
        .delivery-hours p {
            margin-bottom: 0;
        }
        
        .payment-wrapper {
            background: #fff;
            border: 1px solid #f1f5f9;
            border-radius: var(--card-radius);
            padding: 30px;
            transition: all 0.3s ease;
        }

        .payment-wrapper:hover {
            border-color: var(--primary-color);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .payment-item {
            background: #f8fafc;
            border: 1px solid #f1f5f9;
            border-radius: 12px;
            padding: 16px 20px;
            transition: all 0.3s ease;
            height: 100%;
        }

        .payment-item:hover {
            border-color: var(--primary-color);
            background: #fff;
        }

        .payment-item h6 {
            font-weight: 600;
            margin-bottom: 2px;
        }

        /* --- Branches Carousel --- */
        #branches {
            padding: var(--section-padding);
            background: #f8fafc;
        }

        #branchCarousel {
            border-radius: 20px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            margin-bottom: 50px;
        }

        .carousel-item {
            transition: transform 0.8s ease-in-out;
        }

        .carousel-item .position-relative {
            min-height: 450px;
            background: #f8fafc;
        }

        .carousel-indicators button {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #cbd5e1;
        }
        .carousel-indicators button.active {
            background-color: var(--primary-color);
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: rgba(15, 23, 42, 0.2);
            border-radius: 50%;
            padding: 1.5rem;
            background-size: 60%;
            backdrop-filter: blur(4px);
        }
        
        .carousel-control-prev-icon:hover,
        .carousel-control-next-icon:hover {
            background-color: var(--primary-color);
        }

        /* --- Branch Cards Grid --- */
        .branch-card-grid {
            background: #fff;
            border: 1px solid #f1f5f9;
            border-radius: var(--card-radius);
            padding: 24px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
            transition: transform 0.2s;
            height: 100%;
        }
        .branch-card-grid:hover {
            transform: translateY(-4px);
            border-color: #e2e8f0;
        }
        .branch-card-grid .title-wrap {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }
        .branch-card-grid h5 {
            font-weight: 700;
            margin-bottom: 0;
            font-size: 1.05rem;
        }
        .branch-card-grid .branch-badge {
            background: #f1f5f9;
            color: #475569;
            font-size: 0.7rem;
            padding: 4px 12px;
            border-radius: 50px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .branch-card-grid .branch-info {
            color: #64748b;
            font-size: 0.9rem;
            padding-bottom: 12px;
            margin-bottom: 12px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }
        .branch-card-grid .branch-info i {
            color: #94a3b8;
            margin-top: 4px;
        }
        .branch-card-grid .branch-manager {
            color: #64748b;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .branch-card-grid .branch-manager i {
            color: #94a3b8;
        }
        .branch-card-grid .branch-manager span {
            color: #1e293b;
            font-weight: 500;
        }

        /* --- Contact CTA Banner --- */
        .cta-banner {
            background-color: #0f172a;
            border-radius: 30px;
            padding: 60px;
            text-align: center;
            margin: 40px 0 60px 0;
        }

        .cta-banner h3 {
            color: #fff;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .cta-banner p {
            color: #94a3b8;
            margin-bottom: 25px;
        }

        .cta-banner .btn {
            background: #fff;
            color: #0f172a;
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 600;
        }

        .cta-banner .btn:hover {
            background: var(--primary-color);
            color: #fff;
        }

        /* --- Contact Info Section --- */
        #contact-info {
            padding: 40px 0;
        }

        .contact-card {
            background: #f8fafc;
            border-radius: var(--card-radius);
            padding: 30px;
            text-align: center;
            height: 100%;
        }

        .contact-card i {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .contact-card h6 {
            font-weight: 700;
        }

        .contact-card p {
            color: #64748b;
            margin: 0;
        }

        /* --- Footer --- */
        .footer {
            background-color: #0f172a;
            color: #cbd5e1;
            padding: 40px 0 20px 0;
        }

        .footer h5 {
            color: #fff;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .footer a {
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer a:hover {
            color: #fff;
        }

        .footer-bottom {
            border-top: 1px solid #1e293b;
            padding-top: 20px;
            margin-top: 30px;
            text-align: center;
            font-size: 0.9rem;
        }

        /* --- Media Queries --- */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.2rem;
            }
            .hero-buttons .btn {
                display: block;
                width: 100%;
                margin: 10px 0;
            }
            .cta-banner {
                padding: 30px;
            }
            .navbar-brand {
                font-size: 1.1rem;
            }
            .timeline-row {
                flex-direction: column;
                gap: 6px;
            }
            .carousel-item .position-relative {
                min-height: 350px;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">
                <!-- Logo Image Placeholder with fallback -->
                <img src="images/logo.png" onerror="this.src='https://placehold.co/40x40/0d6efd/ffffff?text=Logo'" alt="VapeExpo Logo">
                Vape Expo
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item"><a class="nav-link" href="#products">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="#branches">Branches</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact-info">Contact</a></li>
                    
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-bs-toggle="dropdown">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                @if (Auth::user()->role == 'super_admin')
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
                        <!-- ADDED: Register Button before Login -->
                        <li class="nav-item ms-lg-2">
                            <a class="nav-link btn-nav-register" href="{{ route('register') }}">Register</a>
                        </li>
                        <li class="nav-item ms-lg-2">
                            <a class="nav-link btn-nav-login" href="{{ route('login') }}">Login</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section (Background Image) -->
    <section id="home">
        <div class="container hero-container">
            <div class="hero-text">
                <div class="hero-badge">Now open across Calamba, Laguna</div>
                <h1 class="hero-title">Your Premium <span>Vape</span><br>Destination</h1>
                <p class="hero-subtitle">Discover top-tier vape products from trusted brands. Walk in, explore, and find your perfect match at Vape Expo.</p>
                <div class="hero-buttons">
                    <a href="#branches" class="btn btn-hero-primary">Find a Branch Near You</a>
                    <a href="#products" class="btn btn-hero-secondary">Browse Products</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Story Section -->
    <section id="story">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="story-text-group text-center">
                        <div class="badge-label">Our Story</div>
                        <h2>Built from Passion, Grown by Community</h2>
                        <p>What started as one shop with a big dream has become the go-to vape destination across Calamba. Here's how it happened.</p>
                    </div>

                    <div class="founder-profile-card">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                            <div>
                                <div class="founder-title">Founder & Owner</div>
                                <div class="founder-role">Carlo Caranto</div>
                            </div>
                            <div class="d-flex gap-3 founder-meta">
                                <span><i class="bi bi-geo-alt-fill text-primary"></i> Calamba, Laguna</span>
                                <span><i class="bi bi-shop text-primary"></i> 5 Branches</span>
                                <span><i class="bi bi-calendar3 text-primary"></i> Founded May 30, 2024</span>
                            </div>
                        </div>
                        <div class="founder-desc">
                            "I started Vape Expo to create a place where vapers could find quality products without the hassle — a shop that feels welcoming and reliable. Every branch is built with that same mission."
                        </div>
                    </div>

                    <div class="timeline-group">
                        <div class="timeline-row">
                            <div class="timeline-date-box">MAY 2024</div>
                            <div class="timeline-content-box">
                                <h6>The Beginning</h6>
                                <p>We opened our very first Vape Expo Canlubang branch, driven by a passion for quality vape products and a desire to create a welcoming community.</p>
                            </div>
                        </div>
                        <div class="timeline-row">
                            <div class="timeline-date-box">APRIL 2025</div>
                            <div class="timeline-content-box">
                                <h6>Growing Roots</h6>
                                <p>Due to growing demand, we expanded to open our second branch in Calamba — Asia 1, MCDC, and Majada Out — each staffed with people who share the same passion for service.</p>
                            </div>
                        </div>
                        <div class="timeline-row">
                            <div class="timeline-date-box">NOV 2025</div>
                            <div class="timeline-content-box">
                                <h6>Reaching More</h6>
                                <p>Paciano and Paciano V2 joined the family, extending Vape Expo's reach across Calamba City and beyond — bringing the brand to more vapers.</p>
                            </div>
                        </div>
                        <div class="timeline-row">
                            <div class="timeline-date-box">2026</div>
                            <div class="timeline-content-box">
                                <h6>Going Strong</h6>
                                <p>Every branch is open from 9 AM to 10 PM, seven days a week — because we believe great service should be available whenever you need it.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section id="stats">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-number">5</div>
                        <div class="stat-label">Branches Open</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-number">1</div>
                        <div class="stat-label">Dedicated Owner</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-number">5</div>
                        <div class="stat-label">Staff Members</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-number">13 hrs</div>
                        <div class="stat-label">Open Every Day</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section id="products">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Our Products</h2>
                <p class="text-muted">From premium disposables to sleek pod systems, we've got what you're looking for.</p>
            </div>
            <div class="row g-4">
                @php
                    $products = [
                        ['name' => 'ULTRA', 'desc' => 'High-performance disposable vape', 'img' => 'x-ultra.jpg'],
                        ['name' => 'Slimbar', 'desc' => 'Sleek and portable pod system', 'img' => 'slimbar.jpg'],
                        ['name' => 'Relx', 'desc' => 'Popular pod system with various flavors', 'img' => 'relx.jpg'],
                        ['name' => 'Many More', 'desc' => 'Explore our wide selection of brands in-store', 'img' => 'DISPOSABLEVAPES.jpg'],
                    ];
                @endphp
                @foreach ($products as $product)
                    <div class="col-6 col-md-3">
                        <div class="product-card">
                            <div class="product-img-wrapper">
                                <img src="{{ asset('images/products/' . $product['img']) }}" 
                                     alt="{{ $product['name'] }}"
                                     style="width: 100%; height: 100%; object-fit: contain;"
                                     onerror="this.style.display='none'; this.parentElement.innerHTML = `
                                        <div style='width:100%; height:100%; display:flex; align-items:center; justify-content:center; background-color: #f8fafc; border-radius: 8px;'>
                                            <span style='color: #94a3b8; font-weight: 600; font-size: 1.1rem;'>{{ $product['name'] }}</span>
                                        </div>
                                     `;">
                            </div>
                            <h5>{{ $product['name'] }}</h5>
                            <p>{{ $product['desc'] }}</p>
                            <a href="{{ route('login') }}" class="product-btn">Shop Now</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Delivery Section -->
    <section id="delivery">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Delivery Options</h2>
                <p class="text-muted">We make sure your orders arrive safely and quickly.</p>
            </div>

            <div class="row g-4">
                <!-- Calamba Delivery -->
                <div class="col-lg-6">
                    <div class="delivery-card">
                        <div class="delivery-header">
                            <div class="delivery-icon-box">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div>
                                <h4>Calamba City</h4>
                                <span class="delivery-pill">Free Delivery</span>
                            </div>
                        </div>
                        <div class="delivery-content">
                            <p>We deliver within Calamba City only.</p>
                            <div class="delivery-highlight-box">
                                <strong>Free Delivery</strong><br>
                                <span class="text-muted">Within Calamba City</span>
                            </div>
                            <div class="delivery-hours">
                                <span class="fw-semibold"><i class="bi bi-clock text-primary me-1"></i> Delivery Hours:</span>
                                <p class="text-muted mt-1">9:00 AM – 8:00 PM Daily</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Outside Calamba -->
                <div class="col-lg-6">
                    <div class="delivery-card">
                        <div class="delivery-header">
                            <div class="delivery-icon-box">
                                <i class="bi bi-truck"></i>
                            </div>
                            <div>
                                <h4>Outside Calamba</h4>
                                <span class="delivery-pill">Lalamove Delivery</span>
                            </div>
                        </div>
                        <div class="delivery-content">
                            <p>For deliveries outside Calamba City.</p>
                            <div class="delivery-highlight-box">
                                <strong>Lalamove Delivery</strong><br>
                                <span class="text-muted">Fees based on Lalamove rate</span>
                            </div>
                            <div class="delivery-hours">
                                <span class="fw-semibold"><i class="bi bi-info-circle text-primary me-1"></i> How it works:</span>
                                <ul class="text-muted mt-1 ps-3" style="font-size: 0.9rem;">
                                    <li>We book Lalamove for your delivery</li>
                                    <li>Fee calculated via Lalamove's rate</li>
                                    <li>We confirm exact fee before dispatch</li>
                                    <li>Est. delivery: 1-3 hours</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Methods -->
                <div class="col-12 mt-2">
                    <div class="payment-wrapper">
                        <h5 class="mb-3"><i class="bi bi-credit-card text-primary me-2"></i>Payment Methods</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="payment-item d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-3 p-2 me-3">
                                        <i class="bi bi-cash text-primary fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">Cash on Delivery (COD)</h6>
                                        <small class="text-muted">Pay upon delivery</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="payment-item d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-3 p-2 me-3">
                                        <i class="bi bi-phone text-primary fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">GCash</h6>
                                        <small class="text-muted">Pay via GCash upon delivery</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Branches Section (Carousel + Grid) -->
    <section id="branches">
        <div class="container">
            <div class="text-center mb-5">
                <h6 class="text-primary fw-bold text-uppercase small">Where to Find Us</h6>
                <h2 class="fw-bold">Our Branches</h2>
                <p class="text-muted">5 Branches across Calamba and surrounding areas —<br>each one ready to serve you with the same care and quality.</p>
            </div>

            @php
                $branches = [
                    [
                        'name' => 'VAPE EXPO - Majada Out Branch',
                        'address' => 'EFG Building, Majada Out Road, Calamba City',
                        'landmark' => 'Near 7-Eleven Majada Out and Gran Avila',
                        'manager' => 'Rocky Ace',
                        'img' => 'majada.jpg',
                    ],
                    [
                        'name' => 'VAPE EXPO - Asia 1 Branch',
                        'address' => 'Blk 67 Lot 1 Asia 1 Rd., Canlubang, Calamba',
                        'landmark' => 'Near Hernandez Grocery and Grimaldo',
                        'manager' => 'Karl Viscaino',
                        'img' => 'asia1.jpg',
                    ],
                    [
                        'name' => 'VAPE EXPO - MCDC Branch',
                        'address' => 'Blk 1 Lot 10 Kapayapaan, Canlubang, Calamba City',
                        'landmark' => 'Near Geosnack and Mango Royale MCDC',
                        'manager' => 'Mhark Apoliga',
                        'img' => 'mcdc.jpg',
                    ],
                    [
                        'name' => 'VAPE EXPO - Paciano Branch',
                        'address' => '215 National Road, Brgy. Paciano Rizal, Calamba City',
                        'landmark' => 'In front of Paciano Barangay Hall and 7‑Eleven Paciano',
                        'manager' => 'Jeremy Abustan',
                        'img' => 'paciano.jpg',
                    ],
                    [
                        'name' => 'VAPE EXPO - Paciano V2 Branch',
                        'address' => '39 Mayapa‑Canlubang Cadre Road, Calamba',
                        'landmark' => 'Near the area',
                        'manager' => 'Rhe Ann Alqueza',
                        'img' => 'paciano2.jpg',
                    ],
                ];
            @endphp

            <!-- Carousel -->
            <div id="branchCarousel" class="carousel slide shadow-sm" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    @foreach ($branches as $index => $branch)
                        <button type="button" data-bs-target="#branchCarousel" data-bs-slide-to="{{ $index }}"
                            class="{{ $index == 0 ? 'active' : '' }}"
                            aria-current="{{ $index == 0 ? 'true' : 'false' }}"
                            aria-label="Slide {{ $index + 1 }}"></button>
                    @endforeach
                </div>

                <div class="carousel-inner">
                    @foreach ($branches as $index => $branch)
                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                            <div class="position-relative">
                                <div style="max-height: 500px; overflow: hidden; display: flex; justify-content: center; align-items: center; background: #f8fafc;">
                                    <img src="{{ asset('images/branches/' . $branch['img']) }}" class="d-block w-100"
                                        alt="{{ $branch['name'] }}"
                                        style="width: 100%; height: auto; max-height: 500px; object-fit: contain;"
                                        onerror="this.src='https://placehold.co/1200x500/f8fafc/64748b?text=' + encodeURIComponent('{{ $branch['name'] }}'); this.style.objectFit='cover';">
                                </div>
                                <div class="position-absolute bottom-0 start-0 end-0 p-4"
                                    style="background: linear-gradient(to top, rgba(15,23,42,0.9) 0%, rgba(15,23,42,0.4) 70%, transparent 100%);">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-12">
                                                <h3 class="text-white mb-2 fw-bold">{{ $branch['name'] }}</h3>
                                                <p class="text-white-75 mb-1">
                                                    <i class="bi bi-geo-alt-fill text-primary me-2"></i>
                                                    {{ $branch['address'] }}
                                                </p>
                                                <p class="text-white-75 mb-2">
                                                    <i class="bi bi-pin-map-fill text-info me-2"></i>
                                                    {{ $branch['landmark'] }}
                                                </p>
                                                <p class="text-white mb-0">
                                                    <i class="bi bi-person-badge-fill text-success me-2"></i>
                                                    <span class="fw-semibold">Manager:</span> {{ $branch['manager'] }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#branchCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#branchCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>

            <!-- Grid Cards -->
            <div class="row g-4 mt-2">
                @php
                    $branch_grid_data = [
                        ['name' => 'Asia 1 Branch', 'address' => 'Blk 67 Lot 1, Canlubang, Calamba, Laguna', 'manager' => 'Karl Viscaino', 'since' => '2024'],
                        ['name' => 'Majada Out Branch', 'address' => 'EFG Building, Majada Out Road', 'manager' => 'Rocky Ace', 'since' => '2025'],
                        ['name' => 'MCDC Branch', 'address' => 'Blk 1 Lot 10, Canlubang, Calamba, Laguna', 'manager' => 'Mhark Apoliga', 'since' => '2025'],
                        ['name' => 'Paciano Branch', 'address' => '215 National Road, Brgy. Paciano Rizal, Calamba City', 'manager' => 'Jeremy Abustan', 'since' => '2025'],
                        ['name' => 'Paciano V2 Branch', 'address' => '39 Mayapa, Canlubang Cadre Road, Calamba', 'manager' => 'Rhe Ann Alqueza', 'since' => '2025'],
                    ];
                @endphp

                @foreach ($branch_grid_data as $grid_branch)
                    <div class="col-md-4">
                        <div class="branch-card-grid">
                            <div class="title-wrap">
                                <h5>{{ $grid_branch['name'] }}</h5>
                                <span class="branch-badge">Since {{ $grid_branch['since'] }}</span>
                            </div>
                            <div class="branch-info">
                                <i class="bi bi-geo-alt"></i>
                                {{ $grid_branch['address'] }}
                            </div>
                            <div class="branch-manager">
                                <i class="bi bi-person"></i>
                                <span>{{ $grid_branch['manager'] }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Ready to Explore Banner -->
    <div class="container">
        <div class="cta-banner">
            <h3>Ready to explore?</h3>
            <p>Visit any of our 5 branches today and discover your next favorite vape.</p>
            
            @guest
                <a href="{{ route('login') }}" class="btn">Find Your Nearest Branch</a>
            @else
                <a href="#branches" class="btn">Find Your Nearest Branch</a>
            @endguest
        </div>
    </div>

    <!-- Contact & Info Footer -->
    <section id="contact-info">
        <div class="container">
            <div class="text-center mb-5">
                <h6 class="text-primary fw-bold text-uppercase small">Get in Touch</h6>
                <h2 class="fw-bold">Contact Us</h2>
                <p class="text-muted">Have questions? We're open 9 AM to 10 PM every day across all our branches.</p>
            </div>

            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="contact-card">
                        <i class="bi bi-telephone"></i>
                        <h6>Call Us</h6>
                        <p>0960 328 0432</p>
                        <small class="text-muted">All Branches</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-card">
                        <i class="bi bi-clock"></i>
                        <h6>Store Hours</h6>
                        <p>9:00 AM – 10:00 PM</p>
                        <small class="text-muted">Daily, All Branches</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-card">
                        <i class="bi bi-person"></i>
                        <h6>Owner</h6>
                        <p>Carlo Caranto</p>
                        <small class="text-muted">Vape Expo</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5>VapeExpo</h5>
                    <p class="small">Your trusted vape shop since 2024.<br>Owner: Carlo Caranto</p>
                    <p><i class="bi bi-telephone me-2"></i>0960 328 0432<br>
                        <i class="bi bi-envelope me-2"></i>info@vapeexpo.com</p>
                </div>
                
                <div class="col-md-4 mb-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="#home">Home</a></li>
                        <li class="mb-2"><a href="#branches">Branches</a></li>
                        <li class="mb-2"><a href="#products">Products</a></li>
                        <li class="mb-2"><a href="#contact-info">Contact</a></li>
                    </ul>
                </div>

                <div class="col-md-4 mb-4 text-md-end">
                    <h5>Store Hours</h5>
                    <p class="small mb-0">Monday – Sunday<br>9:00 AM – 10:00 PM</p>
                </div>
            </div>
            <div class="footer-bottom">
                <small>&copy; {{ date('Y') }} Vape Expo. All rights reserved.</small>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>