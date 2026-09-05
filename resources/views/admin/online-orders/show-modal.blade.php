<style>
    /* Modern Minimalist Modal Styles */
    .order-modal-container {
        padding: 1.5rem;
        max-height: 85vh;
        overflow-y: auto;
        background: #f8f9fa;
    }

    .order-modal-container::-webkit-scrollbar {
        width: 6px;
    }

    .order-modal-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .order-modal-container::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    /* Modal Header */
    .modal-header-minimal {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #eef2f6;
    }

    .modal-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 0;
    }

    .modal-title i {
        color: #3b82f6;
        margin-right: 0.5rem;
    }

    /* Cards */
    .info-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #eef2f6;
        margin-bottom: 1rem;
        overflow: hidden;
    }

    .card-header-minimal {
        padding: 0.875rem 1.25rem;
        background: white;
        border-bottom: 1px solid #eef2f6;
    }

    .card-header-minimal h6 {
        font-size: 0.8rem;
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 0;
    }

    .card-header-minimal h6 i {
        margin-right: 0.5rem;
        color: #3b82f6;
    }

    .card-body-minimal {
        padding: 1rem 1.25rem;
    }

    /* Info Rows */
    .info-row {
        display: flex;
        margin-bottom: 0.75rem;
    }

    .info-label {
        width: 100px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
    }

    .info-value {
        flex: 1;
        font-size: 0.8rem;
        color: #1a1a2e;
        font-weight: 500;
    }

    .info-value .badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.65rem;
    }

    /* Status Badges */
    .badge-pending {
        background: #fef3c7;
        color: #d97706;
    }

    .badge-confirmed {
        background: #dbeafe;
        color: #2563eb;
    }

    .badge-processing {
        background: #e0e7ff;
        color: #4f46e5;
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

    .badge-cancelled {
        background: #fee2e2;
        color: #dc2626;
    }

    /* Product Image */
    .product-image {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 10px;
        background: #f8f9fa;
    }

    /* Order Items Table */
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
        padding: 0.75rem;
        border-bottom: 1px solid #eef2f6;
    }

    .order-items-table td {
        padding: 0.75rem;
        vertical-align: middle;
        border-bottom: 1px solid #eef2f6;
        font-size: 0.8rem;
    }

    .product-name {
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 0;
    }

    /* Order Timeline */
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
        border: 2px solid;
    }

    .timeline-icon.completed {
        background: #10b981;
        border-color: #10b981;
        color: white;
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

    /* Alert */
    .alert-minimal {
        border-radius: 12px;
        padding: 0.75rem 1rem;
        font-size: 0.75rem;
        margin-bottom: 1rem;
    }

    .alert-info-minimal {
        background: #eff6ff;
        border: 1px solid #dbeafe;
        color: #2563eb;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .order-modal-container {
            padding: 1rem;
        }

        .info-label {
            width: 80px;
        }

        .timeline-icon {
            width: 32px;
            height: 32px;
        }

        .timeline-line {
            left: 16px;
        }

        .product-image {
            width: 40px;
            height: 40px;
        }

        .order-items-table th,
        .order-items-table td {
            padding: 0.5rem;
        }
    }

    /* Delivery Status Badge Overrides */
    .badge-info {
        background: #17a2b8;
        color: white;
    }

    .badge-primary {
        background: #0d6efd;
        color: white;
    }

    .badge-warning {
        background: #ffc107;
        color: #212529;
    }

    .badge-success {
        background: #198754;
        color: white;
    }

    .badge-secondary {
        background: #6c757d;
        color: white;
    }
</style>

