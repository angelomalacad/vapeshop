@extends('layouts.customer')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Order #{{ $order->order_number }}</h2>
            <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-secondary rounded-pill">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                                <!-- Order Items Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white fw-semibold">
                        <i class="bi bi-receipt me-2"></i> Order Items
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table mb-0">
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
                                                <div class="d-flex align-items-center">
                                                    @php
                                                        $inventory = \App\Models\BranchInventory::with('product')->find($item->inventory_id);
                                                        $imageUrl = null;
                                                        if ($inventory && $inventory->product && $inventory->product->image) {
                                                            $imageUrl = \Storage::url($inventory->product->image);
                                                        }
                                                    @endphp
                                                    
                                                    <div class="flex-shrink-0 me-3">
                                                        @if($imageUrl)
                                                            <img src="{{ $imageUrl }}" alt="{{ $item->product->name }}" 
                                                                 style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                                        @else
                                                            <div style="width: 60px; height: 60px; background: #f8f9fa; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #adb5bd;">
                                                                <i class="bi bi-image"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    
                                                    <div>
                                                        <div class="fw-semibold">{{ $item->product->name }}</div>
                                                        
                                                        {{-- ================================================ --}}
                                                        {{-- ADDED: Variant below the name (clean alignment) --}}
                                                        {{-- ================================================ --}}
                                                        @if ($item->flavor)
                                                            <div class="small text-muted">Variant: {{ $item->flavor->name }}</div>
                                                        @endif
                                                        {{-- ================================================ --}}
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-end">₱{{ number_format($item->price, 2) }}</td>
                                            <td class="text-end">₱{{ number_format($item->subtotal, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Subtotal:</td>
                                        <td class="text-end fw-bold">₱{{ number_format($order->subtotal, 2) }}</td>
                                    </tr>
                                    {{-- Tax Row Removed as requested --}}
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold fs-5">Total:</td>
                                        <td class="text-end fw-bold fs-5 text-danger">
                                            ₱{{ number_format($order->total_amount, 2) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Status Timeline Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white fw-semibold">
                        <i class="bi bi-clock-history me-2"></i> Order Status Timeline
                    </div>
                    <div class="card-body">
                        <!-- Main Order Status Timeline -->
                        <div class="status-timeline">
                            <div class="status-steps">
                                <!-- Pending -->
                                <div
                                    class="status-step {{ $order->order_status == 'pending' ? 'active' : ($order->order_status != 'pending' && $order->order_status != 'cancelled' ? 'completed' : '') }}">
                                    <div class="status-icon">
                                        <i class="bi bi-clock-history"></i>
                                    </div>
                                    <div class="status-label">Pending</div>
                                    <div class="status-date">{{ $order->created_at->format('M d, Y') }}</div>
                                    <div class="status-time">{{ $order->created_at->format('h:i A') }}</div>
                                </div>

                                <!-- Confirmed -->
                                <div
                                    class="status-step {{ $order->order_status == 'confirmed' ? 'active' : (in_array($order->order_status, ['processing', 'ready', 'out_for_delivery', 'delivered']) ? 'completed' : '') }}">
                                    <div class="status-icon">
                                        <i class="bi bi-check-circle"></i>
                                    </div>
                                    <div class="status-label">Confirmed</div>
                                    @if ($statusTimestamps['confirmed'])
                                        <div class="status-date">{{ $statusTimestamps['confirmed']->format('M d, Y') }}
                                        </div>
                                        <div class="status-time">{{ $statusTimestamps['confirmed']->format('h:i A') }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Packing -->
                                <div
                                    class="status-step {{ $order->order_status == 'processing' ? 'active' : (in_array($order->order_status, ['ready', 'out_for_delivery', 'delivered']) ? 'completed' : '') }}">
                                    <div class="status-icon">
                                        <i class="bi bi-box-seam"></i>
                                    </div>
                                    <div class="status-label">Processing</div>
                                    @if ($statusTimestamps['packing'])
                                        <div class="status-date">{{ $statusTimestamps['packing']->format('M d, Y') }}</div>
                                        <div class="status-time">{{ $statusTimestamps['packing']->format('h:i A') }}</div>
                                    @endif
                                </div>

                                <!-- Ready -->
                                <div
                                    class="status-step {{ $order->order_status == 'ready' ? 'active' : (in_array($order->order_status, ['out_for_delivery', 'delivered']) ? 'completed' : '') }}">
                                    <div class="status-icon">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </div>
                                    <div class="status-label">Ready</div>
                                    @if ($statusTimestamps['ready'])
                                        <div class="status-date">{{ $statusTimestamps['ready']->format('M d, Y') }}</div>
                                        <div class="status-time">{{ $statusTimestamps['ready']->format('h:i A') }}</div>
                                    @endif
                                </div>

                                <!-- Out for Delivery -->
                                <div
                                    class="status-step {{ $order->order_status == 'out_for_delivery' ? 'active' : ($order->order_status == 'delivered' ? 'completed' : '') }}">
                                    <div class="status-icon">
                                        <i class="bi bi-truck"></i>
                                    </div>
                                    <div class="status-label">Out for Delivery</div>
                                    @if ($statusTimestamps['out_for_delivery'])
                                        <div class="status-date">
                                            {{ $statusTimestamps['out_for_delivery']->format('M d, Y') }}</div>
                                        <div class="status-time">
                                            {{ $statusTimestamps['out_for_delivery']->format('h:i A') }}</div>
                                    @endif
                                </div>

                                <!-- Delivered -->
                                <div class="status-step {{ $order->order_status == 'delivered' ? 'active' : '' }}">
                                    <div class="status-icon">
                                        <i class="bi bi-flag-fill"></i>
                                    </div>
                                    <div class="status-label">Delivered</div>
                                    @if ($statusTimestamps['delivered'])
                                        <div class="status-date">{{ $statusTimestamps['delivered']->format('M d, Y') }}
                                        </div>
                                        <div class="status-time">{{ $statusTimestamps['delivered']->format('h:i A') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Delivery Logs -->
                        @if (
                            $order->delivery_type == 'delivery' &&
                                $order->delivery &&
                                in_array($order->order_status, ['out_for_delivery', 'delivered']))
                            <div class="delivery-logs mt-4">
                                <h6 class="mb-3"><i class="bi bi-truck me-2"></i> Delivery Logs</h6>
                                <div class="delivery-timeline">
                                    <!-- Assigned to Driver -->
                                    @if ($order->delivery->assigned_at)
                                        <div class="delivery-log-item">
                                            <div class="delivery-log-icon assigned">
                                                <i class="bi bi-person-check"></i>
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

                                    <!-- Picked Up -->
                                    @if ($order->delivery->picked_up_at)
                                        <div class="delivery-log-item">
                                            <div class="delivery-log-icon picked">
                                                <i class="bi bi-box-seam"></i>
                                            </div>
                                            <div class="delivery-log-content">
                                                <div class="delivery-log-title">Picked Up</div>
                                                <div class="delivery-log-date">
                                                    {{ $order->delivery->picked_up_at->format('F d, Y') }}</div>
                                                <div class="delivery-log-time">
                                                    {{ $order->delivery->picked_up_at->format('h:i A') }}</div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- In Transit -->
                                    @if ($statusTimestamps['in_transit'])
                                        <div class="delivery-log-item">
                                            <div class="delivery-log-icon transit">
                                                <i class="bi bi-truck"></i>
                                            </div>
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

                                    <!-- Delivered -->
                                    @if ($order->delivery->delivered_at)
                                        <div class="delivery-log-item">
                                            <div class="delivery-log-icon delivered">
                                                <i class="bi bi-check-circle-fill"></i>
                                            </div>
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
                                @if ($order->delivery->driver)
                                    <div class="driver-info mt-3 p-3 bg-light rounded">
                                        <div class="d-flex align-items-center">
                                            <div class="driver-avatar me-3">
                                                <i class="bi bi-person-circle fs-1 text-primary"></i>
                                            </div>
                                            <div>
                                                <strong>Driver: {{ $order->delivery->driver->name }}</strong><br>
                                                <small class="text-muted">Contact:
                                                    {{ $order->delivery->driver->phone ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Delivery Details Card with Proof Images -->
                                @if ($order->delivery_type == 'delivery' && $order->delivery)
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white fw-semibold">
                            <i class="bi bi-geo-alt me-2"></i> Delivery Address
                        </div>
                        <div class="card-body">
                            <p class="mb-1">{{ $order->delivery_address }}</p>
                            <p class="mb-1">
                                {{-- If barangay is 'Other', show other_barangay instead. Else show normal barangay. --}}
                                {{ ($order->barangay === 'Other' && $order->other_barangay) ? $order->other_barangay : $order->barangay }}, 
                                {{ $order->city }}
                            </p>
                            @if ($order->landmark)
                                <p class="mb-0 text-muted"><small>Landmark: {{ $order->landmark }}</small></p>
                            @endif
                            <hr>
                            <p class="mb-0"><strong>Recipient:</strong> {{ $order->customer_name }}</p>
                            <p class="mb-0"><strong>Contact:</strong> {{ $order->customer_phone }}</p>

                            <!-- Proof Images with Thumbnails -->
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
                                            <div class="mt-2">
                                                <a href="{{ Storage::url($order->delivery->delivery_proof) }}"
                                                    target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-box-arrow-up-right"></i> View Full
                                                </a>
                                            </div>
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
                                            <div class="mt-2">
                                                <a href="{{ Storage::url($order->delivery->payment_proof) }}"
                                                    target="_blank" class="btn btn-sm btn-outline-success">
                                                    <i class="bi bi-box-arrow-up-right"></i> View Full
                                                </a>
                                            </div>
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
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-semibold">
                        <i class="bi bi-info-circle me-2"></i> Order Information
                    </div>
                    <div class="card-body">
                        <p><strong>Order Number:</strong></p>
                        <p><code>{{ $order->order_number }}</code></p>

                        <p><strong>Date placed:</strong></p>
                        <p>{{ $order->created_at->format('F d, Y h:i A') }}</p>

                        <p><strong>Branch:</strong></p>
                        <p>{{ $order->branch->name }}</p>

                        <p><strong>Payment Method:</strong></p>
                        <p>{{ strtoupper($order->payment_method) }}</p>

                        <p><strong>Tracking Number:</strong></p>
                        <p><code>{{ $order->delivery->tracking_number ?? 'N/A' }}</code></p>

                        @if ($order->notes)
                            <hr>
                            <p><strong>Your Notes:</strong></p>
                            <p class="text-muted">{{ $order->notes }}</p>
                        @endif
                    </div>
                </div>

                {{-- ================================================ --}}
                {{-- ADDED: Lalamove Tracking Link Card (Driver will add later) --}}
                {{-- ================================================ --}}
                <div class="card shadow-sm border-0 mt-4" id="lalamoveTrackingCard">
                    <div class="card-header bg-white fw-semibold">
                        <i class="bi bi-truck me-2 text-primary"></i> Lalamove Tracking
                    </div>
                    <div class="card-body text-center">
                        @if($order->delivery && $order->delivery->tracking_number && filter_var($order->delivery->tracking_number, FILTER_VALIDATE_URL))
                            <i class="bi bi-box-arrow-up-right display-4 text-success mb-3 d-block"></i>
                            <p class="mb-2">Your package is on its way!</p>
                            <a href="{{ $order->delivery->tracking_number }}" target="_blank" class="btn btn-primary rounded-pill w-100">
                                <i class="bi bi-eye me-1"></i> Track Package
                            </a>
                        @else
                            <i class="bi bi-clock-history display-4 text-secondary mb-3 d-block"></i>
                            <p class="mb-0 text-muted">Tracking link will be available once your order is out for delivery.</p>
                        @endif
                    </div>
                </div>
                {{-- ================================================ --}}

                <!-- Need Help Card -->
                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header bg-white fw-semibold">
                        <i class="bi bi-question-circle me-2"></i> Need Help?
                    </div>
                    <div class="card-body text-center">
                        <i class="bi bi-headset display-4 text-primary mb-3 d-block"></i>
                        <p>Have questions about your order?</p>
                        <button class="btn btn-outline-primary rounded-pill" onclick="openGmail()">
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
                            <img id="previewImage" src="" style="max-width: 100%; max-height: 400px; border-radius: 5px;">
                        </div>
                        <div style="padding: 10px 15px; background: #f8f9fa; border-top: 1px solid #ddd; text-align: right;">
                            <button type="button" class="btn btn-sm btn-secondary" onclick="closeImagePreview()">Close</button>
                            <a id="downloadLink" href="#" download class="btn btn-sm btn-primary">Download</a>
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

                    @media (max-width: 768px) {
                        .status-steps {
                            flex-direction: column;
                            gap: 20px;
                        }

                        .status-step:not(:last-child):before {
                            display: none;
                        }

                        .status-step {
                            display: flex;
                            align-items: center;
                            text-align: left;
                            gap: 15px;
                        }

                        .status-icon {
                            margin: 0;
                        }

                        .status-label,
                        .status-date,
                        .status-time {
                            text-align: left;
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