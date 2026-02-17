<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vape Expo - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #0B0C10;
            color: #C5C6C7;
        }
        .navbar, .footer {
            background-color: #1F2833 !important;
        }
        .navbar-brand, .nav-link {
            color: #FFFFFF !important;
        }
        .navbar-brand:hover, .nav-link:hover {
            color: #66FCF1 !important;
        }
        .hero {
            background: linear-gradient(135deg, #0B0C10 0%, #1F2833 100%);
            color: white;
            padding: 80px 0;
            margin-bottom: 50px;
            border-bottom: 3px solid #66FCF1;
        }
        .btn-primary {
            background-color: #66FCF1;
            border-color: #66FCF1;
            color: #0B0C10;
            font-weight: 600;
        }
        .btn-primary:hover {
            background-color: #45a29e;
            border-color: #45a29e;
            color: #FFFFFF;
        }
        .btn-outline-light {
            border-color: #66FCF1;
            color: #66FCF1;
        }
        .btn-outline-light:hover {
            background-color: #66FCF1;
            color: #0B0C10;
        }
        .card {
            background-color: #1F2833;
            border: none;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(102,252,241,0.2);
        }
        .card-title {
            color: #FFFFFF;
        }
        .card-text {
            color: #C5C6C7;
        }
        .text-primary {
            color: #66FCF1 !important;
        }
        .branch-img {
            height: 180px;
            object-fit: cover;
            border-radius: 8px 8px 0 0;
            border-bottom: 2px solid #66FCF1;
        }
        .product-img {
            height: 200px;
            object-fit: contain;
            background-color: #0B0C10;
            padding: 1rem;
        }
        .staff-list {
            background-color: #0B0C10;
            padding: 40px 0;
        }
        .footer {
            background-color: #1F2833;
            color: #C5C6C7;
            border-top: 3px solid #66FCF1;
        }
        .footer a {
            color: #66FCF1;
        }
        .footer a:hover {
            color: #FFFFFF;
        }
        .contact-info i {
            color: #66FCF1;
            margin-right: 8px;
        }
        .owner-name {
            color: #66FCF1;
            font-weight: 600;
        }

        /* Carousel improvements */
        .carousel-item {
            transition: transform 0.8s ease-in-out;
        }
        .carousel-item .position-relative {
            min-height: 400px;
        }
        .text-white-75 {
            color: rgba(255, 255, 255, 0.75);
        }
        .carousel-indicators button {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin: 0 6px;
            background-color: #66FCF1;
        }
        .carousel-indicators button.active {
            background-color: #FFFFFF;
            transform: scale(1.2);
        }
        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: #1F2833 !important;
            border-radius: 50%;
            padding: 1.5rem;
            opacity: 0.8;
            background-size: 60%;
        }
        .carousel-control-prev-icon:hover,
        .carousel-control-next-icon:hover {
            opacity: 1;
            background-color: #66FCF1 !important;
        }

        /* Product card enhancements */
        #products .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 12px;
            overflow: hidden;
            background-color: #FFFFFF;
        }

        #products .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(102,252,241,0.25) !important;
        }

        #products .card-img-wrapper {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 2px solid #66FCF1;
        }

        #products .card-title {
            color: #1F2833;
            font-size: 1.2rem;
        }

        #products .card-text {
            color: #6c757d;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        #products .text-primary {
            color: #0B0C10 !important;
            background: linear-gradient(135deg, #66FCF1 0%, #45a29e 100%);
            display: inline-block;
            padding: 0.3rem 1rem;
            border-radius: 25px;
            font-size: 1.1rem;
            margin-top: 0.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            #products .col-md-4 {
                margin-bottom: 1rem;
            }
            
            #products .card-img-wrapper {
                height: 160px !important;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">
                <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="40" class="d-inline-block align-text-top me-2">
                Vape Expo
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#branches">Branches</a></li>
                    <li class="nav-item"><a class="nav-link" href="#products">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="#staff">Our Team</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
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
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-3">Vape Expo</h1>
                    <p class="lead mb-3">Your trusted vape shop in Calamba,Laguna</p>
                    <div class="contact-info mb-4">
                        <p><i class="bi bi-telephone me-2"></i>Contact: <a href="tel:+639603280432" class="text-white">0960 328 0432</a></p>
                        <p><i class="bi bi-clock me-2"></i>Business Hours: 9:00 AM – 10:00 PM (Daily)</p>
                    </div>
                    @auth
                        @if(Auth::user()->role == 'super_admin')
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-light btn-lg px-4 me-md-2">Go to Admin Dashboard</a>
                        @elseif(Auth::user()->role == 'branch_admin')
                            <a href="{{ route('branch-admin.dashboard') }}" class="btn btn-light btn-lg px-4 me-md-2">Go to Branch Dashboard</a>
                        @elseif(Auth::user()->role == 'customer')
                            <a href="{{ route('customer.dashboard') }}" class="btn btn-light btn-lg px-4 me-md-2">Go to My Dashboard</a>
                        @endif
                    @else
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg px-4 me-md-2">Get Started</a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4">Login</a>
                    @endauth
                </div>
                <div class="col-lg-6 text-center mt-4 mt-lg-0">
                    <img src="{{ asset('images/shop-front.jpg') }}" alt="Vape Expo Store" class="img-fluid rounded-3 shadow-lg" style="max-height: 350px;">
                </div>
            </div>
        </div>
    </section>

    @php
        $branches = [
            [
                'name'    => 'VAPE EXPO - Majada Out Branch',
                'address' => 'EFG Building, Majada Out Road, Calamba City',
                'landmark'=> 'Near 7-Eleven Majada Out and Gran Avila',
                'manager' => 'Rocky Ace',
                'img'     => 'majada.jpg'
            ],
            [
                'name'    => 'VAPE EXPO - Asia 1 Branch',
                'address' => 'Blk 67 Lot 1 Asia 1 Rd., Canlubang, Calamba',
                'landmark'=> 'Near Hernandez Grocery and Grimaldo',
                'manager' => 'Karl Viscaino',
                'img'     => 'asia1.jpg'
            ],
            [
                'name'    => 'VAPE EXPO - MCDC Branch',
                'address' => 'Blk 1 Lot 10 Kapayapaan, Canlubang, Calamba City',
                'landmark'=> 'Near Geosnack and Mango Royale MCDC',
                'manager' => 'Mhark Apoliga',
                'img'     => 'mcdc.jpg'
            ],
            [
                'name'    => 'VAPE EXPO - Paciano Branch',
                'address' => '215 National Road, Brgy. Paciano Rizal, Calamba City',
                'landmark'=> 'In front of Paciano Barangay Hall and 7‑Eleven Paciano',
                'manager' => 'Jeremy Abustan',
                'img'     => 'paciano.jpg'
            ],
            [
                'name'    => 'VAPE EXPO - Paciano V2 Branch',
                'address' => '39 Mayapa‑Canlubang Cadre Road, Calamba',
                'landmark'=> 'Near the area',
                'manager' => 'Rhe Ann Alqueza',
                'img'     => 'paciano2.jpg'
            ],
        ];
    @endphp

    <!-- Branches Carousel -->
    <section id="branches" class="container py-5">
        <h2 class="text-center mb-5">Our Branches</h2>
        <div id="branchCarousel" class="carousel slide carousel-dark shadow-lg rounded-4 overflow-hidden" data-bs-ride="carousel">
            <!-- Indicators -->
            <div class="carousel-indicators">
                @foreach($branches as $index => $branch)
                    <button type="button" data-bs-target="#branchCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}" aria-current="{{ $index == 0 ? 'true' : 'false' }}" aria-label="Slide {{ $index+1 }}"></button>
                @endforeach
            </div>

            <!-- Slides -->
            <div class="carousel-inner">
                @foreach($branches as $index => $branch)
                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                        <div class="position-relative" style="background-color: #0B0C10;">
                            <!-- Image Container with proper aspect ratio -->
                            <div style="max-height: 500px; overflow: hidden; display: flex; justify-content: center; align-items: center;">
                                <img src="{{ asset('images/branches/' . $branch['img']) }}"
                                     class="d-block w-100"
                                     alt="{{ $branch['name'] }}"
                                     style="width: 100%; height: auto; max-height: 500px; object-fit: contain; background-color: #0B0C10;"
                                     onerror="this.src='https://via.placeholder.com/1200x500?text=' + encodeURIComponent('{{ $branch['name'] }}'); this.style.objectFit='cover';">
                            </div>
                            
                            <!-- Branch Info Overlay -->
                            <div class="position-absolute bottom-0 start-0 end-0 p-4" style="background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.5) 70%, transparent 100%);">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-12">
                                            <h3 class="text-white mb-2 fw-bold">{{ $branch['name'] }}</h3>
                                            <p class="text-white-75 mb-1">
                                                <i class="bi bi-geo-alt-fill text-warning me-2"></i>
                                                <span class="text-white">{{ $branch['address'] }}</span>
                                            </p>
                                            <p class="text-white-75 mb-2">
                                                <i class="bi bi-pin-map-fill text-info me-2"></i>
                                                <span class="text-white-50">{{ $branch['landmark'] }}</span>
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

            <!-- Controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#branchCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon bg-dark rounded-circle p-3" style="background-size: 60%;" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#branchCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon bg-dark rounded-circle p-3" style="background-size: 60%;" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>

    <!-- Products Section (FIXED) -->
    <section id="products" class="bg-light py-5">
        <div class="container">
            <h2 class="text-center mb-5">Our Products</h2>
            <div class="row g-4 justify-content-center">
                @php
                    $products = [
                        ['name' => 'ULTRA', 'desc' => 'High-performance disposable vape', 'img' => 'x-ultra.jpg'],
                        ['name' => 'Slimbar', 'desc' => 'Sleek and portable pod system', 'img' => 'slimbar.jpg'],
                        ['name' => 'Relx', 'desc' => 'Popular pod system with various flavors', 'img' => 'relx.jpg'],
                    ];
                @endphp
                @foreach($products as $product)
                <div class="col-md-4 col-lg-3">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-img-wrapper p-3 d-flex align-items-center justify-content-center" style="height: 200px; background-color: #f8f9fa;">
                            <img src="{{ asset('images/products/' . $product['img']) }}" 
                                 class="img-fluid" 
                                 alt="{{ $product['name'] }}" 
                                 style="max-height: 160px; width: auto; object-fit: contain;"
                                 onerror="this.src='https://via.placeholder.com/200x160?text={{ $product['name'] }}'">
                        </div>
                        <div class="card-body text-center">
                            <h5 class="card-title fw-bold mb-2">{{ $product['name'] }}</h5>
                            <p class="card-text text-muted small mb-2">{{ $product['desc'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Staff Section -->
    <section id="staff" class="staff-list py-5">
        <div class="container">
            <h2 class="text-center mb-5">Meet Our Team</h2>
            <div class="row g-4">
                @php
                    $staff = [
                        ['name' => 'Karl Viscaino', 'role' => 'Asia 1 Staff', 'branch' => 'Asia 1 Branch'],
                        ['name' => 'Mhark Apoliga', 'role' => 'MCDC Staff', 'branch' => 'MCDC Branch'],
                        ['name' => 'Rocky Ace', 'role' => 'Majada Staff', 'branch' => 'Majada Out Branch'],
                        ['name' => 'Jeremy Abustan', 'role' => 'Paciano Staff', 'branch' => 'Paciano Branch'],
                        ['name' => 'Rhe Ann Alqueza', 'role' => 'Paciano V2 Staff', 'branch' => 'Paciano V2 Branch'],
                    ];
                @endphp
                @foreach($staff as $member)
                <div class="col-md-4 col-lg-3">
                    <div class="card text-center h-100 border-0 bg-transparent">
                        <div class="card-body">
                            <i class="bi bi-person-circle display-1 text-secondary"></i>
                            <h5 class="card-title mt-3">{{ $member['name'] }}</h5>
                            <p class="card-text text-muted">{{ $member['role'] }}</p>
                            <small class="text-primary"><i class="bi bi-shop"></i> {{ $member['branch'] }}</small>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Contact & Info Footer -->
    <footer id="contact" class="footer py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5><i class="bi bi-shop me-2"></i>Vape Expo</h5>
                    <p>Your trusted vape shop since 2024.<br>Owner: Carlo Caranto</p>
                    <p><i class="bi bi-telephone me-2"></i>0960 328 0432<br>
                    <i class="bi bi-envelope me-2"></i>info@vapeexpo.com</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Business Hours</h5>
                    <p>Monday – Sunday<br>9:00 AM – 10:00 PM</p>
                    <h5>Branches Opened</h5>
                    <p>Main Branch: May 20, 2024<br>Other branches: 2024–2025</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="#home" class="text-white-50 text-decoration-none">Home</a></li>
                        <li><a href="#branches" class="text-white-50 text-decoration-none">Branches</a></li>
                        <li><a href="#products" class="text-white-50 text-decoration-none">Products</a></li>
                        <li><a href="#staff" class="text-white-50 text-decoration-none">Our Team</a></li>
                    </ul>
                </div>
            </div>
            <hr class="bg-white">
            <div class="text-center">
                <small>&copy; {{ date('Y') }} Vape Expo. All rights reserved.</small>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>