<div class="order-modal-container">
    <!-- Header -->
    <div class="modal-header-minimal">
        <h5 class="modal-title">
            <i class="bi bi-cart"></i> Order #{{ $order->order_number }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <!-- ORDER ITEMS SECTION -->
    @if ($order->items && $order->items->count() > 0)
        <div class="info-card mb-3">
            <div class="card-header-minimal">
                <h6><i class="bi bi-receipt"></i> Order Items</h6>
            </div>
            <div class="card-body-minimal p-0">
                <div class="table-responsive">
                    <table class="table order-items-table">
                        <thead>
                            <tr>
                                <th style="width: 60px">Image</th>
                                <th>Product</th>
                                <th class="text-center" style="width: 70px">Qty</th>
                                <th class="text-end" style="width: 100px">Price</th>
                                <th class="text-end" style="width: 100px">Subtotal</th>
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
                                @endphp
                                <tr>
                                    <td>
                                        @if ($imageUrl)
                                            <img src="{{ $imageUrl }}" alt="{{ $product->name }}"
                                                class="product-image">
                                        @else
                                            <div
                                                class="product-image bg-light d-flex align-items-center justify-content-center">
                                                <i class="bi bi-box-seam text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="product-name">{{ $item->product->name }}</div>
                                        @if ($item->flavor)
                                            <small class="text-muted">{{ $item->flavor->name }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">₱{{ number_format($item->price, 2) }}</td>
                                    <td class="text-end">₱{{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-light">
                            <tr>
                                <td colspan="4" class="text-end fw-bold">Total:</td>
                                <td class="text-end fw-bold text-danger">
                                    ₱{{ number_format($order->subtotal, 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <div class="row g-3">
        <!-- LEFT COLUMN - Order & Customer Info -->
        <div class="col-md-6">
            <!-- Order Information Card -->
            <div class="info-card">
                <div class="card-header-minimal">
                    <h6><i class="bi bi-info-circle"></i> Order Information</h6>
                </div>
                <div class="card-body-minimal">
                    <div class="info-row">
                        <div class="info-label">Order #</div>
                        <div class="info-value">{{ $order->order_number }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Date Placed</div>
                        <div class="info-value">{{ $order->created_at->format('F d, Y h:i A') }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Branch</div>
                        <div class="info-value">{{ $order->branch->name ?? 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Payment Method</div>
                        <div class="info-value">{{ strtoupper($order->payment_method) }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Delivery Type</div>
                        <div class="info-value">
                            <span class="badge bg-info bg-opacity-10 text-dark">
                                <i
                                    class="bi bi-{{ $order->delivery_type == 'delivery' ? 'truck' : 'building' }} me-1"></i>
                                {{ ucfirst($order->delivery_type) }}
                            </span>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Status</div>
                        <div class="info-value">
                            @php
                                $statusClass = match ($order->order_status) {
                                    'pending' => 'badge-pending',
                                    'confirmed' => 'badge-confirmed',
                                    'processing' => 'badge-processing',
                                    'ready' => 'badge-ready',
                                    'picked_up' => 'badge-picked_up',
                                    'out_for_delivery' => 'badge-out_for_delivery',
                                    'delivered' => 'badge-delivered',
                                    'cancelled' => 'badge-cancelled',
                                    default => 'badge-secondary',
                                };
                                $displayStatus = match ($order->order_status) {
                                    'processing' => 'Packing',
                                    'picked_up' => 'Picked Up',
                                    'out_for_delivery' => 'Out for Delivery',
                                    default => ucfirst($order->order_status),
                                };
                            @endphp
                            <span class="badge {{ $statusClass }}">
                                {{ $displayStatus }}
                            </span>
                        </div>
                    </div>
                    @if ($order->notes)
                        <div class="info-row">
                            <div class="info-label">Order Notes</div>
                            <div class="info-value">{{ $order->notes }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Customer Information Card -->
            <div class="info-card">
                <div class="card-header-minimal">
                    <h6><i class="bi bi-person"></i> Customer Information</h6>
                </div>
                <div class="card-body-minimal">
                    <div class="info-row">
                        <div class="info-label">Name</div>
                        <div class="info-value">{{ $order->customer_name }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Phone</div>
                        <div class="info-value">{{ $order->customer_phone }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ $order->customer_email ?? 'N/A' }}</div>
                    </div>
                    @if ($order->delivery_type == 'delivery')
                        <div class="info-row">
                            <div class="info-label">Delivery Address</div>
                            <div class="info-value">{{ $order->delivery_address }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">City/Barangay</div>
                            <div class="info-value">{{ $order->city ?? 'N/A' }}, {{ $order->barangay ?? 'N/A' }}</div>
                        </div>
                        @if ($order->landmark)
                            <div class="info-row">
                                <div class="info-label">Landmark</div>
                                <div class="info-value">{{ $order->landmark }}</div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Delivery Info Card (if delivery type is delivery) -->
            @if ($order->delivery_type == 'delivery' && $order->delivery)
                <div class="info-card">
                    <div class="card-header-minimal">
                        <h6><i class="bi bi-truck"></i> Delivery Information</h6>
                    </div>
                    <div class="card-body-minimal">
                        <div class="info-row">
                            <div class="info-label">Driver</div>
                            <div class="info-value">{{ $order->delivery->driver->name ?? 'Not Assigned' }}</div>
                        </div>
                        @if ($order->delivery->driver)
                            <div class="info-row">
                                <div class="info-label">Driver Contact</div>
                                <div class="info-value">{{ $order->delivery->driver->phone ?? 'N/A' }}</div>
                            </div>
                        @endif
                        <div class="info-row">
                            <div class="info-label">Delivery Status</div>
                            <div class="info-value">
                                @php
                                    $deliveryStatusClass = match ($order->delivery->status) {
                                        'assigned' => 'badge-info',
                                        'picked_up' => 'badge-primary',
                                        'in_transit' => 'badge-warning',
                                        'delivered' => 'badge-success',
                                        default => 'badge-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $deliveryStatusClass }}">
                                    {{ ucfirst($order->delivery->status) }}
                                </span>
                            </div>
                        </div>
                        @if ($order->delivery->picked_up_at)
                            <div class="info-row">
                                <div class="info-label">Picked Up</div>
                                <div class="info-value">
                                    {{ \Carbon\Carbon::parse($order->delivery->picked_up_at)->format('M d, Y h:i A') }}
                                </div>
                            </div>
                        @endif
                        @if ($order->delivery->in_transit_at)
                            <div class="info-row">
                                <div class="info-label">In Transit</div>
                                <div class="info-value">
                                    {{ \Carbon\Carbon::parse($order->delivery->in_transit_at)->format('M d, Y h:i A') }}
                                </div>
                            </div>
                        @endif
                        @if ($order->delivery->delivered_at)
                            <div class="info-row">
                                <div class="info-label">Delivered</div>
                                <div class="info-value">
                                    {{ \Carbon\Carbon::parse($order->delivery->delivered_at)->format('M d, Y h:i A') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- RIGHT COLUMN - Order Timeline -->
        <div class="col-md-6">
            <div class="info-card">
                <div class="card-header-minimal">
                    <h6><i class="bi bi-clock-history"></i> Order Timeline</h6>
                </div>
                <div class="card-body-minimal">
                    <div class="timeline-container">
                        @php
                            // Status progression levels
                            $statusOrder = [
                                'pending' => 0,
                                'confirmed' => 1,
                                'processing' => 2,
                                'ready' => 3,
                                'picked_up' => 4,
                                'out_for_delivery' => 5,
                                'in_transit' => 5,
                                'delivered' => 6,
                                'cancelled' => 99,
                            ];

                            $currentStatus = $order->order_status;
                            $currentStatusLevel = $statusOrder[$currentStatus] ?? 0;

                            // Also check delivery status
                            if ($order->delivery) {
                                $deliveryStatus = $order->delivery->status;
                                $deliveryStatusLevel = $statusOrder[$deliveryStatus] ?? 0;
                                $currentStatusLevel = max($currentStatusLevel, $deliveryStatusLevel);
                            }

                            // Helper function
                            $isCompleted = function ($level) use ($currentStatusLevel) {
                                return $currentStatusLevel >= $level;
                            };

                            // Get timestamps (from orders or deliveries)
                            $confirmedAt = $order->confirmed_at;
                            $processingAt = $order->processing_at;
                            $readyAt = $order->ready_at;
                            $pickedUpAt = $order->delivery ? $order->delivery->picked_up_at : null;
                            $outForDeliveryAt =
                                $order->out_for_delivery_at ?:
                                ($order->delivery
                                    ? $order->delivery->in_transit_at
                                    : null);
                            $deliveredAt =
                                $order->delivered_at ?: ($order->delivery ? $order->delivery->delivered_at : null);

                            // Format date helper
                            $formatDate = function ($date) {
                                return $date ? \Carbon\Carbon::parse($date)->format('F d, Y h:i A') : null;
                            };
                        @endphp

                        <!-- Order Placed (Always completed) -->
                        <div class="timeline-item">
                            <div class="timeline-icon completed">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title">Order Placed</div>
                                <div class="timeline-date">{{ $order->created_at->format('F d, Y h:i A') }}</div>
                            </div>
                            <div class="timeline-line {{ $isCompleted(1) ? 'completed' : '' }}"></div>
                        </div>

                        <!-- Confirmed -->
                        <div class="timeline-item">
                            <div class="timeline-icon {{ $isCompleted(1) ? 'completed' : 'pending' }}">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title">Confirmed</div>
                                @if ($confirmedAt)
                                    <div class="timeline-date">{{ $formatDate($confirmedAt) }}</div>
                                @elseif ($isCompleted(1))
                                    <div class="timeline-date">{{ $order->created_at->format('F d, Y h:i A') }}</div>
                                @else
                                    <div class="timeline-date text-muted">Pending confirmation</div>
                                @endif
                            </div>
                            <div class="timeline-line {{ $isCompleted(2) ? 'completed' : '' }}"></div>
                        </div>

                        <!-- Packing -->
                        <div class="timeline-item">
                            <div class="timeline-icon {{ $isCompleted(2) ? 'completed' : 'pending' }}">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title">Packing</div>
                                @if ($processingAt)
                                    <div class="timeline-date">{{ $formatDate($processingAt) }}</div>
                                @elseif ($isCompleted(2))
                                    <div class="timeline-date">{{ $order->created_at->format('F d, Y h:i A') }}</div>
                                @else
                                    <div class="timeline-date text-muted">Not yet started</div>
                                @endif
                            </div>
                            <div class="timeline-line {{ $isCompleted(3) ? 'completed' : '' }}"></div>
                        </div>

                        <!-- Ready -->
                        <div class="timeline-item">
                            <div class="timeline-icon {{ $isCompleted(3) ? 'completed' : 'pending' }}">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title">Ready</div>
                                @if ($readyAt)
                                    <div class="timeline-date">{{ $formatDate($readyAt) }}</div>
                                @elseif ($isCompleted(3))
                                    <div class="timeline-date">{{ $order->created_at->format('F d, Y h:i A') }}</div>
                                @else
                                    <div class="timeline-date text-muted">Not yet ready</div>
                                @endif
                            </div>
                            <div class="timeline-line {{ $isCompleted(4) ? 'completed' : '' }}"></div>
                        </div>

                        <!-- Picked Up -->
                        <div class="timeline-item">
                            <div class="timeline-icon {{ $isCompleted(4) ? 'completed' : 'pending' }}">
                                <i class="bi bi-box-arrow-up"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title">Picked Up</div>
                                @if ($pickedUpAt)
                                    <div class="timeline-date">{{ $formatDate($pickedUpAt) }}</div>
                                @elseif ($isCompleted(4))
                                    <div class="timeline-date">{{ $order->created_at->format('F d, Y h:i A') }}</div>
                                @else
                                    <div class="timeline-date text-muted">Not yet picked up</div>
                                @endif
                            </div>
                            <div class="timeline-line {{ $isCompleted(5) ? 'completed' : '' }}"></div>
                        </div>

                        <!-- Out for Delivery -->
                        <div class="timeline-item">
                            <div class="timeline-icon {{ $isCompleted(5) ? 'completed' : 'pending' }}">
                                <i class="bi bi-truck"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title">Out for Delivery</div>
                                @if ($outForDeliveryAt)
                                    <div class="timeline-date">{{ $formatDate($outForDeliveryAt) }}</div>
                                @elseif ($isCompleted(5))
                                    <div class="timeline-date">{{ $order->created_at->format('F d, Y h:i A') }}</div>
                                @else
                                    <div class="timeline-date text-muted">Not yet dispatched</div>
                                @endif
                            </div>
                            <div class="timeline-line {{ $isCompleted(6) ? 'completed' : '' }}"></div>
                        </div>

                        <!-- Delivered (Last item - no line) -->
                        <div class="timeline-item">
                            <div class="timeline-icon {{ $isCompleted(6) ? 'completed' : 'pending' }}">
                                <i class="bi bi-flag-fill"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title">Delivered</div>
                                @if ($deliveredAt)
                                    <div class="timeline-date">{{ $formatDate($deliveredAt) }}</div>
                                @elseif ($isCompleted(6))
                                    <div class="timeline-date">{{ $order->created_at->format('F d, Y h:i A') }}</div>
                                @else
                                    <div class="timeline-date text-muted">Not yet delivered</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Note for Owner -->
            <div class="alert-minimal alert-info-minimal mt-3">
                <i class="bi bi-info-circle me-2"></i>
                This is a <strong>read-only view</strong>. The driver and branch staff are responsible for updating the
                order status. You can monitor the progress here.
            </div>
        </div>
    </div>
</div>
