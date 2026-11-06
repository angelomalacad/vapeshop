<!-- resources/views/driver/online-orders/show.blade.php -->
<style>
    .modal-body-custom {
        padding: 0;
        height: 85vh;
        /* Changed from max-height to height to fix double scrollbars */
        overflow-y: auto;
        overflow-x: hidden;
    }

    /* ✅ ADDED: Hide the scrollbar in the Order Items table */
    .table-responsive {
        scrollbar-width: none;
        /* Firefox */
        -ms-overflow-style: none;
        /* IE and Edge */
    }

    .table-responsive::-webkit-scrollbar {
        display: none;
        /* Chrome, Safari, Opera */
    }

    .modal-header-custom {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #eef2f6;
        background: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .order-number {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1a1a2e;
        margin: 0;
    }

    .order-date {
        font-size: 0.75rem;
        color: #64748b;
        margin: 0;
    }

    .info-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        margin-bottom: 1rem;
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
        margin: 0;
    }

    .product-image {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 10px;
        background: #f8f9fa;
    }

    .order-items-table {
        margin-bottom: 0;
    }

    .order-items-table th {
        background: #f8f9fa;
        font-weight: 600;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #eef2f6;
    }

    .order-items-table td {
        padding: 0.75rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #eef2f6;
        font-size: 0.8rem;
        color: #334155;
    }

    .product-name {
        font-weight: 600;
        color: #1a1a2e;
        margin: 0;
    }

    .product-flavor {
        font-size: 0.7rem;
        color: #64748b;
    }

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
    }

    .status-btn {
        width: 100%;
        padding: 0.75rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        border: none;
        margin-bottom: 0.5rem;
        cursor: pointer;
    }

    .status-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .btn-delivery {
        background: #1a1a2e;
        color: white;
    }

    .btn-delivery:hover {
        background: #16213e;
    }

    .btn-close-modal {
        position: absolute;
        top: 20px;
        right: 20px;
        background: transparent;
        border: none;
        font-size: 24px;
        cursor: pointer !important;
        color: #666;
        padding: 0;
        z-index: 99999 !important;
    }

    .btn-close-modal:hover {
        color: #333;
    }

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

    .alert-warning-custom {
        background: #fef3c7;
        border: 1px solid #fde68a;
        color: #92400e;
    }

    .totals-row {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 1rem;
    }

    .totals-align-fixed .totals-label {
        margin-left: 14rem;
    }

    .totals-align-fixed .totals-value {
        margin-right: 5rem;
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

    .badge-ready {
        background: #d1fae5;
        color: #059669;
    }

    .badge-pending {
        background: #f1f5f9;
        color: #475569;
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

    .delivery-date-range {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .delivery-date-range .form-control {
        border-radius: 8px;
        font-size: 0.8rem;
    }

    .stock-info {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        padding: 0.4rem 0.6rem;
        border-radius: 8px;
        font-size: 0.75rem;
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
        font-size: 0.9rem;
    }

    @media (max-width: 767.98px) {
        .modal-body-custom {
            height: 88vh;
        }

        .modal-body-custom>div {
            padding: 1.25rem 1rem !important;
        }

        .btn-close-modal {
            top: 14px;
            right: 14px;
            font-size: 28px;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #f1f5f9;
            transition: all 0.2s ease;
        }

        .btn-close-modal:active {
            background: #e2e8f0;
            transform: scale(0.94);
        }

        .modal-header-custom {
            padding: 0 0 0.75rem 0 !important;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.25rem;
        }

        .order-number {
            font-size: 1.05rem;
            line-height: 1.3;
            word-break: break-all;
            padding-right: 2.5rem;
        }

        .order-date {
            font-size: 0.72rem;
        }

        .info-card {
            border-radius: 14px;
            margin-bottom: 0.75rem;
        }

        .card-header-custom {
            padding: 0.75rem 1rem;
        }

        .card-header-custom h6 {
            font-size: 0.82rem;
        }

        .info-card .card-body,
        .info-card>.p-3 {
            padding: 0.9rem !important;
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
        }

        .order-items-table td:nth-child(1) {
            padding-bottom: 0.5rem;
            padding-right: 0;
        }

        .order-items-table .d-flex {
            gap: 0.7rem !important;
        }

        .product-image {
            width: 48px !important;
            height: 48px !important;
            border-radius: 10px !important;
            flex-shrink: 0;
        }

        .product-name {
            font-size: 0.88rem;
            line-height: 1.3;
            word-break: break-word;
        }

        .product-flavor {
            font-size: 0.7rem;
        }

        .order-items-table td:nth-child(2),
        .order-items-table td:nth-child(3),
        .order-items-table td:nth-child(4) {
            display: inline-block;
            width: auto;
            padding-right: 0.75rem;
            font-size: 0.78rem;
            color: #475569;
        }

        .order-items-table td:nth-child(2)::before {
            content: 'Qty: ';
            font-weight: 600;
            color: #94a3b8;
        }

        .order-items-table td:nth-child(3)::before {
            content: '@ ';
            font-weight: 500;
            color: #94a3b8;
        }

        .order-items-table td:nth-child(4) {
            float: right;
            padding-right: 0;
            font-weight: 700;
            color: #1a1a2e;
            font-size: 0.85rem;
        }

        .order-items-table td:nth-child(5) {
            padding-top: 0.5rem;
            text-align: left !important;
        }

        .order-items-table td:nth-child(5) .stock-info {
            display: inline-flex;
            justify-content: flex-start;
            padding: 0.35rem 0.65rem;
            font-size: 0.72rem;
            margin-top: 0.15rem;
        }

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

        .info-card .row>.col-6 {
            flex: 0 0 100%;
            max-width: 100%;
        }

        .status-btn {
            padding: 0.85rem;
            font-size: 0.88rem;
            border-radius: 12px;
            margin-bottom: 0.5rem;
        }

        .status-btn:active {
            transform: scale(0.98);
            box-shadow: none;
        }

        .alert-custom {
            padding: 0.85rem 1rem;
            font-size: 0.82rem;
            border-radius: 12px;
            line-height: 1.5;
        }

        .delivery-date-range {
            flex-wrap: wrap;
            gap: 0.4rem;
        }

        .delivery-date-range .form-control {
            flex: 1;
            min-width: 0;
            font-size: 0.78rem;
            padding: 0.5rem 0.65rem;
            border-radius: 10px;
            min-height: 40px;
        }

        .delivery-date-range>span {
            font-size: 0.72rem;
            color: #94a3b8;
        }

        #saveDeliveryDateBtn {
            width: 100%;
            padding: 0.65rem;
            font-size: 0.85rem;
            font-weight: 600;
            border-radius: 10px;
            margin-top: 0.6rem !important;
        }

        #saveDeliveryDateBtn:active {
            transform: scale(0.98);
        }

        #result .alert {
            font-size: 0.8rem;
            padding: 0.65rem 0.85rem;
            border-radius: 10px;
        }
    }

    @media (max-width: 767.98px) {
        .order-items-table tbody tr {
            display: grid !important;
            grid-template-columns: calc(52px + 0.5rem) auto 1fr auto !important;
            grid-template-areas:
                "prod  prod  prod  prod"
                ".     qty   price total"
                ".     stock stock stock" !important;
            column-gap: 0.75rem !important;
            row-gap: 0.3rem !important;
            align-items: center !important;
            padding: 0.9rem 0 !important;
            border-bottom: 1px solid #eef2f6 !important;
        }

        .order-items-table tbody tr:last-child {
            border-bottom: 0 !important;
        }

        .order-items-table tbody tr td {
            display: block !important;
            width: auto !important;
            min-width: 0 !important;
            float: none !important;
            margin: 0 !important;
            padding: 0 !important;
            border: 0 !important;
            text-align: left !important;
            vertical-align: top !important;
        }

        .order-items-table tbody tr td:nth-child(1) {
            grid-area: prod;
        }

        .order-items-table tbody tr td:nth-child(2),
        .order-items-table tbody tr td:nth-child(3) {
            font-size: 0.8rem !important;
            color: #64748b !important;
            white-space: nowrap;
        }

        .order-items-table tbody tr td:nth-child(2) {
            grid-area: qty;
        }

        .order-items-table tbody tr td:nth-child(2)::before {
            content: 'Qty: ' !important;
        }

        .order-items-table tbody tr td:nth-child(3) {
            grid-area: price;
        }

        .order-items-table tbody tr td:nth-child(3)::before {
            content: 'Price: ' !important;
        }

        .order-items-table tbody tr td:nth-child(4) {
            grid-area: total;
            justify-self: end !important;
            text-align: right !important;
            font-size: 0.95rem !important;
            font-weight: 700 !important;
            color: #1a1a2e !important;
            white-space: nowrap;
        }

        .order-items-table tbody tr td:nth-child(4)::before {
            content: none !important;
        }

        .order-items-table tbody tr td:nth-child(5) {
            grid-area: stock;
            justify-self: start !important;
        }

        .order-items-table tbody tr td:nth-child(5) .stock-info {
            display: inline-flex !important;
            flex-direction: row !important;
            flex-wrap: wrap !important;
            align-items: center !important;
            gap: 0.2rem 0.4rem !important;
            width: auto !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0.3rem 0.7rem !important;
            border-radius: 999px !important;
            font-size: 0.72rem !important;
            line-height: 1.2 !important;
        }

        .order-items-table tbody tr td:nth-child(5) .stock-info span,
        .order-items-table tbody tr td:nth-child(5) .stock-info small {
            margin: 0 !important;
        }

        .order-items-table .product-image {
            width: 52px !important;
            height: 52px !important;
            flex-shrink: 0;
            border-radius: 10px !important;
            object-fit: cover;
        }
    }
