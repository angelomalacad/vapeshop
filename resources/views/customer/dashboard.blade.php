@extends('layouts.customer')

@section('content')
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: #f5f7fb;
            min-height: 100vh;
        }

        /* ===== ANIMATIONS ===== */

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

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .animate-fadeInUp {
            animation: fadeInUp 0.6s ease forwards;
        }

        .animate-fadeInLeft {
            animation: fadeInLeft 0.6s ease forwards;
        }

        .animate-fadeInRight {
            animation: fadeInRight 0.6s ease forwards;
        }

        .delay-1 {
            animation-delay: 0.1s;
            opacity: 0;
        }

        .delay-2 {
            animation-delay: 0.2s;
            opacity: 0;
        }

        .delay-3 {
            animation-delay: 0.3s;
            opacity: 0;
        }

        .delay-4 {
            animation-delay: 0.4s;
            opacity: 0;
        }

        .float-icon {
            animation: float 3s ease-in-out infinite;
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

        /* Action Cards - Minimalist & Proper Alignment */
        .action-card {
            border: none;
            border-radius: 16px;
            transition: all 0.3s ease;
            background: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border: 1px solid #eef2f6;
            height: 100%;
            opacity: 0;
            animation: fadeInUp 0.5s ease forwards;
        }

        .action-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .action-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .action-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        .action-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            border-color: #e2e8f0;
        }

        .action-card .card-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            margin-bottom: 1rem;
            background: #f8f9fa;
            color: #4a5568;
            transition: all 0.3s ease;
        }

        .action-card:hover .card-icon {
            background: #e74c3c;
            color: white;
        }

        .action-card .btn {
            border-radius: 30px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            background: white;
            border: 1px solid #e2e8f0;
            color: #4a5568;
            width: 100%;
        }

        .action-card .btn:hover {
            background: #1a1a2e;
            border-color: #1a1a2e;
            color: white;
        }

        /* Modern Cards */
        .modern-card {
            border: none;
            border-radius: 20px;
            background: white;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
            animation: fadeInUp 0.5s ease;
            transition: all 0.3s ease;
        }

        .modern-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
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

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            border: none;
            border-radius: 20px;
            background: white;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        /* Table Styles - Fitted to Box */
        .order-table {
            margin-bottom: 0;
            width: 100%;
        }

        .order-table th {
            background: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
            padding: 0.75rem;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #4a5568;
        }

        .order-table td {
            padding: 0.75rem;
            vertical-align: middle;
            border-bottom: 1px solid #e9ecef;
            font-size: 0.8rem;
        }

        .order-table tbody tr {
            transition: all 0.2s;
        }

        .order-table tbody tr:hover {
            background: #f8f9fa;
        }

        .order-table code {
            font-size: 0.75rem;
            word-break: break-word;
        }

        /* Table Responsive Container */
        .table-responsive {
            overflow-x: auto;
        }

        /* Branch Cards */
        .branch-card {
            border: 1px solid #eef2f6;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: white;
            margin-bottom: 0.75rem;
            cursor: pointer;
        }

        .branch-card:hover {
            border-color: #e74c3c;
            transform: translateX(8px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .branch-card .badge {
            background: #fef3e2;
            color: #e67e22;
            font-size: 0.65rem;
            font-weight: 500;
            padding: 2px 8px;
            border-radius: 20px;
        }

        /* Badge Styles */
        .badge-modern {
            padding: 0.35rem 0.65rem;
            border-radius: 30px;
            font-weight: 500;
            font-size: 0.65rem;
            white-space: nowrap;
        }

        /* Map Container */
        .map-container {
            height: 280px;
            width: 100%;
            background: #f8f9fa;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .map-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        /* Branch List Container - No Horizontal Scroll */
        .branch-list-container {
            max-height: 250px;
            overflow-y: auto;
            overflow-x: hidden;
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

        /* Footer */
        .footer-custom {
            border-top: 1px solid #eef2f6;
            padding-top: 1rem;
            margin-top: 2rem;
            color: #6c757d;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .action-card {
                margin-bottom: 1rem;
            }

            .welcome-banner {
                padding: 1rem;
            }

            .order-table th,
            .order-table td {
                padding: 0.5rem;
            }

            .badge-modern {
                font-size: 0.6rem;
                padding: 0.25rem 0.5rem;
            }
        }
    </style>

    <div class="container">
        <!-- Welcome Banner -->
        <div class="welcome-banner">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="text-white mb-2 fw-bold">
                        <i class="bi bi-stars me-2 text-warning float-icon"></i>Welcome back, {{ Auth::user()->name }}!
                    </h4>
                    <p class="text-white-50 mb-0">Discover the best vaping experience at Vape Expo. Quality products, great
                        prices, and friendly service.</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="d-inline-block bg-white bg-opacity-10 rounded-3 px-4 py-2">
                        <i class="bi bi-calendar3 text-white me-2"></i>
                        <span class="text-white">{{ now()->format('l, F j, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1 small">Total Orders</h6>
                            <h3 class="mb-0 fw-bold text-primary">
                                {{ ($orderCounts['pending'] ?? 0) + ($orderCounts['processing'] ?? 0) + ($orderCounts['out_for_delivery'] ?? 0) + ($orderCounts['delivered'] ?? 0) }}
                            </h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-2 rounded-circle">
                            <i class="bi bi-receipt fs-4 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1 small">Cart Items</h6>
                            <h3 class="mb-0 fw-bold text-success">{{ $cartCount ?? 0 }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-2 rounded-circle">
                            <i class="bi bi-cart fs-4 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1 small">In Transit</h6>
                            <h3 class="mb-0 fw-bold text-warning">{{ $orderCounts['out_for_delivery'] ?? 0 }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-2 rounded-circle">
                            <i class="bi bi-truck fs-4 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1 small">Delivered</h6>
                            <h3 class="mb-0 fw-bold text-info">{{ $orderCounts['delivered'] ?? 0 }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 p-2 rounded-circle">
                            <i class="bi bi-check-circle-fill fs-4 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Action Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card action-card">
                    <div class="card-body">
                        <div class="card-icon">
                            <i class="bi bi-shop fs-4"></i>
                        </div>
                        <h5 class="card-title fw-semibold fs-6 mb-2">Browse Products</h5>
                        <p class="card-text text-muted small mb-3">View all available vape products from our collection.</p>
                        <a href="{{ route('customer.products.index') }}" class="btn rounded-pill">
                            Shop Now <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card action-card">
                    <div class="card-body">
                        <div class="card-icon">
                            <i class="bi bi-cart fs-4"></i>
                        </div>
                        <h5 class="card-title fw-semibold fs-6 mb-2">My Cart</h5>
                        <p class="card-text text-muted small mb-3">View and manage your shopping cart items.</p>
                        <a href="{{ route('customer.cart.index') }}" class="btn rounded-pill">
                            View Cart <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card action-card">
                    <div class="card-body">
                        <div class="card-icon">
                            <i class="bi bi-truck fs-4"></i>
                        </div>
                        <h5 class="card-title fw-semibold fs-6 mb-2">Track Orders</h5>
                        <p class="card-text text-muted small mb-3">Monitor your order status and delivery progress.</p>
                        <a href="{{ route('customer.orders.index') }}" class="btn rounded-pill">
                            Track Now <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Recent Orders Section -->
            <div class="col-lg-7">
                <div class="card modern-card">
                    <div class="card-header-modern d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-clock-history me-2"></i> Recent Orders
                        </div>
                        <a href="{{ route('customer.orders.index') }}" class="btn btn-sm btn-link text-decoration-none p-0"
                            style="color: #e74c3c;">
                            View All <i class="bi bi-arrow-right ms-1 small"></i>
                        </a>
                    </div>
                    <div class="card-body p-0">
                        @if (isset($recentOrders) && $recentOrders->count() > 0)
                            <div class="table-responsive">
                                <table class="table order-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Order #</th>
                                            <th>Date</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recentOrders as $order)
                                            <tr>
                                                <td>
                                                    <code class="fw-bold">{{ $order->order_number }}</code>
                                                    @if ($order->delivery)
                                                        <br><small class="text-muted"><i class="bi bi-upc-scan"></i>
                                                            {{ $order->delivery->tracking_number ?? 'N/A' }}</small>
                                                    @endif
                                                </td>
                                                <td class="text-nowrap">
                                                    {{ $order->created_at->format('M d, Y') }}<br>
                                                    <small
                                                        class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                                                </td>
                                                <td class="text-nowrap">
                                                    <strong
                                                        class="text-success">₱{{ number_format($order->total_amount, 2) }}</strong>
                                                </td>
                                                <td>
                                                    @php
                                                        $statusBadge = [
                                                            'pending' => [
                                                                'bg' => 'warning',
                                                                'icon' => 'bi-clock-history',
                                                            ],
                                                            'confirmed' => [
                                                                'bg' => 'info',
                                                                'icon' => 'bi-check-circle',
                                                            ],
                                                            'processing' => ['bg' => 'primary', 'icon' => 'bi-gear'],
                                                            'ready' => ['bg' => 'success', 'icon' => 'bi-box-seam'],
                                                            'out_for_delivery' => [
                                                                'bg' => 'secondary',
                                                                'icon' => 'bi-truck',
                                                            ],
                                                            'delivered' => [
                                                                'bg' => 'dark',
                                                                'icon' => 'bi-check-circle-fill',
                                                            ],
                                                            'cancelled' => ['bg' => 'danger', 'icon' => 'bi-x-circle'],
                                                        ];
                                                        $badge = $statusBadge[$order->order_status] ?? [
                                                            'bg' => 'secondary',
                                                            'icon' => 'bi-info-circle',
                                                        ];
                                                    @endphp
                                                    <span class="badge bg-{{ $badge['bg'] }} badge-modern">
                                                        <i class="bi {{ $badge['icon'] }} me-1"></i>
                                                        {{ ucfirst($order->order_status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('customer.orders.show', $order) }}"
                                                        class="btn btn-sm btn-outline-primary rounded-pill">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-inbox fs-1 d-block mb-3 float-icon" style="color: #cbd5e0;"></i>
                                <p class="mb-3">No orders yet. Start shopping now!</p>
                                <a href="{{ route('customer.products.index') }}"
                                    class="btn btn-primary rounded-pill px-4">
                                    <i class="bi bi-shop me-2"></i>Start Shopping
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Branch Information with Embedded Map & Directions -->
            <div class="col-lg-5">
                <div class="card modern-card">
                    <div class="card-header-modern">
                        <i class="bi bi-geo-alt me-2"></i> Our Branches
                    </div>
                    <div class="card-body p-3">
                        <div class="map-container">
                            <iframe id="branchMap"
                                src="https://maps.google.com/maps?q=Calamba%20City%20Laguna&t=&z=12&ie=UTF8&iwloc=&output=embed"
                                allowfullscreen></iframe>
                        </div>

                        <div class="branch-list-container">
                            @foreach ($branches as $branch)
                                @php
                                    $landmarks = [
                                        'Majada Out Branch' => 'Near 7-Eleven and Gran Avila',
                                        'Asia 1 Branch' => 'Near Hernandez Grocery and Grimaldo',
                                        'MCDC Branch' => 'Near Geosnack and Mango Royale',
                                        'Paciano Branch' => 'In front of Barangay Hall and 7-Eleven',
                                        'Paciano V2 Branch' => 'Near the area',
                                    ];
                                    $landmark = $landmarks[$branch->name] ?? '';
                                @endphp
                                <div class="branch-card p-2 branch-location" data-address="{{ $branch->address }}"
                                    data-name="{{ $branch->name }}">
                                    <div class="d-flex align-items-start justify-content-between">
                                        <div class="d-flex align-items-start flex-grow-1">
                                            <i class="bi bi-building fs-5 me-2 text-primary float-icon"></i>
                                            <div class="flex-grow-1">
                                                <strong class="d-block">{{ $branch->name }}</strong>
                                                <small
                                                    class="text-muted d-block">{{ Str::limit($branch->address, 50) }}</small>
                                                @if ($landmark)
                                                    <span class="badge mt-1">{{ $landmark }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="ms-2">
                                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($branch->address) }}"
                                                target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill"
                                                style="border-color: #e2e8f0; font-size: 0.7rem; padding: 0.2rem 0.5rem; white-space: nowrap;">
                                                <i class="bi bi-compass"></i> Directions
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
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

        <!-- Footer -->
        <div class="footer-custom mt-4 pt-3">
            <div class="row">
                <div class="col-md-6 text-md-start">
                    <small><i class="bi bi-shield-check me-1"></i> Vape Expo - Quality Vape Products Since 2024</small>
                </div>
                <div class="col-md-6 text-md-end">
                    <small><i class="bi bi-telephone me-1"></i> Need help? Contact owner: <strong>Carlo Caranto - 0960 328
                            0432</strong></small>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.branch-location').forEach(item => {
            item.addEventListener('click', function() {
                const address = encodeURIComponent(this.dataset.address);
                const mapIframe = document.getElementById('branchMap');
                mapIframe.src =
                    `https://maps.google.com/maps?q=${address}&t=&z=15&ie=UTF8&iwloc=&output=embed`;
                document.querySelectorAll('.branch-location').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
@endsection