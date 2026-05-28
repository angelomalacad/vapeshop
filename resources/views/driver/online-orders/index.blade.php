@extends('layouts.driver')

@section('title', 'Online Orders - Driver')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0"><i class="bi bi-cart me-2"></i> Online Orders</h1>
            <p class="text-muted mb-0">Manage customer orders from confirmation to delivery</p>
        </div>
        <div>
            <span class="badge bg-primary px-3 py-2">
                <i class="bi bi-shop me-1"></i> {{ Auth::user()->branch->name ?? 'No Branch' }}
            </span>
        </div>
    </div>
    
    <!-- Status Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-2 col-6">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body bg-warning bg-opacity-10 rounded-3">
                    <div class="d-flex flex-column align-items-center">
                        <div class="bg-warning rounded-circle p-2 mb-2" style="width: 45px; height: 45px;">
                            <i class="bi bi-hourglass-split text-white fs-5 d-flex justify-content-center align-items-center h-100"></i>
                        </div>
                        <h6 class="mb-0 fw-bold text-warning">Pending</h6>
                        <h2 class="mb-0 fw-bold">{{ $counts['pending'] }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body bg-info bg-opacity-10 rounded-3">
                    <div class="d-flex flex-column align-items-center">
                        <div class="bg-info rounded-circle p-2 mb-2" style="width: 45px; height: 45px;">
                            <i class="bi bi-check-circle text-white fs-5 d-flex justify-content-center align-items-center h-100"></i>
                        </div>
                        <h6 class="mb-0 fw-bold text-info">Confirmed</h6>
                        <h2 class="mb-0 fw-bold">{{ $counts['confirmed'] }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body bg-primary bg-opacity-10 rounded-3">
                    <div class="d-flex flex-column align-items-center">
                        <div class="bg-primary rounded-circle p-2 mb-2" style="width: 45px; height: 45px;">
                            <i class="bi bi-gear text-white fs-5 d-flex justify-content-center align-items-center h-100"></i>
                        </div>
                        <h6 class="mb-0 fw-bold text-primary">Processing</h6>
                        <h2 class="mb-0 fw-bold">{{ $counts['processing'] }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body bg-success bg-opacity-10 rounded-3">
                    <div class="d-flex flex-column align-items-center">
                        <div class="bg-success rounded-circle p-2 mb-2" style="width: 45px; height: 45px;">
                            <i class="bi bi-box-seam text-white fs-5 d-flex justify-content-center align-items-center h-100"></i>
                        </div>
                        <h6 class="mb-0 fw-bold text-success">Ready</h6>
                        <h2 class="mb-0 fw-bold">{{ $counts['ready'] }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body bg-secondary bg-opacity-10 rounded-3">
                    <div class="d-flex flex-column align-items-center">
                        <div class="bg-secondary rounded-circle p-2 mb-2" style="width: 45px; height: 45px;">
                            <i class="bi bi-truck text-white fs-5 d-flex justify-content-center align-items-center h-100"></i>
                        </div>
                        <h6 class="mb-0 fw-bold text-secondary">Out for Delivery</h6>
                        <h2 class="mb-0 fw-bold">{{ $counts['out_for_delivery'] }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body bg-dark bg-opacity-10 rounded-3">
                    <div class="d-flex flex-column align-items-center">
                        <div class="bg-dark rounded-circle p-2 mb-2" style="width: 45px; height: 45px;">
                            <i class="bi bi-check-circle-fill text-white fs-5 d-flex justify-content-center align-items-center h-100"></i>
                        </div>
                        <h6 class="mb-0 fw-bold text-dark">Delivered</h6>
                        <h2 class="mb-0 fw-bold">{{ $counts['delivered'] }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Orders Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-list-ul me-2 text-primary"></i> Order List
            </h5>
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
                        @forelse($orders as $order)
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
                        @endphp
                        <tr>
                            <td class="ps-4"><code>{{ $order->order_number }}</code></td>
                            <td>{{ $order->created_at->format('M d, Y h:i A') }}</small></div></td>
                            <td>
                                <div class="fw-semibold">{{ $order->customer_name }}</div>
                                <small class="text-muted">{{ $order->customer_phone }}</small>
                            </div></td>
                            <td><strong>₱{{ number_format($order->total_amount, 2) }}</strong></div></td>
                            <td>
                                <span class="badge bg-{{ $order->delivery_type == 'delivery' ? 'info' : 'success' }} bg-opacity-10 text-dark">
                                    <i class="bi bi-{{ $order->delivery_type == 'delivery' ? 'truck' : 'building' }} me-1"></i>
                                    {{ ucfirst($order->delivery_type) }}
                                </span>
                            </div></td>
                            <td>
                                <span class="badge bg-{{ $statusColors[$order->order_status] }}">
                                    {{ ucfirst($order->order_status) }}
                                </span>
                            </div></td>
                            <td class="pe-4">
                                <button onclick="openOrderModal({{ $order->id }})" class="btn btn-sm btn-info rounded-pill px-3">
                                    <i class="bi bi-eye me-1"></i> Manage
                                </button>
                            </div>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-inbox display-1 text-muted"></i>
                                <h5 class="mt-3">No Online Orders</h5>
                                <p class="text-muted">There are no online orders to process at this time.</p>
                            </div>
                        </tr>
                        @endforelse
                    </tbody>
                </div>
            </div>
        </div>
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-center">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    function openOrderModal(orderId) {
        const modal = document.getElementById('customModal');
        const modalContent = document.getElementById('customModalContent');
        
        modalContent.innerHTML = `
            <div style="padding: 20px; text-align: center;">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2">Loading order details...</p>
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
                        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px;">
                            Failed to load order details. Please try again.
                        </div>
                        <div style="text-align: center; margin-top: 15px;">
                            <button onclick="closeModal()" class="btn btn-secondary">Close</button>
                        </div>
                    </div>
                `;
            });
    }
</script>
@endsection