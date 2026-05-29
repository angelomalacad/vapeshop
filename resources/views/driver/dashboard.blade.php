@extends('layouts.driver')

@section('content')
    <div class="container-fluid">
        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm bg-gradient-primary text-white"
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body py-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="mb-0 fw-bold"><i class="bi bi-person-circle me-2"></i> {{ Auth::user()->name }}
                                </h2>
                                <p class="mb-0 mt-1 opacity-75">
                                    <i class="bi bi-truck me-1"></i> Driver Dashboard
                                </p>
                            </div>
                            <div>
                                @if (isset($todayShift) && $todayShift)
                                    <span class="badge bg-success px-3 py-2 fs-6"><i
                                            class="bi bi-check-circle-fill me-1"></i> On Duty Today</span>
                                @else
                                    <span class="badge bg-secondary px-3 py-2 fs-6"><i class="bi bi-clock me-1"></i> Off
                                        Duty</span>
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
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted text-uppercase small fw-bold">Pending Orders</span>
                                <h2 class="mb-0 fw-bold mt-2 text-warning">{{ $pendingOrdersCount ?? 0 }}</h2>
                                <small class="text-muted">Awaiting confirmation</small>
                            </div>
                            <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                                <i class="bi bi-hourglass-split fs-2 text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted text-uppercase small fw-bold">Ready Orders</span>
                                <h2 class="mb-0 fw-bold mt-2 text-success">{{ $readyOrdersCount ?? 0 }}</h2>
                                <small class="text-muted">Ready for delivery</small>
                            </div>
                            <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                                <i class="bi bi-box-seam fs-2 text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted text-uppercase small fw-bold">Out for Delivery</span>
                                <h2 class="mb-0 fw-bold mt-2 text-info">{{ $outForDeliveryCount ?? 0 }}</h2>
                                <small class="text-muted">On the way</small>
                            </div>
                            <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                                <i class="bi bi-truck fs-2 text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted text-uppercase small fw-bold">My Deliveries</span>
                                <h2 class="mb-0 fw-bold mt-2 text-primary">{{ $totalDeliveries ?? 0 }}</h2>
                                <small class="text-muted">Assigned to you</small>
                            </div>
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
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
                <div class="card border-0 shadow-sm text-center h-100 hover-lift transition">
                    <div class="card-body py-5">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-4 mb-3">
                            <i class="bi bi-cart display-3 text-primary"></i>
                        </div>
                        <h4 class="mb-2">Online Orders</h4>
                        <p class="text-muted mb-4">View and process customer orders</p>
                        <a href="{{ route('driver.online-orders.index') }}" class="btn btn-primary rounded-pill px-4 py-2">
                            <i class="bi bi-arrow-right me-2"></i> Manage Orders
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm text-center h-100 hover-lift transition">
                    <div class="card-body py-5">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex p-4 mb-3">
                            <i class="bi bi-truck display-3 text-success"></i>
                        </div>
                        <h4 class="mb-2">My Deliveries</h4>
                        <p class="text-muted mb-4">Track and update your deliveries</p>
                        <a href="{{ route('driver.deliveries') }}" class="btn btn-success rounded-pill px-4 py-2">
                            <i class="bi bi-arrow-right me-2"></i> View Deliveries
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Online Orders Section -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold">
                    <i class="bi bi-cart me-2 text-primary"></i> Recent Online Orders
                </h5>
                <a href="{{ route('driver.online-orders.index') }}" class="btn btn-sm btn-outline-primary rounded-pill">
                    View All <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body p-0">
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
                                <th class="pe-4">Actions</th>
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
                                        <span
                                            class="badge bg-{{ $order->delivery_type == 'delivery' ? 'info' : 'success' }} bg-opacity-10 text-dark">
                                            <i
                                                class="bi bi-{{ $order->delivery_type == 'delivery' ? 'truck' : 'building' }} me-1"></i>
                                            {{ ucfirst($order->delivery_type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $badgeColor }}">
                                            {{ ucfirst($order->order_status) }}
                                        </span>
                                    </td>
                                    <td class="pe-4">
                                        <button onclick="openOrderModal({{ $order->id }})"
                                            class="btn btn-sm btn-info rounded-pill px-3">
                                            <i class="bi bi-eye me-1"></i> Manage
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="bi bi-inbox display-1 text-muted"></i>
                                        <h5 class="mt-3">No Recent Orders</h5>
                                        <p class="text-muted">No online orders have been placed recently.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if (isset($recentOnlineOrders) && $recentOnlineOrders->count() > 0)
                <div class="card-footer bg-white text-center py-2">
                    <small class="text-muted">Showing last {{ $recentOnlineOrders->count() }} orders</small>
                </div>
            @endif
        </div>

        <!-- Recent Deliveries -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="bi bi-clock-history me-2 text-primary"></i> Recent Deliveries
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Tracking #</th>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Status</th>
                                <th>Updated</th>
                                <th class="pe-4"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentDeliveries ?? [] as $delivery)
                                <tr>
                                    <td class="ps-4"><code>{{ $delivery->tracking_number }}</code></td>
                                    <td class="text-nowrap">{{ $delivery->order->order_number ?? 'N/A' }}</td>
                                    <td>{{ $delivery->recipient_name }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $delivery->status == 'delivered' ? 'success' : ($delivery->status == 'in_transit' ? 'warning' : 'info') }}">
                                            {{ ucfirst($delivery->status) }}
                                        </span>
                                    </td>
                                    <td class="text-nowrap">{{ $delivery->updated_at->diffForHumans() }}</td>
                                    <td class="pe-4">
                                        <a href="{{ route('driver.deliveries.show', $delivery) }}"
                                            class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            <i class="bi bi-eye me-1"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                        <p class="text-muted mb-0">No recent deliveries</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .hover-lift {
            transition: transform 0.2s;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
        }

        .transition {
            transition: all 0.2s;
        }

        /* Table hover effect */
        .table tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
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
