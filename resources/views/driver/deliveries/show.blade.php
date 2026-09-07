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

        <div class="row g-3">
            <!-- LEFT COLUMN -->
            <div class="col-md-7">
                <!-- Order Items Card -->
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
                                            <th style="width: 15%">Image</th>
                                            <th style="width: 35%">Product</th>
                                            <th class="text-center" style="width: 10%">Qty</th>
                                            <th class="text-end" style="width: 20%">Price</th>
                                            <th class="text-end" style="width: 20%">Subtotal</th>
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
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="p-3 bg-light">
                                <div class="totals-row totals-total">
                                    <span class="totals-label">Total</span>
                                    <span
                                        class="totals-value text-success">₱{{ number_format($delivery->order->subtotal, 2) }}</span>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-3">
                                <p class="text-muted small mb-0">No items found</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Delivery Information Card -->
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
                                // ✅ FIXED: Status badge colors matching online orders
                                $statusBadgeClass = match ($delivery->status) {
                                    'pending' => 'badge-secondary',
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

                        @if ($delivery->picked_up_at)
                            <p class="info-label">Picked Up</p>
                            <p class="info-value">
                                {{ \Carbon\Carbon::parse($delivery->picked_up_at)->format('M d, Y h:i A') }}</p>
                        @endif
                        @if ($delivery->out_for_delivery_at || $delivery->in_transit_at)
                            <p class="info-label">Out for Delivery</p>
                            <p class="info-value">
                                {{ \Carbon\Carbon::parse($delivery->out_for_delivery_at ?? $delivery->in_transit_at)->format('M d, Y h:i A') }}</p>
                        @endif
                        @if ($delivery->delivered_at)
                            <p class="info-label">Delivered</p>
                            <p class="info-value">
                                {{ \Carbon\Carbon::parse($delivery->delivered_at)->format('M d, Y h:i A') }}</p>
                        @endif
                    </div>
                </div>

                <!-- Customer Details Card -->
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

            <!-- RIGHT COLUMN - DELIVERY PROGRESS -->
            <div class="col-md-5">
                <div class="info-card">
                    <div class="card-header-custom">
                        <h6><i class="bi bi-clock-history"></i> Delivery Progress</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="timeline-container">
                            @php
                                // ✅ FIXED: Status progression using out_for_delivery
                                $deliveryStatusOrder = [
                                    'pending' => 0,
                                    'assigned' => 1,
                                    'picked_up' => 2,
                                    'out_for_delivery' => 3,
                                    'in_transit' => 3, // Treat in_transit as out_for_delivery
                                    'delivered' => 4,
                                    'delivery_failed' => 99,
                                    'failed' => 99,
                                ];

                                $currentDeliveryStatus = $delivery->status;
                                $currentDeliveryLevel = $deliveryStatusOrder[$currentDeliveryStatus] ?? 0;

                                // Helper function
                                $isDeliveryCompleted = function ($level) use ($currentDeliveryLevel) {
                                    return $currentDeliveryLevel >= $level;
                                };

                                // Check if this is the current step
                                $isCurrentStep = function ($level) use ($currentDeliveryLevel) {
                                    return $currentDeliveryLevel == $level;
                                };

                                // Format date helper
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

                            <!-- ✅ FIXED: Out for Delivery (was In Transit) -->
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

                @php
                    // Lalamove Eligibility Check
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