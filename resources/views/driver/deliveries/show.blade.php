<style>
    /* Modern Minimalist Styles */
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
        margin-bottom: 0;
    }

    .product-flavor {
        font-size: 0.7rem;
        color: #64748b;
    }

    /* Customer Info */
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

    .table-responsive::-webkit-scrollbar {
        display: none;
    }

    .table-responsive {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    /* Badge Styles - From Online Orders */
    .badge-pending {
        background: #fef3c7;
        color: #d97706;
    }

    .badge-confirmed {
        background: #dbeafe;
        color: #2563eb;
    }

    .badge-packing {
        background: #e0e7ff;
        color: #4f46e5;
    }

    .badge-ready {
        background: #d1fae5;
        color: #059669;
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

    /* ADDED: Blue Lalamove Pending */
    .badge-lalamove_pending {
        background: #dbeafe;
        color: #2563eb;
    }

    .badge {
        padding: 0.35rem 0.65rem;
        border-radius: 30px;
        font-weight: 500;
        font-size: 0.7rem;
    }

    /* Form Elements */
    .form-control,
    .form-select {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 0.5rem 0.75rem;
        font-size: 0.8rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
        outline: none;
    }

    /* Buttons */
    .btn-manage {
        background: #1a1a2e;
        border: none;
        border-radius: 30px;
        padding: 0.35rem 1rem;
        font-size: 0.75rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-manage:hover {
        background: #16213e;
        transform: translateY(-1px);
    }

    .btn-update-status {
        background: #3b82f6;
        color: white;
        border: none;
        border-radius: 30px;
        padding: 0.6rem 1rem;
        font-size: 0.8rem;
        font-weight: 500;
        width: 100%;
        transition: all 0.3s ease;
    }

    .btn-update-status:hover {
        background: #2563eb;
        transform: translateY(-1px);
    }

    .btn-save-lalamove {
        background: #10b981;
        color: white;
        border: none;
        border-radius: 30px;
        padding: 0.6rem 1rem;
        font-size: 0.8rem;
        font-weight: 500;
        width: 100%;
        transition: all 0.3s ease;
    }

    .btn-save-lalamove:hover {
        background: #059669;
        transform: translateY(-1px);
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

    /* Image Preview Modal */
    .image-preview-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        z-index: 10000;
        justify-content: center;
        align-items: center;
    }

    .image-preview-content {
        background: white;
        border-radius: 16px;
        width: 90%;
        max-width: 600px;
        overflow: hidden;
    }

    .image-preview-header {
        padding: 1rem 1.25rem;
        background: white;
        border-bottom: 1px solid #eef2f6;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .image-preview-body {
        padding: 1.5rem;
        text-align: center;
    }

    .image-preview-body img {
        max-width: 100%;
        max-height: 400px;
        border-radius: 12px;
    }

    .image-preview-footer {
        padding: 1rem 1.25rem;
        background: #f8f9fa;
        border-top: 1px solid #eef2f6;
        text-align: right;
    }

    @media (max-width: 768px) {

        .order-table th,
        .order-table td {
            padding: 0.5rem;
        }

        .order-table img {
            width: 40px !important;
            height: 40px !important;
        }
    }

    /* Delivery Status Badge Overrides */
    .badge-secondary {
        background: #6c757d;
        color: white;
    }

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

    .badge-danger {
        background: #dc3545;
        color: white;
    }
</style>

<div class="modal-body-custom">
    <div style="padding: 1.5rem;">
        <!-- Header -->
        <div class="modal-header-custom" style="padding: 0 0 1rem 0;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="order-number"><i class="bi bi-truck text-primary me-2"></i> Delivery</h5>
                    <p class="order-date">Delivery for Order #{{ $delivery->order->order_number ?? 'N/A' }}</p>
                </div>
                <button type="button" class="btn-close" onclick="closeModal()"></button>
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
                                            <th>Image</th>
                                            <th>Product</th>
                                            <th class="text-center" style="width: 60px">Qty</th>
                                            <th class="text-end" style="width: 90px">Price</th>
                                            <th class="text-end" style="width: 90px">Subtotal</th>
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
                                        class="totals-value text-success">₱{{ number_format($delivery->order->total_amount, 2) }}</span>
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
                                // Determine the correct badge class based on delivery status
                                $statusBadgeClass = match ($delivery->status) {
                                    'pending' => 'badge-secondary',
                                    'assigned' => 'badge-info',
                                    'picked_up' => 'badge-primary',
                                    'in_transit' => 'badge-warning',
                                    'delivered' => 'badge-success',
                                    'failed' => 'badge-danger',
                                    default => 'badge-secondary',
                                };
                                $displayDeliveryStatus = ucfirst(str_replace('_', ' ', $delivery->status));
                            @endphp
                            <span class="badge {{ $statusBadgeClass }}">
                                {{ $displayDeliveryStatus }}
                            </span>
                        </p>

                        @if ($delivery->picked_up_at)
                            <p class="info-label">Picked Up</p>
                            <p class="info-value">{{ $delivery->picked_up_at->format('M d, Y h:i A') }}</p>
                        @endif
                        @if ($delivery->delivered_at)
                            <p class="info-label">Delivered</p>
                            <p class="info-value">{{ $delivery->delivered_at->format('M d, Y h:i A') }}</p>
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

            <!-- RIGHT COLUMN -->
            <div class="col-md-5">

                @php
                    // Lalamove Eligibility Check
                    $cityLower = strtolower(trim($delivery->order->city ?? ''));
                    $isCalambaCity = $cityLower === 'calamba city' || $cityLower === 'calamba';
                    $isLalamoveEligible = !$isCalambaCity;

                    // Check if tracking number is already saved
                    $hasTracking = $delivery && !empty($delivery->tracking_number);
                @endphp

                {{-- LALAMOVE TRACKING CARD --}}
                @if (
                    $isLalamoveEligible &&
                        ($delivery->order->order_status === 'out_for_delivery' ||
                            $delivery->order->order_status === 'lalamove_pending' ||
                            $delivery->order->order_status === 'ready'))
                    <div class="info-card" style="border: 1px solid #0d6efd;">
                        <div class="card-header-custom bg-primary bg-opacity-10">
                            <h6 class="text-primary"><i class="bi bi-truck"></i> Lalamove Tracking</h6>
                        </div>
                        <div class="card-body p-3">
                            <form action="{{ url('/driver/online-orders/update-lalamove/' . $delivery->order->id) }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="mb-3">
                                    <p class="info-label">Lalamove Tracking Link *</p>
                                    <input type="url" name="tracking_url" class="form-control"
                                        placeholder="Paste Lalamove Link here..."
                                        value="{{ $delivery->tracking_number ?? '' }}" required>
                                </div>

                                {{-- ✅ ADDED: Lalamove Driver Name Input --}}
                                <div class="mb-3">
                                    <p class="info-label">Lalamove Driver Name</p>
                                    <input type="text" name="lalamove_driver_name" class="form-control"
                                        placeholder="Enter Lalamove driver name..."
                                        value="{{ $delivery->notes ?? '' }}">
                                </div>

                                <div class="d-flex align-items-center gap-2">
                                    <button type="submit" class="btn-save-lalamove">
                                        <i class="bi bi-check-circle"></i>
                                        {{ $hasTracking ? 'Update Link' : 'Save' }}
                                    </button>

                                    @if ($hasTracking)
                                        <a href="{{ $delivery->tracking_number }}" target="_blank"
                                            class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                        @if ($delivery->delivery_proof)
                                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                                onclick="window.open('{{ Storage::url($delivery->delivery_proof) }}', '_blank')">
                                                <i class="bi bi-image"></i> Proof
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                @endif

                @if ($delivery->status == 'delivered')
                    <!-- Completed Delivery View -->
                    <div class="info-card">
                        <div class="card-header-custom">
                            <h6><i class="bi bi-check-circle-fill" style="color: #10b981;"></i> Delivery Completed
                            </h6>
                        </div>
                        <div class="card-body p-3">
                            @if ($delivery->delivery_proof || $delivery->payment_proof)
                                <div class="row g-2">
                                    @if ($delivery->delivery_proof)
                                        <div class="col-md-6">
                                            <p class="info-label mb-2">Delivery Proof</p>
                                            <img src="{{ Storage::url($delivery->delivery_proof) }}"
                                                class="proof-image"
                                                onclick="showImagePreview('{{ Storage::url($delivery->delivery_proof) }}', 'Delivery Proof')">
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
                                            <img src="{{ Storage::url($delivery->payment_proof) }}"
                                                class="proof-image"
                                                onclick="showImagePreview('{{ Storage::url($delivery->payment_proof) }}', 'Payment Proof')">
                                            <div class="mt-2 text-center">
                                                <a href="{{ Storage::url($delivery->payment_proof) }}" download
                                                    class="btn btn-sm btn-outline-success rounded-pill">
                                                    <i class="bi bi-download"></i> Download
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="bi bi-image fs-1 text-muted"></i>
                                    <p class="text-muted small mt-2">No proof images available</p>
                                </div>
                            @endif

                            @if ($delivery->delivered_at)
                                <div class="alert-custom alert-success-custom text-center mt-3">
                                    <i class="bi bi-check-circle-fill me-1"></i> Delivered on
                                    {{ $delivery->delivered_at->format('M d, Y h:i A') }}
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <!-- Active Delivery - Update Status Form -->
                    <div class="info-card">
                        <div class="card-header-custom">
                            <h6><i class="bi bi-arrow-repeat"></i> Update Status</h6>
                        </div>
                        <div class="card-body p-3">
                            <div id="errorAlert" class="alert-custom alert-danger-custom" style="display: none;">
                            </div>
                            <div id="successAlert" class="alert-custom alert-success-custom" style="display: none;">
                            </div>

                            <form id="updateStatusForm" action="{{ route('driver.delivery.update', $delivery) }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <p class="info-label">Status</p>
                                    <select name="status" id="statusSelect" class="form-select" required>
                                        <option value="picked_up"
                                            {{ $delivery->status == 'picked_up' ? 'selected' : '' }}>Picked Up</option>
                                        <option value="in_transit"
                                            {{ $delivery->status == 'in_transit' ? 'selected' : '' }}>In Transit
                                        </option>
                                        <option value="delivered"
                                            {{ $delivery->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                        <option value="failed" {{ $delivery->status == 'failed' ? 'selected' : '' }}>
                                            Failed Delivery</option>
                                    </select>
                                </div>

                                <div class="mb-3" id="deliveryProofField">
                                    <p class="info-label">Delivery Proof Photo <span class="text-danger"
                                            id="deliveryProofRequired">*</span></p>
                                    <input type="file" name="delivery_proof" id="deliveryProof"
                                        class="form-control" accept="image/*">
                                    <small class="text-muted" style="font-size: 0.75rem;">Required when marking as
                                        delivered</small>
                                    <div id="deliveryProofPreview" class="mt-2" style="display: none;">
                                        <img id="deliveryProofImg" src="#"
                                            style="max-width: 100%; max-height: 80px; border-radius: 8px;">
                                    </div>
                                </div>

                                <div class="mb-3" id="paymentProofField">
                                    <p class="info-label">Payment Proof Photo <span class="text-danger"
                                            id="paymentProofRequired">*</span></p>
                                    <input type="file" name="payment_proof" id="paymentProof"
                                        class="form-control" accept="image/*">
                                    <small class="text-muted" style="font-size: 0.75rem;">Required when marking as
                                        delivered</small>
                                    <div id="paymentProofPreview" class="mt-2" style="display: none;">
                                        <img id="paymentProofImg" src="#"
                                            style="max-width: 100%; max-height: 80px; border-radius: 8px;">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <p class="info-label">Notes</p>
                                    <textarea name="notes" class="form-control" rows="2" placeholder="Optional delivery notes...">{{ $delivery->driver_notes }}</textarea>
                                </div>

                                <button type="submit" class="btn-update-status" id="submitBtn">
                                    <i class="bi bi-check-circle me-2"></i> Update Status
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div id="imagePreviewModal" class="image-preview-modal">
    <div class="image-preview-content">
        <div class="image-preview-header">
            <h6 class="mb-0" id="previewTitle">Image Preview</h6>
            <button type="button" class="btn-close" onclick="closeImagePreview()"></button>
        </div>
        <div class="image-preview-body">
            <img id="previewImage" src="">
        </div>
        <div class="image-preview-footer">
            <button type="button" class="btn btn-sm btn-secondary me-2" onclick="closeImagePreview()">Close</button>
            <a id="downloadLink" href="#" download class="btn btn-sm btn-primary">Download</a>
        </div>
    </div>
</div>

<script>
    const statusSelect = document.getElementById('statusSelect');
    const deliveryProofInput = document.getElementById('deliveryProof');
    const paymentProofInput = document.getElementById('paymentProof');
    const deliveryProofPreview = document.getElementById('deliveryProofPreview');
    const paymentProofPreview = document.getElementById('paymentProofPreview');
    const deliveryProofImg = document.getElementById('deliveryProofImg');
    const paymentProofImg = document.getElementById('paymentProofImg');
    const submitBtn = document.getElementById('submitBtn');
    const updateForm = document.getElementById('updateStatusForm');
    const errorAlert = document.getElementById('errorAlert');
    const successAlert = document.getElementById('successAlert');

    if (deliveryProofInput) {
        deliveryProofInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (!file.type.startsWith('image/')) {
                    alert('Please select an image file');
                    this.value = '';
                    return;
                }
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size must be less than 2MB');
                    this.value = '';
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(event) {
                    deliveryProofImg.src = event.target.result;
                    deliveryProofPreview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                deliveryProofPreview.style.display = 'none';
                deliveryProofImg.src = '';
            }
        });
    }

    if (paymentProofInput) {
        paymentProofInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (!file.type.startsWith('image/')) {
                    alert('Please select an image file');
                    this.value = '';
                    return;
                }
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size must be less than 2MB');
                    this.value = '';
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(event) {
                    paymentProofImg.src = event.target.result;
                    paymentProofPreview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                paymentProofPreview.style.display = 'none';
                paymentProofImg.src = '';
            }
        });
    }

    function toggleRequired() {
        const isDelivered = statusSelect && statusSelect.value === 'delivered';

        if (deliveryProofInput) {
            deliveryProofInput.required = isDelivered;
            const deliveryProofRequired = document.getElementById('deliveryProofRequired');
            if (deliveryProofRequired) {
                deliveryProofRequired.style.display = isDelivered ? 'inline' : 'none';
            }
        }

        if (paymentProofInput) {
            paymentProofInput.required = isDelivered;
            const paymentProofRequired = document.getElementById('paymentProofRequired');
            if (paymentProofRequired) {
                paymentProofRequired.style.display = isDelivered ? 'inline' : 'none';
            }
        }
    }

    if (statusSelect) {
        statusSelect.addEventListener('change', toggleRequired);
        toggleRequired();
    }

    if (updateForm) {
        updateForm.addEventListener('submit', function(e) {
            const isDelivered = statusSelect && statusSelect.value === 'delivered';

            errorAlert.style.display = 'none';
            successAlert.style.display = 'none';

            if (isDelivered) {
                if (!deliveryProofInput.files.length && !deliveryProofInput.value) {
                    e.preventDefault();
                    errorAlert.textContent =
                        'Please upload a delivery proof photo. Required when marking as delivered.';
                    errorAlert.style.display = 'block';
                    deliveryProofInput.focus();
                    return false;
                }

                if (!paymentProofInput.files.length && !paymentProofInput.value) {
                    e.preventDefault();
                    errorAlert.textContent =
                        'Please upload a payment proof photo. Required when marking as delivered.';
                    errorAlert.style.display = 'block';
                    paymentProofInput.focus();
                    return false;
                }
            }

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2"></span> Processing...';
            }
        });
    }

    function showImagePreview(imageUrl, title) {
        document.getElementById('previewImage').src = imageUrl;
        document.getElementById('previewTitle').textContent = title;
        document.getElementById('downloadLink').href = imageUrl;
        document.getElementById('imagePreviewModal').style.display = 'flex';
    }

    function closeImagePreview() {
        document.getElementById('imagePreviewModal').style.display = 'none';
    }

    document.getElementById('imagePreviewModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeImagePreview();
        }
    });

    function closeModal() {
        const modalElement = document.getElementById('deliveryModal');
        if (modalElement) {
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) modal.hide();
        }
        const container = document.getElementById('modalContainer');
        if (container) container.innerHTML = '';
        const customModal = document.getElementById('customModal');
        if (customModal) customModal.style.display = 'none';
    }
</script>
