@extends('layouts.driver')

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
    
    /* Status Cards - Modern Minimalist */
    .status-card {
        border: none;
        border-radius: 12px;
        transition: all 0.3s ease;
        background: white;
        overflow: hidden;
        padding: 0.4rem 0.8rem;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    }
    
    .status-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
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
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    
    .modern-card:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
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
    
    .order-table tbody tr {
        transition: all 0.2s;
    }
    
    .order-table tbody tr:hover {
        background: #f8f9fa;
    }
    
    /* Badge Styles */
    .badge-pending { background: #fef3c7; color: #d97706; }
    .badge-confirmed { background: #dbeafe; color: #2563eb; }
    .badge-packing { background: #e0e7ff; color: #4f46e5; }
    .badge-ready { background: #d1fae5; color: #059669; }
    .badge-out_for_delivery { background: #fef3c7; color: #d97706; }
    .badge-delivered { background: #d1fae5; color: #059669; }
    .badge-cancelled { background: #fee2e2; color: #dc2626; }
    
    .badge {
        padding: 0.35rem 0.65rem;
        border-radius: 30px;
        font-weight: 500;
        font-size: 0.7rem;
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
    
    /* Simple Pagination */
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
    
    .simple-pagination .btn-outline-primary {
        border-color: #e2e8f0;
        color: #1a1a2e;
    }
    
    .simple-pagination .btn-outline-primary:hover {
        background: #1a1a2e;
        border-color: #1a1a2e;
        color: white;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .status-number {
            font-size: 1rem;
        }
        
        .status-icon {
            width: 28px;
            height: 28px;
        }
        
        .status-icon i {
            font-size: 0.9rem;
        }
        
        .order-table th,
        .order-table td {
            padding: 0.5rem;
        }
    }

    /* --- FIXED SIDEBAR STYLES (Does NOT touch your layout) --- */
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
        
        /* OFFSET TO CLEAR HEADER: Adjust this number to match your navbar height */
        margin-top: 80px; 
    }
    
    /* Dark header of the sidebar */
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
    
    /* Menu list */
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
    
    /* Active State: Light blue background, blue text, and blue left border */
    .sidebar-menu .menu-item.active {
        background: #eff6ff;
        color: #2563eb;
        border-left: 3px solid #2563eb;
        border-radius: 12px 0 0 12px;
        margin-left: 4px;
        padding-left: 13px;
    }
            /* ============================================================ */
        /* RESPONSIVE CONTENT FIX (Container, Cards, Table)             */
        /* ============================================================ */
        
        /* 1. Ensure the main container doesn't overflow or collapse */
        .container-fluid {
            padding: 20px;
            overflow-x: hidden; /* Prevents horizontal scrollbar */
        }
        
        /* 2. Make sure the Page Header wraps nicely on mobile */
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 10px;
            }
            .page-header .badge {
                align-self: flex-start;
            }
        }

        /* 3. Table Responsiveness (Makes it scroll horizontally if needed) */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch; /* Smooth scrolling on iOS */
        }

        /* 4. Fix Status Cards on Mobile (2 per row) */
        @media (max-width: 768px) {
            .row.g-2 {
                display: flex;
                flex-wrap: wrap;
            }
            .row.g-2 > .col-4 {
                flex: 0 0 50%; /* Force 2 cards per row */
                max-width: 50%;
            }
            .status-number {
                font-size: 1rem;
            }
            .status-icon {
                width: 28px;
                height: 28px;
            }
            .status-icon i {
                font-size: 0.9rem;
            }
        }

        /* 5. Fix Table Columns on Mobile (Prevents squishing) */
        @media (max-width: 768px) {
            .order-table th,
            .order-table td {
                padding: 0.5rem;
                font-size: 0.75rem; /* Slightly smaller text */
                white-space: nowrap;
            }
            .order-table td .fw-semibold {
                font-size: 0.8rem;
            }
            .order-table img {
                width: 40px !important;
                height: 40px !important;
            }
        }

        /* 6. Stack Filter Form on Mobile */
        @media (max-width: 576px) {
            .card-body .row.g-3 {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }
            .card-body .row.g-3 .col-md-4 {
                width: 100%;
            }
            .card-body .row.g-3 .col-12 {
                display: flex;
                flex-direction: column;
                gap: 8px;
            }
            .card-body .row.g-3 .col-12 .btn {
                width: 100%;
            }
            .card-body .row.g-3 .col-12 .btn-outline-secondary.ms-2 {
                margin-left: 0 !important;
                margin-top: 5px;
            }
        }
</style>

<!-- 1. THE SIDEBAR (Floats on the left, clears header, doesn't shift layout) -->
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
        
         <!-- HISTORY LINKED HERE -->
<!-- HISTORY LINKED HERE -->
<a href="{{ route('driver.delivery-history', ['sidebar' => 1]) }}" class="menu-item {{ request()->routeIs('driver.delivery-history') ? 'active' : '' }}">
    <i class="bi bi-clock-history"></i> Delivery History
</a>
</div>
</div>

<!-- 2. YOUR ORIGINAL CONTENT (100% UNTOUCHED - Zero layout changes) -->
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="page-title mb-1"><i class="bi bi-cart me-2 text-primary"></i> Online Orders</h1>
                <p class="page-subtitle mb-0">Manage customer orders from confirmation to delivery</p>
            </div>
            <div>
                <span class="badge bg-primary bg-opacity-10 text-dark px-3 py-2 rounded-pill">
                    <i class="bi bi-shop me-1"></i> {{ Auth::user()->branch->name ?? 'No Specific Branch' }}
                </span>
            </div>
        </div>
    </div>
    
    <!-- Status Cards (Compact, Left Icon) -->
    <div class="row g-2 mb-4">
        <div class="col-md-2 col-4">
            <div class="card status-card">
                <div class="card-body status-card-body">
                    <div class="status-icon bg-warning bg-opacity-10">
                        <i class="bi bi-hourglass-split text-warning"></i>
                    </div>
                    <div class="status-info">
                        <h2 class="status-number">{{ $counts['pending'] }}</h2>
                        <p class="status-label">Pending</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-4">
            <div class="card status-card">
                <div class="card-body status-card-body">
                    <div class="status-icon bg-info bg-opacity-10">
                        <i class="bi bi-check-circle text-info"></i>
                    </div>
                    <div class="status-info">
                        <h2 class="status-number">{{ $counts['confirmed'] }}</h2>
                        <p class="status-label">Confirmed</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-4">
            <div class="card status-card">
                <div class="card-body status-card-body">
                    <div class="status-icon bg-primary bg-opacity-10">
                        <i class="bi bi-box-seam text-primary"></i>
                    </div>
                    <div class="status-info">
                        <h2 class="status-number">{{ $counts['processing'] }}</h2>
                        <p class="status-label">Packing</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-4">
            <div class="card status-card">
                <div class="card-body status-card-body">
                    <div class="status-icon bg-success bg-opacity-10">
                        <i class="bi bi-check-circle-fill text-success"></i>
                    </div>
                    <div class="status-info">
                        <h2 class="status-number">{{ $counts['ready'] }}</h2>
                        <p class="status-label">Ready</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-4">
            <div class="card status-card">
                <div class="card-body status-card-body">
                    <div class="status-icon bg-secondary bg-opacity-10">
                        <i class="bi bi-truck text-secondary"></i>
                    </div>
                    <div class="status-info">
                        <h2 class="status-number">{{ $counts['out_for_delivery'] }}</h2>
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
                        <h2 class="status-number">{{ $counts['delivered'] }}</h2>
                        <p class="status-label">Delivered</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('driver.online-orders.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Filter by Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Packing</option>
                        <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Ready</option>
                        <option value="out_for_delivery" {{ request('status') == 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Date From</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Date To</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-funnel me-1"></i> Apply Filters
                    </button>
                    <a href="{{ route('driver.online-orders.index') }}" class="btn btn-outline-secondary ms-2">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Orders Table -->
    <div class="card modern-card">
        <div class="card-header-modern d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0"><i class="bi bi-list-ul me-2 text-primary"></i> Order List</h5>
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
                            <th>Type</th>
                            <th>Status</th>
                            <th>Delivery Date & Time</th>
                            <th class="pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        @php
                            $statusClass = match($order->order_status) {
                                'pending' => 'badge-pending',
                                'confirmed' => 'badge-confirmed',
                                'processing' => 'badge-packing',
                                'ready' => 'badge-ready',
                                'out_for_delivery' => 'badge-out_for_delivery',
                                'delivered' => 'badge-delivered',
                                'cancelled' => 'badge-cancelled',
                                default => 'badge-secondary'
                            };
                            
                            $displayStatus = $order->order_status == 'processing' ? 'Packing' : ucfirst($order->order_status);
                            
                            // Get first product and image
                            $firstItem = $order->items->first();
                            $product = $firstItem ? $firstItem->product : null;
                            $productName = $product ? $product->name : 'N/A';
                            $itemsCount = $order->items->count();
                            
                            // Get product image
                            $imageUrl = null;
                            if ($product && $product->image) {
                                if (filter_var($product->image, FILTER_VALIDATE_URL)) {
                                    $imageUrl = $product->image;
                                } elseif (Storage::disk('public')->exists($product->image)) {
                                    $imageUrl = Storage::url($product->image);
                                }
                            }
                            
                            // Get delivery date and time
                            $deliveryDateTime = $order->delivered_at ?? $order->updated_at;
                        @endphp
                        <tr>
                            <td class="ps-4"><code class="fw-semibold">{{ $order->order_number }}</code></td>
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
                                <div class="fw-semibold">{{ $productName }}</div>
                                <small class="text-muted">{{ $itemsCount }} item(s)</small>
                            </td>
                            <td class="text-nowrap">
                                {{ $order->created_at->format('M d, Y') }}<br>
                                <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $order->customer_name }}</div>
                                <small class="text-muted">{{ $order->customer_phone }}</small>
                            </td>
                            <td><strong class="text-success">₱{{ number_format($order->total_amount, 2) }}</strong></td>
                            <td>
                                <span class="delivery-badge">
                                    <i class="bi bi-{{ $order->delivery_type == 'delivery' ? 'truck' : 'building' }} me-1"></i>
                                    {{ ucfirst($order->delivery_type) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $statusClass }}">
                                    {{ $displayStatus }}
                                </span>
                            </td>
                            <td>
                                @if(in_array($order->order_status, ['pending', 'confirmed', 'processing', 'ready', 'out_for_delivery']))
                                    <span class="text-muted">N/A</span>
                                @elseif($deliveryDateTime)
                                    {{ \Carbon\Carbon::parse($deliveryDateTime)->format('M d, Y') }}<br>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($deliveryDateTime)->format('h:i A') }}</small>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="pe-4">
                                <button onclick="openOrderModal({{ $order->id }})" class="btn btn-manage btn-sm text-white">
                                    <i class="bi bi-eye me-1"></i> Manage
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <i class="bi bi-inbox display-1 text-muted"></i>
                                <h5 class="mt-3">No Online Orders</h5>
                                <p class="text-muted">There are no online orders to process at this time.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Simple Previous / Next Pagination -->
        @if($orders->hasPages())
        <div class="simple-pagination">
            @if($orders->onFirstPage())
                <button class="btn btn-outline-secondary" disabled>Previous</button>
            @else
                <a href="{{ $orders->previousPageUrl() }}" class="btn btn-outline-primary">Previous</a>
            @endif
            
            @if($orders->hasMorePages())
                <a href="{{ $orders->nextPageUrl() }}" class="btn btn-outline-primary">Next</a>
            @else
                <button class="btn btn-outline-secondary" disabled>Next</button>
            @endif
        </div>
        @endif
    </div>
</div>

<script>
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