</style>

<div class="modal-body-custom">
    <div style="padding: 1.5rem; position: relative;">
        <button type="button" class="btn-close-modal" onclick="window.closeModal()">&times;</button>

        <div class="modal-header-custom" style="padding: 0 0 1rem 0;">
            <div>
                <h5 class="order-number">Order #{{ $order->order_number }}</h5>
                <p class="order-date">Placed on {{ $order->created_at->format('M d, Y h:i A') }}</p>
            </div>
        </div>

        <div class="row g-3">
            <!-- LEFT COLUMN -->
            <div class="col-md-7">
                <div class="info-card">
                    <div class="card-header-custom">
                        <h6><i class="bi bi-receipt"></i> Order Items</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table order-items-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-center">Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->items as $item)
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

                                        $stockAvailable = 0;
                                        $stockStatus = 'out_of_stock';
                                        $stockClass = 'stock-info-out';
                                        $stockIcon = 'bi-x-circle-fill';
                                        $stockColor = 'text-danger';

                                        if ($product) {
                                            $branchInventory = \App\Models\BranchInventory::where(
                                                'id',
                                                $item->inventory_id,
                                            )->first();

                                            if ($branchInventory) {
                                                $stockAvailable = $branchInventory->available_quantity;

                                                if ($stockAvailable <= 0) {
                                                    $stockStatus = 'out_of_stock';
                                                    $stockClass = 'stock-info-out';
                                                    $stockIcon = 'bi-x-circle-fill';
                                                    $stockColor = 'text-danger';
                                                } elseif ($stockAvailable <= $branchInventory->low_stock_threshold) {
                                                    $stockStatus = 'low_stock';
                                                    $stockClass = 'stock-info-low';
                                                    $stockIcon = 'bi-exclamation-triangle-fill';
                                                    $stockColor = 'text-warning';
                                                } else {
                                                    $stockStatus = 'in_stock';
                                                    $stockClass = 'stock-info-in';
                                                    $stockIcon = 'bi-check-circle-fill';
                                                    $stockColor = 'text-success';
                                                }
                                            }
                                        }
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                @if ($imageUrl)
                                                    <img src="{{ $imageUrl }}" alt="{{ $product->name }}"
                                                        class="product-image">
                                                @else
                                                    <div
                                                        class="product-image bg-light d-flex align-items-center justify-content-center">
                                                        <i class="bi bi-box-seam text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="product-name">{{ $item->product->name }}</div>
                                                    @if ($item->flavor)
                                                        <div class="product-flavor">Flavor: {{ $item->flavor->name }}
                                                        </div>
                                                    @endif

                                                    {{-- ✅ Show pickup branch and readiness conditionally --}}
                                                    <div class="product-flavor mt-1" style="font-size: 0.7rem;">
                                                        <i class="bi bi-shop"></i>
                                                        <strong>{{ $item->inventory->branch->name ?? 'Unknown' }}</strong>

                                                        {{-- Only show the badge if the order is still in 'ready' state --}}
                                                        @if ($order->order_status === 'ready')
                                                            @php
                                                                $itemStatus = $item->status ?? 'pending';
                                                            @endphp
                                                            <span
                                                                class="badge {{ $itemStatus === 'ready' ? 'badge-ready' : 'badge-pending' }} ms-1"
                                                                style="font-size: 0.65rem;">
                                                                {{ $itemStatus === 'ready' ? 'Ready for Pickup' : 'Still Packing' }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">₱{{ number_format($item->price, 2) }}</td>
                                        <td class="text-end">₱{{ number_format($item->subtotal, 2) }}</td>
                                        <td class="text-center">
                                            <div class="stock-info {{ $stockClass }}">
                                                <i
                                                    class="bi {{ $stockIcon }} {{ $stockColor }} stock-info-icon"></i>
                                                <span class="fw-semibold">{{ $stockAvailable }}</span>
                                                <small class="ms-1">({{ $item->quantity }} needed)</small>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3 bg-light">
                        <div class="totals-row totals-align-fixed">
                            <span class="totals-label">Subtotal</span>
                            <span class="totals-value">₱{{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="totals-row totals-align-fixed">
                            <span class="totals-label">Delivery Fee</span>
                            <span class="totals-value">₱{{ number_format($order->delivery_fee ?? 0, 2) }}</span>
                        </div>
                        <div class="totals-row totals-total totals-align-fixed">
                            <span class="totals-label">Total</span>
                            <span class="totals-value">₱{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="info-card">
                    <div class="card-header-custom">
                        <h6><i class="bi bi-person"></i> Customer Information</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-6">
                                <p class="info-label">Name</p>
                                <p class="info-value">{{ $order->customer_name }}</p>
                                <p class="info-label">Phone</p>
                                <p class="info-value">{{ $order->customer_phone }}</p>
                            </div>
                            <div class="col-6">
                                <p class="info-label">Email</p>
                                <p class="info-value">{{ $order->customer_email ?? 'N/A' }}</p>
                                @if ($order->delivery_type == 'delivery')
                                    <p class="info-label">Address</p>
                                    <p class="info-value">{{ $order->delivery_address }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN -->
            <div class="col-md-5">
                <!-- Update Status -->
                <div class="info-card">
                    <div class="card-header-custom">
                        <h6><i class="bi bi-arrow-repeat"></i> Update Status</h6>
                    </div>
                    <div class="card-body p-3">
                        @php
                            $deliveryStatus = $order->delivery ? $order->delivery->status : null;
                            $orderStatus = $order->order_status;

                            $statusMap = [
                                'assigned' => 'ready',
                                'picked_up' => 'picked_up',
                                'out_for_delivery' => 'out_for_delivery',
                                'delivered' => 'delivered',
                                'delivery_failed' => 'delivery_failed',
                            ];

                            if (isset($statusMap[$deliveryStatus])) {
                                $orderStatus = $statusMap[$deliveryStatus];
                            }

                            $statusClass = match ($orderStatus) {
                                'ready' => 'badge-ready',
                                'out_for_delivery' => 'badge-out_for_delivery',
                                'picked_up' => 'badge-picked_up',
                                'in_transit' => 'badge-out_for_delivery',
                                'delivered' => 'badge-delivered',
                                'delivery_failed' => 'badge-delivery_failed',
                                'cancelled' => 'badge-cancelled',
                                default => 'badge-secondary',
                            };

                            $displayStatus = ucfirst(str_replace('_', ' ', $orderStatus));
                            if ($orderStatus == 'delivery_failed') {
                                $displayStatus = 'Delivery Failed';
                            }

                            // ✅ Check if ALL items are ready based on their individual status
                            $allItemsReady = !$order->items->where('status', '!=', 'ready')->count();
                        @endphp

                        <div class="mb-3">
                            <p class="info-label">Current Status</p>
                            <span class="badge {{ $statusClass }}">{{ $displayStatus }}</span>
                        </div>

                        @if ($orderStatus == 'ready')
                            @if ($allItemsReady)
                                <button type="button" class="status-btn btn-delivery"
                                    onclick="handleStatus('start-delivery', {{ $order->id }})">
                                    <i class="bi bi-truck me-2"></i> Start Delivery
                                </button>
                            @else
                                <div class="alert-custom alert-warning-custom mb-3">
                                    <i class="bi bi-clock-history me-2"></i>
                                    <strong>Waiting for All Branches</strong><br>
                                    <small class="text-muted">
                                        Some items are still being packed. You can only start delivery once all branches
                                        are ready.
                                    </small>
                                </div>
                                <button type="button" class="status-btn btn-delivery" disabled
                                    style="opacity: 0.5; cursor: not-allowed;">
                                    <i class="bi bi-truck me-2"></i> Start Delivery (Waiting...)
                                </button>
                            @endif
                        @elseif(in_array($orderStatus, ['picked_up', 'out_for_delivery']))
                            <div class="alert-custom alert-info-custom mb-3">
                                <i class="bi bi-truck me-2"></i>
                                <strong>Delivery in Progress</strong><br>
                                <small class="text-muted">
                                    Status: {{ $displayStatus }}<br>
                                    Update delivery status below.
                                </small>
                            </div>
                            @if ($order->delivery)
                                <button type="button" class="status-btn btn-delivery"
                                    onclick="showDeliveryStatusModal({{ json_encode($order->delivery->load('order.items.product', 'order.branch')) }})">
                                    <i class="bi bi-truck me-2"></i> Manage Delivery
                                </button>
                            @endif
                        @elseif($orderStatus == 'delivered')
                            <div class="alert-custom alert-success-custom text-center">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                <strong>Order Completed</strong><br>
                                <small class="text-muted">Delivered on
                                    {{ $order->updated_at->format('M d, Y h:i A') }}</small>
                            </div>
                        @elseif($orderStatus == 'delivery_failed')
                            <div class="alert-custom alert-warning-custom text-center">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <strong>Delivery Failed</strong>
                            </div>
                        @endif

                        @if (!in_array($orderStatus, ['delivered', 'delivery_failed']))
                            <div class="mb-3">
                                <label class="info-label">Expected Delivery Date</label>
                                <div class="delivery-date-range">
                                    <input type="date" name="delivery_date_from" id="delivery_date_from"
                                        class="form-control"
                                        value="{{ $order->delivery_date_from ? \Carbon\Carbon::parse($order->delivery_date_from)->format('Y-m-d') : '' }}">
                                    <span class="text-muted">to</span>
                                    <input type="date" name="delivery_date_to" id="delivery_date_to"
                                        class="form-control"
                                        value="{{ $order->delivery_date_to ? \Carbon\Carbon::parse($order->delivery_date_to)->format('Y-m-d') : '' }}">
                                </div>
                                <button type="button" class="btn btn-primary btn-sm mt-2" id="saveDeliveryDateBtn"
                                    onclick="saveDeliveryDate()">
                                    Save
                                </button>
                            </div>
                        @endif

                        <div id="result" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.closeModal = function() {
        const modal = document.getElementById('customModal');
        if (modal) modal.style.display = 'none';
        const content = document.getElementById('customModalContent');
        if (content) content.innerHTML = '';
    };

    document.addEventListener('DOMContentLoaded', function() {
        const successMessage = sessionStorage.getItem('delivery_success_message');
        if (successMessage) {
            if (typeof showNotification === 'function') {
                showNotification(successMessage, 'success');
            }
            sessionStorage.removeItem('delivery_success_message');
        }

        const deliveryDateFrom = document.getElementById('delivery_date_from');
        const deliveryDateTo = document.getElementById('delivery_date_to');

        if (deliveryDateFrom && deliveryDateTo) {
            deliveryDateFrom.min = new Date().toISOString().split('T')[0];
            deliveryDateFrom.addEventListener('change', function() {
                deliveryDateTo.min = this.value;
                if (deliveryDateTo.value < this.value) {
                    deliveryDateTo.value = this.value;
                }
            });
            if (deliveryDateFrom.value) {
                deliveryDateTo.min = deliveryDateFrom.value;
            }
        }
    });

    window.handleStatus = function(action, orderId) {
        const resultDiv = document.getElementById('result');
        const button = event.target.closest('button');
        if (!button) return;

        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Processing...';

        let url = '';
        switch (action) {
            case 'start-delivery':
                url = `/driver/online-orders/${orderId}/start-delivery`;
                break;
            default:
                return;
        }

        fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    sessionStorage.setItem('delivery_success_message', data.message ||
                        'Status updated successfully!');
                    window.location.reload();
                } else {
                    if (typeof showNotification === 'function') {
                        showNotification(data.message || 'Error occurred', 'error');
                    } else {
                        alert(data.message || 'Error occurred');
                    }
                    button.disabled = false;
                    button.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (typeof showNotification === 'function') {
                    showNotification('Network error. Please try again.', 'error');
                } else {
                    alert('Network error. Please try again.');
                }
                button.disabled = false;
                button.innerHTML = originalText;
            });
    };

    function saveDeliveryDate() {
        const orderId = {{ $order->id }};
        const deliveryDateFrom = document.getElementById('delivery_date_from').value;
        const deliveryDateTo = document.getElementById('delivery_date_to').value;
        const resultDiv = document.getElementById('result');

        if (!deliveryDateFrom || !deliveryDateTo) {
            if (resultDiv) resultDiv.innerHTML =
                '<div class="alert alert-danger">Please select both From and To dates.</div>';
            return;
        }

        if (deliveryDateTo < deliveryDateFrom) {
            if (resultDiv) resultDiv.innerHTML =
                '<div class="alert alert-danger">To date cannot be before From date.</div>';
            return;
        }

        const saveBtn = document.getElementById('saveDeliveryDateBtn');
        const originalBtnText = 'Save';
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';

        if (resultDiv) resultDiv.innerHTML = '<div class="alert alert-info">Saving delivery dates...</div>';

        fetch(`{{ url('/driver/online-orders') }}/${orderId}/delivery-date`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    delivery_date_from: deliveryDateFrom,
                    delivery_date_to: deliveryDateTo
                })
            })
            .then(response => response.json())
            .then(data => {
                saveBtn.disabled = false;
                saveBtn.innerHTML = originalBtnText;

                if (data.success) {
                    if (resultDiv) resultDiv.innerHTML = '<div class="alert alert-success">' + (data.message ||
                        'Delivery dates saved successfully!') + '</div>';
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    if (resultDiv) resultDiv.innerHTML = '<div class="alert alert-danger">' + (data.message ||
                        'Error saving delivery dates') + '</div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                saveBtn.disabled = false;
                saveBtn.innerHTML = originalBtnText;
                if (resultDiv) resultDiv.innerHTML =
                    '<div class="alert alert-danger">Network error. Please try again.</div>';
            });
    }
</script>
