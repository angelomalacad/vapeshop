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

    /* --- DRIVER MENU SIDEBAR STYLES (Added without touching your layout) --- */
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

    /* ============================================================ */
    /* ✅ MOBILE-APP STYLES (Android + iPhone)                      */
    /* ============================================================ */
    @media (max-width: 767.98px) {

        /* ---------- Hide sidebar on mobile (bottom nav handles it) ---------- */
        .app-sidebar {
            display: none !important;
        }

        /* ---------- Container padding ---------- */
        .container {
            padding-left: 14px;
            padding-right: 14px;
        }

        /* ---------- Page Header ---------- */
        .page-header {
            margin-bottom: 1rem !important;
            gap: 0.75rem !important;
            flex-direction: column;
            align-items: flex-start !important;
        }

        .page-title {
            font-size: 1.15rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            margin-bottom: 0.35rem;
        }

        .page-title i {
            font-size: 1.15rem;
        }

        /* ---------- Stats Badges (2 per row, scrollable fallback) ---------- */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.4rem;
            width: 100%;
            margin-top: 0.25rem;
        }

        .stat-badge {
            padding: 0.5rem 0.6rem;
            font-size: 0.68rem;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.25rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .stat-badge i {
            font-size: 0.72rem;
        }

        /* ---------- Section Headers ---------- */
        .section-header {
            margin-bottom: 0.85rem;
        }

        .section-title {
            font-size: 0.92rem;
            display: flex;
            align-items: center;
            gap: 0.35rem;
            flex-wrap: wrap;
        }

        .section-title i {
            font-size: 0.95rem;
            margin-right: 0;
        }

        /* ---------- Table Wrapper ---------- */
        .table-wrapper {
            border-radius: 14px;
            overflow: hidden;
        }

        /* ---------- Delivery Table → Card List ---------- */
        .delivery-table {
            border-collapse: separate;
            border-spacing: 0;
        }

        .delivery-table thead {
            display: none;
        }

        .delivery-table tbody tr {
            display: block;
            padding: 0.9rem 1rem;
            border-bottom: 1px solid #eef2f6;
            position: relative;
            transition: background 0.2s ease;
        }

        .delivery-table tbody tr:last-child {
            border-bottom: none;
        }

        .delivery-table tbody tr:active {
            background: #f8fafc;
        }

        .delivery-table td {
            display: block;
            padding: 0.2rem 0;
            border: none;
            font-size: 0.82rem;
            text-align: left !important;
        }

        /* Order # (col 1) — top of card */
        .delivery-table td:nth-child(1) {
            font-size: 0.88rem;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 0.1rem;
        }

        .delivery-table td:nth-child(1) .fw-semibold {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        .delivery-table td:nth-child(1) i {
            font-size: 0.75rem;
        }

        /* Image (col 2) — float right */
        .delivery-table td:nth-child(2) {
            position: absolute;
            top: 0.9rem;
            right: 1rem;
            padding: 0;
            width: auto;
        }

        .delivery-table td:nth-child(2) img,
        .delivery-table td:nth-child(2) > div {
            width: 56px !important;
            height: 56px !important;
            border-radius: 10px !important;
        }

        /* Product (col 3) */
        .delivery-table td:nth-child(3) {
            padding-right: 4.5rem;
            padding-bottom: 0.55rem;
            border-bottom: 1px solid #f1f5f9;
            margin-bottom: 0.4rem;
        }

        .delivery-table td:nth-child(3) div:first-child {
            font-size: 0.9rem;
            color: #1a1a2e;
            font-weight: 500;
            line-height: 1.3;
            margin-bottom: 0.15rem;
        }

        .delivery-table td:nth-child(3) small {
            font-size: 0.72rem;
        }

        /* Amount (col 4) — inline row */
        .delivery-table td:nth-child(4) {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.35rem 0;
            border-bottom: 1px solid #f8fafc;
        }

        .delivery-table td:nth-child(4)::before {
            content: 'Amount';
            font-size: 0.72rem;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .delivery-table td:nth-child(4) .fw-bold {
            font-size: 0.95rem;
            font-weight: 700;
            color: #10b981;
        }

        /* Customer (col 5) — inline row */
        .delivery-table td:nth-child(5) {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.35rem 0;
            border-bottom: 1px solid #f8fafc;
        }

        .delivery-table td:nth-child(5)::before {
            content: 'Customer';
            font-size: 0.72rem;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        /* Contact (col 6) — inline row */
        .delivery-table td:nth-child(6) {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.35rem 0;
            border-bottom: 1px solid #f8fafc;
        }

        .delivery-table td:nth-child(6)::before {
            content: 'Contact';
            font-size: 0.72rem;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        /* Address (col 7) — stacked block */
        .delivery-table td:nth-child(7) {
            padding: 0.5rem 0;
            border-bottom: 1px solid #f8fafc;
            line-height: 1.4;
        }

        .delivery-table td:nth-child(7)::before {
            content: 'Address';
            display: block;
            font-size: 0.72rem;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 0.25rem;
        }

        .delivery-table td:nth-child(7) .small {
            font-size: 0.72rem;
        }

        /* Assigned (col 8) — inline row */
        .delivery-table td:nth-child(8) {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.35rem 0;
            border-bottom: 1px solid #f8fafc;
        }

        .delivery-table td:nth-child(8)::before {
            content: 'Assigned';
            font-size: 0.72rem;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        /* Status (col 9) — inline row */
        .delivery-table td:nth-child(9) {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0 0.25rem;
        }

        .delivery-table td:nth-child(9)::before {
            content: 'Status';
            font-size: 0.72rem;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        /* Completed table has 10 columns — adjust */
        #completed-section .delivery-table td:nth-child(9)::before {
            content: 'Status';
        }

        #completed-section .delivery-table td:nth-child(9) {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.35rem 0;
            border-bottom: 1px solid #f8fafc;
        }

        /* Delivered On (col 8 for completed table) — rename label */
        #completed-section .delivery-table td:nth-child(8)::before {
            content: 'Delivered On';
        }

        /* Action (col 10 for completed table) — full width bottom */
        #completed-section .delivery-table td:nth-child(10) {
            padding-top: 0.65rem;
            margin-top: 0.25rem;
            text-align: center;
        }

        #completed-section .delivery-table .btn-view {
            width: 100%;
            padding: 0.6rem 1rem;
            font-size: 0.82rem;
            font-weight: 600;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #completed-section .delivery-table .btn-view:active {
            transform: scale(0.98);
        }

        /* Status Badges */
        .status-badge {
            font-size: 0.68rem;
            padding: 0.28rem 0.6rem;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 0.25rem;
            margin-top: 0.75rem;
        }

        .pagination .page-link {
            font-size: 0.78rem;
            padding: 0.4rem 0.65rem;
            border-radius: 10px !important;
        }

        /* Empty state */
        .empty-state {
            padding: 2.5rem 1rem;
            border-radius: 14px;
        }

        .empty-state i {
            font-size: 2.5rem;
        }

        .empty-state h5 {
            font-size: 0.95rem;
        }

        .empty-state p {
            font-size: 0.78rem;
        }

        /* Modal — bottom sheet */
        .modal-dialog {
            margin: 0 !important;
            align-items: flex-end;
            min-height: calc(100% - 20px);
        }

        .modal-content {
            border-radius: 24px 24px 0 0 !important;
            border: none;
        }
    }

    /* Extra small devices */
    @media (max-width: 380px) {
        .page-title {
            font-size: 1.02rem;
        }

        .stat-badge {
            font-size: 0.6rem;
            padding: 0.4rem 0.5rem;
        }

        .stat-badge i {
            font-size: 0.65rem;
        }

        .section-title {
            font-size: 0.85rem;
        }

        .delivery-table td:nth-child(3) div:first-child {
            font-size: 0.82rem;
        }

        .delivery-table td:nth-child(4) .fw-bold {
            font-size: 0.88rem;
        }

        #completed-section .delivery-table .btn-view {
            font-size: 0.75rem;
            padding: 0.55rem 0.85rem;
        }
    }

    /* Touch device — remove hover */
    @media (hover: none) {
        .delivery-table tbody tr:hover {
            background: transparent;
        }

        .btn-view:hover {
            background: #eff6ff;
            color: #3b82f6;
            border-color: #dbeafe;
            transform: none;
        }

        .btn-update:hover {
            transform: none;
            box-shadow: none;
        }
    }

    /* iPhone safe area */
    @supports (padding-bottom: env(safe-area-inset-bottom)) {
        @media (max-width: 767.98px) {
            .modal-content {
                padding-bottom: env(safe-area-inset-bottom);
            }
        }
    }
