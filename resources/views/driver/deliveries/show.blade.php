<style>
    /* Modern Minimalist Modal Styles */
    .modal-header-custom {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #eef2f6;
        background: white;
    }

    .order-number {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 0.25rem;
    }

    .order-date {
        font-size: 0.75rem;
        color: #64748b;
        margin-bottom: 0;
    }

    /* Cards */
    .info-card {
        border: none;
        border-radius: 16px;
        background: white;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        margin-bottom: 1.25rem;
        overflow: hidden;
    }

    .card-header-custom {
        padding: 0.875rem 1.25rem;
        border-bottom: 1px solid #eef2f6;
        background: white;
    }

    .card-header-custom h6 {
        font-size: 0.875rem;
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 0;
    }

    .card-header-custom i {
        color: #3b82f6;
        margin-right: 0.5rem;
    }

    /* Product Image */
    .product-image {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 10px;
        background: #f8f9fa;
    }

    /* Table Styles */
    .order-items-table {
        margin-bottom: 0;
        width: 100%;
        table-layout: fixed;
    }

    .order-items-table th {
        background: #f8f9fa;
        font-weight: 600;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        padding: 0.75rem 0.5rem;
        border-bottom: 1px solid #eef2f6;
        text-align: left;
    }

    .order-items-table td {
        padding: 0.75rem 0.5rem;
        vertical-align: middle;
        border-bottom: 1px solid #eef2f6;
        font-size: 0.8rem;
        color: #334155;
        word-wrap: break-word;
    }

    .product-name {
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 0;
    }

    .product-flavor {
        font-size: 0.7rem;
        color: #64748b;
    }

    /* Info Labels */
    .info-label {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        margin-bottom: 0.25rem;
    }

    .info-value {
        font-size: 0.875rem;
        color: #1a1a2e;
        margin-bottom: 0.75rem;
        font-weight: 500;
        word-wrap: break-word;
    }

    /* Alert Styles */
    .alert-custom {
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .alert-info-custom {
        background: #eff6ff;
        border: 1px solid #dbeafe;
        color: #1e40af;
    }

    .alert-success-custom {
        background: #ecfdf5;
        border: 1px solid #d1fae5;
        color: #065f46;
    }

    /* Totals */
    .totals-row {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 1rem;
    }

    /* ✅ NEW: Align totals under TOTAL column */
    .totals-align-fixed .totals-label {
        margin-left: 25rem;
    }

    .totals-align-fixed .totals-value {
        margin-right: 6rem;
    }

    .totals-label {
        font-size: 0.8rem;
        color: #64748b;
    }

    .totals-value {
        font-size: 0.8rem;
        font-weight: 600;
        color: #1a1a2e;
    }

    .totals-total {
        border-top: 1px solid #eef2f6;
        margin-top: 0.5rem;
        padding-top: 0.5rem;
    }

    .totals-total .totals-label {
        font-weight: 700;
        font-size: 0.9rem;
        color: #1a1a2e;
    }

    .totals-total .totals-value {
        font-weight: 700;
        font-size: 0.9rem;
        color: #e74c3c;
    }

    /* Modal Body Scroll */
    .modal-body-custom {
        max-height: 85vh;
        overflow-y: auto;
        padding: 0;
    }

    /* ✅ FIXED: DELIVERY PROGRESS TIMELINE STYLES */
    .timeline-container {
        padding: 0.5rem 0;
    }

    .timeline-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 1.5rem;
        position: relative;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-right: 1rem;
        z-index: 1;
        background: white;
        border: 2px solid #cbd5e1;
        color: #94a3b8;
    }

    .timeline-icon.completed {
        background: #10b981;
        border-color: #10b981;
        color: white;
    }

    .timeline-icon.current {
        background: #fef3c7;
        border-color: #d97706;
        color: #d97706;
    }

    .timeline-icon.pending {
        background: white;
        border-color: #cbd5e1;
        color: #94a3b8;
    }

    .timeline-icon i {
        font-size: 1rem;
    }

    .timeline-content {
        flex: 1;
    }

    .timeline-title {
        font-weight: 600;
        font-size: 0.85rem;
        color: #1a1a2e;
        margin-bottom: 0.25rem;
    }

    .timeline-date {
        font-size: 0.7rem;
        color: #64748b;
    }

    .timeline-line {
        position: absolute;
        left: 20px;
        top: 40px;
        width: 2px;
        height: calc(100% - 20px);
        background: #e2e8f0;
    }

    .timeline-line.completed {
        background: #10b981;
    }

    .timeline-item:last-child .timeline-line {
        display: none;
    }

    /* Proof Images */
    .proof-image {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .proof-image:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* ✅ NEW: Stock Info Styles */
    .stock-info {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.25rem;
        padding: 0.5rem;
        border-radius: 8px;
        font-size: 0.75rem;
        text-align: center;
        white-space: nowrap;
    }

    .stock-info-in {
        background: #d1fae5;
        border: 1px solid #a7f3d0;
        color: #059669;
    }

    .stock-info-low {
        background: #fef3c7;
        border: 1px solid #fde68a;
        color: #d97706;
    }

    .stock-info-out {
        background: #fee2e2;
        border: 1px solid #fecaca;
        color: #dc2626;
    }

    .stock-info-icon {
        font-size: 1rem;
    }

    /* ✅ FIXED: Status Badge Colors */
    .badge-pending {
        background: #fef3c7;
        color: #d97706;
    }

    .badge-ready {
        background: #d1fae5;
        color: #059669;
    }

    .badge-picked_up {
        background: #dbeafe;
        color: #2563eb;
    }

    .badge-out_for_delivery {
        background: #fef3c7;
        color: #d97706;
    }

    .badge-delivered {
        background: #d1fae5;
        color: #059669;
    }

    .badge-delivery_failed {
        background: #fee2e2;
        color: #dc2626;
    }

    .badge-secondary {
        background: #f1f5f9;
        color: #475569;
    }

    .badge {
        display: inline-block;
        padding: 0.35rem 0.65rem;
        border-radius: 30px;
        font-weight: 600;
        font-size: 0.7rem;
        text-transform: capitalize;
        line-height: 1;
    }

    /* ============================================================ */
    /* ✅ MOBILE-APP STYLES (Android + iPhone)                      */
    /* ============================================================ */
    @media (max-width: 767.98px) {

        /* ---------- Modal Body Padding ---------- */
        .modal-body-custom {
            max-height: 88vh;
        }

        .modal-body-custom > div {
            padding: 1.25rem 1rem !important;
        }

        /* ---------- Header ---------- */
        .modal-header-custom {
            padding: 0 0 0.75rem 0 !important;
        }

        .order-number {
            font-size: 1.05rem;
            line-height: 1.3;
            padding-right: 2rem;
        }

        .order-date {
            font-size: 0.72rem;
        }

        /* ---------- Cards ---------- */
        .info-card {
            border-radius: 14px;
            margin-bottom: 0.85rem !important;
        }

        .card-header-custom {
            padding: 0.75rem 1rem;
        }

        .card-header-custom h6 {
            font-size: 0.82rem;
        }

        .info-card .card-body,
        .info-card > .p-3 {
            padding: 0.9rem !important;
        }

        /* ---------- Order Items Table → Card List ---------- */
        .order-items-table {
            table-layout: auto;
        }

        .order-items-table thead {
            display: none;
        }

        .order-items-table tbody tr {
            display: block;
            padding: 0.85rem 1rem;
            border-bottom: 1px solid #eef2f6;
            position: relative;
        }

        .order-items-table tbody tr:last-child {
            border-bottom: none;
        }

        .order-items-table td {
            display: block;
            padding: 0.2rem 0;
            border: none;
            font-size: 0.82rem;
            text-align: left !important;
            width: auto !important;
        }

        /* Image cell (col 1) */
        .order-items-table td:nth-child(1) {
            display: inline-block;
            vertical-align: top;
            margin-right: 0.75rem;
            padding: 0;
            width: auto !important;
        }

        .order-items-table td:nth-child(1) .product-image {
            width: 48px !important;
            height: 48px !important;
            border-radius: 10px !important;
        }

        /* Product name (col 2) */
        .order-items-table td:nth-child(2) {
            display: inline-block;
            vertical-align: top;
            width: calc(100% - 60px) !important;
            padding: 0 0 0.5rem 0;
            margin-bottom: 0.4rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .order-items-table td:nth-child(2) .product-name {
            font-size: 0.88rem;
            line-height: 1.3;
            word-break: break-word;
        }

        .order-items-table td:nth-child(2) .product-flavor {
            font-size: 0.7rem;
        }

        /* Qty / Price / Subtotal (cols 3,4,5) - inline rows */
        .order-items-table td:nth-child(3),
        .order-items-table td:nth-child(4),
        .order-items-table td:nth-child(5) {
            display: inline-block;
            width: auto !important;
            padding: 0.3rem 0.75rem 0.3rem 0;
            font-size: 0.78rem;
            color: #475569;
        }

        .order-items-table td:nth-child(3)::before {
            content: 'Qty: ';
            font-weight: 600;
            color: #94a3b8;
        }

        .order-items-table td:nth-child(4)::before {
            content: '@ ';
            font-weight: 500;
            color: #94a3b8;
        }

        .order-items-table td:nth-child(5) {
            float: right;
            padding-right: 0;
            font-weight: 700;
            color: #1a1a2e;
            font-size: 0.85rem;
        }

        /* Stock cell (col 6) */
        .order-items-table td:nth-child(6) {
            display: block;
            padding-top: 0.4rem;
            text-align: left !important;
        }

        .order-items-table td:nth-child(6) .stock-info {
            display: inline-flex;
            flex-direction: row;
            gap: 0.5rem;
            padding: 0.35rem 0.65rem;
            font-size: 0.7rem;
            margin-top: 0.15rem;
        }

        /* ---------- Totals ---------- */
        .info-card .p-3.bg-light {
            padding: 0.75rem 1rem !important;
        }

        .totals-align-fixed .totals-label,
        .totals-align-fixed .totals-value {
            margin: 0 !important;
        }

        .totals-row {
            padding: 0.35rem 0 !important;
        }

        .totals-label {
            font-size: 0.82rem;
        }

        .totals-value {
            font-size: 0.82rem;
        }

        .totals-total {
            margin-top: 0.4rem;
            padding-top: 0.4rem;
        }

        .totals-total .totals-label,
        .totals-total .totals-value {
            font-size: 0.95rem;
        }

        /* ---------- Info Rows ---------- */
        .info-label {
            font-size: 0.68rem;
            margin-bottom: 0.15rem;
        }

        .info-value {
            font-size: 0.82rem;
            margin-bottom: 0.65rem;
            line-height: 1.4;
            word-break: break-word;
        }

        /* Delivery Info + Customer Details — stack columns */
        .row.g-3 > .col-md-6 {
            flex: 0 0 100%;
            max-width: 100%;
        }

        /* ---------- Timeline ---------- */
        .timeline-container {
            padding: 0.5rem 0;
        }

        .timeline-item {
            margin-bottom: 1.25rem;
        }

        .timeline-icon {
            width: 36px;
            height: 36px;
            margin-right: 0.85rem;
        }

        .timeline-icon i {
            font-size: 0.9rem;
        }

        .timeline-line {
            left: 18px;
            top: 36px;
        }

        .timeline-title {
            font-size: 0.82rem;
        }

        .timeline-date {
            font-size: 0.68rem;
        }

        /* ---------- Proof Images ---------- */
        .proof-image {
            height: 140px;
            border-radius: 12px;
        }

        /* Proof image download buttons */
        .info-card .btn-outline-primary,
        .info-card .btn-outline-success {
            font-size: 0.72rem;
            padding: 0.35rem 0.75rem;
            width: 100%;
        }

        /* ---------- Lalamove Tracking Card ---------- */
        .info-card[style*="border: 1px solid #0d6efd"] {
            border-radius: 14px;
        }

        .info-card[style*="border: 1px solid #0d6efd"] .info-value a {
            font-size: 0.78rem;
        }

        /* ---------- Badges ---------- */
        .badge {
            font-size: 0.68rem;
            padding: 0.3rem 0.6rem;
        }
    }

    /* Extra small devices */
    @media (max-width: 380px) {
        .order-number {
            font-size: 0.95rem;
        }

        .order-date {
            font-size: 0.68rem;
        }

        .order-items-table td:nth-child(1) .product-image {
            width: 42px !important;
            height: 42px !important;
        }

        .order-items-table td:nth-child(2) {
            width: calc(100% - 54px) !important;
        }

        .order-items-table td:nth-child(2) .product-name {
            font-size: 0.82rem;
        }

        .info-value {
            font-size: 0.78rem;
        }

        .timeline-icon {
            width: 32px;
            height: 32px;
        }

        .timeline-icon i {
            font-size: 0.8rem;
        }

        .timeline-line {
            left: 16px;
            top: 32px;
        }

        .timeline-title {
            font-size: 0.78rem;
        }

        .proof-image {
            height: 120px;
        }
    }

    /* Touch device — remove hover */
    @media (hover: none) {
        .proof-image:hover {
            transform: none;
            box-shadow: none;
        }

        .proof-image:active {
            transform: scale(0.98);
        }
    }
</style>

<div class="modal-body-custom">
    <div style="padding: 1.5rem;">
        <!-- Header -->
        <div class="modal-header-custom" style="padding: 0 0 1rem 0;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="order-number"><i class="bi bi-truck text-primary me-2"></i> Delivery Details</h5>
                    <p class="order-date">Delivery for Order #{{ $delivery->order->order_number ?? 'N/A' }}</p>
                </div>
                <button type="button" class="btn-close" onclick="window.closeBranchDeliveryModal()"></button>
            </div>
        </div>

        <!-- Order Items - FULL WIDTH -->
        <div class="info-card">
            <div class="card-header-custom">
                <h6><i class="bi bi-box-seam"></i> Order Items</h6>
            </div>
            <div class="card-body p-0">
                @if ($delivery->order && $delivery->order->items->count() > 0)
                    <div class="table-responsive">
                        <table class="table order-items-table">
                            <thead>
                                <tr>
                                    <th style="width: 10%">Image</th>
                                    <th style="width: 25%">Product</th>
                                    <th class="text-center" style="width: 8%">Qty</th>
                                    <th class="text-end" style="width: 15%">Price</th>
                                    <th class="text-end" style="width: 15%">Subtotal</th>
                                    <th class="text-center" style="width: 15%">Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($delivery->order->items as $item)
                                    @php
                                        $product = $item->product;
                                        $imageUrl = null;
                                        if ($product && $product->image) {
                                            if (filter_var($product->image, FILTER_VALIDATE_URL)) {
                                                $imageUrl = $product->image;
                                            } elseif (Storage::disk('public')->exists($product->image)) {
                                                $imageUrl = Storage::url($product->image);
                                            }
                                        }

                                        // ✅ STOCK CHECK
                                        $stockAvailable = 0;
                                        $stockClass = 'stock-info-in';
                                        $stockIcon = 'bi-check-circle-fill';
                                        
                                        if ($delivery->order->branch_id && $product) {
                                            $branchInventory = \App\Models\BranchInventory::where('branch_id', $delivery->order->branch_id)
                                                ->where('product_id', $product->id)
                                                ->when($item->flavor_id, function($query) use ($item) {
                                                    return $query->where('flavor_id', $item->flavor_id);
                                                }, function($query) {
                                                    return $query->whereNull('flavor_id');
                                                })
                                                ->first();

                                            if ($branchInventory) {
                                                $stockAvailable = $branchInventory->available_quantity;

                                                if ($stockAvailable <= 0) {
                                                    $stockClass = 'stock-info-out';
                                                    $stockIcon = 'bi-x-circle-fill';
                                                } elseif ($stockAvailable <= $branchInventory->low_stock_threshold) {
                                                    $stockClass = 'stock-info-low';
                                                    $stockIcon = 'bi-exclamation-triangle-fill';
                                                }
                                            }
                                        }
                                    @endphp
                                    <tr>
                                        <td>
                                            @if ($imageUrl)
                                                <img src="{{ $imageUrl }}"
                                                    alt="{{ $product->name ?? 'N/A' }}" class="product-image">
                                            @else
                                                <div
                                                    class="product-image bg-light d-flex align-items-center justify-content-center">
                                                    <i class="bi bi-image text-muted"
                                                        style="font-size: 1.2rem;"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="product-name">{{ $item->product->name ?? 'N/A' }}</div>
                                            @if ($item->flavor)
                                                <div class="product-flavor">Flavor: {{ $item->flavor->name }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">₱{{ number_format($item->price, 2) }}</td>
                                        <td class="text-end">₱{{ number_format($item->subtotal, 2) }}</td>
                                        <td class="text-center">
                                            <div class="stock-info {{ $stockClass }}">
                                                <i class="bi {{ $stockIcon }} stock-info-icon"></i>
                                                <span><strong>Avail:</strong> {{ $stockAvailable }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- ✅ Subtotal and Total -->
                    <div class="p-3 bg-light">
                        <div class="totals-row totals-align-fixed">
                            <span class="totals-label">Subtotal</span>
                            <span class="totals-value">₱{{ number_format($delivery->order->subtotal, 2) }}</span>
                        </div>
                        <div class="totals-row totals-total totals-align-fixed">
                            <span class="totals-label">Total</span>
                            <span class="totals-value">₱{{ number_format($delivery->order->subtotal, 2) }}</span>
                        </div>
                    </div>
                @else
                    <div class="text-center py-3">
                        <p class="text-muted small mb-0">No items found</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Delivery Information + Customer Details - ONE ROW -->
        <div class="row g-3">
            <!-- Delivery Information (Left) -->
            <div class="col-md-6">
                <div class="info-card">
                    <div class="card-header-custom">
                        <h6><i class="bi bi-info-circle"></i> Delivery Information</h6>
                    </div>
                    <div class="card-body p-3">
                        <p class="info-label">Order #</p>
                        <p class="info-value text-break">{{ $delivery->order->order_number ?? 'N/A' }}</p>

                        <p class="info-label">Status</p>
                        <p class="info-value">
                            @php
                                $statusBadgeClass = match ($delivery->status) {
                                    'pending' => 'badge-pending',
                                    'assigned' => 'badge-ready',
                                    'picked_up' => 'badge-picked_up',
                                    'out_for_delivery' => 'badge-out_for_delivery',
                                    'in_transit' => 'badge-out_for_delivery',
                                    'delivered' => 'badge-delivered',
                                    'failed' => 'badge-delivery_failed',
                                    'delivery_failed' => 'badge-delivery_failed',
                                    default => 'badge-secondary',
                                };
                                $displayDeliveryStatus = ucfirst(str_replace('_', ' ', $delivery->status));
                                if ($delivery->status == 'in_transit') {
                                    $displayDeliveryStatus = 'Out for Delivery';
                                }
                            @endphp
                            <span class="badge {{ $statusBadgeClass }}">
                                {{ $displayDeliveryStatus }}
                            </span>
                        </p>

                        <!-- Driver Information -->
                        <div class="info-label">Driver</div>
                        <p class="info-value">
                            @if ($delivery->driver)
                                <i class="bi bi-person-badge text-primary me-1"></i>
                                {{ $delivery->driver->name }}
                            @elseif ($delivery->notes)
                                <i class="bi bi-person-badge text-primary me-1"></i>
                                {{ $delivery->notes }}
                            @else
                                <span class="text-muted">Not Assigned</span>
                            @endif
                        </p>

                        @if ($delivery->driver && $delivery->driver->phone)
                            <div class="info-label">Driver Contact</div>
                            <p class="info-value">
                                <i class="bi bi-telephone text-primary me-1"></i>
                                {{ $delivery->driver->phone }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Customer Details (Right) -->
            <div class="col-md-6">
                <div class="info-card">
                    <div class="card-header-custom">
                        <h6><i class="bi bi-person"></i> Customer Details</h6>
                    </div>
                    <div class="card-body p-3">
                        <p class="info-label">Name</p>
                        <p class="info-value">{{ $delivery->recipient_name }}</p>

                        <p class="info-label">Phone</p>
                        <p class="info-value">{{ $delivery->recipient_phone }}</p>

                        <p class="info-label">Address</p>
                        <p class="info-value">{{ $delivery->delivery_address }}</p>

                        @if ($delivery->order)
                            <p class="info-label">City/Barangay</p>
                            <p class="info-value">{{ $delivery->order->city ?? 'N/A' }},
                                {{ $delivery->order->barangay ?? 'N/A' }}</p>
                            @if ($delivery->order->landmark)
                                <p class="info-label">Landmark</p>
                                <p class="info-value">{{ $delivery->order->landmark }}</p>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Delivery Progress + Lalamove Tracking - ONE ROW -->
        <div class="row g-3">
            <!-- Delivery Progress (Left) -->
            <div class="col-md-6">
                <div class="info-card">
                    <div class="card-header-custom">
                        <h6><i class="bi bi-clock-history"></i> Delivery Progress</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="timeline-container">
                            @php
                                $deliveryStatusOrder = [
                                    'pending' => 0,
                                    'assigned' => 1,
                                    'picked_up' => 2,
                                    'out_for_delivery' => 3,
                                    'in_transit' => 3,
                                    'delivered' => 4,
                                    'delivery_failed' => 99,
                                    'failed' => 99,
                                ];

                                $currentDeliveryStatus = $delivery->status;
                                $currentDeliveryLevel = $deliveryStatusOrder[$currentDeliveryStatus] ?? 0;

                                $isDeliveryCompleted = function ($level) use ($currentDeliveryLevel) {
                                    return $currentDeliveryLevel >= $level;
                                };

                                $isCurrentStep = function ($level) use ($currentDeliveryLevel) {
                                    return $currentDeliveryLevel == $level;
                                };

                                $formatDate = function ($date) {
                                    return $date ? \Carbon\Carbon::parse($date)->format('M d, Y h:i A') : null;
                                };
                            @endphp

                            <!-- Assigned to Driver -->
                            <div class="timeline-item">
                                <div class="timeline-icon {{ $isDeliveryCompleted(1) ? 'completed' : ($isCurrentStep(1) ? 'current' : 'pending') }}">
                                    <i class="bi bi-person-check"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-title">Assigned to Driver</div>
                                    @if ($delivery->assigned_at)
                                        <div class="timeline-date">{{ $formatDate($delivery->assigned_at) }}</div>
                                    @elseif ($isDeliveryCompleted(1))
                                        <div class="timeline-date">Assigned</div>
                                    @else
                                        <div class="timeline-date text-muted">Pending</div>
                                    @endif
                                    @if ($delivery->driver)
                                        <div class="timeline-details">
                                            <i class="bi bi-person-badge me-1"></i> Driver:
                                            {{ $delivery->driver->name }}
                                        </div>
                                    @endif
                                </div>
                                <div class="timeline-line {{ $isDeliveryCompleted(2) ? 'completed' : '' }}"></div>
                            </div>

                            <!-- Picked Up -->
                            <div class="timeline-item">
                                <div class="timeline-icon {{ $isDeliveryCompleted(2) ? 'completed' : ($isCurrentStep(2) ? 'current' : 'pending') }}">
                                    <i class="bi bi-box-seam"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-title">Picked Up</div>
                                    @if ($delivery->picked_up_at)
                                        <div class="timeline-date">{{ $formatDate($delivery->picked_up_at) }}</div>
                                    @elseif ($isDeliveryCompleted(2))
                                        <div class="timeline-date">Picked Up</div>
                                    @else
                                        <div class="timeline-date text-muted">Waiting</div>
                                    @endif
                                </div>
                                <div class="timeline-line {{ $isDeliveryCompleted(3) ? 'completed' : '' }}"></div>
                            </div>

                            <!-- Out for Delivery -->
                            <div class="timeline-item">
                                <div class="timeline-icon {{ $isDeliveryCompleted(3) ? 'completed' : ($isCurrentStep(3) ? 'current' : 'pending') }}">
                                    <i class="bi bi-truck"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-title">Out for Delivery</div>
                                    @if ($delivery->out_for_delivery_at)
                                        <div class="timeline-date">{{ $formatDate($delivery->out_for_delivery_at) }}</div>
                                    @elseif ($delivery->in_transit_at)
                                        <div class="timeline-date">{{ $formatDate($delivery->in_transit_at) }}</div>
                                    @elseif ($isDeliveryCompleted(3))
                                        <div class="timeline-date">Out for Delivery</div>
                                    @else
                                        <div class="timeline-date text-muted">Waiting</div>
                                    @endif
                                </div>
                                <div class="timeline-line {{ $isDeliveryCompleted(4) ? 'completed' : '' }}"></div>
                            </div>

                            <!-- Delivered -->
                            <div class="timeline-item">
                                <div class="timeline-icon {{ $isDeliveryCompleted(4) ? 'completed' : ($isCurrentStep(4) ? 'current' : 'pending') }}">
                                    <i class="bi bi-flag-fill"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-title">Delivered</div>
                                    @if ($delivery->delivered_at)
                                        <div class="timeline-date">{{ $formatDate($delivery->delivered_at) }}</div>
                                    @elseif ($isDeliveryCompleted(4))
                                        <div class="timeline-date">Delivered</div>
                                    @else
                                        <div class="timeline-date text-muted">Waiting</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lalamove Tracking / Proofs (Right) -->
            <div class="col-md-6">
                @php
                    $cityLower = strtolower(trim($delivery->order->city ?? ''));
                    $isCalambaCity = $cityLower === 'calamba city' || $cityLower === 'calamba';
                    $isLalamoveEligible = !$isCalambaCity;
                @endphp

                <!-- LALAMOVE TRACKING CARD (VIEW ONLY) -->
                @if ($isLalamoveEligible && !empty($delivery->tracking_number))
                    <div class="info-card" style="border: 1px solid #0d6efd;">
                        <div class="card-header-custom bg-primary bg-opacity-10">
                            <h6 class="text-primary"><i class="bi bi-truck"></i> Lalamove Tracking</h6>
                        </div>
                        <div class="card-body p-3">
                            <p class="info-label">Lalamove Tracking Link</p>
                            <p class="info-value">
                                <a href="{{ $delivery->tracking_number }}" target="_blank"
                                    class="text-primary text-break">
                                    <i class="bi bi-box-arrow-up-right me-1"></i> View Tracking Link
                                </a>
                            </p>
                            @if ($delivery->notes)
                                <p class="info-label">Lalamove Driver Name</p>
                                <p class="info-value">{{ $delivery->notes }}</p>
                            @endif
                        </div>
                    </div>
                @endif

                @if ($delivery->status == 'delivered' && ($delivery->delivery_proof || $delivery->payment_proof))
                    <!-- Proof Images -->
                    <div class="info-card">
                        <div class="card-header-custom">
                            <h6><i class="bi bi-image"></i> Proof of Delivery</h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="row g-2">
                                @if ($delivery->delivery_proof)
                                    <div class="col-md-6">
                                        <p class="info-label mb-2">Delivery Proof</p>
                                        <img src="{{ Storage::url($delivery->delivery_proof) }}" class="proof-image"
                                            onclick="window.showImagePreview('{{ Storage::url($delivery->delivery_proof) }}', 'Delivery Proof')">
                                        <div class="mt-2 text-center">
                                            <a href="{{ Storage::url($delivery->delivery_proof) }}" download
                                                class="btn btn-sm btn-outline-primary rounded-pill">
                                                <i class="bi bi-download"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                @endif
                                @if ($delivery->payment_proof)
                                    <div class="col-md-6">
                                        <p class="info-label mb-2">Payment Proof</p>
                                        <img src="{{ Storage::url($delivery->payment_proof) }}" class="proof-image"
                                            onclick="window.showImagePreview('{{ Storage::url($delivery->payment_proof) }}', 'Payment Proof')">
                                        <div class="mt-2 text-center">
                                            <a href="{{ Storage::url($delivery->payment_proof) }}" download
                                                class="btn btn-sm btn-outline-success rounded-pill">
                                                <i class="bi bi-download"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>