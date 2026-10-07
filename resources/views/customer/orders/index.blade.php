@extends('layouts.customer')

@section('content')
    <div class="container orders-container">
        <h2 class="mb-4 orders-title"><i class="bi bi-receipt"></i> My Orders</h2>

        @if ($orders->count())
            @foreach ($orders as $order)
                <div class="card shadow-sm border-0 mb-3 order-card">
                    <div class="card-body order-card-body">
                        <div class="row align-items-center order-row">
                            <div class="col-md-3 order-product-col">
                                <small class="text-muted order-label">Order #</small>
                                <div class="order-number"><strong>{{ $order->order_number }}</strong></div>

                                {{-- Product Image & Name --}}
                                @php
                                    $firstItem = $order->items->first();
                                    $imageUrl = null;
                                    if ($firstItem) {
                                        $inventory = \App\Models\BranchInventory::with('product')->find(
                                            $firstItem->inventory_id,
                                        );
                                        if ($inventory && $inventory->product && $inventory->product->image) {
                                            $imageUrl = \Storage::url($inventory->product->image);
                                        }
                                    }
                                @endphp

                                <div class="d-flex align-items-center gap-2 mt-1 order-product-info">
                                    @if ($imageUrl)
                                        <img src="{{ $imageUrl }}" alt="Product" class="order-product-img"
                                            style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;">
                                    @else
                                        <div class="order-product-img order-product-placeholder"
                                            style="width: 50px; height: 50px; background: #f8f9fa; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #adb5bd;">
                                            <i class="bi bi-image" style="font-size: 1.5rem;"></i>
                                        </div>
                                    @endif
                                    <div class="order-product-details">
                                        <strong>{{ $firstItem->product->name ?? 'Order Items' }}</strong>

                                        {{-- Display the Flavor/Variant below the name --}}
                                        @if ($firstItem && $firstItem->flavor)
                                            <br><small class="text-muted">Variant: {{ $firstItem->flavor->name }}</small>
                                        @endif

                                        @if ($order->items->count() > 1)
                                            <br><small class="text-muted">+ {{ $order->items->count() - 1 }} more
                                                item(s)</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- Date Column -->
                            <div class="col-md-2 order-date-col">
                                <small class="text-muted order-label">Date of Order</small>
                                <div class="fw-semibold order-value">{{ $order->created_at->format('M d, Y') }}</div>
                            </div>

                            <!-- Delivery Date Column (No Cut Off) -->
                            <div class="col-md-2 order-delivery-col">
                                <small class="text-muted order-label">Delivery Date</small>
                                <div class="fw-semibold order-delivery-value"
                                    style="white-space: nowrap; font-size: 0.75rem; color: #0d6efd;">
                                    @php
                                        $deliveryFrom = $order->delivery_date_from
                                            ? \Carbon\Carbon::parse($order->delivery_date_from)
                                            : null;
                                        $deliveryTo = $order->delivery_date_to
                                            ? \Carbon\Carbon::parse($order->delivery_date_to)
                                            : null;

                                        if ($deliveryFrom && $deliveryTo) {
                                            if ($deliveryFrom->eq($deliveryTo)) {
                                                $deliveryDisplay = $deliveryFrom->format('M d, Y');
                                            } else {
                                                $deliveryDisplay =
                                                    $deliveryFrom->format('M d, Y') .
                                                    ' – ' .
                                                    $deliveryTo->format('M d, Y');
                                            }
                                        } elseif ($deliveryFrom) {
                                            $deliveryDisplay = $deliveryFrom->format('M d, Y');
                                        } elseif ($deliveryTo) {
                                            $deliveryDisplay = $deliveryTo->format('M d, Y');
                                        } elseif ($order->delivery_date) {
                                            $deliveryDisplay = $order->delivery_date->format('M d, Y');
                                        } else {
                                            $deliveryDisplay = 'Pending';
                                        }
                                    @endphp
                                    {{ $deliveryDisplay }}
                                </div>
                            </div>


                            <div class="col-md-2 order-total-col">
                                <small class="text-muted order-label">Total</small>
                                <div class="fw-bold text-danger order-total-value">
                                    ₱{{ number_format($order->total_amount, 2) }}</div>
                            </div>
                            <div class="col-md-1 order-status-col">
                                <small class="text-muted order-label">Status</small>
                                <div>
                                    {{-- ✅ UPDATED: readable label (no underscore) + red style for failed delivery --}}
                                    @php $isDeliveryFailed = $order->order_status === 'delivery_failed'; @endphp
                                    <span
                                        class="badge {{ $isDeliveryFailed ? 'badge-delivery-failed' : $order->order_status_badge_class }}">
                                        @if ($isDeliveryFailed)
                                            <i class="bi bi-x-circle-fill me-1"></i>
                                        @endif
                                        {{ $isDeliveryFailed ? 'Delivery Failed' : $order->order_status_label }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-2 text-md-end order-actions-col">
                                <div class="d-flex justify-content-end align-items-center gap-1 order-actions"
                                    style="white-space: nowrap;">
                                    <a href="{{ route('customer.orders.show', $order) }}"
                                        class="btn btn-outline-primary rounded-pill btn-sm view-details-btn"
                                        style="white-space: nowrap;">View Details</a>

                                    {{-- ONLY ALLOW CANCELLATION IF STATUS IS STRICTLY 'pending' --}}
                                    @if ($order->order_status === 'pending')
                                        <button type="button" class="btn btn-outline-danger rounded-pill btn-sm cancel-btn"
                                            style="white-space: nowrap;" data-bs-toggle="modal"
                                            data-bs-target="#cancelOrderModal{{ $order->id }}">
                                            Cancel
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- ============================================================ --}}
                {{-- CANCEL MODAL --}}
                {{-- ============================================================ --}}
                @if ($order->order_status === 'pending')
                    <div class="modal fade" id="cancelOrderModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content cancel-modal-content"
                                style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                                <div class="modal-header cancel-modal-header"
                                    style="border-bottom: 1px solid #eef2f6; padding: 1.25rem 1.5rem;">
                                    <h5 class="modal-title fw-bold" style="color: #dc3545;">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i> Cancel Order
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body cancel-modal-body" style="padding: 1.5rem;">
                                    <p class="mb-0">Are you sure you want to cancel <strong>Order
                                            #{{ $order->order_number }}</strong>?</p>
                                    <p class="text-muted small mt-2">This action cannot be undone.</p>
                                </div>
                                <div class="modal-footer cancel-modal-footer"
                                    style="border-top: 1px solid #eef2f6; padding: 1rem 1.5rem;">
                                    <button type="button" class="btn btn-secondary rounded-pill px-4"
                                        data-bs-dismiss="modal">Close</button>

                                    {{-- THE ACTUAL CANCEL FORM INSIDE THE MODAL --}}
                                    <form action="{{ route('customer.orders.cancel', $order) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger rounded-pill px-4">
                                            <i class="bi bi-check-circle me-1"></i> Yes, Cancel Order
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                {{-- ============================================================ --}}
            @endforeach
            <div class="orders-pagination">
                {{ $orders->links() }}
            </div>
        @else
            <div class="text-center py-5 bg-white rounded-4 shadow-sm empty-orders">
                <i class="bi bi-inbox display-1 text-muted"></i>
                <h3 class="mt-3">No orders yet</h3>
                <a href="{{ route('customer.products.index') }}" class="btn btn-primary rounded-pill mt-3">Start
                    Shopping</a>
            </div>
        @endif
    </div>

    <style>
        /* ✅ NEW: Delivery Failed status badge (soft red, one line) */
        .badge-delivery-failed {
            background-color: #fee2e2 !important;
            color: #dc2626 !important;
            border: 1px solid #fecaca;
            font-weight: 600;
            white-space: nowrap;
            line-height: 1.3;
            text-align: left;
        }

        /* Laptop/desktop: slightly smaller so it fits the Status column */
        @media (min-width: 992px) {
            .badge-delivery-failed {
                font-size: 0.68rem;
                padding: 0.3rem 0.5rem;
            }
        }

        /* Tablet only: the Status column is very narrow, so allow wrapping there */
        @media (min-width: 768px) and (max-width: 991.98px) {
            .badge-delivery-failed {
                white-space: normal;
            }
        }

        /* ===== MOBILE APP-LIKE STYLES ===== */
        @media (max-width: 767.98px) {
            .orders-container {
                padding-left: 14px;
                padding-right: 14px;
            }

            /* Header */
            .orders-title {
                font-size: 1.15rem;
                margin-bottom: 1rem !important;
                display: flex;
                align-items: center;
                gap: 0.4rem;
            }

            .orders-title i {
                color: #0d6efd;
                font-size: 1.2rem;
            }

            /* Order Card */
            .order-card {
                border-radius: 16px !important;
                overflow: hidden;
                box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04) !important;
                transition: transform 0.15s ease;
            }

            .order-card:active {
                transform: scale(0.99);
            }

            .order-card-body {
                padding: 0.95rem 1rem;
            }

            /* Turn row into a card-like block layout */
            .order-row {
                display: block !important;
            }

            .order-row>[class*="col-"] {
                width: 100% !important;
                max-width: 100% !important;
                flex: 0 0 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            /* Product section */
            .order-product-col {
                padding-bottom: 0.75rem !important;
                margin-bottom: 0.75rem !important;
                border-bottom: 1px solid #f1f5f9;
                position: relative;
            }

            .order-label {
                font-size: 0.68rem;
                text-transform: uppercase;
                letter-spacing: 0.4px;
                font-weight: 600;
                color: #94a3b8 !important;
                display: block;
                margin-bottom: 0.15rem;
            }

            .order-number {
                font-size: 0.85rem;
                color: #1a1a2e;
                margin-bottom: 0.5rem;
            }

            .order-number strong {
                font-size: 0.9rem;
                word-break: break-all;
            }

            .order-product-info {
                gap: 0.7rem !important;
                margin-top: 0 !important;
            }

            .order-product-img,
            .order-product-placeholder {
                width: 52px !important;
                height: 52px !important;
                border-radius: 10px !important;
                flex-shrink: 0;
            }

            .order-product-placeholder i {
                font-size: 1.25rem !important;
            }

            .order-product-details {
                min-width: 0;
                flex: 1;
            }

            .order-product-details strong {
                font-size: 0.9rem;
                color: #1a1a2e;
                display: block;
                line-height: 1.3;
                margin-bottom: 0.15rem;
                word-break: break-word;
            }

            .order-product-details small {
                font-size: 0.72rem;
                line-height: 1.35;
                color: #64748b !important;
            }

            /* Info rows (Date, Delivery Date, Total, Status) - 2-column grid */
            .order-date-col,
            .order-delivery-col,
            .order-total-col,
            .order-status-col {
                display: inline-block !important;
                width: 50% !important;
                max-width: 50% !important;
                flex: 0 0 50% !important;
                padding: 0.4rem 0.4rem 0.4rem 0 !important;
                vertical-align: top;
            }

            .order-date-col,
            .order-delivery-col {
                margin-bottom: 0.25rem;
            }

            .order-value,
            .order-delivery-value,
            .order-total-value {
                font-size: 0.82rem;
                line-height: 1.3;
            }

            .order-delivery-value {
                font-size: 0.75rem !important;
                white-space: normal !important;
                word-break: break-word;
            }

            .order-total-value {
                font-size: 0.95rem !important;
            }

            .order-status-col .badge {
                font-size: 0.68rem;
                padding: 0.28rem 0.55rem;
            }

            /* Actions row - full width at bottom */
            .order-actions-col {
                width: 100% !important;
                max-width: 100% !important;
                flex: 0 0 100% !important;
                margin-top: 0.65rem;
                padding-top: 0.75rem !important;
                border-top: 1px solid #f1f5f9;
                text-align: left !important;
            }

            .order-actions {
                display: flex !important;
                gap: 0.5rem !important;
                width: 100%;
                justify-content: stretch !important;
                flex-wrap: nowrap;
            }

            .order-actions .btn {
                flex: 1;
                padding: 0.55rem 0.5rem;
                font-size: 0.78rem;
                font-weight: 500;
                display: flex;
                align-items: center;
                justify-content: center;
                white-space: nowrap !important;
            }

            .order-actions .btn:active {
                transform: scale(0.97);
            }

            /* When only "View Details" exists (no cancel), make it full width */
            .order-actions .btn:only-child {
                flex: 1;
            }

            /* Modal - Mobile App Style */
            .cancel-modal-content {
                border-radius: 20px !important;
                margin: 1rem;
            }

            .cancel-modal-header {
                padding: 1rem 1.15rem !important;
            }

            .cancel-modal-header .modal-title {
                font-size: 1rem !important;
            }

            .cancel-modal-body {
                padding: 1.15rem !important;
                font-size: 0.88rem;
            }

            .cancel-modal-body p {
                line-height: 1.5;
            }

            .cancel-modal-body .text-muted {
                font-size: 0.78rem !important;
            }

            .cancel-modal-footer {
                padding: 0.9rem 1.15rem !important;
                gap: 0.5rem;
                flex-wrap: nowrap;
            }

            .cancel-modal-footer .btn {
                flex: 1;
                padding: 0.6rem 0.75rem;
                font-size: 0.82rem;
                font-weight: 500;
            }

            /* Pagination */
            .orders-pagination {
                margin-top: 1rem;
            }

            .orders-pagination .pagination {
                justify-content: center;
                flex-wrap: wrap;
                gap: 0.25rem;
            }

            .orders-pagination .page-link {
                font-size: 0.82rem;
                padding: 0.45rem 0.7rem;
                border-radius: 10px !important;
                margin: 0;
            }

            /* Empty state */
            .empty-orders {
                padding: 3rem 1.25rem !important;
                border-radius: 16px !important;
            }

            .empty-orders i.display-1 {
                font-size: 3rem !important;
            }

            .empty-orders h3 {
                font-size: 1.15rem;
            }

            .empty-orders .btn {
                padding: 0.6rem 1.35rem;
                font-size: 0.85rem;
            }
        }

        /* Extra small devices */
        @media (max-width: 380px) {
            .orders-title {
                font-size: 1.05rem;
            }

            .order-number strong {
                font-size: 0.85rem;
            }

            .order-product-details strong {
                font-size: 0.85rem;
            }

            .order-actions .btn {
                font-size: 0.72rem;
                padding: 0.5rem 0.4rem;
            }

            .order-value,
            .order-delivery-value {
                font-size: 0.78rem;
            }

            .order-total-value {
                font-size: 0.88rem !important;
            }
        }

        /* Tablet */
        @media (min-width: 768px) and (max-width: 991.98px) {
            .order-card-body {
                padding: 1rem;
            }

            .order-product-img,
            .order-product-placeholder {
                width: 45px !important;
                height: 45px !important;
            }

            .order-actions .btn {
                font-size: 0.75rem;
                padding: 0.4rem 0.6rem;
            }

            .order-delivery-value {
                font-size: 0.7rem !important;
            }
        }

        /* Touch device: remove hover transforms */
        @media (hover: none) {
            .order-card:hover {
                transform: none;
            }

            .order-actions .btn:hover {
                transform: none;
            }
        }
    </style>
@endsection
