@extends('layouts.driver')

@section('page-class', 'has-sidebar')

@section('title', 'Online Orders - Driver')

@section('content')
    <style>
        /* Modern Minimalist Styles */
        .page-header {
            margin-bottom: 1.5rem;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1a1a2e;
        }

        .page-subtitle {
            color: #64748b;
            font-size: 0.875rem;
        }

        /* Status Cards */
        .status-card {
            border: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            background: white;
            overflow: hidden;
            padding: 0.4rem 0.8rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
        }

        .status-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        }

        .status-card-body {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0;
        }

        .status-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            flex-shrink: 0;
        }

        .status-icon i {
            font-size: 1rem;
        }

        .status-info {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .status-number {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1a1a2e;
            margin: 0;
        }

        .status-label {
            font-size: 0.6rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            color: #64748b;
            margin: 0;
        }

        /* Modern Card */
        .modern-card {
            border: none;
            border-radius: 16px;
            background: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .card-header-modern {
            background: white;
            border-bottom: 1px solid #eef2f6;
            padding: 1rem 1.25rem;
        }

        .card-header-modern h5 {
            font-size: 1rem;
            font-weight: 600;
            color: #1a1a2e;
        }

        /* Table Styles */
        .order-table {
            margin-bottom: 0;
        }

        .order-table th {
            background: #f8f9fa;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            padding: 0.75rem;
            border-bottom: 1px solid #eef2f6;
            white-space: nowrap;
        }

        .order-table td {
            padding: 0.75rem;
            vertical-align: middle;
            border-bottom: 1px solid #eef2f6;
            color: #334155;
            font-size: 0.85rem;
        }

        .order-table tbody tr:hover {
            background: #f8f9fa;
        }

        /* Badge Styles */
        .badge-ready {
            background: #d1fae5;
            color: #059669;
        }

        .badge-out_for_delivery {
            background: #fef3c7;
            color: #d97706;
        }

        .badge-picked_up {
            background: #dbeafe;
            color: #2563eb;
        }

        .badge-in_transit {
            background: #e0e7ff;
            color: #4f46e5;
        }

        .badge-delivered {
            background: #d1fae5;
            color: #059669;
        }

        .badge-delivery_failed {
            background: #fee2e2;
            color: #dc2626;
        }

        .badge-cancelled {
            background: #fee2e2;
            color: #dc2626;
        }

        .badge {
            padding: 0.35rem 0.65rem;
            border-radius: 30px;
            font-weight: 500;
            font-size: 0.7rem;
        }

        /* Buttons */
        .btn-manage {
            background: #1a1a2e;
            border: none;
            border-radius: 30px;
            padding: 0.35rem 1rem;
            font-size: 0.75rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-manage:hover {
            background: #16213e;
            transform: translateY(-1px);
        }

        /* Delivery Type Badge */
        .delivery-badge {
            padding: 0.25rem 0.65rem;
            border-radius: 30px;
            font-weight: 500;
            font-size: 0.7rem;
            background: #f1f5f9;
            color: #475569;
        }

        .delivery-badge i {
            font-size: 0.7rem;
        }

        /* Branch Badge - ADDED */
        .branch-badge {
            padding: 0.25rem 0.65rem;
            border-radius: 30px;
            font-weight: 500;
            font-size: 0.7rem;
            background: #e0f2fe;
            color: #0369a1;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        /* Pagination */
        .simple-pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 0;
        }

        .simple-pagination .btn {
            border-radius: 30px;
            padding: 0.3rem 1.2rem;
            font-size: 0.8rem;
            font-weight: 500;
        }

        /* Responsive */
        @media (max-width: 768px) {

            .order-table th,
            .order-table td {
                padding: 0.5rem;
                font-size: 0.75rem;
                white-space: nowrap;
            }
        }

        /* MODAL STYLES - CRITICAL */
        #customModal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 99999;
            display: none;
            justify-content: center;
            align-items: center;
        }

        #customModalContent {
            background: white;
            width: 95%;
            max-width: 900px;
            max-height: 90vh;
            overflow-y: auto;
            border-radius: 12px;
            position: relative;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
    </style>

    <!-- SIDEBAR -->
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

    <!-- CONTENT -->
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="page-title mb-1"><i class="bi bi-cart me-2 text-primary"></i> Online Orders</h1>
                    <p class="page-subtitle mb-0">Manage your assigned deliveries</p>
                </div>
            </div>
        </div>

        <!-- Status Cards - FIXED COUNTS -->
        <div class="row g-2 mb-4">
            <div class="col-md-2 col-4">
                <div class="card status-card">
                    <div class="card-body status-card-body">
                        <div class="status-icon bg-success bg-opacity-10">
                            <i class="bi bi-box-seam text-success"></i>
                        </div>
                        <div class="status-info">
                            <h2 class="status-number">{{ $counts['ready'] ?? 0 }}</h2>
                            <p class="status-label">Ready</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-4">
                <div class="card status-card">
                    <div class="card-body status-card-body">
                        <div class="status-icon bg-info bg-opacity-10">
                            <i class="bi bi-box-seam text-info"></i>
                        </div>
                        <div class="status-info">
                            <h2 class="status-number">{{ $counts['picked_up'] ?? 0 }}</h2>
                            <p class="status-label">Picked Up</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-4">
                <div class="card status-card">
                    <div class="card-body status-card-body">
                        <div class="status-icon bg-warning bg-opacity-10">
                            <i class="bi bi-truck text-warning"></i>
                        </div>
                        <div class="status-info">
                            <h2 class="status-number">{{ $counts['out_for_delivery'] ?? 0 }}</h2>
                            <p class="status-label">Out for Delivery</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-4">
                <div class="card status-card">
                    <div class="card-body status-card-body">
                        <div class="status-icon bg-dark bg-opacity-10">
                            <i class="bi bi-flag-fill text-dark"></i>
                        </div>
                        <div class="status-info">
                            <h2 class="status-number">{{ $counts['delivered'] ?? 0 }}</h2>
                            <p class="status-label">Delivered</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-4">
                <div class="card status-card">
                    <div class="card-body status-card-body">
                        <div class="status-icon bg-danger bg-opacity-10">
                            <i class="bi bi-x-circle text-danger"></i>
                        </div>
                        <div class="status-info">
                            <h2 class="status-number">{{ $counts['delivery_failed'] ?? 0 }}</h2>
                            <p class="status-label">Failed</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('driver.online-orders.index') }}" class="row g-3 align-items-end">
            <!-- Order # Search -->
            <div class="col-md-2">
                <label class="form-label fw-semibold">Order #</label>
                <input type="text" name="order_number" class="form-control" 
                       placeholder="Search order #..." value="{{ request('order_number') }}">
            </div>
            
            <!-- Status Filter -->
            <div class="col-md-2">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Ready</option>
                    <option value="picked_up" {{ request('status') == 'picked_up' ? 'selected' : '' }}>Picked Up</option>
                    <option value="out_for_delivery" {{ request('status') == 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="delivery_failed" {{ request('status') == 'delivery_failed' ? 'selected' : '' }}>Delivery Failed</option>
                </select>
            </div>
            
            <!-- ✅ NEW: Delivery Type Filter -->
            <div class="col-md-2">
                <label class="form-label fw-semibold">Delivery Type</label>
                <select name="delivery_type" class="form-select">
                    <option value="">All Types</option>
                    <option value="lalamove" {{ request('delivery_type') == 'lalamove' ? 'selected' : '' }}>Lalamove</option>
                    <option value="staff" {{ request('delivery_type') == 'staff' ? 'selected' : '' }}>Staff</option>
                </select>
            </div>
            
            <!-- Branch Filter -->
            <div class="col-md-2">
                <label class="form-label fw-semibold">Branch</label>
                <select name="branch_id" class="form-select">
                    <option value="">All Branches</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Date From -->
            <div class="col-md-1">
                <label class="form-label fw-semibold">From</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            
            <!-- Date To -->
            <div class="col-md-1">
                <label class="form-label fw-semibold">To</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            
            <!-- Buttons -->
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                <a href="{{ route('driver.online-orders.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

        <!-- Orders Table -->
        <div class="card modern-card">
            <div class="card-header-modern d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="mb-0"><i class="bi bi-list-ul me-2 text-primary"></i> Delivery Orders</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table order-table">
                        <thead>
                            <tr>
                                <th class="ps-4">Order #</th>
                                <th>Image</th>
                                <th>Product Name</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Branch</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Lalamove Info</th>
                                <th class="pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                @php
                                    // ✅ FIXED: Map delivery status to display status
                                    $deliveryStatus = $order->delivery ? $order->delivery->status : null;
                                    $displayStatus = $order->order_status;
                                    
                                    // Map statuses for display - in_transit shows as out_for_delivery
                                    $statusMap = [
                                        'assigned' => 'ready',
                                        'picked_up' => 'picked_up',
                                        'out_for_delivery' => 'out_for_delivery',
                                        'delivered' => 'delivered',
                                        'delivery_failed' => 'delivery_failed'
                                    ];
                                    
                                    if (isset($statusMap[$deliveryStatus])) {
                                        $displayStatus = $statusMap[$deliveryStatus];
                                    }
                                    
                                    $statusClass = match ($displayStatus) {
                                        'ready' => 'badge-ready',
                                        'out_for_delivery' => 'badge-out_for_delivery',
                                        'picked_up' => 'badge-picked_up',
                                        'in_transit' => 'badge-out_for_delivery',
                                        'delivered' => 'badge-delivered',
                                        'delivery_failed' => 'badge-delivery_failed',
                                        'cancelled' => 'badge-cancelled',
                                        default => 'badge-secondary',
                                    };
                                    
                                    $displayStatusLabel = ucfirst(str_replace('_', ' ', $displayStatus));
                                    if ($displayStatus == 'delivery_failed') {
                                        $displayStatusLabel = 'Delivery Failed';
                                    }

                                    $firstItem = $order->items->first();
                                    $product = $firstItem ? $firstItem->product : null;
                                    $productName = $product ? $product->name : 'N/A';
                                    $itemsCount = $order->items->count();

                                    $imageUrl = null;
                                    if ($product && $product->image) {
                                        if (filter_var($product->image, FILTER_VALIDATE_URL)) {
                                            $imageUrl = $product->image;
                                        } elseif (Storage::disk('public')->exists($product->image)) {
                                            $imageUrl = Storage::url($product->image);
                                        }
                                    }

                                    $cityLower = strtolower(trim($order->city ?? ''));
                                    $isCalambaCity = $cityLower === 'calamba city' || $cityLower === 'calamba';
                                    $isLalamoveEligible = !$isCalambaCity;
                                @endphp
                                <tr>
                                    <td class="ps-4"><code class="fw-semibold">{{ $order->order_number }}</code></td>
                                    <td>
                                        @if ($imageUrl)
                                            <img src="{{ $imageUrl }}" alt="{{ $productName }}"
                                                style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                        @else
                                            <div
                                                style="width: 50px; height: 50px; background: #f8f9fa; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                                <i class="bi bi-image text-muted" style="font-size: 1.2rem;"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $productName }}</div>
                                        <small class="text-muted">{{ $itemsCount }} item(s)</small>
                                    </td>
                                    <td class="text-nowrap">
                                        {{ $order->updated_at->format('M d, Y') }}<br>
                                        <small class="text-muted">{{ $order->updated_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $order->customer_name }}</div>
                                        <small class="text-muted">{{ $order->customer_phone }}</small>
                                    </td>
                                    <td><strong class="text-success">₱{{ number_format($order->total_amount, 2) }}</strong></td>
                                    <td>
                                        @if($order->branch)
                                            <span class="branch-badge">
                                                <i class="bi bi-shop me-1"></i>{{ $order->branch->name }}
                                            </span>
                                        @else
                                            <span class="branch-badge">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="delivery-badge">
                                            @if ($order->is_lalamove)
                                                <i class="bi bi-truck me-1 text-primary"></i> Lalamove
                                            @else
                                                <i class="bi bi-bicycle me-1 text-success"></i> Staff
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $statusClass }}">{{ $displayStatusLabel }}</span>
                                    </td>
                                    <td>
                                        @if ($isLalamoveEligible && $order->delivery && !empty($order->delivery->tracking_number))
                                            <a href="{{ $order->delivery->tracking_number }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i> View Link
                                            </a>
                                        @elseif($isLalamoveEligible && in_array($order->order_status, ['out_for_delivery', 'picked_up']))
                                            <span class="text-muted">Awaiting link</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="pe-4">
                                        <button onclick="window.openOrderModal({{ $order->id }})"
                                            class="btn btn-manage btn-sm text-white">
                                            <i class="bi bi-eye me-1"></i> Manage
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center py-5">
                                        <i class="bi bi-inbox display-1 text-muted"></i>
                                        <h5 class="mt-3">No Online Orders</h5>
                                        <p class="text-muted">There are no online orders assigned to you at this time.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($orders->hasPages())
                <div class="simple-pagination">
                    @if ($orders->onFirstPage())
                        <button class="btn btn-outline-secondary" disabled>Previous</button>
                    @else
                        <a href="{{ $orders->appends(request()->except('page'))->previousPageUrl() }}" class="btn btn-outline-primary">Previous</a>
                    @endif

                    @if ($orders->hasMorePages())
                        <a href="{{ $orders->appends(request()->except('page'))->nextPageUrl() }}" class="btn btn-outline-primary">Next</a>
                    @else
                        <button class="btn btn-outline-secondary" disabled>Next</button>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- MODAL CONTAINER - CRITICAL FIX -->
    <div id="customModal">
        <div id="customModalContent">
            <!-- Content will be injected here via AJAX -->
        </div>
    </div>

    @include('driver.deliveries.show-modal')

    <script>
        // ✅ GLOBAL FUNCTIONS
        window.closeModal = function() {
            const modal = document.getElementById('customModal');
            if (modal) {
                modal.style.display = 'none';
            }
            const content = document.getElementById('customModalContent');
            if (content) {
                content.innerHTML = '';
            }
        };

        // ✅ CHECK FOR STORED SUCCESS MESSAGE ON PAGE LOAD
        document.addEventListener('DOMContentLoaded', function() {
            const successMessage = sessionStorage.getItem('delivery_success_message');
            if (successMessage) {
                if (typeof showNotification === 'function') {
                    showNotification(successMessage, 'success');
                }
                sessionStorage.removeItem('delivery_success_message');
            }
        });

        window.openOrderModal = function(orderId) {
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

                    // ✅ Execute any scripts in the returned HTML
                    const scripts = modalContent.querySelectorAll('script');
                    scripts.forEach(script => {
                        const newScript = document.createElement('script');
                        newScript.textContent = script.textContent;
                        document.body.appendChild(newScript);
                        script.remove();
                    });
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
                                <button onclick="window.closeModal()" class="btn btn-secondary rounded-pill px-4">Close</button>
                            </div>
                        </div>
                    `;
                });
        };
    </script>
@endsection