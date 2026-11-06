<!-- resources/views/driver/deliveries/show-modal.blade.php -->
<div id="deliveryStatusModal" style="display:none;">
    <style>
        #deliveryStatusModal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 99999;
            display: none;
            justify-content: center;
            align-items: center;
            overflow-y: auto;
            padding: 20px;
        }

        #deliveryStatusModal .modal-body-custom {
            background: white;
            width: 95%;
            max-width: 1200px;
            max-height: 90vh;
            overflow-y: auto;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .modal-body-custom {
            padding: 0;
            max-height: 85vh;
            overflow-y: auto;
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

        .totals-row.totals-under-total {
            justify-content: flex-end;
            gap: 2rem;
            padding-right: 9.5rem;
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

        .badge {
            display: inline-block;
            padding: 0.35rem 0.65rem;
            border-radius: 30px;
            font-weight: 600;
            font-size: 0.7rem;
            text-transform: capitalize;
            line-height: 1;
        }

        .badge-ready {
            background-color: #d1fae5 !important;
            color: #059669 !important;
        }

        .badge-out_for_delivery {
            background-color: #fef3c7 !important;
            color: #d97706 !important;
        }

        .badge-picked_up {
            background-color: #dbeafe !important;
            color: #2563eb !important;
        }

        .badge-in_transit {
            background-color: #e0e7ff !important;
            color: #4f46e5 !important;
        }

        .badge-delivered {
            background-color: #d1fae5 !important;
            color: #059669 !important;
        }

        .badge-delivery_failed {
            background-color: #fee2e2 !important;
            color: #dc2626 !important;
        }

        .badge-cancelled {
            background-color: #fee2e2 !important;
            color: #dc2626 !important;
        }

        .badge-pending {
            background-color: #f1f5f9 !important;
            color: #475569 !important;
        }

        .badge-assigned {
            background-color: #e0f2fe !important;
            color: #0369a1 !important;
        }

        .badge-secondary {
            background-color: #f1f5f9 !important;
            color: #475569 !important;
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

        .lalamove-tracking-section {
            background: #f0f7ff;
            border: 1px solid #dbeafe;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .lalamove-tracking-section .info-label {
            color: #1e40af;
        }

        .lalamove-tracking-input {
            border-radius: 8px;
            border: 1px solid #bfdbfe;
            padding: 0.5rem 0.75rem;
            font-size: 0.8rem;
            width: 100%;
            background: white;
        }

        .lalamove-tracking-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        .btn-save-tracking {
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-top: 0.5rem;
            width: 100%;
        }

        .btn-save-tracking:hover {
            background: #2563eb;
            transform: translateY(-1px);
        }

        .btn-save-tracking:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .tracking-link-display {
            font-size: 0.75rem;
            word-break: break-all;
            margin-top: 0.5rem;
        }

        .btn-save-tracking.saved {
            background: #10b981;
            cursor: default;
        }

        .btn-save-tracking.saved:hover {
            background: #10b981;
            transform: none;
        }

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

        @media (max-width: 767.98px) {
            #deliveryStatusModal {
                align-items: flex-end;
                padding: 0;
                background: rgba(0, 0, 0, 0.55);
            }

            #deliveryStatusModal .modal-body-custom {
                width: 100% !important;
                max-width: 100% !important;
                max-height: 94vh !important;
                border-radius: 24px 24px 0 0 !important;
                animation: deliverySheetUp 0.3s ease-out;
                padding-bottom: env(safe-area-inset-bottom);
            }

            @keyframes deliverySheetUp {
                from {
                    transform: translateY(100%);
                }

                to {
                    transform: translateY(0);
                }
            }

            #deliveryStatusModal .modal-body-custom>div {
                padding: 1.25rem 1rem !important;
            }

            #deliveryStatusModal .modal-body-custom::before {
                content: '';
                display: block;
                width: 40px;
                height: 4px;
                background: #cbd5e1;
                border-radius: 2px;
                margin: 10px auto 4px;
            }

            .btn-close-modal {
                top: 16px;
                right: 14px;
                font-size: 26px;
                width: 34px;
                height: 34px;
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
                flex-direction: row;
                justify-content: flex-start;
                gap: 0.5rem;
                padding: 0.35rem 0.65rem;
                font-size: 0.7rem;
                margin-top: 0.15rem;
            }

            .order-items-table td:nth-child(5) .stock-info span {
                white-space: nowrap;
            }

            .info-card .p-3.bg-light {
                padding: 0.75rem 1rem !important;
            }

            .totals-row.totals-under-total {
                padding-right: 0 !important;
                justify-content: space-between !important;
                gap: 0 !important;
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

            #deliveryStatusModal .form-control,
            #deliveryStatusModal .form-select,
            #deliveryStatusModal .lalamove-tracking-input {
                border-radius: 10px;
                font-size: 16px !important;
                padding: 0.6rem 0.75rem;
                border: 1.5px solid #e2e8f0;
                min-height: 42px;
            }

            #deliveryStatusModal .form-control:focus,
            #deliveryStatusModal .form-select:focus,
            #deliveryStatusModal .lalamove-tracking-input:focus {
                border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            }

            #deliveryStatusModal textarea.form-control {
                font-size: 16px !important;
                min-height: 80px;
            }

            .lalamove-tracking-section {
                padding: 0.85rem;
                border-radius: 12px;
            }

            .btn-save-tracking {
                padding: 0.7rem 1rem;
                font-size: 0.88rem;
                font-weight: 600;
                border-radius: 12px;
                min-height: 44px;
            }

            .btn-save-tracking:active {
                transform: scale(0.98);
            }

            #delivery-status-result .alert,
            #lalamove_tracking_result .alert {
                font-size: 0.8rem;
                padding: 0.7rem 0.9rem;
                border-radius: 10px;
                margin-bottom: 0;
            }

            .tracking-link-display {
                font-size: 0.78rem;
            }

            #deliveryStatusModal input[type="file"].form-control {
                padding: 0.5rem;
                font-size: 0.8rem !important;
                min-height: 42px;
            }

            #deliveryStatusModal textarea {
                font-size: 16px !important;
            }
        }

        @media (max-width: 380px) {
            .order-number {
                font-size: 0.95rem;
            }

            .product-image {
                width: 42px !important;
                height: 42px !important;
            }

            .product-name {
                font-size: 0.82rem;
            }

            .info-value {
                font-size: 0.78rem;
            }

            .status-btn {
                font-size: 0.82rem;
                padding: 0.75rem;
            }

            .btn-save-tracking {
                font-size: 0.82rem;
                padding: 0.65rem 0.85rem;
            }
        }

        @media (hover: none) {
            .status-btn:hover {
                transform: none;
                box-shadow: none;
            }

            .btn-save-tracking:hover {
                transform: none;
            }

            .btn-close-modal:hover {
                background: #f1f5f9;
            }
        }

        @supports (padding-bottom: env(safe-area-inset-bottom)) {
            @media (max-width: 767.98px) {
                #deliveryStatusModal .modal-body-custom {
                    padding-bottom: env(safe-area-inset-bottom);
                }
            }
        }
    </style>

    <div class="modal-body-custom">
        <div style="padding: 1.5rem; position: relative;">
            <button type="button" class="btn-close-modal" onclick="window.closeDeliveryModal()">&times;</button>

            <div class="modal-header-custom" style="padding: 0 0 1rem 0;">
                <div>
                    <h5 class="order-number">Delivery Details</h5>
                    <p class="order-date">Update delivery status</p>
                </div>
            </div>

            <div id="delivery-status-result" class="mb-3"></div>

            @if (isset($delivery))
                <div class="row g-3">
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
                                    <tbody id="modal-items-list">
                                        @php
                                            $orderData = $delivery->order ?? null;
                                            $subtotal = 0;
                                        @endphp

                                        @if ($orderData && $orderData->items->count() > 0)
                                            @foreach ($orderData->items as $item)
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

                                                    $quantity = $item->quantity;
                                                    $price = $item->price;
                                                    $itemTotal = $quantity * $price;
                                                    $subtotal += $itemTotal;

                                                    $stockAvailable = 0;
                                                    $stockReserved = 0;
                                                    $stockTotal = 0;
                                                    $stockClass = 'stock-info-out';
                                                    $stockIcon = 'bi-x-circle-fill';
                                                    $stockColor = 'text-danger';

                                                    $branchInventory = \App\Models\BranchInventory::withoutGlobalScopes()
                                                        ->where('id', $item->inventory_id)
                                                        ->first();
                                                    if ($branchInventory) {
                                                        $stockAvailable = $branchInventory->available_quantity;
                                                        $stockReserved = $branchInventory->reserved_quantity;
                                                        $stockTotal = $branchInventory->quantity;

                                                        if ($stockAvailable <= 0) {
                                                            $stockClass = 'stock-info-out';
                                                            $stockIcon = 'bi-x-circle-fill';
                                                            $stockColor = 'text-danger';
                                                        } elseif (
                                                            $stockAvailable <= $branchInventory->low_stock_threshold
                                                        ) {
                                                            $stockClass = 'stock-info-low';
                                                            $stockIcon = 'bi-exclamation-triangle-fill';
                                                            $stockColor = 'text-warning';
                                                        } else {
                                                            $stockClass = 'stock-info-in';
                                                            $stockIcon = 'bi-check-circle-fill';
                                                            $stockColor = 'text-success';
                                                        }
                                                    }
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            @if ($imageUrl)
                                                                <img src="{{ $imageUrl }}"
                                                                    alt="{{ $product->name }}" class="product-image">
                                                            @else
                                                                <div
                                                                    class="product-image bg-light d-flex align-items-center justify-content-center">
                                                                    <i class="bi bi-box-seam text-muted"></i>
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <div class="product-name">{{ $product->name }}</div>
                                                                @if ($item->flavor)
                                                                    <div class="product-flavor">Flavor:
                                                                        {{ $item->flavor->name }}</div>
                                                                @endif

                                                                {{-- ✅ Added Pickup Branch Name --}}
                                                                <div class="product-flavor mt-1"
                                                                    style="font-size: 0.7rem;">
                                                                    <i class="bi bi-shop"></i>
                                                                    <strong>{{ $branchInventory?->branch?->name ?? 'Unknown Branch' }}</strong>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">{{ $quantity }}</td>
                                                    <td class="text-end">₱{{ number_format($price, 2) }}</td>
                                                    <td class="text-end">₱{{ number_format($itemTotal, 2) }}</td>
                                                    <td class="text-center">
                                                        <div class="stock-info {{ $stockClass }}">
                                                            <i
                                                                class="bi {{ $stockIcon }} {{ $stockColor }} stock-info-icon"></i>
                                                            <span><strong>Avail:</strong> {{ $stockAvailable }}</span>
                                                            <span><strong>Reserved:</strong>
                                                                {{ $stockReserved }}</span>
                                                            <span><strong>Total:</strong> {{ $stockTotal }}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="5" class="text-center">No items found</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="p-3 bg-light">
                                <div class="totals-row totals-under-total">
                                    <span class="totals-label">Subtotal</span>
                                    <span class="totals-value"
                                        id="modal-subtotal">₱{{ number_format($subtotal, 2) }}</span>
                                </div>
                                <div class="totals-row totals-total totals-under-total">
                                    <span class="totals-label">Total</span>
                                    <span class="totals-value"
                                        id="modal-total">₱{{ number_format($orderData->total_amount ?? 0, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="card-header-custom">
                                <h6><i class="bi bi-truck"></i> Delivery Information</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-6">
                                        <p class="info-label">Status</p>
                                        <p class="info-value" id="modal-current-status">
                                            @php
                                                $status = $delivery->status ?? 'pending';
                                                $statusClass = 'badge-' . $status;
                                            @endphp
                                            <span
                                                class="badge {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
                                        </p>
                                    </div>
                                    <div class="col-6">
                                        <p class="info-label">Order #</p>
                                        <p class="info-value">{{ $orderData->order_number ?? 'N/A' }}</p>
                                        <p class="info-label">Picked Up</p>
                                        <p class="info-value">
                                            {{ $delivery->picked_up_at ? \Carbon\Carbon::parse($delivery->picked_up_at)->format('M d, Y h:i A') : '-' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="card-header-custom">
                                <h6><i class="bi bi-person"></i> Customer Information</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-6">
                                        <p class="info-label">Name</p>
                                        <p class="info-value">{{ $orderData->customer_name ?? 'N/A' }}</p>
                                        <p class="info-label">Phone</p>
                                        <p class="info-value">{{ $orderData->customer_phone ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-6">
                                        <p class="info-label">Address</p>
                                        <p class="info-value">{{ $orderData->delivery_address ?? 'N/A' }}</p>
                                        <p class="info-label">City</p>
                                        <p class="info-value">{{ $orderData->city ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        @php
                            $isLalamove =
                                $delivery->is_lalamove ||
                                ($orderData &&
                                    $orderData->city &&
                                    $orderData->city !== 'Calamba' &&
                                    $orderData->city !== 'Calamba City');
                        @endphp

                        <div class="info-card" id="lalamove-tracking-section"
                            style="display: {{ $isLalamove ? 'block' : 'none' }};">
                            <div class="card-header-custom">
                                <h6><i class="bi bi-truck"></i> Lalamove Tracking</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="lalamove-tracking-section">
                                    <div class="mb-3">
                                        <label class="info-label">Tracking Link</label>
                                        <input type="url" class="lalamove-tracking-input"
                                            id="lalamove_tracking_link" placeholder="https://lalamove.com/track/..."
                                            value="{{ $delivery->tracking_number ?? '' }}">
                                        <small class="text-muted d-block mt-1">Paste the Lalamove tracking URL</small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="info-label">Lalamove Driver Name</label>
                                        <input type="text" class="lalamove-tracking-input"
                                            id="lalamove_driver_name" placeholder="Enter driver name"
                                            value="{{ $delivery->notes ?? '' }}">
                                    </div>

                                    <button type="button" class="btn-save-tracking" id="saveLalamoveTrackingBtn"
                                        onclick="saveLalamoveTracking()">
                                        <i class="bi bi-save me-1"></i> Save Tracking Info
                                    </button>

                                    <div id="lalamove_tracking_result" class="mt-2"></div>

                                    @if ($delivery->tracking_number && str_starts_with($delivery->tracking_number, 'http'))
                                        <div id="existing-tracking-display" style="display:block;" class="mt-3">
                                            <label class="info-label">Current Tracking Link</label>
                                            <div class="tracking-link-display">
                                                <a href="{{ $delivery->tracking_number }}"
                                                    id="existing-tracking-link" target="_blank" class="text-primary">
                                                    <i class="bi bi-box-arrow-up-right me-1"></i> View Tracking Link
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <div id="existing-tracking-display" style="display:none;" class="mt-3">
                                            <label class="info-label">Current Tracking Link</label>
                                            <div class="tracking-link-display">
                                                <a href="#" id="existing-tracking-link" target="_blank"
                                                    class="text-primary">
                                                    <i class="bi bi-box-arrow-up-right me-1"></i> View Tracking Link
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- ✅ FIXED: Hide update form if status is already delivered or failed --}}
                        @if (!in_array($delivery->status, ['delivered', 'failed']))
                            <div class="info-card">
                                <div class="card-header-custom">
                                    <h6><i class="bi bi-arrow-repeat"></i> Update Delivery Status</h6>
                                </div>
                                <div class="card-body p-3">
                                    <form id="statusUpdateForm" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" id="delivery_id" name="delivery_id"
                                            value="{{ $delivery->id }}">

                                        <div class="mb-3">
                                            <label class="info-label">Select Status</label>
                                            <select class="form-select" id="status" name="status" required>
                                                <option value="">-- Select Status --</option>
                                                <option value="picked_up">Picked Up</option>
                                                <option value="out_for_delivery">Out for Delivery</option>
                                                <option value="delivered">Delivered</option>
                                                <option value="failed">Failed</option>
                                            </select>
                                        </div>

                                        <div id="delivery-proof-section" style="display:none;">
                                            <div class="mb-3">
                                                <label class="info-label">Delivery Proof Photo *</label>
                                                <input type="file" class="form-control" id="delivery_proof"
                                                    name="delivery_proof" accept="image/*">
                                                <small class="text-muted">Max size: 5MB. Allowed: JPEG, PNG,
                                                    JPG</small>
                                            </div>
                                        </div>

                                        <div id="payment-proof-section" style="display:none;">
                                            <div class="mb-3">
                                                <label class="info-label">Payment Proof Photo *</label>
                                                <input type="file" class="form-control" id="payment_proof"
                                                    name="payment_proof" accept="image/*">
                                                <small class="text-muted">Max size: 5MB. Allowed: JPEG, PNG,
                                                    JPG</small>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="info-label">Notes / Reason</label>
                                            <textarea class="form-control" id="notes" name="notes" rows="3"
                                                placeholder="Enter any notes or reason for status update...">{{ $delivery->driver_notes ?? '' }}</textarea>
                                        </div>

                                        <button type="submit" class="btn-save-tracking" id="submitStatusBtn">
                                            <i class="fas fa-check-circle"></i> Update Delivery Status
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            {{-- Read-Only Alert when finalized --}}
                            <div class="info-card">
                                <div class="card-header-custom">
                                    <h6><i class="bi bi-info-circle"></i> Delivery Finalized</h6>
                                </div>
                                <div class="card-body p-3 text-center">
                                    <div class="alert alert-{{ $delivery->status === 'failed' ? 'danger' : 'success' }} mb-0"
                                        style="border-radius: 12px;">
                                        <i class="bi bi-{{ $delivery->status === 'failed' ? 'x-circle-fill' : 'check-circle-fill' }} me-2"
                                            style="font-size: 1.2rem;"></i>
                                        This delivery has already been marked as
                                        <strong>{{ ucfirst($delivery->status) }}</strong> and cannot be edited.
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="info-card">
                            <div class="card-header-custom">
                                <h6><i class="bi bi-clock-history"></i> Delivery Timeline</h6>
                            </div>
                            <div class="card-body p-3">
                                <p class="info-label">Picked Up</p>
                                <p class="info-value">
                                    {{ $delivery->picked_up_at ? \Carbon\Carbon::parse($delivery->picked_up_at)->format('M d, Y h:i A') : '-' }}
                                </p>
                                <p class="info-label">Out for Delivery</p>
                                <p class="info-value">
                                    {{ $delivery->out_for_delivery_at || $delivery->in_transit_at ? \Carbon\Carbon::parse($delivery->out_for_delivery_at ?? $delivery->in_transit_at)->format('M d, Y h:i A') : '-' }}
                                </p>
                                <p class="info-label">Delivered</p>
                                <p class="info-value">
                                    {{ $delivery->delivered_at ? \Carbon\Carbon::parse($delivery->delivered_at)->format('M d, Y h:i A') : '-' }}
                                </p>
                                @if ($delivery->failed_at)
                                    <p class="info-label text-danger">Failed At</p>
                                    <p class="info-value text-danger">
                                        {{ \Carbon\Carbon::parse($delivery->failed_at)->format('M d, Y h:i A') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @else
                {{-- Fallback for AJAX (The JS will handle the logic here) --}}
                <div class="row g-3">
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
                                    <tbody id="modal-items-list"></tbody>
                                </table>
                            </div>
                            <div class="p-3 bg-light">
                                <div class="totals-row totals-under-total">
                                    <span class="totals-label">Subtotal</span>
                                    <span class="totals-value" id="modal-subtotal">₱0.00</span>
                                </div>
                                <div class="totals-row totals-total totals-under-total">
                                    <span class="totals-label">Total</span>
                                    <span class="totals-value" id="modal-total">₱0.00</span>
                                </div>
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="card-header-custom">
                                <h6><i class="bi bi-truck"></i> Delivery Information</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-6">
                                        <p class="info-label">Status</p>
                                        <p class="info-value" id="modal-current-status"></p>
                                    </div>
                                    <div class="col-6">
                                        <p class="info-label">Order #</p>
                                        <p class="info-value" id="modal-order-number"></p>
                                        <p class="info-label">Picked Up</p>
                                        <p class="info-value" id="modal-picked-up-date"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="card-header-custom">
                                <h6><i class="bi bi-person"></i> Customer Information</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-6">
                                        <p class="info-label">Name</p>
                                        <p class="info-value" id="modal-customer-name"></p>
                                        <p class="info-label">Phone</p>
                                        <p class="info-value" id="modal-customer-phone"></p>
                                    </div>
                                    <div class="col-6">
                                        <p class="info-label">Address</p>
                                        <p class="info-value" id="modal-customer-address"></p>
                                        <p class="info-label">City</p>
                                        <p class="info-value" id="modal-customer-city"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="info-card" id="lalamove-tracking-section" style="display:none;">
                            <div class="card-header-custom">
                                <h6><i class="bi bi-truck"></i> Lalamove Tracking</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="lalamove-tracking-section">
                                    <div class="mb-3">
                                        <label class="info-label">Tracking Link</label>
                                        <input type="url" class="lalamove-tracking-input"
                                            id="lalamove_tracking_link" placeholder="https://lalamove.com/track/..."
                                            value="">
                                        <small class="text-muted d-block mt-1">Paste the Lalamove tracking URL</small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="info-label">Lalamove Driver Name</label>
                                        <input type="text" class="lalamove-tracking-input"
                                            id="lalamove_driver_name" placeholder="Enter driver name" value="">
                                    </div>
                                    <button type="button" class="btn-save-tracking" id="saveLalamoveTrackingBtn"
                                        onclick="saveLalamoveTracking()">
                                        <i class="bi bi-save me-1"></i> Save Tracking Info
                                    </button>
                                    <div id="lalamove_tracking_result" class="mt-2"></div>
                                    <div id="existing-tracking-display" style="display:none;" class="mt-3">
                                        <label class="info-label">Current Tracking Link</label>
                                        <div class="tracking-link-display">
                                            <a href="#" id="existing-tracking-link" target="_blank"
                                                class="text-primary">
                                                <i class="bi bi-box-arrow-up-right me-1"></i> View Tracking Link
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="card-header-custom">
                                <h6><i class="bi bi-arrow-repeat"></i> Update Delivery Status</h6>
                            </div>
                            <div class="card-body p-3">
                                <form id="statusUpdateForm" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" id="delivery_id" name="delivery_id">
                                    <div class="mb-3">
                                        <label class="info-label">Select Status</label>
                                        <select class="form-select" id="status" name="status" required>
                                            <option value="">-- Select Status --</option>
                                            <option value="picked_up">Picked Up</option>
                                            <option value="out_for_delivery">Out for Delivery</option>
                                            <option value="delivered">Delivered</option>
                                            <option value="failed">Failed</option>
                                        </select>
                                    </div>
                                    <div id="delivery-proof-section" style="display:none;">
                                        <div class="mb-3">
                                            <label class="info-label">Delivery Proof Photo *</label>
                                            <input type="file" class="form-control" id="delivery_proof"
                                                name="delivery_proof" accept="image/*">
                                            <small class="text-muted">Max size: 5MB. Allowed: JPEG, PNG, JPG</small>
                                        </div>
                                    </div>
                                    <div id="payment-proof-section" style="display:none;">
                                        <div class="mb-3">
                                            <label class="info-label">Payment Proof Photo *</label>
                                            <input type="file" class="form-control" id="payment_proof"
                                                name="payment_proof" accept="image/*">
                                            <small class="text-muted">Max size: 5MB. Allowed: JPEG, PNG, JPG</small>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="info-label">Notes / Reason</label>
                                        <textarea class="form-control" id="notes" name="notes" rows="3"
                                            placeholder="Enter any notes or reason for status update..."></textarea>
                                    </div>
                                    <button type="submit" class="btn-save-tracking" id="submitStatusBtn">
                                        <i class="fas fa-check-circle"></i> Update Delivery Status
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="card-header-custom">
                                <h6><i class="bi bi-clock-history"></i> Delivery Timeline</h6>
                            </div>
                            <div class="card-body p-3">
                                <p class="info-label">Picked Up</p>
                                <p class="info-value" id="modal-picked-up-date"></p>
                                <p class="info-label">Out for Delivery</p>
                                <p class="info-value" id="modal-out-for-delivery-date"></p>
                                <p class="info-label">Delivered</p>
                                <p class="info-value" id="modal-delivered-date"></p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    // ================================================================
    // Inject server-rendered modal HTML (used when JS payload lacks
    // inventory data — server-render has correct stock values)
    // ================================================================
    function injectServerRenderedModal(html) {
        const old = document.getElementById('deliveryStatusModal');
        if (old) {
            old.outerHTML = html;
        }
        const newEl = document.getElementById('deliveryStatusModal');
        if (newEl) {
            newEl.style.display = 'flex';
            document.body.classList.add('modal-open');
        }
        if (typeof window.bindFormListeners === 'function') {
            window.bindFormListeners();
        }
    }

    // ================================================================
    // Main modal display function
    // ================================================================
    window.showDeliveryStatusModal = function(deliveryData) {
        if (!deliveryData || !deliveryData.id) {
            console.error('showDeliveryStatusModal: invalid deliveryData');
            return;
        }

        if (typeof window.closeModal === 'function') {
            window.closeModal();
        }

        // 🔑 KEY FIX:
        // If the caller's payload doesn't include inventory data on items,
        // the client-side render would show 0 / 0 / 0 (because item.inventory
        // is undefined). Instead, fall back to the server-rendered modal —
        // which uses BranchInventory::withoutGlobalScopes() and always shows
        // the correct stock.
        const items = (deliveryData.order && deliveryData.order.items) || [];
        const itemsHaveStock = items.length === 0 || items.every(function(it) {
            return (it.inventory !== undefined && it.inventory !== null) ||
                it.stock_available !== undefined;
        });

        if (items.length > 0 && !itemsHaveStock) {
            // Fetch server-rendered HTML (correct stock)
            fetch(`/driver/deliveries/${deliveryData.id}/modal-data`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(r => {
                    if (!r.ok) throw new Error('HTTP ' + r.status);
                    return r.json();
                })
                .then(data => {
                    if (data.success && data.html) {
                        injectServerRenderedModal(data.html);
                    } else {
                        alert(data.message || 'Failed to load delivery data.');
                    }
                })
                .catch(err => {
                    console.error('showDeliveryStatusModal fallback error:', err);
                    alert('Error loading delivery data: ' + err.message);
                });
            return;
        }

        // ---------- Normal JS render path (payload has inventory) ----------
        const $ = id => document.getElementById(id);
        const setText = (id, v) => {
            const el = $(id);
            if (el) el.textContent = v;
        };
        const setHtml = (id, v) => {
            const el = $(id);
            if (el) el.innerHTML = v;
        };

        const deliveryIdInput = $('delivery_id');
        if (deliveryIdInput) deliveryIdInput.value = deliveryData.id;

        setText('modal-order-number',
            (deliveryData.order && deliveryData.order.order_number) || 'N/A');

        const statusBadges = {
            'pending': '<span style="display:inline-block; padding:4px 10px; border-radius:30px; font-weight:600; font-size:0.7rem; background-color:#f1f5f9; color:#475569; text-transform:capitalize;">Pending</span>',
            'assigned': '<span style="display:inline-block; padding:4px 10px; border-radius:30px; font-weight:600; font-size:0.7rem; background-color:#e0f2fe; color:#0369a1; text-transform:capitalize;">Assigned</span>',
            'picked_up': '<span style="display:inline-block; padding:4px 10px; border-radius:30px; font-weight:600; font-size:0.7rem; background-color:#dbeafe; color:#2563eb; text-transform:capitalize;">Picked Up</span>',
            'out_for_delivery': '<span style="display:inline-block; padding:4px 10px; border-radius:30px; font-weight:600; font-size:0.7rem; background-color:#fef3c7; color:#d97706; text-transform:capitalize;">Out for Delivery</span>',
            'in_transit': '<span style="display:inline-block; padding:4px 10px; border-radius:30px; font-weight:600; font-size:0.7rem; background-color:#fef3c7; color:#d97706; text-transform:capitalize;">Out for Delivery</span>',
            'delivered': '<span style="display:inline-block; padding:4px 10px; border-radius:30px; font-weight:600; font-size:0.7rem; background-color:#d1fae5; color:#059669; text-transform:capitalize;">Delivered</span>',
            'failed': '<span style="display:inline-block; padding:4px 10px; border-radius:30px; font-weight:600; font-size:0.7rem; background-color:#fee2e2; color:#dc2626; text-transform:capitalize;">Failed</span>'
        };
        setHtml('modal-current-status', statusBadges[deliveryData.status] ||
            '<span style="display:inline-block; padding:4px 10px; border-radius:30px; font-weight:600; font-size:0.7rem; background-color:#f1f5f9; color:#475569; text-transform:capitalize;">' +
            deliveryData.status + '</span>');

        function formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        setText('modal-picked-up-date', formatDate(deliveryData.picked_up_at));
        setText('modal-out-for-delivery-date',
            formatDate(deliveryData.out_for_delivery_at || deliveryData.in_transit_at));
        setText('modal-delivered-date', formatDate(deliveryData.delivered_at));

        const order = deliveryData.order || {};
        setText('modal-customer-name', order.customer_name || 'N/A');
        setText('modal-customer-phone', order.customer_phone || 'N/A');
        setText('modal-customer-address', order.delivery_address || 'N/A');
        setText('modal-customer-city', order.city || 'N/A');

        const isLalamove = deliveryData.is_lalamove ||
            (order.city && order.city !== 'Calamba' && order.city !== 'Calamba City');
        const lalamoveTrackingSection = $('lalamove-tracking-section');

        if (lalamoveTrackingSection) {
            if (isLalamove) {
                lalamoveTrackingSection.style.display = 'block';
                const trackLink = $('lalamove_tracking_link');
                const trackName = $('lalamove_driver_name');
                if (trackLink) trackLink.value = deliveryData.tracking_number || '';
                if (trackName) trackName.value = deliveryData.notes || '';

                const saveBtn = $('saveLalamoveTrackingBtn');
                const existingTrackingDisplay = $('existing-tracking-display');
                const existingTrackingLink = $('existing-tracking-link');
                const hasTracking = deliveryData.tracking_number &&
                    deliveryData.tracking_number.startsWith('http');

                if (saveBtn) {
                    saveBtn.innerHTML = hasTracking ?
                        '<i class="bi bi-pencil-square me-1"></i> Update Tracking Info' :
                        '<i class="bi bi-save me-1"></i> Save Tracking Info';
                }
                if (existingTrackingDisplay) {
                    existingTrackingDisplay.style.display = hasTracking ? 'block' : 'none';
                }
                if (existingTrackingLink) {
                    existingTrackingLink.href = hasTracking ? deliveryData.tracking_number : '#';
                    existingTrackingLink.style.pointerEvents = hasTracking ? 'auto' : 'none';
                    existingTrackingLink.style.opacity = hasTracking ? '1' : '0.5';
                }
                setHtml('lalamove_tracking_result', '');
            } else {
                lalamoveTrackingSection.style.display = 'none';
            }
        }

        // Items (payload has inventory — safe to render)
        if (items.length > 0) {
            let itemsHtml = '';
            let subtotal = 0;

            items.forEach(function(item) {
                const productName = item.product ? item.product.name : 'Product';
                const quantity = item.quantity || 0;
                const price = parseFloat(item.price) || 0;
                const itemTotal = quantity * price;
                subtotal += itemTotal;

                const productImage = item.product && item.product.image ? item.product.image : null;
                const imageUrl = productImage ?
                    (productImage.startsWith('http') ? productImage : '/storage/' + productImage) :
                    null;

                const inv = item.inventory || {};
                const avail = item.stock_available ?? inv.available_quantity ?? 0;
                const reserved = item.stock_reserved ?? inv.reserved_quantity ?? 0;
                const totalStock = item.stock_total ?? inv.quantity ?? 0;
                const lowThresh = inv.low_stock_threshold ?? 10;

                let stockClass, stockIcon;
                if (avail <= 0) {
                    stockClass = 'stock-info-out';
                    stockIcon = 'bi-x-circle-fill';
                } else if (avail <= lowThresh) {
                    stockClass = 'stock-info-low';
                    stockIcon = 'bi-exclamation-triangle-fill';
                } else {
                    stockClass = 'stock-info-in';
                    stockIcon = 'bi-check-circle-fill';
                }

                itemsHtml += `
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                ${imageUrl
                                    ? `<img src="${imageUrl}" alt="${productName}" class="product-image">`
                                    : `<div class="product-image bg-light d-flex align-items-center justify-content-center"><i class="bi bi-box-seam text-muted"></i></div>`}
                                <div>
                                    <div class="product-name">${productName}</div>
                                    ${item.flavor ? `<div class="product-flavor">Flavor: ${item.flavor.name}</div>` : ''}
                                    ${inv.branch ? `<div class="product-flavor mt-1" style="font-size: 0.7rem;"><i class="bi bi-shop"></i> <strong>${inv.branch.name}</strong></div>` : ''}
                                </div>
                            </div>
                        </td>
                        <td class="text-center">${quantity}</td>
                        <td class="text-end">₱${price.toFixed(2)}</td>
                        <td class="text-end">₱${itemTotal.toFixed(2)}</td>
                        <td class="text-center">
                            <div class="stock-info ${stockClass}">
                                <i class="bi ${stockIcon} stock-info-icon"></i>
                                <span><strong>Avail:</strong> ${avail}</span>
                                <span><strong>Reserved:</strong> ${reserved}</span>
                                <span><strong>Total:</strong> ${totalStock}</span>
                            </div>
                        </td>
                    </tr>
                `;
            });

            setHtml('modal-items-list', itemsHtml);
            setText('modal-subtotal', '₱' + subtotal.toFixed(2));
            setText('modal-total', '₱' + subtotal.toFixed(2));
        } else {
            setHtml('modal-items-list', '<tr><td colspan="5" class="text-center">No items found</td></tr>');
        }

        // ✅ DISABLE FORM IF FINALIZED
        const statusSelect = $('status');
        const submitBtn = $('submitStatusBtn');
        const form = $('statusUpdateForm');
        const isFinalized = ['delivered', 'failed'].includes(deliveryData.status);

        if (isFinalized) {
            if (form) {
                form.querySelectorAll('input, select, textarea, button').forEach(el => el.disabled = true);
            }
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-lock-fill"></i> Delivery Finalized';
                submitBtn.className = 'btn-save-tracking btn-secondary';
            }
        } else {
            if (statusSelect) statusSelect.value = '';
            if (form) {
                form.querySelectorAll('input, select, textarea, button').forEach(el => el.disabled = false);
            }
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-check-circle"></i> Update Delivery Status';
                submitBtn.className = 'btn-save-tracking';
            }
        }

        const modal = $('deliveryStatusModal');
        if (modal) {
            modal.style.display = 'flex';
            document.body.classList.add('modal-open');
        }

        if (typeof window.bindFormListeners === 'function') {
            window.bindFormListeners();
        }
    };

    // ================================================================
    // Form listeners — extracted so they survive AJAX modal injection
    // ================================================================
    window.bindFormListeners = function() {
        const statusSelect = document.getElementById('status');
        if (statusSelect && !statusSelect.dataset.bound) {
            statusSelect.dataset.bound = '1';
            statusSelect.addEventListener('change', function() {
                const status = this.value;
                const dp = document.getElementById('delivery-proof-section');
                const pp = document.getElementById('payment-proof-section');
                if (dp) dp.style.display = 'none';
                if (pp) pp.style.display = 'none';
                if (status === 'delivered') {
                    if (dp) dp.style.display = 'block';
                    if (pp) pp.style.display = 'block';
                }
                const notes = document.getElementById('notes');
                if (notes) {
                    if (status === 'failed') {
                        notes.placeholder = 'Please provide reason for failed delivery...';
                        notes.required = true;
                    } else {
                        notes.placeholder = 'Enter any notes for status update...';
                        notes.required = false;
                    }
                }
            });
        }

        const form = document.getElementById('statusUpdateForm');
        if (form && !form.dataset.bound) {
            form.dataset.bound = '1';
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // Prevent submission if form is disabled
                if (form.querySelector('button[type="submit"]').disabled) {
                    return;
                }

                const deliveryId = document.getElementById('delivery_id').value;
                const status = document.getElementById('status').value;

                if (!status) {
                    alert('Please select a status.');
                    return;
                }
                if (status === 'delivered') {
                    const dp = document.getElementById('delivery_proof');
                    const pp = document.getElementById('payment_proof');
                    if (!dp || !dp.files.length) {
                        alert('Please upload delivery proof photo.');
                        return;
                    }
                    if (!pp || !pp.files.length) {
                        alert('Please upload payment proof photo.');
                        return;
                    }
                }
                if (status === 'failed') {
                    const n = document.getElementById('notes');
                    if (!n || !n.value) {
                        alert('Please provide a reason for failed delivery.');
                        return;
                    }
                }

                const submitBtn = document.getElementById('submitStatusBtn');
                submitBtn.disabled = true;
                submitBtn.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-1"></span> Updating...';

                const formData = new FormData(form);

                fetch(`/driver/deliveries/${deliveryId}/update-status`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content'),
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const resultDiv = document.getElementById('delivery-status-result');
                            if (resultDiv) {
                                resultDiv.innerHTML = '<div class="alert alert-success">' +
                                    (data.message || 'Delivery status updated successfully!') +
                                    '</div>';
                            }
                            setTimeout(() => {
                                window.closeDeliveryModal();
                                location.reload();
                            }, 1500);
                        } else {
                            const resultDiv = document.getElementById('delivery-status-result');
                            if (resultDiv) {
                                resultDiv.innerHTML = '<div class="alert alert-danger">' +
                                    (data.message || 'Failed to update delivery status.') + '</div>';
                            }
                            submitBtn.disabled = false;
                            submitBtn.innerHTML =
                                '<i class="fas fa-check-circle"></i> Update Delivery Status';
                        }
                    })
                    .catch(error => {
                        const resultDiv = document.getElementById('delivery-status-result');
                        if (resultDiv) {
                            resultDiv.innerHTML =
                                '<div class="alert alert-danger">Error updating delivery status: ' +
                                error.message + '</div>';
                        }
                        submitBtn.disabled = false;
                        submitBtn.innerHTML =
                            '<i class="fas fa-check-circle"></i> Update Delivery Status';
                    });
            });
        }
    };

    // ================================================================
    // Save Lalamove tracking
    // ================================================================
    window.saveLalamoveTracking = function() {
        const deliveryId = document.getElementById('delivery_id').value;
        const trackingLink = document.getElementById('lalamove_tracking_link').value.trim();
        const driverName = document.getElementById('lalamove_driver_name').value.trim();
        const resultDiv = document.getElementById('lalamove_tracking_result');
        const saveBtn = document.getElementById('saveLalamoveTrackingBtn');

        if (!trackingLink) {
            resultDiv.innerHTML =
                '<div class="alert alert-danger alert-minimal mt-2">Please enter a tracking link.</div>';
            return;
        }
        if (!trackingLink.startsWith('http://') && !trackingLink.startsWith('https://')) {
            resultDiv.innerHTML =
                '<div class="alert alert-danger alert-minimal mt-2">Please enter a valid URL.</div>';
            return;
        }

        saveBtn.disabled = true;
        saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';
        resultDiv.innerHTML = '<div class="alert alert-info alert-minimal mt-2">Saving tracking info...</div>';

        const formData = new FormData();
        formData.append('tracking_number', trackingLink);
        formData.append('lalamove_driver_name', driverName);

        fetch(`/driver/deliveries/${deliveryId}/update-lalamove`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    resultDiv.innerHTML = '<div class="alert alert-success alert-minimal mt-2">' +
                        (data.message || 'Tracking info saved successfully!') + '</div>';
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    resultDiv.innerHTML = '<div class="alert alert-danger alert-minimal mt-2">' +
                        (data.message || 'Failed to save tracking info.') + '</div>';
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = '<i class="bi bi-save me-1"></i> Save Tracking Info';
                }
            })
            .catch(error => {
                resultDiv.innerHTML =
                    '<div class="alert alert-danger alert-minimal mt-2">Network error. Please try again.</div>';
                saveBtn.disabled = false;
                saveBtn.innerHTML = '<i class="bi bi-save me-1"></i> Save Tracking Info';
            });
    };

    // ================================================================
    // Open modal by ID (fetches server-rendered HTML — correct stock)
    // ================================================================
    window.openDeliveryModal = function(deliveryId) {
        if (typeof window.closeModal === 'function') {
            window.closeModal();
        }

        fetch(`/driver/deliveries/${deliveryId}/modal-data`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('HTTP ' + response.status);
                return response.json();
            })
            .then(data => {
                if (data.success && data.html) {
                    // Your controller returns {success, html} — inject server-render
                    injectServerRenderedModal(data.html);
                } else if (data.success && data.delivery) {
                    // Legacy JSON path
                    showDeliveryStatusModal(data.delivery);
                } else {
                    alert(data.message || 'Failed to load delivery data.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading delivery data: ' + error.message);
            });
    };

    // ================================================================
    // Close modal
    // ================================================================
    window.closeDeliveryModal = function() {
        const modal = document.getElementById('deliveryStatusModal');
        if (modal) {
            modal.style.display = 'none';
            document.body.classList.remove('modal-open');
        }
    };

    // ================================================================
    // Bind on initial page load
    // ================================================================
    document.addEventListener('DOMContentLoaded', function() {
        window.bindFormListeners();
    });
</script>
