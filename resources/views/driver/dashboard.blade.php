@extends('layouts.driver')

@section('content')
    <div class="container-fluid">
        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm welcome-banner">
                    <div class="card-body py-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="mb-0 fw-bold text-white"><i class="bi bi-person-circle me-2"></i> {{ Auth::user()->name }}
                                </h2>
                                <p class="mb-0 mt-1 text-white opacity-75">
                                    <i class="bi bi-truck me-1"></i> Driver Dashboard
                                </p>
                            </div>
                            <div>
                                @if (isset($todayShift) && $todayShift)
                                    <span class="badge bg-success px-3 py-2 fs-6"><i class="bi bi-check-circle-fill me-1"></i> On Duty Today</span>
                                @else
                                    <span class="badge bg-secondary px-3 py-2 fs-6"><i class="bi bi-clock me-1"></i> Off Duty</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Cards - Order Status Summary -->
        <div class="row g-4 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted text-uppercase small fw-semibold">Pending Orders</span>
                                <h2 class="mb-0 fw-bold mt-2">{{ $pendingOrdersCount ?? 0 }}</h2>
                                <small class="text-muted">Awaiting confirmation</small>
                            </div>
                            <div class="stat-icon-wrapper">
                                <i class="bi bi-hourglass-split fs-2 text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted text-uppercase small fw-semibold">Ready Orders</span>
                                <h2 class="mb-0 fw-bold mt-2">{{ $readyOrdersCount ?? 0 }}</h2>
                                <small class="text-muted">Ready for delivery</small>
                            </div>
                            <div class="stat-icon-wrapper">
                                <i class="bi bi-box-seam fs-2 text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted text-uppercase small fw-semibold">Out for Delivery</span>
                                <h2 class="mb-0 fw-bold mt-2">{{ $outForDeliveryCount ?? 0 }}</h2>
                                <small class="text-muted">On the way</small>
                            </div>
                            <div class="stat-icon-wrapper">
                                <i class="bi bi-truck fs-2 text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted text-uppercase small fw-semibold">My Deliveries</span>
                                <h2 class="mb-0 fw-bold mt-2">{{ $totalDeliveries ?? 0 }}</h2>
                                <small class="text-muted">Assigned to you</small>
                            </div>
                            <div class="stat-icon-wrapper">
                                <i class="bi bi-truck fs-2 text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm action-card">
                    <div class="card-body py-3 text-center">
                        <div class="action-icon-wrapper mb-2">
                            <i class="bi bi-cart display-5 text-primary"></i>
                        </div>
                        <h5 class="mb-1 fw-semibold">Online Orders</h5>
                        <p class="text-muted mb-3 small">View and process customer orders</p>
                        <a href="{{ route('driver.online-orders.index') }}" class="btn btn-outline-primary rounded-pill px-3 py-1 btn-sm">
                            Manage Orders <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm action-card">
                    <div class="card-body py-3 text-center">
                        <div class="action-icon-wrapper mb-2">
                            <i class="bi bi-truck display-5 text-success"></i>
                        </div>
                        <h5 class="mb-1 fw-semibold">My Delivery History</h5>
                        <p class="text-muted mb-3 small">Track delivered Items</p>
                        <!-- ✅ This link points to driver.delivery-history -->
                        <a href="{{ route('driver.delivery-history') }}" class="btn btn-outline-success rounded-pill px-3 py-1 btn-sm">
                            View History <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- ✅ NEW: Recent Activity with Tabs -->
        <div class="card border-0 shadow-sm modern-card">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="mb-0 fw-semibold">
                    <i class="bi bi-clock-history me-2 text-primary"></i> Recent Activity
                </h5>
            </div>
            <div class="card-body p-0">
                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs px-3 pt-3 border-0" id="recentTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders" type="button" role="tab" aria-controls="orders" aria-selected="true">
                            <i class="bi bi-cart me-1"></i> Recent Online Orders
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="deliveries-tab" data-bs-toggle="tab" data-bs-target="#deliveries" type="button" role="tab" aria-controls="deliveries" aria-selected="false">
                            <i class="bi bi-truck me-1"></i> Recent Deliveries
                        </button>
                    </li>
                </ul>

                <!-- Tabs Content -->
                <div class="tab-content" id="recentTabsContent">
                    <!-- Recent Online Orders Tab -->
                    <div class="tab-pane fade show active" id="orders" role="tabpanel" aria-labelledby="orders-tab">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Order #</th>
                                        <th>Date</th>
                                        <th>Customer</th>
                                        <th>Total</th>
                                        <th>Delivery Type</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentOnlineOrders ?? [] as $order)
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'confirmed' => 'info',
                                                'processing' => 'primary',
                                                'ready' => 'success',
                                                'out_for_delivery' => 'secondary',
                                                'delivered' => 'dark',
                                                'cancelled' => 'danger',
                                            ];
                                            $badgeColor = $statusColors[$order->order_status] ?? 'secondary';
                                        @endphp
                                        <tr>
                                            <td class="ps-4"><code>{{ $order->order_number }}</code></td>
                                            <td class="text-nowrap">{{ $order->created_at->format('M d, Y h:i A') }}</td>
                                            <td>
                                                <div class="fw-semibold">{{ $order->customer_name }}</div>
                                                <small class="text-muted">{{ $order->customer_phone }}</small>
                                            </td>
                                            <td><strong>₱{{ number_format($order->total_amount, 2) }}</strong></td>
                                            <td>
                                                <span class="badge bg-{{ $order->delivery_type == 'delivery' ? 'info' : 'success' }} bg-opacity-10 text-dark">
                                                    <i class="bi bi-{{ $order->delivery_type == 'delivery' ? 'truck' : 'building' }} me-1"></i>
                                                    {{ ucfirst($order->delivery_type) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $badgeColor }}">
                                                    {{ ucfirst($order->order_status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <i class="bi bi-inbox display-1 text-muted"></i>
                                                <h5 class="mt-3">No Recent Orders</h5>
                                                <p class="text-muted">No online orders have been placed recently.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if (isset($recentOnlineOrders) && $recentOnlineOrders->count() > 0)
                            <div class="bg-white text-center py-2 border-top">
                                <small class="text-muted">Showing last {{ $recentOnlineOrders->count() }} orders</small>
                            </div>
                        @endif
                    </div>

                    <!-- Recent Deliveries Tab -->
                    <div class="tab-pane fade" id="deliveries" role="tabpanel" aria-labelledby="deliveries-tab">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Tracking #</th>
                                        <th>Order #</th>
                                        <th>Customer</th>
                                        <th>Status</th>
                                        <th>Updated</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentDeliveries ?? [] as $delivery)
                                        <tr>
                                            <td class="ps-4"><code>{{ $delivery->tracking_number }}</code></td>
                                            <td class="text-nowrap">{{ $delivery->order->order_number ?? 'N/A' }}</td>
                                            <td>{{ $delivery->recipient_name }}</td>
                                            <td>
                                                <span class="badge bg-{{ $delivery->status == 'delivered' ? 'success' : ($delivery->status == 'in_transit' ? 'warning' : 'info') }}">
                                                    {{ ucfirst($delivery->status) }}
                                                </span>
                                            </td>
                                            <td class="text-nowrap">{{ $delivery->updated_at->diffForHumans() }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                                <p class="text-muted mb-0">No recent deliveries</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if (isset($recentDeliveries) && $recentDeliveries->count() > 0)
                            <div class="bg-white text-center py-2 border-top">
                                <small class="text-muted">Showing last {{ $recentDeliveries->count() }} deliveries</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Welcome Banner - Dark Navy */
        .welcome-banner {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            border-radius: 16px;
            position: relative;
            overflow: hidden;
        }
        
        .welcome-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
            border-radius: 50%;
        }
        
        /* Stat Cards - Minimalist */
        .stat-card {
            border: none;
            border-radius: 16px;
            transition: all 0.3s ease;
            background: white;
        }
        
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }
        
        .stat-card h2 {
            color: #1a1a2e;
            font-size: 2rem;
        }
        
        .stat-icon-wrapper {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            background: #f8f9fa;
        }
        
        /* Action Cards - Minimalist */
        .action-card {
            border: none;
            border-radius: 16px;
            transition: all 0.3s ease;
            background: white;
        }
        
        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.08);
        }
        
        .action-icon-wrapper {
            transition: all 0.3s ease;
        }
        
        .action-card:hover .action-icon-wrapper {
            transform: scale(1.05);
        }
        
        /* Modern Cards */
        .modern-card {
            border: none;
            border-radius: 16px;
            transition: all 0.3s ease;
            background: white;
        }
        
        .modern-card:hover {
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }
        
        /* Table Styles */
        .table {
            margin-bottom: 0;
        }
        
        .table th {
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            padding: 1rem;
            border-bottom: 1px solid #eef2f6;
        }
        
        .table td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #eef2f6;
            color: #334155;
            font-size: 0.875rem;
        }
        
        .table tbody tr {
            transition: all 0.2s;
        }
        
        .table tbody tr:hover {
            background: #f8f9fa;
        }
        
        /* Badge Styles */
        .badge {
            padding: 0.35rem 0.65rem;
            border-radius: 30px;
            font-weight: 500;
            font-size: 0.7rem;
        }
        
        /* Buttons */
        .btn-outline-primary {
            border-color: #e2e8f0;
            color: #1a1a2e;
        }
        
        .btn-outline-primary:hover {
            background: #1a1a2e;
            border-color: #1a1a2e;
            color: white;
        }
        
        .btn-outline-success {
            border-color: #e2e8f0;
            color: #1a1a2e;
        }
        
        .btn-outline-success:hover {
            background: #27ae60;
            border-color: #27ae60;
            color: white;
        }
        
        /* Hover Lift */
        .hover-lift {
            transition: transform 0.2s;
        }
        
        .hover-lift:hover {
            transform: translateY(-5px);
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
            background: #1a1a2e;
            border-radius: 10px;
        }
    </style>

    <script>
        // Function to open order modal
        function openOrderModal(orderId) {
            const modal = document.getElementById('customModal');
            const modalContent = document.getElementById('customModalContent');

            modalContent.innerHTML = `
                <div style="padding: 40px; text-align: center;">
                    <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Loading order details...</p>
                </div>
            `;
            modal.style.display = 'flex';

            fetch(`/driver/online-orders/${orderId}`)
                .then(response => response.text())
                .then(html => {
                    modalContent.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error:', error);
                    modalContent.innerHTML = `
                        <div style="padding: 20px;">
                            <div class="alert alert-danger mb-3">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                Failed to load order details. Please try again.
                            </div>
                            <div class="text-center">
                                <button onclick="closeModal()" class="btn btn-secondary rounded-pill px-4">Close</button>
                            </div>
                        </div>
                    `;
                });
        }
    </script>
@endsection