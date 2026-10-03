<div class="track-modal-wrapper" style="padding: 20px;">
    <div class="d-flex justify-content-between align-items-center mb-3 track-modal-header">
        <h5 class="mb-0 track-modal-title"><i class="bi bi-geo-alt"></i> Track Delivery - Order #{{ $order->order_number }}</h5>
        <button type="button" class="btn-close" onclick="closeTrackModal()"></button>
    </div>
    <hr class="track-modal-divider">

    <!-- Status Timeline -->
    <div class="mb-4 track-timeline-wrapper">
        <div class="d-flex justify-content-between flex-wrap track-timeline">
            <div
                class="text-center track-step {{ in_array($order->order_status, ['pending', 'confirmed', 'processing', 'ready', 'out_for_delivery', 'delivered']) ? 'text-success' : 'text-muted' }}">
                <i class="bi bi-clock-history fs-3 track-step-icon"></i>
                <div class="small fw-bold track-step-label">Pending</div>
                <div class="small track-step-date">{{ $order->created_at->format('M d, h:i A') }}</div>
            </div>
            <div
                class="text-center track-step {{ in_array($order->order_status, ['confirmed', 'processing', 'ready', 'out_for_delivery', 'delivered']) ? 'text-success' : 'text-muted' }}">
                <i class="bi bi-check-circle fs-3 track-step-icon"></i>
                <div class="small fw-bold track-step-label">Confirmed</div>
            </div>
            <div
                class="text-center track-step {{ in_array($order->order_status, ['processing', 'ready', 'out_for_delivery', 'delivered']) ? 'text-success' : 'text-muted' }}">
                <i class="bi bi-gear fs-3 track-step-icon"></i>
                <div class="small fw-bold track-step-label">Processing</div>
            </div>
            <div
                class="text-center track-step {{ in_array($order->order_status, ['ready', 'out_for_delivery', 'delivered']) ? 'text-success' : 'text-muted' }}">
                <i class="bi bi-box-seam fs-3 track-step-icon"></i>
                <div class="small fw-bold track-step-label">Ready</div>
            </div>
            <div
                class="text-center track-step {{ in_array($order->order_status, ['out_for_delivery', 'delivered']) ? 'text-success' : 'text-muted' }}">
                <i class="bi bi-truck fs-3 track-step-icon"></i>
                <div class="small fw-bold track-step-label">Out for Delivery</div>
            </div>
            <div class="text-center track-step {{ $order->order_status == 'delivered' ? 'text-success' : 'text-muted' }}">
                <i class="bi bi-check-circle-fill fs-3 track-step-icon"></i>
                <div class="small fw-bold track-step-label">Delivered</div>
            </div>
        </div>
    </div>

    <!-- Delivery Details -->
    @if ($delivery)
        <div class="row g-3 track-delivery-row">
            <div class="col-md-6">
                <div class="card bg-light track-info-card">
                    <div class="card-body track-info-body">
                        <h6 class="track-info-title"><i class="bi bi-truck"></i> Delivery Information</h6>
                        <p class="track-info-line"><strong>Tracking Number:</strong> {{ $delivery->tracking_number }}</p>
                        <p class="track-info-line"><strong>Status:</strong>
                            <span
                                class="badge bg-{{ $delivery->status == 'delivered' ? 'success' : ($delivery->status == 'in_transit' ? 'warning' : 'info') }}">
                                {{ ucfirst($delivery->status) }}
                            </span>
                        </p>
                        @if ($delivery->driver)
                            <p class="track-info-line"><strong>Driver:</strong> {{ $delivery->driver->name }}</p>
                            <p class="track-info-line"><strong>Driver Contact:</strong> {{ $delivery->driver->phone ?? 'N/A' }}</p>
                        @endif
                        @if ($delivery->assigned_at)
                            <p class="track-info-line"><strong>Assigned:</strong> {{ $delivery->assigned_at->format('M d, Y h:i A') }}</p>
                        @endif
                        @if ($delivery->picked_up_at)
                            <p class="track-info-line"><strong>Picked Up:</strong> {{ $delivery->picked_up_at->format('M d, Y h:i A') }}</p>
                        @endif
                        @if ($delivery->delivered_at)
                            <p class="track-info-line"><strong>Delivered:</strong> {{ $delivery->delivered_at->format('M d, Y h:i A') }}</p>
                        @endif
                        @if ($delivery->driver_notes)
                            <p class="track-info-line"><strong>Driver Notes:</strong> {{ $delivery->driver_notes }}</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-light track-info-card">
                    <div class="card-body track-info-body">
                        <h6 class="track-info-title"><i class="bi bi-geo-alt"></i> Delivery Address</h6>
                        <p class="track-info-line">{{ $order->delivery_address }}</p>
                        <p class="track-info-line">{{ $order->barangay }}, {{ $order->city }}</p>
                        @if ($order->landmark)
                            <p class="track-info-line"><strong>Landmark:</strong> {{ $order->landmark }}</p>
                        @endif
                        <p class="track-info-line"><strong>Recipient:</strong> {{ $order->customer_name }}</p>
                        <p class="track-info-line"><strong>Contact:</strong> {{ $order->customer_phone }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Proof of Delivery & Payment -->
        @if ($delivery->delivery_proof || $delivery->payment_proof)
            <div class="row g-3 mt-1 track-proof-row">
                @if ($delivery->delivery_proof)
                    <div class="col-md-6">
                        <div class="card track-proof-card">
                            <div class="card-header bg-success text-white track-proof-header">Delivery Proof</div>
                            <div class="card-body text-center track-proof-body">
                                <a href="{{ Storage::url($delivery->delivery_proof) }}" target="_blank">
                                    <img src="{{ Storage::url($delivery->delivery_proof) }}" class="img-fluid rounded track-proof-img"
                                        style="max-height: 150px;">
                                </a>
                                <div class="mt-2">
                                    <a href="{{ Storage::url($delivery->delivery_proof) }}" target="_blank"
                                        class="btn btn-sm btn-outline-primary track-proof-btn">View Full Image</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if ($delivery->payment_proof)
                    <div class="col-md-6">
                        <div class="card track-proof-card">
                            <div class="card-header bg-info text-white track-proof-header">Payment Proof</div>
                            <div class="card-body text-center track-proof-body">
                                <a href="{{ Storage::url($delivery->payment_proof) }}" target="_blank">
                                    <img src="{{ Storage::url($delivery->payment_proof) }}" class="img-fluid rounded track-proof-img"
                                        style="max-height: 150px;">
                                </a>
                                <div class="mt-2">
                                    <a href="{{ Storage::url($delivery->payment_proof) }}" target="_blank"
                                        class="btn btn-sm btn-outline-success track-proof-btn">View Full Image</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Live Location (if shared) -->
        @if ($delivery->driver_latitude && $delivery->driver_longitude)
            <div class="mt-3 track-location-wrapper">
                <div class="alert alert-secondary track-location-alert">
                    <i class="bi bi-geo-alt-fill"></i> Driver's last known location:
                    <a href="https://maps.google.com/?q={{ $delivery->driver_latitude }},{{ $delivery->driver_longitude }}"
                        target="_blank" class="track-location-link">
                        View on Map
                    </a>
                </div>
            </div>
        @endif
    @else
        <div class="alert alert-info track-info-alert">
            <i class="bi bi-info-circle"></i> Your order is being prepared. Once dispatched, tracking information will
            appear here.
        </div>
    @endif

    <div class="mt-4 text-center track-actions">
        <button onclick="closeTrackModal()" class="btn btn-secondary rounded-pill track-close-btn">
            <i class="bi bi-x-circle"></i> Close
        </button>
        <button onclick="window.location.reload()" class="btn btn-primary rounded-pill track-refresh-btn">
            <i class="bi bi-arrow-repeat"></i> Refresh Status
        </button>
    </div>
</div>

<style>
    /* ===== MOBILE APP-LIKE STYLES ===== */
    @media (max-width: 767.98px) {
        .track-modal-wrapper {
            padding: 16px 14px !important;
        }

        /* Header */
        .track-modal-header {
            margin-bottom: 0.75rem !important;
            gap: 0.5rem;
        }

        .track-modal-title {
            font-size: 0.95rem;
            line-height: 1.3;
            word-break: break-word;
            flex: 1;
        }

        .track-modal-title i {
            color: #0d6efd;
            margin-right: 0.25rem;
        }

        .track-modal-divider {
            margin: 0.6rem 0 1rem;
        }

        .track-modal-header .btn-close {
            flex-shrink: 0;
        }

        /* Status Timeline - Vertical Mobile Style */
        .track-timeline-wrapper {
            margin-bottom: 1rem !important;
        }

        .track-timeline {
            flex-direction: column;
            gap: 0;
            position: relative;
            padding-left: 0.5rem;
        }

        /* Vertical connector line */
        .track-timeline::before {
            content: '';
            position: absolute;
            left: 26px;
            top: 26px;
            bottom: 26px;
            width: 3px;
            background: #e9ecef;
            border-radius: 2px;
            z-index: 0;
        }

        .track-step {
            display: flex !important;
            align-items: flex-start;
            text-align: left;
            gap: 0.85rem;
            padding: 0.45rem 0;
            position: relative;
            z-index: 1;
            flex: none;
            min-width: 0;
        }

        .track-step-icon {
            width: 44px;
            height: 44px;
            min-width: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #f1f5f9;
            border: 2px solid #fff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
            font-size: 1.05rem !important;
            transition: all 0.3s ease;
            color: #94a3b8;
        }

        .track-step.text-success .track-step-icon {
            background: #28a745;
            color: white;
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        }

        /* The last completed step (current) gets a pulse */
        .track-step.text-success:not(:has(~ .track-step.text-success)) .track-step-icon {
            animation: pulseStep 1.5s ease-in-out infinite;
        }

        @keyframes pulseStep {
            0%, 100% {
                box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3), 0 0 0 0 rgba(40, 167, 69, 0.4);
            }
            50% {
                box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3), 0 0 0 8px rgba(40, 167, 69, 0);
            }
        }

        /* Content layout */
        .track-step > div:not(.track-step-icon) {
            flex: 1;
            padding-top: 0.4rem;
        }

        .track-step-label {
            font-size: 0.88rem;
            margin-bottom: 0.1rem;
            text-align: left;
            color: #1a1a2e;
        }

        .track-step.text-success .track-step-label {
            color: #28a745;
        }

        .track-step.text-muted .track-step-label {
            color: #94a3b8;
        }

        .track-step-date {
            font-size: 0.72rem;
            text-align: left;
            color: #94a3b8;
            display: block;
        }

        /* Delivery cards stack nicely */
        .track-delivery-row {
            gap: 0.75rem;
        }

        .track-delivery-row > [class*="col-"] {
            margin-bottom: 0;
        }

        .track-info-card {
            border-radius: 14px !important;
            border: 1px solid #eef2f6 !important;
            background: #f8fafc !important;
            overflow: hidden;
        }

        .track-info-body {
            padding: 1rem !important;
        }

        .track-info-title {
            font-size: 0.88rem;
            color: #1a1a2e;
            margin-bottom: 0.75rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .track-info-title i {
            color: #0d6efd;
            margin-right: 0.3rem;
        }

        .track-info-line {
            font-size: 0.82rem;
            margin-bottom: 0.5rem;
            line-height: 1.5;
            color: #475569;
            word-break: break-word;
        }

        .track-info-line:last-child {
            margin-bottom: 0;
        }

        .track-info-line strong {
            color: #1a1a2e;
            font-weight: 600;
            display: inline-block;
            margin-right: 0.2rem;
        }

        .track-info-line .badge {
            font-size: 0.7rem;
            padding: 0.3rem 0.6rem;
        }

        /* Proof cards */
        .track-proof-row {
            gap: 0.75rem;
            margin-top: 0.25rem !important;
        }

        .track-proof-card {
            border-radius: 14px !important;
            overflow: hidden;
            border: 1px solid #eef2f6 !important;
        }

        .track-proof-header {
            padding: 0.6rem 0.85rem;
            font-size: 0.82rem;
            font-weight: 600;
        }

        .track-proof-body {
            padding: 0.85rem !important;
        }

        .track-proof-img {
            max-height: 180px !important;
            width: auto !important;
            border-radius: 10px !important;
        }

        .track-proof-btn {
            font-size: 0.75rem;
            padding: 0.4rem 0.85rem;
            border-radius: 10px;
            font-weight: 500;
        }

        /* Location alert */
        .track-location-wrapper {
            margin-top: 0.75rem !important;
        }

        .track-location-alert {
            font-size: 0.8rem;
            padding: 0.7rem 0.9rem;
            border-radius: 12px;
            line-height: 1.5;
        }

        .track-location-alert i {
            color: #0d6efd;
            margin-right: 0.2rem;
        }

        .track-location-link {
            font-weight: 600;
            color: #0d6efd;
            text-decoration: none;
            white-space: nowrap;
        }

        /* Info alert */
        .track-info-alert {
            font-size: 0.82rem;
            padding: 0.85rem 1rem;
            border-radius: 12px;
            line-height: 1.5;
            margin-bottom: 0;
        }

        .track-info-alert i {
            color: #0d6efd;
            margin-right: 0.3rem;
        }

        /* Actions */
        .track-actions {
            margin-top: 1rem !important;
            display: flex;
            gap: 0.6rem;
            flex-wrap: wrap;
        }

        .track-actions .btn {
            flex: 1;
            min-width: 0;
            padding: 0.7rem 0.75rem;
            font-size: 0.85rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            transition: all 0.2s ease;
            border-radius: 12px !important;
        }

        .track-actions .btn:active {
            transform: scale(0.97);
        }

        .track-close-btn {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            color: #475569;
        }

        .track-close-btn:hover {
            background: #e2e8f0;
            border-color: #cbd5e1;
            color: #1e293b;
        }

        .track-refresh-btn {
            background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
            border: none;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.25);
        }

        .track-refresh-btn:hover {
            background: linear-gradient(135deg, #0b5ed7 0%, #0a58ca 100%);
            box-shadow: 0 6px 16px rgba(13, 110, 253, 0.35);
        }
    }

    /* Extra small devices */
    @media (max-width: 380px) {
        .track-modal-title {
            font-size: 0.85rem;
        }

        .track-step-icon {
            width: 38px;
            height: 38px;
            min-width: 38px;
            font-size: 0.95rem !important;
        }

        .track-timeline::before {
            left: 23px;
        }

        .track-step-label {
            font-size: 0.82rem;
        }

        .track-step-date {
            font-size: 0.68rem;
        }

        .track-info-title {
            font-size: 0.82rem;
        }

        .track-info-line {
            font-size: 0.78rem;
        }

        .track-actions .btn {
            font-size: 0.78rem;
            padding: 0.6rem 0.6rem;
        }
    }

    /* Tablet */
    @media (min-width: 768px) and (max-width: 991.98px) {
        .track-modal-wrapper {
            padding: 18px;
        }

        .track-step {
            min-width: 80px;
        }

        .track-step-icon {
            font-size: 1.6rem !important;
        }
    }

    /* Touch device */
    @media (hover: none) {
        .track-close-btn:hover {
            background: #f1f5f9;
            border-color: #e2e8f0;
            color: #475569;
        }

        .track-refresh-btn:hover {
            background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.25);
        }
    }
</style>