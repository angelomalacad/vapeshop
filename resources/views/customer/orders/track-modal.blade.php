<div style="padding: 20px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0"><i class="bi bi-geo-alt"></i> Track Delivery - Order #{{ $order->order_number }}</h5>
        <button type="button" class="btn-close" onclick="closeTrackModal()"></button>
    </div>
    <hr>

    <!-- Status Timeline -->
    <div class="mb-4">
        <div class="d-flex justify-content-between flex-wrap">
            <div
                class="text-center {{ in_array($order->order_status, ['pending', 'confirmed', 'processing', 'ready', 'out_for_delivery', 'delivered']) ? 'text-success' : 'text-muted' }}">
                <i class="bi bi-clock-history fs-3"></i>
                <div class="small fw-bold">Pending</div>
                <div class="small">{{ $order->created_at->format('M d, h:i A') }}</div>
            </div>
            <div
                class="text-center {{ in_array($order->order_status, ['confirmed', 'processing', 'ready', 'out_for_delivery', 'delivered']) ? 'text-success' : 'text-muted' }}">
                <i class="bi bi-check-circle fs-3"></i>
                <div class="small fw-bold">Confirmed</div>
            </div>
            <div
                class="text-center {{ in_array($order->order_status, ['processing', 'ready', 'out_for_delivery', 'delivered']) ? 'text-success' : 'text-muted' }}">
                <i class="bi bi-gear fs-3"></i>
                <div class="small fw-bold">Processing</div>
            </div>
            <div
                class="text-center {{ in_array($order->order_status, ['ready', 'out_for_delivery', 'delivered']) ? 'text-success' : 'text-muted' }}">
                <i class="bi bi-box-seam fs-3"></i>
                <div class="small fw-bold">Ready</div>
            </div>
            <div
                class="text-center {{ in_array($order->order_status, ['out_for_delivery', 'delivered']) ? 'text-success' : 'text-muted' }}">
                <i class="bi bi-truck fs-3"></i>
                <div class="small fw-bold">Out for Delivery</div>
            </div>
            <div class="text-center {{ $order->order_status == 'delivered' ? 'text-success' : 'text-muted' }}">
                <i class="bi bi-check-circle-fill fs-3"></i>
                <div class="small fw-bold">Delivered</div>
            </div>
        </div>
    </div>

    <!-- Delivery Details -->
    @if ($delivery)
        <div class="row">
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6><i class="bi bi-truck"></i> Delivery Information</h6>
                        <p><strong>Tracking Number:</strong> {{ $delivery->tracking_number }}</p>
                        <p><strong>Status:</strong>
                            <span
                                class="badge bg-{{ $delivery->status == 'delivered' ? 'success' : ($delivery->status == 'in_transit' ? 'warning' : 'info') }}">
                                {{ ucfirst($delivery->status) }}
                            </span>
                        </p>
                        @if ($delivery->driver)
                            <p><strong>Driver:</strong> {{ $delivery->driver->name }}</p>
                            <p><strong>Driver Contact:</strong> {{ $delivery->driver->phone ?? 'N/A' }}</p>
                        @endif
                        @if ($delivery->assigned_at)
                            <p><strong>Assigned:</strong> {{ $delivery->assigned_at->format('M d, Y h:i A') }}</p>
                        @endif
                        @if ($delivery->picked_up_at)
                            <p><strong>Picked Up:</strong> {{ $delivery->picked_up_at->format('M d, Y h:i A') }}</p>
                        @endif
                        @if ($delivery->delivered_at)
                            <p><strong>Delivered:</strong> {{ $delivery->delivered_at->format('M d, Y h:i A') }}</p>
                        @endif
                        @if ($delivery->driver_notes)
                            <p><strong>Driver Notes:</strong> {{ $delivery->driver_notes }}</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6><i class="bi bi-geo-alt"></i> Delivery Address</h6>
                        <p>{{ $order->delivery_address }}</p>
                        <p>{{ $order->barangay }}, {{ $order->city }}</p>
                        @if ($order->landmark)
                            <p><strong>Landmark:</strong> {{ $order->landmark }}</p>
                        @endif
                        <p><strong>Recipient:</strong> {{ $order->customer_name }}</p>
                        <p><strong>Contact:</strong> {{ $order->customer_phone }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Proof of Delivery & Payment -->
        @if ($delivery->delivery_proof || $delivery->payment_proof)
            <div class="row mt-3">
                @if ($delivery->delivery_proof)
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-success text-white">Delivery Proof</div>
                            <div class="card-body text-center">
                                <a href="{{ Storage::url($delivery->delivery_proof) }}" target="_blank">
                                    <img src="{{ Storage::url($delivery->delivery_proof) }}" class="img-fluid rounded"
                                        style="max-height: 150px;">
                                </a>
                                <div class="mt-2">
                                    <a href="{{ Storage::url($delivery->delivery_proof) }}" target="_blank"
                                        class="btn btn-sm btn-outline-primary">View Full Image</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if ($delivery->payment_proof)
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-info text-white">Payment Proof</div>
                            <div class="card-body text-center">
                                <a href="{{ Storage::url($delivery->payment_proof) }}" target="_blank">
                                    <img src="{{ Storage::url($delivery->payment_proof) }}" class="img-fluid rounded"
                                        style="max-height: 150px;">
                                </a>
                                <div class="mt-2">
                                    <a href="{{ Storage::url($delivery->payment_proof) }}" target="_blank"
                                        class="btn btn-sm btn-outline-success">View Full Image</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Live Location (if shared) -->
        @if ($delivery->driver_latitude && $delivery->driver_longitude)
            <div class="mt-3">
                <div class="alert alert-secondary">
                    <i class="bi bi-geo-alt-fill"></i> Driver's last known location:
                    <a href="https://maps.google.com/?q={{ $delivery->driver_latitude }},{{ $delivery->driver_longitude }}"
                        target="_blank">
                        View on Map
                    </a>
                </div>
            </div>
        @endif
    @else
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Your order is being prepared. Once dispatched, tracking information will
            appear here.
        </div>
    @endif

    <div class="mt-4 text-center">
        <button onclick="closeTrackModal()" class="btn btn-secondary rounded-pill">
            <i class="bi bi-x-circle"></i> Close
        </button>
        <button onclick="window.location.reload()" class="btn btn-primary rounded-pill">
            <i class="bi bi-arrow-repeat"></i> Refresh Status
        </button>
    </div>
</div>