</style>

<!-- 1. THE DRIVER MENU SIDEBAR (Floats on the left, clears header) -->
<div class="app-sidebar">
    <div class="sidebar-header">
        <h6><i class="bi bi-grid-3x3-gap-fill"></i> Driver Menu</h6>
    </div>
    
    <div class="sidebar-menu">
        <a href="{{ route('driver.dashboard') }}" class="menu-item {{ request()->routeIs('driver.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        
        <a href="{{ route('driver.online-orders.index') }}" class="menu-item {{ request()->routeIs('driver.online-orders*') ? 'active' : '' }}">
            <i class="bi bi-cart"></i> Online Orders
        </a>
        
        <a href="{{ route('driver.delivery-history') }}" class="menu-item {{ request()->routeIs('driver.delivery-history') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i> Delivery History
        </a>
    </div>
</div>

<!-- 2. YOUR ORIGINAL CONTENT -->
<div class="container">
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
    
    <!-- Active Deliveries Section -->
    @if($activeDeliveries->count() > 0)
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
                        <th>Assigned</th>
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
                        <td>{{ $delivery->assigned_at ? $delivery->assigned_at->diffForHumans() : 'N/A' }}</td>
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
        
        @if($activeDeliveries->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $activeDeliveries->links() }}
        </div>
        @endif
    </div>
    @endif

    <!-- Completed Deliveries Section -->
    @if($completedDeliveries->count() > 0)
    <div class="mb-4" id="completed-section">
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
                        <th>Status</th>
                        <th class="text-end">Action</th>
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
                            <span class="status-badge status-badge-completed">
                                <i class="bi bi-check-circle-fill"></i> Delivered
                            </span>
                        </td>
                        <td class="text-end">
                            <button type="button" class="btn-view" onclick="openDeliveryModal({{ $delivery->id }})">
                                <i class="bi bi-eye me-1"></i> View Proof
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($completedDeliveries->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $completedDeliveries->links() }}
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