@extends('layouts.driver')

@section('content')
<style>
    /* Modern Minimalist Styles */
    .page-header {
        margin-bottom: 1.5rem;
    }
    
    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 0;
    }
    
    .stats-container {
        display: flex;
        gap: 0.75rem;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .stat-badge {
        padding: 0.5rem 1rem;
        border-radius: 30px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .stat-badge-active {
        background: #eff6ff;
        color: #2563eb;
    }
    
    .stat-badge-completed {
        background: #ecfdf5;
        color: #059669;
    }
    
    .stat-badge-total {
        background: #f8f9fa;
        color: #1a1a2e;
    }
    
    /* Section Headers */
    .section-header {
        margin-bottom: 1.25rem;
    }
    
    .section-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 0;
    }
    
    .section-title i {
        margin-right: 0.5rem;
    }
    
    /* Modern Table Styles */
    .table-wrapper {
        background: white;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    
    .delivery-table {
        width: 100%;
        margin-bottom: 0;
        border-collapse: collapse;
        table-layout: fixed; /* Ensures columns stay nicely in the box */
    }
    
    .delivery-table thead {
        background: #f8f9fa;
    }
    
    .delivery-table th {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        padding: 0.875rem 1.25rem;
        border-bottom: 1px solid #eef2f6;
        text-align: left;
    }
    
    .delivery-table td {
        padding: 0.875rem 1.25rem;
        border-bottom: 1px solid #eef2f6;
        vertical-align: middle;
        font-size: 0.85rem;
        color: #1a1a2e;
    }
    
    .delivery-table tbody tr {
        transition: all 0.2s ease;
    }
    
    .delivery-table tbody tr:hover {
        background: #f8f9fa;
    }
    
    .delivery-table tbody tr:last-child td {
        border-bottom: none;
    }
    
    /* Status Badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.65rem;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 500;
        gap: 0.25rem;
    }
    
    .status-badge-active {
        background: #dbeafe;
        color: #2563eb;
    }
    
    .status-badge-completed {
        background: #d1fae5;
        color: #059669;
    }
    
    /* Buttons */
    .btn-update {
        background: #3b82f6;
        color: white;
        border: none;
        border-radius: 12px;
        padding: 0.4rem 1rem;
        font-size: 0.7rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-update:hover {
        background: #2563eb;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59,130,246,0.3);
    }
    
    .btn-view {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        width: auto !important;
        background: #eff6ff;
        color: #3b82f6;
        border: 1px solid #dbeafe;
        border-radius: 12px;
        padding: 0.4rem 1rem;
        font-size: 0.7rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-view:hover {
        background: #3b82f6;
        border-color: #3b82f6;
        color: white;
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
    
    /* Pagination */
    .pagination {
        margin-bottom: 0;
        margin-top: 1rem;
    }
    
    .pagination .page-link {
        border: none;
        color: #1a1a2e;
        border-radius: 30px;
        margin: 0 2px;
        padding: 0.5rem 0.75rem;
        font-size: 0.8rem;
    }
    
    .pagination .page-link:hover {
        background: #f1f5f9;
        color: #1a1a2e;
    }
    
    .pagination .active .page-link {
        background: #3b82f6;
        color: white;
    }
    
    /* Empty State */
    .empty-state {
        background: white;
        border-radius: 16px;
        padding: 3rem;
        text-align: center;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .empty-state i {
        font-size: 3rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }
    
    .empty-state h5 {
        font-size: 1rem;
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 0.5rem;
    }
    
    .empty-state p {
        font-size: 0.8rem;
        color: #64748b;
        margin-bottom: 0;
    }

    /* Filter Form Styles */
    .filter-container {
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid #eef2f6;
    }

    .filter-form .form-label {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        margin-bottom: 0.25rem;
    }

    .filter-form .form-control,
    .filter-form .form-select {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 0.5rem 0.75rem;
        font-size: 0.85rem;
        transition: all 0.2s;
    }

    .filter-form .form-control:focus,
    .filter-form .form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59,130,246,0.1);
        outline: none;
    }

    .filter-form .btn-filter {
        background: #3b82f6;
        color: white;
        border: none;
        border-radius: 10px;
        padding: 0.5rem 1.5rem;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .filter-form .btn-filter:hover {
        background: #2563eb;
        transform: translateY(-1px);
    }

    .filter-form .btn-reset {
        background: #f1f5f9;
        color: #475569;
        border: none;
        border-radius: 10px;
        padding: 0.5rem 1.5rem;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .filter-form .btn-reset:hover {
        background: #e2e8f0;
        color: #1a1a2e;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .page-title {
            font-size: 1.25rem;
        }
        
        .delivery-table th,
        .delivery-table td {
            padding: 0.75rem;
        }
        
        .stats-container {
            margin-top: 0.5rem;
        }
    }

    /* --- SIDEBAR STYLES (Only applied if sidebar is active) --- */
    @if(request()->get('sidebar') == 1)
    .app-sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: 260px;
        background: #ffffff;
        border-radius: 0 16px 16px 0;
        box-shadow: 2px 0 20px rgba(0,0,0,0.05);
        z-index: 1040;
        overflow: hidden;
        padding-bottom: 20px;
        margin-top: 80px;
    }
    
    .sidebar-header {
        background: #1e293b;
        padding: 18px 20px;
        text-align: center;
        color: #fff;
    }
    
    .sidebar-header i {
        font-size: 1.2rem;
        margin-right: 8px;
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
        border-radius: 12px 0 0 12px;
        margin-left: 4px;
        padding-left: 13px;
    }
    @endif
</style>

<!-- 1. THE SIDEBAR (Wrapped in a check - Only shows if sidebar=1) -->
@if(request()->get('sidebar') == 1)
<div class="app-sidebar">
    <!-- Dark Blue Header -->
    <div class="sidebar-header">
        <h6><i class="bi bi-grid-3x3-gap-fill"></i> Driver Menu</h6>
    </div>
    
    <!-- Menu Links -->
    <div class="sidebar-menu">
        <a href="{{ route('driver.dashboard') }}" class="menu-item {{ request()->routeIs('driver.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        
        <a href="{{ route('driver.online-orders.index') }}" class="menu-item {{ request()->routeIs('driver.online-orders*') ? 'active' : '' }}">
            <i class="bi bi-cart"></i> Online Orders
        </a>
        
        <!-- History link is active now -->
        <a href="{{ route('driver.delivery-history', ['sidebar' => 1]) }}" class="menu-item active">
            <i class="bi bi-clock-history"></i> Delivery History
        </a>
    </div>
</div>
@endif

<!-- 2. YOUR MAIN CONTENT -->
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h2 class="page-title"><i class="bi bi-truck me-2 text-primary"></i> Delivery History</h2>
        </div>
        <div class="stats-container">
            <span class="stat-badge stat-badge-active">
                <i class="bi bi-play-circle-fill me-1"></i> {{ $activeCount ?? 0 }} Active
            </span>
            <span class="stat-badge stat-badge-completed">
                <i class="bi bi-check-circle-fill me-1"></i> {{ $completedCount ?? 0 }} Completed
            </span>
            <span class="stat-badge stat-badge-total">
                <i class="bi bi-receipt me-1"></i> {{ $totalDeliveries ?? 0 }} Total
            </span>
        </div>
    </div>

                <!-- FILTER SECTION -->
    <div class="filter-container">
        <form method="GET" action="{{ route('driver.delivery-history', ['sidebar' => request()->get('sidebar')]) }}" class="filter-form row g-3 align-items-end">
            <!-- Search by Order Number -->
            <div class="col-md-2">
                <label class="form-label">Search Order</label>
                <input type="text" name="search" class="form-control" placeholder="Type Order #..." value="{{ request('search') }}">
            </div>

            <!-- Filter by Active/Completed -->
            <div class="col-md-2">
                <label class="form-label">Show</label>
                <select name="filter_section" class="form-select">
                    <option value="all" {{ request('filter_section') == 'all' ? 'selected' : '' }}>All Deliveries</option>
                    <option value="active" {{ request('filter_section') == 'active' ? 'selected' : '' }}>Active Only</option>
                    <option value="completed" {{ request('filter_section') == 'completed' ? 'selected' : '' }}>Completed Only</option>
                </select>
            </div>

            <!-- Filter by Delivery Type -->
            <div class="col-md-2">
                <label class="form-label">Delivery Type</label>
                <select name="delivery_type" class="form-select">
                    <option value="">All Types</option>
                    <option value="lalamove" {{ request('delivery_type') == 'lalamove' ? 'selected' : '' }}>Lalamove</option>
                    <option value="staff" {{ request('delivery_type') == 'staff' ? 'selected' : '' }}>Staff</option>
                </select>
            </div>

            <!-- Date From -->
            <div class="col-md-2">
                <label class="form-label">Date From</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>

            <!-- Date To -->
            <div class="col-md-2">
                <label class="form-label">Date To</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>

            <!-- Buttons (Now securely inside the border) -->
            <div class="col-md-2">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn-filter">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    <a href="{{ route('driver.delivery-history', ['sidebar' => request()->get('sidebar')]) }}" class="btn-reset">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Active Deliveries Section -->
    @if($activeDeliveries->count() > 0 && (request('filter_section') == 'all' || request('filter_section') == 'active' || empty(request('filter_section'))))
    <div class="mb-5">
        <div class="section-header">
            <h4 class="section-title">
                <i class="bi bi-play-circle-fill text-warning"></i> Active Deliveries
                <span class="count-badge">{{ $activeCount ?? 0 }} active</span>
            </h4>
        </div>
        <div class="table-wrapper">
            <table class="delivery-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Amount</th>
                        <th>Customer</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Type</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activeDeliveries as $delivery)
                    @php
                        $firstItem = $delivery->order->items->first();
                        $product = $firstItem ? $firstItem->product : null;
                        $productName = $product ? $product->name : 'N/A';
                        $itemsCount = $delivery->order->items->count();
                        $imageUrl = null;
                        if ($product && $product->image) {
                            if (filter_var($product->image, FILTER_VALIDATE_URL)) {
                                $imageUrl = $product->image;
                            } elseif (Storage::disk('public')->exists($product->image)) {
                                $imageUrl = Storage::url($product->image);
                            }
                        }

                        // Lalamove Eligibility Check
                        $cityLower = strtolower(trim($delivery->order->city ?? ''));
                        $isCalambaCity = $cityLower === 'calamba city' || $cityLower === 'calamba';
                        $isLalamoveEligible = !$isCalambaCity;
                    @endphp
                    <tr>
                        <td>
                            <span class="fw-semibold">
                                <i class="bi bi-receipt me-1 text-muted"></i>
                                #{{ $delivery->order->order_number ?? 'N/A' }}
                            </span>
                        </td>
                        <td>
                            @if($imageUrl)
                                <img src="{{ $imageUrl }}" alt="{{ $productName }}" 
                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                            @else
                                <div style="width: 50px; height: 50px; background: #f8f9fa; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-image text-muted" style="font-size: 1.2rem;"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div>{{ $productName }}</div>
                            <small class="text-muted">{{ $itemsCount }} item(s)</small>
                        </td>
                        <td>
                            <span class="fw-bold text-success">₱{{ number_format($delivery->order->total_amount ?? 0, 2) }}</span>
                        </td>
                        <td>{{ $delivery->recipient_name }}</td>
                        <td>{{ $delivery->recipient_phone }}</td>
                        <td>
                            <div>{{ Str::limit($delivery->delivery_address, 40) }}</div>
                            @if($delivery->order)
                            <div class="text-muted small">
                                {{ $delivery->order->barangay ?? '' }}, {{ $delivery->order->city ?? '' }}
                            </div>
                            @endif
                        </td>
                        <td>
                            <span class="delivery-badge">
                                @if($isLalamoveEligible)
                                    <i class="bi bi-truck me-1 text-primary"></i> Lalamove
                                @else
                                    <i class="bi bi-bicycle me-1 text-success"></i> Staff
                                @endif
                            </span>
                        </td>
                        <td>
                            <span class="status-badge status-badge-active">
                                <i class="bi bi-{{ $delivery->status == 'in_transit' ? 'truck' : ($delivery->status == 'picked_up' ? 'box-seam' : 'clock') }}"></i>
                                {{ ucfirst($delivery->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- ✅ Pagination for Active Deliveries (5 per page) -->
        @if($activeDeliveries->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $activeDeliveries->appends(['completed_page' => request('completed_page')])->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
    @endif

     <!-- Completed Deliveries Section -->
    @if($completedDeliveries->count() > 0 && (request('filter_section') == 'all' || request('filter_section') == 'completed' || empty(request('filter_section'))))
    <div class="mb-4">
        <div class="section-header">
            <h4 class="section-title">
                <i class="bi bi-check-circle-fill text-success"></i> Completed Deliveries
                <span class="count-badge">{{ $completedCount ?? 0 }} completed</span>
            </h4>
        </div>
        <div class="table-wrapper">
            <table class="delivery-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Amount</th>
                        <th>Customer</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Delivered On</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Lalamove Info</th>
                        <th class="text-end" style="width: 115px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($completedDeliveries as $delivery)
                    @php
                        $firstItem = $delivery->order->items->first();
                        $product = $firstItem ? $firstItem->product : null;
                        $productName = $product ? $product->name : 'N/A';
                        $itemsCount = $delivery->order->items->count();
                        $imageUrl = null;
                        if ($product && $product->image) {
                            if (filter_var($product->image, FILTER_VALIDATE_URL)) {
                                $imageUrl = $product->image;
                            } elseif (Storage::disk('public')->exists($product->image)) {
                                $imageUrl = Storage::url($product->image);
                            }
                        }

                        // Lalamove Eligibility Check
                        $cityLower = strtolower(trim($delivery->order->city ?? ''));
                        $isCalambaCity = $cityLower === 'calamba city' || $cityLower === 'calamba';
                        $isLalamoveEligible = !$isCalambaCity;
                    @endphp
                    <tr>
                        <td>
                            <span class="fw-semibold">
                                <i class="bi bi-receipt me-1 text-muted"></i>
                                #{{ $delivery->order->order_number ?? 'N/A' }}
                            </span>
                        </td>
                        <td>
                            @if($imageUrl)
                                <img src="{{ $imageUrl }}" alt="{{ $productName }}" 
                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                            @else
                                <div style="width: 50px; height: 50px; background: #f8f9fa; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-image text-muted" style="font-size: 1.2rem;"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div>{{ $productName }}</div>
                            <small class="text-muted">{{ $itemsCount }} item(s)</small>
                        </td>
                        <td>
                            <span class="fw-bold text-success">₱{{ number_format($delivery->order->total_amount ?? 0, 2) }}</span>
                        </td>
                        <td>{{ $delivery->recipient_name }}</td>
                        <td>{{ $delivery->recipient_phone }}</td>
                        <td>
                            <div>{{ Str::limit($delivery->delivery_address, 40) }}</div>
                            @if($delivery->order)
                            <div class="text-muted small">
                                {{ $delivery->order->barangay ?? '' }}, {{ $delivery->order->city ?? '' }}
                            </div>
                            @endif
                        </td>
                        <td>{{ $delivery->delivered_at ? $delivery->delivered_at->format('M d, Y h:i A') : 'N/A' }}</td>
                        <td>
                            <span class="delivery-badge">
                                @if($isLalamoveEligible)
                                    <i class="bi bi-truck me-1 text-primary"></i> Lalamove
                                @else
                                    <i class="bi bi-bicycle me-1 text-success"></i> Staff
                                @endif
                            </span>
                        </td>
                        <td>
                            <span class="status-badge status-badge-completed">
                                <i class="bi bi-check-circle-fill"></i> Delivered
                            </span>
                        </td>
                        
                        {{-- Lalamove Info Column --}}
                        <td>
                            @if($isLalamoveEligible && !empty($delivery->tracking_number))
                                <a href="{{ $delivery->tracking_number }}" target="_blank" class="btn btn-sm btn-primary" style="font-size: 0.7rem; padding: 0.2rem 0.6rem;">
                                    <i class="bi bi-eye"></i> Link
                                </a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        
                        <td class="text-end" style="white-space: nowrap;">
                            <button type="button" class="btn-view" onclick="openDeliveryModal({{ $delivery->id }})">
                                <i class="bi bi-eye me-1"></i> Proof
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- ✅ Pagination for Completed Deliveries (5 per page) -->
        @if($completedDeliveries->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $completedDeliveries->appends(['active_page' => request('active_page')])->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
    @endif

    <!-- No Deliveries Message -->
    @if($activeDeliveries->count() == 0 && $completedDeliveries->count() == 0)
    <div class="empty-state">
        <i class="bi bi-truck"></i>
        <h5>No Deliveries Assigned</h5>
        <p>You don't have any deliveries assigned yet.</p>
    </div>
    @endif
</div>

<!-- Modal Container -->
<div id="modalContainer"></div>

<script>
    function openDeliveryModal(deliveryId) {
        const container = document.getElementById('modalContainer');
        
        container.innerHTML = `
            <div class="modal fade" id="deliveryModal" tabindex="-1" data-bs-backdrop="static">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body text-center p-5">
                            <div class="spinner-border text-primary" role="status"></div>
                            <p class="mt-2 text-muted">Loading delivery details...</p>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        const modal = new bootstrap.Modal(document.getElementById('deliveryModal'));
        modal.show();
        
        fetch(`/driver/deliveries/${deliveryId}`)
            .then(response => response.text())
            .then(html => {
                const modalContent = document.querySelector('#deliveryModal .modal-content');
                if (modalContent) {
                    modalContent.innerHTML = html;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const modalContent = document.querySelector('#deliveryModal .modal-content');
                if (modalContent) {
                    modalContent.innerHTML = `
                        <div class="modal-header" style="border-bottom: 1px solid #eef2f6;">
                            <h5 class="modal-title">Error</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-danger">
                                Failed to load delivery details. Please try again.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    `;
                }
            });
    }
</script>
@endsection