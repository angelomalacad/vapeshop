@extends('layouts.customer')

@section('content')
    <div class="container orders-container">
        
        <h2 class="mb-4 orders-title"><i class="bi bi-receipt"></i> My Orders</h2>

        @if ($orders->count())
            @foreach ($orders as $order)
                <div class="card shadow-sm border-0 mb-3 order-card">
                    <div class="card-body order-card-body">
                        <div class="row align-items-center order-row">
                            
                            <div class="col-lg-3 col-md-3 order-product-col">
                                <small class="text-muted order-label">Order #</small>
                                <div class="order-number"><strong>{{ $order->order_number }}</strong></div>

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

                            <div class="col-lg-2 col-md-2 order-date-col">
                                <small class="text-muted order-label">Date of Order</small>
                                <div class="fw-semibold order-value">{{ $order->created_at->format('M d, Y') }}</div>
                            </div>

                            <div class="col-lg-2 col-md-2 order-delivery-col">
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

                            {{-- ✅ FIXED: Changed to col-lg-2 to fit the total price on one line --}}
                            <div class="col-lg-2 col-md-2 order-total-col">
                                <small class="text-muted order-label">Total</small>
                                <div class="fw-bold text-danger order-total-value" style="white-space: nowrap;">
                                    ₱{{ number_format($order->total_amount, 2) }}</div>
                            </div>
                            
                            <div class="col-lg-1 col-md-1 order-status-col">
                                <small class="text-muted order-label">Status</small>
                                <div>
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
                            
                            <div class="col-lg-2 col-md-2 text-md-end order-actions-col">
                                <div class="d-flex justify-content-end align-items-center gap-1 order-actions"
                                    style="white-space: nowrap;">
                                    <a href="{{ route('customer.orders.show', $order) }}"
                                        class="btn btn-outline-primary rounded-pill btn-sm view-details-btn"
                                        style="white-space: nowrap;">View Details</a>

                                    @if ($order->order_status === 'pending')
                                        
                                        @if($order->items->count() > 1)
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-outline-secondary rounded-pill btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="white-space: nowrap;">
                                                    Items
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="min-width: 250px; border-radius: 12px; padding: 0.5rem;">
                                                    <li><h6 class="dropdown-header px-2">Manage Items</h6></li>
                                                    @foreach($order->items as $item)
                                                        <li>
                                                            <div class="dropdown-item d-flex justify-content-between align-items-center px-2 py-2" style="cursor: default;">
                                                                <div class="d-flex flex-column" style="max-width: 70%;">
                                                                    <span class="fw-semibold text-truncate" style="font-size: 0.85rem;">
                                                                        {{ $item->product->name ?? 'Item' }}
                                                                    </span>
                                                                    @if($item->flavor)
                                                                        <small class="text-muted text-truncate" style="font-size: 0.75rem;">
                                                                            {{ $item->flavor->name }} (x{{ $item->quantity }})
                                                                        </small>
                                                                    @endif
                                                                </div>
                                                                
                                                                <button type="button" 
                                                                    class="btn btn-sm btn-outline-danger btn-icon-circle open-remove-modal-btn" 
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#removeItemModal"
                                                                    data-order-id="{{ $order->id }}"
                                                                    data-item-id="{{ $item->id }}"
                                                                    data-item-name="{{ $item->product->name ?? 'Item' }}"
                                                                    data-item-flavor="{{ $item->flavor->name ?? '' }}"
                                                                    data-item-qty="{{ $item->quantity }}"
                                                                    title="Remove this item">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

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

    <div class="modal fade" id="removeItemModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <div class="modal-header" style="border-bottom: 1px solid #eef2f6; padding: 1.25rem 1.5rem;">
                    <h5 class="modal-title fw-bold" style="color: #1a1a2e;">
                        <i class="bi bi-trash-fill me-2 text-danger"></i> Remove Item
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 1.5rem;">
                    <div class="mb-3 p-3 bg-light rounded" style="border: 1px solid #e2e8f0;">
                        <h6 class="fw-bold mb-1" id="modalItemName" style="color: #1a1a2e;">Product Name</h6>
                        <small class="text-muted" id="modalItemFlavor" style="display:block; margin-bottom: 0.5rem;">Flavor</small>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">In this order:</span>
                            <span class="fw-bold" id="modalItemQty" style="color: #1a1a2e;">1</span>
                        </div>
                    </div>
                    
                    <p class="mb-2 fw-semibold text-center" style="font-size: 0.9rem;">How many do you want to remove?</p>
                    
                    <div class="d-flex align-items-center gap-2 mb-3 justify-content-center">
                        <button type="button" class="btn btn-outline-secondary px-3 py-1" onclick="adjustRemoveQty(-1)">-</button>
                        <input type="text" inputmode="numeric" pattern="[0-9]*" id="removeQtyInput" class="form-control text-center fw-bold" value="1" style="max-width: 80px; font-size: 1.1rem;">
                        <button type="button" class="btn btn-outline-secondary px-3 py-1" onclick="adjustRemoveQty(1)">+</button>
                    </div>
                    
                    <p class="text-muted small mb-0 text-center"><i class="bi bi-info-circle me-1"></i> Removing all items will automatically cancel the order.</p>
                    <div id="removeItemError" class="text-danger small mt-2 fw-semibold text-center" style="display: none;"></div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #eef2f6; padding: 1rem 1.5rem;">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger rounded-pill px-4" id="confirmRemoveBtn">
                        <i class="bi bi-trash me-1"></i> Remove Item
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .btn-icon-circle {
            width: 28px !important;
            height: 28px !important;
            min-width: 28px !important;
            max-width: 28px !important;
            padding: 0 !important;
            border-radius: 50% !important;
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .btn-icon-circle i {
            font-size: 0.8rem;
            line-height: 1;
        }

        .badge-delivery-failed {
            background-color: #fee2e2 !important;
            color: #dc2626 !important;
            border: 1px solid #fecaca;
            font-weight: 600;
            white-space: nowrap;
            line-height: 1.3;
            text-align: left;
        }

        @media (min-width: 992px) {
            .badge-delivery-failed {
                font-size: 0.68rem;
                padding: 0.3rem 0.5rem;
            }
            .order-actions {
                gap: 0.35rem !important;
            }
            .order-actions .btn {
                padding: 0.35rem 0.6rem;
                font-size: 0.75rem;
            }
        }

        @media (min-width: 768px) and (max-width: 991.98px) {
            .badge-delivery-failed {
                white-space: normal;
            }
        }

        @media (max-width: 767.98px) {
            .orders-container {
                padding-left: 14px;
                padding-right: 14px;
            }

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

            .order-actions .btn:only-child {
                flex: 1;
            }
            
            .order-actions .dropdown {
                flex: 1 1 auto;
                min-width: 30%;
            }
            
            .order-actions .dropdown .btn {
                width: 100%;
            }

            .dropdown-menu {
                font-size: 0.85rem;
                border-radius: 12px !important;
                box-shadow: 0 4px 20px rgba(0,0,0,0.15) !important;
                min-width: 220px !important;
                right: 0 !important;
                left: auto !important;
            }

            .cancel-modal-content,
            #removeItemModal .modal-content {
                border-radius: 20px !important;
                margin: 1rem;
            }
            
            @media (max-width: 480px) {
                #removeItemModal .modal-dialog {
                    margin: 0;
                    position: fixed;
                    bottom: 0;
                    left: 0;
                    right: 0;
                    width: 100%;
                    max-width: 100%;
                }
                #removeItemModal .modal-content {
                    border-radius: 24px 24px 0 0 !important;
                    margin: 0;
                    padding-bottom: env(safe-area-inset-bottom);
                }
            }

            .cancel-modal-header,
            #removeItemModal .modal-header {
                padding: 1rem 1.15rem !important;
            }

            .cancel-modal-header .modal-title,
            #removeItemModal .modal-title {
                font-size: 1rem !important;
            }

            .cancel-modal-body,
            #removeItemModal .modal-body {
                padding: 1.15rem !important;
                font-size: 0.88rem;
            }

            .cancel-modal-footer,
            #removeItemModal .modal-footer {
                padding: 0.9rem 1.15rem !important;
                gap: 0.5rem;
                flex-wrap: nowrap;
            }

            .cancel-modal-footer .btn,
            #removeItemModal .modal-footer .btn {
                flex: 1;
                padding: 0.6rem 0.75rem;
                font-size: 0.82rem;
                font-weight: 500;
            }

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

        @media (hover: none) {
            .order-card:hover {
                transform: none;
            }

            .order-actions .btn:hover {
                transform: none;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let activeOrderId = null;
            let activeItemId = null;
            let maxQty = 1;

            const removeModal = document.getElementById('removeItemModal');
            const qtyInput = document.getElementById('removeQtyInput');
            const errorDiv = document.getElementById('removeItemError');
            const confirmBtn = document.getElementById('confirmRemoveBtn');

            removeModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                
                activeOrderId = button.getAttribute('data-order-id');
                activeItemId = button.getAttribute('data-item-id');
                maxQty = parseInt(button.getAttribute('data-item-qty')) || 1;
                const itemName = button.getAttribute('data-item-name');
                const itemFlavor = button.getAttribute('data-item-flavor');

                document.getElementById('modalItemName').textContent = itemName;
                
                const flavorEl = document.getElementById('modalItemFlavor');
                if (itemFlavor) {
                    flavorEl.textContent = 'Flavor: ' + itemFlavor;
                    flavorEl.style.display = 'block';
                } else {
                    flavorEl.style.display = 'none';
                }

                document.getElementById('modalItemQty').textContent = maxQty;
                
                qtyInput.value = 1;
                errorDiv.style.display = 'none';
                errorDiv.className = 'text-danger small mt-2 fw-semibold text-center';
                confirmBtn.style.display = 'inline-block';
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = '<i class="bi bi-trash me-1"></i> Remove Item';
                document.querySelector('#removeItemModal .btn-light').style.display = 'inline-block';
            });

            qtyInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
                let val = parseInt(this.value);
                errorDiv.style.display = 'none';

                if (this.value !== '' && val > maxQty) {
                    this.value = maxQty;
                    errorDiv.textContent = `You only have ${maxQty} of this item.`;
                    errorDiv.style.display = 'block';
                }
            });

            qtyInput.addEventListener('blur', function() {
                let val = parseInt(this.value);
                if (isNaN(val) || val < 1) {
                    this.value = 1;
                }
            });

            window.adjustRemoveQty = function(change) {
                let currentVal = parseInt(qtyInput.value) || 1;
                let newVal = currentVal + change;

                if (newVal >= 1 && newVal <= maxQty) {
                    qtyInput.value = newVal;
                    errorDiv.style.display = 'none';
                } else if (newVal > maxQty) {
                    errorDiv.textContent = `You only have ${maxQty} of this item in your order.`;
                    errorDiv.style.display = 'block';
                }
            };

            confirmBtn.addEventListener('click', function() {
                let val = parseInt(qtyInput.value);
                if (isNaN(val) || val < 1) {
                    qtyInput.value = 1;
                    val = 1;
                }

                const qtyToRemove = val;
                
                confirmBtn.disabled = true;
                confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Removing...';
                errorDiv.style.display = 'none';

                fetch(`/customer/orders/${activeOrderId}/remove-item`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ 
                        item_id: activeItemId,
                        quantity: qtyToRemove 
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        errorDiv.className = 'text-success small mt-2 fw-semibold text-center';
                        errorDiv.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> ' + (data.message || 'Item removed!');
                        errorDiv.style.display = 'block';
                        
                        confirmBtn.style.display = 'none';
                        document.querySelector('#removeItemModal .btn-light').style.display = 'none';

                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                        
                    } else {
                        errorDiv.className = 'text-danger small mt-2 fw-semibold text-center';
                        errorDiv.textContent = data.message || 'Failed to remove item.';
                        errorDiv.style.display = 'block';
                        confirmBtn.disabled = false;
                        confirmBtn.innerHTML = '<i class="bi bi-trash me-1"></i> Remove Item';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    errorDiv.className = 'text-danger small mt-2 fw-semibold text-center';
                    errorDiv.textContent = 'Network error. Please try again.';
                    errorDiv.style.display = 'block';
                    confirmBtn.disabled = false;
                    confirmBtn.innerHTML = '<i class="bi bi-trash me-1"></i> Remove Item';
                });
            });
        });
    </script>
@endsection