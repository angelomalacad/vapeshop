@extends('layouts.customer')

@section('content')
    <div class="container order-detail-container">
        <div class="d-flex justify-content-between align-items-center mb-4 order-detail-header">
            <h2 class="order-detail-title">Order #{{ $order->order_number }}</h2>
            <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-secondary rounded-pill back-btn">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <!-- Order Items Card -->
                <div class="card shadow-sm border-0 mb-4 detail-card">
                    <div class="card-header bg-white fw-semibold detail-card-header">
                        <i class="bi bi-receipt me-2"></i> Order Items
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table mb-0 order-items-table">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Price</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center order-item-product">
                                                    @php
                                                        $inventory = \App\Models\BranchInventory::with('product')->find(
                                                            $item->inventory_id,
                                                        );
                                                        $imageUrl = null;
                                                        if (
                                                            $inventory &&
                                                            $inventory->product &&
                                                            $inventory->product->image
                                                        ) {
                                                            $imageUrl = \Storage::url($inventory->product->image);
                                                        }
                                                    @endphp
                                                    <div class="flex-shrink-0 me-3 order-item-img-wrapper">
                                                        @if ($imageUrl)
                                                            <img src="{{ $imageUrl }}" alt="{{ $item->product->name }}"
                                                                class="order-item-img"
                                                                style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                                        @else
                                                            <div class="order-item-img order-item-img-placeholder"
                                                                style="width: 60px; height: 60px; background: #f8f9fa; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #adb5bd;">
                                                                <i class="bi bi-image"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="order-item-info">
                                                        <div class="fw-semibold">{{ $item->product->name }}</div>
                                                        @if ($item->flavor)
                                                            <div class="small text-muted">Variant: {{ $item->flavor->name }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center order-item-qty">{{ $item->quantity }}</td>
                                            <td class="text-end order-item-price">₱{{ number_format($item->price, 2) }}</td>
                                            <td class="text-end order-item-subtotal">
                                                ₱{{ number_format($item->subtotal, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Subtotal:</td>
                                        <td class="text-end fw-bold">₱{{ number_format($order->subtotal, 2) }}</td>
                                    </tr>
                                    {{-- ✅ Delivery Fee row (black text, aligned with Subtotal) --}}
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Delivery Fee:</td>
                                        <td class="text-end fw-bold">₱{{ number_format($order->delivery_fee ?? 0, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold fs-5">Total:</td>
                                        <td class="text-end fw-bold fs-5 text-danger">
                                            ₱{{ number_format($order->total_amount, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Status Timeline Card -->
                <div class="card shadow-sm border-0 mb-4 detail-card">
                    <div class="card-header bg-white fw-semibold detail-card-header">
                        <i class="bi bi-clock-history me-2"></i> Order Status Timeline
                    </div>
                    <div class="card-body">
                        @php
                            // ✅ NEW: Driver marked the delivery as failed
                            $isDeliveryFailed = $order->order_status === 'delivery_failed';
                        @endphp
                        <!-- Main Order Status Timeline -->
                        <div class="status-timeline">
                            <!-- Status Timeline -->
                            <div class="status-steps">
                                <!-- Pending -->
                                <div
                                    class="status-step {{ $order->order_status == 'pending' ? 'active' : ($order->order_status != 'pending' && $order->order_status != 'cancelled' ? 'completed' : '') }}">
                                    <div class="status-icon"><i class="bi bi-clock-history"></i></div>
                                    <div class="status-label">Pending</div>
                                    <div class="status-date">{{ $order->created_at->format('M d, Y') }}</div>
                                    <div class="status-time">{{ $order->created_at->format('h:i A') }}</div>
                                </div>

                                <!-- Confirmed -->
                                <div
                                    class="status-step {{ $order->order_status == 'confirmed' ? 'active' : (in_array($order->order_status, ['processing', 'ready', 'picked_up', 'out_for_delivery', 'delivered']) ? 'completed' : '') }} {{ $isDeliveryFailed ? 'completed' : '' }}">
                                    <div class="status-icon"><i class="bi bi-check-circle"></i></div>
                                    <div class="status-label">Confirmed</div>
                                    @if ($statusTimestamps['confirmed'])
                                        <div class="status-date">{{ $statusTimestamps['confirmed']->format('M d, Y') }}
                                        </div>
                                        <div class="status-time">{{ $statusTimestamps['confirmed']->format('h:i A') }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Processing -->
                                <div
                                    class="status-step {{ $order->order_status == 'processing' ? 'active' : (in_array($order->order_status, ['ready', 'picked_up', 'out_for_delivery', 'delivered']) ? 'completed' : '') }} {{ $isDeliveryFailed ? 'completed' : '' }}">
                                    <div class="status-icon"><i class="bi bi-box-seam"></i></div>
                                    <div class="status-label">Processing</div>
                                    @if ($statusTimestamps['packing'])
                                        <div class="status-date">{{ $statusTimestamps['packing']->format('M d, Y') }}</div>
                                        <div class="status-time">{{ $statusTimestamps['packing']->format('h:i A') }}</div>
                                    @endif
                                </div>

                                <!-- Ready -->
                                <div
                                    class="status-step {{ $order->order_status == 'ready' ? 'active' : (in_array($order->order_status, ['picked_up', 'out_for_delivery', 'delivered']) ? 'completed' : '') }} {{ $isDeliveryFailed ? 'completed' : '' }}">
                                    <div class="status-icon"><i class="bi bi-check-circle-fill"></i></div>
                                    <div class="status-label">Ready</div>
                                    @if ($statusTimestamps['ready'])
                                        <div class="status-date">{{ $statusTimestamps['ready']->format('M d, Y') }}</div>
                                        <div class="status-time">{{ $statusTimestamps['ready']->format('h:i A') }}</div>
                                    @endif
                                </div>

                                <!-- Picked Up -->
                                <div
                                    class="status-step {{ $order->order_status == 'picked_up' ? 'active' : (in_array($order->order_status, ['out_for_delivery', 'delivered']) ? 'completed' : '') }} {{ $isDeliveryFailed ? 'completed' : '' }}">
                                    <div class="status-icon"><i class="bi bi-box-seam"></i></div>
                                    <div class="status-label">Picked Up</div>
                                    @if ($statusTimestamps['picked_up'])
                                        <div class="status-date">{{ $statusTimestamps['picked_up']->format('M d, Y') }}
                                        </div>
                                        <div class="status-time">{{ $statusTimestamps['picked_up']->format('h:i A') }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Out for Delivery -->
                                <div
                                    class="status-step {{ $order->order_status == 'out_for_delivery' ? 'active' : ($order->order_status == 'delivered' ? 'completed' : '') }} {{ $isDeliveryFailed ? 'completed failed-link' : '' }}">
                                    <div class="status-icon"><i class="bi bi-truck"></i></div>
                                    <div class="status-label">Out for Delivery</div>
                                    @if ($statusTimestamps['out_for_delivery'])
                                        <div class="status-date">
                                            {{ $statusTimestamps['out_for_delivery']->format('M d, Y') }}</div>
                                        <div class="status-time">
                                            {{ $statusTimestamps['out_for_delivery']->format('h:i A') }}</div>
                                    @endif
                                </div>

                                <!-- Delivered -->
                                @if ($isDeliveryFailed)
                                    <!-- Delivery Failed (replaces Delivered) -->
                                    <div class="status-step failed">
                                        <div class="status-icon"><i class="bi bi-x-circle-fill"></i></div>
                                        <div class="status-label">Delivery Failed</div>
                                        @if (!empty($statusTimestamps['failed']))
                                            <div class="status-date">{{ $statusTimestamps['failed']->format('M d, Y') }}
                                            </div>
                                            <div class="status-time">{{ $statusTimestamps['failed']->format('h:i A') }}
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="status-step {{ $order->order_status == 'delivered' ? 'active' : '' }}">
                                        <div class="status-icon"><i class="bi bi-flag-fill"></i></div>
                                        <div class="status-label">Delivered</div>
                                        @if ($statusTimestamps['delivered'])
                                            <div class="status-date">{{ $statusTimestamps['delivered']->format('M d, Y') }}
                                            </div>
                                            <div class="status-time">{{ $statusTimestamps['delivered']->format('h:i A') }}
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if ($isDeliveryFailed)
                            @php $failedAt = $statusTimestamps['failed'] ?? null; @endphp
                            <div class="delivery-failed-alert mt-3">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    <div>
                                        <strong>Delivery Failed</strong>
                                        @if ($failedAt)
                                            <div class="small">{{ $failedAt->format('F d, Y h:i A') }}</div>
                                        @endif
                                        <div class="mt-2">
                                            <strong>Reason:</strong>
                                            {{ $order->delivery && !empty($order->delivery->driver_notes) ? $order->delivery->driver_notes : 'No reason was provided by the driver.' }}
                                        </div>
                                        <div class="small mt-2">Please contact support if you need help with your order.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @php
                            $cityLower = strtolower(trim($order->city ?? ''));
                            $isCalambaCity = $cityLower === 'calamba city' || $cityLower === 'calamba';
                            $isLalamoveEligible = !$isCalambaCity;
                        @endphp

                        <!-- Delivery Logs -->
                        @if (
                            $order->delivery_type == 'delivery' &&
                                $order->delivery &&
                                in_array($order->order_status, ['out_for_delivery', 'delivered']))
                            <div class="delivery-logs mt-4">
                                <h6 class="mb-3"><i class="bi bi-truck me-2"></i> Delivery Logs</h6>
                                <div class="delivery-timeline">
                                    @if ($order->delivery->assigned_at)
                                        <div class="delivery-log-item">
                                            <div class="delivery-log-icon assigned"><i class="bi bi-person-check"></i>
                                            </div>
                                            <div class="delivery-log-content">
                                                <div class="delivery-log-title">Assigned to Driver</div>
                                                <div class="delivery-log-date">
                                                    {{ $order->delivery->assigned_at->format('F d, Y') }}</div>
                                                <div class="delivery-log-time">
                                                    {{ $order->delivery->assigned_at->format('h:i A') }}</div>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($order->delivery->picked_up_at)
                                        <div class="delivery-log-item">
                                            <div class="delivery-log-icon picked"><i class="bi bi-box-seam"></i></div>
                                            <div class="delivery-log-content">
                                                <div class="delivery-log-title">Picked Up</div>
                                                <div class="delivery-log-date">
                                                    {{ $order->delivery->picked_up_at->format('F d, Y') }}</div>
                                                <div class="delivery-log-time">
                                                    {{ $order->delivery->picked_up_at->format('h:i A') }}</div>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($statusTimestamps['in_transit'])
                                        <div class="delivery-log-item">
                                            <div class="delivery-log-icon transit"><i class="bi bi-truck"></i></div>
                                            <div class="delivery-log-content">
                                                <div class="delivery-log-title">In Transit</div>
                                                <div class="delivery-log-date">
                                                    {{ $statusTimestamps['in_transit']->format('F d, Y') }}</div>
                                                <div class="delivery-log-time">
                                                    {{ $statusTimestamps['in_transit']->format('h:i A') }}</div>
                                                <div class="delivery-log-note">Driver is on the way to your location</div>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($order->delivery->delivered_at)
                                        <div class="delivery-log-item">
                                            <div class="delivery-log-icon delivered"><i
                                                    class="bi bi-check-circle-fill"></i></div>
                                            <div class="delivery-log-content">
                                                <div class="delivery-log-title">Delivered</div>
                                                <div class="delivery-log-date">
                                                    {{ $order->delivery->delivered_at->format('F d, Y') }}</div>
                                                <div class="delivery-log-time">
                                                    {{ $order->delivery->delivered_at->format('h:i A') }}</div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Driver Info -->
                                @if ($order->delivery->driver || !empty($order->delivery->notes))
                                    <div class="driver-info mt-3 p-3 bg-light rounded">
                                        <div class="d-flex align-items-center">
                                            <div class="driver-avatar me-3"><i
                                                    class="bi bi-person-circle fs-1 text-primary"></i></div>
                                            <div>
                                                <strong>{{ $order->delivery->driver->name ?? $order->delivery->notes }}</strong><br>
                                                <small class="text-muted">
                                                    @if ($order->delivery->driver)
                                                        Contact: {{ $order->delivery->driver->phone ?? 'N/A' }}
                                                    @else
                                                        Lalamove Courier
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{-- Lalamove Tracking --}}
                                @if ($isLalamoveEligible)
                                    <div class="mt-3">
                                        <div
                                            class="d-flex align-items-center justify-content-between bg-white border rounded p-2 shadow-sm lalamove-tracking-box">
                                            <div>
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-truck text-primary me-1"
                                                        style="font-size: 0.9rem;"></i>
                                                    <span class="fw-semibold small text-primary">Lalamove Tracking</span>
                                                </div>
                                                @if ($order->delivery->tracking_number && filter_var($order->delivery->tracking_number, FILTER_VALIDATE_URL))
                                                    <div class="small text-success mt-1"><i
                                                            class="bi bi-check-circle-fill me-1"></i> Link ready</div>
                                                @else
                                                    <div class="small text-muted mt-1">Link available when out for
                                                        delivery.</div>
                                                @endif
                                            </div>
                                            @if ($order->delivery->tracking_number && filter_var($order->delivery->tracking_number, FILTER_VALIDATE_URL))
                                                <a href="{{ $order->delivery->tracking_number }}" target="_blank"
                                                    class="btn btn-primary btn-sm px-3 track-btn"
                                                    style="font-size: 0.85rem;"><i class="bi bi-eye me-1"></i> Track</a>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Delivery Details Card with Proof Images -->
                @if ($order->delivery_type == 'delivery' && $order->delivery)
                    <div class="card shadow-sm border-0 detail-card">
                        <div class="card-header bg-white fw-semibold detail-card-header">
                            <i class="bi bi-geo-alt me-2"></i> Delivery Address
                        </div>
                        <div class="card-body">
                            <p class="mb-1">{{ $order->delivery_address }}</p>
                            <p class="mb-1">
                            <p class="mb-1">
                                {{ $order->barangay === 'Other' && $order->other_barangay ? $order->other_barangay : $order->barangay }},
                                {{ $order->city }}, Laguna</p>
                            @if ($order->landmark)
                                <p class="mb-0 text-muted"><small>Landmark: {{ $order->landmark }}</small></p>
                            @endif
                            <hr>
                            <p class="mb-0"><strong>Recipient:</strong> {{ $order->customer_name }}</p>
                            <p class="mb-0"><strong>Contact:</strong> {{ $order->customer_phone }}</p>

                            @if ($order->delivery->delivery_proof || $order->delivery->payment_proof)
                                <hr>
                                <div class="row mt-2">
                                    @if ($order->delivery->delivery_proof)
                                        <div class="col-md-6 mb-3">
                                            <strong>Delivery Proof:</strong>
                                            <div class="mt-2">
                                                <img src="{{ Storage::url($order->delivery->delivery_proof) }}"
                                                    class="img-thumbnail proof-thumbnail"
                                                    style="width: 100%; max-height: 150px; object-fit: cover; cursor: pointer;"
                                                    onclick="showImagePreview('{{ Storage::url($order->delivery->delivery_proof) }}', 'Delivery Proof')">
                                            </div>
                                            <div class="mt-2"><a
                                                    href="{{ Storage::url($order->delivery->delivery_proof) }}"
                                                    target="_blank" class="btn btn-sm btn-outline-primary"><i
                                                        class="bi bi-box-arrow-up-right"></i> View Full</a></div>
                                        </div>
                                    @endif
                                    @if ($order->delivery->payment_proof)
                                        <div class="col-md-6 mb-3">
                                            <strong>Payment Proof:</strong>
                                            <div class="mt-2">
                                                <img src="{{ Storage::url($order->delivery->payment_proof) }}"
                                                    class="img-thumbnail proof-thumbnail"
                                                    style="width: 100%; max-height: 150px; object-fit: cover; cursor: pointer;"
                                                    onclick="showImagePreview('{{ Storage::url($order->delivery->payment_proof) }}', 'Payment Proof')">
                                            </div>
                                            <div class="mt-2"><a
                                                    href="{{ Storage::url($order->delivery->payment_proof) }}"
                                                    target="_blank" class="btn btn-sm btn-outline-success"><i
                                                        class="bi bi-box-arrow-up-right"></i> View Full</a></div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-4">
                <!-- Order Information Card -->
                <div class="card shadow-sm border-0 detail-card">
                    <div class="card-header bg-white fw-semibold detail-card-header">
                        <i class="bi bi-info-circle me-2"></i> Order Information
                    </div>
                    <div class="card-body order-info-body">
                        <p><strong>Order Number:</strong></p>
                        <p><code>{{ $order->order_number }}</code></p>
                        <p><strong>Date placed:</strong></p>
                        <p>{{ $order->created_at->format('F d, Y h:i A') }}</p>
                        <p><strong>Branch:</strong></p>
                        <p>{{ $order->branch->name }}</p>
                        <p><strong>Payment Method:</strong></p>
                        <p>{{ strtoupper($order->payment_method) }}</p>

                        <!-- Delivery Date (From – To) - BLUE -->
                        <p><strong>Delivery Date:</strong></p>
                        <p class="order-delivery-date" style="color: #0d6efd; font-weight: 600;">
                            @php
                                $deliveryFrom = $order->delivery_date_from
                                    ? \Carbon\Carbon::parse($order->delivery_date_from)
                                    : null;
                                $deliveryTo = $order->delivery_date_to
                                    ? \Carbon\Carbon::parse($order->delivery_date_to)
                                    : null;

                                if ($deliveryFrom && $deliveryTo) {
                                    if ($deliveryFrom->eq($deliveryTo)) {
                                        $deliveryDisplay = $deliveryFrom->format('F d, Y');
                                    } else {
                                        $deliveryDisplay =
                                            $deliveryFrom->format('F d, Y') . ' – ' . $deliveryTo->format('F d, Y');
                                    }
                                } elseif ($deliveryFrom) {
                                    $deliveryDisplay = $deliveryFrom->format('F d, Y');
                                } elseif ($deliveryTo) {
                                    $deliveryDisplay = $deliveryTo->format('F d, Y');
                                } elseif ($order->delivery_date) {
                                    $deliveryDisplay = $order->delivery_date->format('F d, Y');
                                } else {
                                    $deliveryDisplay = 'Pending';
                                }
                            @endphp
                            {{ $deliveryDisplay }}
                        </p>

                        @if ($order->notes)
                            <hr>
                            <p><strong>Your Notes:</strong></p>
                            <p class="text-muted">{{ $order->notes }}</p>
                        @endif
                    </div>
                </div>

                <!-- Need Help Card -->
                <div class="card shadow-sm border-0 mt-4 detail-card">
                    <div class="card-header bg-white fw-semibold detail-card-header">
                        <i class="bi bi-question-circle me-2"></i> Need Help?
                    </div>
                    <div class="card-body text-center need-help-body">
                        <i class="bi bi-headset display-4 text-primary mb-3 d-block"></i>
                        <p>Have questions about your order?</p>
                        <button class="btn btn-outline-primary rounded-pill contact-support-btn" onclick="openGmail()">
                            <i class="bi bi-envelope me-1"></i> Contact Support
                        </button>
                    </div>
                </div>

                <script>
                    function openGmail() {
                        const email = 'vapeexpo2024@gmail.com';
                        const subject = encodeURIComponent('Customer Support Inquiry');
                        const url = `https://mail.google.com/mail/?view=cm&fs=1&to=${email}&su=${subject}`;
                        window.open(url, '_blank');
                    }
                </script>

                <!-- Image Preview Modal -->
                <div id="imagePreviewModal"
                    style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 10000; justify-content: center; align-items: center;">
                    <div style="background: white; border-radius: 8px; width: 90%; max-width: 600px; overflow: hidden;">
                        <div
                            style="padding: 10px 15px; background: #f8f9fa; border-bottom: 1px solid #ddd; display: flex; justify-content: space-between; align-items: center;">
                            <h6 class="mb-0" id="previewTitle">Image Preview</h6>
                            <button type="button" class="btn-close" onclick="closeImagePreview()"></button>
                        </div>
                        <div style="padding: 20px; text-align: center;">
                            <img id="previewImage" src=""
                                style="max-width: 100%; max-height: 400px; border-radius: 5px;">
                        </div>
                        <div
                            style="padding: 10px 15px; background: #f8f9fa; border-top: 1px solid #ddd; text-align: right;">
                            <button type="button" class="btn btn-sm btn-secondary"
                                onclick="closeImagePreview()">Close</button>
                            <a id="downloadLink" href="#" download class="btn btn-sm btn-primary">Download</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .status-timeline {
            padding: 10px 0;
        }

        .status-steps {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }

        .status-step {
            flex: 1;
            text-align: center;
            position: relative;
            min-width: 100px;
        }

        .status-step:not(:last-child):before {
            content: '';
            position: absolute;
            top: 25px;
            right: -50%;
            width: 100%;
            height: 3px;
            background: #e9ecef;
            z-index: 0;
        }

        .status-step.completed:not(:last-child):before {
            background: #28a745;
        }

        .status-step.active:not(:last-child):before {
            background: #28a745;
        }

        .status-icon {
            width: 55px;
            height: 55px;
            margin: 0 auto 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #e9ecef;
            color: #6c757d;
            position: relative;
            z-index: 1;
            transition: all 0.3s ease;
        }

        .status-step.completed .status-icon {
            background: #28a745;
            color: white;
        }

        .status-step.active .status-icon {
            background: #28a745;
            color: white;
            box-shadow: 0 0 0 5px rgba(40, 167, 69, 0.2);
        }

        .status-label {
            font-weight: 600;
            font-size: 13px;
            margin-bottom: 5px;
            color: #6c757d;
        }

        .status-step.completed .status-label,
        .status-step.active .status-label {
            color: #28a745;
        }

        .status-date,
        .status-time {
            font-size: 11px;
            color: #adb5bd;
        }

        .status-step.completed .status-date,
        .status-step.completed .status-time,
        .status-step.active .status-date,
        .status-step.active .status-time {
            color: #6c757d;
        }

        .delivery-logs {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 15px;
        }

        .delivery-timeline {
            position: relative;
        }

        .delivery-log-item {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            position: relative;
        }

        .delivery-log-item:not(:last-child):before {
            content: '';
            position: absolute;
            left: 22px;
            top: 40px;
            bottom: -20px;
            width: 2px;
            background: #dee2e6;
        }

        .delivery-log-icon {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            z-index: 1;
            background: white;
            border: 2px solid;
        }

        .delivery-log-icon.assigned {
            border-color: #0d6efd;
            color: #0d6efd;
        }

        .delivery-log-icon.picked {
            border-color: #6f42c1;
            color: #6f42c1;
        }

        .delivery-log-icon.transit {
            border-color: #fd7e14;
            color: #fd7e14;
        }

        .delivery-log-icon.delivered {
            border-color: #28a745;
            color: #28a745;
        }

        .delivery-log-content {
            flex: 1;
        }

        .delivery-log-title {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 3px;
            color: #212529;
        }

        .delivery-log-date,
        .delivery-log-time {
            font-size: 11px;
            color: #6c757d;
            display: inline-block;
        }

        .delivery-log-time:before {
            content: '•';
            margin: 0 5px;
        }

        .delivery-log-note {
            font-size: 12px;
            color: #6c757d;
            margin-top: 3px;
        }

        .driver-info {
            border-left: 3px solid #0d6efd;
        }

        .proof-thumbnail {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .proof-thumbnail:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .lalamove-log-box {
            padding: 8px 12px !important;
            margin-top: 10px !important;
            border-radius: 8px !important;
            border: 1px solid #0d6efd !important;
            background: #ffffff !important;
        }

        .lalamove-log-box .lalamove-header {
            font-size: 13px !important;
            font-weight: 600 !important;
            color: #0d6efd !important;
            margin-bottom: 5px !important;
        }

        .lalamove-log-box .lalamove-header i {
            margin-right: 4px !important;
        }

        .lalamove-log-box p {
            font-size: 12px !important;
            margin-bottom: 6px !important;
        }

        .lalamove-log-box .btn-sm {
            font-size: 12px !important;
            padding: 4px 12px !important;
        }

        /* ===== MOBILE APP-LIKE STYLES ===== */
        @media (max-width: 767.98px) {
            .order-detail-container {
                padding-left: 14px;
                padding-right: 14px;
            }

            /* Header */
            .order-detail-header {
                margin-bottom: 1rem !important;
                gap: 0.6rem;
                flex-wrap: wrap;
            }

            .order-detail-title {
                font-size: 1.05rem;
                margin-bottom: 0;
                word-break: break-all;
                line-height: 1.3;
            }

            .back-btn {
                padding: 0.4rem 0.75rem;
                font-size: 0.78rem;
                white-space: nowrap;
            }

            /* Detail Cards */
            .detail-card {
                border-radius: 16px !important;
                overflow: hidden;
                box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04) !important;
            }

            .detail-card-header {
                padding: 0.85rem 1rem;
                font-size: 0.88rem;
                border-radius: 16px 16px 0 0 !important;
            }

            .detail-card .card-body:not(.p-0) {
                padding: 1rem;
            }

            /* Order Items Table - Card-like rows */
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
                padding: 0.25rem 0;
                border: none;
                text-align: left !important;
                font-size: 0.82rem;
            }

            .order-items-table td:first-child {
                padding-bottom: 0.6rem;
                padding-right: 0;
            }

            .order-item-product {
                gap: 0.7rem !important;
            }

            .order-item-img,
            .order-item-img-placeholder {
                width: 52px !important;
                height: 52px !important;
                border-radius: 10px !important;
                flex-shrink: 0;
            }

            .order-item-info {
                min-width: 0;
                flex: 1;
            }

            .order-item-info .fw-semibold {
                font-size: 0.88rem;
                color: #1a1a2e;
                line-height: 1.3;
                word-break: break-word;
            }

            .order-item-info .small {
                font-size: 0.72rem;
            }

            /* Inline qty/price/total */
            .order-items-table td.order-item-qty,
            .order-items-table td.order-item-price,
            .order-items-table td.order-item-subtotal {
                display: inline-block;
                width: auto;
                padding-right: 0.75rem;
                font-size: 0.78rem;
                color: #475569;
            }

            .order-items-table td.order-item-qty::before {
                content: 'Qty: ';
                font-weight: 600;
                color: #94a3b8;
            }

            .order-items-table td.order-item-price::before {
                content: '@ ';
                font-weight: 500;
                color: #94a3b8;
            }

            .order-items-table td.order-item-subtotal {
                float: right;
                padding-right: 0;
                font-weight: 600;
                color: #e74c3c;
                font-size: 0.88rem;
            }

            /* Table footer (subtotal & total) */
            .order-items-table tfoot tr {
                display: flex;
                justify-content: space-between;
                padding: 0.6rem 1rem;
                background: #f8fafc;
                border-top: 1px solid #eef2f6;
            }

            .order-items-table tfoot td {
                padding: 0 !important;
                border: none;
                font-size: 0.82rem;
            }

            .order-items-table tfoot td:first-child {
                text-align: left !important;
            }

            .order-items-table tfoot tr:last-child {
                background: #fff5f5;
                padding: 0.75rem 1rem;
            }

            .order-items-table tfoot tr:last-child td {
                font-size: 1rem !important;
            }

            /* Status Timeline - Vertical Mobile Style */
            .status-steps {
                flex-direction: column;
                gap: 0;
                position: relative;
                padding-left: 0.5rem;
            }

            /* Vertical connector line */
            .status-steps::before {
                content: '';
                position: absolute;
                left: 27px;
                top: 30px;
                bottom: 30px;
                width: 3px;
                background: #e9ecef;
                border-radius: 2px;
                z-index: 0;
            }

            .status-step {
                display: flex !important;
                align-items: flex-start;
                text-align: left;
                gap: 0.85rem;
                padding: 0.5rem 0;
                min-width: 0;
                flex: none;
                position: relative;
                z-index: 1;
            }

            .status-step:not(:last-child):before {
                display: none !important;
            }

            .status-icon {
                width: 44px;
                height: 44px;
                min-width: 44px;
                margin: 0 !important;
                font-size: 1.05rem;
                background: #f1f5f9;
                border: 2px solid #fff;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
            }

            .status-step.completed .status-icon,
            .status-step.active .status-icon {
                background: #28a745;
                color: white;
                box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
            }

            .status-step.active .status-icon {
                animation: pulseStatus 1.5s ease-in-out infinite;
            }

            @keyframes pulseStatus {

                0%,
                100% {
                    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3), 0 0 0 0 rgba(40, 167, 69, 0.4);
                }

                50% {
                    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3), 0 0 0 8px rgba(40, 167, 69, 0);
                }
            }

            /* Status content layout */
            .status-step>div:not(.status-icon) {
                flex: 1;
                padding-top: 0.4rem;
            }

            .status-label {
                font-size: 0.88rem;
                margin-bottom: 0.15rem;
                text-align: left;
                color: #1a1a2e;
            }

            .status-step.completed .status-label,
            .status-step.active .status-label {
                color: #28a745;
            }

            .status-date,
            .status-time {
                font-size: 0.72rem;
                text-align: left;
                display: inline-block;
                color: #94a3b8;
            }

            .status-time {
                margin-left: 0.4rem;
            }

            /* Delivery Logs */
            .delivery-logs {
                padding: 0.85rem;
                border-radius: 14px;
            }

            .delivery-logs h6 {
                font-size: 0.88rem;
                margin-bottom: 0.75rem !important;
            }

            .delivery-log-item {
                gap: 0.75rem;
                margin-bottom: 1rem;
            }

            .delivery-log-item:not(:last-child):before {
                left: 19px;
                top: 36px;
                bottom: -18px;
            }

            .delivery-log-icon {
                width: 38px;
                height: 38px;
                font-size: 0.88rem;
            }

            .delivery-log-title {
                font-size: 0.85rem;
            }

            .delivery-log-date,
            .delivery-log-time {
                font-size: 0.7rem;
            }

            .delivery-log-note {
                font-size: 0.72rem;
            }

            /* Driver Info */
            .driver-info {
                padding: 0.85rem !important;
            }

            .driver-avatar i {
                font-size: 2rem !important;
            }

            .driver-info strong {
                font-size: 0.85rem;
            }

            .driver-info small {
                font-size: 0.72rem;
            }

            /* Lalamove Tracking */
            .lalamove-tracking-box {
                padding: 0.75rem !important;
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .lalamove-tracking-box .track-btn {
                width: 100%;
                padding: 0.55rem 1rem !important;
                font-size: 0.82rem !important;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            /* Delivery Address section */
            .detail-card .card-body p {
                font-size: 0.85rem;
                margin-bottom: 0.35rem;
                line-height: 1.5;
            }

            .detail-card .card-body strong {
                font-size: 0.82rem;
                color: #374151;
            }

            .detail-card .card-body hr {
                margin: 0.75rem 0;
            }

            /* Proof images */
            .proof-thumbnail {
                max-height: 200px !important;
            }

            /* Order Info Body */
            .order-info-body p {
                font-size: 0.85rem;
                margin-bottom: 0.4rem;
                line-height: 1.5;
            }

            .order-info-body p strong {
                font-size: 0.8rem;
                color: #374151;
                text-transform: uppercase;
                letter-spacing: 0.3px;
                font-weight: 600;
            }

            .order-info-body code {
                font-size: 0.82rem;
                word-break: break-all;
                background: #f1f5f9;
                padding: 0.35rem 0.55rem;
                border-radius: 8px;
                display: inline-block;
                color: #0d6efd;
                font-weight: 600;
            }

            .order-info-body hr {
                margin: 0.85rem 0;
            }

            .order-delivery-date {
                font-size: 0.88rem;
                line-height: 1.4;
            }

            /* Need Help Card */
            .need-help-body {
                padding: 1.25rem 1rem !important;
            }

            .need-help-body i.display-4 {
                font-size: 2.5rem !important;
            }

            .need-help-body p {
                font-size: 0.85rem;
                margin-bottom: 0.85rem;
            }

            .contact-support-btn {
                padding: 0.6rem 1.25rem;
                font-size: 0.85rem;
                width: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .contact-support-btn:active {
                transform: scale(0.98);
            }

            /* Image preview modal mobile */
            #imagePreviewModal>div {
                width: 94% !important;
                border-radius: 14px !important;
                margin: 1rem;
            }

            #imagePreviewModal #previewImage {
                max-height: 55vh !important;
            }

            #imagePreviewModal .btn {
                font-size: 0.78rem;
                padding: 0.4rem 0.75rem;
            }
        }

        /* Extra small devices */
        @media (max-width: 380px) {
            .order-detail-title {
                font-size: 0.95rem;
            }

            .back-btn {
                font-size: 0.72rem;
                padding: 0.35rem 0.65rem;
            }

            .order-item-info .fw-semibold {
                font-size: 0.82rem;
            }

            .status-icon {
                width: 38px;
                height: 38px;
                min-width: 38px;
                font-size: 0.95rem;
            }

            .status-steps::before {
                left: 24px;
            }

            .status-label {
                font-size: 0.82rem;
            }

            .detail-card-header {
                font-size: 0.82rem;
                padding: 0.75rem 0.9rem;
            }
        }

        /* Tablet */
        @media (min-width: 768px) and (max-width: 991.98px) {
            .status-step {
                min-width: 85px;
            }

            .status-icon {
                width: 48px;
                height: 48px;
            }

            .status-label {
                font-size: 12px;
            }

            .status-date,
            .status-time {
                font-size: 10px;
            }
        }

        /* Touch device */
        @media (hover: none) {
            .proof-thumbnail:hover {
                transform: none;
                box-shadow: none;
            }

            .proof-thumbnail:active {
                transform: scale(0.98);
            }
        }

        /* ===== DESKTOP/LAPTOP FIX: keep all 7 timeline steps on one row ===== */
        @media (min-width: 768px) {
            .status-steps {
                flex-wrap: nowrap;
                gap: 0;
            }

            .status-step {
                flex: 1 1 0;
                min-width: 0;
                padding: 0 2px;
            }

            .status-label {
                line-height: 1.25;
            }
        }

        /* Slightly smaller text on laptop-width screens so labels fit under each icon */
        @media (min-width: 768px) and (max-width: 1199.98px) {
            .status-icon {
                width: 46px;
                height: 46px;
            }

            .status-step:not(:last-child):before {
                top: 22px;
            }

            .status-label {
                font-size: 12px;
            }

            .status-date,
            .status-time {
                font-size: 10px;
            }
        }

        /* ===== FAILED DELIVERY STATE ===== */
        .status-step.failed .status-icon {
            background: #dc3545;
            color: white;
            box-shadow: 0 0 0 5px rgba(220, 53, 69, 0.2);
        }

        .status-step.failed .status-label {
            color: #dc3545;
        }

        .status-step.failed .status-date,
        .status-step.failed .status-time {
            color: #6c757d;
        }

        .status-step.failed-link:not(:last-child):before {
            background: #dc3545;
        }

        .delivery-failed-alert {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-left: 4px solid #dc3545;
            color: #991b1b;
            border-radius: 12px;
            padding: 14px 16px;
            font-size: 14px;
        }

        .delivery-failed-alert i {
            font-size: 1.2rem;
            color: #dc3545;
        }

        @media (max-width: 767.98px) {
            .delivery-failed-alert {
                padding: 0.85rem;
                font-size: 0.82rem;
                border-radius: 14px;
            }
        }
    </style>

    @php
        $statusColors = [
            'pending' => 'warning',
            'confirmed' => 'info',
            'processing' => 'primary',
            'ready' => 'success',
            'out_for_delivery' => 'secondary',
            'delivered' => 'dark',
            'cancelled' => 'danger',
        ];
        $statusIcons = [
            'pending' => 'bi-clock-history',
            'confirmed' => 'bi-check-circle',
            'processing' => 'bi-box-seam',
            'ready' => 'bi-check-circle-fill',
            'out_for_delivery' => 'bi-truck',
            'delivered' => 'bi-flag-fill',
            'cancelled' => 'bi-x-circle',
        ];
    @endphp

    <script>
        function showImagePreview(imageUrl, title) {
            const modal = document.getElementById('imagePreviewModal');
            const previewImage = document.getElementById('previewImage');
            const previewTitle = document.getElementById('previewTitle');
            const downloadLink = document.getElementById('downloadLink');

            previewImage.src = imageUrl;
            previewTitle.textContent = title;
            downloadLink.href = imageUrl;
            modal.style.display = 'flex';
        }

        function closeImagePreview() {
            document.getElementById('imagePreviewModal').style.display = 'none';
        }

        document.getElementById('imagePreviewModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImagePreview();
            }
        });
    </script>
@endsection
